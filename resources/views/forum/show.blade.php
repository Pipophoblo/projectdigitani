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
    transition: all 0.3s ease;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    border: none;
    background: transparent;
    color: #333;
    padding: 5px 10px;
    border-radius: 5px;
    font-family: inherit;
    font-size: inherit;
    }
    
    .like-btn:hover {
    background-color: #f0f0f0;
}

/* Active/liked state */
.like-btn.liked {
    color: #ff5959;
    font-weight: bold;
}

.like-btn.liked:hover {
    background-color: #ffeeee;
}

/* Heart animation */
@keyframes heartBeat {
    0% { transform: scale(1); }
    14% { transform: scale(1.3); }
    28% { transform: scale(1); }
    42% { transform: scale(1.3); }
    70% { transform: scale(1); }
}

.like-btn.liked .heart-icon, 
.like-btn:active .heart-icon {
    animation: heartBeat 1s ease-in-out;
}

/* Disable text selection on button */
.like-btn {
    user-select: none;
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
        <button class="action-btn like-btn {{ $isLiked ? 'liked' : '' }}" data-thread-id="{{ $thread->id }}">
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
    $(document).ready(function() {
        $('.like-btn').click(function() {
            @auth
                let button = $(this);
                let threadId = button.data('thread-id');
                
                console.log('Like button clicked for thread: ' + threadId);
                
                $.ajax({
                    url: '/forum/' + threadId + '/like',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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
                            alert('Terjadi kesalahan.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                        
                        if (xhr.status === 401) {
                            alert('Silakan login terlebih dahulu untuk menyukai thread.');
                            window.location.href = '{{ route('login') }}';
                        } else {
                            alert('Terjadi kesalahan. Silakan coba lagi.');
                        }
                    }
                });
            @else
                alert('Silakan login terlebih dahulu untuk menyukai thread.');
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
    });
</script>
@endsection