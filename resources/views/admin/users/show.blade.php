@extends('layouts.app')

@push('styles')
<style>
    .profile-details-card {
        background: rgba(255, 255, 255, 0.02) !important;
        backdrop-filter: blur(16px) !important;
        border: 1px solid rgba(255, 255, 255, 0.06) !important;
        border-radius: 20px !important;
        padding: 40px;
    }
    .large-avatar-box {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        background: linear-gradient(135deg, #a78bfa, #8b5cf6);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 56px;
        font-weight: 800;
        border: 4px solid rgba(139, 92, 246, 0.3);
        box-shadow: 0 0 30px rgba(139, 92, 246, 0.2);
        overflow: hidden;
        margin: 0 auto 20px;
    }
    .large-avatar-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .detail-label {
        font-size: 11px;
        text-transform: uppercase;
        font-weight: 700;
        color: rgba(255, 255, 255, 0.4);
        letter-spacing: 1px;
        margin-bottom: 4px;
    }
    .detail-value {
        font-size: 15px;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 20px;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <!-- Back navigation -->
    <div class="mb-4">
        <a href="{{ route('users.index') }}" class="btn btn-outline-light px-3 fw-bold" style="border-radius: 12px;">
            ⬅️ Kembali ke Daftar User
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="profile-details-card text-center">
                <!-- Avatar -->
                <div class="large-avatar-box">
                    @if($user->avatar)
                        <img src="{{ $user->avatar }}" alt="Avatar">
                    @else
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    @endif
                </div>

                <h2 class="text-white fw-bold mb-1">{{ $user->name }}</h2>
                <p class="text-muted mb-4">@ {{ $user->username ?: 'username_empty' }}</p>

                <div class="row text-start mt-4 pt-4 border-top border-white border-opacity-10">
                    <div class="col-md-6">
                        <div class="detail-label">Email Address</div>
                        <div class="detail-value">{{ $user->email }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label">Role</div>
                        <div class="detail-value">
                            <span class="badge {{ $user->role === 'admin' ? 'bg-primary' : 'bg-secondary' }}">
                                {{ $user->role === 'admin' ? 'Administrator' : 'Standard User' }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label">Account Status</div>
                        <div class="detail-value">
                            <span class="badge {{ $user->status === 'Active' ? 'bg-success' : 'bg-danger' }}">
                                {{ $user->status }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label">Joined Date</div>
                        <div class="detail-value">{{ $user->created_at->format('d M Y, H:i') }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label">Last Active Login</div>
                        <div class="detail-value">
                            {{ $user->last_login ? \Carbon\Carbon::parse($user->last_login)->format('d M Y, H:i') : 'Never logged in' }}
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-top border-white border-opacity-10 text-start">
                    <h5 class="text-white fw-bold mb-3">⏱ Recent Activity Summary</h5>
                    <ul class="list-unstyled text-muted small" style="line-height: 2;">
                        <li>🟢 User logged in on browser from Client IP (Success)</li>
                        <li>👤 Account initialized and role verified successfully</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
