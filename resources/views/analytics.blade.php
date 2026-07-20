@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-5 mt-2">

        <div>
            <h1 class="h3 fw-bold mb-1" style="letter-spacing: -0.02em;">
                📊 Supply Chain Analytics
            </h1>
            <p class="text-muted fw-medium mb-0">
                Detailed threat analysis & geographical distributions
            </p>
        </div>

        <a href="/" class="btn btn-outline-light d-inline-flex align-items-center gap-2 fw-semibold" style="border-radius: 12px; padding: 10px 20px; border-color: var(--border-color);">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" x2="5" y1="12" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Back to Dashboard
        </a>

    </div>

    <div class="row g-4 mb-4">

        <div class="col-lg-6">

            <div class="card shadow-sm h-100">

                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Risk Level Distribution</span>
                    <span class="text-muted fw-normal" style="font-size: 13px;">Proportion of risk categories</span>
                </div>

                <div class="card-body d-flex align-items-center justify-content-center" style="min-height: 340px;">

                    <div style="position: relative; height: 280px; width: 100%;">
                        <canvas id="riskPie"></canvas>
                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-6">

            <div class="card shadow-sm h-100">

                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Countries by Region</span>
                    <span class="text-muted fw-normal" style="font-size: 13px;">Regional coverage frequency</span>
                </div>

                <div class="card-body" style="min-height: 340px;">

                    <div style="position: relative; height: 280px; width: 100%;">
                        <canvas id="regionChart"></canvas>
                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="row g-4">

        <div class="col-md-6">

            <div class="card shadow-sm">

                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="text-danger">🔴 Top 5 Highest Risk</span>
                    <span class="badge bg-danger" style="font-size: 11px !important;">Critical Area</span>
                </div>

                <div class="card-body p-0">

                    <table class="table table-hover align-middle mb-0">

                        <thead>

                        <tr style="border-bottom: 2px solid var(--border-color);">

                            <th style="padding-left: 24px;">Country</th>

                            <th width="120">Score</th>

                        </tr>

                        </thead>

                        <tbody id="highestTable">

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        <div class="col-md-6">

            <div class="card shadow-sm">

                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="text-success">🟢 Top 5 Lowest Risk</span>
                    <span class="badge bg-success" style="font-size: 11px !important;">Stable Area</span>
                </div>

                <div class="card-body p-0">

                    <table class="table table-hover align-middle mb-0">

                        <thead>

                        <tr style="border-bottom: 2px solid var(--border-color);">

                            <th style="padding-left: 24px;">Country</th>

                            <th width="120">Score</th>

                        </tr>

                        </thead>

                        <tbody id="lowestTable">

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

<script>

let pieChart;
let regionChart;

function loadAnalytics(){

Promise.all([

fetch('/api/dashboard').then(r=>r.json()),

fetch('/api/analytics').then(r=>r.json())

])

.then(([dashboard,analytics])=>{

    const isDark = document.body.classList.contains('bg-dark');
    const textColor = isDark ? '#94a3b8' : '#64748b';
    const gridColor = isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)';

    // PIE

    if(pieChart){

        pieChart.destroy();

    }

    pieChart=new Chart(

        document.getElementById('riskPie'),

        {

            type:'pie',

            data:{

                labels:[

                    'High',

                    'Medium',

                    'Low'

                ],

                datasets:[{

                    data:[

                        dashboard.high_risk,

                        dashboard.medium_risk,

                        dashboard.low_risk

                    ],
                    backgroundColor: [
                        'rgba(239, 68, 68, 0.85)',
                        'rgba(245, 158, 11, 0.85)',
                        'rgba(16, 185, 129, 0.85)'
                    ],
                    borderColor: isDark ? '#111625' : '#ffffff',
                    borderWidth: 2

                }]

            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            color: textColor,
                            font: {
                                family: 'Plus Jakarta Sans',
                                weight: '500'
                            }
                        }
                    }
                }
            }

        }

    );

    // REGION

    if(regionChart){

        regionChart.destroy();

    }

    regionChart=new Chart(

        document.getElementById('regionChart'),

        {

            type:'bar',

            data:{

                labels:analytics.region.map(x=>x.region),

                datasets:[{

                    label:'Countries',

                    data:analytics.region.map(x=>x.total),
                    backgroundColor: 'rgba(99, 102, 241, 0.85)',
                    borderColor: '#6366f1',
                    borderWidth: 1,
                    borderRadius: 8,
                    borderSkipped: false,
                    barThickness: 30

                }]

            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: gridColor, drawTicks: false },
                        ticks: { color: textColor, font: { family: 'Plus Jakarta Sans' }, stepSize: 1 }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: textColor, font: { family: 'Plus Jakarta Sans', weight: '600' } }
                    }
                }
            }

        }

    );

    // Highest

    let highest='';

    analytics.highest.forEach(item=>{

        highest+=`

        <tr>

            <td style="padding-left: 24px;" class="fw-semibold">${item.country.name}</td>

            <td>
                <span class="badge bg-danger" style="min-width: 60px; text-align: center; font-size: 13px !important;">
                    ${item.total_score}
                </span>
            </td>

        </tr>

        `;

    });

    document.getElementById('highestTable').innerHTML=highest;

    // Lowest

    let lowest='';

    analytics.lowest.forEach(item=>{

        lowest+=`

        <tr>

            <td style="padding-left: 24px;" class="fw-semibold">${item.country.name}</td>

            <td>
                <span class="badge bg-success" style="min-width: 60px; text-align: center; font-size: 13px !important;">
                    ${item.total_score}
                </span>
            </td>

        </tr>

        `;

    });

    document.getElementById('lowestTable').innerHTML=lowest;

});

}

loadAnalytics();

setInterval(loadAnalytics,30000);

// Watch for theme toggles to update chart configurations
document.getElementById('darkModeBtn').addEventListener('click', () => {
    setTimeout(() => {
        const isDarkNow = document.body.classList.contains('bg-dark');
        const newTextColor = isDarkNow ? '#94a3b8' : '#64748b';
        const newGridColor = isDarkNow ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)';
        
        if(regionChart) {
            regionChart.options.scales.x.ticks.color = newTextColor;
            regionChart.options.scales.y.ticks.color = newTextColor;
            regionChart.options.scales.y.grid.color = newGridColor;
            regionChart.update();
        }
        if(pieChart) {
            pieChart.options.plugins.legend.labels.color = newTextColor;
            pieChart.data.datasets[0].borderColor = isDarkNow ? '#111625' : '#ffffff';
            pieChart.update();
        }
    }, 100);
});

</script>

@endsection