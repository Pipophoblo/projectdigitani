@extends('layouts.app')

@section('title', 'Edit Article - IPB Digitani')

@section('styles')
<style>
    .form-container {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        max-width: 900px;
        margin: 0 auto;
    }
    
    .form-title {
        font-size: 28px;
        margin-bottom: 20px;
        color: #333;
        text-align: center;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
    }
    
    .form-control {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        font-size: 16px;
        transition: border-color 0.3s;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #6e8efb;
        box-shadow: 0 0 0 2px rgba(110, 142, 251, 0.2);
    }
    
    textarea.form-control {
        min-height: 300px;
        resize: vertical;
    }
    
    .form-buttons {
        display: flex;
        justify-content: space-between;
        margin-top: 30px;
    }
    
    .btn {
        padding: 12px 25px;
        border-radius: 30px;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        border: none;
        transition: all 0.3s;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #6e8efb, #a777e3);
        color: white;
    }
    
    .btn-secondary {
        background: rgba(0, 0, 0, 0.05);
        color: #333;
    }
    
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .btn-draft {
        background: rgba(0, 0, 0, 0.05);
        color: #333;
    }
    
    .btn-submit {
        background: linear-gradient(135deg, #6e8efb, #a777e3);
        color: white;
    }
    
    .current-image {
        max-width: 300px;
        margin: 10px 0;
        border-radius: 8px;
    }
    
    .invalid-feedback {
        color: #e74c3c;
        font-size: 14px;
        margin-top: 5px;
    }
    
    .is-invalid {
        border-color: #e74c3c;
    }
    
    .status-badge {
        display: inline-block;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 14px;
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
    <div class="form-container">
        <h1 class="form-title">
            Edit Article 
            <span class="status-badge status-{{ $article->status }}">{{ ucfirst($article->status) }}</span>
        </h1>
        
        <form action="{{ route('articles.update', $article->slug) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $article->title) }}" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="image" class="form-label">Featured Image</label>
                @if($article->image)
    <div>
        <img src="{{ Storage::disk('digitani')->url($article->image) }}" alt="Current Image" class="current-image">
        <p>Upload a new image to replace the current one</p>
    </div>
@endif

                <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror">
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="summary" class="form-label">Summary (Optional)</label>
                <textarea name="summary" id="summary" class="form-control @error('summary') is-invalid @enderror" rows="3">{{ old('summary', $article->summary) }}</textarea>
                @error('summary')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="content" class="form-label">Content</label>
                <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror" required>{{ old('content', $article->content) }}</textarea>
                @error('content')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="keywords" class="form-label">Keywords (Separate with commas)</label>
                <input type="text" name="keywords" id="keywords" class="form-control @error('keywords') is-invalid @enderror" value="{{ old('keywords', $article->keywords) }}">
                @error('keywords')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            @if(auth()->user()->isAdmin())
                <div class="form-group">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                        <option value="draft" {{ $article->status == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="pending" {{ $article->status == 'pending' ? 'selected' : '' }}>Pending Review</option>
                        <option value="published" {{ $article->status == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="rejected" {{ $article->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    @error('status')
<div class="invalid-feedback"></div>
@enderror
                </div>
            @endif
            
            <div class="form-buttons">
                <a href="{{ route('articles.show', $article->slug) }}" class="btn btn-secondary">Cancel</a>
                <div>
                    @if(!auth()->user()->isAdmin())
                        <button type="submit" name="submit_type" value="draft" class="btn btn-draft">Save as Draft</button>
                    @endif
                    <button type="submit" class="btn btn-submit">{{ auth()->user()->isAdmin() ? 'Update Article' : 'Submit for Review' }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection