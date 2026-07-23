@extends('layouts.app')

@section('content')

@php
    $latestWeather = $country->weatherSnapshots()->latest()->first();
    $historicalWeather = $country->weatherSnapshots()->latest()->skip(1)->take(5)->get();
    $isWatchlisted = $isWatchlisted ?? false;
@endphp

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-5 mt-2">
        <div>
            <h1 class="h3 fw-bold mb-1" style="letter-spacing: -0.02em;">
                📍 Country Profile
            </h1>
            <p class="text-muted fw-medium mb-0">
                Detailed supply chain indicators & threat trends for {{ $country->name }}
            </p>
        </div>
        <div class="d-flex gap-2">
            <!-- Watchlist Toggle Button -->
            <button id="toggleWatchlistBtn" class="btn btn-glass d-inline-flex align-items-center gap-2 fw-semibold" 
                    data-country-id="{{ $country->id }}"
                    title="{{ $isWatchlisted ? 'Remove from Watchlist' : 'Add to Watchlist' }}">
                <span id="starIcon" style="color: {{ $isWatchlisted ? '#fbbf24' : 'rgba(255,255,255,0.4)' }}; font-size: 16px;">
                    {{ $isWatchlisted ? '★' : '☆' }}
                </span>
                <span id="watchlistBtnText" class="d-none d-sm-inline">{{ $isWatchlisted ? 'Pinned to Watchlist' : 'Pin to Watchlist' }}</span>
            </button>
            
            <a href="/" class="btn btn-outline-light d-inline-flex align-items-center gap-2 fw-semibold" style="border-radius: 12px; padding: 10px 20px; border-color: var(--border-color);">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" x2="5" y1="12" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Back to Dashboard
            </a>
        </div>
    </div>

    <div class="row g-4">

        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>📋 General Information</span>
                    <span class="text-muted fw-normal" style="font-size: 13px;">Demographics & Geography</span>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column flex-sm-row gap-4 align-items-center align-items-sm-start mb-4">
                        <img src="{{ $country->flag_png }}"
                             class="border rounded-3 shadow-sm"
                             alt="{{ $country->name }}"
                             style="width: 140px; height: auto; object-fit: cover;">
                        <div>
                            <h2 class="fw-bold mb-1" style="letter-spacing: -0.02em;">{{ $country->name }}</h2>
                            <span class="badge bg-primary">{{ $country->region }}</span>
                            <span class="badge bg-secondary ms-1">{{ $country->subregion }}</span>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="p-3 border rounded-3" style="border-color: var(--border-color) !important; background-color: rgba(148, 163, 184, 0.02);">
                                <small class="text-muted d-block uppercase fw-bold" style="font-size: 10px; letter-spacing: 0.05em;">Capital</small>
                                <strong style="font-size: 15px;">{{ $country->capital ?? '-' }}</strong>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 border rounded-3" style="border-color: var(--border-color) !important; background-color: rgba(148, 163, 184, 0.02);">
                                <small class="text-muted d-block uppercase fw-bold" style="font-size: 10px; letter-spacing: 0.05em;">Population</small>
                                <strong style="font-size: 15px;">{{ number_format($country->population) }}</strong>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 border rounded-3" style="border-color: var(--border-color) !important; background-color: rgba(148, 163, 184, 0.02);">
                                <small class="text-muted d-block uppercase fw-bold" style="font-size: 10px; letter-spacing: 0.05em;">Currency</small>
                                <strong style="font-size: 15px;">{{ $country->currency_name ?? '-' }} ({{ $country->currency_code ?? '-' }})</strong>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 border rounded-3" style="border-color: var(--border-color) !important; background-color: rgba(148, 163, 184, 0.02);">
                                <small class="text-muted d-block uppercase fw-bold" style="font-size: 10px; letter-spacing: 0.05em;">Coordinates</small>
                                <strong style="font-size: 14px;">Lat: {{ $country->latitude }}, Long: {{ $country->longitude }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>🛡️ Risk Metrics Breakdown</span>
                    <span class="text-muted fw-normal" style="font-size: 13px;">Real-Time Score</span>
                </div>
                <div class="card-body">
                    @if($risk)
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <tbody>
                                <tr>
                                    <td class="fw-semibold text-muted" style="font-size: 14px; width: 50%;">⛅ Weather Score</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="progress flex-grow-1" style="height: 6px; background-color: rgba(100, 116, 139, 0.1);">
                                                <div class="progress-bar" id="riskWeatherBar" style="width: {{ min(100, $risk->weather_score * 10) }}%; background: #6366f1 !important;"></div>
                                            </div>
                                            <strong id="riskWeatherValue" style="min-width: 24px; text-align: right;">{{ $risk->weather_score }}</strong>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-muted" style="font-size: 14px;">📈 Inflation Score</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="progress flex-grow-1" style="height: 6px; background-color: rgba(100, 116, 139, 0.1);">
                                                <div class="progress-bar" style="width: {{ min(100, $risk->inflation_score * 10) }}%; background: #6366f1 !important;"></div>
                                            </div>
                                            <strong style="min-width: 24px; text-align: right;">{{ $risk->inflation_score }}</strong>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-muted" style="font-size: 14px;">💱 Currency Score</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="progress flex-grow-1" style="height: 6px; background-color: rgba(100, 116, 139, 0.1);">
                                                <div class="progress-bar" style="width: {{ min(100, $risk->currency_score * 10) }}%; background: #6366f1 !important;"></div>
                                            </div>
                                            <strong style="min-width: 24px; text-align: right;">{{ $risk->currency_score }}</strong>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-muted" style="font-size: 14px;">📰 News Score</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="progress flex-grow-1" style="height: 6px; background-color: rgba(100, 116, 139, 0.1);">
                                                <div class="progress-bar" style="width: {{ min(100, $risk->news_score * 10) }}%; background: #6366f1 !important;"></div>
                                            </div>
                                            <strong style="min-width: 24px; text-align: right;">{{ $risk->news_score }}</strong>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="border-top: 2px solid var(--border-color); background-color: rgba(99, 102, 241, 0.02) !important;">
                                    <td class="fw-bold" style="font-size: 15px;">Total Aggregated Score</td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <strong id="riskTotalValue" style="font-size: 18px; color: var(--text-main);">{{ $risk->total_score }}</strong>
                                            <div id="riskLevelBadgeContainer">
                                                @if($risk->risk_level == 'High')
                                                    <span class="badge bg-danger">High Threat</span>
                                                @elseif($risk->risk_level == 'Medium')
                                                    <span class="badge bg-warning text-dark">Medium Threat</span>
                                                @else
                                                    <span class="badge bg-success">Low Threat</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @else
                        <div class="alert alert-warning border-0" style="border-radius: 12px; background-color: rgba(245, 158, 11, 0.1); color: #d97706;">
                            ⚠️ Risk score data is currently unavailable.
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <div class="row g-4 mt-2">
        <!-- Weather Snapshot -->
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>🌤️ Real-Time Weather Conditions</span>
                    <span class="text-muted fw-normal" style="font-size: 13px;">Live from Open-Meteo</span>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <div class="p-3 border rounded-3 text-center" style="border-color: var(--border-color) !important; background-color: rgba(6, 182, 212, 0.02);">
                                <small class="text-muted d-block text-uppercase fw-bold mb-1" style="font-size: 10px; letter-spacing: 0.05em;">Temperature</small>
                                <strong id="weatherTemp" style="font-size: 22px; color: var(--text-main);">
                                    {{ $latestWeather && $latestWeather->temperature !== null ? $latestWeather->temperature . ' °C' : '-' }}
                                </strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 border rounded-3 text-center" style="border-color: var(--border-color) !important; background-color: rgba(245, 158, 11, 0.02);">
                                <small class="text-muted d-block text-uppercase fw-bold mb-1" style="font-size: 10px; letter-spacing: 0.05em;">Wind Speed</small>
                                <strong id="weatherWind" style="font-size: 22px; color: var(--text-main);">
                                    {{ $latestWeather && $latestWeather->wind_speed !== null ? $latestWeather->wind_speed . ' km/h' : '-' }}
                                </strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 border rounded-3 text-center" style="border-color: var(--border-color) !important; background-color: rgba(99, 102, 241, 0.02);">
                                <small class="text-muted d-block text-uppercase fw-bold mb-1" style="font-size: 10px; letter-spacing: 0.05em;">Rainfall</small>
                                <strong id="weatherRain" style="font-size: 22px; color: var(--text-main);">
                                    {{ $latestWeather && $latestWeather->rainfall !== null ? $latestWeather->rainfall . ' mm' : '0 mm' }}
                                </strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 border rounded-3 text-center" style="border-color: var(--border-color) !important; background-color: rgba(239, 68, 68, 0.02);">
                                <small class="text-muted d-block text-uppercase fw-bold mb-1" style="font-size: 10px; letter-spacing: 0.05em;">Storm Risk</small>
                                <div class="mt-1" id="weatherRiskBadgeContainer">
                                    @if($latestWeather)
                                        @if($latestWeather->storm_risk == 'high')
                                            <span class="badge bg-danger" id="weatherRiskBadge">High Risk</span>
                                        @elseif($latestWeather->storm_risk == 'medium')
                                            <span class="badge bg-warning text-dark" id="weatherRiskBadge">Medium Risk</span>
                                        @else
                                            <span class="badge bg-success" id="weatherRiskBadge">Low Risk</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary" id="weatherRiskBadge">No Data</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top" style="border-color: var(--border-color) !important;">
                        <small class="text-muted fw-semibold" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em;">
                            Last Sync: <span id="weatherSyncTime" class="text-main fw-normal" style="font-size: 12px; margin-left: 4px;">{{ $latestWeather ? $latestWeather->updated_at->toIso8601String() : '-' }}</span>
                        </small>
                        @if(auth()->user()->role === 'admin')<button id="updateWeatherBtn" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-2 fw-semibold" style="border-radius: 10px; padding: 8px 14px;">
                            <span id="weatherBtnSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            <span id="weatherBtnIcon">🔄</span>
                            Perbarui dari Open-Meteo
                        </button>@endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Weather History Logs -->
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>📜 Historical Weather Logs</span>
                    <span class="text-muted fw-normal" style="font-size: 13px;">Previous snapshots</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr style="border-bottom: 2px solid var(--border-color);">
                                    <th style="padding-left: 24px;">Date & Time</th>
                                    <th>Temp</th>
                                    <th>Wind</th>
                                    <th>Rain</th>
                                    <th style="padding-right: 24px;">Storm Risk</th>
                                </tr>
                            </thead>
                            <tbody id="weatherHistoryBody">
                                @forelse($historicalWeather as $history)
                                    <tr>
                                        <td style="padding-left: 24px;" class="text-muted fw-semibold" style="font-size: 13px;">
                                            {{ $history->created_at->toIso8601String() }}
                                        </td>
                                        <td><strong>{{ $history->temperature }} °C</strong></td>
                                        <td>{{ $history->wind_speed }} km/h</td>
                                        <td>{{ $history->rainfall }} mm</td>
                                        <td style="padding-right: 24px;">
                                            @if($history->storm_risk == 'high')
                                                <span class="badge bg-danger">High</span>
                                            @elseif($history->storm_risk == 'medium')
                                                <span class="badge bg-warning text-dark">Medium</span>
                                            @else
                                                <span class="badge bg-success">Low</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            No historical weather logs available.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4 shadow-sm">

        <div class="card-header d-flex justify-content-between align-items-center">
            <span>📈 Risk Score Trend</span>
            <span class="text-muted fw-normal" style="font-size: 13px;">Historical changes over time</span>
        </div>

        <div class="card-body">

            <div style="position: relative; height: 350px; width: 100%;">
                <canvas id="trendChart"></canvas>
            </div>

        </div>

    </div>

</div>

<script>

let trendChart;

function loadTrend(){

fetch('/api/trend/{{ $country->id }}')

.then(response=>response.json())

.then(data=>{

const labels=data.map(item=>{

return new Date(item.created_at).toLocaleString();

});

const scores=data.map(item=>item.total_score);

if(trendChart){

trendChart.destroy();

}

const ctx = document.getElementById('trendChart').getContext('2d');
const isDark = document.body.classList.contains('bg-dark');
const textColor = isDark ? '#94a3b8' : '#64748b';
const gridColor = isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)';

// Create soft gradient under line
const gradient = ctx.createLinearGradient(0, 0, 0, 300);
gradient.addColorStop(0, 'rgba(99, 102, 241, 0.35)');
gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');

trendChart=new Chart(

    ctx,

    {

        type: 'line',

        data: {

            labels: labels,

            datasets: [{

                label: 'Risk Score',

                data: scores,

                fill: true,
                backgroundColor: gradient,
                borderColor: '#6366f1',

                borderWidth: 3,

                tension: 0.4,
                pointBackgroundColor: '#6366f1',
                pointBorderColor: isDark ? '#111625' : '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 8

            }]

        },

        options: {

            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: isDark ? '#1e293b' : '#0f172a',
                    titleColor: '#fff',
                    bodyColor: '#e2e8f0',
                    padding: 12,
                    cornerRadius: 10,
                    titleFont: {
                        family: 'Plus Jakarta Sans',
                        weight: '700'
                    },
                    bodyFont: {
                        family: 'Plus Jakarta Sans'
                    }
                }
            },
            scales: {
                y: {
                    grid: { color: gridColor, drawTicks: false },
                    ticks: {
                        color: textColor,
                        font: { family: 'Plus Jakarta Sans', size: 12 }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: {
                        color: textColor,
                        font: { family: 'Plus Jakarta Sans', size: 11 }
                    }
                }
            }

        }

    }

);

});

}

loadTrend();

setInterval(loadTrend,30000);

// Watch for theme toggles to update chart configurations
document.getElementById('darkModeBtn')?.addEventListener('click', () => {
    setTimeout(() => {
        const isDarkNow = document.body.classList.contains('bg-dark');
        const newTextColor = isDarkNow ? '#94a3b8' : '#64748b';
        const newGridColor = isDarkNow ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)';
        
        if(trendChart) {
            trendChart.options.scales.x.ticks.color = newTextColor;
            trendChart.options.scales.y.ticks.color = newTextColor;
            trendChart.options.scales.y.grid.color = newGridColor;
            trendChart.data.datasets[0].pointBorderColor = isDarkNow ? '#111625' : '#ffffff';
            trendChart.update();
        }
    }, 100);
});

// Live weather refresh for country page
document.getElementById('updateWeatherBtn')?.addEventListener('click', () => {
    const btn = document.getElementById('updateWeatherBtn');
    const spinner = document.getElementById('weatherBtnSpinner');
    const icon = document.getElementById('weatherBtnIcon');
    const countryId = {{ $country->id }};

    if (btn) btn.disabled = true;
    if (spinner) spinner.classList.remove('d-none');
    if (icon) icon.classList.add('d-none');

    // Get CSRF Token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    fetch(`/admin/weather/refresh/${countryId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            // Update Weather card fields
            document.getElementById('weatherTemp').innerText = res.weather.temperature !== null ? `${res.weather.temperature} °C` : '-';
            document.getElementById('weatherWind').innerText = res.weather.wind_speed !== null ? `${res.weather.wind_speed} km/h` : '-';
            document.getElementById('weatherRain').innerText = res.weather.rainfall !== null ? `${res.weather.rainfall} mm` : '0 mm';
            
            // Update weather risk badge
            const badgeContainer = document.getElementById('weatherRiskBadgeContainer');
            let badgeClass = 'bg-success';
            let badgeText = 'Low Risk';
            if (res.weather.storm_risk === 'high') {
                badgeClass = 'bg-danger';
                badgeText = 'High Risk';
            } else if (res.weather.storm_risk === 'medium') {
                badgeClass = 'bg-warning text-dark';
                badgeText = 'Medium Risk';
            }
            badgeContainer.innerHTML = `<span class="badge ${badgeClass}" id="weatherRiskBadge">${badgeText}</span>`;
            
            // Update Sync Time
            document.getElementById('weatherSyncTime').innerText = new Date(res.weather.updated_at).toLocaleString();
            
            // Update Risk breakdown table values
            if (document.getElementById('riskWeatherBar')) {
                document.getElementById('riskWeatherBar').style.width = `${Math.min(100, res.risk.weather_score * 10)}%`;
            }
            if (document.getElementById('riskWeatherValue')) {
                document.getElementById('riskWeatherValue').innerText = res.risk.weather_score;
            }
            if (document.getElementById('riskTotalValue')) {
                document.getElementById('riskTotalValue').innerText = res.risk.total_score;
            }
            
            // Update Risk level badge
            const riskLevelBadgeContainer = document.getElementById('riskLevelBadgeContainer');
            if (riskLevelBadgeContainer) {
                let riskBadge = '<span class="badge bg-success">Low Threat</span>';
                if (res.risk.risk_level === 'High') {
                    riskBadge = '<span class="badge bg-danger">High Threat</span>';
                } else if (res.risk.risk_level === 'Medium') {
                    riskBadge = '<span class="badge bg-warning text-dark">Medium Threat</span>';
                }
                riskLevelBadgeContainer.innerHTML = riskBadge;
            }

            // Append new row to history table
            const historyBody = document.getElementById('weatherHistoryBody');
            const newRow = document.createElement('tr');
            newRow.classList.add('row-updated');
            
            let historyRiskBadge = '<span class="badge bg-success">Low</span>';
            if (res.weather.storm_risk === 'high') {
                historyRiskBadge = '<span class="badge bg-danger">High</span>';
            } else if (res.weather.storm_risk === 'medium') {
                historyRiskBadge = '<span class="badge bg-warning text-dark">Medium</span>';
            }

            newRow.innerHTML = `
                <td style="padding-left: 24px;" class="text-muted fw-semibold" style="font-size: 13px;">
                    ${new Date(res.weather.updated_at).toLocaleString()}
                </td>
                <td><strong>${res.weather.temperature} °C</strong></td>
                <td>${res.weather.wind_speed} km/h</td>
                <td>${res.weather.rainfall} mm</td>
                <td style="padding-right: 24px;">${historyRiskBadge}</td>
            `;
            
            // Remove "No historical weather logs available" if it is present
            if (historyBody.innerHTML.includes('No historical weather logs')) {
                historyBody.innerHTML = '';
            }
            
            // Prepend new row
            historyBody.insertBefore(newRow, historyBody.firstChild);

            // Re-load trend line chart to reflect new risk scores
            if (typeof loadTrend === 'function') {
                loadTrend();
            }
        } else {
            alert("Failed to refresh: " + res.message);
        }
    })
    .catch(err => {
        console.error(err);
        alert("An error occurred while updating weather data.");
    })
    .finally(() => {
        if (btn) btn.disabled = false;
        if (spinner) spinner.classList.add('d-none');
        if (icon) icon.classList.remove('d-none');
    });
});

// Watchlist Toggle
document.getElementById('toggleWatchlistBtn')?.addEventListener('click', function(e) {
    e.preventDefault();
    const countryId = this.getAttribute('data-country-id');
    const star = document.getElementById('starIcon');
    const text = document.getElementById('watchlistBtnText');
    const btn = this;

    btn.disabled = true;

    toggleWatchlistGlobal(countryId, function(isWatchlisted, message) {
        btn.disabled = false;
        if (isWatchlisted) {
            star.textContent = '★';
            star.style.color = '#fbbf24';
            text.textContent = 'Pinned to Watchlist';
            btn.setAttribute('title', 'Remove from Watchlist');
        } else {
            star.textContent = '☆';
            star.style.color = 'rgba(255,255,255,0.4)';
            text.textContent = 'Pin to Watchlist';
            btn.setAttribute('title', 'Add to Watchlist');
        }
    });
});

</script>
@endsection
