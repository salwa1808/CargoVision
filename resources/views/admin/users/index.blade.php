@extends('layouts.app')

@push('styles')
<style>
    /* Dark Glassmorphism Styling */
    .stat-card-glass {
        background: rgba(255, 255, 255, 0.03) !important;
        backdrop-filter: blur(16px) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        border-radius: 16px !important;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        padding: 24px;
        position: relative;
        overflow: hidden;
    }
    .stat-card-glass:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(139, 92, 246, 0.2);
        border-color: rgba(139, 92, 246, 0.2) !important;
    }
    .stat-icon {
        position: absolute;
        right: 20px;
        bottom: 20px;
        font-size: 40px;
        opacity: 0.15;
        color: var(--accent-primary, #a78bfa);
    }
    .stat-value {
        font-size: 32px;
        font-weight: 800;
        color: #ffffff;
        margin-bottom: 4px;
    }
    .stat-title {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: rgba(255, 255, 255, 0.5);
    }

    .table-container {
        background: rgba(255, 255, 255, 0.02) !important;
        backdrop-filter: blur(16px) !important;
        border: 1px solid rgba(255, 255, 255, 0.06) !important;
        border-radius: 16px;
        padding: 24px;
    }

    .admin-table {
        width: 100%;
        color: #ffffff;
        border-collapse: separate;
        border-spacing: 0 8px;
    }
    .admin-table th {
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        padding: 12px;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 11px;
        color: rgba(255, 255, 255, 0.4);
        letter-spacing: 0.5px;
    }
    .admin-table td {
        padding: 12px;
        vertical-align: middle;
        background: rgba(255, 255, 255, 0.01);
        border-top: 1px solid rgba(255, 255, 255, 0.03);
        border-bottom: 1px solid rgba(255, 255, 255, 0.03);
    }
    .admin-table tr:hover td {
        background: rgba(139, 92, 246, 0.04);
        border-color: rgba(139, 92, 246, 0.15);
    }
    .admin-table tr td:first-child {
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
        border-left: 1px solid rgba(255, 255, 255, 0.03);
    }
    .admin-table tr td:last-child {
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
        border-right: 1px solid rgba(255, 255, 255, 0.03);
    }

    /* Modal Glass styling */
    .modal-content-glass {
        background: rgba(15, 8, 30, 0.95) !important;
        backdrop-filter: blur(20px) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        border-radius: 20px !important;
        color: #ffffff;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
    }
    .modal-header-glass {
        border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
    }
    .modal-footer-glass {
        border-top: 1px solid rgba(255, 255, 255, 0.08) !important;
    }

    /* Avatar Preview */
    .avatar-preview-box {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #a78bfa, #8b5cf6);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 32px;
        font-weight: 800;
        border: 2px solid rgba(255, 255, 255, 0.15);
        overflow: hidden;
        margin: 0 auto 12px;
    }
    .avatar-preview-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Status Badge */
    .badge-status {
        font-size: 10px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
    }
    .badge-active {
        background: rgba(16, 185, 129, 0.15);
        color: #34d399;
        border: 1px solid rgba(16, 185, 129, 0.25);
    }
    .badge-inactive {
        background: rgba(239, 68, 68, 0.15);
        color: #f87171;
        border: 1px solid rgba(239, 68, 68, 0.25);
    }

    .toast-container {
        position: fixed;
        top: 24px;
        right: 24px;
        z-index: 1060;
    }
    .custom-toast {
        background: rgba(15, 8, 30, 0.9) !important;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(139, 92, 246, 0.3) !important;
        border-radius: 12px !important;
        color: #ffffff;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">

    <!-- Toast Notifications -->
    <div class="toast-container">
        @if(session('success'))
            <div class="toast custom-toast show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-body d-flex align-items-center">
                    <span class="me-2">✅</span>
                    <span>{{ session('success') }}</span>
                    <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="toast custom-toast show border-danger" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-body d-flex align-items-center text-danger">
                    <span class="me-2">❌</span>
                    <span>{{ session('error') }}</span>
                    <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif
    </div>

    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 class="h3 text-white mb-1 fw-bold">👥 User Management</h1>
            <p class="text-muted small mb-0">Manage all registered users and their platform roles.</p>
        </div>
        <button class="btn btn-primary px-4 fw-bold" style="border-radius: 12px;" data-bs-toggle="modal" data-bs-target="#addUserModal">
            ➕ Add User
        </button>
    </div>

    <!-- Statistics Row -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card-glass">
                <div class="stat-value">{{ $totalUsers }}</div>
                <div class="stat-title">Total Users</div>
                <div class="stat-icon">👥</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card-glass">
                <div class="stat-value text-success">{{ $activeUsers }}</div>
                <div class="stat-title">Active Users</div>
                <div class="stat-icon">🟢</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card-glass">
                <div class="stat-value text-danger">{{ $inactiveUsers }}</div>
                <div class="stat-title">Inactive Users</div>
                <div class="stat-icon">🔴</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card-glass">
                <div class="stat-value text-primary">{{ $administrators }}</div>
                <div class="stat-title">Administrators</div>
                <div class="stat-icon">🛡️</div>
            </div>
        </div>
    </div>

    <!-- User Table Container -->
    <div class="table-container">
        <!-- Filter and Search controls -->
        <form action="{{ route('users.index') }}" method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search name, username, email..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select" onchange="this.form.submit()">
                    <option value="all" {{ request('role') == 'all' ? 'selected' : '' }}>All Roles</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Standard User</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                    <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-outline-light w-100 fw-bold">🔍 Filter</button>
                <a href="{{ route('users.index') }}" class="btn btn-outline-danger px-3">🔄</a>
            </div>
        </form>

        <!-- Data Table -->
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Avatar</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="avatar-preview-box" style="width: 36px; height: 36px; font-size: 14px; margin: 0;">
                                    @if($user->avatar)
                                        <img src="{{ $user->avatar }}" alt="Avatar">
                                    @else
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    @endif
                                </div>
                            </td>
                            <td><strong class="text-white">{{ $user->name }}</strong></td>
                            <td>{{ $user->username ?: '—' }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge {{ $user->role === 'admin' ? 'bg-primary' : 'bg-secondary' }}">
                                    {{ $user->role === 'admin' ? 'Administrator' : 'Standard User' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-status {{ $user->status === 'Active' ? 'badge-active' : 'badge-inactive' }}">
                                    {{ $user->status }}
                                </span>
                            </td>
                            <td>{{ $user->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-outline-light" title="View Detail">👁️</a>
                                    <button type="button" class="btn btn-sm btn-outline-primary edit-user-btn" 
                                            data-id="{{ $user->id }}" 
                                            data-name="{{ $user->name }}"
                                            data-username="{{ $user->username }}"
                                            data-email="{{ $user->email }}"
                                            data-role="{{ $user->role }}"
                                            data-status="{{ $user->status }}"
                                            data-avatar="{{ $user->avatar }}"
                                            title="Edit User">✏️</button>
                                    @if(auth()->id() !== $user->id)
                                        <button type="button" class="btn btn-sm btn-outline-danger delete-user-btn" 
                                                data-id="{{ $user->id }}" 
                                                data-name="{{ $user->name }}"
                                                title="Delete User">🗑️</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>

<!-- ============ ADD USER MODAL ============ -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modal-content-glass">
            <div class="modal-header modal-header-glass border-0">
                <h5 class="modal-title text-white fw-bold">➕ Add User</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('users.store') }}" method="POST" id="addUserForm">
                @csrf
                <div class="modal-body py-4">
                    <div class="text-center mb-4">
                        <div class="avatar-preview-box" id="addAvatarPreview">U</div>
                        <input type="file" id="addAvatarInput" accept="image/*" class="d-none">
                        <input type="hidden" name="avatar" id="addAvatarBase64">
                        <button type="button" class="btn btn-sm btn-outline-light" onclick="document.getElementById('addAvatarInput').click()">📷 Choose Avatar</button>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold text-uppercase">Full Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold text-uppercase">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label text-muted small fw-bold text-uppercase">Email Address</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold text-uppercase">Password</label>
                            <input type="password" name="password" class="form-control" required minlength="8">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold text-uppercase">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required minlength="8">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold text-uppercase">Role</label>
                            <select name="role" class="form-select" required>
                                <option value="user">Standard User</option>
                                <option value="admin">Administrator</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold text-uppercase">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-glass border-0">
                    <button type="button" class="btn btn-outline-light px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">Add User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============ EDIT USER MODAL ============ -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modal-content-glass">
            <div class="modal-header modal-header-glass border-0">
                <h5 class="modal-title text-white fw-bold">✏️ Edit User</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" id="editUserForm">
                @csrf
                @method('PUT')
                <div class="modal-body py-4">
                    <div class="text-center mb-4">
                        <div class="avatar-preview-box" id="editAvatarPreview">U</div>
                        <input type="file" id="editAvatarInput" accept="image/*" class="d-none">
                        <input type="hidden" name="avatar" id="editAvatarBase64">
                        <button type="button" class="btn btn-sm btn-outline-light" onclick="document.getElementById('editAvatarInput').click()">📷 Change Avatar</button>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold text-uppercase">Full Name</label>
                            <input type="text" name="name" id="editName" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold text-uppercase">Username</label>
                            <input type="text" name="username" id="editUsername" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label text-muted small fw-bold text-uppercase">Email Address</label>
                            <input type="email" name="email" id="editEmail" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold text-uppercase">New Password (leave blank if unchanged)</label>
                            <input type="password" name="password" class="form-control" minlength="8">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold text-uppercase">Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="form-control" minlength="8">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold text-uppercase">Role</label>
                            <select name="role" id="editRole" class="form-select" required>
                                <option value="user">Standard User</option>
                                <option value="admin">Administrator</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold text-uppercase">Status</label>
                            <select name="status" id="editStatus" class="form-select" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-glass border-0">
                    <button type="button" class="btn btn-outline-light px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">Update User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============ DELETE CONFIRMATION MODAL ============ -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-glass">
            <div class="modal-header modal-header-glass border-0">
                <h5 class="modal-title text-white fw-bold">🗑 Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4 text-center">
                <span style="font-size: 48px;">⚠️</span>
                <p class="text-white mt-3 fw-bold" id="deleteMessage">Apakah Anda yakin ingin menghapus user ini?</p>
                <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer modal-footer-glass border-0 justify-content-center">
                <form action="" method="POST" id="deleteUserForm" class="w-100 d-flex gap-2">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-outline-light w-50" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger w-50 fw-bold">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Automatically hide toast
        setTimeout(function() {
            document.querySelectorAll('.toast').forEach(function(toastEl) {
                const toast = new bootstrap.Toast(toastEl);
                toast.hide();
            });
        }, 3000);

        // Edit User Event
        document.querySelectorAll('.edit-user-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const username = this.getAttribute('data-username');
                const email = this.getAttribute('data-email');
                const role = this.getAttribute('data-role');
                const status = this.getAttribute('data-status');
                const avatar = this.getAttribute('data-avatar');

                const form = document.getElementById('editUserForm');
                form.action = `/admin/users/${id}`;

                document.getElementById('editName').value = name;
                document.getElementById('editUsername').value = username || '';
                document.getElementById('editEmail').value = email;
                document.getElementById('editRole').value = role;
                document.getElementById('editStatus').value = status;

                const preview = document.getElementById('editAvatarPreview');
                if (avatar) {
                    preview.innerHTML = `<img src="${avatar}" alt="Avatar">`;
                    document.getElementById('editAvatarBase64').value = avatar;
                } else {
                    preview.textContent = name.substring(0, 1).toUpperCase();
                    document.getElementById('editAvatarBase64').value = '';
                }

                new bootstrap.Modal(document.getElementById('editUserModal')).show();
            });
        });

        // Delete User Event
        document.querySelectorAll('.delete-user-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');

                const form = document.getElementById('deleteUserForm');
                form.action = `/admin/users/${id}`;

                document.getElementById('deleteMessage').innerHTML = `Apakah Anda yakin ingin menghapus user <strong>${name}</strong>?`;

                new bootstrap.Modal(document.getElementById('deleteUserModal')).show();
            });
        });

        // Add Avatar image handler
        setupAvatarFileReader('addAvatarInput', 'addAvatarPreview', 'addAvatarBase64');
        setupAvatarFileReader('editAvatarInput', 'editAvatarPreview', 'editAvatarBase64');
    });

    function setupAvatarFileReader(inputId, previewId, hiddenId) {
        document.getElementById(inputId).addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(evt) {
                const base64 = evt.target.result;
                document.getElementById(hiddenId).value = base64;
                document.getElementById(previewId).innerHTML = `<img src="${base64}" alt="Avatar">`;
            };
            reader.readAsDataURL(file);
        });
    }
</script>
@endpush
