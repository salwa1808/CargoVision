@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
        <div>
            <h1 class="h3 fw-bold mb-1" style="letter-spacing: -0.02em;">
                🌤️ Global Weather Intelligence
            </h1>
            <p class="text-muted fw-medium mb-0">
                Real-time weather monitoring & storm risk indicators for maritime supply chain routes
            </p>
        </div>
        <button id="refreshAllBtn" class="btn btn-primary d-inline-flex align-items-center gap-2 fw-semibold" style="border-radius: 12px; padding: 10px 20px;">
            <svg id="refreshAllSpinner" class="spinner-icon d-none" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
            </svg>
            <span id="refreshAllIcon">🔄</span>
            Refresh Page Data
        </button>
    </div>

    <!-- Weather Stats Overview -->
    <div class="row g-4 mb-4">
        <!-- Average Temp -->
        <div class="col-lg-3 col-md-6">
            <div class="card shadow-sm border-start border-5" style="border-left-color: #06b6d4 !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.05em;">Avg Global Temp</small>
                            <h2 id="avgTemp" class="fw-bold mt-2 mb-0" style="font-size: 28px;">-- °C</h2>
                        </div>
                        <div class="stat-icon-wrapper text-info" style="background-color: rgba(6, 182, 212, 0.1);">
                            🌡️
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- High Wind Speed Count -->
        <div class="col-lg-3 col-md-6">
            <div class="card shadow-sm border-start border-5" style="border-left-color: #f59e0b !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-warning fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.05em;">Windy Conditions</small>
                            <h2 id="windyCount" class="fw-bold mt-2 mb-0 text-warning" style="font-size: 28px;">--</h2>
                        </div>
                        <div class="stat-icon-wrapper text-warning" style="background-color: rgba(245, 158, 11, 0.1);">
                            💨
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Heavy Rainfall Count -->
        <div class="col-lg-3 col-md-6">
            <div class="card shadow-sm border-start border-5" style="border-left-color: #6366f1 !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-primary fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.05em;">Heavy Rain Areas</small>
                            <h2 id="heavyRainCount" class="fw-bold mt-2 mb-0 text-primary" style="font-size: 28px;">--</h2>
                        </div>
                        <div class="stat-icon-wrapper text-primary" style="background-color: rgba(99, 102, 241, 0.1);">
                            🌧️
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- High Storm Risk Count -->
        <div class="col-lg-3 col-md-6">
            <div class="card shadow-sm border-start border-5" style="border-left-color: #ef4444 !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-danger fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.05em;">High Storm Risk</small>
                            <h2 id="highStormCount" class="fw-bold mt-2 mb-0 text-danger" style="font-size: 28px;">--</h2>
                        </div>
                        <div class="stat-icon-wrapper text-danger" style="background-color: rgba(239, 68, 68, 0.1);">
                            ⚠️
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Map container inside card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>🗺️ Spatial Weather Risk Distribution</span>
            <span class="text-muted fw-normal" style="font-size: 13px;">Color indicators: Red (High), Yellow (Medium), Blue/Green (Low)</span>
        </div>
        <div class="card-body p-0" style="border-radius: 0 0 20px 20px; overflow: hidden;">
            <div id="weatherMap" style="height:450px"></div>
        </div>
    </div>

    <!-- Filters & Table -->
    <div class="card shadow-sm">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <span class="fw-bold">📋 Tracked Weather Listings</span>
            
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <input type="text" id="weatherSearch" class="form-control form-control-sm" placeholder="Search country..." style="max-width: 200px;">
                
                <select id="regionFilter" class="form-select form-select-sm" style="max-width: 150px;">
                    <option value="">All Regions</option>
                    <option value="Asia">Asia</option>
                    <option value="Europe">Europe</option>
                    <option value="Africa">Africa</option>
                    <option value="Americas">Americas</option>
                    <option value="Oceania">Oceania</option>
                </select>

                <select id="riskFilter" class="form-select form-select-sm" style="max-width: 150px;">
                    <option value="">All Weather Risk</option>
                    <option value="high">High Risk</option>
                    <option value="medium">Medium Risk</option>
                    <option value="low">Low Risk</option>
                </select>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--border-color);">
                            <th style="padding-left: 24px;">Country</th>
                            <th>Region</th>
                            <th>Temperature</th>
                            <th>Wind Speed</th>
                            <th>Rainfall</th>
                            <th>Storm Risk</th>
                            <th>Aggregated Risk</th>
                            <th class="text-end" style="padding-right: 24px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="weatherTableBody">
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading weather data...</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .spinner-icon {
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .row-updated {
        animation: highlight 1.5s ease-out-back;
    }
    @keyframes highlight {
        0% { background-color: rgba(99, 102, 241, 0.25); }
        100% { background-color: transparent; }
    }
</style>
@endsection

@push('scripts')
<script>
let weatherData = [];
let map;
let markerGroup;
let activeTileLayer;

// Setup Map
function initMap() {
    map = L.map('weatherMap').setView([10, 0], 2);
    
    function getTileUrl() {
        return document.body.classList.contains('bg-dark')
            ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png'
            : 'https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png';
    }

    activeTileLayer = L.tileLayer(getTileUrl(), {
        maxZoom: 18,
        attribution: '© OpenStreetMap © CartoDB'
    }).addTo(map);

    markerGroup = L.layerGroup().addTo(map);

    // Watch for theme toggles to swap tiles
    document.getElementById('darkModeBtn').addEventListener('click', () => {
        setTimeout(() => {
            map.removeLayer(activeTileLayer);
            activeTileLayer = L.tileLayer(getTileUrl(), {
                maxZoom: 18,
                attribution: '© OpenStreetMap © CartoDB'
            }).addTo(map);
        }, 120);
    });
}

// Fetch data from API
function fetchWeatherData() {
    return fetch('/api/weather')
        .then(response => response.json())
        .then(data => {
            weatherData = data;
            updateStats();
            renderTable();
            renderMap();
        })
        .catch(err => {
            console.error("Error loading weather data:", err);
        });
}

// Update stats cards
function updateStats() {
    if (weatherData.length === 0) return;

    // Filter out countries with null temperature
    const validTemps = weatherData.filter(x => x.temperature !== null).map(x => parseFloat(x.temperature));
    const avgTemp = validTemps.length > 0 ? (validTemps.reduce((a, b) => a + b, 0) / validTemps.length).toFixed(1) : '--';
    
    const windyCount = weatherData.filter(x => x.wind_speed >= 30).length;
    const heavyRainCount = weatherData.filter(x => x.rainfall >= 20).length;
    const highStormCount = weatherData.filter(x => x.storm_risk === 'high').length;

    document.getElementById('avgTemp').innerText = avgTemp !== '--' ? `${avgTemp} °C` : '--';
    document.getElementById('windyCount').innerText = windyCount;
    document.getElementById('heavyRainCount').innerText = heavyRainCount;
    document.getElementById('highStormCount').innerText = highStormCount;
}

// Render Table
function renderTable() {
    const tableBody = document.getElementById('weatherTableBody');
    const searchVal = document.getElementById('weatherSearch').value.toLowerCase();
    const regionVal = document.getElementById('regionFilter').value;
    const riskVal = document.getElementById('riskFilter').value;

    const filtered = weatherData.filter(item => {
        const matchesSearch = item.name.toLowerCase().includes(searchVal);
        const matchesRegion = !regionVal || item.region === regionVal;
        const matchesRisk = !riskVal || item.storm_risk === riskVal;
        return matchesSearch && matchesRegion && matchesRisk;
    });

    if (filtered.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-4 text-muted">
                    No records found matching your filters.
                </td>
            </tr>
        `;
        return;
    }

    tableBody.innerHTML = filtered.map(item => {
        const tempDisplay = item.temperature !== null ? `${item.temperature} °C` : '<span class="text-muted">-</span>';
        const windDisplay = item.wind_speed !== null ? `${item.wind_speed} km/h` : '<span class="text-muted">-</span>';
        const rainDisplay = item.rainfall !== null ? `${item.rainfall} mm` : '<span class="text-muted">0 mm</span>';
        
        let stormRiskBadge = '<span class="badge bg-secondary">Low</span>';
        if (item.storm_risk === 'high') {
            stormRiskBadge = '<span class="badge bg-danger">🔴 High</span>';
        } else if (item.storm_risk === 'medium') {
            stormRiskBadge = '<span class="badge bg-warning text-dark">🟡 Medium</span>';
        } else {
            stormRiskBadge = '<span class="badge bg-success">🟢 Low</span>';
        }

        let threatBadge = '<span class="badge bg-secondary">Low</span>';
        if (item.risk_level === 'High') {
            threatBadge = '<span class="badge bg-danger">High Threat</span>';
        } else if (item.risk_level === 'Medium') {
            threatBadge = '<span class="badge bg-warning text-dark">Medium Threat</span>';
        } else {
            threatBadge = '<span class="badge bg-success">Low Threat</span>';
        }

        const dateStr = item.updated_at ? new Date(item.updated_at).toLocaleString() : 'Never';

        return `
            <tr id="row-${item.id}">
                <td style="padding-left: 24px;">
                    <div class="d-flex align-items-center gap-2">
                        <img src="${item.flag_png}" alt="${item.name}" class="border rounded" style="width:28px; height:18px; object-fit:cover;">
                        <a href="/country/${item.id}" class="fw-bold text-decoration-none text-main">${item.name}</a>
                    </div>
                </td>
                <td class="text-muted">${item.region}</td>
                <td><strong>${tempDisplay}</strong></td>
                <td>${windDisplay}</td>
                <td>${rainDisplay}</td>
                <td>${stormRiskBadge}</td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <strong style="font-size: 13.5px; min-width: 32px;">${item.total_score}</strong>
                        ${threatBadge}
                    </div>
                </td>
                <td class="text-end" style="padding-right: 24px;">
                    <div class="d-flex justify-content-end gap-2">
                        <button onclick="refreshSingleWeather(${item.id})" class="btn btn-sm btn-outline-light d-flex align-items-center gap-1 border-color fw-semibold" id="btn-refresh-${item.id}" title="Refresh Live Data" style="border-radius:8px;">
                            <span id="spinner-${item.id}" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            <span id="icon-${item.id}">🔄</span>
                            Refresh
                        </button>
                        <a href="/country/${item.id}" class="btn btn-sm btn-outline-light border-color fw-semibold" style="border-radius:8px;">
                            Profile
                        </a>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

// Render Map Markers
function renderMap() {
    markerGroup.clearLayers();
    
    weatherData.forEach(item => {
        if (!item.latitude || !item.longitude) return;

        let color = '#10b981'; // low/default
        if (item.storm_risk === 'high') color = '#ef4444';
        else if (item.storm_risk === 'medium') color = '#f59e0b';

        const marker = L.circleMarker([item.latitude, item.longitude], {
            radius: 8,
            fillColor: color,
            color: '#fff',
            weight: 1.5,
            opacity: 1,
            fillOpacity: 0.8
        });

        const popupContent = `
            <div style="font-family:'Plus Jakarta Sans',sans-serif; padding: 4px; min-width: 180px;">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <img src="${item.flag_png}" class="border rounded" style="width: 24px; height: 15px; object-fit: cover;">
                    <h6 style="font-weight: 700; margin: 0; font-size: 13.5px;">${item.name}</h6>
                </div>
                <div style="font-size: 12px; margin-bottom: 6px;">
                    <div class="d-flex justify-content-between"><span>Temp:</span><strong>${item.temperature !== null ? item.temperature + ' °C' : '-'}</strong></div>
                    <div class="d-flex justify-content-between"><span>Wind Speed:</span><strong>${item.wind_speed !== null ? item.wind_speed + ' km/h' : '-'}</strong></div>
                    <div class="d-flex justify-content-between"><span>Rainfall:</span><strong>${item.rainfall} mm</strong></div>
                    <div class="d-flex justify-content-between mt-1 pt-1 border-top"><span>Storm Risk:</span><strong style="text-transform: capitalize;">${item.storm_risk}</strong></div>
                    <div class="d-flex justify-content-between"><span>Aggregated Risk:</span><strong>${item.total_score} (${item.risk_level})</strong></div>
                </div>
                <div class="d-flex gap-1 mt-2">
                    <button class="btn btn-xs btn-primary w-100 fw-bold" onclick="refreshSingleWeather(${item.id})" style="font-size: 10px; padding: 4px 6px; border-radius: 6px;">🔄 Update Live</button>
                    <a href="/country/${item.id}" class="btn btn-xs btn-outline-dark w-100 fw-bold text-center" style="font-size: 10px; padding: 4px 6px; border-radius: 6px; text-decoration: none;">View Detail</a>
                </div>
            </div>
        `;

        marker.bindPopup(popupContent);
        markerGroup.addLayer(marker);
    });
}

// Refresh Single Country Weather
function refreshSingleWeather(countryId) {
    const btn = document.getElementById(`btn-refresh-${countryId}`);
    const spinner = document.getElementById(`spinner-${countryId}`);
    const icon = document.getElementById(`icon-${countryId}`);

    if (btn) btn.disabled = true;
    if (spinner) spinner.classList.remove('d-none');
    if (icon) icon.classList.add('d-none');

    // Get CSRF Token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    fetch(`/api/weather/refresh/${countryId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            // Find index and update
            const index = weatherData.findIndex(x => x.id === countryId);
            if (index !== -1) {
                weatherData[index].temperature = res.weather.temperature;
                weatherData[index].wind_speed = res.weather.wind_speed;
                weatherData[index].rainfall = res.weather.rainfall;
                weatherData[index].storm_risk = res.weather.storm_risk;
                weatherData[index].updated_at = res.weather.updated_at;
                weatherData[index].weather_score = res.risk.weather_score;
                weatherData[index].risk_level = res.risk.risk_level;
                weatherData[index].total_score = res.risk.total_score;

                updateStats();
                renderTable();
                renderMap();

                // Visual feedback highlight
                const row = document.getElementById(`row-${countryId}`);
                if (row) {
                    row.classList.add('row-updated');
                    setTimeout(() => {
                        row.classList.remove('row-updated');
                    }, 1500);
                }
            }
        } else {
            alert("Failed to refresh: " + res.message);
        }
    })
    .catch(err => {
        console.error(err);
        alert("An error occurred while refreshing weather data.");
    })
    .finally(() => {
        if (btn) btn.disabled = false;
        if (spinner) spinner.classList.add('d-none');
        if (icon) icon.classList.remove('d-none');
    });
}

// Global Refresh all page data
document.getElementById('refreshAllBtn').addEventListener('click', () => {
    const btn = document.getElementById('refreshAllBtn');
    const spinner = document.getElementById('refreshAllSpinner');
    const icon = document.getElementById('refreshAllIcon');

    btn.disabled = true;
    spinner.classList.remove('d-none');
    icon.classList.add('d-none');

    fetchWeatherData().finally(() => {
        btn.disabled = false;
        spinner.classList.add('d-none');
        icon.classList.remove('d-none');
    });
});

// Event listeners for filters
document.getElementById('weatherSearch').addEventListener('input', renderTable);
document.getElementById('regionFilter').addEventListener('change', renderTable);
document.getElementById('riskFilter').addEventListener('change', renderTable);

window.onload = function() {
    initMap();
    fetchWeatherData();
    // Hide default loader
    const loader = document.getElementById("loader");
    if(loader) loader.style.display="none";
}
</script>
@endpush
