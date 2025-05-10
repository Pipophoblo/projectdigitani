@extends('layouts.app')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('title', $thread->title . ' - IPB Digitani Forum')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/forumtani.css') }}">
<style>
    .thread-container {
        max-width: 1000px;
        margin: 30px auto;
        padding: 0 20px;
    }
    
    .thread-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .thread-header h1 {
        font-size: 24px;
    }
    
    .category-badge {
        background-color: #2b4eff;
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 14px;
    }
    
    .thread-meta {
        margin-bottom: 15px;
        font-size: 14px;
        color: #666;
    }
    
    .thread-content {
        background-color: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }
    
    .comments-section h2 {
        margin-bottom: 20px;
    }
    
    .comment-form {
        background-color: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }
    
    .comment-form textarea {
        width: 100%;
        min-height: 100px;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        margin-bottom: 15px;
    }
    
    .action-buttons {
        display: flex;
        gap: 10px;
        margin: 15px 0;
    }
    
    .action-btn {
        padding: 8px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 14px;
    }
    
    .like-btn {
        background-color: {{ $isLiked ? '#ff5959' : '#f0f0f0' }};
        color: {{ $isLiked ? 'white' : '#333' }};
    }
    
    .share-btn {
        background-color: #f0f0f0;
        color: #333;
    }
    
    .report-btn {
        background-color: #f0f0f0;
        color: #333;
        margin-left: auto;
    }
    
    .comments-list {
        margin-top: 20px;
    }
    
    .comment {
        background-color: #f9f9f9;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 15px;
    }
    
    .comment-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }
    
    .comment-user {
        font-weight: bold;
    }
    
    .comment-date {
        color: #666;
        font-size: 12px;
    }
    
    .btn-back {
        padding: 8px 15px;
        background-color: #6c757d;
        border: none;
        color: white;
        border-radius: 5px;
        text-decoration: none;
        font-size: 14px;
    }

    .liked {
    color: red;
}
</style>
@endsection

@section('content')
<div class="thread-container">
    <a href="{{ route('forum.index') }}" class="btn-back">← Kembali ke Forum</a>
    
    <div class="thread-header">
        <h1>{{ $thread->title }}</h1>
        <span class="category-badge">{{ $thread->category->name }}</span>
    </div>
    
    <div class="thread-meta">
        <strong>{{ $thread->user->name }}</strong> 
        <span class="badge">{{ $thread->user->role }}</span> • 
        <span>{{ $thread->created_at->format('d F Y H:i') }}</span> • 
        <span>{{ $thread->view_count }} kali dilihat</span>
    </div>
    
    <div class="thread-content">
        {{ $thread->content }}
    </div>
    
    <div class="action-buttons">
        <button class="action-btn like-btn" data-thread-id="{{ $thread->id }}">
            ❤️ <span class="likes-count">{{ $thread->likes()->count() }}</span> Suka
        </button>        
        <button class="action-btn share-btn">
            🔗 Bagikan
        </button>
        <button class="action-btn report-btn">
            ⚠️ Laporkan
        </button>
    </div>
    
    <div class="comments-section">
        <h2>Komentar ({{ $thread->comments->count() }})</h2>
        
        @auth
        <div class="comment-form">
            <form action="{{ route('forum.comment', $thread->id) }}" method="POST">
                @csrf
                <textarea name="content" placeholder="Tulis komentar anda..." required></textarea>
                <button type="submit" class="action-btn" style="background-color: #2b4eff; color: white;">Kirim Komentar</button>
            </form>
        </div>
        @else
        <div class="comment-form">
            <p>Silakan <a href="{{ route('login') }}">login</a> untuk menambahkan komentar.</p>
        </div>
        @endauth
        
        <div class="comments-list">
            @if($thread->comments->count() > 0)
                @foreach($thread->comments as $comment)
                <div class="comment">
                    <div class="comment-header">
                        <span class="comment-user">
                            {{ $comment->user->name }}
                            <span class="badge">{{ $comment->user->role }}</span>
                        </span>
                        <span class="comment-date">{{ $comment->created_at->format('d F Y H:i') }}</span>
                    </div>
                    <div class="comment-body">
                        {{ $comment->content }}
                    </div>
                </div>
                @endforeach
            @else
                <p>Belum ada komentar. Jadilah yang pertama berkomentar!</p>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Like functionality
    const likeBtn = document.getElementById('like-btn');
    const likesCount = document.getElementById('likes-count');
    
    likeBtn.addEventListener('click', function() {
        @auth
        const threadId = this.getAttribute('data-thread-id');
        
        fetch(`/forum/${threadId}/like`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                likesCount.textContent = data.likes_count;
                
                if (data.action === 'liked') {
                    likeBtn.style.backgroundColor = '#ff5959';
                    likeBtn.style.color = 'white';
                } else {
                    likeBtn.style.backgroundColor = '#f0f0f0';
                    likeBtn.style.color = '#333';
                }
            }
        })
        .catch(error => console.error('Error:', error));
        @else
        window.location.href = '{{ route('login') }}';
        @endauth
    });
    
    // Share functionality
    document.querySelector('.share-btn').addEventListener('click', function() {
        const url = window.location.href;
        
        if (navigator.share) {
            navigator.share({
                title: '{{ $thread->title }}',
                text: 'Cek diskusi ini di Forum Tani!',
                url: url
            })
            .catch(error => console.log('Error sharing:', error));
        } else {
            // Fallback for browsers that don't support the Web Share API
            navigator.clipboard.writeText(url)
                .then(() => alert('URL disalin ke clipboard!'))
                .catch(err => console.error('Could not copy text: ', err));
        }
    });
    
    // Report functionality
    document.querySelector('.report-btn').addEventListener('click', function() {
        @auth
        alert('Fitur pelaporan thread akan segera hadir!');
        @else
        window.location.href = '{{ route('login') }}';
        @endauth
    });
</script>

<script>
    $(document).ready(function() {
        $('.like-btn').click(function() {
            let button = $(this);
            let threadId = button.data('thread-id');
    
            $.ajax({
                url: '/forum/' + threadId + '/like',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        // Update the like count
                        button.find('.likes-count').text(response.likes_count);
                        
                        // Optionally update button text or style
                        if (response.action === 'liked') {
                            button.addClass('liked');
                        } else {
                            button.removeClass('liked');
                        }
                    } else {
                        alert('Something went wrong.');
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 401) {
                        alert('You need to be logged in to like this.');
                    } else {
                        alert('An error occurred. Please try again.');
                    }
                }
            });
        });
    });
</script>
    
@endsection