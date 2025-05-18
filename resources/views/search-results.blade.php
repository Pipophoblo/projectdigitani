@extends('layouts.app')

@section('title', 'Search Results - ' . $query)

@section('styles')
<style>
    .search-header {
        margin-bottom: 30px;
    }
    
    .search-results-container {
        display: grid;
        grid-template-columns: 1fr;
        gap: 30px;
    }
    
    .result-section {
        margin-bottom: 40px;
    }
    
    .result-section h2 {
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }
    
    .result-card {
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        margin-bottom: 15px;
        transition: transform 0.2s ease;
    }
    
    .result-card:hover {
        transform: translateY(-3px);
    }
    
    .result-card h3 {
        margin-top: 0;
        margin-bottom: 10px;
    }
    
    .result-card p {
        color: #666;
        margin-bottom: 10px;
    }
    
    .meta {
        display: flex;
        font-size: 0.9rem;
        color: #777;
    }
    
    .meta span {
        margin-right: 15px;
    }
    
    .read-more {
        display: inline-block;
        margin-top: 10px;
        color: #4CAF50;
        text-decoration: none;
        font-weight: bold;
    }
    
    .no-results {
        text-align: center;
        padding: 40px;
        background: #f9f9f9;
        border-radius: 8px;
    }
</style>
@endsection

@section('content')
<div class="search-header">
    <h1>Search Results for "{{ $query }}"</h1>
    <p>Found {{ count($articles) + count($threads) }} results</p>
    
    <form action="{{ route('search') }}" method="GET" class="search-container mt-3">
        <input type="text" name="query" value="{{ $query }}" placeholder="Cari artikel atau diskusi...">
        <button type="submit">🔍 Search</button>
    </form>
</div>

<div class="search-results-container">
    <!-- Article Results -->
    <div class="result-section">
        <h2>Article Results ({{ count($articles) }})</h2>
        
        @if(count($articles) > 0)
            @foreach($articles as $article)
                <div class="result-card">
                    <h3>{{ $article->title }}</h3>
                    <p>{{ \Illuminate\Support\Str::limit(strip_tags($article->content), 150) }}</p>
                    <div class="meta">
                        <span>📅 {{ $article->published_at->format('d M Y') }}</span>
                        <span>👁️ {{ $article->view_count }} views</span>
                    </div>
                    <a href="{{ route('articles.show', $article) }}" class="read-more">Read Article →</a>
                </div>
            @endforeach
        @else
            <p>No articles found matching "{{ $query }}"</p>
        @endif
    </div>
    
    <!-- Thread Results -->
    <div class="result-section">
        <h2>Discussion Results ({{ count($threads) }})</h2>
        
        @if(count($threads) > 0)
            @foreach($threads as $thread)
                <div class="result-card">
                    <h3>{{ $thread->title }}</h3>
                    <p>{{ \Illuminate\Support\Str::limit(strip_tags($thread->content), 150) }}</p>
                    <div class="meta">
                        <span>👤 {{ $thread->user->name ?? 'Unknown User' }}</span>
                        <span>💬 {{ $thread->comments->count() ?? 0 }} comments</span>
                        <span>👁️ {{ $thread->view_count ?? 0 }} views</span>
                    </div>
                    <a href="{{ route('forum.show', $thread->id) }}" class="read-more">View Discussion →</a>
                </div>
            @endforeach
        @else
            <p>No discussions found matching "{{ $query }}"</p>
        @endif
    </div>
</div>

@if(count($articles) == 0 && count($threads) == 0)
    <div class="no-results">
        <h2>No results found for "{{ $query }}"</h2>
        <p>Try different keywords or check your spelling</p>
    </div>
@endif
@endsection