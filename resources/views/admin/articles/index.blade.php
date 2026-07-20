@extends('layouts.app')

@push('styles')
<style>
    /* Dark Glassmorphism Styling */
    .stat-card-glass {
        background: rgba(255, 255, 255, 0.03) !important;
        backdrop-filter: blur(16px) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        border-radius: 16px !important;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        padding: 24px;
        position: relative;
        overflow: hidden;
    }
    .stat-card-glass:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(139, 92, 246, 0.2);
        border-color: rgba(139, 92, 246, 0.2) !important;
    }
    .stat-icon {
        position: absolute;
        right: 20px;
        bottom: 20px;
        font-size: 40px;
        opacity: 0.15;
        color: var(--accent-primary, #a78bfa);
    }
    .stat-value {
        font-size: 32px;
        font-weight: 800;
        color: #ffffff;
        margin-bottom: 4px;
    }
    .stat-title {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: rgba(255, 255, 255, 0.5);
    }

    .table-container {
        background: rgba(255, 255, 255, 0.02) !important;
        backdrop-filter: blur(16px) !important;
        border: 1px solid rgba(255, 255, 255, 0.06) !important;
        border-radius: 16px;
        padding: 24px;
    }

    .admin-table {
        width: 100%;
        color: #ffffff;
        border-collapse: separate;
        border-spacing: 0 8px;
    }
    .admin-table th {
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        padding: 12px;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 11px;
        color: rgba(255, 255, 255, 0.4);
        letter-spacing: 0.5px;
    }
    .admin-table td {
        padding: 12px;
        vertical-align: middle;
        background: rgba(255, 255, 255, 0.01);
        border-top: 1px solid rgba(255, 255, 255, 0.03);
        border-bottom: 1px solid rgba(255, 255, 255, 0.03);
    }
    .admin-table tr:hover td {
        background: rgba(139, 92, 246, 0.04);
        border-color: rgba(139, 92, 246, 0.15);
    }
    .admin-table tr td:first-child {
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
        border-left: 1px solid rgba(255, 255, 255, 0.03);
    }
    .admin-table tr td:last-child {
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
        border-right: 1px solid rgba(255, 255, 255, 0.03);
    }

    /* Thumbnail Box */
    .thumb-preview-box {
        width: 60px;
        height: 40px;
        border-radius: 6px;
        background: rgba(255, 255, 255, 0.05);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .thumb-preview-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Modal Glass styling */
    .modal-content-glass {
        background: rgba(15, 8, 30, 0.95) !important;
        backdrop-filter: blur(20px) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        border-radius: 20px !important;
        color: #ffffff;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
    }
    .modal-header-glass {
        border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
    }
    .modal-footer-glass {
        border-top: 1px solid rgba(255, 255, 255, 0.08) !important;
    }

    .toast-container {
        position: fixed;
        top: 24px;
        right: 24px;
        z-index: 1060;
    }
    .custom-toast {
        background: rgba(15, 8, 30, 0.9) !important;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(139, 92, 246, 0.3) !important;
        border-radius: 12px !important;
        color: #ffffff;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">

    <!-- Toast Notifications -->
    <div class="toast-container">
        @if(session('success'))
            <div class="toast custom-toast show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-body d-flex align-items-center">
                    <span class="me-2">✅</span>
                    <span>{{ session('success') }}</span>
                    <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif
    </div>

    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 class="h3 text-white mb-1 fw-bold">📰 Article Management</h1>
            <p class="text-muted small mb-0">Publish geopolitical news, logistics warnings, or economic updates.</p>
        </div>
        <a href="{{ route('articles.create') }}" class="btn btn-primary px-4 fw-bold" style="border-radius: 12px;">
            ➕ New Article
        </a>
    </div>

    <!-- Statistics Row -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card-glass">
                <div class="stat-value">{{ $totalArticles }}</div>
                <div class="stat-title">Total Articles</div>
                <div class="stat-icon">📰</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card-glass">
                <div class="stat-value text-success">{{ $publishedCount }}</div>
                <div class="stat-title">Published</div>
                <div class="stat-icon">🟢</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card-glass">
                <div class="stat-value text-warning">{{ $draftCount }}</div>
                <div class="stat-title">Draft</div>
                <div class="stat-icon">📝</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card-glass">
                <div class="stat-value text-primary">{{ $featuredCount }}</div>
                <div class="stat-title">Featured</div>
                <div class="stat-icon">⭐️</div>
            </div>
        </div>
    </div>

    <!-- Articles Table Container -->
    <div class="table-container">
        <!-- Filter and Search controls -->
        <form action="{{ route('articles.index') }}" method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search title or summary..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select" onchange="this.form.submit()">
                    <option value="all" {{ request('category') == 'all' ? 'selected' : '' }}>All Categories</option>
                    <option value="Geopolitics" {{ request('category') == 'Geopolitics' ? 'selected' : '' }}>Geopolitics</option>
                    <option value="Logistics" {{ request('category') == 'Logistics' ? 'selected' : '' }}>Logistics</option>
                    <option value="Weather Warning" {{ request('category') == 'Weather Warning' ? 'selected' : '' }}>Weather Warning</option>
                    <option value="Economic" {{ request('category') == 'Economic' ? 'selected' : '' }}>Economic</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                    <option value="Published" {{ request('status') == 'Published' ? 'selected' : '' }}>Published</option>
                    <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-outline-light w-100 fw-bold">🔍 Filter</button>
                <a href="{{ route('articles.index') }}" class="btn btn-outline-danger px-3">🔄</a>
            </div>
        </form>

        <!-- Data Table -->
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Thumbnail</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Author</th>
                        <th>Status</th>
                        <th>Featured</th>
                        <th>Views</th>
                        <th>Publish Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($articles as $article)
                        <tr>
                            <td>
                                <div class="thumb-preview-box">
                                    @if($article->thumbnail)
                                        <img src="{{ $article->thumbnail }}" alt="Thumbnail">
                                    @else
                                        <span style="font-size: 14px;">🖼️</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <strong class="text-white" style="max-width: 250px; display: inline-block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ $article->title }}
                                </strong>
                            </td>
                            <td>{{ $article->category }}</td>
                            <td>{{ $article->author ? $article->author->name : 'System' }}</td>
                            <td>
                                <span class="badge {{ $article->status === 'Published' ? 'bg-success' : 'bg-warning' }}">
                                    {{ $article->status }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $article->featured ? 'bg-primary' : 'bg-secondary' }}">
                                    {{ $article->featured ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td>{{ $article->views }}</td>
                            <td>{{ $article->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('articles.show', $article->id) }}" class="btn btn-sm btn-outline-light" title="Preview Article">👁️</a>
                                    <a href="{{ route('articles.edit', $article->id) }}" class="btn btn-sm btn-outline-primary" title="Edit Article">✏️</a>
                                    <button type="button" class="btn btn-sm btn-outline-danger delete-article-btn" 
                                            data-id="{{ $article->id }}" 
                                            data-title="{{ $article->title }}"
                                            title="Delete Article">🗑️</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">No articles found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $articles->links() }}
        </div>
    </div>
</div>

<!-- ============ DELETE CONFIRMATION MODAL ============ -->
<div class="modal fade" id="deleteArticleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-glass">
            <div class="modal-header modal-header-glass border-0">
                <h5 class="modal-title text-white fw-bold">🗑 Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4 text-center">
                <span style="font-size: 48px;">⚠️</span>
                <p class="text-white mt-3 fw-bold" id="deleteMessage">Apakah Anda yakin ingin menghapus artikel ini?</p>
                <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer modal-footer-glass border-0 justify-content-center">
                <form action="" method="POST" id="deleteArticleForm" class="w-100 d-flex gap-2">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-outline-light w-50" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger w-50 fw-bold">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Automatically hide toast
        setTimeout(function() {
            document.querySelectorAll('.toast').forEach(function(toastEl) {
                const toast = new bootstrap.Toast(toastEl);
                toast.hide();
            });
        }, 3000);

        // Delete Article Event
        document.querySelectorAll('.delete-article-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const title = this.getAttribute('data-title');

                const form = document.getElementById('deleteArticleForm');
                form.action = `/admin/articles/${id}`;

                document.getElementById('deleteMessage').innerHTML = `Apakah Anda yakin ingin menghapus artikel <strong>${title}</strong>?`;

                new bootstrap.Modal(document.getElementById('deleteArticleModal')).show();
            });
        });
    });
</script>
@endpush
