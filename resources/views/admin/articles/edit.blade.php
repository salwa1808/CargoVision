@extends('layouts.app')

@push('styles')
<!-- Include Quill stylesheet -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<style>
    .editor-card {
        background: rgba(255, 255, 255, 0.02) !important;
        backdrop-filter: blur(16px) !important;
        border: 1px solid rgba(255, 255, 255, 0.06) !important;
        border-radius: 20px !important;
        padding: 32px;
    }
    .thumb-upload-area {
        border: 2px dashed rgba(255, 255, 255, 0.15);
        border-radius: 12px;
        padding: 30px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        background: rgba(255, 255, 255, 0.01);
    }
    .thumb-upload-area:hover {
        border-color: var(--accent-primary, #a78bfa);
        background: rgba(139, 92, 246, 0.03);
    }
    .thumb-preview {
        max-width: 100%;
        max-height: 200px;
        border-radius: 8px;
        object-fit: cover;
        margin-top: 15px;
    }

    /* Customize Quill Editor to match dark theme */
    .ql-toolbar.ql-snow {
        background: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }
    .ql-container.ql-snow {
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        background: rgba(255, 255, 255, 0.02) !important;
        border-bottom-left-radius: 12px;
        border-bottom-right-radius: 12px;
        min-height: 250px;
        color: #ffffff;
        font-family: inherit;
    }
    .ql-snow .ql-stroke {
        stroke: rgba(255, 255, 255, 0.6) !important;
    }
    .ql-snow .ql-fill {
        fill: rgba(255, 255, 255, 0.6) !important;
    }
    .ql-snow .ql-picker {
        color: rgba(255, 255, 255, 0.6) !important;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <!-- Back Navigation -->
    <div class="mb-4">
        <a href="{{ route('articles.index') }}" class="btn btn-outline-light px-3 fw-bold" style="border-radius: 12px;">
            ⬅️ Kembali ke Daftar Artikel
        </a>
    </div>

    <!-- Title Header -->
    <div class="mb-4">
        <h1 class="h3 text-white mb-1 fw-bold">✏️ Edit Article</h1>
        <p class="text-muted small">Update and modify existing supply chain news.</p>
    </div>

    <form action="{{ route('articles.update', $article->id) }}" method="POST" id="editArticleForm">
        @csrf
        @method('PUT')
        <div class="row g-4">
            <!-- Left inputs panel -->
            <div class="col-lg-8">
                <div class="editor-card">
                    <!-- Title & Slug -->
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold text-uppercase">Article Title</label>
                        <input type="text" name="title" id="titleInput" class="form-control" placeholder="Enter title..." value="{{ $article->title }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold text-uppercase">Slug Preview</label>
                        <input type="text" id="slugPreview" class="form-control" value="{{ $article->slug }}" readonly style="opacity: 0.5;">
                    </div>

                    <!-- Category & Status & Featured -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label text-muted small fw-bold text-uppercase">Category</label>
                            <select name="category" class="form-select" required>
                                <option value="Geopolitics" {{ $article->category === 'Geopolitics' ? 'selected' : '' }}>Geopolitics</option>
                                <option value="Logistics" {{ $article->category === 'Logistics' ? 'selected' : '' }}>Logistics</option>
                                <option value="Weather Warning" {{ $article->category === 'Weather Warning' ? 'selected' : '' }}>Weather Warning</option>
                                <option value="Economic" {{ $article->category === 'Economic' ? 'selected' : '' }}>Economic</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted small fw-bold text-uppercase">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="Draft" {{ $article->status === 'Draft' ? 'selected' : '' }}>Draft</option>
                                <option value="Published" {{ $article->status === 'Published' ? 'selected' : '' }}>Published</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted small fw-bold text-uppercase">Featured Article</label>
                            <select name="featured" class="form-select" required>
                                <option value="No" {{ !$article->featured ? 'selected' : '' }}>No</option>
                                <option value="Yes" {{ $article->featured ? 'selected' : '' }}>Yes</option>
                            </select>
                        </div>
                    </div>

                    <!-- Summary -->
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold text-uppercase">Summary / Excerpt</label>
                        <textarea name="summary" class="form-control" rows="2" placeholder="Brief summary of the article...">{{ $article->summary }}</textarea>
                    </div>

                    <!-- Content (Rich Text) -->
                    <div class="mb-4">
                        <label class="form-label text-muted small fw-bold text-uppercase">Article Content</label>
                        <div id="editor"></div>
                        <input type="hidden" name="content" id="contentInput">
                    </div>
                </div>
            </div>

            <!-- Right metadata panel -->
            <div class="col-lg-4">
                <!-- Thumbnail -->
                <div class="editor-card mb-4">
                    <h5 class="text-white fw-bold mb-3">🖼️ Article Thumbnail</h5>
                    <div class="thumb-upload-area" onclick="document.getElementById('thumbnailFileInput').click()">
                        <span style="font-size: 28px;">📷</span>
                        <p class="text-muted small mb-0 mt-2">Click to change thumbnail image</p>
                        <input type="file" id="thumbnailFileInput" accept="image/*" class="d-none">
                        <input type="hidden" name="thumbnail" id="thumbnailBase64">
                    </div>
                    <div class="text-center">
                        <img src="{{ $article->thumbnail }}" id="thumbPreview" class="thumb-preview {{ $article->thumbnail ? '' : 'd-none' }}" alt="Thumbnail Preview">
                    </div>
                </div>

                <!-- SEO Settings -->
                <div class="editor-card">
                    <h5 class="text-white fw-bold mb-3">🔍 SEO Configuration</h5>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold text-uppercase">Meta Title</label>
                        <input type="text" name="meta_title" class="form-control" placeholder="Meta title..." value="{{ $article->meta_title }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold text-uppercase">Meta Description</label>
                        <textarea name="meta_description" class="form-control" rows="3" placeholder="Meta description...">{{ $article->meta_description }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold text-uppercase">Keywords</label>
                        <input type="text" name="meta_keywords" class="form-control" placeholder="supply, chain, risk..." value="{{ $article->meta_keywords }}">
                    </div>
                </div>

                <!-- Action Button -->
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold" style="border-radius: 12px;">
                        💾 Update Article
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<!-- Include Quill JS library -->
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Quill Editor
        const quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    ['link', 'image'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['clean']
                ]
            }
        });

        // Set initial HTML content of Quill
        const initialContent = `{!! $article->content !!}`;
        quill.root.innerHTML = initialContent;

        // Form submission sync
        const form = document.getElementById('editArticleForm');
        form.addEventListener('submit', function() {
            // Retrieve Quill content HTML
            const content = quill.root.innerHTML;
            document.getElementById('contentInput').value = content;
        });

        // Slug Auto Generator logic
        const titleInput = document.getElementById('titleInput');
        const slugPreview = document.getElementById('slugPreview');

        titleInput.addEventListener('input', function() {
            const text = this.value;
            const slug = text.toLowerCase()
                             .replace(/[^a-z0-9\s-]/g, '')
                             .replace(/\s+/g, '-')
                             .replace(/-+/g, '-');
            slugPreview.value = slug;
        });

        // Thumbnail file handler
        const thumbInput = document.getElementById('thumbnailFileInput');
        thumbInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(evt) {
                const base64 = evt.target.result;
                document.getElementById('thumbnailBase64').value = base64;
                const preview = document.getElementById('thumbPreview');
                preview.src = base64;
                preview.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        });
    });
</script>
@endpush
