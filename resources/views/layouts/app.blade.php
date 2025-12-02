<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        @hasSection('title')
            @yield('title') – SGRS-CEEAC
        @else
            SGRS-CEEAC – Système de Gestion des Réunions Statutaires
        @endif
    </title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- User ID pour Echo (notifications temps réel) -->
    @auth
        <meta name="user-id" content="{{ auth()->id() }}">
    @endauth

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/ceeac-logo.png') }}">

    <!-- Bootstrap CSS (AJOUT IMPORTANT) -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous"
    >

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Police Figtree -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700&display=swap"
          rel="stylesheet" />

    <!-- Vite (Tailwind, Alpine, Echo, etc.) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- CSS Design Moderne -->
    <link rel="stylesheet" href="{{ asset('css/modern-design.css') }}">

    <style>
        :root {
            --sgrs-primary: #667eea;
            --sgrs-secondary: #764ba2;
            --sgrs-success: #43e97b;
            --sgrs-info: #4facfe;
            --sgrs-warning: #f093fb;
            --sgrs-danger: #f5576c;
            --sgrs-gradient-1: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --sgrs-gradient-2: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --sgrs-gradient-3: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --sgrs-gradient-4: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }

        body {
            font-family: 'Figtree', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background-color: #f8f9fa;
        }

        .sgrs-sidebar {
            width: 280px;
            background: linear-gradient(180deg, #1e3a8a 0%, #1e40af 100%);
            box-shadow: 2px 0 15px rgba(0,0,0,0.1);
            color: #ffffff;
        }

        .sgrs-sidebar .nav-link {
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
            margin-bottom: 0.3rem;
            color: rgba(255, 255, 255, 0.85);
            border: none;
        }

        .sgrs-sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            transform: translateX(3px);
        }

        .sgrs-sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            font-weight: 600;
            border-left: 4px solid #ffffff;
        }

        .sgrs-sidebar .nav-link.active i {
            color: #ffffff;
        }

        .sgrs-sidebar .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        .sgrs-sidebar .nav-link-sub {
            padding-left: 2.5rem;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.75);
        }

        .sgrs-sidebar .nav-link-sub:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffffff;
        }

        .sgrs-sidebar .nav-link-sub.active {
            background-color: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            font-weight: 600;
        }

        .sgrs-brand-title {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: .05em;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.7);
            margin-top: 1rem;
        }

        .nav-section-label {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: .05em;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.7);
        }

        .badge-module {
            font-size: 0.7rem;
            padding: 0.25em 0.5em;
            background-color: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            font-weight: 600;
            border-radius: 4px;
        }

        .badge-notification {
            position: absolute;
            top: -5px;
            right: -5px;
            font-size: 0.7rem;
            padding: 0.2em 0.4em;
            min-width: 18px;
            text-align: center;
        }

        .sgrs-content-wrapper {
            min-height: 100vh;
        }

        .card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12) !important;
        }

        .card-header {
            background: #ffffff;
            border-bottom: 1px solid #f1f5f9;
            padding: 1.25rem 1.5rem;
            font-weight: 600;
        }

        .card-body {
            padding: 1.5rem;
        }

        .card-footer {
            background: #f8fafc;
            border-top: 1px solid #f1f5f9;
            padding: 1rem 1.5rem;
        }

        .btn {
            transition: all 0.3s ease;
            font-weight: 600;
            border-radius: 10px;
            padding: 0.65rem 1.5rem;
            font-size: 0.9rem;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }

        .btn-outline-primary {
            border: 2px solid #667eea;
            color: #667eea;
        }

        .btn-outline-primary:hover {
            background: #667eea;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .btn-outline-secondary {
            border: 2px solid #e2e8f0;
            color: #64748b;
        }

        .btn-outline-secondary:hover {
            background: #f8f9fa;
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }

        .btn-outline-danger {
            border: 2px solid #f5576c;
            color: #f5576c;
        }

        .btn-outline-danger:hover {
            background: #f5576c;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 87, 108, 0.3);
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
        }

        /* Tableaux modernes */
        .table {
            font-size: 0.95rem;
            background: #ffffff;
        }

        .table thead th {
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            color: #475569;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-bottom: 2px solid #e2e8f0;
            padding: 1.25rem 1rem;
            white-space: nowrap;
            position: relative;
        }

        .table thead th.sortable {
            cursor: pointer;
            user-select: none;
            transition: all 0.2s ease;
        }

        .table thead th.sortable:hover {
            background: rgba(102, 126, 234, 0.05);
            color: #667eea;
        }

        .table thead th i {
            font-size: 0.7rem;
            margin-left: 0.5rem;
            opacity: 0.5;
            transition: opacity 0.2s ease;
        }

        .table thead th.sortable:hover i {
            opacity: 1;
        }

        .table tbody td {
            padding: 1.25rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            font-size: 0.9rem;
        }

        .table tbody td:first-child {
            font-weight: 600;
            color: #1e293b;
        }

        .table tbody tr {
            transition: all 0.2s ease;
            border-bottom: 1px solid #f1f5f9;
        }

        .table tbody tr:hover {
            background: linear-gradient(90deg, #f8fafc 0%, #ffffff 100%);
            transform: scale(1.001);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .badge {
            font-weight: 600;
            padding: 0.5em 0.85em;
            font-size: 0.8rem;
            border-radius: 6px;
            letter-spacing: 0.3px;
        }

        /* Formulaires modernes */
        .form-label {
            font-weight: 600;
            color: #334155;
            font-size: 0.9rem;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-label i {
            color: #667eea;
            font-size: 0.9rem;
        }

        .form-label .required {
            color: #ef4444;
            margin-left: 0.25rem;
        }

        .form-control,
        .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.875rem 1.125rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: #ffffff;
            color: #1e293b;
        }

        .form-control:hover:not(:focus),
        .form-select:hover:not(:focus) {
            border-color: #cbd5e1;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            outline: none;
            background: #ffffff;
        }

        .form-control.is-invalid,
        .form-select.is-invalid {
            border-color: #ef4444;
            background: #fef2f2;
        }

        .form-control.is-invalid:focus,
        .form-select.is-invalid:focus {
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
            border-color: #ef4444;
        }

        .form-control.is-valid,
        .form-select.is-valid {
            border-color: #10b981;
            background: #f0fdf4;
        }

        .invalid-feedback {
            font-size: 0.85rem;
            margin-top: 0.5rem;
            color: #ef4444;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-text {
            font-size: 0.85rem;
            color: #64748b;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-text i {
            font-size: 0.9rem;
        }

        .bg-primary-subtle {
            background-color: rgba(102, 126, 234, 0.1) !important;
        }

        .text-primary {
            color: var(--sgrs-primary) !important;
        }

        .sgrs-topbar {
            background: #ffffff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-bottom: 1px solid #f1f5f9;
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .sgrs-topbar .navbar-brand {
            font-weight: 700;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .sgrs-topbar .btn-outline-secondary {
            border-radius: 10px;
            transition: all 0.2s ease;
        }
        
        .sgrs-topbar .btn-outline-secondary:hover {
            background: #f8fafc;
            transform: translateY(-1px);
        }

        /* Titres de page modernes */
        .page-header {
            background: #ffffff;
            padding: 1.5rem 0;
            border-bottom: 1px solid #f1f5f9;
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .breadcrumb {
            background: transparent;
            padding: 0;
            margin: 0;
            font-size: 0.9rem;
        }

        .breadcrumb-item a {
            color: #64748b;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .breadcrumb-item a:hover {
            color: #667eea;
        }

        .breadcrumb-item.active {
            color: #1e293b;
            font-weight: 500;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            content: "/";
            color: #cbd5e1;
            padding: 0 0.5rem;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card {
            animation: fadeIn 0.5s ease-out;
        }

        /* Scrollbar personnalisée */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Alertes modernes */
        .alert {
            border-radius: 12px;
            border: none;
            padding: 1rem 1.25rem;
            font-weight: 500;
        }

        .alert-success {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            color: #ffffff;
        }

        .alert-danger {
            background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%);
            color: #ffffff;
        }

        .alert-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: #ffffff;
        }

        .alert-info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: #ffffff;
        }

        /* Badges modernes */
        .badge.bg-success {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%) !important;
            color: #ffffff;
        }

        .badge.bg-danger {
            background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%) !important;
            color: #ffffff;
        }

        .badge.bg-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: #ffffff;
        }

        .badge.bg-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
            color: #ffffff;
        }

        .badge.bg-info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
            color: #ffffff;
        }

        /* Input groups modernes */
        .input-group-text {
            background: #f8f9fa;
            border: 2px solid #e2e8f0;
            border-right: none;
            border-radius: 10px 0 0 10px;
        }

        .input-group .form-control {
            border-left: none;
            border-radius: 0 10px 10px 0;
        }

        .input-group .form-control:focus {
            border-left: 2px solid #667eea;
        }

        /* Pagination moderne */
        .pagination {
            gap: 0.5rem;
        }

        .page-link {
            border-radius: 8px;
            border: 2px solid #e2e8f0;
            color: #64748b;
            padding: 0.5rem 0.75rem;
            transition: all 0.3s ease;
        }

        .page-link:hover {
            background: #667eea;
            border-color: #667eea;
            color: #ffffff;
            transform: translateY(-2px);
        }

        .page-item.active .page-link {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: #667eea;
            color: #ffffff;
        }

        /* Dropdowns modernes */
        .dropdown-menu {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 0.5rem;
        }

        .dropdown-item {
            border-radius: 8px;
            padding: 0.65rem 1rem;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background: #f8f9fa;
            transform: translateX(3px);
        }

        /* Filtres modernes */
        .filter-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        /* KPI Cards modernes */
        .kpi-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid #f1f5f9;
            transition: all 0.3s ease;
        }

        .kpi-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .kpi-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .kpi-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0.5rem 0;
        }

        .kpi-label {
            font-size: 0.9rem;
            color: #64748b;
            font-weight: 500;
        }
    </style>
</head>

<body class="bg-light">
    <div id="app">
        <div class="d-flex sgrs-content-wrapper">
           {{-- SIDEBAR (bureau) --}}
        <nav class="sgrs-sidebar d-none d-md-flex flex-column">
            <div class="p-4 border-bottom border-white border-opacity-20">
                <div class="d-flex align-items-center mb-2">
                    <img src="{{ asset('images/logo-ceeac.png') }}"
                        alt="CEEAC"
                        width="40"
                        height="40"
                        class="me-2 rounded-circle bg-white p-1">
                    <div>
                        <div class="fw-bold text-white">SGRS - CEEAC</div>
                        <div class="text-white-50 small">Réunions statutaires</div>
                    </div>
                </div>
            </div>

            <div class="flex-grow-1 p-3">
                <div class="sgrs-brand-title mb-2">Navigation principale</div>
                <ul class="nav nav-pills flex-column gap-1">

                    {{-- Tableau de bord --}}
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}"
                        class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer"></i>
                            <span>Tableau de bord</span>
                            <span class="badge badge-module ms-auto d-none d-xl-inline">Vue globale</span>
                        </a>
                    </li>

                    {{-- Réunions (liste principale) --}}
                    <li class="nav-item">
                        <a href="{{ route('meetings.index') }}"
                        class="nav-link {{ request()->routeIs('meetings.*') ? 'active' : '' }}">
                            <i class="bi bi-people-fill"></i>
                            <span>Réunions</span>
                        </a>
                    </li>

                    {{-- Calendrier --}}
                    <li class="nav-item">
                        <a href="{{ route('calendar.index') }}"
                        class="nav-link {{ request()->routeIs('calendar.*') ? 'active' : '' }}">
                            <i class="bi bi-calendar-check"></i>
                            <span>Calendrier</span>
                        </a>
                    </li>

                    {{-- Documents --}}
                    <li class="nav-item">
                        <a href="{{ route('documents.index') }}"
                        class="nav-link {{ request()->routeIs('documents.*') ? 'active' : '' }}">
                            <i class="bi bi-folder2-open"></i>
                            <span>Documents</span>
                        </a>
                    </li>

                    {{-- Participants --}}
                    <li class="nav-item">
                        <a href="{{ route('participants.index') }}"
                        class="nav-link {{ request()->routeIs('participants.*') ? 'active' : '' }}">
                            <i class="bi bi-person-lines-fill"></i>
                            <span>Participants</span>
                        </a>
                    </li>

                    @can('delegations.view')
                        <li class="nav-item">
                            <a href="{{ route('delegations.index') }}"
                            class="nav-link {{ request()->routeIs('delegations.*') ? 'active' : '' }}">
                                <i class="bi bi-flag"></i>
                                <span>Délégations</span>
                            </a>
                        </li>
                    @endcan
                </ul>

                {{-- Sous-menu CONFIGURATION DES RÉUNIONS --}}
                <div class="mt-4">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <span class="nav-section-label">Configuration des réunions</span>
                        <span class="badge badge-module">SGRS</span>
                    </div>

                    <ul class="nav nav-pills flex-column gap-1 nav-submenu">
                        <li class="nav-item">
                            <button class="nav-link w-100 d-flex justify-content-between align-items-center
                                        {{ request()->routeIs('meeting-types.*') || request()->routeIs('committees.*') || request()->routeIs('rooms.*') ? 'active' : '' }}"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#submenuMeetingsConfig"
                                    aria-expanded="{{ request()->routeIs('meeting-types.*') || request()->routeIs('committees.*') || request()->routeIs('rooms.*') ? 'true' : 'false' }}"
                                    aria-controls="submenuMeetingsConfig">
                                <span class="d-flex align-items-center gap-2">
                                    <i class="bi bi-sliders"></i>
                                    <span>Paramétrage</span>
                                </span>
                                <i class="bi bi-chevron-down small"></i>
                            </button>

                            <div class="collapse {{ request()->routeIs('meeting-types.*') || request()->routeIs('committees.*') || request()->routeIs('rooms.*') ? 'show' : '' }}"
                                id="submenuMeetingsConfig">
                                <ul class="nav flex-column mt-1">

                                    {{-- Types de réunions --}}
                                    <li class="nav-item">
                                        <a href="{{ route('meeting-types.index') }}"
                                        class="nav-link nav-link-sub {{ request()->routeIs('meeting-types.*') ? 'active' : '' }}">
                                            <i class="bi bi-diagram-3"></i>
                                            <span>Types de réunions</span>
                                        </a>
                                    </li>

                                    {{-- Comités --}}
                                    <li class="nav-item">
                                        <a href="{{ route('committees.index') }}"
                                        class="nav-link nav-link-sub {{ request()->routeIs('committees.*') ? 'active' : '' }}">
                                            <i class="bi bi-diagram-2"></i>
                                            <span>Comités</span>
                                        </a>
                                    </li>

                                    {{-- Salles de réunion (redirige vers rooms.index) --}}
                                    <li class="nav-item">
                                        <a href="{{ route('rooms.index') }}"
                                        class="nav-link nav-link-sub {{ request()->routeIs('rooms.*') ? 'active' : '' }}">
                                            <i class="bi bi-building"></i>
                                            <span>Salles de réunion</span>
                                        </a>
                                    </li>

                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>

                {{-- Bloc Administration / Journal --}}
                <div class="mt-4">
                    <div class="nav-section-label mb-1">Administration</div>
                    <ul class="nav nav-pills flex-column gap-1 nav-submenu">
                        @can('users.view')
                            <li class="nav-item">
                                <a href="{{ route('users.index') }}"
                                class="nav-link nav-link-sub {{ request()->routeIs('users.*') ? 'active' : '' }}">
                                    <i class="bi bi-people"></i>
                                    <span>Utilisateurs</span>
                                </a>
                            </li>
                        @endcan

                        {{-- Rôles et Permissions - Visible uniquement pour Super-Admin --}}
                        @if(auth()->user() && auth()->user()->hasRole('super-admin'))
                            <li class="nav-item">
                                <a href="{{ route('roles.index') }}"
                                class="nav-link nav-link-sub {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                                    <i class="bi bi-shield-check"></i>
                                    <span>Rôles et Permissions</span>
                                </a>
                            </li>
                        @endif

                        

                        @can('document_types.view')
                            <li class="nav-item">
                                <a href="{{ route('document-types.index') }}"
                                class="nav-link nav-link-sub {{ request()->routeIs('document-types.*') ? 'active' : '' }}">
                                    <i class="bi bi-file-earmark-text"></i>
                                    <span>Types de documents</span>
                                </a>
                            </li>
                        @endcan

                        <li class="nav-item">
                            <a href="{{ route('reports.index') }}"
                            class="nav-link nav-link-sub {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                                <i class="bi bi-graph-up"></i>
                                <span>Rapports et statistiques</span>
                            </a>
                        </li>

                        @can('audit_logs.view')
                            <li class="nav-item">
                                <a href="{{ route('audit-logs.index') }}"
                                class="nav-link nav-link-sub {{ request()->routeIs('audit-logs.*') ? 'active' : '' }}">
                                    <i class="bi bi-clipboard-data"></i>
                                    <span>Journal des actions</span>
                                </a>
                            </li>
                        @endcan

                        @can('manage settings')
                            <li class="nav-item">
                                <a href="{{ route('settings.index') }}"
                                class="nav-link nav-link-sub {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                                    <i class="bi bi-gear"></i>
                                    <span>Paramètres généraux</span>
                                </a>
                            </li>
                        @endcan

                        <li class="nav-item">
                            <a href="{{ route('raci.index') }}"
                            class="nav-link nav-link-sub {{ request()->routeIs('raci.*') ? 'active' : '' }}">
                                <i class="bi bi-diagram-3"></i>
                                <span>Matrice RACI</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-top border-white border-opacity-20 p-3 small text-white-50">
                <div>Commission de la CEEAC</div>
                <div>Système de Gestion des Réunions Statutaires</div>
            </div>
        </nav>

            {{-- ZONE PRINCIPALE --}}
            <div class="flex-grow-1 d-flex flex-column">
                {{-- TOPBAR --}}
                <nav class="navbar navbar-expand-md navbar-light bg-white sgrs-topbar">
                    <div class="container-fluid">
                        {{-- Bouton burger (mobile) --}}
                        <button class="navbar-toggler d-md-none"
                                type="button"
                                data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvasSidebar"
                                aria-controls="offcanvasSidebar">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        {{-- Branding mobile --}}
                        <span class="navbar-brand d-md-none">
                            <img src="{{ asset('images/ceeac-logo.png') }}"
                                 alt="CEEAC"
                                 width="28"
                                 height="28"
                                 class="me-1 rounded-circle">
                            SGRS-CEEAC
                        </span>

                        <div class="ms-auto d-flex align-items-center gap-3">
                            {{-- Notifications (cloche) --}}
                            @auth
                                <div class="dropdown position-relative">
                                    <button class="btn btn-sm btn-outline-secondary rounded-circle"
                                            type="button"
                                            id="notificationsDropdown"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                        <i class="bi bi-bell"></i>
                                        @php
                                            $unreadCount = auth()->user()->unreadNotifications()->count();
                                        @endphp
                                        <span id="notification-badge-count"
                                              class="badge bg-danger rounded-pill badge-notification {{ $unreadCount ? '' : 'd-none' }}">
                                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                        </span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end"
                                        aria-labelledby="notificationsDropdown"
                                        style="min-width: 280px;">
                                        <li class="dropdown-header small fw-semibold">
                                            Notifications
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <div id="notification-dropdown-list"
                                             style="max-height: 300px; overflow-y: auto;">
                                            @forelse(auth()->user()->unreadNotifications()->take(10) as $notif)
                                                <li>
                                                    <a href="{{ route('meetings.show', $notif->data['meeting_id'] ?? '#') }}"
                                                       class="dropdown-item small">
                                                        <div class="fw-semibold">
                                                            {{ $notif->data['title'] ?? 'Rappel de réunion' }}
                                                        </div>
                                                        <div class="text-muted">
                                                            {{ $notif->data['meeting_type'] ?? 'Réunion statutaire' }}
                                                            @if(!empty($notif->data['room']))
                                                                • {{ $notif->data['room'] }}
                                                            @endif
                                                        </div>
                                                    </a>
                                                </li>
                                            @empty
                                                <li>
                                                    <span class="dropdown-item small text-muted">
                                                        Aucune notification non lue.
                                                    </span>
                                                </li>
                                            @endforelse
                                        </div>
                                    </ul>
                                </div>
                            @endauth

                            {{-- Menu utilisateur --}}
                            @auth
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle d-flex align-items-center"
                                            type="button"
                                            id="userMenuDropdown"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                        <span class="me-2">
                                            <i class="bi bi-person-circle"></i>
                                        </span>
                                        <span class="d-none d-sm-inline">
                                            {{ auth()->user()->name }}
                                        </span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end"
                                        aria-labelledby="userMenuDropdown">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                                <i class="bi bi-person-gear me-2"></i> Profil
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" class="dropdown-item">
                                                    <i class="bi bi-box-arrow-right me-2"></i>
                                                    Déconnexion
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            @endauth
                        </div>
                    </div>
                </nav>

                {{-- SIDEBAR OFFCANVAS (mobile) --}}
                <div class="offcanvas offcanvas-start"
                    tabindex="-1"
                    id="offcanvasSidebar"
                    aria-labelledby="offcanvasSidebarLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasSidebarLabel">
                            <img src="{{ asset('images/ceeac-logo.png') }}"
                                alt="CEEAC"
                                width="28"
                                height="28"
                                class="me-2 rounded-circle">
                            SGRS-CEEAC
                        </h5>
                        <button type="button"
                                class="btn-close text-reset"
                                data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="nav nav-pills flex-column gap-1 mb-3">
                            <li class="nav-item">
                                <a href="{{ route('dashboard') }}"
                                class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                    <i class="bi bi-speedometer"></i>
                                    <span>Tableau de bord</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('meetings.index') }}"
                                class="nav-link {{ request()->routeIs('meetings.*') ? 'active' : '' }}">
                                    <i class="bi bi-people-fill"></i>
                                    <span>Réunions</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('calendar.index') }}"
                                class="nav-link {{ request()->routeIs('calendar.*') ? 'active' : '' }}">
                                    <i class="bi bi-calendar-check"></i>
                                    <span>Calendrier</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('documents.index') }}"
                                class="nav-link {{ request()->routeIs('documents.*') ? 'active' : '' }}">
                                    <i class="bi bi-folder2-open"></i>
                                    <span>Documents</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('participants.index') }}"
                                class="nav-link {{ request()->routeIs('participants.*') ? 'active' : '' }}">
                                    <i class="bi bi-person-lines-fill"></i>
                                    <span>Participants</span>
                                </a>
                            </li>

                            @can('delegations.view')
                                <li class="nav-item">
                                    <a href="{{ route('delegations.index') }}"
                                    class="nav-link {{ request()->routeIs('delegations.*') ? 'active' : '' }}">
                                        <i class="bi bi-flag"></i>
                                        <span>Délégations</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>

                        {{-- Sous-menu Configuration des réunions (mobile) --}}
                        <div class="mb-3">
                            <div class="nav-section-label mb-1">Configuration des réunions</div>
                            <div class="nav nav-pills flex-column nav-submenu">
                                <button class="nav-link d-flex justify-content-between align-items-center
                                            {{ request()->routeIs('meeting-types.*') || request()->routeIs('committees.*') || request()->routeIs('rooms.*') ? 'active' : '' }}"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#offcanvasSubmenuMeetingsConfig"
                                        aria-expanded="{{ request()->routeIs('meeting-types.*') || request()->routeIs('committees.*') || request()->routeIs('rooms.*') ? 'true' : 'false' }}"
                                        aria-controls="offcanvasSubmenuMeetingsConfig">
                                    <span class="d-flex align-items-center gap-2">
                                        <i class="bi bi-sliders"></i>
                                        <span>Paramétrage</span>
                                    </span>
                                    <i class="bi bi-chevron-down small"></i>
                                </button>

                                <div class="collapse {{ request()->routeIs('meeting-types.*') || request()->routeIs('committees.*') || request()->routeIs('rooms.*') ? 'show' : '' }}"
                                    id="offcanvasSubmenuMeetingsConfig">
                                    <ul class="nav flex-column mt-1">
                                        <li class="nav-item">
                                            <a href="{{ route('meeting-types.index') }}"
                                            class="nav-link nav-link-sub {{ request()->routeIs('meeting-types.*') ? 'active' : '' }}">
                                                <i class="bi bi-diagram-3"></i>
                                                <span>Types de réunions</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('committees.index') }}"
                                            class="nav-link nav-link-sub {{ request()->routeIs('committees.*') ? 'active' : '' }}">
                                                <i class="bi bi-diagram-2"></i>
                                                <span>Comités</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('rooms.index') }}"
                                            class="nav-link nav-link-sub {{ request()->routeIs('rooms.*') ? 'active' : '' }}">
                                                <i class="bi bi-building"></i>
                                                <span>Salles de réunion</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- Bloc Administration mobile --}}
                        <div>
                            <div class="nav-section-label mb-1">Administration</div>
                            <ul class="nav nav-pills flex-column nav-submenu">
                                @can('audit_logs.view')
                                    <li class="nav-item">
                                        <a href="{{ route('audit-logs.index') }}"
                                        class="nav-link nav-link-sub {{ request()->routeIs('audit-logs.*') ? 'active' : '' }}">
                                            <i class="bi bi-clipboard-data"></i>
                                            <span>Journal des actions</span>
                                        </a>
                                    </li>
                                @endcan

                                @can('manage settings')
                                    <li class="nav-item">
                                        <a href="{{ route('settings.index') }}"
                                        class="nav-link nav-link-sub {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                                            <i class="bi bi-gear"></i>
                                            <span>Paramètres généraux</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- CONTENU PRINCIPAL --}}
                <main class="flex-grow-1">
                    <div class="container-fluid py-4">
                        {{-- Alertes globales --}}
                        @includeWhen(View::exists('partials.alerts'), 'partials.alerts')

                        @yield('content')
                    </div>
                </main>
            </div>
        </div>
    </div>

    {{-- Conteneur global pour les toasts (notifications temps réel) --}}
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1080;">
        <div id="notification-toast-container" class="toast-container"></div>
    </div>

    {{-- Scripts spécifiques (Chart.js, Echo, etc.) injectés par les vues --}}
    @stack('scripts')
  {{-- Conteneur global pour les toasts (notifications temps réel) --}}
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1080;">
        <div id="notification-toast-container" class="toast-container"></div>
    </div>

    {{-- Scripts spécifiques (Chart.js, Echo, etc.) injectés par les vues --}}
    @stack('scripts')

    {{-- Bootstrap JS (bundle : Popper + Bootstrap) --}}
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"
    ></script>
</body>
</html>
