@extends('layouts.app')

@section('title', 'Matrice RACI')

@section('content')
<div class="container-fluid py-4">
    {{-- En-tête --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="page-title">
                <i class="bi bi-diagram-3 text-primary"></i>
                Matrice RACI - Responsabilités
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
                    <li class="breadcrumb-item active">Matrice RACI</li>
                </ol>
            </nav>
        </div>
    </div>

    {{-- Légende --}}
    <div class="modern-card mb-4">
        <div class="modern-card-header">
            <h5 class="mb-0">
                <i class="bi bi-info-circle"></i>
                Légende RACI
            </h5>
        </div>
        <div class="modern-card-body">
            <div class="row g-3">
                @foreach($legend as $code => $info)
                    <div class="col-md-3">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-{{ $info['color'] }} me-2" style="font-size: 1rem; padding: 0.5rem 0.75rem;">
                                {{ $code }}
                            </span>
                            <div>
                                <strong>{{ $info['label'] }}</strong>
                                <br>
                                <small class="text-muted">{{ $info['description'] }}</small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Matrice RACI --}}
    <div class="modern-card">
        <div class="modern-card-header">
            <h5 class="mb-0">
                <i class="bi bi-table"></i>
                Matrice des Responsabilités par Processus
            </h5>
        </div>
        <div class="modern-card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern table-bordered mb-0">
                    <thead>
                        <tr>
                            <th style="min-width: 250px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                                Processus clé
                            </th>
                            @foreach($stakeholders as $stakeholder)
                                <th class="text-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                                    {{ $stakeholder }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($raciMatrix as $process => $roles)
                            <tr>
                                <td class="fw-bold" style="background: #f8fafc;">
                                    {{ $process }}
                                </td>
                                @foreach($stakeholders as $stakeholder)
                                    @php
                                        $code = $roles[$stakeholder] ?? '-';
                                        $info = $legend[$code] ?? null;
                                        $color = $info['color'] ?? 'secondary';
                                    @endphp
                                    <td class="text-center align-middle" style="font-size: 1.2rem; font-weight: bold;">
                                        @if($code !== '-')
                                            <span class="badge bg-{{ $color }}" 
                                                  data-bs-toggle="tooltip" 
                                                  data-bs-placement="top"
                                                  title="{{ $info['label'] ?? '' }} - {{ $info['description'] ?? '' }}">
                                                {{ $code }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Principes de gouvernance --}}
    <div class="modern-card mt-4">
        <div class="modern-card-header">
            <h5 class="mb-0">
                <i class="bi bi-shield-check"></i>
                Principes de gouvernance
            </h5>
        </div>
        <div class="modern-card-body">
            <ul class="list-unstyled mb-0">
                <li class="mb-2">
                    <i class="bi bi-check-circle text-success me-2"></i>
                    Chaque processus possède au moins un <strong>Responsable (R)</strong> et un seul <strong>Approbateur (A)</strong>.
                </li>
                <li class="mb-2">
                    <i class="bi bi-check-circle text-success me-2"></i>
                    Les rôles <strong>Consultés (C)</strong> garantissent la collaboration entre Directions.
                </li>
                <li class="mb-2">
                    <i class="bi bi-check-circle text-success me-2"></i>
                    Les rôles <strong>Informés (I)</strong> assurent une bonne circulation de l'information.
                </li>
                <li class="mb-2">
                    <i class="bi bi-check-circle text-success me-2"></i>
                    La <strong>DSI</strong> agit comme maître d'œuvre technique ; le <strong>Protocole</strong> et le <strong>SG</strong> assument la validation institutionnelle ; le <strong>Président</strong> reste l'autorité ultime.
                </li>
            </ul>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Initialiser les tooltips Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>
@endpush
@endsection

