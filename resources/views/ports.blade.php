@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-5 mt-2">

        <div>

            <h1 class="h3 fw-bold mb-1" style="letter-spacing: -0.02em;">
                🚢 World Ports
            </h1>

            <p class="text-muted fw-medium mb-0">
                Global maritime logistics hub tracking database
            </p>

        </div>

        <span class="badge bg-primary fs-6" style="padding: 10px 16px !important; border-radius: 12px !important;">

            Total Tracked:
            <strong style="font-size: 15px; margin-left: 2px;">{{ number_format($ports->total()) }}</strong>

        </span>

    </div>

    <!-- Search Form -->
    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="row g-3">

                    <div class="col-md-5">

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Search port name...">

                    </div>

                    <div class="col-md-4">

                        <select
                            name="country"
                            class="form-select">

                            <option value="">

                                All Countries

                            </option>

                            @foreach($countries as $country)

                                <option
                                    value="{{ $country->id }}"
                                    @selected(request('country')==$country->id)>

                                    {{ $country->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-3 d-flex gap-2">

                        <button class="btn btn-primary flex-grow-1 fw-bold" style="border-radius: 12px; padding: 10px 16px; background-color: #6366f1; border-color: #6366f1;">

                            🔍 Search

                        </button>

                        <a href="{{ route('ports') }}"
                           class="btn btn-outline-light fw-bold"
                           style="border-radius: 12px; padding: 10px 16px; border-color: var(--border-color);">

                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <!-- Map container inside card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>🗺️ Maritime Hubs & Vessel Tracking</span>
            <div class="d-flex gap-3 align-items-center">
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" id="togglePorts" checked style="cursor: pointer;">
                    <label class="form-check-label text-muted fw-semibold" for="togglePorts" style="font-size: 13px; cursor: pointer; user-select: none;">Ports</label>
                </div>
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" id="toggleVessels" checked style="cursor: pointer;">
                    <label class="form-check-label text-muted fw-semibold" for="toggleVessels" style="font-size: 13px; cursor: pointer; user-select: none;">Vessels</label>
                </div>
            </div>
        </div>
        <div class="card-body p-0" style="border-radius: 0 0 20px 20px; overflow: hidden;">
            <div id="portMap" style="height:480px"></div>
        </div>
    </div>

    <!-- Table -->
    <div class="card shadow-sm">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>

                    <tr style="border-bottom: 2px solid var(--border-color);">

                        <th width="80" style="padding-left: 24px;">No</th>

                        <th>Port Name</th>

                        <th>Country</th>

                        <th>Latitude</th>

                        <th>Longitude</th>

                    </tr>

                    </thead>

                    <tbody>

                    @foreach($ports as $port)

                    <tr>

                        <td style="padding-left: 24px;" class="text-muted fw-semibold">

                            {{ ($ports->currentPage() - 1) * $ports->perPage() + $loop->iteration }}

                        </td>

                        <td class="fw-bold" style="color: var(--text-main);">

                            {{ $port->name }}

                        </td>

                        <td>

                            {{ $port->country->name }}

                        </td>

                        <td class="text-muted" style="font-size: 13.5px;">

                            {{ $port->latitude }}

                        </td>

                        <td class="text-muted" style="font-size: 13.5px;">

                            {{ $port->longitude }}

                        </td>

                    </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>
    
    <div class="d-flex justify-content-center mt-4">
        {{ $ports->links() }}
    </div>

</div>

@endsection

@push('scripts')

<link
rel="stylesheet"
href="https://unpkg.com/leaflet/dist/leaflet.css"/>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<style>
.vessel-div-icon {
    background: transparent !important;
    border: none !important;
}
.leaflet-popup-content-wrapper {
    background: rgba(15, 8, 30, 0.96) !important;
    background-color: rgba(15, 8, 30, 0.96) !important;
    backdrop-filter: blur(12px) !important;
    -webkit-backdrop-filter: blur(12px) !important;
    color: #ffffff !important;
    border: 1px solid rgba(255, 255, 255, 0.15) !important;
    border-radius: 14px !important;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.6) !important;
}
.leaflet-popup-tip {
    background: rgba(15, 8, 30, 0.96) !important;
    background-color: rgba(15, 8, 30, 0.96) !important;
}
.leaflet-popup-content {
    color: #D1D5DB !important;
}
.leaflet-popup-content strong, .leaflet-popup-content h5, .leaflet-popup-content h6 {
    color: #ffffff !important;
}
.leaflet-popup-close-button {
    color: rgba(255, 255, 255, 0.6) !important;
}
.leaflet-popup-close-button:hover {
    color: #ffffff !important;
    background: transparent !important;
}
</style>

<script>

const map=L.map('portMap').setView([20,0],2);

let activeTileLayer;
function getTileUrl() {
    return document.body.classList.contains('bg-dark')
        ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png'
        : 'https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png';
}

activeTileLayer = L.tileLayer(getTileUrl(), {
    maxZoom: 18,
    attribution: '© OpenStreetMap © CartoDB'
}).addTo(map);

// Watch for theme toggles to swap tiles
document.getElementById('darkModeBtn').addEventListener('click', () => {
    setTimeout(() => {
        map.removeLayer(activeTileLayer);
        activeTileLayer = L.tileLayer(getTileUrl(), {
            maxZoom: 18,
            attribution: '© OpenStreetMap © CartoDB'
        }).addTo(map);
    }, 100);
});

// Layer Groups
const portsLayer = L.layerGroup().addTo(map);
const vesselsLayer = L.layerGroup().addTo(map);

// Load Ports
fetch("{{ url('/api/ports') }}")
.then(r=>r.json())
.then(data=>{
    data.forEach(port=>{
        L.marker([
            port.latitude,
            port.longitude
        ])
        .addTo(portsLayer)
        .bindPopup(`
            <div style="font-family:'Plus Jakarta Sans',sans-serif; padding: 4px;">
                <h6 style="font-weight:700; margin-bottom: 4px; font-size:14px; color:#ffffff;">${port.name}</h6>
                <span style="font-size:12px; color:#9CA3AF;">Country: <strong style="color:#D1D5DB;">${port.country.name}</strong></span>
            </div>
        `);
    });
});

// Helper for Vessel Color
function getVesselColor(type) {
    switch(type) {
        case 'Container': return '#10b981'; // Emerald Green
        case 'Tanker': return '#ef4444'; // Red
        case 'Cargo': return '#3b82f6'; // Blue
        case 'Bulk Carrier': return '#f59e0b'; // Amber
        case 'Passenger': return '#8b5cf6'; // Purple
        default: return '#6b7280'; // Gray
    }
}

// Client-side snapping to prevent vessels from showing up on land
function adjustIfOnLand(lat, lng) {
    let newLat = lat;
    let newLng = lng;

    // Australia Land Box
    if (lat > -39 && lat < -11 && lng > 113 && lng < 153) {
        let distWest = lng - 113;
        let distEast = 153 - lng;
        let distSouth = lat - (-39);
        let distNorth = -11 - lat;
        let minDist = Math.min(distWest, distEast, distSouth, distNorth);
        if (minDist === distWest) newLng = 112;
        else if (minDist === distEast) newLng = 154;
        else if (minDist === distSouth) newLat = -40;
        else newLat = -10;
    }
    // US / Canada Land Box
    else if (lat > 25 && lat < 49 && lng > -125 && lng < -70) {
        let distWest = lng - (-125);
        let distEast = -70 - lng;
        if (distWest < distEast) newLng = -127;
        else newLng = -68;
    }
    // South America Land Box
    else if (lat > -55 && lat < 12 && lng > -81 && lng < -35) {
        let distWest = lng - (-81);
        let distEast = -35 - lng;
        if (distWest < distEast) newLng = -83;
        else newLng = -33;
    }
    // Africa Land Box
    else if (lat > -35 && lat < 37 && lng > -17 && lng < 51) {
        let distWest = lng - (-17);
        let distEast = 51 - lng;
        if (distWest < distEast) newLng = -19;
        else newLng = 53;
    }
    // Europe / Northern Asia Land Box
    else if (lat > 45 && lat < 70 && lng > -10 && lng < 140) {
        if (lng < 20) {
            newLat = 50.5;
            newLng = -1.0;
        } else {
            newLat = 72;
        }
    }
    // India / Southeast Asia Land Box
    else if (lat > 8 && lat < 30 && lng > 68 && lng < 100) {
        let distWest = lng - 68;
        let distEast = 100 - lng;
        if (distWest < distEast) newLng = 66;
        else newLng = 102;
    }

    return [newLat, newLng];
}

// Load Vessels
fetch("{{ url('/api/vessels') }}")
.then(r=>r.json())
.then(data=>{
    const vesselMarkers = [];

    data.forEach(vessel=>{
        const color = getVesselColor(vessel.type);
        const heading = vessel.heading || 0;
        
        // Correct position dynamically if initial coordinates are on land
        const initialPos = adjustIfOnLand(vessel.latitude, vessel.longitude);
        const vesselLat = initialPos[0];
        const vesselLng = initialPos[1];
        
        // Custom rotated SVG ship marker
        const vesselIcon = L.divIcon({
            html: `
                <div style="transform: rotate(${heading}deg); width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="${color}" stroke="#ffffff" stroke-width="1.5" style="filter: drop-shadow(0px 2px 4px rgba(0,0,0,0.3));">
                        <path d="M12,2 L16,6 L16,17 L12,21 L8,17 L8,6 Z" />
                        <circle cx="12" cy="11" r="1.5" fill="#ffffff" />
                    </svg>
                </div>
            `,
            className: 'vessel-div-icon',
            iconSize: [30, 30],
            iconAnchor: [15, 15]
        });

        // Detailed popup content
        const popupContent = `
            <div style="font-family:'Plus Jakarta Sans',sans-serif; width: 220px; padding: 4px; color: #D1D5DB;">
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.12); padding-bottom: 6px; margin-bottom: 8px;">
                    <h6 style="font-weight:700; margin: 0; font-size:14px; color:#ffffff;">${vessel.name}</h6>
                    <span style="font-size: 10px; font-weight: 700; padding: 2px 6px; border-radius: 6px; background: ${color}30; color: ${color}; border: 1px solid ${color}60;">
                        ${vessel.type}
                    </span>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; font-size:12px; margin-bottom: 8px;">
                    <div><span style="font-size:11px; color:#9CA3AF;">Status:</span><br><strong style="color:#D1D5DB;">${vessel.status}</strong></div>
                    <div><span style="font-size:11px; color:#9CA3AF;">Speed:</span><br><strong style="color:#D1D5DB;">${vessel.speed} kn</strong></div>
                    <div><span style="font-size:11px; color:#9CA3AF;">Heading:</span><br><strong id="popup-heading-${vessel.id}" style="color:#D1D5DB;">${heading}°</strong></div>
                    <div><span style="font-size:11px; color:#9CA3AF;">IMO:</span><br><strong style="color:#D1D5DB;">${vessel.imo || '-'}</strong></div>
                </div>
                <div style="padding-top: 6px; border-top: 1px dashed rgba(255,255,255,0.12); font-size:12px;">
                    <span style="font-size:11px; color:#9CA3AF;">Destination:</span><br>
                    <strong style="color:#D1D5DB;">${vessel.destination || 'Unknown'}</strong> ${vessel.port ? `<span style="font-size:11px; color:#9CA3AF;">(${vessel.port.name})</span>` : ''}
                </div>
            </div>
        `;

        const marker = L.marker([
            vesselLat,
            vesselLng
        ], { icon: vesselIcon })
        .addTo(vesselsLayer)
        .bindPopup(popupContent);

        vesselMarkers.push({
            id: vessel.id,
            marker: marker,
            status: vessel.status,
            speed: parseFloat(vessel.speed) || 12,
            heading: heading
        });
    });

    // Real-time animation loop
    setInterval(() => {
        vesselMarkers.forEach(item => {
            if (item.status === 'Underway') {
                let currentLatLng = item.marker.getLatLng();
                let lat = currentLatLng.lat;
                let lng = currentLatLng.lng;
                
                // Adjust step size based on ship speed (1 knot = ~0.0001 deg per step)
                let step = item.speed * 0.0002;
                
                // Convert heading (0 is North, 90 is East) to standard polar angle
                let rad = (90 - item.heading) * Math.PI / 180;
                let dLat = Math.sin(rad) * step;
                let dLng = Math.cos(rad) * step;
                
                let nextLat = lat + dLat;
                let nextLng = lng + dLng;
                
                // Wrap longitude
                if (nextLng > 180) nextLng -= 360;
                if (nextLng < -180) nextLng += 360;

                // Keep inside lat bounds
                if (nextLat > 85) nextLat = 85;
                if (nextLat < -85) nextLat = -85;
                
                // Snap if hit land and turn around
                let adjusted = adjustIfOnLand(nextLat, nextLng);
                if (adjusted[0] !== nextLat || adjusted[1] !== nextLng) {
                    item.heading = (item.heading + 180) % 360;
                    
                    const element = item.marker.getElement();
                    if (element) {
                        const divIcon = element.querySelector('div');
                        if (divIcon) {
                            divIcon.style.transform = `rotate(${item.heading}deg)`;
                        }
                    }
                    
                    const pHeading = document.getElementById(`popup-heading-${item.id}`);
                    if (pHeading) {
                        pHeading.innerText = `${item.heading}°`;
                    }

                    nextLat = adjusted[0];
                    nextLng = adjusted[1];
                }
                
                item.marker.setLatLng([nextLat, nextLng]);
            }
        });
    }, 1000);
});

// Toggle handlers
document.getElementById('togglePorts').addEventListener('change', function(e) {
    if (e.target.checked) {
        map.addLayer(portsLayer);
    } else {
        map.removeLayer(portsLayer);
    }
});

document.getElementById('toggleVessels').addEventListener('change', function(e) {
    if (e.target.checked) {
        map.addLayer(vesselsLayer);
    } else {
        map.removeLayer(vesselsLayer);
    }
});

</script>

@endpush