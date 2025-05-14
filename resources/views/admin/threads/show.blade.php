@extends('admin.layouts.app')

@section('title', 'Thread Details')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Thread Details</h1>
        <div>
            <a href="{{ route('admin.threads.edit', $thread) }}" class="btn btn-info">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.threads.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Threads
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Thread Information</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>ID:</strong> {{ $thread->id }}</p>
                    <p><strong>Title:</strong> {{ $thread->title }}</p>
                    <p><strong>Author:</strong> {{ $thread->user->name }}</p>
                    <p><strong>Category:</strong> {{ $thread->category->name }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Created:</strong> {{ $thread->created_at->format('Y-m-d H:i') }}</p>
                    <p><strong>Views:</strong> {{ $thread->view_count }}</p>
                    <p><strong>Resolved:</strong> {{ $thread->is_resolved ? 'Yes' : 'No' }}</p>
                </div>
            </div>
            
            <div class="mt-4">
                <h5>Content:</h5>
                <div class="border p-3 rounded bg-light">
                    {!! nl2br(e($thread->content)) !!}
                </div>
            </div>
        </div>
    </div>
    
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Comments ({{ $thread->comments->count() }})</h6>
        </div>
        <div class="card-body">
            @if($thread->comments->count() > 0)
                @foreach($thread->comments as $comment)
                    <div class="border-bottom mb-3 pb-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong>{{ $comment->user->name }}</strong> <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        <p class="mt-2">{!! nl2br(e($comment->content)) !!}</p>
                    </div>
                @endforeach
            @else
                <p class="text-muted">No comments yet.</p>
            @endif
        </div>
    </div>
@endsection