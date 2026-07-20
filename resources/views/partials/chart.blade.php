<div class="card mt-4 shadow-sm">

    <div class="card-header d-flex justify-content-between align-items-center">
        <span>📊 Risk Level Distribution</span>
        <span class="text-muted fw-normal" style="font-size: 13px;">Overview of tracked regions</span>
    </div>

    <div class="card-body">

        <div style="position: relative; height: 320px; width: 100%;">
            <canvas id="riskChart"></canvas>
        </div>

    </div>

</div>

<script>

let chart;

function loadChart() {
    fetch("{{ url('/api/dashboard') }}")
    .then(response => response.json())
    .then(data => {
        document.getElementById('totalCountries').innerText = data.total_countries;
        document.getElementById('highRisk').innerText = data.high_risk;
        document.getElementById('mediumRisk').innerText = data.medium_risk;
        document.getElementById('lowRisk').innerText = data.low_risk;

        if (chart) {
            chart.data.datasets[0].data = [
                data.high_risk,
                data.medium_risk,
                data.low_risk
            ];
            chart.update();
            return;
        }

        const ctx = document.getElementById('riskChart').getContext('2d');
    
    // Create modern gradients for bars
    const gradientHigh = ctx.createLinearGradient(0, 0, 0, 300);
    gradientHigh.addColorStop(0, 'rgba(239, 68, 68, 0.85)');
    gradientHigh.addColorStop(1, 'rgba(239, 68, 68, 0.4)');

    const gradientMedium = ctx.createLinearGradient(0, 0, 0, 300);
    gradientMedium.addColorStop(0, 'rgba(245, 158, 11, 0.85)');
    gradientMedium.addColorStop(1, 'rgba(245, 158, 11, 0.4)');

    const gradientLow = ctx.createLinearGradient(0, 0, 0, 300);
    gradientLow.addColorStop(0, 'rgba(16, 185, 129, 0.85)');
    gradientLow.addColorStop(1, 'rgba(16, 185, 129, 0.4)');

    const isDark = document.body.classList.contains('bg-dark');
    const textColor = isDark ? '#94a3b8' : '#64748b';
    const gridColor = isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)';

    const chartType = localStorage.getItem('default_chart') || 'bar';
    const enableAnim = localStorage.getItem('enable_chart_animation') !== 'off';
    const animSpeed = localStorage.getItem('chart_animation_speed') || 'normal';

    let animDuration = 1000;
    if (!enableAnim) {
        animDuration = 0;
    } else if (animSpeed === 'slow') {
        animDuration = 2000;
    } else if (animSpeed === 'fast') {
        animDuration = 500;
    }

    const config = {
        type: chartType,
        data: {
            labels: [
                'High Risk',
                'Medium Risk',
                'Low Risk'
            ],
            datasets: [{
                label: 'Jumlah Negara',
                data: [
                    data.high_risk,
                    data.medium_risk,
                    data.low_risk
                ],
                backgroundColor: [
                    gradientHigh,
                    gradientMedium,
                    gradientLow
                ],
                borderColor: [
                    '#ef4444',
                    '#f59e0b',
                    '#10b981'
                ],
                borderWidth: 1.5,
                borderRadius: chartType === 'pie' ? 0 : 12,
                borderSkipped: false,
                barThickness: chartType === 'bar' ? 50 : undefined
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: animDuration
            },
            plugins: {
                legend: {
                    display: chartType === 'pie',
                    position: 'right',
                    labels: {
                        color: textColor,
                        font: {
                            family: 'Plus Jakarta Sans',
                            size: 12
                        }
                    }
                },
                tooltip: {
                    backgroundColor: isDark ? '#1e293b' : '#0f172a',
                    titleColor: '#fff',
                    bodyColor: '#e2e8f0',
                    padding: 12,
                    cornerRadius: 10,
                    titleFont: {
                        family: 'Plus Jakarta Sans',
                        weight: '700',
                        size: 13
                    },
                    bodyFont: {
                        family: 'Plus Jakarta Sans',
                        size: 13
                    },
                    boxWidth: 8,
                    boxHeight: 8,
                    boxPadding: 4
                }
            }
        }
    };

    if (chartType !== 'pie') {
        config.options.scales = {
            y: {
                beginAtZero: true,
                grid: {
                    color: gridColor,
                    drawTicks: false
                },
                ticks: {
                    color: textColor,
                    font: {
                        family: 'Plus Jakarta Sans',
                        size: 12
                    },
                    stepSize: 1
                }
            },
            x: {
                grid: {
                    display: false
                },
                ticks: {
                    color: textColor,
                    font: {
                        family: 'Plus Jakarta Sans',
                        weight: '600',
                        size: 12
                    }
                }
            }
        };
    }

    chart = new Chart(ctx, config);

    // Theme Switch listener
    document.getElementById('darkModeBtn').addEventListener('click', () => {
        setTimeout(() => {
            const isDarkNow = document.body.classList.contains('bg-dark');
            const newTextColor = isDarkNow ? '#94a3b8' : '#64748b';
            const newGridColor = isDarkNow ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)';
            
            if(chart) {
                chart.options.scales.x.ticks.color = newTextColor;
                chart.options.scales.y.ticks.color = newTextColor;
                chart.options.scales.y.grid.color = newGridColor;
                chart.options.plugins.tooltip.backgroundColor = isDarkNow ? '#1e293b' : '#0f172a';
                chart.update();
            }
        }, 100);
    });

    });
}

loadChart();
setInterval(loadChart, 5000);

</script>