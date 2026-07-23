@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">📊 Supply Chain Risk Analytics</h1>
            <p class="text-muted mb-0">Tren ekonomi, mata uang, dan faktor pembentuk risiko global.</p>
        </div>
        @if(auth()->user()?->role === 'admin')
            <div class="d-flex gap-2">
                <form method="POST" action="{{ route('admin.risk.recalculate') }}">@csrf
                    <button class="btn btn-primary">🧮 Hitung Ulang Risiko</button>
                </form>
                <form method="POST" action="{{ route('admin.data.sync') }}">@csrf
                    <button class="btn btn-outline-primary">🔄 Sinkronkan Semua Data</button>
                </form>
            </div>
        @endif
    </div>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <div class="card card-body shadow-sm mb-4">
        <div class="row align-items-end g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Negara untuk grafik tren</label>
                <select id="analyticsCountry" class="form-select"><option value="">Memuat negara...</option></select>
            </div>
            <div class="col-md-6 text-md-end">
                <small id="dataScope" class="text-muted">Data tersimpan pada database CargoVision.</small>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-6"><div class="card h-100 shadow-sm"><div class="card-header">Risk Level Distribution</div><div class="card-body"><div style="height:300px"><canvas id="riskPie"></canvas></div></div></div></div>
        <div class="col-lg-6"><div class="card h-100 shadow-sm"><div class="card-header">Average Risk Factor Breakdown</div><div class="card-body"><div style="height:300px"><canvas id="factorChart"></canvas></div></div></div></div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-6"><div class="card h-100 shadow-sm"><div class="card-header">GDP Trend</div><div class="card-body"><div style="height:280px"><canvas id="gdpChart"></canvas></div></div></div></div>
        <div class="col-lg-6"><div class="card h-100 shadow-sm"><div class="card-header">Inflation Trend</div><div class="card-body"><div style="height:280px"><canvas id="inflationChart"></canvas></div></div></div></div>
        <div class="col-lg-6"><div class="card h-100 shadow-sm"><div class="card-header">Currency Trend</div><div class="card-body"><div style="height:280px"><canvas id="currencyChart"></canvas></div></div></div></div>
        <div class="col-lg-6"><div class="card h-100 shadow-sm"><div class="card-header">Risk Trend</div><div class="card-body"><div style="height:280px"><canvas id="riskTrendChart"></canvas></div></div></div></div>
    </div>

    <div class="row g-4">
        <div class="col-md-6"><div class="card shadow-sm"><div class="card-header text-danger">🔴 Highest-Risk Countries</div><div class="table-responsive"><table class="table mb-0"><tbody id="highestTable"></tbody></table></div></div></div>
        <div class="col-md-6"><div class="card shadow-sm"><div class="card-header text-success">🟢 Lowest-Risk Countries</div><div class="table-responsive"><table class="table mb-0"><tbody id="lowestTable"></tbody></table></div></div></div>
    </div>
</div>

<script>
const charts = {};
const colors = { text:'#94a3b8', grid:'rgba(148,163,184,.12)' };

function chart(id, type, labels, datasets, extra = {}) {
    if (charts[id]) charts[id].destroy();
    charts[id] = new Chart(document.getElementById(id), {
        type, data:{labels,datasets},
        options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{labels:{color:colors.text}}},
            scales:type === 'pie' ? {} : {x:{ticks:{color:colors.text},grid:{display:false}},y:{beginAtZero:true,ticks:{color:colors.text},grid:{color:colors.grid}}}, ...extra}
    });
}

function lineDataset(label, data, color) {
    return {label,data,borderColor:color,backgroundColor:color+'22',fill:true,tension:.3};
}

async function loadCountries() {
    const countries = await fetch('/api/countries').then(r=>r.json());
    const select = document.getElementById('analyticsCountry');
    select.innerHTML = countries.map(c=>`<option value="${c.id}">${c.name}</option>`).join('');
    await loadAnalytics();
}

async function loadAnalytics() {
    const countryId = document.getElementById('analyticsCountry').value;
    const [dashboard, data] = await Promise.all([
        fetch('/api/dashboard').then(r=>r.json()),
        fetch('/api/analytics'+(countryId ? '?country_id='+countryId : '')).then(r=>r.json())
    ]);
    if (!countryId && data.selected_country) document.getElementById('analyticsCountry').value = data.selected_country.id;
    document.getElementById('dataScope').textContent = 'Tren terpilih: '+(data.selected_country?.name || 'belum tersedia');

    chart('riskPie','pie',['High','Medium','Low'],[{data:[dashboard.high_risk,dashboard.medium_risk,dashboard.low_risk],backgroundColor:['#ef4444','#f59e0b','#10b981']}]);
    chart('factorChart','bar',['Weather','Inflation','News','Currency'],[{label:'Average score',data:Object.values(data.risk_factors),backgroundColor:['#06b6d4','#f59e0b','#ef4444','#8b5cf6'],borderRadius:8}]);

    const economic = data.economic_trend || [];
    chart('gdpChart','line',economic.map(x=>x.year),[lineDataset('GDP',economic.map(x=>x.gdp),'#06b6d4')]);
    chart('inflationChart','line',economic.map(x=>x.year),[lineDataset('Inflation',economic.map(x=>x.inflation),'#f59e0b')]);
    const currency = data.currency_trend || [];
    chart('currencyChart','line',currency.map(x=>new Date(x.created_at).toLocaleDateString()),[lineDataset('Exchange rate',currency.map(x=>x.exchange_rate),'#8b5cf6')]);
    const risk = data.risk_trend || [];
    chart('riskTrendChart','line',risk.map(x=>new Date(x.created_at).toLocaleDateString()),[lineDataset('Risk score',risk.map(x=>x.total_score),'#ef4444')]);

    const rows = items => items.length ? items.map(x=>`<tr><td class="ps-4 fw-semibold">${x.country?.name || '-'}</td><td class="text-end pe-4"><span class="badge ${x.risk_level==='High'?'bg-danger':'bg-success'}">${x.total_score}</span></td></tr>`).join('') : '<tr><td class="text-center text-muted py-4">Belum ada data</td></tr>';
    document.getElementById('highestTable').innerHTML = rows(data.highest || []);
    document.getElementById('lowestTable').innerHTML = rows(data.lowest || []);
}

document.getElementById('analyticsCountry').addEventListener('change', loadAnalytics);
loadCountries().catch(() => document.getElementById('dataScope').textContent = 'Data analytics belum dapat dimuat.');
</script>
@endsection
