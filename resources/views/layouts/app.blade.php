
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Client Projects Tracker')</title>

    @vite([
        'resources/css/studio/vendor.min.css',
        'resources/css/studio/app.min.css',
        'resources/css/studio/custom.css'
    ])

    <style>
        /* General page styles */
        .app-content {
            min-height: calc(100vh - 60px);
            min-width: 0;
        }

        .table-container {
            background: var(--bs-component-bg, #fff);
            padding: 20px;
            border-radius: 10px;
            overflow-x: auto;
        }

        .stat-card { height: 100%; }

        /* Header */
        .app-header .navbar-nav { margin-left: auto; }

        .app-header .navbar-user-info {
            display: flex;
            align-items: center;
            gap: 15px;
            padding-right: 20px;
        }

        .app-header .navbar-user-info form { margin: 0; }

        /* Icons */
        #sidebar .menu-icon svg {
            width: 18px;
            height: 18px;
            display: block;
        }

        #header #sidebarToggle svg {
            width: 24px;
            height: 24px;
            display: block;
        }

        /* Desktop sidebar controls */
        #sidebar .sidebar-desktop-controls {
            display: none;
        }

        #sidebar .sidebar-desktop-toggle {
            width: 36px;
            height: 36px;
            flex-shrink: 0;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            border: 0 !important;
            box-shadow: none !important;
            border-radius: 8px;
            background: transparent;
            color: #344054;
            cursor: pointer;
        }

        #sidebar .sidebar-desktop-toggle:hover,
        #sidebar .sidebar-desktop-toggle:focus-visible {
            background: #e5e9f2;
            color: #2563eb;
        }

        #sidebar .sidebar-desktop-toggle:focus-visible {
            outline: 2px solid #2563eb;
            outline-offset: 2px;
        }

        #sidebar .sidebar-desktop-toggle svg {
            width: 22px;
            height: 22px;
            transition: transform 0.25s ease;
        }

        #sidebar .sidebar-close-button { display: none; }

        /* Desktop */
        @media (min-width: 992px) {
            #header #sidebarToggle,
            #header .navbar-mobile-toggler#sidebarToggle {
                display: none !important;
            }

            #sidebar .sidebar-desktop-controls {
                position: relative;
                z-index: 2;
                width: 100%;
                box-sizing: border-box;
                min-height: 60px;
                padding: 10px 13px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 8px;
                border-bottom: 1px solid #dae0ec;
            }

            #sidebar.app-sidebar {
                transition: width 0.25s ease !important;
            }

            #content.app-content {
                transition: margin-inline-start 0.25s ease;
            }

            #sidebar .sidebar-desktop-label {
                font-size: 14px;
                font-weight: 600;
                color: #4d6593;
                white-space: nowrap;
            }

            #sidebar .menu-header { display: none; }

            #app.app-sidebar-minified #sidebar .sidebar-desktop-controls {
                justify-content: center;
                padding-inline: 4px;
            }

            #app.app-sidebar-minified #sidebar .sidebar-desktop-label {
                display: none !important;
            }

            #app.app-sidebar-minified #sidebar .sidebar-desktop-toggle svg {
                transform: rotate(180deg);
            }
        }

        /* Mobile */
        @media (max-width: 991.98px) {
            #app { isolation: isolate; }

            #header.app-header { z-index: 1000 !important; }

            #header #sidebarToggle {
                display: inline-flex !important;
                align-items: center;
                justify-content: center;
                border: 0 !important;
                background: transparent !important;
                padding: 6px;
                cursor: pointer;
            }

            #header #sidebarToggle:focus-visible {
                outline: 2px solid #2563eb;
                outline-offset: 2px;
            }

            #sidebar .sidebar-desktop-controls {
                display: none !important;
            }

            #header #sidebarToggle svg { display: block; }

            #sidebar.app-sidebar {
                position: fixed !important;
                top: 0 !important;
                bottom: 0 !important;
                left: 0 !important;
                inset-inline-start: 0 !important;
                height: 100dvh !important;
                width: 240px !important;
                z-index: 1100 !important;
                transform: translateX(-100%) !important;
                transition: transform 0.25s ease !important;
                visibility: hidden !important;
                pointer-events: none !important;
                display: flex !important;
                flex-direction: column;
                background: #fff;
            }

            #app.app-sidebar-mobile-toggled #sidebar.app-sidebar {
                transform: translateX(0) !important;
                visibility: visible !important;
                pointer-events: auto !important;
            }

            #sidebar .app-sidebar-content {
                width: 100% !important;
                min-width: 0;
                flex: 1 1 auto;
                min-height: 0;
                overflow-y: auto;
            }

            #app .app-sidebar-mobile-backdrop {
                display: none !important;
                position: fixed !important;
                inset: 0 !important;
                z-index: 1090 !important;
                background: rgba(0, 0, 0, 0.45);
            }

            #app.app-sidebar-mobile-toggled .app-sidebar-mobile-backdrop {
                display: block !important;
            }

            #sidebar .sidebar-close-button {
                position: relative;
                z-index: 2;
                flex: 0 0 auto;
                display: flex !important;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                padding: 12px 15px;
                min-height: 60px;
                border-bottom: 1px solid #dae0ec;
            }

            #sidebar .sidebar-close-button strong {
                display: block !important;
                font-size: 14px;
                color: #344054;
            }

            #sidebar #sidebarClose {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 36px;
                height: 36px;
                border: 0;
                background: transparent;
                border-radius: 8px;
                color: #344054;
                cursor: pointer;
            }

            #sidebar #sidebarClose:hover { background: #e5e9f2; }

            #sidebar #sidebarClose svg {
                width: 22px;
                height: 22px;
            }

            body.sidebar-mobile-open { overflow: hidden !important; }

            #app.app-sidebar-mobile-toggled #header #sidebarToggle {
                visibility: hidden !important;
            }

            #content.app-content {
                position: relative;
                z-index: auto !important;
                margin-inline-start: 0 !important;
                margin-left: 0 !important;
                padding: 20px 15px !important;
                width: 100%;
                min-width: 0;
            }

            .page-header { font-size: 22px; }
            .table-container { overflow-x: auto; }
        }

        @media (max-width: 575.98px) {
            #content.app-content {
                padding: 15px 10px !important;
            }

            .page-header { font-size: 20px; }

            .app-header .navbar-header {
                min-width: 0;
                flex: 1 1 auto;
            }

            .app-header .navbar-brand {
                font-size: 14px;
                white-space: nowrap;
            }

            .app-header .navbar-nav {
                flex: 0 0 auto;
                margin-left: auto;
            }

            .app-header .navbar-user-info {
                gap: 5px;
                padding-right: 10px;
            }

            .app-header .navbar-user-info .fw-semibold {
                display: none;
            }

            .app-header .btn {
                font-size: 12px;
                padding: 5px 8px;
            }

            .table-container { padding: 10px; }
            .card-body { padding: 15px; }
        }

        /* Profile popup */
        #header .header-profile-button {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            border: 0;
            background: transparent;
            cursor: pointer;
            padding: 5px 8px;
            border-radius: 10px;
            color: #334155;
            max-width: 100%;
        }

        #header .header-profile-button:hover {
            background: #eef2f9;
        }

        #header .header-profile-button:focus-visible {
            outline: 2px solid #2563eb;
            outline-offset: 2px;
        }

        #header .header-user-icon {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #e8f0fe;
            color: #2563eb;
            flex-shrink: 0;
            overflow: hidden;
        }

        .profile-header-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        #header .header-user-name {
            font-size: 14px;
            font-weight: 600;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 180px;
        }

        .profile-dialog {
            border: 0;
            padding: 0;
            border-radius: 16px;
            box-shadow: 0 20px 65px rgba(0,0,0,.22);
            width: min(450px, 95vw);
            max-height: 90dvh;
            color: #243145;
        }

        .profile-dialog::backdrop {
            background: rgba(15,23,42,.55);
        }

        .profile-dialog-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 22px;
            border-bottom: 1px solid #e5e7eb;
        }

        .profile-dialog-header h2 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
        }

        .profile-dialog-close {
            cursor: pointer;
            border: 0;
            background: transparent;
            font-size: 28px;
            line-height: 1;
            width: 34px;
            height: 34px;
            border-radius: 8px;
            color: #334155;
        }

        .profile-dialog-close:hover { background: #f1f5f9; }

        .profile-dialog-content {
            padding: 22px;
            overflow-y: auto;
            max-height: calc(90dvh - 80px);
        }

        .profile-avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: #e8f0fe;
            color: #2563eb;
            display: grid;
            place-items: center;
            margin: 0 auto 12px;
        }

        .profile-avatar svg {
            width: 34px;
            height: 34px;
        }

        .profile-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #edf0f4;
        }

        .profile-row dt {
            font-weight: 600;
            color: #64748b;
        }

        .profile-row dd {
            margin: 0;
            text-align: right;
            overflow-wrap: anywhere;
        }

        .profile-dialog-status {
            text-align: center;
            color: #64748b;
        }

        /* Profile settings */
        #profileSettingsSection[hidden] {
            display: none !important;
        }

        .profile-settings-section {
            margin-top: 20px;
            padding-top: 18px;
            border-top: 1px solid #e5e7eb;
        }

        .profile-password-section {
            margin-top: 22px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }

        .profile-password-section .form-label {
            font-size: 14px;
            font-weight: 500;
            color: #475569;
        }

        .profile-password-section .form-control {
            height: 42px;
            border-radius: 8px;
        }

        .profile-password-section .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .profile-password-section .btn {
            height: 42px;
            border-radius: 8px;
            font-weight: 600;
        }

        @media (max-width: 575.98px) {
            #header .header-profile-button {
                gap: 6px;
                padding: 4px;
            }

            #header .header-user-icon {
                width: 32px;
                height: 32px;
            }

            #header .header-user-name {
                max-width: 95px;
                font-size: 12px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

<div id="app" class="app app-header-fixed app-sidebar-fixed">

    @include('partials.header')

    @include('partials.sidebar')

    <div class="app-sidebar-mobile-backdrop" id="sidebarBackdrop"></div>

    <div id="content" class="app-content">

        <h1 class="page-header">
            @yield('page-title', 'Dashboard')
        </h1>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')

    </div>
</div>

@auth
<dialog id="userProfileDialog"
        class="profile-dialog"
        aria-labelledby="userProfileTitle">

    <div class="profile-dialog-header">
        <h2 id="userProfileTitle">My Profile</h2>

        <button type="button"
                id="userProfileClose"
                class="profile-dialog-close"
                aria-label="Close profile">&times;</button>
    </div>

    <div class="profile-dialog-content">

        <p id="userProfileStatus"
           class="profile-dialog-status"
           role="status"
           aria-live="polite">
            Loading profile...
        </p>

        <div id="userProfileDetails" hidden>

            <div class="profile-avatar" aria-hidden="true">
                <svg viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="1.8"
                     stroke-linecap="round"
                     stroke-linejoin="round">
                    <circle cx="12" cy="8" r="4"/>
                    <path d="M4 21v-2a8 8 0 0 1 16 0v2"/>
                </svg>
            </div>

            <dl>
                <div class="profile-row">
                    <dt>Full Name</dt>
                    <dd id="profileName"></dd>
                </div>

                <div class="profile-row">
                    <dt>Email</dt>
                    <dd id="profileEmail"></dd>
                </div>
            </dl>

            <!-- One button for all profile settings -->
            <button type="button"
                    id="editProfileButton"
                    class="btn btn-outline-primary w-100 mt-3">
                Edit Profile
            </button>

            <!-- Both forms are inside this section -->
            <div id="profileSettingsSection"
                 class="profile-settings-section"
                 hidden>

                @include('partials.profile-edit-form')

                <!-- Change Password -->
                <div class="profile-password-section">

                    <h5 class="mb-3">Change Password</h5>

                    <div id="passwordMessage"
                         class="alert d-none"
                         role="status"
                         aria-live="polite"></div>

                    <form id="changePasswordForm">

                        @csrf

                        <div class="mb-3">
                            <label for="currentPassword" class="form-label">
                                Current Password
                            </label>

                            <input type="password"
                                   id="currentPassword"
                                   name="current_password"
                                   class="form-control"
                                   autocomplete="current-password"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="newPassword" class="form-label">
                                New Password
                            </label>

                            <input type="password"
                                   id="newPassword"
                                   name="password"
                                   class="form-control"
                                   autocomplete="new-password"
                                   minlength="8"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">
                                Confirm New Password
                            </label>

                            <input type="password"
                                   id="confirmPassword"
                                   name="password_confirmation"
                                   class="form-control"
                                   autocomplete="new-password"
                                   minlength="8"
                                   required>
                        </div>

                        <button type="submit"
                                id="updatePasswordBtn"
                                class="btn btn-primary w-100">
                            Update Password
                        </button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</dialog>
@endauth

{{-- Studio JavaScript --}}
@vite([
    'resources/js/studio/vendor.min.js',
    'resources/js/studio/app.min.js'
])

@stack('scripts')

<script>
(function () {
    function initSidebar() {
        const app = document.getElementById('app');
        const desktopToggle = document.getElementById('sidebarDesktopToggle');
        const mobileToggle = document.getElementById('sidebarToggle');
        const mobileClose = document.getElementById('sidebarClose');
        const backdrop = document.getElementById('sidebarBackdrop');

        if (!app) return;

        const mobileQuery = window.matchMedia('(max-width: 991.98px)');
        const storageKey = 'appSidebarMinified';

        try {
            app.classList.toggle(
                'app-sidebar-minified',
                localStorage.getItem(storageKey) === 'true'
            );
        } catch (error) {}

        app.classList.remove('sidebar-desktop-closed');

        function syncButtons() {
            const isMinified = app.classList.contains('app-sidebar-minified');
            const isMobileOpen = app.classList.contains('app-sidebar-mobile-toggled');

            if (desktopToggle) {
                const label = isMinified ? 'Expand sidebar' : 'Collapse sidebar';
                desktopToggle.setAttribute('aria-expanded', String(!isMinified));
                desktopToggle.setAttribute('aria-label', label);
                desktopToggle.setAttribute('title', label);
            }

            if (mobileToggle) {
                mobileToggle.setAttribute('aria-expanded', String(isMobileOpen));
                mobileToggle.setAttribute(
                    'aria-label',
                    isMobileOpen ? 'Close menu' : 'Open menu'
                );
            }
        }

        function setMobileSidebar(open) {
            app.classList.toggle('app-sidebar-mobile-toggled', open);
            document.body.classList.toggle('sidebar-mobile-open', open);
            syncButtons();
        }

        if (desktopToggle) {
            desktopToggle.addEventListener('click', function (event) {
                event.preventDefault();
                if (mobileQuery.matches) return;

                const isMinified = app.classList.toggle('app-sidebar-minified');

                try {
                    if (isMinified) {
                        localStorage.setItem(storageKey, 'true');
                    } else {
                        localStorage.removeItem(storageKey);
                    }
                } catch (error) {}

                syncButtons();
            });
        }

        if (mobileToggle) {
            mobileToggle.addEventListener('click', function (event) {
                event.preventDefault();
                if (!mobileQuery.matches) return;

                setMobileSidebar(
                    !app.classList.contains('app-sidebar-mobile-toggled')
                );
            });
        }

        if (mobileClose) {
            mobileClose.addEventListener('click', function () {
                if (mobileQuery.matches) setMobileSidebar(false);
            });
        }

        if (backdrop) {
            backdrop.addEventListener('click', function () {
                if (mobileQuery.matches) setMobileSidebar(false);
            });
        }

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && mobileQuery.matches) {
                setMobileSidebar(false);
            }
        });

        function onBreakpointChange() {
            setMobileSidebar(false);
        }

        if (typeof mobileQuery.addEventListener === 'function') {
            mobileQuery.addEventListener('change', onBreakpointChange);
        } else {
            mobileQuery.addListener(onBreakpointChange);
        }

        syncButtons();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSidebar);
    } else {
        initSidebar();
    }
})();
</script>

<script>
(function () {
    function initProfile() {
        const button = document.getElementById('userProfileTrigger');
        const dialog = document.getElementById('userProfileDialog');
        const close = document.getElementById('userProfileClose');
        const status = document.getElementById('userProfileStatus');
        const details = document.getElementById('userProfileDetails');

        if (!button || !dialog || !close || !status || !details) return;

        const fields = {
            name: 'profileName',
            email: 'profileEmail'
        };

        const requestUrl = @json(route('profile.me'));
        let requestController = null;

        function showValue(key, value) {
            document.getElementById(fields[key]).textContent = value ?? '—';
        }

        function closeDialog() {
            if (requestController) requestController.abort();
            dialog.close();
        }

        button.addEventListener('click', async function () {
            if (dialog.open) return;

            dialog.showModal();
            details.hidden = true;
            status.hidden = false;
            status.textContent = 'Loading profile...';

            requestController = new AbortController();

            try {
                const response = await fetch(requestUrl, {
                    method: 'GET',
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    signal: requestController.signal
                });

                if (response.status === 401) {
                    throw new Error('Your session expired. Please sign in again.');
                }

                if (!response.ok) {
                    throw new Error('Unable to load profile. Please try again.');
                }

                const data = await response.json();

                if (!dialog.open) return;

                showValue('name', data.name);
                showValue('email', data.email);

                details.hidden = false;
                status.hidden = true;

            } catch (error) {
                if (error.name === 'AbortError') return;

                status.textContent = error.message || 'Unable to load profile.';
                details.hidden = true;
                status.hidden = false;

            } finally {
                requestController = null;
            }
        });

        close.addEventListener('click', closeDialog);

        dialog.addEventListener('click', function (event) {
            if (event.target === dialog) closeDialog();
        });

        dialog.addEventListener('cancel', function () {
            if (requestController) requestController.abort();
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initProfile);
    } else {
        initProfile();
    }
})();
</script>

<script>
/* Open and close all profile settings with one button */
document.addEventListener('DOMContentLoaded', function () {

    const editButton = document.getElementById('editProfileButton');
    const settingsSection = document.getElementById('profileSettingsSection');

    if (!editButton || !settingsSection) return;

    editButton.addEventListener('click', function () {

        settingsSection.hidden = !settingsSection.hidden;

        editButton.textContent = settingsSection.hidden
            ? 'Edit Profile'
            : 'Close Profile Settings';

    });

});
</script>

</body>
</html>
