@extends('dashboard.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Send Email to {{ $user->name }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.dashboard.users.send-email.send', $user) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject *</label>
                            <input type="text" 
                                   name="subject" 
                                   id="subject"
                                   class="form-control @error('subject') is-invalid @enderror"
                                   value="{{ old('subject') }}"
                                   required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Message *</label>
                            <textarea name="message" 
                                      id="message" 
                                      rows="6"
                                      class="form-control @error('message') is-invalid @enderror"
                                      required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="action_url" class="form-label">Action URL (Optional)</label>
                                <input type="url" 
                                       name="action_url" 
                                       id="action_url"
                                       class="form-control"
                                       value="{{ old('action_url') }}"
                                       placeholder="https://example.com">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="action_text" class="form-label">Action Button Text</label>
                                <input type="text" 
                                       name="action_text" 
                                       id="action_text"
                                       class="form-control"
                                       value="{{ old('action_text', 'View Details') }}"
                                       placeholder="View Details">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.dashboard.users.show', $user) }}" 
                               class="btn btn-outline-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-1"></i> Send Email
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .card {
        border: none;
        border-radius: 10px;
    }
    .card-header {
        border-radius: 10px 10px 0 0 !important;
    }
    textarea {
        resize: vertical;
        min-height: 150px;
    }
</style>
@endsection