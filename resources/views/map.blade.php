@extends('layouts.app')

@push('styles')
<style>
    /* Card design overrides */
    .map-sidebar-card {
        margin-bottom: 24px;
    }
    
    /* Map Container styling */
    .map-container-wrapper {
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.05);
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
    }
    
    #map {
        height: 600px;
        width: 100%;
        background-color: #0b0617;
        z-index: 1;
    }

    /* Fullscreen Mode Styling */
    .map-container-wrapper:-webkit-full-screen {
        width: 100vw !important;
        height: 100vh !important;
        border-radius: 0 !important;
        border: none !important;
    }
    .map-container-wrapper:-webkit-full-screen #map {
        height: 100vh !important;
    }
    .map-container-wrapper:fullscreen {
        width: 100vw !important;
        height: 100vh !important;
        border-radius: 0 !important;
        border: none !important;
    }
    .map-container-wrapper:fullscreen #map {
        height: 100vh !important;
    }

    /* Custom Leaflet Controls styled with Glassmorphism */
    .leaflet-bar {
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3) !important;
        border-radius: 10px !important;
        overflow: hidden;
    }
    .leaflet-bar a {
        background: rgba(20, 10, 35, 0.85) !important;
        backdrop-filter: blur(10px) !important;
        -webkit-backdrop-filter: blur(10px) !important;
        color: #ffffff !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06) !important;
        transition: all 0.2s ease;
    }
    .leaflet-bar a:hover {
        background: rgba(139, 92, 246, 0.25) !important;
        color: #c084fc !important;
    }
    .leaflet-control-zoom-in, .leaflet-control-zoom-out {
        font-weight: bold;
    }

    /* Custom Map Overlays */
    .map-overlay-btn {
        position: absolute;
        z-index: 1000;
        background: rgba(20, 10, 35, 0.85);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 10px;
        color: #ffffff;
        padding: 8px 12px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        transition: all 0.2s ease;
    }
    .map-overlay-btn:hover {
        background: rgba(139, 92, 246, 0.25);
        border-color: rgba(139, 92, 246, 0.4);
        box-shadow: 0 0 10px rgba(139, 92, 246, 0.4);
        transform: translateY(-1px);
    }
    .map-btn-fullscreen {
        top: 12px;
        right: 12px;
    }
    .map-btn-reset {
        top: 12px;
        right: 110px;
    }

    /* Search Autocomplete */
    .map-search-container {
        position: absolute;
        top: 12px;
        left: 50px;
        z-index: 1000;
        width: 280px;
    }
    .map-search-input {
        width: 100%;
        background: rgba(20, 10, 35, 0.85) !important;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        border-radius: 10px !important;
        padding: 8px 12px 8px 32px !important;
        font-size: 13px !important;
        color: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }
    .map-search-input:focus {
        border-color: rgba(139, 92, 246, 0.5) !important;
        box-shadow: 0 0 10px rgba(139, 92, 246, 0.3) !important;
    }
    .map-search-icon {
        position: absolute;
        left: 10px;
        top: 11px;
        color: rgba(255, 255, 255, 0.4);
        font-size: 13px;
        pointer-events: none;
    }
    .map-search-results {
        position: absolute;
        top: 42px;
        left: 0;
        right: 0;
        background: rgba(15, 7, 28, 0.95);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 10px;
        max-height: 250px;
        overflow-y: auto;
        display: none;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
        z-index: 1001;
    }
    .map-search-item {
        padding: 8px 12px;
        font-size: 12.5px;
        color: rgba(255, 255, 255, 0.8);
        cursor: pointer;
        transition: all 0.15s ease;
        border-bottom: 1px solid rgba(255, 255, 255, 0.03);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .map-search-item:hover {
        background: rgba(139, 92, 246, 0.2);
        color: #ffffff;
    }
    .map-search-item:last-child {
        border-bottom: none;
    }

    /* Map Legend */
    .map-legend {
        position: absolute;
        bottom: 12px;
        left: 12px;
        z-index: 1000;
        background: rgba(20, 10, 35, 0.85);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 10px;
        padding: 10px 14px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }
    .legend-title {
        font-size: 10px;
        font-weight: 700;
        color: rgba(255, 255, 255, 0.4);
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 6px;
    }
    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        font-weight: 600;
        color: #ffffff;
        margin-bottom: 4px;
    }
    .legend-item:last-child {
        margin-bottom: 0;
    }
    .legend-color {
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }

    /* Leaflet persistent tooltip overrides */
    .leaflet-country-tooltip {
        background: rgba(15, 8, 30, 0.75) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        color: #ffffff !important;
        font-size: 9.5px !important;
        font-weight: 600 !important;
        border-radius: 6px !important;
        padding: 2px 5px !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.5) !important;
        text-shadow: 0 1px 2px rgba(0,0,0,0.8);
    }
    .leaflet-country-tooltip::before {
        border-top-color: rgba(15, 8, 30, 0.75) !important;
    }

    /* Route Path flow animation */
    @keyframes routeFlow {
        from {
            stroke-dashoffset: 20;
        }
        to {
            stroke-dashoffset: 0;
        }
    }
    .animated-route-path {
        animation: routeFlow 1.2s linear infinite;
    }

    /* Pulsing Custom Marker pins */
    .custom-leaflet-marker {
        background: transparent;
        border: none;
    }
    .marker-pin {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background-color: var(--glow-color);
        border: 2.5px solid #ffffff;
        box-shadow: 0 0 10px var(--glow-color), 0 0 20px var(--glow-color);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }
    .marker-pin:hover {
        transform: scale(1.35);
        box-shadow: 0 0 15px var(--glow-color), 0 0 30px var(--glow-color);
    }
    .marker-high {
        animation: pulse-high 1.8s infinite;
    }
    .marker-medium {
        animation: pulse-medium 1.8s infinite;
    }
    .marker-low {
        animation: pulse-low 2.5s infinite;
    }
    @keyframes pulse-high {
        0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.8); }
        70% { box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
        100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
    }
    @keyframes pulse-medium {
        0% { box-shadow: 0 0 0 0 rgba(251, 191, 36, 0.8); }
        70% { box-shadow: 0 0 0 8px rgba(251, 191, 36, 0); }
        100% { box-shadow: 0 0 0 0 rgba(251, 191, 36, 0); }
    }
    @keyframes pulse-low {
        0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.6); }
        70% { box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
        100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }

    /* Leaflet popup customization to match dark glassmorphism styling */
    .leaflet-popup-content-wrapper {
        background: rgba(15, 8, 30, 0.92) !important;
        backdrop-filter: blur(12px) !important;
        -webkit-backdrop-filter: blur(12px) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        color: #ffffff !important;
        border-radius: 16px !important;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5) !important;
    }
    .leaflet-popup-tip {
        background: rgba(15, 8, 30, 0.92) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
    }
    .leaflet-popup-close-button {
        color: rgba(255, 255, 255, 0.5) !important;
        padding: 6px 8px !important;
    }
    .leaflet-popup-close-button:hover {
        color: #ffffff !important;
        background: transparent !important;
    }

    /* Interactive route planning styling */
    .route-summary-val {
        font-weight: 700;
        color: #ffffff;
    }
    .route-summary-lbl {
        color: rgba(255, 255, 255, 0.55);
        font-size: 12.5px;
    }
    
    /* Risk progress bars colors */
    .progress-risk-low {
        background: linear-gradient(90deg, #34d399, #10b981) !important;
    }
    .progress-risk-medium {
        background: linear-gradient(90deg, #fbbf24, #f59e0b) !important;
    }
    .progress-risk-high {
        background: linear-gradient(90deg, #f87171, #ef4444) !important;
    }

    /* Radio mode styles */
    .transport-modes {
        display: flex;
        gap: 10px;
        margin-top: 8px;
    }
    .transport-mode-item {
        flex: 1;
    }
    .transport-mode-item input[type="radio"] {
        display: none;
    }
    .transport-mode-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 12px;
        padding: 10px;
        cursor: pointer;
        color: rgba(255, 255, 255, 0.6);
        font-weight: 600;
        font-size: 13px;
        transition: all 0.25s ease;
    }
    .transport-mode-item input[type="radio"]:checked + .transport-mode-label {
        background: rgba(139, 92, 246, 0.15);
        border-color: rgba(139, 92, 246, 0.4);
        color: #c084fc;
        box-shadow: 0 0 10px rgba(139, 92, 246, 0.25);
    }
    .transport-mode-label span.mode-icon {
        font-size: 20px;
        margin-bottom: 4px;
    }
    .transport-mode-label:hover {
        background: rgba(255, 255, 255, 0.06);
        color: #ffffff;
    }
    
    /* Recommendations alert panel */
    .recommendation-alert {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 12px;
        padding: 14px 16px;
        margin-top: 12px;
    }
    
    .recommendation-title {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 4px;
    }
    /* Dark mode native select override (fallback) */
    .form-select,
    #originCountry,
    #destinationCountry {
        background-color: rgba(20, 10, 40, 0.9) !important;
        color: #ffffff !important;
        border: 1px solid rgba(139, 92, 246, 0.35) !important;
        border-radius: 10px !important;
    }
    .form-select option,
    #originCountry option,
    #destinationCountry option {
        background-color: #1a0f35 !important;
        color: #ffffff !important;
    }

    /* Tom Select custom dark theme overrides */
    .ts-wrapper.form-select {
        padding: 0 !important;
        background: transparent !important;
        border: none !important;
    }
    .ts-wrapper .ts-control {
        background: rgba(20, 10, 40, 0.92) !important;
        border: 1px solid rgba(139, 92, 246, 0.35) !important;
        border-radius: 10px !important;
        color: #ffffff !important;
        padding: 8px 12px !important;
        box-shadow: none !important;
        cursor: pointer;
    }
    .ts-wrapper.focus .ts-control {
        border-color: rgba(139, 92, 246, 0.7) !important;
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.18) !important;
    }
    .ts-wrapper .ts-control input {
        color: #ffffff !important;
        background: transparent !important;
    }
    .ts-wrapper .ts-control input::placeholder {
        color: rgba(255, 255, 255, 0.4) !important;
    }
    .ts-dropdown {
        background: rgba(18, 8, 35, 0.98) !important;
        border: 1px solid rgba(139, 92, 246, 0.3) !important;
        border-radius: 12px !important;
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.7) !important;
        backdrop-filter: blur(16px) !important;
        -webkit-backdrop-filter: blur(16px) !important;
        overflow: hidden;
        z-index: 9999 !important;
    }
    .ts-dropdown .ts-dropdown-content {
        max-height: 280px !important;
        overflow-y: auto !important;
        scrollbar-width: thin;
        scrollbar-color: rgba(139, 92, 246, 0.4) transparent;
    }
    .ts-dropdown .ts-dropdown-content::-webkit-scrollbar {
        width: 5px;
    }
    .ts-dropdown .ts-dropdown-content::-webkit-scrollbar-track {
        background: transparent;
    }
    .ts-dropdown .ts-dropdown-content::-webkit-scrollbar-thumb {
        background: rgba(139, 92, 246, 0.4);
        border-radius: 3px;
    }
    .ts-dropdown .option {
        color: #D1D5DB !important;
        padding: 9px 14px !important;
        font-size: 13px !important;
        transition: background 0.15s ease;
    }
    .ts-dropdown .option:hover,
    .ts-dropdown .option.active {
        background: rgba(139, 92, 246, 0.25) !important;
        color: #ffffff !important;
    }
    .ts-dropdown .option.selected {
        background: rgba(139, 92, 246, 0.35) !important;
        color: #c084fc !important;
        font-weight: 600;
    }
    .ts-dropdown .optgroup-header {
        background: rgba(139, 92, 246, 0.08) !important;
        color: rgba(255, 255, 255, 0.45) !important;
        font-size: 11px !important;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        padding: 6px 14px !important;
    }
    .ts-dropdown input.ts-search {
        background: rgba(30, 15, 55, 0.9) !important;
        color: #ffffff !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
        padding: 8px 12px !important;
        font-size: 13px !important;
        width: 100%;
        outline: none;
    }
    .ts-dropdown input.ts-search::placeholder {
        color: rgba(255, 255, 255, 0.35) !important;
    }
    .ts-wrapper .item {
        color: #ffffff !important;
    }
    .ts-wrapper .placeholder {
        color: rgba(255, 255, 255, 0.4) !important;
    }
</style>
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css">
@endpush

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-5 mt-2">
        <div>
            <h1 class="h3 fw-bold mb-1" style="letter-spacing: -0.02em;">
                🌍 Interactive Global Route Planner
            </h1>
            <p class="text-muted fw-medium mb-0">
                Visualize multi-modal transit corridors, check dynamic weather systems, and audit security matrices.
            </p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Sidebar: Route Planner -->
        <div class="col-lg-4">
            <div class="card map-sidebar-card shadow-sm">
                <div class="card-header">
                    <span>🗺️ Route Planner</span>
                </div>
                <div class="card-body">
                    <form id="routePlannerForm">
                        <!-- Origin Country -->
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em;">Origin Country</label>
                            <select id="originCountry" class="form-select" required>
                                <option value="" disabled selected>Select origin...</option>
                                @foreach($countries as $c)
                                    @if($c->latitude !== null && $c->longitude !== null)
                                        <option value="{{ $c->id }}" data-lat="{{ $c->latitude }}" data-lon="{{ $c->longitude }}">
                                            {{ $c->name }} ({{ $c->cca3 }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <!-- Destination Country -->
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em;">Destination Country</label>
                            <select id="destinationCountry" class="form-select" required>
                                <option value="" disabled selected>Select destination...</option>
                                @foreach($countries as $c)
                                    @if($c->latitude !== null && $c->longitude !== null)
                                        <option value="{{ $c->id }}" data-lat="{{ $c->latitude }}" data-lon="{{ $c->longitude }}">
                                            {{ $c->name }} ({{ $c->cca3 }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <!-- Transport Mode -->
                        <div class="mb-4">
                            <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em;">Transport Mode</label>
                            <div class="transport-modes">
                                <div class="transport-mode-item">
                                    <input type="radio" name="transport_mode" id="modeShip" value="Ship" checked>
                                    <label for="modeShip" class="transport-mode-label">
                                        <span class="mode-icon">🚢</span>
                                        <span>Ship</span>
                                    </label>
                                </div>
                                <div class="transport-mode-item">
                                    <input type="radio" name="transport_mode" id="modeAir" value="Air">
                                    <label for="modeAir" class="transport-mode-label">
                                        <span class="mode-icon">✈️</span>
                                        <span>Air</span>
                                    </label>
                                </div>
                                <div class="transport-mode-item">
                                    <input type="radio" name="transport_mode" id="modeTruck" value="Truck">
                                    <label for="modeTruck" class="transport-mode-label">
                                        <span class="mode-icon">🚛</span>
                                        <span>Truck</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 justify-content-center fw-bold" style="height: 48px;">
                            Calculate Route
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Quick Info Helper -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <span>💡 Routing Notes</span>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-2">
                        Transit estimations are calculated instantly using geographic coordinates (Haversine formula).
                    </p>
                    <div style="font-size: 12px; color: rgba(255,255,255,0.7);">
                        <div class="d-flex justify-content-between mb-1">
                            <span>🚢 Sea Speed</span>
                            <span class="fw-bold">30 km/h</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>✈️ Air Speed</span>
                            <span class="fw-bold">800 km/h</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>🚛 Road Speed</span>
                            <span class="fw-bold">70 km/h</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel: Map & Route Information -->
        <div class="col-lg-8">
            <!-- Map Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-body p-0">
                    <div class="map-container-wrapper" id="mapWrapper">
                        <!-- Search Autocomplete -->
                        <div class="map-search-container">
                            <span class="map-search-icon">🔍</span>
                            <input type="text" id="mapSearch" class="map-search-input" placeholder="Search country name...">
                            <div id="searchResults" class="map-search-results"></div>
                        </div>

                        <!-- Reset & Fullscreen Buttons -->
                        <button class="map-overlay-btn map-btn-reset" id="btnResetView" title="Reset View to Default">
                            <span>🔄</span> Reset View
                        </button>
                        <button class="map-overlay-btn map-btn-fullscreen" id="btnFullscreen" title="Toggle Fullscreen">
                            <span>📺</span> Fullscreen
                        </button>

                        <!-- Leaflet map -->
                        <div id="map"></div>

                        <!-- Legend overlay -->
                        <div class="map-legend">
                            <div class="legend-title">Risk Levels</div>
                            <div class="legend-item">
                                <span class="legend-color" style="background-color: #10b981; box-shadow: 0 0 6px #10b981;"></span>
                                <span>Low Risk</span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-color" style="background-color: #fbbf24; box-shadow: 0 0 6px #fbbf24;"></span>
                                <span>Medium Risk</span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-color" style="background-color: #ef4444; box-shadow: 0 0 6px #ef4444;"></span>
                                <span>High Risk</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Route Summary & Risk Profile Panel (Hidden initially, shown after calculation) -->
            <div class="card shadow-sm d-none" id="routeDetailsCard">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>📊 Logistics Route Analytics</span>
                    <span class="badge bg-primary text-uppercase" id="selectedModeBadge">Ship</span>
                </div>
                <div class="card-body">
                    <!-- Route Information Section -->
                    <div class="row g-4 border-bottom pb-4" style="border-color: var(--border-color) !important;">
                        <div class="col-md-6 border-end" style="border-color: var(--border-color) !important;">
                            <h5 class="fw-bold mb-3 text-white" style="font-size: 15px;">Route Summary</h5>
                            
                            <table class="w-100">
                                <tr class="border-bottom" style="border-color: rgba(255,255,255,0.03) !important;">
                                    <td class="py-2 route-summary-lbl">Origin</td>
                                    <td class="py-2 text-end route-summary-val" id="summaryOrigin">-</td>
                                </tr>
                                <tr class="border-bottom" style="border-color: rgba(255,255,255,0.03) !important;">
                                    <td class="py-2 route-summary-lbl">Destination</td>
                                    <td class="py-2 text-end route-summary-val" id="summaryDest">-</td>
                                </tr>
                                <tr class="border-bottom" style="border-color: rgba(255,255,255,0.03) !important;">
                                    <td class="py-2 route-summary-lbl">Distance</td>
                                    <td class="py-2 text-end route-summary-val" id="summaryDistance">-</td>
                                </tr>
                                <tr class="border-bottom" style="border-color: rgba(255,255,255,0.03) !important;">
                                    <td class="py-2 route-summary-lbl">Estimated Time</td>
                                    <td class="py-2 text-end route-summary-val" id="summaryTime">-</td>
                                </tr>
                                <tr class="border-bottom" style="border-color: rgba(255,255,255,0.03) !important;">
                                    <td class="py-2 route-summary-lbl">Average Risk</td>
                                    <td class="py-2 text-end route-summary-val" id="summaryAvgRisk">-</td>
                                </tr>
                                <tr>
                                    <td class="py-2 route-summary-lbl">Highest Risk</td>
                                    <td class="py-2 text-end route-summary-val" id="summaryHighestRisk">-</td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6 ps-md-4">
                            <h5 class="fw-bold mb-3 text-white" style="font-size: 15px;">Weather Status</h5>
                            <div class="mb-3" style="font-size: 13px;">
                                <div class="fw-semibold text-white mb-1 d-flex align-items-center gap-2">
                                    <span id="originFlag"></span>
                                    <span id="originWeatherName">-</span>
                                </div>
                                <div class="text-muted" id="originWeatherText">-</div>
                            </div>
                            <div style="font-size: 13px;">
                                <div class="fw-semibold text-white mb-1 d-flex align-items-center gap-2">
                                    <span id="destFlag"></span>
                                    <span id="destWeatherName">-</span>
                                </div>
                                <div class="text-muted" id="destWeatherText">-</div>
                            </div>

                            <!-- Recommendations Alert -->
                            <div class="recommendation-alert" id="recAlert">
                                <div class="recommendation-title" id="recTitle">Recommendation</div>
                                <p class="mb-0 text-white small" id="recText" style="line-height: 1.5;"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Risk Bar Section -->
                    <div class="pt-4">
                        <h5 class="fw-bold mb-3 text-white" style="font-size: 15px;">Logistics Risk Profile</h5>
                        <p class="text-muted small mb-4">
                            Comparison of individual risk factor matrices along the corridor path.
                        </p>

                        <div class="row g-3">
                            <!-- Weather Risk -->
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between mb-1" style="font-size: 12px;">
                                    <span class="fw-semibold text-white">Weather Risk</span>
                                    <span id="barWeatherVal" class="text-muted">-</span>
                                </div>
                                <div class="progress" style="height: 8px; background-color: rgba(255,255,255,0.05); border-radius: 4px; overflow: hidden;">
                                    <div id="barWeather" class="progress-bar" role="progressbar" style="width: 0%"></div>
                                </div>
                            </div>

                            <!-- Economic Risk -->
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between mb-1" style="font-size: 12px;">
                                    <span class="fw-semibold text-white">Economic Risk</span>
                                    <span id="barEconomicVal" class="text-muted">-</span>
                                </div>
                                <div class="progress" style="height: 8px; background-color: rgba(255,255,255,0.05); border-radius: 4px; overflow: hidden;">
                                    <div id="barEconomic" class="progress-bar" role="progressbar" style="width: 0%"></div>
                                </div>
                            </div>

                            <!-- News Risk -->
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between mb-1" style="font-size: 12px;">
                                    <span class="fw-semibold text-white">News Risk</span>
                                    <span id="barNewsVal" class="text-muted">-</span>
                                </div>
                                <div class="progress" style="height: 8px; background-color: rgba(255,255,255,0.05); border-radius: 4px; overflow: hidden;">
                                    <div id="barNews" class="progress-bar" role="progressbar" style="width: 0%"></div>
                                </div>
                            </div>

                            <!-- Currency Risk -->
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between mb-1" style="font-size: 12px;">
                                    <span class="fw-semibold text-white">Currency Risk</span>
                                    <span id="barCurrencyVal" class="text-muted">-</span>
                                </div>
                                <div class="progress" style="height: 8px; background-color: rgba(255,255,255,0.05); border-radius: 4px; overflow: hidden;">
                                    <div id="barCurrency" class="progress-bar" role="progressbar" style="width: 0%"></div>
                                </div>
                            </div>

                            <!-- Total Risk -->
                            <div class="col-12 mt-4 pt-2 border-top" style="border-color: rgba(255,255,255,0.03) !important;">
                                <div class="d-flex justify-content-between mb-1" style="font-size: 13.5px;">
                                    <span class="fw-bold text-white">Total Route Risk</span>
                                    <span id="barTotalVal" class="fw-bold text-white">-</span>
                                </div>
                                <div class="progress" style="height: 12px; background-color: rgba(255,255,255,0.05); border-radius: 6px; overflow: hidden;">
                                    <div id="barTotal" class="progress-bar" role="progressbar" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
// Initialize Tom Select dark dropdowns for Route Planner
document.addEventListener('DOMContentLoaded', function() {
    ['originCountry', 'destinationCountry'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el && !el.tomselect) {
            new TomSelect('#' + id, {
                maxOptions: null,
                searchField: ['text'],
                placeholder: el.options[0] ? el.options[0].text : 'Select...',
                plugins: [],
                render: {
                    no_results: function(data, escape) {
                        return '<div class="no-results" style="color:#9CA3AF;padding:10px 14px;font-size:13px;">No results for "<strong>' + escape(data.input) + '</strong>"</div>';
                    }
                }
            });
        }
    });
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Inject php array as JSON
    const countriesData = @json($countriesData);
    
    if (typeof L === "undefined") {
        console.error("Leaflet.js failed to load.");
        return;
    }

    // 1. Initialize Map
    const defaultZoom = parseInt(localStorage.getItem('default_map_zoom')) || 2;
    const map = L.map('map', {
        zoomControl: true,
        attributionControl: true
    }).setView([20, 0], defaultZoom);

    let activeTileLayer;
    function getTileUrl() {
        return document.body.classList.contains('bg-dark') || localStorage.getItem("theme") === "dark"
            ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png'
            : 'https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png';
    }

    activeTileLayer = L.tileLayer(getTileUrl(), {
        attribution: '© OpenStreetMap © CartoDB',
        maxZoom: 18
    }).addTo(map);

    // Watch for theme toggles to swap tiles
    const themeBtn = document.getElementById('darkModeBtn');
    if (themeBtn) {
        themeBtn.addEventListener('click', () => {
            setTimeout(() => {
                map.removeLayer(activeTileLayer);
                activeTileLayer = L.tileLayer(getTileUrl(), {
                    attribution: '© OpenStreetMap © CartoDB',
                    maxZoom: 18
                }).addTo(map);
            }, 100);
        });
    }

    // 2. Custom Marker Helper
    function getMarkerIcon(level) {
        const markerColorMode = localStorage.getItem('marker_color_mode') || 'by_risk';
        const markerSize = localStorage.getItem('default_marker_size') || 'medium';
        
        let iconWidth = 16, iconHeight = 16;
        if (markerSize === 'small') { iconWidth = 12; iconHeight = 12; }
        else if (markerSize === 'large') { iconWidth = 24; iconHeight = 24; }
        
        let color = '#10b981'; // green
        let pulseClass = 'marker-low';
        
        if (markerColorMode === 'single') {
            const accent = localStorage.getItem('accent_color') || 'purple';
            if (accent === 'blue') color = '#3b82f6';
            else if (accent === 'green') color = '#10b981';
            else if (accent === 'orange') color = '#f59e0b';
            else if (accent === 'red') color = '#ef4444';
            else color = '#8b5cf6'; // purple
            
            pulseClass = 'marker-medium';
        } else {
            if (level === 'High') {
                color = '#ef4444'; // red
                pulseClass = 'marker-high';
            } else if (level === 'Medium') {
                color = '#fbbf24'; // yellow
                pulseClass = 'marker-medium';
            }
        }

        return L.divIcon({
            className: 'custom-leaflet-marker',
            html: `<div class="marker-pin ${pulseClass}" style="--glow-color: ${color}; width: ${iconWidth}px; height: ${iconHeight}px;"></div>`,
            iconSize: [iconWidth, iconHeight],
            iconAnchor: [iconWidth / 2, iconHeight / 2],
            popupAnchor: [0, -iconHeight / 2]
        });
    }

    // 3. Populate Map Markers (Marker Clustering)
    const markerCluster = L.markerClusterGroup({
        showCoverageOnHover: false,
        zoomToBoundsOnClick: true,
        spiderfyOnMaxZoom: true
    });
    
    const markersMap = new Map(); // Store markers to open them by search

    countriesData.forEach(country => {
        if (country.latitude !== null && country.longitude !== null) {
            const lat = parseFloat(country.latitude);
            const lon = parseFloat(country.longitude);
            if (isNaN(lat) || isNaN(lon)) {
                return;
            }
            const riskLevel = country.risk ? country.risk.risk_level : 'None';
            const riskScore = country.risk ? country.risk.total_score : 'N/A';
            
            let badgeColor = 'secondary';
            if (riskLevel === 'High') badgeColor = 'danger';
            else if (riskLevel === 'Medium') badgeColor = 'warning text-dark';
            else if (riskLevel === 'Low') badgeColor = 'success';

            // Build dynamic modern popup content
            const temp = country.weather ? UserPrefs.formatTemp(parseFloat(country.weather.temperature)) : 'N/A';
            const wind = country.weather ? UserPrefs.formatWind(parseFloat(country.weather.wind_speed)) : 'N/A';
            const rain = country.weather ? country.weather.rainfall + ' mm' : 'N/A';
            const gdp = country.economic ? '$' + UserPrefs.formatNumber(parseFloat(country.economic.gdp)) + ' B' : 'N/A';
            const inflation = country.economic ? UserPrefs.formatNumber(parseFloat(country.economic.inflation)) + '%' : 'N/A';
            const currency = country.currency_code ? `${country.currency_name} (${country.currency_code})` : 'N/A';
            const lastUpdated = country.risk && country.risk.updated_at ? UserPrefs.formatDate(country.risk.updated_at) : 'N/A';

            const popupContent = `
                <div style="font-family:'Plus Jakarta Sans',sans-serif; min-width:240px; padding: 4px;">
                    <div class="d-flex align-items-center gap-2 mb-2 border-bottom pb-2" style="border-color: rgba(255,255,255,0.08) !important;">
                        ${country.flag_png ? `<img src="${country.flag_png}" alt="${country.name} Flag" style="width: 24px; height: 16px; border-radius: 2px;">` : '🏳️'}
                        <h6 style="font-weight:700; margin: 0; font-size:14.5px; color: #ffffff;">${country.name}</h6>
                    </div>
                    <div style="font-size: 11.5px; line-height: 1.7; color: rgba(255, 255, 255, 0.65);">
                        <div style="display:flex; justify-content:space-between; border-bottom:1px solid rgba(255,255,255,0.04); padding: 2px 0;">
                            <span>Risk Level:</span> 
                            <span class="badge bg-${badgeColor}" style="font-size:10px !important; padding: 2px 6px !important;">${riskLevel}</span>
                        </div>
                        <div style="display:flex; justify-content:space-between; border-bottom:1px solid rgba(255,255,255,0.04); padding: 2px 0;">
                            <span>Risk Score:</span> <strong style="color:#ffffff">${riskScore}</strong>
                        </div>
                        <div style="display:flex; justify-content:space-between; border-bottom:1px solid rgba(255,255,255,0.04); padding: 2px 0;">
                            <span>Temperature:</span> <strong style="color:#ffffff">${temp}</strong>
                        </div>
                        <div style="display:flex; justify-content:space-between; border-bottom:1px solid rgba(255,255,255,0.04); padding: 2px 0;">
                            <span>Wind Speed:</span> <strong style="color:#ffffff">${wind}</strong>
                        </div>
                        <div style="display:flex; justify-content:space-between; border-bottom:1px solid rgba(255,255,255,0.04); padding: 2px 0;">
                            <span>Rainfall:</span> <strong style="color:#ffffff">${rain}</strong>
                        </div>
                        <div style="display:flex; justify-content:space-between; border-bottom:1px solid rgba(255,255,255,0.04); padding: 2px 0;">
                            <span>GDP:</span> <strong style="color:#ffffff">${gdp}</strong>
                        </div>
                        <div style="display:flex; justify-content:space-between; border-bottom:1px solid rgba(255,255,255,0.04); padding: 2px 0;">
                            <span>Inflation:</span> <strong style="color:#ffffff">${inflation}</strong>
                        </div>
                        <div style="display:flex; justify-content:space-between; border-bottom:1px solid rgba(255,255,255,0.04); padding: 2px 0;">
                            <span>Currency:</span> <strong style="color:#ffffff">${currency}</strong>
                        </div>
                        <div style="display:flex; justify-content:space-between; padding-top: 4px;">
                            <span>Last Updated:</span> <strong style="color:#ffffff">${lastUpdated}</strong>
                        </div>
                        <div style="margin-top: 10px;">
                            <a href="{{ url('/country') }}/${country.id}" class="btn btn-sm btn-primary w-100 text-decoration-none" style="font-size: 11px !important; padding: 4px 10px !important; border-radius: 8px; justify-content: center; color: #ffffff !important; font-weight: 700;">
                                View Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            `;

            const marker = L.marker([lat, lon], {
                icon: getMarkerIcon(riskLevel)
            }).bindPopup(popupContent);

            const showLabel = localStorage.getItem('show_country_label') === 'on';
            if (showLabel) {
                marker.bindTooltip(country.name, {
                    permanent: true,
                    direction: 'top',
                    className: 'leaflet-country-tooltip'
                });
            }

            markerCluster.addLayer(marker);
            markersMap.set(country.id, marker);
        }
    });
    map.addLayer(markerCluster);

    // 4. Search Autocomplete Implementation
    const searchInput = document.getElementById('mapSearch');
    const searchResults = document.getElementById('searchResults');

    searchInput.addEventListener('input', function () {
        const query = this.value.toLowerCase().trim();
        searchResults.innerHTML = '';
        
        if (!query) {
            searchResults.style.display = 'none';
            return;
        }

        const matches = countriesData.filter(c => 
            c.latitude !== null && c.longitude !== null &&
            !isNaN(parseFloat(c.latitude)) && !isNaN(parseFloat(c.longitude)) &&
            (c.name.toLowerCase().includes(query) || c.cca3.toLowerCase().includes(query))
        ).slice(0, 10);

        if (matches.length === 0) {
            searchResults.innerHTML = `<div class="map-search-item text-muted text-center py-2" style="font-size:12px;">No countries found</div>`;
            searchResults.style.display = 'block';
            return;
        }

        matches.forEach(country => {
            const riskLevel = country.risk ? country.risk.risk_level : 'No Data';
            let color = '#94a3b8'; // grey
            if (riskLevel === 'High') color = '#ef4444';
            else if (riskLevel === 'Medium') color = '#fbbf24';
            else if (riskLevel === 'Low') color = '#10b981';

            const item = document.createElement('div');
            item.className = 'map-search-item';
            item.innerHTML = `
                <div class="d-flex align-items-center gap-2">
                    ${country.flag_png ? `<img src="${country.flag_png}" alt="" style="width: 18px; height: 12px; border-radius: 1px;">` : '🏳️'}
                    <span>${country.name}</span>
                </div>
                <span class="badge" style="background-color: ${color}; font-size: 9px;">${riskLevel}</span>
            `;

            item.addEventListener('click', function () {
                searchInput.value = country.name;
                searchResults.style.display = 'none';
                
                // Pan/Zoom map to marker and open popup
                const marker = markersMap.get(country.id);
                if (marker) {
                    // If marker is in cluster, expand cluster or directly zoom to
                    markerCluster.zoomToShowLayer(marker, function() {
                        marker.openPopup();
                    });
                } else {
                    map.setView([parseFloat(country.latitude), parseFloat(country.longitude)], 5);
                }
            });

            searchResults.appendChild(item);
        });
        
        searchResults.style.display = 'block';
    });

    // Close search results when clicking outside
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.map-search-container')) {
            searchResults.style.display = 'none';
        }
    });

    // 5. Fullscreen Toggle (HTML5 Fullscreen API)
    const btnFullscreen = document.getElementById('btnFullscreen');
    const mapWrapper = document.getElementById('mapWrapper');

    btnFullscreen.addEventListener('click', function () {
        if (!document.fullscreenElement) {
            mapWrapper.requestFullscreen().then(() => {
                btnFullscreen.innerHTML = '<span>🚪</span> Exit Fullscreen';
            }).catch(err => {
                alert(`Error enabling fullscreen: ${err.message}`);
            });
        } else {
            document.exitFullscreen();
        }
    });

    document.addEventListener('fullscreenchange', function() {
        if (!document.fullscreenElement) {
            btnFullscreen.innerHTML = '<span>📺</span> Fullscreen';
        }
        setTimeout(() => {
            map.invalidateSize();
        }, 200);
    });

    // 6. Reset View
    const btnResetView = document.getElementById('btnResetView');
    btnResetView.addEventListener('click', function () {
        map.setView([20, 0], 2);
        searchInput.value = '';
        searchResults.style.display = 'none';
    });

    // 7. Route Planner Logic (Haversine & Draw Polyline)
    let routePolyline = null;
    let originMarker = null;
    let destMarker = null;

    function haversine(lat1, lon1, lat2, lon2) {
        const R = 6371; // km
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                  Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                  Math.sin(dLon / 2) * Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c;
    }

    const routeForm = document.getElementById('routePlannerForm');
    routeForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const originVal = document.getElementById('originCountry').value;
        const destVal = document.getElementById('destinationCountry').value;
        const mode = document.querySelector('input[name="transport_mode"]:checked').value;

        if (!originVal || !destVal) {
            alert("Please select both origin and destination countries.");
            return;
        }

        if (originVal === destVal) {
            alert("Origin and Destination cannot be the same country.");
            return;
        }

        const originCountry = countriesData.find(c => c.id == originVal);
        const destCountry = countriesData.find(c => c.id == destVal);

        if (!originCountry || !destCountry) return;

        // Clear previous route layers
        if (routePolyline) map.removeLayer(routePolyline);
        if (originMarker) map.removeLayer(originMarker);
        if (destMarker) map.removeLayer(destMarker);

        const lat1 = parseFloat(originCountry.latitude);
        const lon1 = parseFloat(originCountry.longitude);
        const lat2 = parseFloat(destCountry.latitude);
        const lon2 = parseFloat(destCountry.longitude);

        // Distance & Time calculations
        const distance = haversine(lat1, lon1, lat2, lon2);
        
        let speed = 30; // Ship default
        if (mode === 'Air') speed = 800;
        else if (mode === 'Truck') speed = 70;

        const hours = distance / speed;
        let timeText = '';
        if (mode === 'Ship') {
            timeText = Math.round(hours / 24) + ' Days';
        } else {
            timeText = Math.round(hours) + ' Hours';
        }

        const routeAnim = localStorage.getItem('show_route_animation') !== 'off';
        // Draw Polyline (Dashed line)
        routePolyline = L.polyline([[lat1, lon1], [lat2, lon2]], {
            color: '#a78bfa',
            weight: 4,
            dashArray: '8, 8',
            opacity: 0.8,
            className: routeAnim ? 'animated-route-path' : ''
        }).addTo(map);

        // Add special route markers (Origin & Destination flags)
        const originFlagIcon = L.divIcon({
            className: 'custom-leaflet-marker',
            html: `<div class="marker-pin" style="--glow-color: #6366f1; width:16px; height:16px;"></div>`,
            iconSize: [16, 16],
            iconAnchor: [8, 8]
        });
        const destFlagIcon = L.divIcon({
            className: 'custom-leaflet-marker',
            html: `<div class="marker-pin" style="--glow-color: #ec4899; width:16px; height:16px;"></div>`,
            iconSize: [16, 16],
            iconAnchor: [8, 8]
        });

        originMarker = L.marker([lat1, lon1], { icon: originFlagIcon }).addTo(map)
            .bindPopup(`<strong>Origin: ${originCountry.name}</strong>`);
        destMarker = L.marker([lat2, lon2], { icon: destFlagIcon }).addTo(map)
            .bindPopup(`<strong>Destination: ${destCountry.name}</strong>`);

        // Fit map bounds to show route polyline
        map.fitBounds(routePolyline.getBounds(), { padding: [50, 50] });

        // Update Route Summary Panel
        document.getElementById('routeDetailsCard').classList.remove('d-none');
        document.getElementById('selectedModeBadge').innerText = mode;
        
        document.getElementById('summaryOrigin').innerText = originCountry.name;
        document.getElementById('summaryDest').innerText = destCountry.name;
        document.getElementById('summaryDistance').innerText = UserPrefs.formatDist(distance);
        document.getElementById('summaryTime').innerText = timeText;

        // Fetch Risk variables
        const oRisk = originCountry.risk && originCountry.risk.total_score ? (parseFloat(originCountry.risk.total_score) || 0) : 0;
        const dRisk = destCountry.risk && destCountry.risk.total_score ? (parseFloat(destCountry.risk.total_score) || 0) : 0;
        const avgRisk = parseFloat(((oRisk + dRisk) / 2).toFixed(2)) || 0;
        
        // Find highest risk country
        let highestName = originCountry.name;
        let highestLevel = originCountry.risk ? originCountry.risk.risk_level : 'Low';
        let highestScore = oRisk;
        if (dRisk > oRisk) {
            highestName = destCountry.name;
            highestLevel = destCountry.risk ? destCountry.risk.risk_level : 'Low';
            highestScore = dRisk;
        }
        document.getElementById('summaryAvgRisk').innerHTML = `${avgRisk} <span class="badge bg-${avgRisk >= 70 ? 'danger' : (avgRisk >= 40 ? 'warning text-dark' : 'success')}" style="font-size:9.5px; margin-left:4px;">${avgRisk >= 70 ? 'High' : (avgRisk >= 40 ? 'Medium' : 'Low')}</span>`;
        document.getElementById('summaryHighestRisk').innerText = `${highestName} (${highestScore} - ${highestLevel})`;

        // Weather text info
        const oWeather = originCountry.weather;
        const dWeather = destCountry.weather;
        document.getElementById('originFlag').innerHTML = originCountry.flag_png ? `<img src="${originCountry.flag_png}" alt="" style="width: 16px; height: 11px;">` : '🏳️';
        document.getElementById('originWeatherName').innerText = originCountry.name;
        document.getElementById('originWeatherText').innerText = oWeather ? `Temp: ${UserPrefs.formatTemp(parseFloat(oWeather.temperature))}, Wind: ${UserPrefs.formatWind(parseFloat(oWeather.wind_speed))}, Rain: ${oWeather.rainfall} mm, Storm: ${oWeather.storm_risk.toUpperCase()}` : 'No Weather Data';

        document.getElementById('destFlag').innerHTML = destCountry.flag_png ? `<img src="${destCountry.flag_png}" alt="" style="width: 16px; height: 11px;">` : '🏳️';
        document.getElementById('destWeatherName').innerText = destCountry.name;
        document.getElementById('destWeatherText').innerText = dWeather ? `Temp: ${UserPrefs.formatTemp(parseFloat(dWeather.temperature))}, Wind: ${UserPrefs.formatWind(parseFloat(dWeather.wind_speed))}, Rain: ${dWeather.rainfall} mm, Storm: ${dWeather.storm_risk.toUpperCase()}` : 'No Weather Data';

        // Recommendation Text
        const recAlert = document.getElementById('recAlert');
        const recTitle = document.getElementById('recTitle');
        const recText = document.getElementById('recText');

        let recTitleStr = '🟢 LOW ROUTE RISK';
        let recBodyStr = `Route between ${originCountry.name} and ${destCountry.name} is stable. Logistics and supply operations can proceed under standard protocols.`;
        recAlert.style.borderLeft = '4px solid #10b981';

        if (avgRisk >= 70) {
            recTitleStr = '🔴 CRITICAL ROUTE RISK';
            recBodyStr = `High risk alerts detected! Severe geopolitical tensions, severe economic/inflation volatility, or weather threats exist. Consider utilizing dual-sourcing, routing through third-party hubs, or adding emergency buffer lead times.`;
            recAlert.style.borderLeft = '4px solid #ef4444';
        } else if (avgRisk >= 40) {
            recTitleStr = '🟡 MODERATE ROUTE RISK';
            recBodyStr = `Caution is advised. Check current weather patterns and news alerts daily. Validate cargo transit insurance parameters and prepare contingency logistics plans.`;
            recAlert.style.borderLeft = '4px solid #fbbf24';
        }
        
        // Mode specific warning
        if (mode === 'Ship') {
            recBodyStr += ' Verify harbor/dock container clearances and look for maritime delays.';
        } else if (mode === 'Air') {
            recBodyStr += ' Check schedules for cargo flight consistency and local airport logistics delays.';
        } else {
            recBodyStr += ' Ensure border-crossing documentation is ready and monitor road/terrain conditions.';
        }

        recTitle.innerText = recTitleStr;
        recText.innerText = recBodyStr;

        // Populate Risk Bars values
        const oWeatherSc = originCountry.risk && originCountry.risk.weather_score ? (parseFloat(originCountry.risk.weather_score) || 0) : 0;
        const dWeatherSc = destCountry.risk && destCountry.risk.weather_score ? (parseFloat(destCountry.risk.weather_score) || 0) : 0;
        const avgWeather = Math.round((oWeatherSc + dWeatherSc) / 2) || 0;
        updateProgress('barWeather', 'barWeatherVal', avgWeather);

        const oEconSc = originCountry.risk && originCountry.risk.inflation_score ? (parseFloat(originCountry.risk.inflation_score) || 0) : 0;
        const dEconSc = destCountry.risk && destCountry.risk.inflation_score ? (parseFloat(destCountry.risk.inflation_score) || 0) : 0;
        const avgEcon = Math.round((oEconSc + dEconSc) / 2) || 0;
        updateProgress('barEconomic', 'barEconomicVal', avgEcon);

        const oNewsSc = originCountry.risk && originCountry.risk.news_score ? (parseFloat(originCountry.risk.news_score) || 0) : 0;
        const dNewsSc = destCountry.risk && destCountry.risk.news_score ? (parseFloat(destCountry.risk.news_score) || 0) : 0;
        const avgNews = Math.round((oNewsSc + dNewsSc) / 2) || 0;
        updateProgress('barNews', 'barNewsVal', avgNews);

        const oCurrSc = originCountry.risk && originCountry.risk.currency_score ? (parseFloat(originCountry.risk.currency_score) || 0) : 0;
        const dCurrSc = destCountry.risk && destCountry.risk.currency_score ? (parseFloat(destCountry.risk.currency_score) || 0) : 0;
        const avgCurr = Math.round((oCurrSc + dCurrSc) / 2) || 0;
        updateProgress('barCurrency', 'barCurrencyVal', avgCurr);

        updateProgress('barTotal', 'barTotalVal', Math.round(avgRisk), true);
    });

    function updateProgress(barId, valId, score, isTotal = false) {
        const barElement = document.getElementById(barId);
        const valElement = document.getElementById(valId);
        
        barElement.style.width = score + '%';
        valElement.innerText = score + '%';

        // Reset classes
        barElement.className = 'progress-bar';
        
        if (score >= 70) {
            barElement.classList.add('progress-risk-high');
            if (isTotal) valElement.className = 'fw-bold text-danger';
        } else if (score >= 40) {
            barElement.classList.add('progress-risk-medium');
            if (isTotal) valElement.className = 'fw-bold text-warning';
        } else {
            barElement.classList.add('progress-risk-low');
            if (isTotal) valElement.className = 'fw-bold text-success';
        }
    }
});
</script>
@endpush
