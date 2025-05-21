@extends('layouts.app')

@section('title', 'IPB Digitani - Home')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/homepage.css') }}">
<style>
    /* Featured Headline Slider Styles */
    .headline-slider {
        position: relative;
        width: 100%;
        height: 380px;
        margin-bottom: 40px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        border-radius: 10px;
        overflow: hidden;
    }
    
    .headline-slide {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        opacity: 0;
        transition: opacity 0.6s ease-in-out;
        background: #fff;
    }
    
    .headline-slide.active {
        opacity: 1;
    }
    
    .headline-image {
        flex: 1;
        height: 100%;
        overflow: hidden;
    }
    
    .headline-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .headline-slide:hover .headline-image img {
        transform: scale(1.05);
    }
    
    .headline-content {
        flex: 1;
        padding: 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .headline-category {
        font-size: 0.9rem;
        text-transform: uppercase;
        color: #466fbf;
        font-weight: bold;
        margin-bottom: 15px;
        letter-spacing: 1px;
    }
    
    .headline-content h2 {
        font-size: 2rem;
        margin: 0 0 15px 0;
        line-height: 1.3;
    }
    
    .headline-content p {
        margin: 0 0 25px 0;
        font-size: 1rem;
        line-height: 1.6;
        color: #666;
    }
    
    .read-more {
        display: inline-block;
        background: linear-gradient(to right, #a2c4f3, #466fbf);
        color: white;
        padding: 10px 25px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
        transition: all 0.3s;
        align-self: flex-start;
    }
    
    .read-more:hover {
        background: linear-gradient(to right, #91b5e5, #3a62af);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        color: white;
    }
    
    /* Slider Controls */
    .slider-controls {
        position: absolute;
        bottom: 20px;
        right: 20px;
        display: flex;
        z-index: 10;
    }
    
    .slider-dots {
        display: flex;
    }
    
    .slider-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: rgba(0, 0, 0, 0.2);
        margin: 0 5px;
        cursor: pointer;
        border: none;
        transition: background 0.3s ease;
    }
    
    .slider-dot.active {
        background: #466fbf;
    }
    
    .slider-arrows {
        display: flex;
        margin-left: 15px;
    }
    
    .slider-arrow {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: linear-gradient(to right, #a2c4f3, #466fbf);
        margin: 0 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: none;
        color: white;
        font-size: 1.2rem;
        transition: all 0.3s ease;
    }
    
    .slider-arrow:hover {
        background: linear-gradient(to right, #91b5e5, #3a62af);
    }
    
    /* Search Form */
    .main-search-container {
        margin: 20px auto;
        max-width: 600px;
        display: flex;
    }
    
    .main-search-container input {
        flex-grow: 1;
        padding: 10px 15px;
        border: 1px solid #ddd;
        border-radius: 5px 0 0 5px;
        font-size: 1rem;
    }
    
    .main-search-container button {
        padding: 10px 20px;
        background: linear-gradient(to right, #a2c4f3, #466fbf);
        color: white;
        border: none;
        border-radius: 0 5px 5px 0;
        cursor: pointer;
        font-size: 1rem;
    }
    
    /* Content Layout */
    .main-content {
        display: grid;
        grid-template-columns: 3fr 1fr;
        gap: 30px;
        margin-bottom: 30px;
    }
    
    .main-column {
        display: flex;
        flex-direction: column;
    }
    
    /* Section Styles */
    .article-section, .thread-section {
        margin-bottom: 40px;
    }
    
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .section-header h2 {
        margin: 0;
        font-size: 1.5rem;
        position: relative;
        padding-left: 15px;
    }
    
    .section-header h2::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 5px;
        background: linear-gradient(to bottom, #a2c4f3, #466fbf);
        border-radius: 3px;
    }
    
    .see-more {
        color: #466fbf;
        text-decoration: none;
        font-weight: bold;
    }
    
    /* Thumbnail Grid for both Articles and Threads */
    .thumbnail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    }
    
    .thumbnail {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background: white;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .thumbnail:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .thumbnail img {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }
    
    .thumbnail .content {
        padding: 15px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    
    .thumbnail h3 {
        margin: 0 0 10px 0;
        font-size: 1.1rem;
    }
    
    .thumbnail p {
        color: #666;
        margin-bottom: 15px;
        flex-grow: 1;
    }
    
    .thumbnail a.btn-link {
        color: #466fbf;
        text-decoration: none;
        font-weight: bold;
        padding: 0;
        align-self: flex-start;
    }
    
    /* Thread-specific styles */
    .thread-thumbnail {
        background-color: #f9f9f9;
        border-left: 3px solid #466fbf;
    }
    
    .thread-thumbnail .thread-category {
        display: inline-block;
        background: rgba(162, 196, 243, 0.2);
        color: #466fbf;
        padding: 3px 10px;
        border-radius: 12px;
        font-size: 0.8rem;
        margin-bottom: 10px;
    }
    
    .thread-meta {
        display: flex;
        flex-wrap: wrap;
        font-size: 0.8rem;
        color: #777;
        margin-top: 10px;
        margin-bottom: 5px;
    }
    
    .thread-meta span {
        margin-right: 15px;
        display: flex;
        align-items: center;
    }
    
    .thread-meta span i {
        margin-right: 5px;
    }
    
    .thread-author {
        display: flex;
        align-items: center;
        margin-top: 15px;
    }
    
    .thread-author-avatar {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        margin-right: 10px;
        background: linear-gradient(to right, #a2c4f3, #466fbf);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: white;
    }
    
    .thread-author-info {
        font-size: 0.9rem;
    }
    
    .thread-author-name {
        font-weight: bold;
        margin-bottom: 2px;
    }
    
    .thread-author-role {
        color: #777;
        font-size: 0.8rem;
    }
    
    /* Sidebar */
    .sidebar {
        display: flex;
        flex-direction: column;
    }
    
    .sidebar-section {
        margin-bottom: 30px;
        background: white;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    
    .sidebar-section h3 {
        margin-top: 0;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
        position: relative;
        padding-left: 15px;
    }
    
    .sidebar-section h3::before {
        content: '';
        position: absolute;
        left: 0;
        top: 30%;
        height: 40%;
        width: 5px;
        background: linear-gradient(to bottom, #a2c4f3, #466fbf);
        border-radius: 3px;
    }
    
    .trending-article {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        text-decoration: none;
        color: inherit;
        padding: 8px;
        border-radius: 5px;
        transition: background-color 0.2s;
    }
    
    .trending-article:hover {
        background-color: #f5f5f5;
    }
    
    .trending-article img {
        width: 60px;
        height: 60px;
        border-radius: 5px;
        object-fit: cover;
        margin-right: 10px;
    }
    
    .trending-article p {
        margin: 0;
        font-size: 0.9rem;
        font-weight: 500;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 900px) {
        .headline-slide {
            flex-direction: column;
        }
        
        .headline-image, .headline-content {
            flex: none;
            width: 100%;
        }
        
        .headline-image {
            height: 50%;
        }
        
        .headline-content {
            height: 50%;
            padding: 20px;
        }
        
        .headline-content h2 {
            font-size: 1.5rem;
        }
        
        .headline-slider {
            height: 500px;
        }
    }
    
    @media (max-width: 767px) {
        .main-content {
            grid-template-columns: 1fr;
        }
        
        .main-column, .sidebar {
            grid-column: 1;
        }
    }
</style>
@endsection

@section('content')
<!-- Main Search Bar -->
<form action="{{ route('search') }}" method="GET" class="main-search-container">
    <input type="text" name="query" placeholder="Cari artikel atau diskusi...">
    <button type="submit">🔍 Search</button>
</form>

<!-- Featured Headline Slider -->
<div class="headline-slider">
    @forelse($headlineArticles as $index => $article)
        <div class="headline-slide {{ $index == 0 ? 'active' : '' }}">
            <div class="headline-image">
            <img src="{{ $article->image ? asset('storage/' . $article->image) : asset('assets/default-article-image.jpg') }}"
                     alt="{{ $article->title }}">
            </div>
            <div class="headline-content">
                <div class="headline-category">ARTIKEL PILIHAN</div>
                <h2>{{ $article->title }}</h2>
                <p>{{ $article->summary ?? \Illuminate\Support\Str::limit(strip_tags($article->content), 150) }}</p>
                <a href="{{ route('articles.show', $article) }}" class="read-more">Baca Selengkapnya</a>
            </div>
        </div>
    @empty
        <div class="headline-slide active">
            <div class="headline-image">
                <img src="{{ asset('assets/default-article-image.jpg') }}" alt="Selamat Datang">
            </div>
            <div class="headline-content">
                <div class="headline-category">SELAMAT DATANG</div>
                <h2>Jelajahi Dunia Pertanian Digital</h2>
                <p>Temukan artikel pertanian terbaru, tips budidaya tanaman, dan bergabunglah dengan komunitas petani untuk berbagi pengalaman dan pengetahuan.</p>
                <a href="{{ route('articles.index') }}" class="read-more">Jelajahi Artikel</a>
            </div>
        </div>
    @endforelse
    
    <!-- Slider Controls -->
    <div class="slider-controls">
        <div class="slider-dots">
            @for($i = 0; $i < max(count($headlineArticles), 1); $i++)
                <button class="slider-dot {{ $i == 0 ? 'active' : '' }}" data-index="{{ $i }}"></button>
            @endfor
        </div>
        <div class="slider-arrows">
            <button class="slider-arrow prev">&#9664;</button>
            <button class="slider-arrow next">&#9654;</button>
        </div>
    </div>
</div>

<div class="main-content">
    <!-- Main Content Column -->
    <div class="main-column">
        <!-- Articles Section -->
        <div class="article-section">
            <div class="section-header">
                <h2>Artikel Terbaru</h2>
                <a href="{{ route('articles.index') }}" class="see-more">Lihat Semua →</a>
            </div>
            
            <div class="thumbnail-grid">
                @forelse($recentArticles as $article)
                    <div class="thumbnail">
                            <img src="{{ $article->image ? asset('storage/' . $article->image) : asset('assets/default-article-image.jpg') }}"
                             alt="{{ $article->title }}">
                        <div class="content">
                            <h3>{{ $article->title }}</h3>
                            <p>{{ \Illuminate\Support\Str::limit(strip_tags($article->content), 80) }}</p>
                            <a href="{{ route('articles.show', $article) }}" class="btn btn-link">Baca selengkapnya →</a>
                        </div>
                    </div>
                @empty
                    <p>Tidak ada artikel terbaru</p>
                @endforelse
            </div>
        </div>
            
        <!-- Thread Section with improved design -->
        <div class="thread-section">
            <div class="section-header">
                <h2>Diskusi Terpopuler</h2>
                <a href="{{ route('forum.index') }}" class="see-more">Lihat Semua →</a>
            </div>
            
            <div class="thumbnail-grid">
                @forelse($trendingThreads as $thread)
                    <div class="thumbnail thread-thumbnail">
                        <div class="content">
                            <span class="thread-category">{{ $thread->category->name ?? 'Umum' }}</span>
                            <h3>{{ $thread->title }}</h3>
                            <p>{{ \Illuminate\Support\Str::limit(strip_tags($thread->content), 100) }}</p>
                            
                            <div class="thread-meta">
                                <span><i>💬</i> {{ $thread->comments->count() }} komentar</span>
                                <span><i>👁️</i> {{ $thread->view_count }} dilihat</span>
                                <span><i>❤️</i> {{ $thread->likes->count() }} disukai</span>
                            </div>
                            
                            <div class="thread-author">
                                <div class="thread-author-avatar">
                                    {{ strtoupper(substr($thread->user->name ?? 'U', 0, 1)) }}
                                </div>
                                <div class="thread-author-info">
                                    <div class="thread-author-name">{{ $thread->user->name }}</div>
                                    <div class="thread-author-role">{{ $thread->user->role ?? 'Member' }}</div>
                                </div>
                            </div>
                            
                            <a href="{{ route('forum.show', $thread->id) }}" class="btn btn-link">Baca diskusi →</a>
                        </div>
                    </div>
                @empty
                    <p>Tidak ada diskusi terpopuler</p>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Trending Articles -->
        <div class="sidebar-section">
            <h3>Artikel Populer</h3>
            
            @forelse($trendingArticles as $article)
                <a href="{{ route('articles.show', $article) }}" class="trending-article">
                <img src="{{ $article->image ? asset('storage/' . $article->image) : asset('assets/default-article-image.jpg') }}"                         alt="{{ $article->title }}">
                    <p>{{ $article->title }}</p>
                </a>
            @empty
                <p>Belum ada artikel populer</p>
            @endforelse
        </div>
        
        <!-- You could add additional sidebar sections here if needed -->
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Featured Headline Slider Functionality
    document.addEventListener('DOMContentLoaded', function() {
        const slides = document.querySelectorAll('.headline-slide');
        const dots = document.querySelectorAll('.slider-dot');
        const prevBtn = document.querySelector('.slider-arrow.prev');
        const nextBtn = document.querySelector('.slider-arrow.next');
        let currentIndex = 0;
        
        function showSlide(index) {
            // Handle index boundaries
            if (index >= slides.length) {
                index = 0;
            } else if (index < 0) {
                index = slides.length - 1;
            }
            
            // Hide all slides
            slides.forEach(slide => {
                slide.classList.remove('active');
            });
            
            // Deactivate all dots
            dots.forEach(dot => {
                dot.classList.remove('active');
            });
            
            // Show current slide and activate corresponding dot
            slides[index].classList.add('active');
            dots[index].classList.add('active');
            
            currentIndex = index;
        }
        
        // Set up automatic slideshow
        let slideInterval = setInterval(() => {
            showSlide(currentIndex + 1);
        }, 7000); // Slightly longer interval for reading
        
        // Add click event listeners to dots
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                clearInterval(slideInterval);
                showSlide(index);
                
                // Restart interval
                slideInterval = setInterval(() => {
                    showSlide(currentIndex + 1);
                }, 7000);
            });
        });
        
        // Previous button
        prevBtn.addEventListener('click', () => {
            clearInterval(slideInterval);
            showSlide(currentIndex - 1);
            
            // Restart interval
            slideInterval = setInterval(() => {
                showSlide(currentIndex + 1);
            }, 7000);
        });
        
        // Next button
        nextBtn.addEventListener('click', () => {
            clearInterval(slideInterval);
            showSlide(currentIndex + 1);
            
            // Restart interval
            slideInterval = setInterval(() => {
                showSlide(currentIndex + 1);
            }, 7000);
        });
    });
</script>
@endsection