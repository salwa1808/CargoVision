@extends('layouts.app')

@push('styles')
<style>
    /* Profile Page Styles */
    .profile-hero {
        display: flex; align-items: center; gap: 24px;
        padding: 32px; margin-bottom: 24px;
    }
    .profile-avatar-lg {
        width: 100px; height: 100px; border-radius: 50%;
        background: linear-gradient(135deg, #a78bfa, #8b5cf6);
        display: flex; align-items: center; justify-content: center;
        font-size: 40px; font-weight: 800; color: #fff;
        border: 4px solid rgba(139,92,246,0.3);
        box-shadow: 0 0 30px rgba(139,92,246,0.3);
        flex-shrink: 0; position: relative; overflow: hidden;
    }
    .profile-avatar-lg img {
        width: 100%; height: 100%; object-fit: cover; border-radius: 50%;
    }
    .profile-hero-info h2 { margin: 0; font-weight: 800; font-size: 24px; }
    .profile-hero-info .role-badge {
        display: inline-block; padding: 4px 14px; border-radius: 20px;
        background: rgba(139,92,246,0.15); color: #c084fc;
        font-size: 12px; font-weight: 700; margin-top: 6px;
    }
    .profile-hero-info .email-text {
        color: rgba(255,255,255,0.5); font-size: 14px; margin-top: 4px;
    }

    .profile-tabs {
        display: flex; gap: 4px; border-bottom: 1px solid rgba(255,255,255,0.06);
        margin-bottom: 24px; overflow-x: auto;
    }
    .profile-tab {
        padding: 12px 20px; font-size: 13px; font-weight: 700;
        color: rgba(255,255,255,0.5); cursor: pointer; border: none;
        background: none; border-bottom: 3px solid transparent;
        transition: all 0.2s; white-space: nowrap;
    }
    .profile-tab:hover { color: rgba(255,255,255,0.8); }
    .profile-tab.active {
        color: #c084fc; border-bottom-color: #8b5cf6;
    }
    .tab-pane { display: none; }
    .tab-pane.active { display: block; animation: fadeIn 0.3s ease; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }

    .stat-card-profile {
        text-align: center; padding: 24px;
    }
    .stat-card-profile .stat-value {
        font-size: 32px; font-weight: 800;
        background: linear-gradient(135deg, #a78bfa, #8b5cf6);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    }
    .stat-card-profile .stat-label {
        font-size: 12px; font-weight: 700; color: rgba(255,255,255,0.4);
        text-transform: uppercase; letter-spacing: 0.05em; margin-top: 4px;
    }

    .info-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 14px 0; border-bottom: 1px solid rgba(255,255,255,0.04);
    }
    .info-row:last-child { border-bottom: none; }
    .info-label { color: rgba(255,255,255,0.4); font-size: 13px; font-weight: 600; }
    .info-value { color: #fff; font-size: 13px; font-weight: 700; }

    .timeline-item {
        display: flex; gap: 14px; padding: 14px 0;
        border-bottom: 1px solid rgba(255,255,255,0.03);
    }
    .timeline-dot {
        width: 10px; height: 10px; border-radius: 50%;
        background: #8b5cf6; margin-top: 5px; flex-shrink: 0;
        box-shadow: 0 0 8px rgba(139,92,246,0.4);
    }
    .timeline-text { font-size: 13px; color: rgba(255,255,255,0.7); font-weight: 600; }
    .timeline-time { font-size: 11px; color: rgba(255,255,255,0.3); margin-top: 2px; }

    /* Password Strength */
    .strength-bar {
        height: 6px; border-radius: 3px; background: rgba(255,255,255,0.05);
        margin-top: 8px; overflow: hidden;
    }
    .strength-fill {
        height: 100%; border-radius: 3px; transition: all 0.3s; width: 0;
    }
    .strength-weak { background: #ef4444; width: 33%; }
    .strength-medium { background: #f59e0b; width: 66%; }
    .strength-strong { background: #10b981; width: 100%; }

    .pwd-toggle {
        position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
        background: none; border: none; color: rgba(255,255,255,0.4);
        cursor: pointer; font-size: 14px;
    }

    /* Activity Log Table */
    .activity-table {
        width: 100%; font-size: 12.5px;
    }
    .activity-table thead th {
        color: rgba(255,255,255,0.4); font-weight: 700; font-size: 11px;
        text-transform: uppercase; letter-spacing: 0.05em;
        padding: 10px 12px; border-bottom: 1px solid rgba(255,255,255,0.06);
    }
    .activity-table tbody td {
        padding: 10px 12px; color: rgba(255,255,255,0.7); font-weight: 500;
        border-bottom: 1px solid rgba(255,255,255,0.03);
    }
    .activity-table tbody tr:hover { background: rgba(139,92,246,0.04); }
    .status-badge {
        font-size: 10px; font-weight: 700; padding: 3px 10px;
        border-radius: 20px;
    }
    .status-success { background: rgba(16,185,129,0.15); color: #34d399; }
    .status-error { background: rgba(239,68,68,0.15); color: #f87171; }

    .pagination-controls {
        display: flex; justify-content: center; gap: 6px; margin-top: 16px;
    }
    .pagination-controls button {
        background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);
        color: rgba(255,255,255,0.6); padding: 6px 12px; border-radius: 8px;
        font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s;
    }
    .pagination-controls button:hover, .pagination-controls button.active {
        background: rgba(139,92,246,0.2); color: #c084fc; border-color: rgba(139,92,246,0.3);
    }
    .pagination-controls button:disabled { opacity: 0.3; cursor: default; }

    /* Toast Notification */
    .profile-toast {
        position: fixed; top: 24px; right: 24px; z-index: 999999;
        padding: 14px 24px; border-radius: 12px;
        background: rgba(16,185,129,0.2); border: 1px solid rgba(16,185,129,0.3);
        color: #34d399; font-size: 13px; font-weight: 700;
        display: none; animation: notifSlideDown 0.3s ease;
        backdrop-filter: blur(16px);
    }
    .profile-toast.show { display: block; }
    .profile-toast.error { background: rgba(239,68,68,0.2); border-color: rgba(239,68,68,0.3); color: #f87171; }

    @media (max-width: 768px) {
        .profile-hero { flex-direction: column; text-align: center; }
        .profile-avatar-lg { width: 80px; height: 80px; font-size: 32px; }
    }
</style>
@endpush

@section('content')
<div class="container py-4">

    <!-- Toast -->
    <div class="profile-toast" id="profileToast"></div>

    <!-- Profile Hero Card -->
    <div class="card mb-4">
        <div class="profile-hero">
            <div class="profile-avatar-lg" id="profileAvatarDisplay">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div class="profile-hero-info">
                <h2 id="profileNameDisplay">{{ $user->name }}</h2>
                <div class="role-badge">{{ $user->role === 'admin' ? '🛡️ Administrator' : '👤 Standard User' }}</div>
                <div class="email-text">{{ $user->email }}</div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="profile-tabs">
        <button class="profile-tab active" data-tab="view" onclick="switchTab('view', this)">👤 Profile</button>
        <button class="profile-tab" data-tab="edit" onclick="switchTab('edit', this)">✏️ Edit Profile</button>
        <button class="profile-tab" data-tab="security" onclick="switchTab('security', this)">🔒 Security</button>
        <button class="profile-tab" data-tab="activity" onclick="switchTab('activity', this)">📋 Activity Log</button>
    </div>

    <!-- ============ TAB 1: VIEW PROFILE ============ -->
    <div class="tab-pane active" id="tab-view">
        <div class="row g-4">
            <!-- Stats Cards -->
            <div class="col-6 col-md-3">
                <div class="card stat-card-profile">
                    <div class="stat-value">{{ $countriesManaged }}</div>
                    <div class="stat-label">Countries Managed</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card stat-card-profile">
                    <div class="stat-value">{{ $riskUpdates }}</div>
                    <div class="stat-label">Risk Updates</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card stat-card-profile">
                    <div class="stat-value">{{ $weatherUpdates }}</div>
                    <div class="stat-label">Weather Updates</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card stat-card-profile">
                    <div class="stat-value" id="loginCountDisplay">0</div>
                    <div class="stat-label">Login Count</div>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">📋 Personal Information</div>
                    <div class="card-body">
                        <div class="info-row"><span class="info-label">Full Name</span><span class="info-value" id="infoName">{{ $user->name }}</span></div>
                        <div class="info-row"><span class="info-label">Email</span><span class="info-value">{{ $user->email }}</span></div>
                        <div class="info-row"><span class="info-label">Role</span><span class="info-value">{{ $user->role === 'admin' ? 'Administrator' : 'Standard User' }}</span></div>
                        <div class="info-row"><span class="info-label">Phone</span><span class="info-value" id="infoPhone">—</span></div>
                        <div class="info-row"><span class="info-label">Location</span><span class="info-value" id="infoLocation">—</span></div>
                        <div class="info-row"><span class="info-label">Timezone</span><span class="info-value" id="infoTimezone">—</span></div>
                        <div class="info-row"><span class="info-label">Joined</span><span class="info-value">{{ $user->created_at->format('d M Y') }}</span></div>
                        <div class="info-row"><span class="info-label">Last Login</span><span class="info-value" id="infoLastLogin">—</span></div>
                    </div>
                </div>
            </div>

            <!-- Bio & Activity Timeline -->
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header">📝 Bio</div>
                    <div class="card-body">
                        <p id="infoBio" style="color: rgba(255,255,255,0.6); font-size: 14px; line-height: 1.7; margin: 0;">No bio set.</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">⏱️ Recent Activity</div>
                    <div class="card-body" id="recentTimeline">
                        <div style="color:rgba(255,255,255,0.3); font-size:13px;">No activity recorded yet.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============ TAB 2: EDIT PROFILE ============ -->
    <div class="tab-pane" id="tab-edit">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">🖼️ Avatar</div>
                    <div class="card-body text-center">
                        <div class="profile-avatar-lg mx-auto mb-3" id="editAvatarPreview" style="width:120px;height:120px;font-size:48px;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <input type="file" id="avatarFileInput" accept="image/*" class="d-none">
                        <button class="btn btn-outline-light btn-sm" onclick="document.getElementById('avatarFileInput').click()">📷 Change Avatar</button>
                        <button class="btn btn-outline-danger btn-sm ms-2" onclick="removeAvatar()">🗑️ Remove</button>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">✏️ Edit Profile Information</div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold text-uppercase">Full Name</label>
                                <input type="text" class="form-control" id="editName" value="{{ $user->name }}">
                                <div class="invalid-feedback" style="display:none;" id="editNameError">Name is required.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold text-uppercase">Username</label>
                                <input type="text" class="form-control" id="editUsername" placeholder="username">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold text-uppercase">Email (read-only)</label>
                                <input type="email" class="form-control" value="{{ $user->email }}" readonly style="opacity:0.5;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold text-uppercase">Phone Number</label>
                                <input type="tel" class="form-control" id="editPhone" placeholder="+62 xxx xxxx xxxx">
                                <div class="invalid-feedback" style="display:none;" id="editPhoneError">Invalid phone number.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold text-uppercase">Location</label>
                                <input type="text" class="form-control" id="editLocation" placeholder="Jakarta, Indonesia">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold text-uppercase">Timezone</label>
                                <select class="form-select" id="editTimezone">
                                    <option value="Asia/Jakarta">Asia/Jakarta (WIB)</option>
                                    <option value="Asia/Makassar">Asia/Makassar (WITA)</option>
                                    <option value="Asia/Jayapura">Asia/Jayapura (WIT)</option>
                                    <option value="Asia/Singapore">Asia/Singapore</option>
                                    <option value="Asia/Tokyo">Asia/Tokyo</option>
                                    <option value="Europe/London">Europe/London</option>
                                    <option value="America/New_York">America/New York</option>
                                    <option value="America/Los_Angeles">America/Los Angeles</option>
                                    <option value="UTC">UTC</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-muted small fw-bold text-uppercase">Bio</label>
                                <textarea class="form-control" id="editBio" rows="3" placeholder="Write something about yourself..."></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold text-uppercase">Preferred Language</label>
                                <select class="form-select" id="editLang">
                                    <option value="en">English</option>
                                    <option value="id">Indonesia</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold text-uppercase">Theme</label>
                                <select class="form-select" id="editTheme">
                                    <option value="dark">Dark Mode</option>
                                    <option value="light">Light Mode</option>
                                    <option value="auto">Auto (System)</option>
                                </select>
                            </div>
                            <div class="col-12 text-end mt-3">
                                <button class="btn btn-primary fw-bold px-4" onclick="saveProfile()" style="border-radius:12px;">
                                    💾 Save Profile
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============ TAB 3: SECURITY ============ -->
    <div class="tab-pane" id="tab-security">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">🔒 Change Password</div>
                    <div class="card-body">
                        <div class="mb-3 position-relative">
                            <label class="form-label text-muted small fw-bold text-uppercase">Current Password</label>
                            <input type="password" class="form-control" id="oldPassword">
                            <button class="pwd-toggle" onclick="togglePwd('oldPassword', this)">👁️</button>
                        </div>
                        <div class="mb-3 position-relative">
                            <label class="form-label text-muted small fw-bold text-uppercase">New Password</label>
                            <input type="password" class="form-control" id="newPassword" oninput="checkStrength(this.value)">
                            <button class="pwd-toggle" onclick="togglePwd('newPassword', this)">👁️</button>
                            <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
                            <small class="text-muted" id="strengthText" style="font-size:11px;"></small>
                        </div>
                        <div class="mb-4 position-relative">
                            <label class="form-label text-muted small fw-bold text-uppercase">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirmPassword">
                            <button class="pwd-toggle" onclick="togglePwd('confirmPassword', this)">👁️</button>
                        </div>
                        <button class="btn btn-primary fw-bold w-100 py-2" onclick="changePassword()" style="border-radius:12px;">
                            🔐 Update Password
                        </button>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">⚙️ Account Settings</div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold text-uppercase">Theme</label>
                                <select class="form-select" id="secTheme">
                                    <option value="dark">Dark Mode</option>
                                    <option value="light">Light Mode</option>
                                    <option value="auto">System Theme</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold text-uppercase">Language</label>
                                <select class="form-select" id="secLang">
                                    <option value="en">English</option>
                                    <option value="id">Indonesia</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="secDesktopNotif">
                                    <label class="form-check-label" for="secDesktopNotif" style="color:rgba(255,255,255,0.7);font-size:13px;font-weight:600;">Desktop Notifications</label>
                                </div>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="secEmailNotif">
                                    <label class="form-check-label" for="secEmailNotif" style="color:rgba(255,255,255,0.7);font-size:13px;font-weight:600;">Email Notifications</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="secAutoRefresh" checked>
                                    <label class="form-check-label" for="secAutoRefresh" style="color:rgba(255,255,255,0.7);font-size:13px;font-weight:600;">Auto Refresh Dashboard</label>
                                </div>
                            </div>
                            <div class="col-12 text-end mt-2">
                                <button class="btn btn-primary fw-bold px-4" onclick="saveAccountSettings()" style="border-radius:12px;">💾 Save Settings</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============ TAB 4: ACTIVITY LOG ============ -->
    <div class="tab-pane" id="tab-activity">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span>📋 Activity Log</span>
                <div class="d-flex gap-2">
                    <input type="text" id="activitySearch" class="form-control form-control-sm" placeholder="Search..." style="width:180px;font-size:12px;">
                    <select id="activityFilter" class="form-select form-select-sm" style="width:140px;font-size:12px;" onchange="renderActivityLog()">
                        <option value="all">All Modules</option>
                        <option value="Dashboard">Dashboard</option>
                        <option value="Profile">Profile</option>
                        <option value="Settings">Settings</option>
                        <option value="Weather">Weather</option>
                        <option value="Global Map">Global Map</option>
                    </select>
                    <button class="btn btn-outline-light btn-sm" onclick="exportCSV()" style="font-size:11px;white-space:nowrap;">📥 CSV</button>
                </div>
            </div>
            <div class="card-body p-0" style="overflow-x:auto;">
                <table class="activity-table">
                    <thead>
                        <tr><th>Date</th><th>Action</th><th>Module</th><th>IP</th><th>Browser</th><th>Status</th></tr>
                    </thead>
                    <tbody id="activityTableBody"></tbody>
                </table>
            </div>
            <div class="card-body pt-0">
                <div class="pagination-controls" id="activityPagination"></div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-switch to tab based on hash
    const hash = window.location.hash.replace('#', '');
    if (hash) {
        const tabBtn = document.querySelector(`.profile-tab[data-tab="${hash}"]`);
        if (tabBtn) switchTab(hash, tabBtn);
    }

    loadProfileData();
    renderActivityLog();
    loadAccountSettings();

    // Avatar file handler
    document.getElementById('avatarFileInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(evt) {
            const data = evt.target.result;
            localStorage.setItem('profile_avatar', data);
            updateAvatarDisplays(data);
        };
        reader.readAsDataURL(file);
    });

    // Search activity log on input
    document.getElementById('activitySearch').addEventListener('input', () => renderActivityLog());

    // Track login count
    let loginCount = parseInt(localStorage.getItem('login_count') || '0');
    loginCount++;
    localStorage.setItem('login_count', loginCount);
    document.getElementById('loginCountDisplay').textContent = loginCount;
});

function switchTab(tab, btn) {
    document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.profile-tab').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + tab)?.classList.add('active');
    if (btn) btn.classList.add('active');
    history.replaceState(null, null, '#' + tab);
}

function showToast(msg, isError) {
    const t = document.getElementById('profileToast');
    t.textContent = msg;
    t.className = 'profile-toast show' + (isError ? ' error' : '');
    setTimeout(() => t.classList.remove('show'), 3000);
}

// ====== PROFILE DATA (localStorage) ======
function loadProfileData() {
    const phone = localStorage.getItem('profile_phone') || '';
    const location = localStorage.getItem('profile_location') || '';
    const timezone = localStorage.getItem('profile_timezone') || 'Asia/Jakarta';
    const bio = localStorage.getItem('profile_bio') || '';
    const username = localStorage.getItem('profile_username') || '';
    const lang = localStorage.getItem('pref_lang') || 'en';
    const theme = localStorage.getItem('theme') || 'dark';
    const avatar = localStorage.getItem('profile_avatar') || '';
    const lastLogin = localStorage.getItem('profile_last_login') || new Date().toISOString();

    // Update last login
    localStorage.setItem('profile_last_login', new Date().toISOString());

    // View Tab
    document.getElementById('infoPhone').textContent = phone || '—';
    document.getElementById('infoLocation').textContent = location || '—';
    document.getElementById('infoTimezone').textContent = timezone;
    document.getElementById('infoBio').textContent = bio || 'No bio set.';
    document.getElementById('infoLastLogin').textContent = UserPrefs.formatDate(lastLogin);

    // Edit Tab
    document.getElementById('editPhone').value = phone;
    document.getElementById('editLocation').value = location;
    document.getElementById('editTimezone').value = timezone;
    document.getElementById('editBio').value = bio;
    document.getElementById('editUsername').value = username;
    document.getElementById('editLang').value = lang;
    document.getElementById('editTheme').value = theme;

    // Avatar
    if (avatar) updateAvatarDisplays(avatar);

    // Recent Activity Timeline
    loadRecentTimeline();
}

function updateAvatarDisplays(dataUrl) {
    ['profileAvatarDisplay', 'editAvatarPreview'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.innerHTML = `<img src="${dataUrl}" alt="Avatar">`;
    });
}

function removeAvatar() {
    localStorage.removeItem('profile_avatar');
    const initial = '{{ strtoupper(substr($user->name, 0, 1)) }}';
    ['profileAvatarDisplay', 'editAvatarPreview'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.textContent = initial;
    });
    showToast('Avatar removed.');
}

function saveProfile() {
    const name = document.getElementById('editName').value.trim();
    const phone = document.getElementById('editPhone').value.trim();
    const nameErr = document.getElementById('editNameError');
    const phoneErr = document.getElementById('editPhoneError');

    nameErr.style.display = 'none';
    phoneErr.style.display = 'none';

    let valid = true;
    if (!name) { nameErr.style.display = 'block'; valid = false; }
    if (phone && !/^[\+]?[\d\s\-()]{7,20}$/.test(phone)) { phoneErr.style.display = 'block'; valid = false; }
    if (!valid) return;

    localStorage.setItem('profile_username', document.getElementById('editUsername').value.trim());
    localStorage.setItem('profile_phone', phone);
    localStorage.setItem('profile_location', document.getElementById('editLocation').value.trim());
    localStorage.setItem('profile_timezone', document.getElementById('editTimezone').value);
    localStorage.setItem('profile_bio', document.getElementById('editBio').value.trim());
    localStorage.setItem('pref_lang', document.getElementById('editLang').value);
    localStorage.setItem('theme', document.getElementById('editTheme').value);

    document.getElementById('infoPhone').textContent = phone || '—';
    document.getElementById('infoLocation').textContent = document.getElementById('editLocation').value.trim() || '—';
    document.getElementById('infoTimezone').textContent = document.getElementById('editTimezone').value;
    document.getElementById('infoBio').textContent = document.getElementById('editBio').value.trim() || 'No bio set.';

    if (typeof ActivityLog !== 'undefined') ActivityLog.log('Updated Profile', 'Profile');
    showToast('✅ Profile Updated Successfully');
}

// ====== RECENT ACTIVITY TIMELINE ======
function loadRecentTimeline() {
    const items = (typeof ActivityLog !== 'undefined') ? ActivityLog.getAll().slice(0, 10) : [];
    const container = document.getElementById('recentTimeline');
    if (!items.length) {
        container.innerHTML = '<div style="color:rgba(255,255,255,0.3);font-size:13px;">No activity recorded yet.</div>';
        return;
    }
    container.innerHTML = items.map(i => `
        <div class="timeline-item">
            <div class="timeline-dot"></div>
            <div>
                <div class="timeline-text">${i.action}</div>
                <div class="timeline-time">${UserPrefs.formatDate(i.date)} — ${new Date(i.date).toLocaleTimeString()}</div>
            </div>
        </div>
    `).join('');
}

// ====== PASSWORD ======
function togglePwd(id, btn) {
    const input = document.getElementById(id);
    if (input.type === 'password') {
        input.type = 'text'; btn.textContent = '🙈';
    } else {
        input.type = 'password'; btn.textContent = '👁️';
    }
}

function checkStrength(val) {
    const fill = document.getElementById('strengthFill');
    const text = document.getElementById('strengthText');
    fill.className = 'strength-fill';
    if (val.length === 0) { text.textContent = ''; return; }
    let score = 0;
    if (val.length >= 6) score++;
    if (val.length >= 10) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    if (score <= 2) { fill.classList.add('strength-weak'); text.textContent = '🔴 Weak'; text.style.color = '#ef4444'; }
    else if (score <= 3) { fill.classList.add('strength-medium'); text.textContent = '🟡 Medium'; text.style.color = '#f59e0b'; }
    else { fill.classList.add('strength-strong'); text.textContent = '🟢 Strong'; text.style.color = '#10b981'; }
}

function changePassword() {
    const old = document.getElementById('oldPassword').value;
    const nw = document.getElementById('newPassword').value;
    const conf = document.getElementById('confirmPassword').value;

    if (!old || !nw || !conf) { showToast('All fields are required.', true); return; }
    if (nw !== conf) { showToast('Passwords do not match.', true); return; }
    if (nw.length < 6) { showToast('Password must be at least 6 characters.', true); return; }

    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    fetch('{{ route("profile.password") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        body: JSON.stringify({ old_password: old, new_password: nw, new_password_confirmation: conf })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast('✅ ' + data.message);
            document.getElementById('oldPassword').value = '';
            document.getElementById('newPassword').value = '';
            document.getElementById('confirmPassword').value = '';
            document.getElementById('strengthFill').className = 'strength-fill';
            document.getElementById('strengthText').textContent = '';
            if (typeof ActivityLog !== 'undefined') ActivityLog.log('Changed Password', 'Security');
        } else {
            showToast('❌ ' + (data.message || 'Failed to update password.'), true);
        }
    })
    .catch(() => showToast('❌ Network error.', true));
}

// ====== ACCOUNT SETTINGS ======
function loadAccountSettings() {
    document.getElementById('secTheme').value = localStorage.getItem('theme') || 'dark';
    document.getElementById('secLang').value = localStorage.getItem('pref_lang') || 'en';
    document.getElementById('secDesktopNotif').checked = localStorage.getItem('enable_desktop') === 'on';
    document.getElementById('secEmailNotif').checked = localStorage.getItem('enable_email_notif') === 'on';
    document.getElementById('secAutoRefresh').checked = localStorage.getItem('auto_refresh') !== 'off';
}

function saveAccountSettings() {
    localStorage.setItem('theme', document.getElementById('secTheme').value);
    localStorage.setItem('pref_lang', document.getElementById('secLang').value);
    localStorage.setItem('enable_desktop', document.getElementById('secDesktopNotif').checked ? 'on' : 'off');
    localStorage.setItem('enable_email_notif', document.getElementById('secEmailNotif').checked ? 'on' : 'off');
    localStorage.setItem('auto_refresh', document.getElementById('secAutoRefresh').checked ? 'on' : 'off');

    if (document.getElementById('secDesktopNotif').checked && typeof Notification !== 'undefined') {
        Notification.requestPermission();
    }

    if (typeof ActivityLog !== 'undefined') ActivityLog.log('Updated Account Settings', 'Security');
    showToast('✅ Account settings saved!');
}

// ====== ACTIVITY LOG TABLE ======
let activityPage = 1;
const PER_PAGE = 15;

function renderActivityLog() {
    const items = (typeof ActivityLog !== 'undefined') ? ActivityLog.getAll() : [];
    const search = (document.getElementById('activitySearch')?.value || '').toLowerCase();
    const filter = document.getElementById('activityFilter')?.value || 'all';

    let filtered = items;
    if (search) filtered = filtered.filter(i => i.action.toLowerCase().includes(search) || i.module.toLowerCase().includes(search));
    if (filter !== 'all') filtered = filtered.filter(i => i.module === filter);

    const totalPages = Math.max(1, Math.ceil(filtered.length / PER_PAGE));
    if (activityPage > totalPages) activityPage = totalPages;
    const start = (activityPage - 1) * PER_PAGE;
    const pageItems = filtered.slice(start, start + PER_PAGE);

    const tbody = document.getElementById('activityTableBody');
    if (!pageItems.length) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4" style="color:rgba(255,255,255,0.3);">No activity found.</td></tr>';
    } else {
        tbody.innerHTML = pageItems.map(i => `
            <tr>
                <td>${UserPrefs.formatDate(i.date)} ${new Date(i.date).toLocaleTimeString([], {hour:'2-digit',minute:'2-digit'})}</td>
                <td>${i.action}</td>
                <td>${i.module}</td>
                <td>${i.ip}</td>
                <td style="max-width:120px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${i.browser}</td>
                <td><span class="status-badge ${i.status === 'Success' ? 'status-success' : 'status-error'}">${i.status}</span></td>
            </tr>
        `).join('');
    }

    // Pagination
    const pagDiv = document.getElementById('activityPagination');
    let pagHTML = '';
    pagHTML += `<button ${activityPage <= 1 ? 'disabled' : ''} onclick="activityPage--;renderActivityLog()">‹ Prev</button>`;
    for (let p = 1; p <= totalPages; p++) {
        pagHTML += `<button class="${p === activityPage ? 'active' : ''}" onclick="activityPage=${p};renderActivityLog()">${p}</button>`;
    }
    pagHTML += `<button ${activityPage >= totalPages ? 'disabled' : ''} onclick="activityPage++;renderActivityLog()">Next ›</button>`;
    pagDiv.innerHTML = pagHTML;
}

function exportCSV() {
    const items = (typeof ActivityLog !== 'undefined') ? ActivityLog.getAll() : [];
    if (!items.length) { showToast('No activity to export.', true); return; }
    let csv = 'Date,Action,Module,IP,Browser,Status\n';
    items.forEach(i => {
        csv += `"${i.date}","${i.action}","${i.module}","${i.ip}","${i.browser}","${i.status}"\n`;
    });
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url; a.download = 'activity_log.csv'; a.click();
    URL.revokeObjectURL(url);
    if (typeof ActivityLog !== 'undefined') ActivityLog.log('Exported Activity CSV', 'Profile');
    showToast('📥 CSV exported!');
}
</script>
@endpush
