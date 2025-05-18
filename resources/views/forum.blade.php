@extends('layouts.app')

@section('title', 'Forum Tani - IPB Digitani')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('styles')
<link rel="stylesheet" href="{{ asset('css/forumtani.css') }}">
<style>
    /* Additional styles for category tags in thread cards */
    .category-tag {
        display: inline-block;
        background: rgba(162, 196, 243, 0.2);
        color: #466fbf;
        padding: 3px 10px;
        border-radius: 12px;
        font-size: 12px;
        margin-bottom: 5px;
        font-weight: 500;
    }
    
    /* Adjust the avatar size for better layout */
    .comment-item .avatar {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 50%;
    }
    
    /* Improve the thread title spacing */
    .comment-item .comment-content h3 {
        margin-top: 0;
        margin-bottom: 4px;
    }
    
    /* Add some space between user info and category */
    .user-info-line {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 5px;
    }
    
    .user-info-line .left {
        display: flex;
        align-items: center;
    }
    
    .badge {
        margin-left: 5px;
        padding: 2px 6px;
        border-radius: 10px;
        background-color: #f0f0f0;
        color: #666;
        font-size: 12px;
    }
    .meta-line {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    color: #666;
    margin-bottom: 5px;
}

.comment-meta-row {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-top: 10px;
}

.meta-info {
    font-size: 13px;
    color: #444;
}

.timestamp-line {
    margin-top: 6px;
}

.timestamp {
    font-size: 12px;
    color: #666;
    display: block;
    text-align: left;
}

</style>
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
            <img src="{{ asset('storage/' . $category->image) }}" alt="">
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
    <div class="user-info-line">
        <div class="left">
            <strong>{{ $thread['user'] }}</strong> <span class="badge">{{ $thread['role'] }}</span>
        </div>
    </div>

    {{-- Kategori di bawah username --}}
    @if(isset($thread['category_name']))
        <span class="category-tag">{{ $thread['category_name'] }}</span>
    @endif

    {{-- Judul dan isi thread --}}
    <a href="{{ route('forum.show', $thread['id']) }}">
        <h3>{{ $thread['title'] ?? 'Thread tanpa judul' }}</h3>
    </a>
    <p>{{ \Illuminate\Support\Str::limit($thread['content'], 200) }}</p>

    {{-- Komentar dan like --}}
    <div class="comment-meta-row">
        <span class="meta-info">🗨️ {{ $thread['comments'] }} Komentar</span>
        @auth
            <button class="action-btn like-btn meta-info {{ isset($thread['is_liked']) && $thread['is_liked'] ? 'liked' : '' }}" data-thread-id="{{ $thread['id'] }}">
                ❤️ <span class="likes-count">{{ $thread['likes'] }}</span> Suka
            </button>
        @else
            <button class="action-btn like-btn meta-info" onclick="redirectToLogin()">
                ❤️ <span class="likes-count">{{ $thread['likes'] }}</span> Suka
            </button>
        @endauth
    </div>

    {{-- Tanggal dibuat di bawah, rata kiri --}}
    <div class="timestamp-line">
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
        // Debug log to confirm script is running
        console.log('Like script initialized');
        
        // Handle like button clicks
        $('.like-btn').click(function() {
            @auth
                let button = $(this);
                let threadId = button.data('thread-id');
                
                console.log('Like button clicked for thread: ' + threadId);
                
                // Verify the thread ID exists
                if (!threadId) {
                    console.error('Missing thread ID on button');
                    return;
                }
                
                $.ajax({
                    url: '/forum/' + threadId + '/like',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        console.log('Sending AJAX request to: /forum/' + threadId + '/like');
                    },
                    success: function(response) {
                        console.log('AJAX Success Response:', response);
                        
                        if (response.success) {
                            // Update the like count
                            button.find('.likes-count').text(response.likes_count);
                            
                            // Toggle liked class based on action
                            if (response.action === 'liked') {
                                button.addClass('liked');
                            } else {
                                button.removeClass('liked');
                            }
                        } else {
                            console.error('Response indicated failure:', response);
                            alert('Terjadi kesalahan. Detail: ' + (response.message || 'Unknown error'));
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                        
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