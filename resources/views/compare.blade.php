@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-5 mt-2">
        <div>
            <h1 class="h3 fw-bold mb-1" style="letter-spacing: -0.02em;">
                🔀 Supply Chain Compare
            </h1>
            <p class="text-muted fw-medium mb-0">
                Perform side-by-side risk audits and economic comparison between up to 3 countries.
            </p>
        </div>
        @if(!$compareCountries->isEmpty())
            <a href="/compare" class="btn btn-outline-light d-inline-flex align-items-center gap-2 fw-semibold" style="border-radius: 12px; border-color: var(--border-color);">
                Reset
            </a>
        @endif
    </div>

    <!-- Country Selection Panel -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header">
            <span>Selector Matrix (Maximum 3 countries)</span>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('compare') }}" id="compareForm">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em;">Country A</label>
                        <select name="countries[]" class="form-select compare-select">
                            <option value="">-- None --</option>
                            @foreach($countries as $c)
                                <option value="{{ $c->id }}" {{ in_array($c->id, $compareIds) && $compareIds[0] == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em;">Country B</label>
                        <select name="countries[]" class="form-select compare-select">
                            <option value="">-- None --</option>
                            @foreach($countries as $c)
                                <option value="{{ $c->id }}" {{ in_array($c->id, $compareIds) && count($compareIds) > 1 && $compareIds[1] == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em;">Country C</label>
                        <select name="countries[]" class="form-select compare-select">
                            <option value="">-- None --</option>
                            @foreach($countries as $c)
                                <option value="{{ $c->id }}" {{ in_array($c->id, $compareIds) && count($compareIds) > 2 && $compareIds[2] == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100 justify-content-center fw-bold" style="height: 48px;">
                            Compare Now
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Comparison Output Matrix -->
    @if($compareCountries->isEmpty())
        <div class="card text-center py-5 shadow-sm border-dashed" style="border: 2px dashed rgba(255,255,255,0.1) !important; background: transparent !important;">
            <div class="card-body">
                <div style="font-size: 56px;" class="mb-3">🔀</div>
                <h4 class="fw-bold">No Countries Selected</h4>
                <p class="text-muted mx-auto" style="max-width: 450px;">
                    Select at least one country in the selector matrix above to inspect regional risk indices, demographic properties, weather factors, and inflation ratings.
                </p>
            </div>
        </div>
    @else
        @php
            $colWidth = 100 / ($compareCountries->count() + 1);
        @endphp
        <div class="card shadow-sm mb-5">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0" style="table-layout: fixed; width: 100%;">
                        <thead>
                            <tr style="border-bottom: 2px solid var(--border-color); background: rgba(255,255,255,0.01);">
                                <th style="padding-left: 24px; width: {{ $colWidth }}%;" class="text-muted fw-bold uppercase">Attribute Matrix</th>
                                @foreach($compareCountries as $country)
                                    <th style="width: {{ $colWidth }}%;">
                                        <div class="d-flex align-items-center gap-2 py-2">
                                            @if($country->flag_png)
                                                <img src="{{ $country->flag_png }}" 
                                                     alt="{{ $country->name }} Flag" 
                                                     class="rounded-1 border" 
                                                     style="width: 32px; height: 20px; object-fit: cover; border-color: var(--border-color) !important;">
                                            @endif
                                            <div>
                                                <a href="{{ url('/country/' . $country->id) }}" class="fw-bold text-decoration-none text-white hover-underline">
                                                    {{ $country->name }}
                                                </a>
                                                <div class="text-muted small">{{ $country->cca3 }}</div>
                                            </div>
                                        </div>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <!-- CATEGORY: DEMOGRAPHICS -->
                            <tr class="table-group-header" style="background: rgba(255,255,255,0.02) !important;">
                                <td colspan="{{ $compareCountries->count() + 1 }}" style="padding-left: 24px;" class="fw-bold text-primary small uppercase tracking-wider">
                                    🌐 General Geography & Demographics
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left: 24px;" class="text-muted small fw-semibold">Official Name</td>
                                @foreach($compareCountries as $country)
                                    <td class="text-white small">{{ $country->official_name ?? '-' }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td style="padding-left: 24px;" class="text-muted small fw-semibold">Region / Subregion</td>
                                @foreach($compareCountries as $country)
                                    <td class="text-white">{{ $country->region ?? '-' }} / {{ $country->subregion ?? '-' }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td style="padding-left: 24px;" class="text-muted small fw-semibold">Capital City</td>
                                @foreach($compareCountries as $country)
                                    <td class="text-white">{{ $country->capital ?? '-' }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td style="padding-left: 24px;" class="text-muted small fw-semibold">Population Size</td>
                                @foreach($compareCountries as $country)
                                    <td class="text-white fw-bold">{{ number_format($country->population) }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td style="padding-left: 24px;" class="text-muted small fw-semibold">Coordinates</td>
                                @foreach($compareCountries as $country)
                                    <td class="text-white small">Lat: {{ $country->latitude }}, Long: {{ $country->longitude }}</td>
                                @endforeach
                            </tr>

                            <!-- CATEGORY: RISK PROFILE -->
                            <tr class="table-group-header" style="background: rgba(255,255,255,0.02) !important;">
                                <td colspan="{{ $compareCountries->count() + 1 }}" style="padding-left: 24px;" class="fw-bold text-danger small uppercase tracking-wider">
                                    🛡️ Risk Assessments & threat matrix
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left: 24px;" class="text-muted small fw-semibold">Overall Threat Level</td>
                                @foreach($compareCountries as $country)
                                    @php
                                        $risk = $country->riskScores->first();
                                        $riskLevel = $risk ? $risk->risk_level : 'None';
                                        $total = $risk ? $risk->total_score : 'N/A';
                                    @endphp
                                    <td>
                                        @if($riskLevel == 'High')
                                            <span class="badge bg-danger">High Threat ({{ $total }})</span>
                                        @elseif($riskLevel == 'Medium')
                                            <span class="badge bg-warning text-dark">Medium Threat ({{ $total }})</span>
                                        @elseif($riskLevel == 'Low')
                                            <span class="badge bg-success">Low Threat ({{ $total }})</span>
                                        @else
                                            <span class="badge bg-secondary">Unknown</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td style="padding-left: 24px;" class="text-muted small fw-semibold">Weather Risk Score</td>
                                @foreach($compareCountries as $country)
                                    @php $risk = $country->riskScores->first(); @endphp
                                    <td class="text-white fw-semibold">{{ $risk ? $risk->weather_score . ' / 10' : '-' }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td style="padding-left: 24px;" class="text-muted small fw-semibold">Inflation Risk Score</td>
                                @foreach($compareCountries as $country)
                                    @php $risk = $country->riskScores->first(); @endphp
                                    <td class="text-white fw-semibold">{{ $risk ? $risk->inflation_score . ' / 10' : '-' }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td style="padding-left: 24px;" class="text-muted small fw-semibold">Currency Risk Score</td>
                                @foreach($compareCountries as $country)
                                    @php $risk = $country->riskScores->first(); @endphp
                                    <td class="text-white fw-semibold">{{ $risk ? $risk->currency_score . ' / 10' : '-' }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td style="padding-left: 24px;" class="text-muted small fw-semibold">News Sentiment Score</td>
                                @foreach($compareCountries as $country)
                                    @php $risk = $country->riskScores->first(); @endphp
                                    <td class="text-white fw-semibold">{{ $risk ? $risk->news_score . ' / 10' : '-' }}</td>
                                @endforeach
                            </tr>

                            <!-- CATEGORY: WEATHER snapshot -->
                            <tr class="table-group-header" style="background: rgba(255,255,255,0.02) !important;">
                                <td colspan="{{ $compareCountries->count() + 1 }}" style="padding-left: 24px;" class="fw-bold text-info small uppercase tracking-wider">
                                    ⛅ Climate & Real-Time Weather Indicators
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left: 24px;" class="text-muted small fw-semibold">Current Temperature</td>
                                @foreach($compareCountries as $country)
                                    @php $weather = $country->weatherSnapshots->first(); @endphp
                                    <td class="text-white">{{ $weather && $weather->temperature !== null ? $weather->temperature . ' °C' : '-' }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td style="padding-left: 24px;" class="text-muted small fw-semibold">Wind Speed</td>
                                @foreach($compareCountries as $country)
                                    @php $weather = $country->weatherSnapshots->first(); @endphp
                                    <td class="text-white">{{ $weather && $weather->wind_speed !== null ? $weather->wind_speed . ' km/h' : '-' }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td style="padding-left: 24px;" class="text-muted small fw-semibold">Rainfall Depth</td>
                                @foreach($compareCountries as $country)
                                    @php $weather = $country->weatherSnapshots->first(); @endphp
                                    <td class="text-white">{{ $weather && $weather->rainfall !== null ? $weather->rainfall . ' mm' : '-' }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td style="padding-left: 24px;" class="text-muted small fw-semibold">Storm / Typhoon Risk</td>
                                @foreach($compareCountries as $country)
                                    @php $weather = $country->weatherSnapshots->first(); @endphp
                                    <td>
                                        @if($weather)
                                            @if($weather->storm_risk == 'high')
                                                <span class="text-danger fw-bold">🔴 High Alert</span>
                                            @elseif($weather->storm_risk == 'medium')
                                                <span class="text-warning fw-bold">🟡 Warning</span>
                                            @else
                                                <span class="text-success fw-bold">🟢 Low Storm Risk</span>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                @endforeach
                            </tr>

                            <!-- CATEGORY: ECONOMY -->
                            <tr class="table-group-header" style="background: rgba(255,255,255,0.02) !important;">
                                <td colspan="{{ $compareCountries->count() + 1 }}" style="padding-left: 24px;" class="fw-bold text-success small uppercase tracking-wider">
                                    📈 Macroeconomic Metrics (World Bank)
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left: 24px;" class="text-muted small fw-semibold">GDP Size (USD)</td>
                                @foreach($compareCountries as $country)
                                    @php
                                        $indicator = $country->economicIndicators->first();
                                        $gdp = $indicator ? $indicator->gdp : null;
                                        if ($gdp !== null) {
                                            if ($gdp >= 1e12) {
                                                $formattedGdp = '$' . number_format($gdp / 1e12, 2) . ' T';
                                            } elseif ($gdp >= 1e9) {
                                                $formattedGdp = '$' . number_format($gdp / 1e9, 2) . ' B';
                                            } else {
                                                $formattedGdp = '$' . number_format($gdp / 1e6, 2) . ' M';
                                            }
                                        } else {
                                            $formattedGdp = '-';
                                        }
                                    @endphp
                                    <td class="text-white fw-bold">{{ $formattedGdp }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td style="padding-left: 24px;" class="text-muted small fw-semibold">Consumer Inflation Rate</td>
                                @foreach($compareCountries as $country)
                                    @php
                                        $indicator = $country->economicIndicators->first();
                                        $inf = $indicator ? $indicator->inflation : null;
                                    @endphp
                                    <td class="fw-semibold">
                                        @if($inf !== null)
                                            <span class="{{ $inf > 5.0 ? 'text-danger' : 'text-success' }}">{{ number_format($inf, 2) }}%</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td style="padding-left: 24px;" class="text-muted small fw-semibold">Indicator Sync Year</td>
                                @foreach($compareCountries as $country)
                                    @php $indicator = $country->economicIndicators->first(); @endphp
                                    <td class="text-white">{{ $indicator ? $indicator->year : '-' }}</td>
                                @endforeach
                            </tr>

                            <!-- CATEGORY: CURRENCY -->
                            <tr class="table-group-header" style="background: rgba(255,255,255,0.02) !important;">
                                <td colspan="{{ $compareCountries->count() + 1 }}" style="padding-left: 24px;" class="fw-bold text-warning small uppercase tracking-wider">
                                    💵 Currency Profile & Conversion
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left: 24px;" class="text-muted small fw-semibold">Currency Denomination</td>
                                @foreach($compareCountries as $country)
                                    <td class="text-white">
                                        {{ $country->currency_name ?? '-' }} ({{ $country->currency_code ?? '-' }} / {{ $country->currency_symbol ?? '-' }})
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td style="padding-left: 24px;" class="text-muted small fw-semibold">Exchange Rate (vs USD)</td>
                                @foreach($compareCountries as $country)
                                    @php
                                        $exchange = $country->exchangeRates->first();
                                        $rate = $exchange ? $exchange->exchange_rate : null;
                                    @endphp
                                    <td class="text-white fw-bold">
                                        @if($rate !== null)
                                            1 USD = {{ number_format($rate, 4) }} {{ $country->currency_code }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const compareForm = document.getElementById('compareForm');
        
        if (compareForm) {
            compareForm.addEventListener('submit', function(e) {
                const selects = document.querySelectorAll('.compare-select');
                let selectedCount = 0;
                
                selects.forEach(select => {
                    if (select.value) selectedCount++;
                });

                if (selectedCount === 0) {
                    e.preventDefault();
                    alert('Please select at least one country to compare.');
                }
            });
        }
    });
</script>
@endpush
