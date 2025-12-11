@extends('layouts.app')

@section('title', 'Accueil institutionnel')

@section('content')
    {{-- En-tête institutionnel avec navigation interne --}}
    <header class="mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body py-3 py-md-4">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <img src="{{ asset('images/logo-ceeac.png') }}"
                             alt="Logo de la CEEAC"
                             width="56"
                             height="56"
                             class="rounded-circle bg-white p-1 border">
                        <div>
                            <div class="small text-muted text-uppercase fw-semibold">
                                Commission de la Communauté Économique des États de l’Afrique Centrale
                            </div>
                            <div class="fw-bold fs-5">
                                SGRS-CEEAC – Système de Gestion des Réunions Statutaires
                            </div>
                        </div>
                    </div>
                    <nav class="d-flex flex-wrap align-items-center gap-3">
                        <a href="#presentation" class="text-decoration-none text-muted small fw-semibold">Présentation</a>
                        <a href="#objectifs" class="text-decoration-none text-muted small fw-semibold">Objectifs</a>
                        <a href="#modules" class="text-decoration-none text-muted small fw-semibold">Modules</a>
                        <a href="#processus" class="text-decoration-none text-muted small fw-semibold">Processus</a>
                        <a href="#cadre-juridique" class="text-decoration-none text-muted small fw-semibold">Cadre juridique</a>
                        <a href="#support" class="text-decoration-none text-muted small fw-semibold">Support</a>
                        <a href="{{ route('login') }}" class="btn btn-sm btn-primary ms-md-2">
                            <i class="bi bi-box-arrow-in-right me-1"></i>
                            Connexion sécurisée
                        </a>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    {{-- Section Hero --}}
    <section class="mb-5">
        <div class="row g-4 align-items-center">
            <div class="col-lg-7">
                <div class="mb-3">
                    <span class="badge bg-primary-subtle text-primary fw-semibold text-uppercase small">
                        Plateforme institutionnelle
                    </span>
                </div>
                <h1 class="fw-bold mb-3" style="font-size: 2.2rem;">
                    SGRS-CEEAC
                </h1>
                <p class="lead text-muted mb-4">
                    Plateforme institutionnelle de gestion des réunions statutaires de la Commission de la CEEAC.
                    Le SGRS-CEEAC permet de planifier, organiser, suivre et archiver les réunions des organes
                    statutaires de la Communauté, au service de la gouvernance et de l’intégration régionale.
                </p>
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-shield-lock me-1"></i>
                        Connexion sécurisée
                    </a>
                    <a href="#presentation" class="btn btn-outline-primary btn-lg">
                        <i class="bi bi-eye me-1"></i>
                        Découvrir la plateforme
                    </a>
                </div>
                <div class="d-flex flex-wrap gap-3 small text-muted">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-people text-primary"></i>
                        <span>Conférence des Chefs d’État, Conseil des Ministres, Comités spécialisés…</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-lock text-primary"></i>
                        <span>Accès réservé aux utilisateurs habilités de la Commission</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h2 class="h5 fw-semibold mb-3">
                            Interface moderne, sécurisée et centralisée
                        </h2>
                        <p class="text-muted small mb-3">
                            Le SGRS-CEEAC offre une interface unique pour le pilotage des réunions statutaires :
                            calendrier consolidé, gestion des délégations, documents officiels, validation institutionnelle
                            et notifications intégrées.
                        </p>
                        <ul class="list-unstyled small mb-0">
                            <li class="d-flex align-items-start gap-2 mb-2">
                                <i class="bi bi-check-circle-fill text-success mt-1"></i>
                                <span>Vue unifiée des réunions statutaires et de leurs états d’avancement.</span>
                            </li>
                            <li class="d-flex align-items-start gap-2 mb-2">
                                <i class="bi bi-check-circle-fill text-success mt-1"></i>
                                <span>Gestion structurée des délégations, organes et comités d’organisation.</span>
                            </li>
                            <li class="d-flex align-items-start gap-2 mb-2">
                                <i class="bi bi-check-circle-fill text-success mt-1"></i>
                                <span>Dépôt, versionnage et validation des documents statutaires de travail.</span>
                            </li>
                            <li class="d-flex align-items-start gap-2">
                                <i class="bi bi-check-circle-fill text-success mt-1"></i>
                                <span>Journalisation des actions et traçabilité complète (AuditLog).</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Présentation du système --}}
    <section id="presentation" class="mb-5">
        <div class="row g-4">
            <div class="col-lg-7">
                <h2 class="h4 fw-bold mb-3">Présentation du Système</h2>
                <p class="text-muted">
                    Le SGRS-CEEAC est l’outil officiel de la Commission de la CEEAC pour gérer l’ensemble du cycle
                    de vie des réunions statutaires des organes de décision (Conférence des Chefs d’État et de
                    Gouvernement, Conseil des Ministres, Comités techniques, etc.).
                </p>
                <p class="text-muted">
                    Il s’inscrit dans la dynamique de modernisation de la gouvernance, de la dématérialisation
                    des processus et de la transformation digitale de la Commission, en offrant une plateforme
                    unique et sécurisée pour la préparation, la tenue et le suivi des réunions.
                </p>
            </div>
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h3 class="h6 fw-semibold mb-3">Périmètre institutionnel</h3>
                        <ul class="list-unstyled small text-muted mb-0">
                            <li class="d-flex align-items-start gap-2 mb-2">
                                <i class="bi bi-building text-primary mt-1"></i>
                                <span>Commissions, directions et services du Secrétariat Général de la CEEAC.</span>
                            </li>
                            <li class="d-flex align-items-start gap-2 mb-2">
                                <i class="bi bi-flag text-primary mt-1"></i>
                                <span>Délégations des États membres, partenaires techniques et financiers.</span>
                            </li>
                            <li class="d-flex align-items-start gap-2">
                                <i class="bi bi-diagram-3 text-primary mt-1"></i>
                                <span>Comités d’organisation, comités techniques et instances de validation.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Objectifs institutionnels --}}
    <section id="objectifs" class="mb-5">
        <h2 class="h4 fw-bold mb-3">Objectifs du SGRS-CEEAC</h2>
        <div class="row g-4">
            <div class="col-md-6 col-xl-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h3 class="h6 fw-semibold mb-2">
                            Moderniser la gouvernance institutionnelle
                        </h3>
                        <p class="small text-muted mb-0">
                            Digitaliser le cycle complet des réunions statutaires pour renforcer l’efficacité,
                            la transparence et la coordination entre les organes de décision de la CEEAC.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h3 class="h6 fw-semibold mb-2">
                            Centraliser les informations et documents
                        </h3>
                        <p class="small text-muted mb-0">
                            Assurer un référentiel unique pour les ordres du jour, rapports, procès-verbaux,
                            projets de décision et documents logistiques associés aux réunions.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h3 class="h6 fw-semibold mb-2">
                            Simplifier l’organisation logistique
                        </h3>
                        <p class="small text-muted mb-0">
                            Faciliter la réservation des salles, la gestion du pays hôte, le cahier des charges
                            logistique et la coordination avec les comités d’organisation.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h3 class="h6 fw-semibold mb-2">
                            Améliorer la traçabilité des décisions
                        </h3>
                        <p class="small text-muted mb-0">
                            Suivre l’historique des réunions, des décisions, des validations documentaires
                            et des engagements, avec un journal d’audit détaillé.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h3 class="h6 fw-semibold mb-2">
                            Renforcer la coordination régionale
                        </h3>
                        <p class="small text-muted mb-0">
                            Offrir une vue consolidée des réunions, participants et délégations, afin de
                            renforcer la coordination entre la Commission, les États membres et les partenaires.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h3 class="h6 fw-semibold mb-2">
                            Sécuriser l’accès et les échanges
                        </h3>
                        <p class="small text-muted mb-0">
                            Garantir un contrôle d’accès fin par rôles et permissions, avec authentification,
                            notifications sécurisées et gestion des habilitations.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Modules principaux --}}
    <section id="modules" class="mb-5">
        <h2 class="h4 fw-bold mb-3">Modules principaux</h2>
        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-primary-subtle text-primary">
                                Réunions
                            </span>
                        </div>
                        <h3 class="h6 fw-semibold mb-2">Gestion des réunions</h3>
                        <p class="small text-muted mb-0">
                            Création, modification, annulation et archivage des réunions statutaires,
                            avec gestion du type de réunion, du comité d’organisation, de la salle et du pays hôte.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-primary-subtle text-primary">
                                Délégations
                            </span>
                        </div>
                        <h3 class="h6 fw-semibold mb-2">Gestion des participants et délégations</h3>
                        <p class="small text-muted mb-0">
                            Modèle centré sur les délégations institutionnelles, avec gestion des membres,
                            chefs de délégation, rôles, confirmations de participation et suivi des présences.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-primary-subtle text-primary">
                                Documents
                            </span>
                        </div>
                        <h3 class="h6 fw-semibold mb-2">Gestion documentaire</h3>
                        <p class="small text-muted mb-0">
                            Dépôt, versionnage et classification des documents (ordres du jour, rapports, PV,
                            notes, projets de décision), avec contrôle d’accès et téléchargement sécurisé.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-primary-subtle text-primary">
                                Cahier des charges
                            </span>
                        </div>
                        <h3 class="h6 fw-semibold mb-2">Gestion du cahier des charges logistique</h3>
                        <p class="small text-muted mb-0">
                            Module Terms of Reference pour formaliser le cahier des charges entre la CEEAC
                            et le pays hôte : responsabilités, partage des charges, versionnage et signatures.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-primary-subtle text-primary">
                                Notifications
                            </span>
                        </div>
                        <h3 class="h6 fw-semibold mb-2">Notifications et workflows de validation</h3>
                        <p class="small text-muted mb-0">
                            Notifications internes et emails pour les invitations, rappels, réponses de
                            participation, validations et rejets de documents, en lien avec la chaîne
                            institutionnelle (Protocole, SG, Présidence).
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-primary-subtle text-primary">
                                Suivi
                            </span>
                        </div>
                        <h3 class="h6 fw-semibold mb-2">Tableaux de bord et rapports</h3>
                        <p class="small text-muted mb-0">
                            Indicateurs clés, statistiques sur les réunions, participants et documents,
                            avec exports PDF/Excel pour le reporting institutionnel et le suivi des décisions.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Processus institutionnel --}}
    <section id="processus" class="mb-5">
        <h2 class="h4 fw-bold mb-3">Processus de gestion d’une réunion statutaire</h2>
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <ol class="mb-0 ps-3">
                    <li class="mb-2">
                        <strong>Programmation et création de la réunion</strong> :
                        définition de l’organe, du type de réunion, de la période indicative et du comité d’organisation.
                    </li>
                    <li class="mb-2">
                        <strong>Définition du pays hôte et du cahier des charges</strong> :
                        élaboration, validation interne et signature du cahier des charges avec le pays hôte.
                    </li>
                    <li class="mb-2">
                        <strong>Gestion des participants et envoi des invitations</strong> :
                        constitution des délégations, convocation des participants, rappel et relances automatiques.
                    </li>
                    <li class="mb-2">
                        <strong>Mise à disposition des documents de travail</strong> :
                        dépôt des ordres du jour, notes, rapports et projets de décision, avec validation multi-niveaux.
                    </li>
                    <li class="mb-2">
                        <strong>Tenue de la réunion</strong> :
                        suivi des présences, gestion logistique, mise à jour en temps réel des informations pratiques.
                    </li>
                    <li class="mb-2">
                        <strong>Élaboration et validation des procès-verbaux et rapports</strong> :
                        versionnage, validation institutionnelle et diffusion des documents finaux.
                    </li>
                    <li>
                        <strong>Archivage et suivi des décisions</strong> :
                        archivage structuré des réunions et documents, suivi des engagements et indicateurs de mise en œuvre.
                    </li>
                </ol>
            </div>
        </div>
    </section>

    {{-- Cadre juridique et conformité --}}
    <section id="cadre-juridique" class="mb-5">
        <h2 class="h4 fw-bold mb-3">Cadre juridique et conformité</h2>
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <p class="text-muted mb-2">
                    Le SGRS-CEEAC s’inscrit dans le cadre des textes organiques, règlements intérieurs
                    et décisions des organes de la Communauté. Il accompagne la mise en œuvre des
                    réformes de gouvernance et des dispositifs de suivi des décisions statutaires.
                </p>
                <p class="text-muted mb-2">
                    Le système respecte les règles de confidentialité et de sécurité en vigueur au sein
                    de la Commission : authentification sécurisée, gestion des rôles et permissions,
                    journalisation des actions (AuditLog) et contrôle d’accès aux documents sensibles.
                </p>
                <p class="text-muted mb-0">
                    L’accès à la plateforme est strictement réservé aux utilisateurs dûment habilités
                    par la Commission de la CEEAC et soumis aux procédures internes de gestion des comptes.
                </p>
            </div>
        </div>
    </section>

    {{-- Support et contact --}}
    <section id="support" class="mb-5">
        <h2 class="h4 fw-bold mb-3">Support et assistance</h2>
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <p class="text-muted mb-2">
                    Le support fonctionnel et technique du SGRS-CEEAC est assuré par la Direction des
                    Systèmes d’Information (DSI) de la Commission de la CEEAC, en lien avec les services
                    métiers concernés par l’organisation des réunions statutaires.
                </p>
                <p class="text-muted mb-0">
                    Pour toute assistance, veuillez contacter l’équipe DSI via les canaux internes habituels
                    (messagerie institutionnelle, support technique ou points focaux désignés).
                </p>
            </div>
        </div>
    </section>

    {{-- Pied de page institutionnel --}}
    <footer class="pb-2">
        <div class="card border-0 shadow-sm">
            <div class="card-body py-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 small">
                <div>
                    © {{ date('Y') }} Commission de la CEEAC – Tous droits réservés.
                    <span class="text-muted">
                        Accès réservé aux utilisateurs autorisés de la Commission de la CEEAC.
                    </span>
                </div>
                <div class="text-muted">
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="text-decoration-none">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Connexion à la plateforme
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </footer>
@endsection
