@extends('layouts.app')

@section('title', 'Buat Thread Baru - IPB Digitani')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/forumtani.css') }}">
<style>
    .form-container {
        max-width: 800px;
        margin: 30px auto;
        background-color: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }
    
    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
    }
    
    textarea.form-control {
        min-height: 200px;
    }
    
    .btn-submit {
        padding: 10px 20px;
        background-color: #2b4eff;
        border: none;
        color: white;
        font-weight: bold;
        border-radius: 5px;
        cursor: pointer;
    }
    
    .btn-back {
        padding: 10px 20px;
        background-color: #6c757d;
        border: none;
        color: white;
        font-weight: bold;
        border-radius: 5px;
        cursor: pointer;
        margin-right: 10px;
    }
</style>
@endsection

@section('content')
<div class="form-container">
    <h1>Buat Thread Baru</h1>
    
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    
    <form action="{{ route('forum.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="title" class="form-label">Judul</label>
            <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}" required>
        </div>
        
        <div class="form-group">
            <label for="category_id" class="form-label">Kategori</label>
            <select id="category_id" name="category_id" class="form-control" required>
                <option value="">Pilih Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group">
            <label for="content" class="form-label">Isi Thread</label>
            <textarea id="content" name="content" class="form-control" required>{{ old('content') }}</textarea>
        </div>
        
        <div class="buttons">
            <a href="{{ route('forum.index') }}" class="btn-back">Kembali</a>
            <button type="submit" class="btn-submit">Posting Thread</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<!-- You could add a rich text editor here if needed -->
<script>
    // Validate form before submission
    document.querySelector('form').addEventListener('submit', function(e) {
        const title = document.getElementById('title').value.trim();
        const content = document.getElementById('content').value.trim();
        const category = document.getElementById('category_id').value;
        
        if (!title || !content || !category) {
            e.preventDefault();
            alert('Semua kolom harus diisi!');
        }
    });
</script>
@endsection