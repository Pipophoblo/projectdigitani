@extends('admin.layouts.app')

@section('title', 'Threads Management')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Threads Management</h1>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Category</th>
                            <th>Views</th>
                            <th>Resolved</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($threads as $thread)
                            <tr>
                                <td>{{ $thread->id }}</td>
                                <td>{{ Str::limit($thread->title, 30) }}</td>
                                <td>{{ $thread->user->name }}</td>
                                <td>{{ $thread->category->name }}</td>
                                <td>{{ $thread->view_count }}</td>
                                <td>
                                    @if($thread->is_resolved)
                                        <span class="badge bg-success">Yes</span>
                                    @else
                                        <span class="badge bg-warning">No</span>
                                    @endif
                                </td>
                                <td>{{ $thread->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.threads.show', $thread) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.threads.edit', $thread) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <form action="{{ route('admin.threads.destroy', $thread) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this thread and all its comments?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $threads->links() }}
        </div>
    </div>
@endsection