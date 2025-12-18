<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\DataTables;

class DashboardLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.activity-logs.index');
    }

   /**
     * Return DataTable JSON response for activity logs.
     *
     * This table is read-only:
     * - No actions
     * - No mutations
     */
        /**
     * Return DataTable JSON response for activity logs.
     */
    public function datatable(Request $request)
    {
        $query = Activity::with(['causer', 'subject'])->latest();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('log_name', fn ($activity) => $activity->log_name ?? '-')
            ->addColumn('description', fn ($activity) => $activity->description)
            ->addColumn('subject', function ($activity) {
                if (!$activity->subject) {
                    return '-';
                }
                
                $subjectType = class_basename($activity->subject_type);
                $subjectId = $activity->subject_id;
                
                // محاولة للحصول على معلومات إضافية للموضوع
                try {
                    $subject = $activity->subject;
                    if (method_exists($subject, 'getActivityLogSubject')) {
                        return $subject->getActivityLogSubject();
                    }
                    
                    if (isset($subject->name)) {
                        return $subjectType . ': ' . $subject->name . ' (#' . $subjectId . ')';
                    }
                    
                    if (isset($subject->title)) {
                        return $subjectType . ': ' . $subject->title . ' (#' . $subjectId . ')';
                    }
                    
                    return $subjectType . ' #' . $subjectId;
                } catch (\Exception $e) {
                    return $subjectType . ' #' . $subjectId;
                }
            })
            ->addColumn('causer', function ($activity) {
                if (!$activity->causer) {
                    return 'System';
                }
                
                $causer = $activity->causer;
                
                if (isset($causer->email)) {
                    return $causer->email . ' (' . ($causer->name ?? '#' . $causer->id) . ')';
                }
                
                if (isset($causer->name)) {
                    return $causer->name . ' (#' . $causer->id . ')';
                }
                
                return 'User #' . $causer->id;
            })
            ->addColumn('created_at', fn ($activity) => $activity->created_at->format('Y-m-d H:i:s'))
            ->rawColumns(['subject', 'causer'])
            ->make(true);
    }




}
