@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-5 mt-2">

        <div>

            <h1 class="h3 fw-bold mb-1" style="letter-spacing: -0.02em;">
                📰 Supply Chain News Center
            </h1>

            <p class="text-muted fw-medium mb-0">
                Latest updates and global logistics intelligence alerts
            </p>

        </div>

        <a href="/" class="btn btn-outline-light d-inline-flex align-items-center gap-2 fw-semibold" style="border-radius: 12px; padding: 10px 20px; border-color: var(--border-color);">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" x2="5" y1="12" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Back to Dashboard
        </a>

    </div>

    @if($articles->isNotEmpty())
        <div class="mb-5">
            <div class="d-flex justify-content-between align-items-end mb-3">
                <div>
                    <h2 class="h5 fw-bold mb-1">Analisis Internal CargoVision</h2>
                    <small class="text-muted">Analisis yang ditulis dan diterbitkan oleh administrator.</small>
                </div>
            </div>
            <div class="row g-3">
                @foreach($articles as $article)
                    <div class="col-lg-4 col-md-6">
                        <article class="card h-100 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="badge bg-info text-dark">{{ $article->category }}</span>
                                    @if($article->featured)<span class="badge bg-warning text-dark">Featured</span>@endif
                                </div>
                                <h3 class="h6 fw-bold">{{ $article->title }}</h3>
                                <p class="text-muted small mb-3">{{ $article->summary ?: \Illuminate\Support\Str::limit(strip_tags($article->content), 170) }}</p>
                                <details>
                                    <summary class="text-primary fw-semibold" style="cursor:pointer">Baca analisis</summary>
                                    <div class="small text-muted mt-3" style="white-space:pre-line">{{ $article->content }}</div>
                                </details>
                            </div>
                            <div class="card-footer bg-transparent text-muted small">Oleh {{ $article->author?->name }} · {{ $article->created_at->format('d M Y') }}</div>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Filter Card -->
    <div class="card shadow-sm mb-5">

        <div class="card-body">

            <form method="GET">

                <div class="row g-3">

                    <div class="col-md-4">

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Search news titles..."
                            value="{{ request('search') }}">

                    </div>

                    <div class="col-md-3">

                        <select
                            name="country"
                            class="form-select">

                            <option value="">
                                All Countries
                            </option>

                            @foreach($countries as $country)

                                <option
                                    value="{{ $country->id }}"
                                    {{ request('country')==$country->id ? 'selected' : '' }}>

                                    {{ $country->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-3">

                        <select
                            name="sentiment"
                            class="form-select">

                            <option value="">
                                All Sentiments
                            </option>

                            <option value="Positive"
                                {{ request('sentiment')=="Positive" ? "selected":"" }}>
                                😊 Positive
                            </option>

                            <option value="Neutral"
                                {{ request('sentiment')=="Neutral" ? "selected":"" }}>
                                😐 Neutral
                            </option>

                            <option value="Negative"
                                {{ request('sentiment')=="Negative" ? "selected":"" }}>
                                😡 Negative
                            </option>

                        </select>

                    </div>

                    <div class="col-md-2 d-grid">

                        <button class="btn btn-primary fw-bold" style="border-radius: 12px; padding: 10px 16px; background-color: #6366f1; border-color: #6366f1;">

                            🔍 Filter

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <!-- News Card Grid -->
    <div class="row g-4">

        @forelse($news as $item)

            <div class="col-lg-4 col-md-6">

                <div class="card h-100 shadow-sm border d-flex flex-column" style="border-radius: 20px; overflow: hidden; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);">

                    <div class="card-body d-flex flex-column h-100" style="padding: 24px;">

                        <div class="d-flex justify-content-between align-items-center mb-3">

                            <span class="badge bg-primary" style="font-size: 11px !important; padding: 4px 10px !important;">
                                🌍 {{ $item->country->name ?? 'Global' }}
                            </span>

                            @if($item->sentiment=="Positive")

                                <span class="badge bg-success" style="font-size: 11px !important; padding: 4px 10px !important; line-height: 1.2;">
                                    😊 Positive
                                </span>

                            @elseif($item->sentiment=="Negative")

                                <span class="badge bg-danger" style="font-size: 11px !important; padding: 4px 10px !important; line-height: 1.2;">
                                    😡 Negative
                                </span>

                            @else

                                <span class="badge bg-secondary" style="font-size: 11px !important; padding: 4px 10px !important; line-height: 1.2;">
                                    😐 Neutral
                                </span>

                            @endif

                        </div>

                        <h5 class="fw-bold mb-2" style="font-size: 16.5px; line-height: 1.4; color: var(--text-main); display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 46px;">
                            {{ $item->title }}
                        </h5>

                        <p class="text-muted" style="font-size: 13.5px; line-height: 1.6; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden; margin-bottom: 24px;">
                            {{ $item->description }}
                        </p>

                        <div class="mt-auto d-flex justify-content-between align-items-center pt-3 border-top" style="border-top-color: var(--border-color) !important;">

                            <small class="text-muted fw-semibold" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.03em;">
                                📅 {{ $item->created_at->format('d M Y') }}
                            </small>

                            @if($item->url)

                                <a
                                    href="{{ $item->url }}"
                                    target="_blank"
                                    class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1 fw-bold"
                                    style="border-radius: 8px; padding: 6px 12px; font-size: 12.5px; background-color: #6366f1; border-color: #6366f1;">
                                    Read Source
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="7" x2="17" y1="17" y2="7"/><polyline points="7 7 17 7 17 17"/></svg>
                                </a>

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        @empty

            <div class="col-12 text-center py-5">
                <div class="text-muted" style="font-size: 18px; font-weight: 500;">
                    📭 No supply chain updates match your filter criteria.
                </div>
            </div>

        @endforelse

    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-5">

        {{ $news->withQueryString()->links() }}

    </div>

</div>

@endsection
