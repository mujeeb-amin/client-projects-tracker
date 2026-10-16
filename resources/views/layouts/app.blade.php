
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Client Projects Tracker')</title>

    <!-- Bootstrap 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        :root {
            --sidebar-bg: #18283e;
            --sidebar-hover: #334155;
            --sidebar-text: #cbd5e1;
        }

        body {
            background-color: #f1f5f9;
            margin: 0;
            min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            background-color: var(--sidebar-bg) !important;
            color: #ffffff !important;

            --bs-offcanvas-bg: #18283e;
            --bs-offcanvas-color: #ffffff;

            border: none !important;
        }

        .sidebar .offcanvas-header,
        .sidebar .offcanvas-body {
            background-color: var(--sidebar-bg) !important;
            color: white !important;
        }

        .sidebar .offcanvas-body {
            width: 100%;
        }

        .sidebar-title {
            color: #ffffff !important;
            font-size: 21px;
            font-weight: 600;
        }

        .sidebar nav a {
            display: block;
            padding: 12px 16px;
            margin-bottom: 6px;

            color: var(--sidebar-text) !important;
            text-decoration: none;

            border-radius: 8px;
            transition: background-color 0.2s;
        }

        .sidebar nav a:hover,
        .sidebar nav a.active {
            background-color: var(--sidebar-hover) !important;
            color: #ffffff !important;
        }

        /* DESKTOP */
        @media (min-width: 992px) {
            .sidebar {
                position: fixed !important;
                top: 0;
                left: 0;

                width: 250px !important;
                height: 100vh !important;

                overflow-y: auto;
                z-index: 1030;
            }

            .main-content {
                margin-left: 250px;
                padding: 30px;
            }
        }

        /* MOBILE AND TABLET */
        @media (max-width: 991.98px) {
            .sidebar {
                width: 260px !important;
            }

            .main-content {
                margin-left: 0;
                padding: 15px;
            }
        }

        /* HEADER */
        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
        }

        .page-title {
            font-size: 24px;
            font-weight: 600;
            margin: 0;
        }

        /* CARDS */
        .stat-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            height: 100%;
        }

        .stat-card .card-body {
            padding: 22px;
        }

        /* TABLES */
        .table-container {
            background: white;
            padding: 20px;
            border-radius: 12px;
            overflow-x: auto;
        }

        /* MOBILE HEADER */
        @media (max-width: 575.98px) {
            .page-title {
                font-size: 18px;
            }

            .top-header {
                flex-wrap: wrap;
            }
        }
    </style>
</head>

<body>

<!-- RESPONSIVE SIDEBAR -->
<div
    class="offcanvas-lg offcanvas-start sidebar"
    tabindex="-1"
    id="sidebar"
    aria-labelledby="sidebarTitle"
>

    <!-- Mobile Sidebar Header -->
    <div class="offcanvas-header d-lg-none">

        <h5 class="offcanvas-title sidebar-title"
            id="sidebarTitle">
            Client Tracker
        </h5>

        <button
            type="button"
            class="btn-close btn-close-white"
            data-bs-dismiss="offcanvas"
            data-bs-target="#sidebar"
            aria-label="Close"
        ></button>

    </div>

    <div class="offcanvas-body d-block p-3">

        <!-- Desktop Sidebar Title -->
        <h4 class="sidebar-title mb-4 d-none d-lg-block">
            Client Tracker
        </h4>

        <nav>

            <a href="/dashboard"
               class="{{ request()->is('dashboard') ? 'active' : '' }}">
                Dashboard
            </a>

            <a href="/clients"
               class="{{ request()->is('clients*') ? 'active' : '' }}">
                Clients
            </a>

            <a href="/projects"
               class="{{ request()->is('projects*') ? 'active' : '' }}">
                Projects
            </a>

            <a href="/payments"
               class="{{ request()->is('payments*') ? 'active' : '' }}">
                Payments
            </a>
            <a href="{{ route('reports.index') }}"
                class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">
                Financial Reports
            </a>

        </nav>

    </div>
</div>

<!-- MAIN CONTENT -->
<main class="main-content">

    <!-- TOP HEADER -->
    <div class="top-header">

        <div class="d-flex align-items-center gap-3">

            <!-- Mobile Menu -->
            <button
                type="button"
                class="btn btn-primary d-lg-none"
                data-bs-toggle="offcanvas"
                data-bs-target="#sidebar"
                aria-controls="sidebar"
                aria-label="Open menu"
            >
                &#9776;
            </button>

            <h4 class="page-title">
                @yield('page-title', 'Dashboard')
            </h4>

        </div>

        <!-- USER INFO -->
        @auth

            <div class="d-flex align-items-center gap-3">

                <span class="d-none d-sm-inline">
                    {{ auth()->user()->name }}
                </span>

                <form action="/logout" method="POST">
                    @csrf

                    <button
                        type="submit"
                        class="btn btn-outline-danger btn-sm"
                    >
                        Logout
                    </button>

                </form>

            </div>

        @endauth

    </div>

    <!-- SUCCESS MESSAGE -->
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show"
             role="alert">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close"
            ></button>

        </div>

    @endif

    <!-- VALIDATION ERRORS -->
    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>

    @endif

    <!-- PAGE CONTENT -->
    @yield('content')

</main>

<!-- BOOTSTRAP JAVASCRIPT -->
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

@stack('scripts')

</body>
</html>
