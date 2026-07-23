<div class="card mt-4 shadow-sm">

    <div class="card-header d-flex justify-content-between align-items-center">
        <span>🗺️ World Risk Map</span>
        <span class="text-muted fw-normal" style="font-size: 13px;">Spatial risk density tracker</span>
    </div>

    <div class="card-body p-0" style="border-radius: 0 0 20px 20px; overflow: hidden;">
        <div id="map" style="height:600px;"></div>
    </div>

</div>

@push('scripts')

<script>

document.addEventListener("DOMContentLoaded", function () {

    if(typeof L === "undefined"){
        console.error("Leaflet gagal dimuat.");
        return;
    }

    const map = L.map('map').setView([20,0],2);

    let activeTileLayer;
    function getTileUrl() {
        return document.body.classList.contains('bg-dark')
            ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png'
            : 'https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png';
    }

    activeTileLayer = L.tileLayer(getTileUrl(), {
        attribution: '© OpenStreetMap © CartoDB'
    }).addTo(map);

    // Watch for theme toggles to swap tiles
    document.getElementById('darkModeBtn')?.addEventListener('click', () => {
        setTimeout(() => {
            map.removeLayer(activeTileLayer);
            activeTileLayer = L.tileLayer(getTileUrl(), {
                attribution: '© OpenStreetMap © CartoDB'
            }).addTo(map);
        }, 100);
    });

    const markerCluster = L.markerClusterGroup();

    map.addLayer(markerCluster);

    function markerColor(level){

        let color="blue";

        if(level==="High") color="red";
        else if(level==="Medium") color="orange";
        else if(level==="Low") color="green";

        return new L.Icon({

            iconUrl:`https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-${color}.png`,

            shadowUrl:'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',

            iconSize:[25,41],
            iconAnchor:[12,41],
            popupAnchor:[1,-34],
            shadowSize:[41,41]

        });

    }

    function loadMap(){

        let hasOpenPopup = false;
        markerCluster.eachLayer(function(layer) {
            if (layer.getPopup() && layer.getPopup().isOpen()) {
                hasOpenPopup = true;
            }
        });
        if (hasOpenPopup) return;

        markerCluster.clearLayers();

        Promise.all([

            fetch("{{ url('/api/countries') }}").then(r=>r.json()),

            fetch("{{ url('/api/risk') }}").then(r=>r.json())

        ])

        .then(([countries,risks])=>{

            countries.forEach(country=>{

                if(country.latitude && country.longitude){

                    let risk = risks.find(r => r.country_id == country.id);

                    let level = "No Data";
                    let score = "-";
                    let badgeColor = "secondary";

                    if(risk){

                        level = risk.risk_level;
                        score = risk.total_score;
                        
                        if(level === "High") badgeColor = "danger";
                        else if(level === "Medium") badgeColor = "warning";
                        else if(level === "Low") badgeColor = "success";

                    }

                    const marker = L.marker(

                        [

                            parseFloat(country.latitude),

                            parseFloat(country.longitude)

                        ],

                        {

                            icon: markerColor(level)

                        }

                    );

                    marker.bindPopup(`

                        <div style="font-family:'Plus Jakarta Sans',sans-serif; min-width:210px; padding: 4px;">

                            <h6 style="font-weight:700; margin-bottom: 12px; font-size:15px; border-bottom: 1px solid var(--border-color); padding-bottom: 6px;">
                                ${country.name}
                            </h6>

                            <div style="font-size: 12px; line-height: 1.8; color: var(--text-muted);">
                                <div style="display:flex; justify-content:space-between; border-bottom:1px solid rgba(148, 163, 184, 0.08); padding: 2px 0;">
                                    <span>Capital:</span> <strong style="color:var(--text-main)">${country.capital ?? '-'}</strong>
                                </div>
                                <div style="display:flex; justify-content:space-between; border-bottom:1px solid rgba(148, 163, 184, 0.08); padding: 2px 0;">
                                    <span>Region:</span> <strong style="color:var(--text-main)">${country.region ?? '-'}</strong>
                                </div>
                                <div style="display:flex; justify-content:space-between; border-bottom:1px solid rgba(148, 163, 184, 0.08); padding: 2px 0;">
                                    <span>Population:</span> <strong style="color:var(--text-main)">${Number(country.population ?? 0).toLocaleString()}</strong>
                                </div>
                                <div style="display:flex; justify-content:space-between; border-bottom:1px solid rgba(148, 163, 184, 0.08); padding: 2px 0;">
                                    <span>Risk Score:</span> <strong style="color:var(--text-main)">${score}</strong>
                                </div>
                                <div style="display:flex; justify-content:space-between; padding-top: 6px; align-items:center;">
                                    <span>Risk Level:</span> 
                                    <span class="badge bg-${badgeColor}" style="padding:4px 8px !important; font-size:10px !important; line-height:1;">
                                        ${level}
                                    </span>
                                </div>
                                <div style="margin-top: 12px;">
                                    <a href="{{ url('/country') }}/${country.id}" class="btn btn-sm btn-primary w-100 text-decoration-none" style="font-size: 11.5px !important; padding: 6px 12px !important; display: flex; align-items: center; justify-content: center; color: #ffffff !important; font-weight: 700; border-radius: 8px;">
                                        View Details
                                    </a>
                                </div>
                            </div>

                        </div>

                    `);

                    markerCluster.addLayer(marker);

                }

            });

        })

        .catch(err=>{

            console.error(err);

        });

    }

    loadMap();

    setInterval(loadMap, 5000);

});

</script>

@endpush
