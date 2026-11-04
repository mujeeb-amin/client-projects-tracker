<!-- SIDEBAR -->
<div id="sidebar" class="app-sidebar">

    {{-- Desktop: Expand / Collapse --}}
    <div class="sidebar-desktop-controls">

        <span class="sidebar-desktop-label">
            Navigation
        </span>

        <button type="button"
                id="sidebarDesktopToggle"
                class="sidebar-desktop-toggle"
                aria-label="Collapse sidebar"
                aria-controls="sidebar"
                aria-expanded="true"
                title="Collapse sidebar">

            <svg viewBox="0 0 24 24"
                 width="22"
                 height="22"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2"
                 stroke-linecap="round"
                 stroke-linejoin="round"
                 aria-hidden="true">

                <path d="M15 18l-6-6 6-6"/>

            </svg>
        </button>

    </div>


    {{-- Mobile: Close Sidebar --}}
    <div class="sidebar-close-button">

        <strong>Client Tracker</strong>

        <button type="button"
                id="sidebarClose"
                class="sidebar-mobile-close"
                aria-label="Close sidebar"
                aria-controls="sidebar"
                title="Close sidebar">

            <svg viewBox="0 0 24 24"
                 width="22"
                 height="22"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2"
                 stroke-linecap="round"
                 aria-hidden="true">

                <path d="M18 6L6 18M6 6l12 12"/>

            </svg>

        </button>

    </div>


    <!-- Sidebar Content -->
    <div class="app-sidebar-content"
         data-scrollbar="true"
         data-height="100%">

        <div class="menu">

            @if(auth()->user()?->hasPermission('dashboard.view'))
            {{-- Dashboard --}}
            <div class="menu-item {{ request()->is('dashboard') ? 'active' : '' }}">

                <a href="/dashboard"
                   class="menu-link"
                   title="Dashboard">

                    <div class="menu-icon">
                        <svg viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             stroke-linecap="round"
                             stroke-linejoin="round"
                             aria-hidden="true">

                            <rect x="3" y="3" width="7" height="7" rx="1"/>
                            <rect x="14" y="3" width="7" height="7" rx="1"/>
                            <rect x="3" y="14" width="7" height="7" rx="1"/>
                            <rect x="14" y="14" width="7" height="7" rx="1"/>

                        </svg>
                    </div>

                    <div class="menu-text">
                        Dashboard
                    </div>

                </a>
            </div>


            @endif
            @if(auth()->user()?->hasPermission('clients.view'))
            {{-- Clients --}}
            <div class="menu-item {{ request()->is('clients*') ? 'active' : '' }}">

                <a href="/clients"
                   class="menu-link"
                   title="Clients">

                    <div class="menu-icon">

                        <svg viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             stroke-linecap="round"
                             stroke-linejoin="round"
                             aria-hidden="true">

                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>

                        </svg>

                    </div>

                    <div class="menu-text">
                        Clients
                    </div>

                </a>
            </div>


            @endif
            @if(auth()->user()?->hasPermission('projects.view'))
            {{-- Projects --}}
            <div class="menu-item {{ request()->is('projects*') ? 'active' : '' }}">

                <a href="/projects"
                   class="menu-link"
                   title="Projects">

                    <div class="menu-icon">

                        <svg viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             stroke-linecap="round"
                             stroke-linejoin="round"
                             aria-hidden="true">

                            <path d="M3 7a2 2 0 0 1 2-2h5l2 2h7a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7z"/>

                        </svg>

                    </div>

                    <div class="menu-text">
                        Projects
                    </div>

                </a>
            </div>


            @endif
            @if(auth()->user()?->hasPermission('payments.view'))
            {{-- Payments --}}
            <div class="menu-item {{ request()->is('payments*') ? 'active' : '' }}">

                <a href="/payments"
                   class="menu-link"
                   title="Payments">

                    <div class="menu-icon">

                        <svg viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             stroke-linecap="round"
                             stroke-linejoin="round"
                             aria-hidden="true">

                            <rect x="2" y="5" width="20" height="14" rx="2"/>
                            <path d="M2 10h20"/>

                        </svg>

                    </div>

                    <div class="menu-text">
                        Payments
                    </div>

                </a>
            </div>


            @endif
            @if(auth()->user()?->hasPermission('reports.view'))
            {{-- Financial Reports --}}
            <div class="menu-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">

                <a href="{{ route('reports.index') }}"
                   class="menu-link"
                   title="Financial Reports">

                    <div class="menu-icon">

                        <svg viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             stroke-linecap="round"
                             stroke-linejoin="round"
                             aria-hidden="true">

                            <path d="M3 3v18h18"/>
                            <path d="M7 16v-4"/>
                            <path d="M12 16V8"/>
                            <path d="M17 16V5"/>

                        </svg>

                    </div>

                    <div class="menu-text">
                        Financial Reports
                    </div>

                </a>
            </div>


            @endif
            {{-- User Management: Super Admin Only --}}
            @auth

                @if(auth()->user()->role === 'super_admin')

                    <div class="menu-item {{ request()->routeIs('users.*') ? 'active' : '' }}">

                        <a href="{{ route('users.index') }}"
                           class="menu-link"
                           title="User Management">

                            <div class="menu-icon">

                                <svg viewBox="0 0 24 24"
                                     width="18"
                                     height="18"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round"
                                     aria-hidden="true">

                                    <circle cx="9" cy="7" r="4"/>

                                    <path d="M2 21v-2a7 7 0 0 1 14 0v2"/>

                                    <path d="M19 8v6"/>

                                    <path d="M16 11h6"/>

                                </svg>

                            </div>

                            <div class="menu-text">
                                User Management
                            </div>

                        </a>

                    </div>

                @endif

            @endauth


            <div class="menu-divider"></div>


            {{-- Logout --}}
            @auth

                <div class="menu-item">

                    <form action="/logout" method="POST">

                        @csrf

                        <button type="submit"
                                class="menu-link w-100 border-0 bg-transparent text-start"
                                title="Logout">

                            <div class="menu-icon">

                                <svg viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round"
                                     aria-hidden="true">

                                    <path d="M10 17l5-5-5-5"/>
                                    <path d="M15 12H3"/>
                                    <path d="M12 3h7a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-7"/>

                                </svg>

                            </div>

                            <div class="menu-text">
                                Logout
                            </div>

                        </button>

                    </form>

                </div>

            @endauth

        </div>

    </div>

</div>
<!-- END SIDEBAR -->