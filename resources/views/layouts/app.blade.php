<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Load preferences immediately to avoid FOUC -->
    <script>
        (function() {
            // Theme Loader
            const theme = localStorage.getItem('theme') || 'dark';
            if (theme === 'dark') {
                document.documentElement.classList.add('theme-dark');
            } else if (theme === 'light') {
                document.documentElement.classList.add('theme-light');
            } else {
                const isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (isDark) {
                    document.documentElement.classList.add('theme-dark');
                } else {
                    document.documentElement.classList.add('theme-light');
                }
            }

            // Accent Color overrides
            const accent = localStorage.getItem('accent_color') || 'purple';
            let gradient = 'linear-gradient(135deg, #8b5cf6, #a78bfa)';
            let mainColor = '#8b5cf6';
            let glowColor = 'rgba(139, 92, 246, 0.45)';
            let rgb = '139, 92, 246';

            if (accent === 'blue') {
                gradient = 'linear-gradient(135deg, #3b82f6, #60a5fa)';
                mainColor = '#3b82f6';
                glowColor = 'rgba(59, 130, 246, 0.45)';
                rgb = '59, 130, 246';
            } else if (accent === 'green') {
                gradient = 'linear-gradient(135deg, #10b981, #34d399)';
                mainColor = '#10b981';
                glowColor = 'rgba(16, 185, 129, 0.45)';
                rgb = '16, 185, 129';
            } else if (accent === 'orange') {
                gradient = 'linear-gradient(135deg, #f59e0b, #fbbf24)';
                mainColor = '#f59e0b';
                glowColor = 'rgba(245, 158, 11, 0.45)';
                rgb = '245, 158, 11';
            } else if (accent === 'red') {
                gradient = 'linear-gradient(135deg, #ef4444, #f87171)';
                mainColor = '#ef4444';
                glowColor = 'rgba(239, 68, 68, 0.45)';
                rgb = '239, 68, 68';
            }

            const css = `
                :root {
                    --accent-primary: ${gradient} !important;
                    --theme-color-main: ${mainColor} !important;
                }
                .btn-glass:hover, .btn:hover, .navbar .btn:hover {
                    box-shadow: 0 0 15px ${glowColor} !important;
                }
                .nav-item.active {
                    color: ${mainColor} !important;
                    border-color: rgba(${rgb}, 0.25) !important;
                    background: rgba(${rgb}, 0.12) !important;
                }
                .text-primary, .text-main, .brand-subtitle {
                    color: ${mainColor} !important;
                }
                body.disable-animations * {
                    transition: none !important;
                    animation: none !important;
                }
            `;
            const style = document.createElement('style');
            style.id = 'accent-override-style';
            style.innerHTML = css;
            document.head.appendChild(style);

            // Collapsed Sidebar Initial state
            const sidebar = localStorage.getItem('sidebar_state') || 'expanded';
            if (sidebar === 'collapsed') {
                document.documentElement.classList.add('sidebar-collapsed-init');
            }
        })();
    </script>

    <title>Global Supply Chain Risk Platform</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css"/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css"/>

    <style>
        :root {
            --bg-body: radial-gradient(circle at 10% 20%, #1c0f38 0%, #0d061c 50%, #05020c 100%);
            --bg-card: rgba(255, 255, 255, 0.02);
            --border-color: rgba(255, 255, 255, 0.06);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --accent-primary: linear-gradient(135deg, #8b5cf6, #a78bfa);
            --shadow-card: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
            --input-bg: rgba(255, 255, 255, 0.02);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg-body) !important;
            color: var(--text-main) !important;
            transition: background-color 0.4s ease, color 0.4s ease;
            overflow-x: hidden;
            min-height: 100vh;
        }

        /* Responsive Wrapper Layout */
        #wrapper {
            display: flex;
            width: 100vw;
            min-height: 100vh;
            align-items: stretch;
        }

        /* Sidebar Styling (Glassmorphism) */
        #sidebar-wrapper {
            min-width: 280px;
            max-width: 280px;
            background: rgba(10, 5, 20, 0.6);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.06);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .brand-logo {
            flex-shrink: 0;
        }

        .brand-text {
            display: flex;
            flex-direction: column;
        }

        .brand-title {
            font-size: 17px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: 0.5px;
            line-height: 1.2;
        }

        .brand-subtitle {
            font-size: 9px;
            font-weight: 800;
            color: #a78bfa;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .sidebar-nav {
            padding: 20px 14px;
            flex-grow: 1;
            overflow-y: auto;
        }

        .nav-section-title {
            font-size: 10px;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.35);
            letter-spacing: 1.2px;
            margin-top: 24px;
            margin-bottom: 8px;
            padding-left: 12px;
            text-transform: uppercase;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 16px;
            color: rgba(255, 255, 255, 0.65);
            font-size: 13.5px;
            font-weight: 600;
            text-decoration: none;
            border-radius: 12px;
            margin-bottom: 4px;
            transition: all 0.2s ease;
            cursor: pointer;
            border: 1px solid transparent;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.04);
            color: #ffffff;
            border-color: rgba(255, 255, 255, 0.04);
        }

        .nav-item.active {
            background: rgba(139, 92, 246, 0.12);
            color: #c084fc;
            border: 1px solid rgba(139, 92, 246, 0.2);
        }

        .nav-icon {
            font-size: 16px;
            width: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .disabled-nav-item {
            opacity: 0.4;
            cursor: not-allowed;
        }

        .disabled-nav-item:hover {
            background: transparent;
            color: rgba(255, 255, 255, 0.65);
            border-color: transparent;
        }

        /* Page Content Wrapper */
        #page-content-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0; /* Prevents flex items from overflowing */
        }

        /* Topbar Styling (Glassmorphism) */
        .top-navbar {
            background: rgba(10, 5, 20, 0.3);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 30px;
            min-height: 75px;
            gap: 20px;
        }

        .btn-toggle-sidebar {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 10px;
            color: #ffffff;
            cursor: pointer;
            display: none;
            padding: 8px 12px;
            transition: all 0.2s ease;
        }

        .btn-toggle-sidebar:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .search-wrapper {
            flex: 1;
            max-width: 400px;
        }

        .search-input {
            width: 100%;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 9px 16px;
            font-size: 13.5px;
            color: #ffffff;
            transition: all 0.2s ease;
        }

        .search-input::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }

        .search-input:focus {
            outline: none;
            border-color: rgba(139, 92, 246, 0.5);
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.15);
        }

        .topbar-utilities {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        /* Glass Buttons */
        .btn-glass {
            background: rgba(255, 255, 255, 0.04) !important;
            backdrop-filter: blur(8px) !important;
            -webkit-backdrop-filter: blur(8px) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 12px !important;
            color: #ffffff !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            padding: 9px 18px !important;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
        }

        .btn-glass:hover {
            background: rgba(255, 255, 255, 0.12) !important;
            border-color: rgba(255, 255, 255, 0.25) !important;
            box-shadow: 0 0 15px rgba(139, 92, 246, 0.45) !important;
            transform: translateY(-1.5px) !important;
        }

        .notification-bell {
            position: relative;
            color: rgba(255, 255, 255, 0.6);
            cursor: pointer;
            transition: color 0.2s ease;
            padding: 4px;
        }

        .notification-bell:hover {
            color: #ffffff;
        }

        .bell-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background: #ff7a00;
            color: #ffffff;
            font-size: 9px;
            font-weight: 700;
            padding: 2px 5px;
            border-radius: 6px;
            line-height: 1;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            border-left: 1px solid rgba(255, 255, 255, 0.08);
            padding-left: 16px;
        }

        .user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, #a78bfa, #8b5cf6);
            color: #ffffff;
            font-size: 13px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-size: 13px;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.2;
        }

        .user-role {
            font-size: 10px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.4);
        }

        /* Notification Center Dropdown */
        .notification-bell { position: relative; }
        .notif-dropdown {
            position: absolute;
            top: calc(100% + 12px);
            right: -60px;
            width: 420px;
            max-height: 560px;
            background: rgba(15, 8, 30, 0.97);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.6), 0 0 30px rgba(139,92,246,0.1);
            z-index: 99999;
            display: none;
            flex-direction: column;
            overflow: hidden;
            animation: notifSlideDown 0.25s cubic-bezier(0.4,0,0.2,1);
        }
        .notif-dropdown.show { display: flex; }
        @keyframes notifSlideDown {
            from { opacity: 0; transform: translateY(-8px) scale(0.97); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .notif-header {
            padding: 16px 18px 12px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            display: flex; justify-content: space-between; align-items: center;
        }
        .notif-header h6 { margin: 0; font-size: 15px; font-weight: 800; color: #fff; }
        .notif-header-actions { display: flex; gap: 8px; }
        .notif-header-actions button {
            background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08);
            color: rgba(255,255,255,0.6); font-size: 11px; font-weight: 600;
            padding: 4px 10px; border-radius: 8px; cursor: pointer; transition: all 0.2s;
        }
        .notif-header-actions button:hover { background: rgba(139,92,246,0.15); color: #c084fc; }
        .notif-search {
            padding: 8px 18px;
            border-bottom: 1px solid rgba(255,255,255,0.04);
        }
        .notif-search input {
            width: 100%; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06);
            border-radius: 10px; padding: 8px 12px; color: #fff; font-size: 12px; outline: none;
        }
        .notif-search input::placeholder { color: rgba(255,255,255,0.3); }
        .notif-filters {
            display: flex; gap: 4px; padding: 8px 18px;
            border-bottom: 1px solid rgba(255,255,255,0.04); overflow-x: auto;
        }
        .notif-filter-btn {
            background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06);
            color: rgba(255,255,255,0.5); font-size: 11px; font-weight: 600;
            padding: 4px 12px; border-radius: 20px; cursor: pointer; white-space: nowrap; transition: all 0.2s;
        }
        .notif-filter-btn.active, .notif-filter-btn:hover {
            background: rgba(139,92,246,0.2); color: #c084fc; border-color: rgba(139,92,246,0.3);
        }
        .notif-list {
            flex: 1; overflow-y: auto; max-height: 340px;
            scrollbar-width: thin; scrollbar-color: rgba(139,92,246,0.3) transparent;
        }
        .notif-item {
            display: flex; gap: 12px; padding: 14px 18px;
            border-bottom: 1px solid rgba(255,255,255,0.03);
            cursor: pointer; transition: all 0.2s; position: relative;
        }
        .notif-item:hover { background: rgba(139,92,246,0.06); }
        .notif-item.unread { border-left: 3px solid #8b5cf6; }
        .notif-item.unread::after {
            content: ''; position: absolute; top: 18px; right: 16px;
            width: 8px; height: 8px; background: #8b5cf6; border-radius: 50%;
        }
        .notif-icon {
            width: 36px; height: 36px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px; flex-shrink: 0;
        }
        .notif-icon.risk { background: rgba(239,68,68,0.15); }
        .notif-icon.weather { background: rgba(59,130,246,0.15); }
        .notif-icon.news { background: rgba(245,158,11,0.15); }
        .notif-icon.economic { background: rgba(16,185,129,0.15); }
        .notif-content { flex: 1; min-width: 0; }
        .notif-title { font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 2px; }
        .notif-desc { font-size: 12px; color: rgba(255,255,255,0.5); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .notif-time { font-size: 10px; color: rgba(255,255,255,0.3); margin-top: 4px; }
        .notif-actions {
            display: flex; gap: 4px; position: absolute; top: 12px; right: 12px;
            opacity: 0; transition: opacity 0.2s;
        }
        .notif-item:hover .notif-actions { opacity: 1; }
        .notif-actions button {
            background: rgba(255,255,255,0.06); border: none; color: rgba(255,255,255,0.5);
            width: 24px; height: 24px; border-radius: 6px; cursor: pointer;
            display: flex; align-items: center; justify-content: center; font-size: 12px; transition: all 0.2s;
        }
        .notif-actions button:hover { background: rgba(139,92,246,0.2); color: #c084fc; }
        .notif-empty {
            padding: 40px 20px; text-align: center; color: rgba(255,255,255,0.3);
            font-size: 13px; font-weight: 600;
        }

        /* Profile Dropdown */
        .user-profile { position: relative; cursor: pointer; }
        .profile-dropdown {
            position: absolute; top: calc(100% + 12px); right: 0; width: 240px;
            background: rgba(15,8,30,0.97); backdrop-filter: blur(24px);
            border: 1px solid rgba(255,255,255,0.08); border-radius: 14px;
            box-shadow: 0 16px 48px rgba(0,0,0,0.5); z-index: 99999;
            display: none; overflow: hidden;
            animation: notifSlideDown 0.2s cubic-bezier(0.4,0,0.2,1);
        }
        .profile-dropdown.show { display: block; }
        .profile-dd-header {
            padding: 16px; border-bottom: 1px solid rgba(255,255,255,0.06);
            display: flex; gap: 12px; align-items: center;
        }
        .profile-dd-header .dd-avatar {
            width: 40px; height: 40px; border-radius: 50%;
            background: linear-gradient(135deg, #a78bfa, #8b5cf6);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 800; font-size: 15px;
            border: 2px solid rgba(255,255,255,0.15);
        }
        .profile-dd-header .dd-info { flex: 1; }
        .profile-dd-header .dd-name { font-size: 13px; font-weight: 700; color: #fff; }
        .profile-dd-header .dd-email { font-size: 11px; color: rgba(255,255,255,0.4); }
        .profile-dd-menu { padding: 6px 0; }
        .profile-dd-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 16px; color: rgba(255,255,255,0.7);
            font-size: 13px; font-weight: 600; text-decoration: none;
            transition: all 0.2s; cursor: pointer; border: none; background: none; width: 100%;
        }
        .profile-dd-item:hover { background: rgba(139,92,246,0.1); color: #c084fc; }
        .profile-dd-item.danger { color: rgba(239,68,68,0.8); }
        .profile-dd-item.danger:hover { background: rgba(239,68,68,0.1); color: #f87171; }
        .profile-dd-divider { height: 1px; background: rgba(255,255,255,0.06); margin: 4px 0; }

        @media (max-width: 576px) {
            .notif-dropdown { width: calc(100vw - 20px); right: -100px; }
            .profile-dropdown { width: 200px; }
        }

        /* Glassmorphism Cards */
        .card {
            background: rgba(255, 255, 255, 0.02) !important;
            backdrop-filter: blur(16px) !important;
            -webkit-backdrop-filter: blur(16px) !important;
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
            border-radius: 20px !important;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3) !important;
            color: #ffffff !important;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1), border-color 0.3s ease !important;
        }

        .card:hover {
            transform: translateY(-4px) !important;
            border-color: rgba(139, 92, 246, 0.2) !important;
            box-shadow: 0 12px 40px rgba(139, 92, 246, 0.15) !important;
        }

        .card-header {
            background: transparent !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06) !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            font-size: 16px !important;
            padding: 20px 24px !important;
        }

        .card-body {
            padding: 24px !important;
        }

        /* Input overrides */
        .form-control, .form-select {
            background-color: rgba(255, 255, 255, 0.03) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            color: #ffffff !important;
            border-radius: 12px !important;
            padding: 10px 16px !important;
        }

        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.2) !important;
            border-color: rgba(139, 92, 246, 0.4) !important;
        }

        /* Buttons overrides across the whole site to exhibit glass effect */
        .btn, .navbar .btn {
            border-radius: 12px !important;
            font-weight: 600 !important;
            font-size: 13.5px !important;
            padding: 10px 18px !important;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.04) !important;
            backdrop-filter: blur(8px) !important;
            -webkit-backdrop-filter: blur(8px) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            color: #ffffff !important;
        }

        .btn:hover, .navbar .btn:hover {
            background: rgba(255, 255, 255, 0.12) !important;
            border-color: rgba(255, 255, 255, 0.25) !important;
            box-shadow: 0 0 15px rgba(139, 92, 246, 0.45) !important;
            transform: translateY(-2px) !important;
            color: #ffffff !important;
        }

        /* Specific glows based on button overrides */
        .btn-primary:hover {
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.5) !important;
        }
        .btn-success:hover {
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.5) !important;
        }
        .btn-warning:hover {
            box-shadow: 0 0 20px rgba(245, 158, 11, 0.5) !important;
        }
        .btn-info:hover {
            box-shadow: 0 0 20px rgba(6, 182, 212, 0.5) !important;
        }

        /* Tables styling */
        .table {
            background: transparent !important;
            color: rgba(255, 255, 255, 0.9) !important;
        }

        .table > :not(caption) > * > * {
            background-color: transparent !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06) !important;
            color: rgba(255, 255, 255, 0.9) !important;
            padding: 14px 16px !important;
        }

        .table-hover tbody tr:hover td {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.02) !important;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.15);
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.06);
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        /* Main Content wrapper */
        .main-content {
            padding: 35px 30px;
            flex: 1;
            overflow-y: auto;
        }

        footer {
            margin-top: auto;
            padding: 30px;
            text-align: center;
            color: var(--text-muted);
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            font-size: 13.5px;
            font-weight: 500;
            background: rgba(10, 5, 20, 0.15);
        }

        /* Modern Blur Loader */
        #loader {
            position: fixed;
            width: 100%;
            height: 100%;
            background: #0d061c;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 999999;
            transition: opacity 0.4s ease, visibility 0.4s ease;
            gap: 15px;
        }

        .loader-spinner {
            width: 48px;
            height: 48px;
            border: 4px solid rgba(255, 255, 255, 0.1);
            border-bottom-color: #8b5cf6;
            border-radius: 50%;
            display: inline-block;
            box-sizing: border-box;
            animation: rotation 1s linear infinite;
        }

        @keyframes rotation {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .loader-text {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.1em;
            color: #a78bfa;
            animation: pulse-glow 1.5s ease-in-out infinite;
        }

        @keyframes pulse-glow {
            0%, 100% { opacity: 0.6; }
            50% { opacity: 1; }
        }

        .pulse-circle {
            display: inline-block;
            width: 8px;
            height: 8px;
            background-color: #10b981;
            border-radius: 50%;
            margin-right: 8px;
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: pulsing 1.2s infinite;
            vertical-align: middle;
        }

        @keyframes pulsing {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            }
            70% {
                transform: scale(1);
                box-shadow: 0 0 0 6px rgba(16, 185, 129, 0);
            }
            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
            }
        }

        /* Badges */
        .badge {
            font-weight: 700 !important;
            padding: 6px 12px !important;
            border-radius: 8px !important;
            font-size: 11px !important;
            border: 1px solid transparent !important;
        }

        .bg-danger {
            background-color: rgba(239, 68, 68, 0.15) !important;
            color: #f87171 !important;
            border-color: rgba(239, 68, 68, 0.25) !important;
        }

        .bg-warning {
            background-color: rgba(245, 158, 11, 0.15) !important;
            color: #fbbf24 !important;
            border-color: rgba(245, 158, 11, 0.25) !important;
        }

        .bg-success {
            background-color: rgba(16, 185, 129, 0.15) !important;
            color: #34d399 !important;
            border-color: rgba(16, 185, 129, 0.25) !important;
        }

        .bg-secondary {
            background-color: rgba(100, 116, 139, 0.15) !important;
            color: #94a3b8 !important;
            border-color: rgba(100, 116, 139, 0.25) !important;
        }

        .bg-primary {
            background-color: rgba(139, 92, 246, 0.15) !important;
            color: #c084fc !important;
            border-color: rgba(139, 92, 246, 0.25) !important;
        }

        /* Leaflet Map overrides for glass theme */
        .leaflet-container {
            font-family: inherit !important;
            background: #0d061c !important;
        }

        .leaflet-bar {
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3) !important;
            border-radius: 10px !important;
            overflow: hidden;
        }

        .leaflet-bar a {
            background-color: rgba(20, 10, 35, 0.8) !important;
            backdrop-filter: blur(10px);
            color: #ffffff !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
            transition: all 0.2s ease;
        }

        .leaflet-bar a:hover {
            background-color: rgba(139, 92, 246, 0.2) !important;
            color: #c084fc !important;
        }

        /* Responsive Layout Behavior */
        @media (max-width: 991px) {
            #sidebar-wrapper {
                margin-left: -280px;
                position: absolute;
                top: 0;
                bottom: 0;
                height: 100vh;
            }
            #wrapper.toggled #sidebar-wrapper {
                margin-left: 0;
            }
            .btn-toggle-sidebar {
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .top-navbar {
                padding: 16px 20px;
            }
        }
        
        /* Collapsed Sidebar styles for desktop viewports */
        @media (min-width: 992px) {
            #wrapper.sidebar-collapsed #sidebar-wrapper {
                min-width: 80px;
                max-width: 80px;
                overflow: hidden;
            }
            #wrapper.sidebar-collapsed #sidebar-wrapper .brand-text,
            #wrapper.sidebar-collapsed #sidebar-wrapper .brand-subtitle,
            #wrapper.sidebar-collapsed #sidebar-wrapper .nav-section-title,
            #wrapper.sidebar-collapsed #sidebar-wrapper .nav-item span:not(.nav-icon) {
                display: none !important;
            }
            #wrapper.sidebar-collapsed #sidebar-wrapper .nav-item {
                justify-content: center;
                padding: 12px;
                margin-left: 8px;
            }
        }

        /* ==========================================================================
           ACCESSIBILITY & CONTRAST IMPROVEMENTS (WCAG AA CONFORMANT)
           ========================================================================== */

        /* 1. Global Backgrounds & Cards Override */
        body.bg-dark, body {
            background-color: #120F24 !important;
            background: #120F24 !important;
            color: #C7CBD4 !important;
        }
        .card, .table-container, .editor-card, .profile-details-card {
            background-color: #1B1630 !important;
            background: #1B1630 !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.4) !important;
        }

        /* 2. Text Elements & Headers Contrast */
        h1, h2, h3, h4, h5, h6, 
        .card-header, .card-title, .modal-title, .text-white, 
        strong, th, thead th, .brand-title, .user-name {
            color: #FFFFFF !important;
        }
        
        p, .text-secondary, td, li, span, label, .form-label, .user-role {
            color: #D1D5DB !important;
        }

        .text-muted, .text-muted-custom, small, .timestamp, .time, .notif-time, .timeline-time {
            color: #9CA3AF !important;
        }

        /* 3. Input, Form, and Search contrast */
        .form-control, .form-select, input, select, textarea, .search-input {
            background-color: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            color: #FFFFFF !important;
        }
        .form-control:focus, .form-select:focus, input:focus, select:focus, textarea:focus, .search-input:focus {
            border-color: #8b5cf6 !important;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.25) !important;
            color: #FFFFFF !important;
        }

        /* Input Placeholders */
        ::placeholder, 
        .form-control::placeholder, 
        .search-input::placeholder, 
        .notif-search input::placeholder {
            color: #A5B4C3 !important;
            opacity: 1 !important;
        }

        /* 4. Sidebar Styles */
        .nav-section-title {
            color: #9CA3AF !important;
            font-weight: 700 !important;
            opacity: 1 !important;
        }
        .nav-item {
            color: #D1D5DB !important;
        }
        .nav-item:hover {
            color: #FFFFFF !important;
            background: rgba(255, 255, 255, 0.06) !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
        }
        .nav-item.active {
            color: #FFFFFF !important;
            background: rgba(139, 92, 246, 0.25) !important;
            border-color: rgba(139, 92, 246, 0.4) !important;
        }

        /* 5. Tables */
        .table thead th, .admin-table th, table th {
            color: #FFFFFF !important;
            background-color: rgba(255, 255, 255, 0.02) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12) !important;
        }
        .table tbody td, .admin-table td, table td {
            color: #D1D5DB !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.04) !important;
        }
        .table tbody tr:hover td, .admin-table tr:hover td, table tr:hover td {
            background-color: rgba(255, 255, 255, 0.04) !important;
        }

        /* 6. Form validation states */
        .invalid-feedback, .text-danger, .status-error, .badge-inactive, .bg-danger {
            color: #ff8787 !important;
        }
        .valid-feedback, .text-success, .status-success, .badge-active, .bg-success {
            color: #4ef0b3 !important;
        }

        /* 7. Buttons */
        .btn-primary, .btn-glass {
            color: #FFFFFF !important;
        }
        .btn-outline-light {
            border-color: rgba(255, 255, 255, 0.3) !important;
            color: #FFFFFF !important;
        }
        .btn-outline-light:hover {
            background-color: rgba(255, 255, 255, 0.1) !important;
            color: #FFFFFF !important;
        }

        /* 8. Badges */
        .badge {
            color: #FFFFFF !important;
        }

        /* 9. Audit overrides for standard bootstrap classes */
        .text-dark { color: #D1D5DB !important; }
        .text-secondary { color: #D1D5DB !important; }
        .text-muted { color: #9CA3AF !important; }
        .text-black { color: #FFFFFF !important; }
        .text-body { color: #C7CBD4 !important; }

        /* 10. Modal and Toast controls */
        .modal-body { color: #D1D5DB !important; }
        .custom-toast {
            background: #1B1630 !important;
            border: 1px solid rgba(139, 92, 246, 0.4) !important;
            color: #FFFFFF !important;
        }

        /* 11. Leaflet Map Popup Contrast Overrides */
        .leaflet-popup-content-wrapper {
            background: rgba(15, 8, 30, 0.96) !important;
            background-color: rgba(15, 8, 30, 0.96) !important;
            backdrop-filter: blur(12px) !important;
            -webkit-backdrop-filter: blur(12px) !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            color: #FFFFFF !important;
            border-radius: 16px !important;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.6) !important;
        }
        .leaflet-popup-tip {
            background: rgba(15, 8, 30, 0.96) !important;
            background-color: rgba(15, 8, 30, 0.96) !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
        }
        .leaflet-popup-content {
            color: #D1D5DB !important;
            font-family: inherit !important;
        }
        .leaflet-popup-content h1, 
        .leaflet-popup-content h2, 
        .leaflet-popup-content h3, 
        .leaflet-popup-content h4, 
        .leaflet-popup-content h5, 
        .leaflet-popup-content h6, 
        .leaflet-popup-content strong {
            color: #FFFFFF !important;
        }
        .leaflet-popup-close-button {
            color: rgba(255, 255, 255, 0.6) !important;
        }
        .leaflet-popup-close-button:hover {
            color: #FFFFFF !important;
        }

        /* 12. Global Dark Select / Dropdown Overrides */
        /* Native fallback — ensures the select box itself is dark */
        select,
        .form-select {
            background-color: rgba(18, 10, 35, 0.95) !important;
            color: #ffffff !important;
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            border-radius: 10px !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23a78bfa' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
        }
        select:focus,
        .form-select:focus {
            border-color: rgba(139, 92, 246, 0.6) !important;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.18) !important;
            outline: none !important;
            background-color: rgba(22, 12, 42, 0.98) !important;
        }
        select option,
        .form-select option {
            background-color: #130b28 !important;
            color: #ffffff !important;
        }
        select option:hover,
        .form-select option:hover,
        select option:checked,
        .form-select option:checked {
            background-color: rgba(139, 92, 246, 0.35) !important;
            color: #c084fc !important;
        }

        /* Tom Select global dark theme overrides (for pages that use Tom Select) */
        .ts-wrapper .ts-control {
            background: rgba(18, 10, 35, 0.95) !important;
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            border-radius: 10px !important;
            color: #ffffff !important;
            padding: 8px 12px !important;
            box-shadow: none !important;
            cursor: pointer;
        }
        .ts-wrapper.focus .ts-control,
        .ts-wrapper.input-active .ts-control {
            border-color: rgba(139, 92, 246, 0.6) !important;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.18) !important;
        }
        .ts-wrapper .ts-control input {
            color: #ffffff !important;
            background: transparent !important;
        }
        .ts-wrapper .ts-control input::placeholder {
            color: rgba(255, 255, 255, 0.38) !important;
        }
        .ts-dropdown {
            background: rgba(14, 7, 28, 0.99) !important;
            border: 1px solid rgba(139, 92, 246, 0.25) !important;
            border-radius: 12px !important;
            box-shadow: 0 16px 48px rgba(0, 0, 0, 0.75) !important;
            backdrop-filter: blur(20px) !important;
            -webkit-backdrop-filter: blur(20px) !important;
            overflow: hidden;
            z-index: 9999 !important;
        }
        .ts-dropdown .ts-dropdown-content {
            max-height: 260px !important;
            overflow-y: auto !important;
            scrollbar-width: thin;
            scrollbar-color: rgba(139, 92, 246, 0.4) transparent;
        }
        .ts-dropdown .ts-dropdown-content::-webkit-scrollbar { width: 5px; }
        .ts-dropdown .ts-dropdown-content::-webkit-scrollbar-track { background: transparent; }
        .ts-dropdown .ts-dropdown-content::-webkit-scrollbar-thumb {
            background: rgba(139, 92, 246, 0.4);
            border-radius: 3px;
        }
        .ts-dropdown .option {
            color: #D1D5DB !important;
            padding: 9px 14px !important;
            font-size: 13px !important;
            transition: background 0.15s ease;
        }
        .ts-dropdown .option:hover,
        .ts-dropdown .option.active {
            background: rgba(139, 92, 246, 0.22) !important;
            color: #ffffff !important;
        }
        .ts-dropdown .option.selected {
            background: rgba(139, 92, 246, 0.32) !important;
            color: #c084fc !important;
            font-weight: 600;
        }
        .ts-dropdown input.ts-search,
        .ts-dropdown .ts-search-input {
            background: rgba(25, 12, 48, 0.95) !important;
            color: #ffffff !important;
            border: none !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
            padding: 9px 14px !important;
            font-size: 13px !important;
            width: 100%;
            outline: none;
        }
        .ts-dropdown input.ts-search::placeholder { color: rgba(255,255,255,0.35) !important; }
        .ts-wrapper .item { color: #ffffff !important; }
        .ts-wrapper .placeholder { color: rgba(255, 255, 255, 0.38) !important; }
        .ts-wrapper.form-select { padding: 0 !important; background: transparent !important; border: none !important; }
    </style>

    <!-- Tom Select: Custom dark dropdown (globally available) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css">

    @stack('styles')

</head>

<body>

<div id="loader">
    <div class="loader-spinner"></div>
    <div class="loader-text">CONNECTING SUPPLY CHAIN CHANNELS...</div>
</div>

<div id="wrapper">

    <!-- Sidebar Wrapper -->
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <svg class="brand-logo" width="24" height="28" viewBox="0 0 24 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 0L2 4V13C2 19.3 6.3 25.1 12 28C17.7 25.1 22 19.3 22 13V4L12 0Z" fill="#ff7a00"/>
                <path d="M12 2.5L3.8 5.8V13.2C3.8 18.5 7.4 23.3 12 25.8V2.5Z" fill="#ff9900"/>
            </svg>
            <div class="brand-text">
                <span class="brand-title" style="font-size: 15px;">Global Supply Chain</span>
                <span class="brand-subtitle">Risk</span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-title">Main Control</div>
            
            <a href="{{ url('/') }}" class="nav-item {{ Request::is('/') ? 'active' : '' }}">
                <span class="nav-icon">🎛️</span> Dashboard
            </a>
            
            <a href="{{ url('/countries') }}" class="nav-item {{ Request::is('countries') ? 'active' : '' }}">
                <span class="nav-icon">🗺️</span> Countries
            </a>
            
            <a href="{{ route('weather') }}" class="nav-item {{ Request::is('weather') ? 'active' : '' }}">
                <span class="nav-icon">⛅</span> Weather
            </a>
            
            <a href="{{ url('/economy') }}" class="nav-item {{ Request::is('economy') ? 'active' : '' }}">
                <span class="nav-icon">📈</span> Economy
            </a>
            
            <a href="{{ url('/currency') }}" class="nav-item {{ Request::is('currency') ? 'active' : '' }}">
                <span class="nav-icon">💵</span> Currency
            </a>
            
            <a href="{{ route('ports') }}" class="nav-item {{ Request::is('ports') ? 'active' : '' }}">
                <span class="nav-icon">🚢</span> Ports
            </a>
            
            <a href="{{ route('news') }}" class="nav-item {{ Request::is('news') ? 'active' : '' }}">
                <span class="nav-icon">📰</span> News & Events
            </a>

            @if(auth()->check() && auth()->user()->role === 'admin')
                <div class="nav-section-title">ADMIN PANEL (User Administrator Only)</div>
                <a href="{{ route('users.index') }}" class="nav-item {{ Request::is('admin/users*') ? 'active' : '' }}">
                    <span class="nav-icon">👥</span> User Management
                </a>
                <a href="{{ route('articles.index') }}" class="nav-item {{ Request::is('admin/articles*') ? 'active' : '' }}">
                    <span class="nav-icon">📰</span> Article Management
                </a>
            @endif

            <div class="nav-section-title">Analytics</div>
            
            <a href="{{ url('/analytics') }}" class="nav-item {{ Request::is('analytics') ? 'active' : '' }}">
                <span class="nav-icon">📊</span> Risk Scores
            </a>
            
            <a href="{{ url('/watchlist') }}" class="nav-item {{ Request::is('watchlist') ? 'active' : '' }}">
                <span class="nav-icon">📋</span> Watchlist
            </a>
            
            <a href="{{ url('/compare') }}" class="nav-item {{ Request::is('compare') ? 'active' : '' }}">
                <span class="nav-icon">🔀</span> Compare
            </a>
            
            <a href="{{ route('map') }}" class="nav-item {{ Request::is('map') ? 'active' : '' }}">
                <span class="nav-icon">🌍</span> Global Map
            </a>

            <div class="nav-section-title">Account</div>
            
            <a href="#" class="nav-item" id="darkModeBtn">
                <span class="nav-icon">🌙</span> Dark Mode
            </a>
            
            <a href="{{ route('settings') }}" class="nav-item {{ Request::is('settings') ? 'active' : '' }}">
                <span class="nav-icon">⚙️</span> Settings
            </a>
            
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
            <a href="#" class="nav-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <span class="nav-icon">🚪</span> Logout
            </a>
        </nav>
    </aside>

    <!-- Page Content Wrapper -->
    <div id="page-content-wrapper">
        
        <!-- Top Navbar -->
        <header class="top-navbar">
            
            <!-- Menu Toggle Button (Mobile) -->
            <button class="btn-toggle-sidebar" id="menu-toggle">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
                    <path d="M3,6H21V8H3V6M3,11H21V13H3V11M3,16H21V18H3V16Z"/>
                </svg>
            </button>
            
            <!-- Search bar -->
            <div class="search-wrapper">
                <input type="text" class="search-input" placeholder="Search countries, ports...">
            </div>

            <!-- Utilities -->
            <div class="topbar-utilities">
                
                @if(auth()->check() && auth()->user()->role === 'admin')
                    <!-- Sync APIs Button (Glass effect) -->
                    <button class="btn-glass" onclick="location.reload();">
                        🔄 Sync APIs
                    </button>
                @endif

                <!-- Notification Bell with Dropdown -->
                <div class="notification-bell" id="notifBellTrigger">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                        <path d="M12,2A2,2 0 0,0 10,4A2,2 0 0,0 10,4.29C7.12,5.14 5,7.82 5,11V17L3,19V20H21V19L19,17V11C19,7.82 16.88,5.14 14,4.29A2,2 0 0,0 14,4A2,2 0 0,0 12,2M12,22A2,2 0 0,0 14,20H10A2,2 0 0,0 12,22Z"/>
                    </svg>
                    <span class="bell-badge" id="notifBadge">0</span>

                    <!-- Notification Dropdown Panel -->
                    <div class="notif-dropdown" id="notifDropdown">
                        <div class="notif-header">
                            <h6>🔔 Notifications</h6>
                            <div class="notif-header-actions">
                                <button onclick="NotifCenter.markAllRead(event)">✓ Read All</button>
                                <button onclick="NotifCenter.clearAll(event)">🗑 Clear</button>
                            </div>
                        </div>
                        <div class="notif-search">
                            <input type="text" id="notifSearchInput" placeholder="Search notifications..." oninput="NotifCenter.render()">
                        </div>
                        <div class="notif-filters">
                            <button class="notif-filter-btn active" data-filter="all" onclick="NotifCenter.setFilter('all', this)">All</button>
                            <button class="notif-filter-btn" data-filter="unread" onclick="NotifCenter.setFilter('unread', this)">Unread</button>
                            <button class="notif-filter-btn" data-filter="risk" onclick="NotifCenter.setFilter('risk', this)">Risk</button>
                            <button class="notif-filter-btn" data-filter="weather" onclick="NotifCenter.setFilter('weather', this)">Weather</button>
                            <button class="notif-filter-btn" data-filter="news" onclick="NotifCenter.setFilter('news', this)">News</button>
                            <button class="notif-filter-btn" data-filter="economic" onclick="NotifCenter.setFilter('economic', this)">Economic</button>
                        </div>
                        <div class="notif-list" id="notifList"></div>
                    </div>
                </div>

                <!-- User Profile with Dropdown -->
                @if(auth()->check())
                    <div class="user-profile" id="profileTrigger">
                        <div class="user-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="user-info d-none d-sm-flex">
                            <span class="user-name">{{ auth()->user()->name }}</span>
                            <span class="user-role">{{ auth()->user()->role === 'admin' ? 'Administrator' : 'Standard User' }}</span>
                        </div>

                        <!-- Profile Dropdown Menu -->
                        <div class="profile-dropdown" id="profileDropdown">
                            <div class="profile-dd-header">
                                <div class="dd-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                                <div class="dd-info">
                                    <div class="dd-name">{{ auth()->user()->name }}</div>
                                    <div class="dd-email">{{ auth()->user()->email }}</div>
                                </div>
                            </div>
                            <div class="profile-dd-menu">
                                <a href="{{ route('profile') }}" class="profile-dd-item">👤 View Profile</a>
                                <a href="{{ route('profile') }}#edit" class="profile-dd-item">✏️ Edit Profile</a>
                                <a href="{{ route('profile') }}#security" class="profile-dd-item">🔒 Security</a>
                                <a href="{{ route('profile') }}#activity" class="profile-dd-item">📋 Activity Log</a>
                                <div class="profile-dd-divider"></div>
                                <button class="profile-dd-item danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">🚪 Logout</button>
                            </div>
                        </div>
                    </div>
                @endif


            </div>

        </header>

        <!-- Main Content Body -->
        <main class="main-content">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer>
            SupplyGuard Risk Intelligence Platform © {{ date('Y') }}
        </footer>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>

<script>
    // Hide Loader
    window.onload = function() {
        document.getElementById("loader").style.display = "none";
    }

    
    // 1. Theme and Preferences initialization logic
    const body = document.body;
    const btn = document.getElementById("darkModeBtn");
    const theme = localStorage.getItem('theme') || 'dark';
    
    if (theme === 'dark') {
        body.classList.add("bg-dark", "text-white");
        if (btn) btn.innerHTML = '<span class="nav-icon">☀</span> Light Mode';
    } else if (theme === 'light') {
        body.classList.remove("bg-dark", "text-white");
        if (btn) btn.innerHTML = '<span class="nav-icon">🌙</span> Dark Mode';
    } else {
        // Auto
        const isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        if (isDark) {
            body.classList.add("bg-dark", "text-white");
        } else {
            body.classList.remove("bg-dark", "text-white");
        }
        if (btn) btn.innerHTML = '<span class="nav-icon">🌗</span> Auto Theme';
    }

    if (btn) {
        btn.addEventListener("click", (e) => {
            e.preventDefault();
            const current = localStorage.getItem('theme') || 'dark';
            let next = 'light';
            if (current === 'light') {
                next = 'dark';
            }
            localStorage.setItem('theme', next);
            window.location.reload();
        });
    }

    // 2. Animations preference
    const enableAnimations = localStorage.getItem('enable_animations') !== 'off';
    if (!enableAnimations) {
        body.classList.add('disable-animations');
    }

    // 3. Collapsed sidebar initial setup
    const wrapper = document.getElementById("wrapper");
    if (document.documentElement.classList.contains('sidebar-collapsed-init')) {
        if (wrapper) wrapper.classList.add('sidebar-collapsed');
        document.documentElement.classList.remove('sidebar-collapsed-init');
    }

    // 4. Widget navigations and dashboard components visibility
    function applyWidgetVisibility() {
        const showWeather = localStorage.getItem('show_weather_widget') !== 'off';
        const showNews = localStorage.getItem('show_news_widget') !== 'off';
        const showAnalytics = localStorage.getItem('show_analytics_widget') !== 'off';
        const showPorts = localStorage.getItem('show_ports_widget') !== 'off';

        const weatherItem = document.querySelector('a[href*="/weather"]');
        const newsItem = document.querySelector('a[href*="/news"]');
        const analyticsItem = document.querySelector('a[href*="/analytics"]');
        const portsItem = document.querySelector('a[href*="/ports"]');

        if (weatherItem) weatherItem.style.display = showWeather ? '' : 'none';
        if (newsItem) newsItem.style.display = showNews ? '' : 'none';
        if (analyticsItem) analyticsItem.style.display = showAnalytics ? '' : 'none';
        if (portsItem) portsItem.style.display = showPorts ? '' : 'none';
        
        // Dashboard cards
        if (window.location.pathname === '/' || window.location.pathname === '/index') {
            const chartCard = document.querySelector('canvas#riskChart')?.closest('.card');
            const mapCard = document.querySelector('div#map')?.closest('.card');
            
            if (chartCard) chartCard.style.display = showAnalytics ? '' : 'none';
            if (mapCard) mapCard.style.display = showPorts ? '' : 'none';
        }
    }
    applyWidgetVisibility();

    // 5. User Preferences formatting library
    window.UserPrefs = {
        getLang: () => localStorage.getItem('pref_lang') || 'en',
        getDateFormat: () => localStorage.getItem('pref_date_format') || 'DD/MM/YYYY',
        getNumberFormat: () => localStorage.getItem('pref_number_format') || '1,000.00',
        getTempUnit: () => localStorage.getItem('pref_temp_unit') || 'Celsius',
        getWindUnit: () => localStorage.getItem('pref_wind_unit') || 'km/h',
        getDistUnit: () => localStorage.getItem('pref_dist_unit') || 'km',
        
        formatNumber: (num) => {
            if (num === null || num === undefined || isNaN(num)) return '-';
            const format = UserPrefs.getNumberFormat();
            if (format === '1.000,00') {
                return Number(num).toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            } else {
                return Number(num).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            }
        },
        formatTemp: (celsius) => {
            if (celsius === null || celsius === undefined || isNaN(celsius)) return '-';
            const unit = UserPrefs.getTempUnit();
            if (unit === 'Fahrenheit') {
                return Math.round((celsius * 9/5) + 32) + ' °F';
            }
            return Math.round(celsius) + ' °C';
        },
        formatWind: (kmh) => {
            if (kmh === null || kmh === undefined || isNaN(kmh)) return '-';
            const unit = UserPrefs.getWindUnit();
            if (unit === 'mph') {
                return Math.round(kmh * 0.621371) + ' mph';
            }
            return Math.round(kmh) + ' km/h';
        },
        formatDist: (km) => {
            if (km === null || km === undefined || isNaN(km)) return '-';
            const unit = UserPrefs.getDistUnit();
            if (unit === 'mile') {
                return Math.round(km * 0.621371) + ' mi';
            }
            return Math.round(km) + ' km';
        },
        formatDate: (dateStr) => {
            if (!dateStr) return '-';
            const date = new Date(dateStr);
            if (isNaN(date.getTime())) return dateStr;
            const dd = String(date.getDate()).padStart(2, '0');
            const mm = String(date.getMonth() + 1).padStart(2, '0');
            const yyyy = date.getFullYear();
            
            const format = UserPrefs.getDateFormat();
            if (format === 'MM/DD/YYYY') return `${mm}/${dd}/${yyyy}`;
            if (format === 'YYYY/MM/DD') return `${yyyy}/${mm}/${dd}`;
            return `${dd}/${mm}/${yyyy}`;
        }
    };

    // 6. Dashboard Auto Refresh
    const autoRefresh = localStorage.getItem('auto_refresh') !== 'off';
    const refreshInterval = parseInt(localStorage.getItem('refresh_interval')) || 15000;
    if (autoRefresh && (window.location.pathname === '/' || window.location.pathname === '/index')) {
        setInterval(() => {
            if (typeof loadDashboard === 'function') loadDashboard();
            if (typeof loadChart === 'function') loadChart();
            if (typeof loadMap === 'function') loadMap();
        }, refreshInterval);
    }
</script>

<!-- High Risk Alerts Toast -->
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="riskToast" class="toast text-bg-danger border-0">
        <div class="d-flex">
            <div class="toast-body">
                <strong>⚠ High Risk Alert</strong>
                <div id="toastMessage"></div>
            </div>
            <button class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<script>
    let lastHighRisk = null;

    function checkHighRisk() {
        // Notification settings
        const enableToast = localStorage.getItem('enable_toast') !== 'off';
        const enableDesktop = localStorage.getItem('enable_desktop') === 'on';
        const triggerHighRisk = localStorage.getItem('alert_high_risk') !== 'off';
        const triggerWeather = localStorage.getItem('alert_weather') !== 'off';
        const triggerNews = localStorage.getItem('alert_news') !== 'off';

        if (!enableToast && !enableDesktop) return;

        fetch("{{ url('/api/high-risk') }}")
            .then(r => r.json())
            .then(data => {
                if (lastHighRisk === null) {
                    lastHighRisk = data.map(x => x.id);
                    return;
                }
                data.forEach(item => {
                    if (!lastHighRisk.includes(item.id)) {
                        lastHighRisk.push(item.id);
                        
                        let showAlert = false;
                        let alertTitle = "Risk Alert";
                        let alertBody = "";

                        // Determine if we should show alert based on preferences
                        if (item.risk_level === 'High' && triggerHighRisk) {
                            showAlert = true;
                            alertTitle = "⚠️ High Risk Alert";
                            alertBody = `${item.country.name} has entered HIGH risk level (Score: ${item.total_score})`;
                        } else if (item.weather_score >= 60 && triggerWeather) {
                            showAlert = true;
                            alertTitle = "⛈️ Weather Alert";
                            alertBody = `Extreme weather risk detected in ${item.country.name} (Weather Score: ${item.weather_score})`;
                        } else if (item.news_score >= 60 && triggerNews) {
                            showAlert = true;
                            alertTitle = "📰 News Alert";
                            alertBody = `Critical security/economic events reported in ${item.country.name} (News Score: ${item.news_score})`;
                        }

                        if (showAlert) {
                            if (enableToast) {
                                const toastEl = document.getElementById("riskToast");
                                if (toastEl) {
                                    document.getElementById("toastMessage").innerHTML =
                                        `<strong>${alertTitle}</strong><br>${alertBody}`;
                                    new bootstrap.Toast(toastEl).show();
                                }
                            }
                            if (enableDesktop && typeof Notification !== 'undefined' && Notification.permission === 'granted') {
                                new Notification(alertTitle, {
                                    body: alertBody,
                                    icon: item.country.flag_png || '/favicon.ico'
                                });
                            }
                        }
                    }
                });
            });
    }

    checkHighRisk();
    setInterval(checkHighRisk, 30000);

    function toggleWatchlistGlobal(countryId, callback) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const url = "{{ url('/watchlist/toggle') }}/" + countryId;
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                if (typeof callback === 'function') {
                    callback(res.watchlisted, res.message);
                }
            } else {
                alert(res.message || 'Error updating watchlist');
            }
        })
        .catch(err => {
            console.error(err);
            alert('An error occurred while updating watchlist.');
        });
    }

    // =============================================
    // NOTIFICATION CENTER ENGINE
    // =============================================
    window.NotifCenter = {
        KEY: 'notif_center_items',
        filter: 'all',

        getAll() {
            try { return JSON.parse(localStorage.getItem(this.KEY)) || []; }
            catch { return []; }
        },
        save(items) { localStorage.setItem(this.KEY, JSON.stringify(items)); },

        add(type, title, desc) {
            const items = this.getAll();
            const id = Date.now() + '_' + Math.random().toString(36).substr(2,5);
            items.unshift({ id, type, title, desc, time: new Date().toISOString(), read: false });
            if (items.length > 100) items.length = 100;
            this.save(items);
            this.render();
        },

        markRead(id, e) {
            if (e) e.stopPropagation();
            const items = this.getAll();
            const item = items.find(i => i.id === id);
            if (item) item.read = true;
            this.save(items);
            this.render();
        },

        deleteItem(id, e) {
            if (e) e.stopPropagation();
            let items = this.getAll().filter(i => i.id !== id);
            this.save(items);
            this.render();
        },

        markAllRead(e) {
            if (e) e.stopPropagation();
            const items = this.getAll();
            items.forEach(i => i.read = true);
            this.save(items);
            this.render();
        },

        clearAll(e) {
            if (e) e.stopPropagation();
            this.save([]);
            this.render();
        },

        setFilter(f, btn) {
            this.filter = f;
            document.querySelectorAll('.notif-filter-btn').forEach(b => b.classList.remove('active'));
            if (btn) btn.classList.add('active');
            this.render();
        },

        timeAgo(iso) {
            const diff = (Date.now() - new Date(iso).getTime()) / 1000;
            if (diff < 60) return 'Just now';
            if (diff < 3600) return Math.floor(diff/60) + 'm ago';
            if (diff < 86400) return Math.floor(diff/3600) + 'h ago';
            return Math.floor(diff/86400) + 'd ago';
        },

        getIcon(type) {
            const map = { risk: '⚠️', weather: '⛈️', news: '📰', economic: '📊' };
            return map[type] || '🔔';
        },

        render() {
            let items = this.getAll();
            const search = (document.getElementById('notifSearchInput')?.value || '').toLowerCase();

            if (search) items = items.filter(i => i.title.toLowerCase().includes(search) || i.desc.toLowerCase().includes(search));
            if (this.filter === 'unread') items = items.filter(i => !i.read);
            else if (this.filter !== 'all') items = items.filter(i => i.type === this.filter);

            const list = document.getElementById('notifList');
            if (!list) return;

            if (items.length === 0) {
                list.innerHTML = '<div class="notif-empty">🔕 No notifications</div>';
            } else {
                list.innerHTML = items.slice(0, 50).map(i => `
                    <div class="notif-item ${i.read ? '' : 'unread'}" onclick="NotifCenter.markRead('${i.id}')">
                        <div class="notif-icon ${i.type}">${this.getIcon(i.type)}</div>
                        <div class="notif-content">
                            <div class="notif-title">${i.title}</div>
                            <div class="notif-desc">${i.desc}</div>
                            <div class="notif-time">${this.timeAgo(i.time)}</div>
                        </div>
                        <div class="notif-actions">
                            <button onclick="NotifCenter.markRead('${i.id}', event)" title="Mark Read">✓</button>
                            <button onclick="NotifCenter.deleteItem('${i.id}', event)" title="Delete">✕</button>
                        </div>
                    </div>
                `).join('');
            }

            // Update badge
            const unread = this.getAll().filter(i => !i.read).length;
            const badge = document.getElementById('notifBadge');
            if (badge) {
                badge.textContent = unread > 9 ? '9+' : unread;
                badge.style.display = unread > 0 ? '' : 'none';
            }
        }
    };

    // Initialize notification center rendering
    NotifCenter.render();

    // Notification bell toggle
    document.getElementById('notifBellTrigger')?.addEventListener('click', function(e) {
        e.stopPropagation();
        const dd = document.getElementById('notifDropdown');
        const pd = document.getElementById('profileDropdown');
        if (pd) pd.classList.remove('show');
        dd?.classList.toggle('show');
    });

    // Profile dropdown toggle
    document.getElementById('profileTrigger')?.addEventListener('click', function(e) {
        e.stopPropagation();
        const pd = document.getElementById('profileDropdown');
        const nd = document.getElementById('notifDropdown');
        if (nd) nd.classList.remove('show');
        pd?.classList.toggle('show');
    });

    // Close dropdowns on outside click
    document.addEventListener('click', function() {
        document.getElementById('notifDropdown')?.classList.remove('show');
        document.getElementById('profileDropdown')?.classList.remove('show');
    });
    // Prevent dropdown close when clicking inside
    document.getElementById('notifDropdown')?.addEventListener('click', e => e.stopPropagation());
    document.getElementById('profileDropdown')?.addEventListener('click', e => e.stopPropagation());

    // =============================================
    // ACTIVITY LOGGER
    // =============================================
    window.ActivityLog = {
        KEY: 'activity_log_items',
        getAll() {
            try { return JSON.parse(localStorage.getItem(this.KEY)) || []; }
            catch { return []; }
        },
        save(items) { localStorage.setItem(this.KEY, JSON.stringify(items)); },
        log(action, module, status) {
            const items = this.getAll();
            items.unshift({
                date: new Date().toISOString(),
                action: action,
                module: module || 'System',
                ip: 'Client',
                browser: navigator.userAgent.split('(')[0].trim(),
                status: status || 'Success'
            });
            if (items.length > 200) items.length = 200;
            this.save(items);
        }
    };

    // Auto-log page visits
    (function() {
        const path = window.location.pathname;
        const pageNames = {
            '/': 'Dashboard', '/countries': 'Countries', '/weather': 'Weather',
            '/economy': 'Economy', '/currency': 'Currency', '/ports': 'Ports',
            '/news': 'News', '/analytics': 'Analytics', '/watchlist': 'Watchlist',
            '/compare': 'Compare', '/map': 'Global Map', '/settings': 'Settings',
            '/profile': 'Profile'
        };
        const name = pageNames[path] || path;
        ActivityLog.log('Viewed ' + name, name);
    })();

    // Feed notifications from high-risk polling into NotifCenter
    const _origCheckHighRisk = checkHighRisk;
    checkHighRisk = function() {
        const enableToast = localStorage.getItem('enable_toast') !== 'off';
        const triggerHighRisk = localStorage.getItem('alert_high_risk') !== 'off';
        const triggerWeather = localStorage.getItem('alert_weather') !== 'off';
        const triggerNews = localStorage.getItem('alert_news') !== 'off';
        const enableDesktop = localStorage.getItem('enable_desktop') === 'on';

        fetch("{{ url('/api/high-risk') }}")
            .then(r => r.json())
            .then(data => {
                if (lastHighRisk === null) {
                    lastHighRisk = data.map(x => x.id);
                    // Seed initial notifications
                    data.slice(0, 5).forEach(item => {
                        if (item.risk_level === 'High') {
                            NotifCenter.add('risk', '⚠ ' + item.country.name + ' High Risk', 'Risk score: ' + item.total_score);
                        }
                    });
                    NotifCenter.render();
                    return;
                }
                data.forEach(item => {
                    if (!lastHighRisk.includes(item.id)) {
                        lastHighRisk.push(item.id);
                        let type = 'risk', title = '', desc = '';

                        if (item.risk_level === 'High' && triggerHighRisk) {
                            type = 'risk'; title = '⚠ ' + item.country.name + ' risk increased';
                            desc = 'Risk Score: ' + item.total_score;
                        } else if (item.weather_score >= 60 && triggerWeather) {
                            type = 'weather'; title = '⛈ Extreme weather in ' + item.country.name;
                            desc = 'Weather Score: ' + item.weather_score;
                        } else if (item.news_score >= 60 && triggerNews) {
                            type = 'news'; title = '📰 Critical news from ' + item.country.name;
                            desc = 'News Score: ' + item.news_score;
                        } else {
                            type = 'economic'; title = '📊 Economic update for ' + item.country.name;
                            desc = 'Total Score: ' + item.total_score;
                        }

                        NotifCenter.add(type, title, desc);

                        if (enableToast) {
                            const toastEl = document.getElementById('riskToast');
                            if (toastEl) {
                                document.getElementById('toastMessage').innerHTML = `<strong>${title}</strong><br>${desc}`;
                                new bootstrap.Toast(toastEl).show();
                            }
                        }
                        if (enableDesktop && typeof Notification !== 'undefined' && Notification.permission === 'granted') {
                            new Notification(title, { body: desc });
                        }
                    }
                });
            });
    };

    // Global Chart.js configuration overrides for better readability
    if (typeof Chart !== 'undefined') {
        Chart.defaults.color = '#D1D5DB';
        Chart.defaults.borderColor = 'rgba(255, 255, 255, 0.08)';
        if (Chart.defaults.plugins && Chart.defaults.plugins.title) {
            Chart.defaults.plugins.title.color = '#FFFFFF';
        }
        if (Chart.defaults.plugins && Chart.defaults.plugins.legend && Chart.defaults.plugins.legend.labels) {
            Chart.defaults.plugins.legend.labels.color = '#D1D5DB';
        }
    }
</script>

@stack('scripts')

</body>
</html>