@extends('admin.layouts.app')

@section('title', 'Password Reset Requests')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Password Reset Requests</h1>
    </div>

    <div class="card shadow">
        <div class="card-body">
            @if($requests->isEmpty())
                <div class="alert alert-info">
                    No password reset requests found.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Requested At</th>
                                <th>Processed At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requests as $request)
                                <tr>
                                    <td>{{ $request->id }}</td>
                                    <td>{{ $request->user->name }}</td>
                                    <td>{{ $request->user->email }}</td>
                                    <td>
                                        @if($request->status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($request->status == 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @else
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td>{{ $request->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td>
                                        {{ $request->processed_at ? $request->processed_at->format('Y-m-d H:i:s') : 'N/A' }}
                                    </td>
                                    <td>
                                        @if($request->status == 'pending')
                                            <a href="{{ route('admin.password-resets.edit', $request) }}" class="btn btn-sm btn-primary">
                                                Process
                                            </a>
                                        @else
                                            <a href="{{ route('admin.users.show', $request->user) }}" class="btn btn-sm btn-info">
                                                View User
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $requests->links() }}
            @endif
        </div>
    </div>
@endsection