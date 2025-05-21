@extends('layouts.app')

@section('title', $article->title . ' - IPB Digitani')

@section('styles')
<style>
    .article-container {
        display: flex;
        gap: 30px;
    }
    
    .article-main {
        width: 70%;
    }
    
    .article-sidebar {
        width: 30%;
    }
    
    .article-header {
        margin-bottom: 30px;
    }
    
    .article-title {
        text-decoration: none;
        font-size: 36px;
        font-weight: bold;
        margin-bottom: 15px;
        color: #333;
    }
    
    .article-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        font-size: 14px;
        color: #666;
    }
    
    .article-author {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .article-date {
        font-style: italic;
    }
    
    .article-featured-image {
        width: 100%;
        border-radius: 15px;
        overflow: hidden;
        margin-bottom: 30px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .article-featured-image img {
        width: 100%;
        height: auto;
        display: block;
    }
    
    .article-content {
        line-height: 1.8;
        color: #333;
        font-size: 16px;
    }
    
    .article-keywords {
        margin-top: 30px;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    
    .keyword {
        background: rgba(110, 142, 251, 0.1);
        color: #6e8efb;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 14px;
    }
    
    .article-actions {
        margin-top: 30px;
        display: flex;
        gap: 15px;
    }
    
    .action-btn {
        padding: 8px 20px;
        border-radius: 25px;
        cursor: pointer;
        font-size: 14px;
        text-decoration: none;
    }
    
    .edit-btn {
        background: rgba(110, 142, 251, 0.1);
        color: #6e8efb;
        border: 1px solid #6e8efb;
    }
    
    .delete-btn {
        background: rgba(255, 67, 67, 0.1);
        color: #ff4343;
        border: 1px solid #ff4343;
    }
    
    .sidebar-widget {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        overflow: hidden;
        padding: 20px;
        margin-bottom: 30px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .widget-title {
        font-size: 20px;
        margin-bottom: 15px;
        font-weight: bold;
        color: #333;
    }
    
    .trending-list {
        list-style-type: none;
        padding: 0;
    }
    
    .trending-list li {
        padding: 12px 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .trending-list li:last-child {
        border-bottom: none;
    }
    
    .trending-list a {
        text-decoration: none;
        color: #333;
        transition: color 0.3s ease;
    }
    
    .trending-list a:hover {
        color: #6e8efb;
    }
    
    .trending-meta {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        color: #666;
        margin-top: 5px;
    }
    
    .related-articles {
        margin-top: 50px;
    }
    
    .related-title {
        font-size: 24px;
        margin-bottom: 20px;
        font-weight: bold;
        color: #333;
    }
    
    .related-grid {
        display: flex;
        gap: 20px;
    }
    
    .related-card {
        width: calc(33.33% - 15px);
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }
    
    .related-card:hover {
        transform: translateY(-5px);
    }
    
    .related-image {
        height: 150px;
        overflow: hidden;
    }
    
    .related-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .related-content {
        padding: 15px;
    }
    
    .related-card-title {
        text-decoration: none;
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 10px;
    }
    
    .related-meta {
        font-size: 12px;
        color: #666;
    }
    
    /* Status badges */
    .status-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 15px;
        font-size: 12px;
        margin-left: 10px;
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
</style>
@endsection

@section('content')
<div class="container">
    <div class="article-container">
        <div class="article-main">
            <div class="article-header">
                <h1 class="article-title">{{ $article->title }}</h1>
                <div class="article-meta">
                    <div class="article-author">
                        By {{ $article->user->name }} 
                        @if($article->status !== 'published')
                            <span class="status-badge status-{{ $article->status }}">{{ ucfirst($article->status) }}</span>
                        @endif
                    </div>
                    <div class="article-date">
                        {{ $article->published_at ? $article->published_at->format('F j, Y') : $article->created_at->format('F j, Y') }}
                    </div>
                </div>
            </div>
            
            @if($article->image)
                <div class="article-featured-image">
                <img src="{{ Storage::url($article->image) }}" alt="{{ $article->title }}">
                </div>
            @endif
            
            <div class="article-content">
                {!! nl2br(e($article->content)) !!}
            </div>
            
            @if($article->keywords)
                <div class="article-keywords">
                    @foreach(explode(',', $article->keywords) as $keyword)
                        <span class="keyword">{{ trim($keyword) }}</span>
                    @endforeach
                </div>
            @endif
            
            @auth
                @if(auth()->id() === $article->user_id || auth()->user()->isAdmin())
                    <div class="article-actions">
                        <a href="{{ route('articles.edit', $article->slug) }}" class="action-btn edit-btn">Edit Article</a>
                        <form action="{{ route('articles.destroy', $article->slug) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this article?')">Delete</button>
                        </form>
                    </div>
                @endif
            @endauth
            
            @if($relatedArticles->count() > 0)
                <div class="related-articles">
                    <h3 class="related-title">Related Articles</h3>
                    <div class="related-grid">
                        @foreach($relatedArticles as $related)
                            <div class="related-card">
                                <div class="related-image">
                                <img src="{{ $related->image ? Storage::url($related->image) : asset('images/placeholder.jpg') }}" alt="{{ $related->title }}">
                                </div>
                                <div class="related-content">
                                    <h4 class="related-card-title">
                                        <a href="{{ route('articles.show', $related->slug) }}">{{ $related->title }}</a>
                                    </h4>
                                    <div class="related-meta">
                                        {{ $related->published_at->format('M d, Y') }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
        
        <div class="article-sidebar">
            <div class="sidebar-widget">
                <h3 class="widget-title">Trending Articles</h3>
                <ul class="trending-list">
                    @foreach($trendingArticles as $trending)
                        <li>
                            <a href="{{ route('articles.show', $trending->slug) }}">{{ $trending->title }}</a>
                            <div class="trending-meta">
                                <span>{{ $trending->view_count }} views</span>
                                <span>{{ $trending->published_at->format('M d') }}</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection