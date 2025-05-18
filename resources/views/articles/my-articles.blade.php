@extends('layouts.app')

@section('title', 'My Articles - IPB Digitani')

@section('styles')
<style>
    .my-articles-container {
        max-width: 900px;
        margin: 0 auto;
    }
    
    .page-title {
        font-size: 28px;
        margin-bottom: 30px;
        color: #333;
        text-align: center;
    }
    
    .create-btn {
        background: linear-gradient(135deg, #6e8efb, #a777e3);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 25px;
        cursor: pointer;
        font-weight: bold;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        margin-bottom: 20px;
    }
    
    .create-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
    }
    
    .article-table {
        width: 100%;
        border-collapse: collapse;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .article-table th, .article-table td {
        padding: 15px;
        text-align: left;
    }
    
    .article-table th {
        background: rgba(110, 142, 251, 0.1);
        color: #333;
        font-weight: 500;
    }
    
    .article-table tr {
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .article-table tr:last-child {
        border-bottom: none;
    }
    
    .status-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 12px;
    }
    
    .status-draft {
        background: #f0f0f0;
        color: #666;
    }
    
    .status-pending {
        background: #fff8e1;
        color: #f57c00;
    }
    
    .status-published {
        background: #e8f5e9;
        color: #2e7d32;
    }
    
    .status-rejected {
        background: #ffebee;
        color: #c62828;
    }
    
    .action-btn {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 5px;
        margin-right: 5px;
        font-size: 14px;
        text-decoration: none;
        transition: all 0.3s;
    }
    
    .view-btn {
        background: rgba(110, 142, 251, 0.1);
        color: #6e8efb;
    }
    
    .edit-btn {
        background: rgba(46, 204, 113, 0.1);
        color: #2ecc71;
    }
    
    .delete-btn {
        background: rgba(231, 76, 60, 0.1);
        color: #e74c3c;
        cursor: pointer;
        border: none;
    }
    
    .action-btn:hover {
        filter: brightness(90%);
    }
    
    .no-articles {
        text-align: center;
        padding: 30px;
        color: #666;
    }
    
    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 30px;
        gap: 10px;
    }
    
    .pagination .page-item {
        list-style: none;
    }
    
    .pagination .page-link {
        padding: 8px 15px;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 5px;
        text-decoration: none;
        color: #333;
        transition: all 0.3s ease;
    }
    
    .pagination .active .page-link {
        background: linear-gradient(135deg, #6e8efb, #a777e3);
        color: white;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="my-articles-container">
        <h1 class="page-title">My Articles</h1>
        
        <a href="{{ route('articles.create') }}" class="create-btn">Create New Article</a>
        
        @if($articles->count() > 0)
            <table class="article-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($articles as $article)
                        <tr>
                            <td>{{ $article->title }}</td>
                            <td>
                                <span class="status-badge status-{{ $article->status }}">
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
                            <td>
                                <a href="{{ route('articles.show', $article->slug) }}" class="action-btn view-btn">View</a>
                                <a href="{{ route('articles.edit', $article->slug) }}" class="action-btn edit-btn">Edit</a>
                                <form action="{{ route('articles.destroy', $article->slug) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this article?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="pagination">
                {{ $articles->links() }}
            </div>
        @else
            <div class="no-articles">
                <p>You haven't created any articles yet.</p>
            </div>
        @endif
    </div>
</div>
@endsection