@extends('admin.layouts.app')

@section('title', 'Process Password Reset')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Process Password Reset Request</h1>
        <a href="{{ route('admin.password-resets.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Requests
        </a>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">User Information</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>Name</th>
                                    <td>{{ $passwordReset->user->name }}</td>
                                </tr>
                                @if($passwordReset->user->username)
                                <tr>
                                    <th>Username</th>
                                    <td>{{ $passwordReset->user->username }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $passwordReset->user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Role</th>
                                    <td>{{ $passwordReset->user->role }}</td>
                                </tr>
                                <tr>
                                    <th>Request Date</th>
                                    <td>{{ $passwordReset->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Process Request</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.password-resets.update', $passwordReset) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label d-block">Action</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="action" id="approve" value="approve" checked>
                                <label class="form-check-label" for="approve">Approve and Set New Password</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="action" id="reject" value="reject">
                                <label class="form-check-label" for="reject">Reject Request</label>
                            </div>
                        </div>
                        
                        <div id="password-section">
                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password</label>
                                <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="new_password" name="new_password" minlength="8">
                                @error('new_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation">
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const approveRadio = document.getElementById('approve');
            const rejectRadio = document.getElementById('reject');
            const passwordSection = document.getElementById('password-section');
            
            function togglePasswordSection() {
                passwordSection.style.display = approveRadio.checked ? 'block' : 'none';
            }
            
            approveRadio.addEventListener('change', togglePasswordSection);
            rejectRadio.addEventListener('change', togglePasswordSection);
            
            // Initial state
            togglePasswordSection();
        });
    </script>
@endsection