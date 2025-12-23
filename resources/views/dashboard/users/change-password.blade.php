@extends('dashboard.layouts.app')

@section('title', 'Change Password')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-key me-2"></i>
                        Change Password for User: {{ $user->name }}
                    </h4>
                </div>
                <div class="card-body">
                    
                    <!-- Display success or error messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.dashboard.users.change-password.update', $user) }}" id="changePasswordForm">
                        @csrf
                        
                        <!-- If the user is changing their own password -->
                        @if(auth()->user()->id == $user->id)

                        @endif
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">
                                        <i class="fas fa-key me-1"></i>
                                        New Password
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password"
                                               placeholder="Enter new password">
                                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mt-2">
                                        <div class="progress" style="height: 5px;">
                                            <div class="progress-bar" id="password-strength-bar" role="progressbar" style="width: 0%;"></div>
                                        </div>
                                        <small id="password-strength-text" class="form-text"></small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">
                                        <i class="fas fa-key me-1"></i>
                                        Confirm New Password
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control @error('password_confirmation') is-invalid @enderror" 
                                               id="password_confirmation" 
                                               name="password_confirmation"
                                               placeholder="Confirm new password">
                                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password_confirmation">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @error('password_confirmation')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small id="password-match" class="form-text text-muted"></small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Password requirements -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Password Requirements
                                </h6>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled mb-0">
                                    <li id="req-length" class="text-danger">
                                        <i class="fas fa-times me-2"></i>
                                        At least 8 characters
                                    </li>
                                    <li id="req-letter" class="text-danger">
                                        <i class="fas fa-times me-2"></i>
                                        Contains uppercase and lowercase letters
                                    </li>
                                    <li id="req-number" class="text-danger">
                                        <i class="fas fa-times me-2"></i>
                                        Contains at least one number
                                    </li>
                                    <li id="req-symbol" class="text-danger">
                                        <i class="fas fa-times me-2"></i>
                                        Contains a special character (@#$%^&*...)
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.dashboard.users.show', $user) }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i>
                                        Back
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="fas fa-save me-1"></i>
                                        Save Changes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
    
    // Check password strength
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    const strengthBar = document.getElementById('password-strength-bar');
    const strengthText = document.getElementById('password-strength-text');
    const submitBtn = document.getElementById('submitBtn');
    
    // Password requirement elements
    const reqLength = document.getElementById('req-length');
    const reqLetter = document.getElementById('req-letter');
    const reqNumber = document.getElementById('req-number');
    const reqSymbol = document.getElementById('req-symbol');
    
    // Check password match
    function checkPasswordMatch() {
        const password = passwordInput.value;
        const confirmPassword = confirmInput.value;
        const matchText = document.getElementById('password-match');
        
        if (confirmPassword === '') {
            matchText.textContent = '';
            matchText.className = 'form-text text-muted';
            return;
        }
        
        if (password === confirmPassword) {
            matchText.innerHTML = '<i class="fas fa-check text-success me-1"></i> Passwords match';
            matchText.className = 'form-text text-success';
        } else {
            matchText.innerHTML = '<i class="fas fa-times text-danger me-1"></i> Passwords do not match';
            matchText.className = 'form-text text-danger';
        }
    }
    
    // Check password strength
    function checkPasswordStrength() {
        const password = passwordInput.value;
        let strength = 0;
        let color = 'bg-danger';
        let text = 'Very Weak';
        
        // Check requirements
        const hasLength = password.length >= 8;
        const hasLetter = /[a-z]/.test(password) && /[A-Z]/.test(password);
        const hasNumber = /\d/.test(password);
        const hasSymbol = /[!@#$%^&*(),.?":{}|<>]/.test(password);
        
        // Update requirement icons
        updateRequirement(reqLength, hasLength);
        updateRequirement(reqLetter, hasLetter);
        updateRequirement(reqNumber, hasNumber);
        updateRequirement(reqSymbol, hasSymbol);
        
        // Calculate strength
        if (hasLength) strength += 25;
        if (hasLetter) strength += 25;
        if (hasNumber) strength += 25;
        if (hasSymbol) strength += 25;
        
        // Determine color and text
        if (strength >= 75) {
            color = 'bg-success';
            text = 'Very Strong';
        } else if (strength >= 50) {
            color = 'bg-info';
            text = 'Good';
        } else if (strength >= 25) {
            color = 'bg-warning';
            text = 'Weak';
        }
        
        // Update progress bar and text
        strengthBar.style.width = strength + '%';
        strengthBar.className = `progress-bar ${color}`;
        strengthText.textContent = `Password Strength: ${text}`;
        strengthText.className = `form-text ${color === 'bg-success' ? 'text-success' : 
                                 color === 'bg-info' ? 'text-info' : 
                                 color === 'bg-warning' ? 'text-warning' : 'text-danger'}`;
    }
    
    function updateRequirement(element, isValid) {
        const icon = element.querySelector('i');
        if (isValid) {
            element.classList.remove('text-danger');
            element.classList.add('text-success');
            icon.classList.remove('fa-times');
            icon.classList.add('fa-check');
        } else {
            element.classList.remove('text-success');
            element.classList.add('text-danger');
            icon.classList.remove('fa-check');
            icon.classList.add('fa-times');
        }
    }
    
    // Add event listeners
    passwordInput.addEventListener('input', function() {
        checkPasswordStrength();
        checkPasswordMatch();
    });
    
    confirmInput.addEventListener('input', checkPasswordMatch);
    
    // Form validation before submission
    document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
        const password = passwordInput.value;
        const confirmPassword = confirmInput.value;
        
        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Passwords do not match. Please make sure the new password and confirmation match.');
            return;
        }
        
        // Add loading state to button
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Saving...';
        submitBtn.disabled = true;
    });
    
    // Initial check
    checkPasswordStrength();
});
</script>
@endpush

@push('styles')
<style>
.card {
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
}

.card-header {
    border-bottom: 2px solid #f0f0f0;
    background-color: #f8f9fa;
}

.progress {
    border-radius: 10px;
}

.toggle-password {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}

.list-unstyled li {
    margin-bottom: 5px;
}

.form-label {
    font-weight: 600;
}
</style>
@endpush