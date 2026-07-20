@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-5 mt-2">
        <div>
            <h1 class="h3 fw-bold mb-1" style="letter-spacing: -0.02em;">
                🗺️ Countries Registry
            </h1>
            <p class="text-muted fw-medium mb-0">
                Manage global jurisdictions, monitor supply chain risk profiles, and watch key hubs.
            </p>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-5">
                    <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em;">Search Countries</label>
                    <input type="text" id="countrySearch" class="form-control" placeholder="Search by name, capital, region...">
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
                    <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em;">Threat Level</label>
                    <select id="riskFilter" class="form-select">
                        <option value="">All Threats</option>
                        <option value="High">High Threat</option>
                        <option value="Medium">Medium Threat</option>
                        <option value="Low">Low Threat</option>
                        <option value="None">Unknown / No Data</option>
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

    <!-- Countries Table Card -->
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Tracked Countries list</span>
            <span class="text-muted fw-normal small">Showing <span id="displayedCount">{{ $countries->count() }}</span> of {{ $countries->count() }} countries</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--border-color);">
                            <th style="padding-left: 24px;">Name</th>
                            <th>Capital</th>
                            <th>Region</th>
                            <th>Population</th>
                            <th>Threat Level</th>
                            <th style="padding-right: 24px; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="countriesTableBody">
                        @forelse($countries as $country)
                            @php
                                $risk = $country->riskScores->first();
                                $riskLevel = $risk ? $risk->risk_level : 'None';
                                $riskScoreVal = $risk ? $risk->total_score : null;
                                $isWatchlisted = in_array($country->id, $watchlistIds);
                            @endphp
                            <tr class="country-row" 
                                data-name="{{ strtolower($country->name) }}"
                                data-capital="{{ strtolower($country->capital ?? '') }}"
                                data-region="{{ $country->region ?? '' }}"
                                data-risk="{{ $riskLevel }}">
                                
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
                                            <div class="text-muted small">{{ $country->cca3 }}</div>
                                        </div>
                                    </div>
                                </td>
                                
                                <td>{{ $country->capital ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $country->region ?? 'Unknown' }}</span>
                                </td>
                                <td>{{ number_format($country->population) }}</td>
                                <td>
                                    @if($riskLevel == 'High')
                                        <span class="badge bg-danger">High ({{ $riskScoreVal }})</span>
                                    @elseif($riskLevel == 'Medium')
                                        <span class="badge bg-warning text-dark">Medium ({{ $riskScoreVal }})</span>
                                    @elseif($riskLevel == 'Low')
                                        <span class="badge bg-success">Low ({{ $riskScoreVal }})</span>
                                    @else
                                        <span class="badge bg-secondary">No Data</span>
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
                                    No countries found in registration database.
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
        const countrySearch = document.getElementById('countrySearch');
        const regionFilter = document.getElementById('regionFilter');
        const riskFilter = document.getElementById('riskFilter');
        const resetFilters = document.getElementById('resetFilters');
        const displayedCount = document.getElementById('displayedCount');
        const rows = document.querySelectorAll('.country-row');

        function filterTable() {
            const query = countrySearch.value.toLowerCase();
            const selectedRegion = regionFilter.value;
            const selectedRisk = riskFilter.value;
            let visibleCount = 0;

            rows.forEach(row => {
                const name = row.getAttribute('data-name');
                const capital = row.getAttribute('data-capital');
                const region = row.getAttribute('data-region');
                const risk = row.getAttribute('data-risk');

                const matchesSearch = name.includes(query) || capital.includes(query) || region.toLowerCase().includes(query);
                const matchesRegion = !selectedRegion || region === selectedRegion;
                const matchesRisk = !selectedRisk || risk === selectedRisk;

                if (matchesSearch && matchesRegion && matchesRisk) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            displayedCount.textContent = visibleCount;
        }

        countrySearch.addEventListener('input', filterTable);
        regionFilter.addEventListener('change', filterTable);
        riskFilter.addEventListener('change', filterTable);

        resetFilters.addEventListener('click', () => {
            countrySearch.value = '';
            regionFilter.value = '';
            riskFilter.value = '';
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
