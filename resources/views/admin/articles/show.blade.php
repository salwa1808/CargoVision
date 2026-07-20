@extends('layouts.app')

@push('styles')
<style>
    .article-preview-card {
        background: rgba(255, 255, 255, 0.02) !important;
        backdrop-filter: blur(16px) !important;
        border: 1px solid rgba(255, 255, 255, 0.06) !important;
        border-radius: 20px !important;
        padding: 40px;
    }
    .article-meta {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.4);
        font-weight: 600;
        margin-bottom: 24px;
        display: flex;
        gap: 16px;
        align-items: center;
        flex-wrap: wrap;
    }
    .article-thumbnail-large {
        width: 100%;
        max-height: 400px;
        border-radius: 12px;
        object-fit: cover;
        margin-bottom: 30px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .article-body-content {
        color: rgba(255, 255, 255, 0.85);
        font-size: 16px;
        line-height: 1.8;
    }
    .article-body-content h1, .article-body-content h2, .article-body-content h3 {
        color: #ffffff;
        font-weight: 700;
        margin-top: 30px;
        margin-bottom: 15px;
    }
    .article-body-content p {
        margin-bottom: 20px;
    }
    .article-body-content img {
        max-width: 100%;
        border-radius: 8px;
        margin: 20px 0;
    }
    .related-card {
        background: rgba(255, 255, 255, 0.01) !important;
        border: 1px solid rgba(255, 255, 255, 0.04) !important;
        border-radius: 12px !important;
        transition: transform 0.3s;
    }
    .related-card:hover {
        transform: translateY(-2px);
        border-color: rgba(139, 92, 246, 0.15) !important;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <!-- Back Navigation -->
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <a href="{{ route('articles.index') }}" class="btn btn-outline-light px-3 fw-bold" style="border-radius: 12px;">
            ⬅️ Kembali ke Daftar Artikel
        </a>
        <a href="{{ route('articles.edit', $article->id) }}" class="btn btn-primary px-4 fw-bold" style="border-radius: 12px;">
            ✏️ Edit Article
        </a>
    </div>

    <div class="row g-4">
        <!-- Main Article Preview -->
        <div class="col-lg-8">
            <div class="article-preview-card">
                <!-- Title -->
                <h1 class="text-white fw-bold mb-3" style="font-size: 32px; line-height: 1.3;">
                    {{ $article->title }}
                </h1>

                <!-- Meta Details -->
                <div class="article-meta">
                    <span>📂 <strong class="text-primary">{{ $article->category }}</strong></span>
                    <span>👤 Author: <strong>{{ $article->author ? $article->author->name : 'System' }}</strong></span>
                    <span>📅 Date: <strong>{{ $article->created_at->format('d M Y, H:i') }}</strong></span>
                    <span>👁️ Views: <strong>{{ $article->views }}</strong></span>
                    @if($article->featured)
                        <span class="badge bg-primary">⭐ Featured</span>
                    @endif
                </div>

                <!-- Thumbnail -->
                @if($article->thumbnail)
                    <img src="{{ $article->thumbnail }}" class="article-thumbnail-large" alt="Thumbnail">
                @endif

                <!-- Content HTML -->
                <div class="article-body-content">
                    {!! $article->content !!}
                </div>
            </div>
        </div>

        <!-- Sidebar Related Articles -->
        <div class="col-lg-4">
            <div class="article-preview-card">
                <h5 class="text-white fw-bold mb-4">📰 Related Risk News</h5>

                @forelse($relatedArticles as $related)
                    <div class="card related-card mb-3">
                        <div class="card-body p-3">
                            <span class="text-primary small fw-bold d-block mb-1">{{ $related->category }}</span>
                            <h6 class="text-white mb-2" style="font-size: 14px; font-weight: 700; line-height: 1.4;">
                                <a href="{{ route('articles.show', $related->id) }}" class="text-decoration-none text-white">
                                    {{ $related->title }}
                                </a>
                            </h6>
                            <small class="text-muted">{{ $related->created_at->format('d M Y') }}</small>
                        </div>
                    </div>
                @empty
                    <p class="text-muted small">No related articles found in this category.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
