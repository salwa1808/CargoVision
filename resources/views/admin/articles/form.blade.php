@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="h3 fw-bold mb-4">{{ $article->exists ? 'Edit' : 'Tambah' }} Artikel Analisis</h1>
    <form method="POST" action="{{ $article->exists ? route('admin.articles.update', $article) : route('admin.articles.store') }}" class="card card-body shadow-sm">
        @csrf
        @if($article->exists) @method('PUT') @endif
        <div class="row g-3">
            <div class="col-md-8"><label class="form-label">Judul</label><input name="title" value="{{ old('title', $article->title) }}" class="form-control" required></div>
            <div class="col-md-4"><label class="form-label">Kategori</label><input name="category" value="{{ old('category', $article->category ?: 'Supply Chain') }}" class="form-control" required></div>
            <div class="col-12"><label class="form-label">Ringkasan</label><textarea name="summary" class="form-control" rows="2">{{ old('summary', $article->summary) }}</textarea></div>
            <div class="col-12"><label class="form-label">Isi Analisis</label><textarea name="content" class="form-control" rows="12" required>{{ old('content', $article->content) }}</textarea></div>
            <div class="col-md-4"><label class="form-label">Status</label><select name="status" class="form-select"><option @selected(old('status',$article->status)==='Draft')>Draft</option><option @selected(old('status',$article->status)==='Published')>Published</option></select></div>
            <div class="col-md-4 d-flex align-items-end"><label class="form-check"><input type="checkbox" name="featured" value="1" class="form-check-input" @checked(old('featured',$article->featured))> <span class="form-check-label">Featured</span></label></div>
            <div class="col-12 d-flex gap-2"><button class="btn btn-primary">Simpan</button><a href="{{ route('admin.articles.index') }}" class="btn btn-outline-secondary">Batal</a></div>
        </div>
    </form>
</div>
@endsection
