@push('styles')
<style>
    .stat-icon-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background-color: rgba(99, 102, 241, 0.1);
        color: #6366f1;
        transition: all 0.3s ease;
    }
    body.bg-dark .stat-icon-wrapper {
        background-color: rgba(129, 140, 248, 0.15);
        color: #818cf8;
    }
    .stat-icon-wrapper.danger {
        background-color: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }
    body.bg-dark .stat-icon-wrapper.danger {
        background-color: rgba(248, 113, 113, 0.15);
        color: #f87171;
    }
    .stat-icon-wrapper.warning {
        background-color: rgba(245, 158, 11, 0.1);
        color: #d97706;
    }
    body.bg-dark .stat-icon-wrapper.warning {
        background-color: rgba(251, 191, 36, 0.15);
        color: #fbbf24;
    }
    .stat-icon-wrapper.success {
        background-color: rgba(16, 185, 129, 0.1);
        color: #059669;
    }
    body.bg-dark .stat-icon-wrapper.success {
        background-color: rgba(52, 211, 153, 0.15);
        color: #34d399;
    }
    .card:hover .stat-icon-wrapper {
        transform: scale(1.1) rotate(5deg);
    }
    
    @keyframes pulsing-danger {
        0% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
        }
        70% {
            transform: scale(1);
            box-shadow: 0 0 0 6px rgba(239, 68, 68, 0);
        }
        100% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
        }
    }
</style>
@endpush

<div class="row g-4">

    <!-- Card 1: Total Countries -->
    <div class="col-lg-3 col-md-6">

        <div class="card shadow-sm border-start border-5" style="border-left-color: #6366f1 !important;">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-muted fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.05em;">
                            Total Countries
                        </small>

                        <h2 id="totalCountries" class="fw-bold mt-2 mb-0 counter" style="font-size: 32px; letter-spacing: -0.02em;">
                            0
                        </h2>

                    </div>

                    <div class="stat-icon-wrapper">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>
                    </div>

                </div>
                
                <div style="height: 38px; display: flex; align-items: center;" class="mt-3">
                    <small class="text-muted fw-medium" style="font-size: 12.5px;">Active tracked jurisdictions</small>
                </div>

            </div>

        </div>

    </div>

    <!-- Card 2: High Risk -->
    <div class="col-lg-3 col-md-6">

        <div class="card shadow-sm border-start border-5" style="border-left-color: #ef4444 !important;">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-danger fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.05em;">
                            High Risk Countries
                        </small>

                        <h2 id="highRisk" class="fw-bold mt-2 mb-0 counter text-danger" style="font-size: 32px; letter-spacing: -0.02em;">
                            0
                        </h2>

                    </div>

                    <div class="stat-icon-wrapper danger">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 13c0 5-3.5 7.5-7.66 9.7a1 1 0 0 1-.68 0C7.5 20.5 4 18 4 13V6a1 1 0 0 1 .76-.97l8-2a1 1 0 0 1 .48 0l8 2A1 1 0 0 1 20 6v7z"/><line x1="12" x2="12" y1="9" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/></svg>
                    </div>

                </div>

                <div class="progress mt-3" style="height: 6px; background-color: rgba(100, 116, 139, 0.1); border-radius: 4px; overflow: hidden;">

                    <div
                        id="highBar"
                        class="progress-bar"
                        style="width:0%; background: linear-gradient(90deg, #f87171, #ef4444) !important; border-radius: 4px;">

                    </div>

                </div>

                <div class="d-flex justify-content-between mt-2">
                    <small id="highPercent" class="text-muted fw-bold" style="font-size: 12px;">
                        0%
                    </small>
                    <small class="text-muted" style="font-size: 11px;">of total scope</small>
                </div>

            </div>

        </div>

    </div>

    <!-- Card 3: Medium Risk -->
    <div class="col-lg-3 col-md-6">

        <div class="card shadow-sm border-start border-5" style="border-left-color: #f59e0b !important;">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-warning fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.05em;">
                            Medium Risk Countries
                        </small>

                        <h2 id="mediumRisk" class="fw-bold mt-2 mb-0 counter text-warning" style="font-size: 32px; letter-spacing: -0.02em;">
                            0
                        </h2>

                    </div>

                    <div class="stat-icon-wrapper warning">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" x2="12" y1="9" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/></svg>
                    </div>

                </div>

                <div class="progress mt-3" style="height: 6px; background-color: rgba(100, 116, 139, 0.1); border-radius: 4px; overflow: hidden;">

                    <div
                        id="mediumBar"
                        class="progress-bar"
                        style="width:0%; background: linear-gradient(90deg, #fbbf24, #f59e0b) !important; border-radius: 4px;">

                    </div>

                </div>

                <div class="d-flex justify-content-between mt-2">
                    <small id="mediumPercent" class="text-muted fw-bold" style="font-size: 12px;">
                        0%
                    </small>
                    <small class="text-muted" style="font-size: 11px;">of total scope</small>
                </div>

            </div>

        </div>

    </div>

    <!-- Card 4: Low Risk -->
    <div class="col-lg-3 col-md-6">

        <div class="card shadow-sm border-start border-5" style="border-left-color: #10b981 !important;">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-success fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.05em;">
                            Low Risk Countries
                        </small>

                        <h2 id="lowRisk" class="fw-bold mt-2 mb-0 counter text-success" style="font-size: 32px; letter-spacing: -0.02em;">
                            0
                        </h2>

                    </div>

                    <div class="stat-icon-wrapper success">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 13c0 5-3.5 7.5-7.66 9.7a1 1 0 0 1-.68 0C7.5 20.5 4 18 4 13V6a1 1 0 0 1 .76-.97l8-2a1 1 0 0 1 .48 0l8 2A1 1 0 0 1 20 6v7z"/><path d="m9 12 2 2 4-4"/></svg>
                    </div>

                </div>

                <div class="progress mt-3" style="height: 6px; background-color: rgba(100, 116, 139, 0.1); border-radius: 4px; overflow: hidden;">

                    <div
                        id="lowBar"
                        class="progress-bar"
                        style="width:0%; background: linear-gradient(90deg, #34d399, #10b981) !important; border-radius: 4px;">

                    </div>

                </div>

                <div class="d-flex justify-content-between mt-2">
                    <small id="lowPercent" class="text-muted fw-bold" style="font-size: 12px;">
                        0%
                    </small>
                    <small class="text-muted" style="font-size: 11px;">of total scope</small>
                </div>

            </div>

        </div>

    </div>

</div>

<script>

function animateCounter(id,value){

    let element=document.getElementById(id);

    let start=0;

    let speed=Math.max(1,Math.ceil(value/40));

    let interval=setInterval(()=>{

        start+=speed;

        if(start>=value){

            start=value;

            clearInterval(interval);

        }

        element.innerHTML=start;

    },20);

}

function loadDashboard(){

    fetch("{{ url('/api/dashboard') }}")

    .then(res=>res.json())

    .then(data=>{

        animateCounter("totalCountries",data.total_countries);

        animateCounter("highRisk",data.high_risk);

        animateCounter("mediumRisk",data.medium_risk);

        animateCounter("lowRisk",data.low_risk);

        let total=data.total_countries;

        let high=((data.high_risk/total)*100).toFixed(1);

        let medium=((data.medium_risk/total)*100).toFixed(1);

        let low=((data.low_risk/total)*100).toFixed(1);

        document.getElementById("highBar").style.width=high+"%";
        document.getElementById("mediumBar").style.width=medium+"%";
        document.getElementById("lowBar").style.width=low+"%";

        document.getElementById("highPercent").innerHTML=high+"%";
        document.getElementById("mediumPercent").innerHTML=medium+"%";
        document.getElementById("lowPercent").innerHTML=low+"%";

        document.getElementById("lastUpdate").innerHTML=
            new Date().toLocaleTimeString();

        document.getElementById("liveStatus").className=
            "badge bg-success fs-6 d-inline-flex align-items-center";

        document.getElementById("liveStatus").innerHTML=
            "<span class=\"pulse-circle\"></span>LIVE";

    })

    .catch(()=>{

        document.getElementById("liveStatus").className=
            "badge bg-danger fs-6 d-inline-flex align-items-center";

        document.getElementById("liveStatus").innerHTML=
            "<span class=\"pulse-circle\" style=\"background-color: #ef4444; box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); animation: pulsing-danger 1.2s infinite;\"></span>OFFLINE";

    });

}

loadDashboard();

setInterval(loadDashboard, 5000);

</script>