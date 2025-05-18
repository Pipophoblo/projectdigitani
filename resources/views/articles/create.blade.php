@extends('layouts.app')

@section('title', 'Create New Article - IPB Digitani')

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
    
    .invalid-feedback {
        color: #e74c3c;
        font-size: 14px;
        margin-top: 5px;
    }
    
    .is-invalid {
        border-color: #e74c3c;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="form-container">
        <h1 class="form-title">Create New Article</h1>
        
        <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="image" class="form-label">Featured Image</label>
                <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror">
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="summary" class="form-label">Summary (Optional)</label>
                <textarea name="summary" id="summary" class="form-control @error('summary') is-invalid @enderror" rows="3">{{ old('summary') }}</textarea>
                @error('summary')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="content" class="form-label">Content</label>
                <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror" required>{{ old('content') }}</textarea>
                @error('content')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="keywords" class="form-label">Keywords (Separate with commas)</label>
                <input type="text" name="keywords" id="keywords" class="form-control @error('keywords') is-invalid @enderror" value="{{ old('keywords') }}">
                @error('keywords')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-buttons">
                <a href="{{ route('articles.index') }}" class="btn btn-secondary">Cancel</a>
                <div>
                    @if(!auth()->user()->isAdmin())
                        <button type="submit" name="submit_type" value="draft" class="btn btn-draft">Save as Draft</button>
                    @endif
                    <button type="submit" class="btn btn-submit">{{ auth()->user()->isAdmin() ? 'Publish' : 'Submit for Review' }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection