<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $verfied_user = User::verified()->count();
        $all_product = Product::all()->count();
        $user_last_month = User::createdLastMonth()->count();

        return view('dashboard.index', compact(['verfied_user', 'all_product','user_last_month']));
    }

    public function productChart(): JsonResponse
    {
       
            $data = Product::countLastWeekPerDay()->get();

            $days = collect(range(0, 6))->map(function ($i) {
                return Carbon::now()->subDays($i)->format('Y-m-d');
            })->reverse();

            $result = $days->map(function ($day) use ($data) {
                $record = $data->firstWhere('date', $day);
                return [
                    'date' => $day,
                    'count' => $record->total ?? 0,
                ];
            });
            
            return response()->json($result->values()->toArray());

    }
}
