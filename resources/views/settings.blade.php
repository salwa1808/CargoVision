@extends('layouts.app')

@push('styles')
<style>
    .settings-section-title {
        font-size: 13px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        color: var(--accent-primary);
        background: var(--accent-primary);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        padding-bottom: 8px;
        margin-bottom: 20px;
    }
    .setting-card {
        margin-bottom: 24px;
    }
    .form-check-input {
        background-color: rgba(255,255,255,0.05) !important;
        border-color: rgba(255,255,255,0.15) !important;
        cursor: pointer;
    }
    .form-check-input:checked {
        background-color: var(--theme-color-main) !important;
        border-color: var(--theme-color-main) !important;
    }
    .form-check-label {
        cursor: pointer;
        font-weight: 500;
        font-size: 14px;
        color: rgba(255,255,255,0.85);
    }
    .form-text {
        color: rgba(255,255,255,0.4) !important;
        font-size: 12px;
    }
    .about-value {
        font-weight: 600;
        color: #ffffff;
    }
    .about-label {
        color: rgba(255,255,255,0.5);
    }
    .settings-slider-label {
        font-size: 12px;
        color: rgba(255,255,255,0.6);
        margin-top: 4px;
        display: flex;
        justify-content: space-between;
    }
    .form-range::-webkit-slider-thumb {
        background: var(--theme-color-main) !important;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-5 mt-2">
        <div>
            <h1 class="h3 fw-bold mb-1" style="letter-spacing: -0.02em;">
                ⚙️ Platform Configuration
            </h1>
            <p class="text-muted fw-medium mb-0">
                Customize look, re-theme controls, toggle desktop push alerts, and manage local storage cache.
            </p>
        </div>
        <div class="d-flex gap-2">
            <button onclick="resetToDefault()" class="btn btn-outline-light d-inline-flex align-items-center gap-2 fw-semibold" style="border-radius: 12px; border-color: var(--border-color);">
                Reset to Default
            </button>
            <button onclick="saveSettings()" class="btn btn-primary d-inline-flex align-items-center gap-2 fw-bold" style="border-radius: 12px;">
                Save Settings
            </button>
        </div>
    </div>

    <div class="row">
        <!-- Left Column: Layout & Appearance Settings -->
        <div class="col-lg-6">
            <!-- Section 1: Appearance -->
            <div class="card setting-card shadow-sm">
                <div class="card-header">
                    <span>🎨 Section 1: Appearance & Themes</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em;">Theme Mode</label>
                            <select id="themeSelect" class="form-select">
                                <option value="dark">Dark Mode</option>
                                <option value="light">Light Mode</option>
                                <option value="auto">Auto (System Default)</option>
                            </select>
                            <div class="form-text">Change body rendering theme.</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em;">Accent Color</label>
                            <select id="accentSelect" class="form-select">
                                <option value="purple">Purple (Default)</option>
                                <option value="blue">Blue</option>
                                <option value="green">Green</option>
                                <option value="orange">Orange</option>
                                <option value="red">Red</option>
                            </select>
                            <div class="form-text">Change active links and styling colors.</div>
                        </div>

                        <div class="col-12 mt-4">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="sidebarSwitch">
                                <label class="form-check-label" for="sidebarSwitch">Collapse Sidebar Navigation</label>
                                <div class="form-text">Collapse sidebar on desktop viewports to icon-only.</div>
                            </div>

                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="animationSwitch" checked>
                                <label class="form-check-label" for="animationSwitch">Enable Layout Animations</label>
                                <div class="form-text">Enable CSS transition properties and page loads.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Dashboard -->
            <div class="card setting-card shadow-sm">
                <div class="card-header">
                    <span>📊 Section 2: Dashboard Preferences</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" id="autoRefreshSwitch" checked>
                                <label class="form-check-label" for="autoRefreshSwitch">Auto Refresh Dashboard</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em;">Refresh Interval</label>
                            <select id="refreshIntervalSelect" class="form-select">
                                <option value="15000">15 Seconds</option>
                                <option value="30000">30 Seconds</option>
                                <option value="60000">1 Minute</option>
                                <option value="300000">5 Minutes</option>
                            </select>
                        </div>

                        <div class="col-12 mt-4 border-top pt-3" style="border-color: rgba(255,255,255,0.05) !important;">
                            <label class="form-label text-muted small fw-bold text-uppercase mb-3" style="letter-spacing: 0.05em;">Widget & Page Visibility</label>
                            
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="showWeatherSwitch" checked>
                                        <label class="form-check-label" for="showWeatherSwitch">Show Weather Widget</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="showNewsSwitch" checked>
                                        <label class="form-check-label" for="showNewsSwitch">Show News Widget</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="showAnalyticsSwitch" checked>
                                        <label class="form-check-label" for="showAnalyticsSwitch">Show Analytics Widget</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="showPortsSwitch" checked>
                                        <label class="form-check-label" for="showPortsSwitch">Show Ports Widget</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Notifications -->
            <div class="card setting-card shadow-sm">
                <div class="card-header">
                    <span>🔔 Section 3: Background Notification Triggers</span>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="enableToastSwitch" checked>
                        <label class="form-check-label" for="enableToastSwitch">Enable Toast Notifications</label>
                        <div class="form-text">Display alert popup cards in the top-right corner.</div>
                    </div>

                    <div class="ps-4 border-start mb-4" style="border-color: rgba(255,255,255,0.06) !important;">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="alertHighRiskSwitch" checked>
                            <label class="form-check-label" for="alertHighRiskSwitch">High Risk Alerts</label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="alertWeatherSwitch" checked>
                            <label class="form-check-label" for="alertWeatherSwitch">Weather Alerts</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="alertNewsSwitch" checked>
                            <label class="form-check-label" for="alertNewsSwitch">News Alerts</label>
                        </div>
                    </div>

                    <div class="form-check form-switch border-top pt-3" style="border-color: rgba(255,255,255,0.05) !important;">
                        <input class="form-check-input" type="checkbox" id="desktopNotificationSwitch">
                        <label class="form-check-label" for="desktopNotificationSwitch">Enable Desktop Push Notification</label>
                        <div class="form-text">Use system-level native OS notifications via the Browser Notification API.</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Interactive Map & User Preference Settings -->
        <div class="col-lg-6">
            <!-- Section 4: Global Map -->
            <div class="card setting-card shadow-sm">
                <div class="card-header">
                    <span>🗺️ Section 4: Global Map Options</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="defaultMapZoomSlider" class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em;">Default Map Zoom</label>
                            <input type="range" class="form-range" min="1" max="10" id="defaultMapZoomSlider" value="2">
                            <div class="settings-slider-label">
                                <span>Min (World View)</span>
                                <span id="sliderZoomVal" class="fw-bold text-white">2</span>
                                <span>Max (Regional View)</span>
                            </div>
                        </div>

                        <div class="col-md-6 mt-4">
                            <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em;">Marker Pin Size</label>
                            <select id="defaultMarkerSizeSelect" class="form-select">
                                <option value="small">Small</option>
                                <option value="medium">Medium (Default)</option>
                                <option value="large">Large</option>
                            </select>
                        </div>

                        <div class="col-md-6 mt-4">
                            <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em;">Marker Color Rules</label>
                            <select id="markerColorModeSelect" class="form-select">
                                <option value="by_risk">By Risk Level (Low/Med/High)</option>
                                <option value="single">Single Accent Color Mode</option>
                            </select>
                        </div>

                        <div class="col-12 mt-4 border-top pt-3" style="border-color: rgba(255,255,255,0.05) !important;">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="showRouteAnimationSwitch" checked>
                                <label class="form-check-label" for="showRouteAnimationSwitch">Show Route Transit Animations</label>
                                <div class="form-text">Draw animated flowing dashes along Calculated straight-line paths.</div>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="showCountryLabelSwitch">
                                <label class="form-check-label" for="showCountryLabelSwitch">Show Persistent Country Labels</label>
                                <div class="form-text">Always show country names text above markers pins.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 5: Analytics -->
            <div class="card setting-card shadow-sm">
                <div class="card-header">
                    <span>📈 Section 5: Analytics Settings</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em;">Default Chart Format</label>
                            <select id="defaultChartSelect" class="form-select">
                                <option value="bar">Bar Chart</option>
                                <option value="line">Line Chart</option>
                                <option value="pie">Pie Chart</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em;">Animation Transition Speed</label>
                            <select id="chartAnimationSpeedSelect" class="form-select">
                                <option value="slow">Slow</option>
                                <option value="normal">Normal (Default)</option>
                                <option value="fast">Fast</option>
                            </select>
                        </div>

                        <div class="col-12 mt-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="enableChartAnimationSwitch" checked>
                                <label class="form-check-label" for="enableChartAnimationSwitch">Enable Chart Animations</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 7: User Preferences -->
            <div class="card setting-card shadow-sm">
                <div class="card-header">
                    <span>🌍 Section 7: Locale & Metrics Settings</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em;">Interface Language</label>
                            <select id="prefLangSelect" class="form-select">
                                <option value="en">English</option>
                                <option value="id">Indonesia</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em;">Date Format</label>
                            <select id="prefDateFormatSelect" class="form-select">
                                <option value="DD/MM/YYYY">DD/MM/YYYY</option>
                                <option value="MM/DD/YYYY">MM/DD/YYYY</option>
                                <option value="YYYY/MM/DD">YYYY/MM/DD</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em;">Number Format</label>
                            <select id="prefNumberFormatSelect" class="form-select">
                                <option value="1,000.00">1,000.00 (US/UK)</option>
                                <option value="1.000,00">1.000,00 (EU/ID)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em;">Temperature Unit</label>
                            <select id="prefTempUnitSelect" class="form-select">
                                <option value="Celsius">Celsius (°C)</option>
                                <option value="Fahrenheit">Fahrenheit (°F)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em;">Wind Speed Unit</label>
                            <select id="prefWindUnitSelect" class="form-select">
                                <option value="km/h">km/h</option>
                                <option value="mph">mph</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em;">Distance Unit</label>
                            <select id="prefDistUnitSelect" class="form-select">
                                <option value="km">km</option>
                                <option value="mile">mile</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 6 & 8: Data Utilities & About Metadata (Full-width row) -->
        <div class="col-12">
            <div class="row">
                <!-- Section 6: Data Utilities -->
                <div class="col-md-6">
                    <div class="card setting-card shadow-sm">
                        <div class="card-header">
                            <span>💾 Section 6: Local Storage & Data Actions</span>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small mb-4">Quick shortcuts to flush storage cache parameters or force refresh backend modules.</p>
                            <div class="row g-2">
                                <div class="col-6">
                                    <button onclick="refreshDashboardData()" class="btn btn-outline-light w-100 justify-content-center text-nowrap py-2" style="font-size:12.5px !important;">
                                        🔄 Reload Dashboard Data
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button onclick="refreshWeatherData()" class="btn btn-outline-light w-100 justify-content-center text-nowrap py-2" style="font-size:12.5px !important;">
                                        ⛈️ Refresh Weather Module
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button onclick="refreshAnalyticsData()" class="btn btn-outline-light w-100 justify-content-center text-nowrap py-2" style="font-size:12.5px !important;">
                                        📈 Refresh Analytics Module
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button onclick="clearLocalCache()" class="btn btn-danger w-100 justify-content-center text-nowrap py-2" style="font-size:12.5px !important;">
                                        🗑️ Clear Local Cache
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 8: About Information -->
                <div class="col-md-6">
                    <div class="card setting-card shadow-sm">
                        <div class="card-header">
                            <span>ℹ️ Section 8: System Metadata</span>
                        </div>
                        <div class="card-body py-3">
                            <table class="w-100" style="font-size:13px; line-height:1.9;">
                                <tr>
                                    <td class="about-label">Project Name</td>
                                    <td class="text-end about-value">Global Supply Chain Risk Intelligence</td>
                                </tr>
                                <tr>
                                    <td class="about-label">Version</td>
                                    <td class="text-end about-value">v1.2.0 (Enterprise)</td>
                                </tr>
                                <tr>
                                    <td class="about-label">Developer</td>
                                    <td class="text-end about-value">CargoVision Team</td>
                                </tr>
                                <tr>
                                    <td class="about-label">Laravel Version</td>
                                    <td class="text-end about-value">{{ app()->version() }}</td>
                                </tr>
                                <tr>
                                    <td class="about-label">PHP Version</td>
                                    <td class="text-end about-value">{{ PHP_VERSION }}</td>
                                </tr>
                                <tr>
                                    <td class="about-label">Database Connection</td>
                                    <td class="text-end about-value">{{ config('database.default') }}</td>
                                </tr>
                                <tr>
                                    <td class="about-label">Last Risk Calculation</td>
                                    <td class="text-end about-value">
                                        {{ \App\Models\RiskScore::latest('updated_at')->first()?->updated_at?->diffForHumans() ?? 'N/A' }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bottom Action Save/Reset -->
        <div class="col-12 text-end mb-5">
            <button onclick="resetToDefault()" class="btn btn-outline-light me-2 fw-semibold px-4 py-2" style="border-radius: 12px; border-color: var(--border-color);">
                Reset to Default
            </button>
            <button onclick="saveSettings()" class="btn btn-primary fw-bold px-5 py-2" style="border-radius: 12px;">
                Save Preferences Settings
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    // 1. Zoom slider live value update
    const slider = document.getElementById('defaultMapZoomSlider');
    const sliderVal = document.getElementById('sliderZoomVal');
    slider.addEventListener('input', function() {
        sliderVal.innerText = this.value;
    });

    // 2. Load settings from LocalStorage
    function loadSavedSettings() {
        // Section 1
        document.getElementById('themeSelect').value = localStorage.getItem('theme') || 'dark';
        document.getElementById('accentSelect').value = localStorage.getItem('accent_color') || 'blue';
        document.getElementById('sidebarSwitch').checked = localStorage.getItem('sidebar_state') === 'collapsed';
        document.getElementById('animationSwitch').checked = localStorage.getItem('enable_animations') !== 'off';

        // Section 2
        document.getElementById('autoRefreshSwitch').checked = localStorage.getItem('auto_refresh') !== 'off';
        document.getElementById('refreshIntervalSelect').value = localStorage.getItem('refresh_interval') || '15000';
        document.getElementById('showWeatherSwitch').checked = localStorage.getItem('show_weather_widget') !== 'off';
        document.getElementById('showNewsSwitch').checked = localStorage.getItem('show_news_widget') !== 'off';
        document.getElementById('showAnalyticsSwitch').checked = localStorage.getItem('show_analytics_widget') !== 'off';
        document.getElementById('showPortsSwitch').checked = localStorage.getItem('show_ports_widget') !== 'off';

        // Section 3
        document.getElementById('enableToastSwitch').checked = localStorage.getItem('enable_toast') !== 'off';
        document.getElementById('alertHighRiskSwitch').checked = localStorage.getItem('alert_high_risk') !== 'off';
        document.getElementById('alertWeatherSwitch').checked = localStorage.getItem('alert_weather') !== 'off';
        document.getElementById('alertNewsSwitch').checked = localStorage.getItem('alert_news') !== 'off';
        document.getElementById('desktopNotificationSwitch').checked = localStorage.getItem('enable_desktop') === 'on';

        // Section 4
        const savedZoom = localStorage.getItem('default_map_zoom') || '2';
        document.getElementById('defaultMapZoomSlider').value = savedZoom;
        sliderVal.innerText = savedZoom;
        document.getElementById('defaultMarkerSizeSelect').value = localStorage.getItem('default_marker_size') || 'medium';
        document.getElementById('markerColorModeSelect').value = localStorage.getItem('marker_color_mode') || 'by_risk';
        document.getElementById('showRouteAnimationSwitch').checked = localStorage.getItem('show_route_animation') !== 'off';
        document.getElementById('showCountryLabelSwitch').checked = localStorage.getItem('show_country_label') === 'on';

        // Section 5
        document.getElementById('defaultChartSelect').value = localStorage.getItem('default_chart') || 'bar';
        document.getElementById('chartAnimationSpeedSelect').value = localStorage.getItem('chart_animation_speed') || 'normal';
        document.getElementById('enableChartAnimationSwitch').checked = localStorage.getItem('enable_chart_animation') !== 'off';

        // Section 7
        document.getElementById('prefLangSelect').value = localStorage.getItem('pref_lang') || 'en';
        document.getElementById('prefDateFormatSelect').value = localStorage.getItem('pref_date_format') || 'DD/MM/YYYY';
        document.getElementById('prefNumberFormatSelect').value = localStorage.getItem('pref_number_format') || '1,000.00';
        document.getElementById('prefTempUnitSelect').value = localStorage.getItem('pref_temp_unit') || 'Celsius';
        document.getElementById('prefWindUnitSelect').value = localStorage.getItem('pref_wind_unit') || 'km/h';
        document.getElementById('prefDistUnitSelect').value = localStorage.getItem('pref_dist_unit') || 'km';
    }

    loadSavedSettings();
});

// 3. Save Settings functionality
function saveSettings() {
    // Section 1
    localStorage.setItem('theme', document.getElementById('themeSelect').value);
    localStorage.setItem('accent_color', document.getElementById('accentSelect').value);
    localStorage.setItem('sidebar_state', document.getElementById('sidebarSwitch').checked ? 'collapsed' : 'expanded');
    localStorage.setItem('enable_animations', document.getElementById('animationSwitch').checked ? 'on' : 'off');
    
    // Section 2
    localStorage.setItem('auto_refresh', document.getElementById('autoRefreshSwitch').checked ? 'on' : 'off');
    localStorage.setItem('refresh_interval', document.getElementById('refreshIntervalSelect').value);
    localStorage.setItem('show_weather_widget', document.getElementById('showWeatherSwitch').checked ? 'on' : 'off');
    localStorage.setItem('show_news_widget', document.getElementById('showNewsSwitch').checked ? 'on' : 'off');
    localStorage.setItem('show_analytics_widget', document.getElementById('showAnalyticsSwitch').checked ? 'on' : 'off');
    localStorage.setItem('show_ports_widget', document.getElementById('showPortsSwitch').checked ? 'on' : 'off');

    // Section 3
    localStorage.setItem('enable_toast', document.getElementById('enableToastSwitch').checked ? 'on' : 'off');
    localStorage.setItem('alert_high_risk', document.getElementById('alertHighRiskSwitch').checked ? 'on' : 'off');
    localStorage.setItem('alert_weather', document.getElementById('alertWeatherSwitch').checked ? 'on' : 'off');
    localStorage.setItem('alert_news', document.getElementById('alertNewsSwitch').checked ? 'on' : 'off');
    
    const desktopChecked = document.getElementById('desktopNotificationSwitch').checked;
    localStorage.setItem('enable_desktop', desktopChecked ? 'on' : 'off');
    if (desktopChecked && typeof Notification !== 'undefined') {
        Notification.requestPermission();
    }

    // Section 4
    localStorage.setItem('default_map_zoom', document.getElementById('defaultMapZoomSlider').value);
    localStorage.setItem('default_marker_size', document.getElementById('defaultMarkerSizeSelect').value);
    localStorage.setItem('marker_color_mode', document.getElementById('markerColorModeSelect').value);
    localStorage.setItem('show_route_animation', document.getElementById('showRouteAnimationSwitch').checked ? 'on' : 'off');
    localStorage.setItem('show_country_label', document.getElementById('showCountryLabelSwitch').checked ? 'on' : 'off');

    // Section 5
    localStorage.setItem('default_chart', document.getElementById('defaultChartSelect').value);
    localStorage.setItem('chart_animation_speed', document.getElementById('chartAnimationSpeedSelect').value);
    localStorage.setItem('enable_chart_animation', document.getElementById('enableChartAnimationSwitch').checked ? 'on' : 'off');

    // Section 7
    localStorage.setItem('pref_lang', document.getElementById('prefLangSelect').value);
    localStorage.setItem('pref_date_format', document.getElementById('prefDateFormatSelect').value);
    localStorage.setItem('pref_number_format', document.getElementById('prefNumberFormatSelect').value);
    localStorage.setItem('pref_temp_unit', document.getElementById('prefTempUnitSelect').value);
    localStorage.setItem('pref_wind_unit', document.getElementById('prefWindUnitSelect').value);
    localStorage.setItem('pref_dist_unit', document.getElementById('prefDistUnitSelect').value);

    // Apply sidebar changes immediately
    const wrapper = document.getElementById('wrapper');
    if (document.getElementById('sidebarSwitch').checked) {
        if (wrapper) wrapper.classList.add('sidebar-collapsed');
    } else {
        if (wrapper) wrapper.classList.remove('sidebar-collapsed');
    }

    alert('Configuration settings saved successfully! Reloading to apply modifications.');
    window.location.reload();
}

// 4. Reset settings functionality
function resetToDefault() {
    if (confirm('Are you sure you want to clear cache and reset all configuration preferences to system defaults?')) {
        localStorage.clear();
        alert('All preferences have been restored to defaults.');
        window.location.reload();
    }
}

// 5. Data Actions
function refreshDashboardData() {
    alert('Dashboard Data Reload triggered.');
    window.location.href = '/';
}

function refreshWeatherData() {
    alert('Weather Module Reload triggered.');
    window.location.href = '/weather';
}

function refreshAnalyticsData() {
    alert('Analytics Module Reload triggered.');
    window.location.href = '/analytics';
}

function clearLocalCache() {
    if (confirm('Clear localStorage and sessionStorage?')) {
        localStorage.clear();
        sessionStorage.clear();
        alert('Browser cache cleared successfully.');
        window.location.reload();
    }
}
</script>
@endpush
