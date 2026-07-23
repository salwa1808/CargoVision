@extends('layouts.app')

@push('styles')
<style>
    .shipment-card{background:rgba(255,255,255,.035);border:1px solid rgba(255,255,255,.08);border-radius:16px}.shipment-stat{padding:20px;height:100%}.shipment-stat strong{display:block;font-size:1.8rem}.shipment-table{color:#fff}.shipment-table th{color:rgba(255,255,255,.5);font-size:.72rem;text-transform:uppercase;border-color:rgba(255,255,255,.08)}.shipment-table td{border-color:rgba(255,255,255,.06);vertical-align:middle}.route-line{color:rgba(255,255,255,.6);font-size:.82rem}.progress{height:7px;background:rgba(255,255,255,.1)}
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4"><h1 class="h3 text-white fw-bold mb-1">Shipment Monitoring <span class="badge bg-secondary fs-6">Fitur Tambahan</span></h1><p class="text-muted mb-1">Simulasi progres shipment yang diperkaya indikator risiko dunia nyata.</p><small class="text-warning">Posisi dan progres perjalanan bukan data GPS/AIS real-time.</small></div>
    <div class="row g-3 mb-4">
        @foreach(['total'=>['Total Shipment','primary'],'active'=>['Sedang Berjalan','info'],'delivered'=>['Terkirim','success'],'delayed'=>['Terlambat','danger']] as $key=>[$label,$color])
            <div class="col-6 col-lg-3"><div class="shipment-card shipment-stat"><strong class="text-{{ $color }}">{{ $statistics[$key] }}</strong><span class="text-muted small">{{ $label }}</span></div></div>
        @endforeach
    </div>
    <div class="shipment-card p-4">
        <form method="GET" action="{{ route('shipments.index') }}" class="row g-2 mb-4">
            <div class="col-lg-5"><input class="form-control" name="search" value="{{ request('search') }}" placeholder="Cari tracking, negara, atau kapal..."></div>
            <div class="col-lg-2"><select class="form-select" name="status"><option value="all">Semua status</option>@foreach(\App\Models\Shipment::STATUSES as $status)<option value="{{ $status }}" @selected(request('status')===$status)>{{ ucwords(str_replace('_',' ',$status)) }}</option>@endforeach</select></div>
            <div class="col-lg-2"><select class="form-select" name="transport_mode"><option value="all">Semua moda</option>@foreach(\App\Models\Shipment::TRANSPORT_MODES as $mode)<option value="{{ $mode }}" @selected(request('transport_mode')===$mode)>{{ ucfirst($mode) }}</option>@endforeach</select></div>
            <div class="col-lg-3 d-flex gap-2"><button class="btn btn-outline-light flex-grow-1">Filter</button><a class="btn btn-outline-secondary" href="{{ route('shipments.index') }}">Reset</a></div>
        </form>
        <div class="table-responsive"><table class="table shipment-table"><thead><tr><th>Tracking</th><th>Rute</th><th>Kapal</th><th>Status</th><th>ETA</th><th>Progress</th><th></th></tr></thead><tbody>
        @forelse($shipments as $shipment)
            <tr><td><strong>{{ $shipment->tracking_number }}</strong>@if($shipment->is_simulated)<span class="badge bg-secondary ms-1">Simulasi</span>@endif<div class="text-muted small">{{ ucfirst($shipment->transport_mode) }}</div></td>
            <td>{{ $shipment->originCountry?->name }} → {{ $shipment->destinationCountry?->name }}<div class="route-line">{{ $shipment->originPort?->name ?? '—' }} → {{ $shipment->destinationPort?->name ?? '—' }}</div></td>
            <td>{{ $shipment->vessel?->name ?? '—' }}<div class="text-muted small">{{ $shipment->vessel?->imo ? 'IMO '.$shipment->vessel->imo : '' }}</div></td>
            <td><span class="badge bg-{{ match($shipment->status){'delivered','arrived'=>'success','cancelled'=>'danger','in_transit','departed'=>'info','loading'=>'warning',default=>'secondary'} }}">{{ ucwords(str_replace('_',' ',$shipment->status)) }}</span></td>
            <td>{{ $shipment->estimated_arrival?->format('d M Y H:i') ?? '—' }}</td>
            <td style="min-width:130px"><div class="small mb-1">{{ $shipment->progress }}%</div><div class="progress"><div class="progress-bar" style="width:{{ $shipment->progress }}%"></div></div></td>
            <td><a class="btn btn-sm btn-primary" href="{{ route('shipments.show',$shipment) }}">Detail</a></td></tr>
        @empty<tr><td colspan="7" class="text-center text-muted py-5">Belum ada shipment yang tercatat oleh sistem.</td></tr>@endforelse
        </tbody></table></div>{{ $shipments->links() }}
    </div>
</div>
@endsection
