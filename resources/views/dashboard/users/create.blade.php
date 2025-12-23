@extends('dashboard.layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Create New User</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.dashboard.users.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name *</label>
                                <input type="text" 
                                       name="first_name" 
                                       id="first_name"
                                       class="form-control @error('first_name') is-invalid @enderror"
                                       value="{{ old('first_name') }}"
                                       required
                                       autofocus>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name *</label>
                                <input type="text" 
                                       name="last_name" 
                                       id="last_name"
                                       class="form-control @error('last_name') is-invalid @enderror"
                                       value="{{ old('last_name') }}"
                                       required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone_number" class="form-label">Phone Number *</label>
                                <input type="text" 
                                       name="phone_number" 
                                       id="phone_number"
                                       placeholder="ex: +966500234567"
                                       class="form-control @error('phone_number') is-invalid @enderror"
                                       value="{{ old('phone_number') }}"
                                       required>
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" 
                                       name="email" 
                                       id="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="country_id" class="form-label">Country</label>
                                <select name="country_id" 
                                        id="country_id"
                                        class="form-select @error('country_id') is-invalid @enderror">
                                    <option value="">Select Country</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                            {{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('country_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="city_id" class="form-label">City</label>
                                <select name="city_id" 
                                        id="city_id"
                                        class="form-select @error('city_id') is-invalid @enderror"
                                        {{ !old('country_id') ? 'disabled' : '' }}>
                                    <option value="">Select Country First</option>
                                    @if(old('country_id'))
                                        @foreach($cities as $city)
                                            <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                                {{ $city->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('city_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password *</label>
                                <input type="password" 
                                       name="password" 
                                       id="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password *</label>
                                <input type="password" 
                                       name="password_confirmation" 
                                       id="password_confirmation"
                                       class="form-control"
                                       required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select name="role" 
                                        id="role"
                                        class="form-select @error('role') is-invalid @enderror">
                                    <option value="">Select Role</option>
                                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="is_active"
                                               id="is_active"
                                               value="1"
                                               {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active User
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="email_verified"
                                               id="email_verified"
                                               value="1"
                                               {{ old('email_verified', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="email_verified">
                                            Email Verified
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.dashboard.users.index') }}" 
                               class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Back to Users
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Create User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#country_id').change(function() {
            var countryId = $(this).val();
            var citySelect = $('#city_id');
            
            // Clear and disable city select
            citySelect.html('<option value="">Loading cities...</option>');
            citySelect.prop('disabled', true);
            
            if (countryId) {
                // Direct URL without route helper
                var url = "{{ url('admin/dashboard/users/cities') }}/" + countryId;
                
                // AJAX request
                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        citySelect.html('<option value="">Select City</option>');
                        
                        $.each(data, function(key, city) {
                            citySelect.append('<option value="' + city.id + '">' + city.name + '</option>');
                        });
                        
                        citySelect.prop('disabled', false);
                    },
                    error: function(xhr, status, error) {
                        citySelect.html('<option value="">Error loading cities</option>');
                        console.error('Error fetching cities:', error);
                    }
                });
            } else {
                citySelect.html('<option value="">Select Country First</option>');
                citySelect.prop('disabled', true);
            }
        });
    });
</script>
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
    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
</style>
@endsection