@extends('admin.layouts.app')

@section('title', 'Manage Articles')
<!-- jQuery (required for Bootstrap JavaScript components) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Articles</h1>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">All Articles</h6>
            <div class="dropdown">
                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Filter by Status
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item {{ !request('status') ? 'active' : '' }}" href="{{ route('admin.articles.index') }}">All</a>
                    <a class="dropdown-item {{ request('status') == 'draft' ? 'active' : '' }}" href="{{ route('admin.articles.index', ['status' => 'draft']) }}">Drafts</a>
                    <a class="dropdown-item {{ request('status') == 'pending' ? 'active' : '' }}" href="{{ route('admin.articles.index', ['status' => 'pending']) }}">Pending Review</a>
                    <a class="dropdown-item {{ request('status') == 'published' ? 'active' : '' }}" href="{{ route('admin.articles.index', ['status' => 'published']) }}">Published</a>
                    <a class="dropdown-item {{ request('status') == 'rejected' ? 'active' : '' }}" href="{{ route('admin.articles.index', ['status' => 'rejected']) }}">Rejected</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Views</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($articles as $article)
                        <tr>
                            <td>{{ $article->title }}</td>
                            <td>{{ $article->user->name }}</td>
                            <td>
                                <span class="badge badge-{{ 
                                    $article->status === 'published' ? 'success' : 
                                    ($article->status === 'pending' ? 'warning' : 
                                    ($article->status === 'rejected' ? 'danger' : 'secondary')) 
                                }}" style="background-color: {{ 
                                    $article->status === 'published' ? '#28a745' : 
                                    ($article->status === 'pending' ? '#ffc107' : 
                                    ($article->status === 'rejected' ? '#dc3545' : '#6c757d')) 
                                }} !important; color: #fff !important; display: inline-block !important; padding: 0.25em 0.4em !important;">
                                    {{ ucfirst($article->status) }}
                                </span>
                            </td>
                            <td>
                                @if($article->status === 'published')
                                    {{ $article->published_at->format('M d, Y') }}
                                @else
                                    {{ $article->updated_at->format('M d, Y') }}
                                @endif
                            </td>
                            <td>{{ $article->view_count }}</td>
                            <td>
                                <a href="{{ route('articles.show', $article->slug) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.articles.edit', $article->slug) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.articles.destroy', $article->slug) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this article?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @if($article->status === 'pending')
                                    <form action="{{ route('admin.articles.updateStatus', $article->slug) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="published">
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Approve and publish this article?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.articles.updateStatus', $article->slug) }}" method="POST" class="d-inline">
                                        @csrf
                                                                            @method('PATCH')
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Reject this article?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No articles found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center">
                {{ $articles->links() }}
            </div>
        </div>
    </div>
</div>
@endsection    