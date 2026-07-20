@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-5 mt-2">

        <div>

            <h1 class="h3 fw-bold mb-1" style="letter-spacing: -0.02em;">
                🌍 Global Supply Chain Risk Dashboard
            </h1>

            <small class="text-muted fw-medium">
                Real-Time Global Logistics & Risk Intelligence Platform
            </small>

        </div>

        <div class="text-end">

            <span class="badge bg-success fs-6 d-inline-flex align-items-center" id="liveStatus" style="padding: 8px 14px !important; border-radius: 10px !important;">
                <span class="pulse-circle"></span>LIVE
            </span>

            <div class="mt-2">

                <small class="text-muted fw-semibold" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em;">

                    Last Sync :

                    <span id="lastUpdate" class="text-main fw-normal" style="font-size: 12px; margin-left: 4px;">
                        -
                    </span>

                </small>

            </div>

        </div>

    </div>

    {{-- Statistik --}}
    @include('partials.stats')

    {{-- Chart --}}
    @include('partials.chart')

    {{-- Peta --}}
    @include('partials.map')

    {{-- Tabel --}}
    @include('partials.risk-table')

</div>

<script>

function updateClock(){

    const now = new Date();

    document.getElementById('lastUpdate').innerHTML =
        now.toLocaleString();

}

updateClock();

setInterval(updateClock,1000);

</script>

@endsection