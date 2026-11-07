<!-- HEADER -->
<div id="header" class="app-header">

    <div class="navbar-header">

        <!-- Brand -->
        <a href="/dashboard" class="navbar-brand">
            <span class="navbar-logo"></span>
            <strong>Client Tracker</strong>
        </a>

        <!-- Mobile hamburger -->
        <button type="button"
                class="navbar-mobile-toggler"
                id="sidebarToggle"
                aria-label="Open menu"
                aria-controls="sidebar"
                aria-expanded="false">

            <svg viewBox="0 0 24 24"
                 width="24" height="24" fill="none"
                 stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" aria-hidden="true">
                <path d="M4 6h16M4 12h16M4 18h16"/>
            </svg>

        </button>

    </div>

    <!-- User Profile Trigger -->
    <div class="navbar-nav">
        @auth
            <div class="navbar-user-info">

                <button type="button"
                        id="userProfileTrigger"
                        class="header-profile-button"
                        aria-label="View my profile"
                        aria-haspopup="dialog"
                        aria-controls="userProfileDialog"
                        title="View my profile">

                    <span class="header-user-icon" aria-hidden="true">

                        @if(auth()->user()->avatar_path)

                            <img
                                src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url(auth()->user()->avatar_path) }}"
                                alt=""
                                class="profile-header-image"
                            >

                        @else

                            <svg viewBox="0 0 24 24"
                                 width="21" height="21" fill="none"
                                 stroke="currentColor" stroke-width="1.8"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="8" r="4"/>
                                <path d="M4 21v-2a8 8 0 0 1 16 0v2"/>
                            </svg>

                        @endif

                    </span>

                    <span class="header-user-name">
                        {{ auth()->user()->name }}
                    </span>

                </button>

            </div>
        @endauth
    </div>

</div>
<!-- END HEADER -->
