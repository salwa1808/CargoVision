@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-5 mt-2">
        <div>
            <h1 class="h3 fw-bold mb-1" style="letter-spacing: -0.02em;">
                📋 Watchlist Management
            </h1>
            <p class="text-muted fw-medium mb-0">
                Monitor risk summaries, real-time alerts, and economic indicators of your pinned supply chain jurisdictions.
            </p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Quick Add Sidebar/Column -->
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <span>➕ Quick Watchlist Add</span>
                </div>
                <div class="card-body">
                    <p class="text-muted small">
                        Select a tracked jurisdiction to instantly pin it to your surveillance dashboard.
                    </p>
                    <form id="quickAddForm" class="mt-3">
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em;">Select Country</label>
                            <select id="countrySelect" class="form-select" required>
                                <option value="" disabled selected>Choose a country...</option>
                                @foreach($availableCountries as $availCountry)
                                    <option value="{{ $availCountry->id }}">{{ $availCountry->name }} ({{ $availCountry->cca3 }})</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" id="addBtn" class="btn btn-primary w-100 justify-content-center">
                            Add to Watchlist
                        </button>
                    </form>
                </div>
            </div>

            <!-- Watchlist Stats Card -->
            <div class="card shadow-sm">
                <div class="card-header">
                    <span>📊 Watchlist Diagnostics</span>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3 border-bottom pb-2" style="border-color: var(--border-color) !important;">
                        <span class="text-muted">Total Pinned</span>
                        <strong class="text-white">{{ $watchlistItems->count() }}</strong>
                    </div>
                    @php
                        $highCount = $watchlistItems->filter(function($item) {
                            $r = $item->country->riskScores->first();
                            return $r && $r->risk_level == 'High';
                        })->count();
                        $medCount = $watchlistItems->filter(function($item) {
                            $r = $item->country->riskScores->first();
                            return $r && $r->risk_level == 'Medium';
                        })->count();
                        $lowCount = $watchlistItems->filter(function($item) {
                            $r = $item->country->riskScores->first();
                            return $r && $r->risk_level == 'Low';
                        })->count();
                    @endphp
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">🔴 High Risk Areas</span>
                        <span class="badge bg-danger">{{ $highCount }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">🟡 Medium Risk Areas</span>
                        <span class="badge bg-warning text-dark">{{ $medCount }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted small">🟢 Stable / Low Risk</span>
                        <span class="badge bg-success">{{ $lowCount }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Watchlist Items Grid -->
        <div class="col-lg-8">
            @if($watchlistItems->isEmpty())
                <div class="card text-center py-5 shadow-sm border-dashed" style="border: 2px dashed rgba(255,255,255,0.1) !important; background: transparent !important;">
                    <div class="card-body">
                        <div style="font-size: 48px;" class="mb-3">📋</div>
                        <h4 class="fw-bold">Watchlist is Empty</h4>
                        <p class="text-muted mx-auto" style="max-width: 400px;">
                            You are not monitoring any countries yet. Use the selector on the left to add your first supply chain hub.
                        </p>
                    </div>
                </div>
            @else
                <div class="row g-4">
                    @foreach($watchlistItems as $item)
                        @php
                            $country = $item->country;
                            $risk = $country->riskScores->first();
                            $weather = $country->weatherSnapshots->first();
                            $indicator = $country->economicIndicators->first();
                            $exchange = $country->exchangeRates->first();
                            
                            $riskLevel = $risk ? $risk->risk_level : 'None';
                            $riskScoreVal = $risk ? $risk->total_score : 'N/A';
                            
                            // Format GDP
                            $gdp = $indicator ? $indicator->gdp : null;
                            if ($gdp !== null) {
                                if ($gdp >= 1e12) {
                                    $formattedGdp = '$' . number_format($gdp / 1e12, 1) . 'T';
                                } elseif ($gdp >= 1e9) {
                                    $formattedGdp = '$' . number_format($gdp / 1e9, 1) . 'B';
                                } else {
                                    $formattedGdp = '$' . number_format($gdp / 1e6, 1) . 'M';
                                }
                            } else {
                                $formattedGdp = '-';
                            }
                        @endphp
                        <div class="col-md-6 watchlist-card-wrapper" data-country-id="{{ $country->id }}">
                            <div class="card h-100 shadow-sm border-start border-4" 
                                 style="border-left-color: {{ $riskLevel == 'High' ? '#ef4444' : ($riskLevel == 'Medium' ? '#f59e0b' : '#10b981') }} !important;">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2">
                                        @if($country->flag_png)
                                            <img src="{{ $country->flag_png }}" 
                                                 alt="{{ $country->name }} Flag" 
                                                 class="rounded-1 border" 
                                                 style="width: 24px; height: 16px; object-fit: cover; border-color: var(--border-color) !important;">
                                        @endif
                                        <a href="{{ url('/country/' . $country->id) }}" class="fw-bold text-decoration-none text-white hover-underline">
                                            {{ $country->name }}
                                        </a>
                                    </div>
                                    <button class="btn btn-sm btn-outline-danger p-1 border-0 remove-watchlist-btn" 
                                            data-country-id="{{ $country->id }}" 
                                            title="Remove from Watchlist"
                                            style="background: transparent !important; color: #ef4444 !important; font-size: 15px;">
                                        ❌
                                    </button>
                                </div>
                                <div class="card-body">
                                    <!-- Threat level block -->
                                    <div class="d-flex justify-content-between align-items-center mb-3 p-2 rounded-3" style="background: rgba(255,255,255,0.02); border: 1px solid var(--border-color);">
                                        <span class="small text-muted fw-bold">RISK THREAT LEVEL</span>
                                        <div>
                                            @if($riskLevel == 'High')
                                                <span class="badge bg-danger">High ({{ $riskScoreVal }})</span>
                                            @elseif($riskLevel == 'Medium')
                                                <span class="badge bg-warning text-dark">Medium ({{ $riskScoreVal }})</span>
                                            @elseif($riskLevel == 'Low')
                                                <span class="badge bg-success">Low ({{ $riskScoreVal }})</span>
                                            @else
                                                <span class="badge bg-secondary">Unknown</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Weather status -->
                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <div class="p-2 border rounded-3 text-center h-100" style="border-color: var(--border-color) !important; background: rgba(6, 182, 212, 0.02);">
                                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 9px; letter-spacing: 0.05em;">Weather Temp</small>
                                                <strong class="text-white" style="font-size: 13.5px;">
                                                    {{ $weather && $weather->temperature !== null ? $weather->temperature . ' °C' : '-' }}
                                                </strong>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-2 border rounded-3 text-center h-100" style="border-color: var(--border-color) !important; background: rgba(239, 68, 68, 0.02);">
                                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 9px; letter-spacing: 0.05em;">Storm Risk</small>
                                                <strong class="text-white" style="font-size: 13.5px;">
                                                    @if($weather)
                                                        @if($weather->storm_risk == 'high')
                                                            <span class="text-danger">🔴 High</span>
                                                        @elseif($weather->storm_risk == 'medium')
                                                            <span class="text-warning">🟡 Med</span>
                                                        @else
                                                            <span class="text-success">🟢 Low</span>
                                                        @endif
                                                    @else
                                                        -
                                                    @endif
                                                </strong>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Economy & Currency details -->
                                    <table class="table table-borderless table-sm small mb-3 text-muted align-middle">
                                        <tbody>
                                            <tr>
                                                <td>GDP Size:</td>
                                                <td class="text-end text-white fw-semibold">{{ $formattedGdp }}</td>
                                            </tr>
                                            <tr>
                                                <td>Inflation Rate:</td>
                                                <td class="text-end text-white fw-semibold">
                                                    {{ $indicator && $indicator->inflation !== null ? number_format($indicator->inflation, 1) . '%' : '-' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Exchange Rate (USD):</td>
                                                <td class="text-end text-white fw-semibold">
                                                    @if($exchange && $exchange->exchange_rate !== null)
                                                        1 USD = {{ number_format($exchange->exchange_rate, 2) }} {{ $country->currency_code }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <a href="{{ url('/country/' . $country->id) }}" class="btn btn-sm btn-glass w-100 justify-content-center">
                                        View Country Profile
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const quickAddForm = document.getElementById('quickAddForm');
        const countrySelect = document.getElementById('countrySelect');
        const addBtn = document.getElementById('addBtn');

        if (quickAddForm) {
            quickAddForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const countryId = countrySelect.value;
                if (!countryId) return;

                addBtn.disabled = true;
                
                toggleWatchlistGlobal(countryId, function(isWatchlisted, message) {
                    // Success, reload page to refresh the watchlist lists
                    location.reload();
                });
            });
        }

        // Remove watchlist actions
        document.querySelectorAll('.remove-watchlist-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const countryId = this.getAttribute('data-country-id');
                const self = this;

                self.disabled = true;

                toggleWatchlistGlobal(countryId, function(isWatchlisted, message) {
                    if (!isWatchlisted) {
                        // Successfully removed, fade out card
                        const cardWrapper = document.querySelector(`.watchlist-card-wrapper[data-country-id="${countryId}"]`);
                        if (cardWrapper) {
                            cardWrapper.style.transition = 'all 0.3s ease';
                            cardWrapper.style.opacity = '0';
                            cardWrapper.style.transform = 'scale(0.9)';
                            setTimeout(() => {
                                location.reload(); // Reload to refresh grid & available countries list
                            }, 300);
                        } else {
                            location.reload();
                        }
                    } else {
                        self.disabled = false;
                    }
                });
            });
        });
    });
</script>
@endpush
