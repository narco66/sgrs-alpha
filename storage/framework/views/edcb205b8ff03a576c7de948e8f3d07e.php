

<?php $__env->startSection('title', 'SGRS-CEEAC – Système de Gestion des Réunions Statutaires'); ?>

<?php $__env->startSection('content'); ?>

<section class="py-4 py-md-5">
    <div class="row align-items-center g-4">
        
        <div class="col-lg-7">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center gap-2">
                    <img src="<?php echo e(asset('images/logo-ceeac.png')); ?>"
                         alt="CEEAC"
                         width="40"
                         height="40"
                         class="rounded-circle bg-white p-1 shadow-sm">
                    <div>
                        <div class="fw-bold text-dark">SGRS‑CEEAC</div>
                        <div class="text-muted small">Réunions statutaires de la CEEAC</div>
                    </div>
                </div>
                <nav class="d-flex align-items-center gap-2">
                    <a href="<?php echo e(route('home')); ?>" class="btn btn-sm btn-outline-secondary">
                        Accueil
                    </a>
                    <?php if(Route::has('login')): ?>
                        <?php if(auth()->guard()->check()): ?>
                            <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-sm btn-primary">
                                Accéder à l’application
                            </a>
                        <?php else: ?>
                            <a href="<?php echo e(route('login')); ?>" class="btn btn-sm btn-outline-primary">
                                Se connecter
                            </a>
                            <?php if(Route::has('register')): ?>
                                <a href="<?php echo e(route('register')); ?>" class="btn btn-sm btn-outline-secondary d-none d-md-inline-flex">
                                    S’inscrire
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </nav>
            </div>

            
            <h1 class="display-5 fw-bold text-dark mb-3">
                SGRS‑CEEAC – Système de Gestion des Réunions Statutaires
            </h1>
            <p class="lead text-muted mb-4">
                Plateforme numérique de la Commission de la CEEAC pour planifier, organiser et suivre
                l’ensemble des réunions statutaires, avec une traçabilité renforcée des décisions
                et une meilleure coordination institutionnelle.
            </p>

            
            <div class="d-flex flex-wrap gap-3 mb-4">
                <?php if(Route::has('login')): ?>
                    <?php if(auth()->guard()->check()): ?>
                        <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-modern btn-modern-primary btn-lg">
                            <i class="bi bi-box-arrow-in-right me-1"></i>
                            Accéder à l’application
                        </a>
                    <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" class="btn btn-modern btn-modern-primary btn-lg">
                            <i class="bi bi-box-arrow-in-right me-1"></i>
                            Se connecter
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
                <a href="#features" class="btn btn-modern btn-modern-secondary btn-lg">
                    <i class="bi bi-play-circle me-1"></i>
                    Découvrir l’application
                </a>
            </div>

            
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="small text-muted text-uppercase fw-semibold mb-1">Digitalisation</div>
                    <p class="text-muted small mb-0">
                        Un processus de bout en bout pour préparer, convoquer et suivre les réunions
                        statutaires des organes de la CEEAC.
                    </p>
                </div>
                <div class="col-md-4">
                    <div class="small text-muted text-uppercase fw-semibold mb-1">Traçabilité</div>
                    <p class="text-muted small mb-0">
                        Historique complet des décisions, documents et participants pour chaque session.
                    </p>
                </div>
                <div class="col-md-4">
                    <div class="small text-muted text-uppercase fw-semibold mb-1">Gouvernance</div>
                    <p class="text-muted small mb-0">
                        Un outil au service de la Commission pour renforcer la gouvernance et la prise de décision.
                    </p>
                </div>
            </div>
        </div>

        
        <div class="col-lg-5">
            <div class="modern-card h-100">
                <div class="modern-card-body">
                    <div class="text-muted small text-uppercase fw-semibold mb-2">
                        Vue d’ensemble
                    </div>
                    <p class="text-muted small mb-3">
                        Illustration indicative de l’interface SGRS‑CEEAC (agenda des réunions, salles,
                        documents et suivi des décisions). Un visuel institutionnel pourra être intégré
                        ici par la DSI.
                    </p>
                    <div class="bg-light rounded-4 p-3 position-relative overflow-hidden">
                        <div class="d-flex flex-column gap-2">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-primary">Calendrier des réunions</span>
                                <span class="text-muted small">
                                    <i class="bi bi-building me-1"></i>CEEAC
                                </span>
                            </div>
                            <div class="d-flex gap-2 mb-2">
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-people-fill me-1"></i>Conseil des Ministres
                                </span>
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-diagram-3 me-1"></i>Comités
                                </span>
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-door-open me-1"></i>Salles
                                </span>
                            </div>
                            <div class="bg-white rounded-3 p-2 shadow-sm">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div class="fw-semibold small">
                                        Réunion du Conseil des Ministres
                                    </div>
                                    <span class="badge bg-success">Planifiée</span>
                                </div>
                                <div class="text-muted small mb-1">
                                    <i class="bi bi-calendar-event me-1"></i>15–17 juillet &middot;
                                    <i class="bi bi-geo-alt ms-1 me-1"></i>Libreville
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-primary" style="width: 65%;"></div>
                                </div>
                            </div>
                            <div class="bg-white rounded-3 p-2 shadow-sm">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div class="fw-semibold small">
                                        Comité des Experts
                                    </div>
                                    <span class="badge bg-warning">En préparation</span>
                                </div>
                                <div class="text-muted small">
                                    <i class="bi bi-file-earmark-text me-1"></i>
                                    Cahier des charges et documents logistiques en cours de validation.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<section id="about" class="my-4 my-md-5">
    <div class="modern-card">
        <div class="modern-card-body">
            <div class="row g-4 align-items-center">
                <div class="col-md-7">
                    <h2 class="h4 fw-bold mb-3">À propos du projet SGRS‑CEEAC</h2>
                    <p class="text-muted mb-3">
                        Le Système de Gestion des Réunions Statutaires (SGRS‑CEEAC) est une
                        plateforme développée pour la Commission de la CEEAC afin de
                        <strong>digitaliser la gestion des réunions statutaires</strong> des organes
                        de décision (Conférences, Conseils, Comités, Groupes d’experts).
                    </p>
                    <p class="text-muted mb-0">
                        SGRS‑CEEAC accompagne le Secrétariat Général et les Directions techniques
                        dans la planification des sessions, la gestion des ordres du jour,
                        des participants, des salles et des documents, tout en assurant
                        une <strong>traçabilité complète des décisions</strong> et de leur suivi.
                    </p>
                </div>
                <div class="col-md-5">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex align-items-start mb-2">
                            <span class="text-primary me-2">
                                <i class="bi bi-check-circle-fill"></i>
                            </span>
                            <span class="text-muted small">
                                Support structuré aux instances de décision de la CEEAC.
                            </span>
                        </li>
                        <li class="d-flex align-items-start mb-2">
                            <span class="text-primary me-2">
                                <i class="bi bi-check-circle-fill"></i>
                            </span>
                            <span class="text-muted small">
                                Centralisation des informations stratégiques des réunions.
                            </span>
                        </li>
                        <li class="d-flex align-items-start">
                            <span class="text-primary me-2">
                                <i class="bi bi-check-circle-fill"></i>
                            </span>
                            <span class="text-muted small">
                                Amélioration de la coordination entre services et États membres.
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>


<section id="features" class="my-4 my-md-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="h4 fw-bold mb-1">Fonctionnalités clés</h2>
            <p class="text-muted small mb-0">
                Un ensemble de modules intégrés pour couvrir tout le cycle de vie des réunions statutaires.
            </p>
        </div>
    </div>

    <div class="row g-3 g-md-4">
        <div class="col-md-4">
            <div class="modern-card h-100">
                <div class="modern-card-body">
                    <div class="kpi-icon mb-3" style="background: rgba(102,126,234,0.08);">
                        <i class="bi bi-calendar-check text-primary"></i>
                    </div>
                    <h3 class="h6 fw-bold mb-2">Gestion des réunions</h3>
                    <p class="text-muted small mb-0">
                        Planification des réunions statutaires, gestion des ordres du jour,
                        des statuts (brouillon, planifiée, en cours, terminée, annulée) et
                        suivi du calendrier institutionnel.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="modern-card h-100">
                <div class="modern-card-body">
                    <div class="kpi-icon mb-3" style="background: rgba(79,172,254,0.08);">
                        <i class="bi bi-people text-info"></i>
                    </div>
                    <h3 class="h6 fw-bold mb-2">Participants & délégations</h3>
                    <p class="text-muted small mb-0">
                        Gestion des délégations par État membre, des profils des participants,
                        des invitations et de la réponse aux convocations.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="modern-card h-100">
                <div class="modern-card-body">
                    <div class="kpi-icon mb-3" style="background: rgba(67,233,123,0.08);">
                        <i class="bi bi-door-open text-success"></i>
                    </div>
                    <h3 class="h6 fw-bold mb-2">Salles & ressources</h3>
                    <p class="text-muted small mb-0">
                        Gestion centralisée des salles de réunion, de leur capacité,
                        des équipements disponibles et des réservations associées aux sessions.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="modern-card h-100">
                <div class="modern-card-body">
                    <div class="kpi-icon mb-3" style="background: rgba(240,147,251,0.08);">
                        <i class="bi bi-folder2-open text-warning"></i>
                    </div>
                    <h3 class="h6 fw-bold mb-2">Documents de réunion</h3>
                    <p class="text-muted small mb-0">
                        Gestion des notes conceptuelles, rapports, PV, présentations et projets
                        de décision, avec versionning et contrôle d’accès.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="modern-card h-100">
                <div class="modern-card-body">
                    <div class="kpi-icon mb-3" style="background: rgba(0,242,254,0.08);">
                        <i class="bi bi-clock-history text-info"></i>
                    </div>
                    <h3 class="h6 fw-bold mb-2">Historique & traçabilité</h3>
                    <p class="text-muted small mb-0">
                        Historique complet des réunions, décisions, documents et actions
                        pour assurer une traçabilité fiable des processus statutaires.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="modern-card h-100">
                <div class="modern-card-body">
                    <div class="kpi-icon mb-3" style="background: rgba(148,163,184,0.15);">
                        <i class="bi bi-bar-chart-line text-secondary"></i>
                    </div>
                    <h3 class="h6 fw-bold mb-2">Tableaux de bord & rapports</h3>
                    <p class="text-muted small mb-0">
                        Indicateurs de suivi des réunions, de la participation, des documents
                        et du respect des échéances, à destination des organes de gouvernance.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>


<section id="benefits" class="my-4 my-md-5">
    <div class="row g-4">
        <div class="col-md-5">
            <h2 class="h4 fw-bold mb-3">Bénéfices pour la CEEAC</h2>
            <p class="text-muted mb-0">
                SGRS‑CEEAC est conçu comme un outil stratégique pour la Commission, la
                Présidence et les services opérationnels, afin de sécuriser et professionnaliser
                la gestion des réunions statutaires.
            </p>
        </div>
        <div class="col-md-7">
            <div class="row g-3">
                <div class="col-sm-6">
                    <div class="modern-card h-100">
                        <div class="modern-card-body">
                            <h3 class="h6 fw-bold mb-2">Gain de temps & réduction des erreurs</h3>
                            <p class="text-muted small mb-0">
                                Centralisation des informations, automatisation de tâches
                                répétitives et diminution des risques de doublons ou d’oubli.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="modern-card h-100">
                        <div class="modern-card-body">
                            <h3 class="h6 fw-bold mb-2">Meilleure coordination</h3>
                            <p class="text-muted small mb-0">
                                Vue partagée entre la Présidence, le Secrétariat Général,
                                les Directions techniques et les services de protocole.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="modern-card h-100">
                        <div class="modern-card-body">
                            <h3 class="h6 fw-bold mb-2">Centralisation de l’information</h3>
                            <p class="text-muted small mb-0">
                                Un référentiel unique des réunions, des documents et des décisions,
                                accessible de manière sécurisée.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="modern-card h-100">
                        <div class="modern-card-body">
                            <h3 class="h6 fw-bold mb-2">Support à la gouvernance</h3>
                            <p class="text-muted small mb-0">
                                Aide à la prise de décision grâce à des historiques fiables,
                                des rapports consolidés et une meilleure visibilité des engagements.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<section id="audience" class="my-4 my-md-5">
    <div class="modern-card">
        <div class="modern-card-body">
            <div class="row g-4 align-items-start">
                <div class="col-md-4">
                    <h2 class="h4 fw-bold mb-3">Pour qui&nbsp;?</h2>
                    <p class="text-muted mb-0">
                        SGRS‑CEEAC s’adresse aux acteurs impliqués dans la préparation,
                        la tenue et le suivi des réunions statutaires au sein de la CEEAC.
                    </p>
                </div>
                <div class="col-md-8">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="border rounded-3 p-3 h-100 bg-light">
                                <div class="fw-semibold mb-1">
                                    <i class="bi bi-award me-1 text-primary"></i>
                                    Présidence de la Commission
                                </div>
                                <p class="text-muted small mb-0">
                                    Suivi consolidé des réunions statutaires, des décisions
                                    et des engagements pris.
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="border rounded-3 p-3 h-100 bg-light">
                                <div class="fw-semibold mb-1">
                                    <i class="bi bi-building-check me-1 text-primary"></i>
                                    Secrétariat Général
                                </div>
                                <p class="text-muted small mb-0">
                                    Pilotage opérationnel du calendrier, des ordres du jour
                                    et de la préparation logistique des sessions.
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="border rounded-3 p-3 h-100 bg-light">
                                <div class="fw-semibold mb-1">
                                    <i class="bi bi-diagram-3 me-1 text-primary"></i>
                                    Directions techniques & Comités
                                </div>
                                <p class="text-muted small mb-0">
                                    Organisation des travaux techniques, préparation des
                                    documents et suivi des recommandations.
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="border rounded-3 p-3 h-100 bg-light">
                                <div class="fw-semibold mb-1">
                                    <i class="bi bi-gear-wide-connected me-1 text-primary"></i>
                                    Services de protocole & DSI
                                </div>
                                <p class="text-muted small mb-0">
                                    Gestion logistique des salles, des ressources, des accès
                                    utilisateurs et du support technique de la plateforme.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<section id="cta-final" class="my-4 my-md-5">
    <div class="modern-card bg-primary-subtle">
        <div class="modern-card-body d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
            <div>
                <h2 class="h4 fw-bold mb-1 text-primary">
                    Prêt à utiliser SGRS‑CEEAC&nbsp;?
                </h2>
                <p class="text-muted mb-0 small">
                    Connectez-vous pour accéder à l’application ou contactez la DSI pour toute
                    question relative au déploiement et au support.
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <?php if(Route::has('login')): ?>
                    <a href="<?php echo e(route('login')); ?>" class="btn btn-modern btn-modern-primary">
                        <i class="bi bi-box-arrow-in-right me-1"></i>
                        Se connecter
                    </a>
                <?php endif; ?>
                <a href="mailto:dsi@ceeac.int" class="btn btn-modern btn-modern-secondary">
                    <i class="bi bi-envelope me-1"></i>
                    Contacter la DSI / Support
                </a>
            </div>
        </div>
    </div>
</section>


<footer class="mt-4 pt-4 border-top small text-muted">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
        <div>
            Commission de la CEEAC &mdash; Direction des Systèmes d’Information
        </div>
        <div class="d-flex flex-wrap gap-3">
            <span>&copy; <?php echo e(date('Y')); ?> SGRS‑CEEAC</span>
            
            <a href="#" class="text-decoration-none text-muted">Mentions légales</a>
            <a href="#" class="text-decoration-none text-muted">Politique de confidentialité</a>
        </div>
    </div>
</footer>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views/welcome.blade.php ENDPATH**/ ?>