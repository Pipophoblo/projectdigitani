@extends('layouts.app')

@section('title', 'IPB Digitani - Home')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/homepage.css') }}">
@endsection

@section('content')
<!-- Search Bar -->
<div class="search-container">
    <input type="text" placeholder="Cari Artikel">
    <button>🔍</button>
</div>

<!-- Main Content -->
<div class="main-content">
    <!-- Headline Artikel -->
    <div class="headline">
        <img src="{{ asset('assets/HEADLINE.png') }}" alt="Headline Artikel">
        <div class="headline-text">
        </div>
    </div>

    <!-- Artikel Populer -->
    <aside class="popular-articles">
        <h3>Artikel Populer</h3>
        <ul>
            @foreach($popularArticles as $article)
            <li>{{ $article }}</li>
            @endforeach
        </ul>
    </aside>

    <!-- Artikel Thumbnail -->
    <div class="thumbnail-grid">
        @foreach($articles as $article)
        <div class="thumbnail">
            <img src="{{ asset($article['image']) }}" alt="{{ $article['title'] }}">
            <p>{{ $article['title'] }}</p>
        </div>
        @endforeach
    </div>

    <button class="more-btn">Lihat Artikel Lainnya</button>
</div>
@endsection

@section('scripts')
<script>
    document.querySelector('.more-btn').addEventListener('click', function() {
        alert('Load more articles functionality will be implemented here');
    });
</script>
@endsection