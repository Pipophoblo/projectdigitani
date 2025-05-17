@extends('layouts.admin')

@section('title', 'View Article')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">View Article</h1>
        <div>
            <a href="{{ route('admin.articles.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Articles
            </a>
            <a href="{{ route('admin.articles.edit', $article->id) }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Edit Article
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Article Content</h6>
                    <span class="badge badge-{{ 
                        $article->status === 'published' ? 'success' : 
                        ($article->status === 'pending' ? 'warning' : 
                        ($article->status === 'rejected' ? 'danger' : 'secondary')) 
                    }}" >
                        {{ ucfirst($article->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <h1 class="h4 mb-3">{{ $article->title }}</h1>
                    
                    @if($article->image)
                        <div class="mb-4">
                            <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" style="max-width: 100%; border-radius: 8px;">
                        </div>
                    @endif
                    
                    @if($article->summary)
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <strong>Summary:</strong> {{ $article->summary }}
                            </div>
                        </div>
                    @endif
                    
                    <div class="mb-4">
                        {!! nl2br(e($article->content)) !!}
                    </div>
                    
                    @if($article->keywords)
                        <div class="mt-4">
                            <strong>Keywords:</strong>
                            @foreach(explode(',', $article->keywords) as $keyword)
                                <span class="badge badge-info">{{ trim($keyword) }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Article Details</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Author:</strong> {{ $article->user->name }}
                    </div>
                    
                    <div class="mb-3">
                        <strong>Status:</strong> {{ ucfirst($article->status) }}
                    </div>
                    
                    <div class="mb-3">
                        <strong>Created:</strong> {{ $article->created_at->format('M d, Y H:i') }}
                    </div>
                    
                    @if($article->status === 'published')
                        <div class="mb-3">
                            <strong>Published:</strong> {{ $article->published_at->format('M d, Y H:i') }}
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <strong>Last Updated:</strong> {{ $article->updated_at->format('M d, Y H:i') }}
                    </div>
                    
                    <div class="mb-3">
                        <strong>Views:</strong> {{ $article->view_count }}
                    </div>
                    
                    <hr>
                    
                    @if($article->status === 'pending')
                        <form action="{{ route('admin.articles.updateStatus', $article->id) }}" method="POST" class="mb-2">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="published">
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fas fa-check mr-1"></i> Approve & Publish
                            </button>
                        </form>
                        <form action="{{ route('admin.articles.updateStatus', $article->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="rejected">
                            <button type="submit" class="btn btn-danger btn-block">
                                <i class="fas fa-times mr-1"></i> Reject
                            </button>
                        </form>
                    @else
                        <a href="{{ route('admin.articles.edit', $article->slug) }}" class="btn btn-primary btn-block">
                            <i class="fas fa-edit mr-1"></i> Edit Article
                        </a>
                    @endif
                    
                    <a href="{{ route('articles.show', $article->slug) }}" target="_blank" class="btn btn-info btn-block mt-2">
                        <i class="fas fa-external-link-alt mr-1"></i> View Public Page
                    </a>
                    
                    <form action="{{ route('admin.articles.destroy', $article->id) }}" method="POST" class="mt-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block" onclick="return confirm('Are you sure you want to delete this article?')">
                            <i class="fas fa-trash mr-1"></i> Delete Article
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection