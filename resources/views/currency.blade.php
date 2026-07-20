@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-5 mt-2">
        <div>
            <h1 class="h3 fw-bold mb-1" style="letter-spacing: -0.02em;">
                💵 Currency & Exchange Rates
            </h1>
            <p class="text-muted fw-medium mb-0">
                Monitor global currency profiles and USD conversion rates synced from open.er-api.com.
            </p>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em;">Search Currency</label>
                    <input type="text" id="currencySearch" class="form-control" placeholder="Search by country, currency name, or code...">
                </div>
                <div class="col-md-5">
                    <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em;">Region</label>
                    <select id="regionFilter" class="form-select">
                        <option value="">All Regions</option>
                        @foreach($countries->pluck('region')->unique()->filter()->sort() as $region)
                            <option value="{{ $region }}">{{ $region }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button id="resetFilters" class="btn btn-outline-light w-100 justify-content-center" title="Reset Filters">
                        🔄
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Currency Table Card -->
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Exchange Rates Ledger (Base: USD)</span>
            <span class="text-muted fw-normal small">Showing <span id="displayedCount">{{ $countries->count() }}</span> of {{ $countries->count() }} countries</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--border-color);">
                            <th style="padding-left: 24px;">Country</th>
                            <th>Currency Name</th>
                            <th>Code</th>
                            <th>Symbol</th>
                            <th>Exchange Rate (per USD)</th>
                            <th style="padding-right: 24px; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="currencyTableBody">
                        @forelse($countries as $country)
                            @php
                                $rateRecord = $country->exchangeRates->first();
                                $rate = $rateRecord ? $rateRecord->exchange_rate : null;
                                $isWatchlisted = in_array($country->id, $watchlistIds);
                            @endphp
                            <tr class="currency-row" 
                                data-name="{{ strtolower($country->name) }}"
                                data-region="{{ $country->region ?? '' }}"
                                data-currency-name="{{ strtolower($country->currency_name ?? '') }}"
                                data-currency-code="{{ strtolower($country->currency_code ?? '') }}">
                                
                                <td style="padding-left: 24px;">
                                    <div class="d-flex align-items-center gap-3">
                                        @if($country->flag_png)
                                            <img src="{{ $country->flag_png }}" 
                                                 alt="{{ $country->name }} Flag" 
                                                 class="rounded-1 border" 
                                                 style="width: 32px; height: 20px; object-fit: cover; border-color: var(--border-color) !important;">
                                        @else
                                            <div class="rounded-1 border d-flex align-items-center justify-content-center" 
                                                 style="width: 32px; height: 20px; font-size: 10px; background: rgba(255,255,255,0.05); border-color: var(--border-color) !important;">
                                                🏳️
                                            </div>
                                        @endif
                                        <div>
                                            <a href="{{ url('/country/' . $country->id) }}" class="fw-bold text-decoration-none text-white hover-underline">
                                                {{ $country->name }}
                                            </a>
                                            <div class="text-muted small">{{ $country->region ?? 'Unknown' }}</div>
                                        </div>
                                    </div>
                                </td>
                                
                                <td>{{ $country->currency_name ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-secondary fw-semibold">{{ $country->currency_code ?? '-' }}</span>
                                </td>
                                <td class="fw-bold" style="font-size: 15px;">{{ $country->currency_symbol ?? '-' }}</td>
                                
                                <td class="fw-bold text-white">
                                    @if($rate !== null)
                                        1 USD = <span style="color: #a78bfa;">{{ number_format($rate, 4) }}</span> {{ $country->currency_code }}
                                    @else
                                        <span class="text-muted fw-normal">No Data</span>
                                    @endif
                                </td>
                                
                                <td style="padding-right: 24px; text-align: right;">
                                    <div class="d-inline-flex gap-2">
                                        <!-- Watchlist Toggle Button -->
                                        <button class="btn btn-sm btn-glass toggle-watchlist-btn" 
                                                data-country-id="{{ $country->id }}" 
                                                title="{{ $isWatchlisted ? 'Remove from Watchlist' : 'Add to Watchlist' }}"
                                                style="padding: 6px 10px !important;">
                                            <span class="star-icon" style="color: {{ $isWatchlisted ? '#fbbf24' : 'rgba(255,255,255,0.3)' }}; font-size: 15px;">
                                                {{ $isWatchlisted ? '★' : '☆' }}
                                            </span>
                                        </button>
                                        <!-- View Detail Button -->
                                        <a href="{{ url('/country/' . $country->id) }}" class="btn btn-sm btn-glass" style="padding: 6px 12px !important;">
                                            Profile
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    No currency data found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const currencySearch = document.getElementById('currencySearch');
        const regionFilter = document.getElementById('regionFilter');
        const resetFilters = document.getElementById('resetFilters');
        const displayedCount = document.getElementById('displayedCount');
        const rows = document.querySelectorAll('.currency-row');

        function filterTable() {
            const query = currencySearch.value.toLowerCase();
            const selectedRegion = regionFilter.value;
            let visibleCount = 0;

            rows.forEach(row => {
                const name = row.getAttribute('data-name');
                const region = row.getAttribute('data-region');
                const currencyName = row.getAttribute('data-currency-name');
                const currencyCode = row.getAttribute('data-currency-code');

                const matchesSearch = name.includes(query) || 
                                     region.toLowerCase().includes(query) || 
                                     currencyName.includes(query) || 
                                     currencyCode.includes(query);
                                     
                const matchesRegion = !selectedRegion || region === selectedRegion;

                if (matchesSearch && matchesRegion) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            displayedCount.textContent = visibleCount;
        }

        currencySearch.addEventListener('input', filterTable);
        regionFilter.addEventListener('change', filterTable);

        resetFilters.addEventListener('click', () => {
            currencySearch.value = '';
            regionFilter.value = '';
            filterTable();
        });

        // Watchlist Toggle Logic
        document.querySelectorAll('.toggle-watchlist-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const countryId = this.getAttribute('data-country-id');
                const star = this.querySelector('.star-icon');
                const self = this;

                // Disable during request
                self.disabled = true;

                toggleWatchlistGlobal(countryId, function(isWatchlisted, message) {
                    self.disabled = false;
                    if (isWatchlisted) {
                        star.textContent = '★';
                        star.style.color = '#fbbf24';
                        self.setAttribute('title', 'Remove from Watchlist');
                    } else {
                        star.textContent = '☆';
                        star.style.color = 'rgba(255,255,255,0.3)';
                        self.setAttribute('title', 'Add to Watchlist');
                    }
                });
            });
        });
    });
</script>
@endpush
