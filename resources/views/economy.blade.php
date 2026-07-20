@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-5 mt-2">
        <div>
            <h1 class="h3 fw-bold mb-1" style="letter-spacing: -0.02em;">
                📈 Economic Indicators
            </h1>
            <p class="text-muted fw-medium mb-0">
                Monitor global macroeconomic health, inflation levels, and GDP metrics synced from the World Bank API.
            </p>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-5">
                    <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em;">Search Countries</label>
                    <input type="text" id="economySearch" class="form-control" placeholder="Search by country, currency...">
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em;">Region</label>
                    <select id="regionFilter" class="form-select">
                        <option value="">All Regions</option>
                        @foreach($countries->pluck('region')->unique()->filter()->sort() as $region)
                            <option value="{{ $region }}">{{ $region }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em;">Inflation Status</label>
                    <select id="inflationFilter" class="form-select">
                        <option value="">All Levels</option>
                        <option value="high">High Inflation (> 5%)</option>
                        <option value="stable">Stable Inflation (0% - 5%)</option>
                        <option value="deflation">Deflation (< 0%)</option>
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

    <!-- Economy Indicators Table -->
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Macroeconomic Ledger</span>
            <span class="text-muted fw-normal small">Showing <span id="displayedCount">{{ $countries->count() }}</span> of {{ $countries->count() }} countries</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--border-color);">
                            <th style="padding-left: 24px;">Country</th>
                            <th>GDP (USD)</th>
                            <th>Inflation Rate</th>
                            <th>Indicator Population</th>
                            <th>Reporting Year</th>
                            <th style="padding-right: 24px; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="economyTableBody">
                        @forelse($countries as $country)
                            @php
                                $indicator = $country->economicIndicators->first();
                                $gdp = $indicator ? $indicator->gdp : null;
                                $inflation = $indicator ? $indicator->inflation : null;
                                $year = $indicator ? $indicator->year : null;
                                $pop = $indicator ? $indicator->population : null;

                                // Format GDP
                                if ($gdp !== null) {
                                    if ($gdp >= 1e12) {
                                        $formattedGdp = '$' . number_format($gdp / 1e12, 2) . ' T';
                                    } elseif ($gdp >= 1e9) {
                                        $formattedGdp = '$' . number_format($gdp / 1e9, 2) . ' B';
                                    } elseif ($gdp >= 1e6) {
                                        $formattedGdp = '$' . number_format($gdp / 1e6, 2) . ' M';
                                    } else {
                                        $formattedGdp = '$' . number_format($gdp, 2);
                                    }
                                } else {
                                    $formattedGdp = 'No Data';
                                }

                                // Classify inflation level
                                $inflationCategory = 'unknown';
                                if ($inflation !== null) {
                                    if ($inflation > 5.0) {
                                        $inflationCategory = 'high';
                                    } elseif ($inflation >= 0.0) {
                                        $inflationCategory = 'stable';
                                    } else {
                                        $inflationCategory = 'deflation';
                                    }
                                }

                                $isWatchlisted = in_array($country->id, $watchlistIds);
                            @endphp
                            <tr class="economy-row" 
                                data-name="{{ strtolower($country->name) }}"
                                data-region="{{ $country->region ?? '' }}"
                                data-inflation-category="{{ $inflationCategory }}"
                                data-inflation-val="{{ $inflation ?? 0 }}">
                                
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
                                
                                <td class="fw-semibold">{{ $formattedGdp }}</td>
                                
                                <td>
                                    @if($inflation !== null)
                                        @if($inflationCategory == 'high')
                                            <span class="text-danger fw-bold">📈 {{ number_format($inflation, 2) }}%</span>
                                        @elseif($inflationCategory == 'stable')
                                            <span class="text-success fw-bold">🟢 {{ number_format($inflation, 2) }}%</span>
                                        @else
                                            <span class="text-info fw-bold">📉 {{ number_format($inflation, 2) }}%</span>
                                        @endif
                                    @else
                                        <span class="text-muted">No Data</span>
                                    @endif
                                </td>
                                
                                <td>{{ $pop ? number_format($pop) : '-' }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $year ?? '-' }}</span>
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
                                    No economic indicators found.
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
        const economySearch = document.getElementById('economySearch');
        const regionFilter = document.getElementById('regionFilter');
        const inflationFilter = document.getElementById('inflationFilter');
        const resetFilters = document.getElementById('resetFilters');
        const displayedCount = document.getElementById('displayedCount');
        const rows = document.querySelectorAll('.economy-row');

        function filterTable() {
            const query = economySearch.value.toLowerCase();
            const selectedRegion = regionFilter.value;
            const selectedInflation = inflationFilter.value;
            let visibleCount = 0;

            rows.forEach(row => {
                const name = row.getAttribute('data-name');
                const region = row.getAttribute('data-region');
                const inflationCategory = row.getAttribute('data-inflation-category');

                const matchesSearch = name.includes(query) || region.toLowerCase().includes(query);
                const matchesRegion = !selectedRegion || region === selectedRegion;
                const matchesInflation = !selectedInflation || inflationCategory === selectedInflation;

                if (matchesSearch && matchesRegion && matchesInflation) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            displayedCount.textContent = visibleCount;
        }

        economySearch.addEventListener('input', filterTable);
        regionFilter.addEventListener('change', filterTable);
        inflationFilter.addEventListener('change', filterTable);

        resetFilters.addEventListener('click', () => {
            economySearch.value = '';
            regionFilter.value = '';
            inflationFilter.value = '';
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
