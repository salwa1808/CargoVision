@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">📝 Article Management</h1>
            <p class="text-muted mb-0">Kelola analisis internal yang ditampilkan pada News & Events.</p>
        </div>
        <a href="{{ route('admin.articles.create') }}" class="btn btn-primary">Tambah Artikel</a>
    </div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead><tr><th>Judul</th><th>Kategori</th><th>Status</th><th>Penulis</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                @forelse($articles as $article)
                    <tr>
                        <td><strong>{{ $article->title }}</strong><br><small class="text-muted">{{ $article->summary }}</small></td>
                        <td>{{ $article->category }}</td>
                        <td><span class="badge {{ $article->status === 'Published' ? 'bg-success' : 'bg-secondary' }}">{{ $article->status }}</span></td>
                        <td>{{ $article->author?->name }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.articles.edit', $article) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus artikel ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-5">Belum ada artikel analisis.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $articles->links() }}</div>
</div>
@endsection
