@extends('layouts.app')

@section('title', 'IPB Digitani - Articles')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/articles.css') }}">
<style>
    .articles-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }
    
    .main-content {
        width: 70%;
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }
    
    .sidebar {
        width: 25%;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        height: fit-content;
    }
    
    .article-card {
        width: calc(33.33% - 15px);
        margin-bottom: 20px;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .article-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }
    
    .article-img {
        height: 180px;
        width: 100%;
        object-fit: cover;
    }
    
    .article-content {
        padding: 15px;
    }
    
    .article-title {
        text-decoration: none;
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 10px;
        color: #333;
    }

    .article-title a {
    text-decoration: none;
    color: inherit;
    }

    
    .article-meta {
        font-size: 12px;
        color: #666;
        display: flex;
        justify-content: space-between;
    }
    
    .filter-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .filter-buttons {
        display: flex;
        gap: 10px;
    }
    
    .filter-btn {
        text-decoration: none;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        padding: 8px 15px;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .filter-btn.active {
        background: linear-gradient(135deg, #6e8efb, #a777e3);
        color: white;
        border-color: transparent;
    }
    
    .search-box {
        display: flex;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 25px;
        overflow: hidden;
        padding: 5px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    
    .search-box input {
        border: none;
        background: transparent;
        padding: 8px 15px;
        outline: none;
        width: 200px;
    }
    
    .search-box button {
        background: linear-gradient(135deg, #6e8efb, #a777e3);
        border: none;
        color: white;
        padding: 8px 15px;
        border-radius: 20px;
        cursor: pointer;
    }
    
    .trending-list {
        list-style-type: none;
        padding: 0;
    }
    
    .trending-list li {
        padding: 10px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .trending-list li:last-child {
        border-bottom: none;
    }
    
    .trending-list li a {
        color: #333;
        text-decoration: none;
        transition: color 0.3s ease;
    }
    
    .trending-list li a:hover {
        color: #6e8efb;
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
</style>
@endsection

@section('content')
<div class="container">
    <div class="filter-container">
        <div class="filter-buttons">
            <a href="{{ route('articles.index', ['filter' => 'latest']) }}" class="filter-btn {{ $filter == 'latest' ? 'active' : '' }}">Latest</a>
            <a href="{{ route('articles.index', ['filter' => 'trending']) }}" class="filter-btn {{ $filter == 'trending' ? 'active' : '' }}">Trending</a>
            <a href="{{ route('articles.index', ['filter' => 'popular']) }}" class="filter-btn {{ $filter == 'popular' ? 'active' : '' }}">Popular</a>
        </div>
        
        <form action="{{ route('articles.search') }}" method="GET" class="search-box">
            <input type="text" name="query" placeholder="Search articles...">
            <button type="submit">Search</button>
        </form>
    </div>
    
    @auth
        @if(auth()->user()->isAdmin() || auth()->user()->isDosen() || auth()->user()->isPeneliti())
            <a href="{{ route('articles.create') }}" class="create-btn">Create New Article</a>
        @endif
    @endauth
    
    <div class="articles-container">
        <div class="main-content">
            @forelse($articles as $article)
                <div class="article-card">
                    <img src="{{ $article->image ? asset('storage/' . $article->image) : asset('images/placeholder.jpg') }}" alt="{{ $article->title }}" class="article-img">
                    <div class="article-content">
                        <h3 class="article-title">
                            <a href="{{ route('articles.show', $article->slug) }}">{{ $article->title }}</a>
                        </h3>
                        <p>{{ Str::limit($article->summary ?? strip_tags($article->content), 100) }}</p>
                        <div class="article-meta">
                            <span>By {{ $article->user->name }}</span>
                            <span>{{ $article->published_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="no-articles">
                    <p>No articles found.</p>
                </div>
            @endforelse
        </div>
        
        <div class="sidebar">
            <h3>Trending Articles</h3>
            <ul class="trending-list">
                @foreach($trendingArticles as $trending)
                    <li>
                        <a href="{{ route('articles.show', $trending->slug) }}">{{ $trending->title }}</a>
                        <div class="article-meta">
                            <small>{{ $trending->view_count }} views</small>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    
    <div class="pagination">
        {{ $articles->links() }}
    </div>
</div>

@if(request('query'))
    <div class="search-results">
        <p>Hasil pencarian untuk: <strong>"{{ request('query') }}"</strong></p>
        @if($articles->isEmpty())
            <p>Tidak ditemukan artikel yang sesuai dengan kata kunci tersebut.</p>
        @endif
    </div>
@endif
@endsection