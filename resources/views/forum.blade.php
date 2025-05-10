@extends('layouts.app')

@section('title', 'Forum Tani - IPB Digitani')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('styles')
<link rel="stylesheet" href="{{ asset('css/forumtani.css') }}">
@endsection

@section('content')
<section class="hero">
    <div class="overlay">
        <h1>Jelajahi Forum Tani.</h1>
        <p>
            Gabung di Forum Tani: tempat seru buat ngobrol seputar dunia pertanian.
            Dari cara menanam yang lebih oke sampai tips hadapi cuaca ekstrem,
            semua ada di sini agar hasil pertanianmu tumbuh lebih baik.
        </p>
        <form action="{{ route('forum.search') }}" method="GET" class="search-box">
            <input type="text" name="search" placeholder="Cari Thread" value="{{ request('search') }}" />
            <button type="submit">🔍</button>
        </form>
        <a href="{{ route('forum.create') }}" class="buat-thread">Buat Thread</a>
    </div>
</section>

<section class="kategori">
    <h2>Kategori Topik Forum</h2>
    <div class="grid-kategori">
        @foreach($categories as $category)
        <a href="{{ route('forum.index', ['category' => $category->id]) }}" class="kategori-item">
            <img src="{{ asset($category->image) }}" alt="{{ $category->name }}">
            <p>{{ $category->name }}</p>
        </a>
        @endforeach
    </div>
</section>

<div class="forum-container">
    <div class="tabs">
        <a href="{{ route('forum.index') }}" class="tab {{ $filter == 'all' ? 'active' : '' }}">Semua</a>
        <a href="{{ route('forum.index', ['filter' => 'trending']) }}" class="tab {{ $filter == 'trending' ? 'active' : '' }}">Trending</a>
        <a href="{{ route('forum.index', ['filter' => 'popular']) }}" class="tab {{ $filter == 'popular' ? 'active' : '' }}">Terpopuler</a>
    </div>

    @if(request('search'))
        <div class="search-results">
            <p>Hasil pencarian untuk: <strong>"{{ request('search') }}"</strong></p>
            @if(count($threads) == 0)
                <p>Tidak ditemukan thread yang sesuai dengan kata kunci tersebut.</p>
            @endif
        </div>
    @endif

    <div class="comment-list">
        @if(count($threads) > 0)
            @foreach($threads as $thread)
            <div class="comment-item">
                <img src="https://via.placeholder.com/50" alt="avatar" class="avatar" />
                <div class="comment-content">
                    <a href="{{ route('forum.show', $thread['id']) }}">
                        <h3>{{ $thread['title'] ?? 'Thread tanpa judul' }}</h3>
                    </a>
                    <strong>{{ $thread['user'] }}</strong> <span class="badge">{{ $thread['role'] }}</span>
                    <p>{{ \Illuminate\Support\Str::limit($thread['content'], 200) }}</p>
                    <div class="comment-meta">
                        <span>🗨️ {{ $thread['comments'] }} Komentar</span>
                        @auth
                            <button class="action-btn like-btn {{ isset($thread['is_liked']) && $thread['is_liked'] ? 'liked' : '' }}" data-thread-id="{{ $thread['id'] }}">
                                ❤️ <span class="likes-count">{{ $thread['likes'] }}</span> Suka
                            </button>
                        @else
                            <button class="action-btn like-btn" onclick="redirectToLogin()">
                                ❤️ <span class="likes-count">{{ $thread['likes'] }}</span> Suka
                            </button>
                        @endauth
                        <span>🔗 Bagikan</span>
                        <span>⚠️ Laporkan</span>
                        <span class="timestamp">Dibuat pada {{ $thread['created_at'] }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="no-threads">
                <p>Belum ada thread yang tersedia.</p>
                <a href="{{ route('forum.create') }}" class="buat-thread">Buat Thread Pertama</a>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Create thread button functionality
    document.querySelector('.buat-thread')?.addEventListener('click', function(e) {
        @guest
            e.preventDefault();
            alert('Silakan login terlebih dahulu untuk membuat thread.');
            window.location.href = '{{ route('login') }}';
        @endguest
    });
    
    function redirectToLogin() {
        alert('Silakan login terlebih dahulu untuk menyukai thread.');
        window.location.href = '{{ route('login') }}';
    }
    
    $(document).ready(function() {
    // Debug log to make sure the script is running
    console.log('Like script initialized');
    
    $('.like-btn').click(function() {
        @auth
            let button = $(this);
            let threadId = button.data('thread-id');
            
            console.log('Like button clicked for thread: ' + threadId);
            
            // Show the thread ID to verify it's correctly set
            console.log('Thread ID:', threadId);
            
            $.ajax({
                url: '/forum/' + threadId + '/like',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                beforeSend: function() {
                    console.log('Sending AJAX request to: /forum/' + threadId + '/like');
                },
                success: function(response) {
                    console.log('AJAX Success Response:', response);
                    
                    if (response.success) {
                        // Update the like count
                        let oldCount = button.find('.likes-count').text();
                        button.find('.likes-count').text(response.likes_count);
                        
                        console.log('Updated likes count from ' + oldCount + ' to ' + response.likes_count);
                        
                        // Toggle liked class based on action
                        if (response.action === 'liked') {
                            button.addClass('liked');
                            console.log('Added liked class');
                        } else {
                            button.removeClass('liked');
                            console.log('Removed liked class');
                        }
                    } else {
                        console.error('Response indicated failure:', response);
                        alert('Terjadi kesalahan. Detail: ' + (response.message || 'Unknown error'));
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    console.error('Response:', xhr.responseText);
                    
                    if (xhr.status === 401) {
                        alert('Silakan login terlebih dahulu untuk menyukai thread.');
                        window.location.href = '{{ route('login') }}';
                    } else if (xhr.status === 404) {
                        alert('Thread tidak ditemukan.');
                    } else if (xhr.status === 419) {
                        alert('CSRF token tidak valid. Silakan refresh halaman.');
                    } else {
                        alert('Terjadi kesalahan. Silakan coba lagi. [Status: ' + xhr.status + ']');
                    }
                }
            });
        @endauth
    });
});
</script>
@endsection