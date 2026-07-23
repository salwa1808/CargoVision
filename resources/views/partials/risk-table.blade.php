<div class="card mt-4 shadow-sm">

    <div class="card-header d-flex justify-content-between align-items-center">
        <span>🌍 Country Risk Leaderboard</span>
        <span class="text-muted fw-normal" style="font-size: 13px;">Rankings based on total threat metrics</span>
    </div>

    <div class="card-body">

        <!-- Filter -->

        <div class="row g-3 mb-4">

            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0" style="border-color: var(--border-color); color: var(--text-muted); border-radius: 12px 0 0 12px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" x2="16.65" y1="21" y2="16.65"/></svg>
                    </span>
                    <input
                        type="text"
                        id="searchCountry"
                        class="form-control border-start-0"
                        placeholder="Search country..."
                        style="border-radius: 0 12px 12px 0; border-color: var(--border-color);"
                    >
                </div>
            </div>

            <div class="col-md-3">

                <select
                    id="regionFilter"
                    class="form-select"
                >

                    <option value="">All Regions</option>

                    <option>Africa</option>
                    <option>Americas</option>
                    <option>Asia</option>
                    <option>Europe</option>
                    <option>Oceania</option>

                </select>

            </div>

            <div class="col-md-3">

                <select
                    id="riskFilter"
                    class="form-select"
                >

                    <option value="">All Risk Levels</option>

                    <option>High</option>

                    <option>Medium</option>

                    <option>Low</option>

                </select>

            </div>

            <div class="col-md-2">

                <button
                    id="resetFilter"
                    class="btn btn-outline-light w-100 fw-bold"
                    style="border-radius: 12px; padding: 10px 16px; border-color: var(--border-color);"
                >

                    Reset Filters

                </button>

            </div>

        </div>

        <!-- Table -->

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead>

                    <tr style="border-bottom: 2px solid var(--border-color);">

                        <th width="80" style="padding-left: 16px;">Rank</th>

                        <th>Country</th>

                        <th>Region</th>

                        <th>Risk Score</th>

                        <th width="160">Risk Status</th>

                    </tr>

                </thead>

                <tbody id="riskTable" style="border-top: none;">

                </tbody>

            </table>

        </div>

    </div>

</div>

<script>

let riskData = [];

// ===============================
// Load Data Realtime
// ===============================

function loadRiskTable(){

    fetch("{{ url('/api/risk') }}")

    .then(response => response.json())

    .then(data => {

        riskData = data;

        applyFilter();

    })

    .catch(error => {

        console.log(error);

    });

}

loadRiskTable();

// refresh otomatis setiap 5 detik

setInterval(loadRiskTable,5000);

// ===============================
// Render Table
// ===============================

function renderTable(data){

    let html='';

    let no=1;

    data.forEach(item=>{

        let badge='success';

        if(item.risk_level=='High'){

            badge='danger';

        }

        else if(item.risk_level=='Medium'){

            badge='warning';

        }

        html+=`

        <tr>

            <td style="padding-left: 16px;" class="fw-bold text-muted">${no++}</td>

            <td>

                <a href="/country/${item.country.id}" class="fw-bold text-decoration-none" style="color: var(--text-main); font-size: 14.5px;">

                    ${item.country.name}

                </a>

            </td>

            <td class="text-muted" style="font-size: 14px;">${item.country.region}</td>

            <td class="fw-bold" style="font-size: 15px; color: var(--text-main);">${item.total_score}</td>

            <td>

                <span class="badge bg-${badge}" style="display: inline-block; width: 100px; text-align: center;">

                    ${item.risk_level}

                </span>

            </td>

        </tr>

        `;

    });

    document.getElementById('riskTable').innerHTML=html;

}

// ===============================
// Filter
// ===============================

function applyFilter(){

    const keyword=document.getElementById('searchCountry').value.toLowerCase();

    const region=document.getElementById('regionFilter').value;

    const risk=document.getElementById('riskFilter').value;

    const filtered=riskData.filter(item=>{

        const cocokNama=item.country.name
            .toLowerCase()
            .includes(keyword);

        const cocokRegion=

            region=='' ||

            item.country.region==region;

        const cocokRisk=

            risk=='' ||

            item.risk_level==risk;

        return cocokNama && cocokRegion && cocokRisk;

    });

    renderTable(filtered);

}

// ===============================
// Event
// ===============================

document.getElementById('searchCountry')

.addEventListener('keyup',applyFilter);

document.getElementById('regionFilter')

.addEventListener('change',applyFilter);

document.getElementById('riskFilter')

.addEventListener('change',applyFilter);

document.getElementById('resetFilter')

.addEventListener('click',function(){

    document.getElementById('searchCountry').value='';

    document.getElementById('regionFilter').value='';

    document.getElementById('riskFilter').value='';

    applyFilter();

});

</script>
