<ul class="sidebar-nav" id="sidebar-nav">

    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"> <!-- collapsed -->
            <i class="bi bi-grid"></i>
            <span>Tableau de bord</span>
        </a>
    </li>
    @if( Auth::user()->typeUser === 0 || Auth::user()->can('voir annonces'))
    <li class="nav-item">
        <a class="nav-link collapsed {{ request()->routeIs('announcement.list') || request()->routeIs('announcement.details') ? 'active' : '' }}" href="{{ route('announcement.list') }}">
            <i class="bi bi-megaphone"></i>
            <span>Annonce</span>
        </a>
    </li>
    @endif

    @if( Auth::user()->typeUser === 0 || Auth::user()->can('voir catégories') || Auth::user()->can('créer catégories') || Auth::user()->can('voir paramètres catégories') || Auth::user()->can('ajouter paramètres catégories'))
    <li class="nav-item">
        <a class="nav-link collapsed {{ request()->is('category_bien*') || request()->is('parameter_category*') ? 'active' : '' }}" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-tag"></i><span>Categorie Bien</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="components-nav" class="nav-content collapse {{ request()->is('category_bien*') || request()->is('parameter_category*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
            @if(Auth::user()->typeUser === 0 || Auth::user()->can('voir catégories'))
            <li>
                <a class="{{ request()->routeIs('category_bien.index') ? 'active' : '' }}" href="{{ route('category_bien.index') }}">
                    <i class="bi bi-circle"></i><span>Liste Categorie</span>
                </a>
            </li>
            @endif
            @if(Auth::user()->typeUser === 0 || Auth::user()->can('créer catégories'))
            <li>
                <a class="{{ request()->routeIs('category_bien.create') ? 'active' : '' }}" href="{{ route('category_bien.create') }}">
                    <i class="bi bi-circle"></i><span>Créer Categorie</span>
                </a>
            </li>
            @endif
            @if(Auth::user()->typeUser === 0 || Auth::user()->can('voir paramètres catégories'))
            <li>
                <a class="{{ request()->routeIs('parameter_category.index') ? 'active' : '' }}" href="{{ route('parameter_category.index') }}">
                    <i class="bi bi-circle"></i><span>Paramètre Categorie</span>
                </a>
            </li>
            @endif
            @if(Auth::user()->typeUser === 0 || Auth::user()->can('ajouter paramètres catégories'))
            <li>
                <a href="{{ route('parameter_category.index', ['showModal' => 'create']) }}">
                    <i class="bi bi-plus-circle"></i><span>Ajouter Paramètre</span>
                </a>
            </li>
            @endif
        </ul>
    </li>
    @endif

    @if( Auth::user()->typeUser === 0 || Auth::user()->can('voir modèles d\'abonnements') || Auth::user()->can('créer modèles d\'abonnements') || Auth::user()->can('voir paramètres modèles d\'abonnements') || Auth::user()->can('ajouter paramètres modèles d\'abonnements') )
    <li class="nav-item">
        <a class="nav-link collapsed {{ request()->is('model_subscription*') || request()->is('parameter_model*') ? 'active' : '' }}" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-credit-card-2-back"></i><span>Modèle Abonnement</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="forms-nav" class="nav-content collapse {{ request()->is('model_subscription*') || request()->is('parameter_model*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
            @if(Auth::user()->typeUser === 0 || Auth::user()->can('voir modèles d\'abonnements'))
            <li>
                <a class="{{ request()->routeIs('model_subscription.index') ? 'active' : '' }}" href="{{ route('model_subscription.index') }}">
                    <i class="bi bi-circle"></i><span>Liste Modèle</span>
                </a>
            </li>
            @endif
            @if(Auth::user()->typeUser === 0 || Auth::user()->can('créer modèles d\'abonnements'))
            <li>
                <a class="{{ request()->routeIs('model_subscription.create') ? 'active' : '' }}" href="{{ route('model_subscription.create') }}">
                    <i class="bi bi-circle"></i><span>Créer Modèle</span>
                </a>
            </li>
            @endif
            @if(Auth::user()->typeUser === 0 || Auth::user()->can('voir paramètres modèles d\'abonnements'))
            <li>
                <a class="{{ request()->routeIs('parameter_model.index') ? 'active' : '' }}" href="{{ route('parameter_model.index') }}">
                    <i class="bi bi-circle"></i><span>Paramètre Modèle</span>
                </a>
            </li>
            @endif
            @if(Auth::user()->typeUser === 0 || Auth::user()->can('ajouter paramètres modèles d\'abonnements'))
            <li>
                <a href="{{ route('parameter_model.index', ['showModal' => 'create']) }}">
                    <i class="bi bi-plus-circle"></i><span>Ajouter Paramètre</span>
                </a>
            </li>
            @endif
        </ul>
    </li>
    @endif

    @if(Auth::user()->typeUser === 0 || Auth::user()->can('voir transactions/abonnements'))
    <li class="nav-item">
        <a class="nav-link collapsed {{ request()->is('payment*') || request()->is('transaction*') ? 'active' : '' }}" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-cash-stack"></i><span>Paiement</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="tables-nav" class="nav-content collapse {{ request()->is('payment*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
            <li>
                <a href="#">
                    <i class="bi bi-circle"></i><span>Transaction</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="bi bi-circle"></i><span>Abonnement</span>
                </a>
            </li>
        </ul>
    </li>
    @endif

    @if(Auth::user()->typeUser === 0 || Auth::user()->can('voir ticket') || Auth::user()->can('voir catégories ticket') || Auth::user()->can('créer catégories ticket') )
    <li class="nav-item">
        <a class="nav-link collapsed {{ request()->routeIs('ticket*') || request()->is('category_ticket*') ? 'active' : '' }}" data-bs-target="#ticket-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-chat-quote"></i><span>Ticket</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="ticket-nav" class="nav-content collapse {{ request()->is('category_ticket*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
            @if(Auth::user()->typeUser === 0 || Auth::user()->can('voir tickets'))
            <li>
                <a class="" href="#">
                    <i class="bi bi-circle"></i><span>Ticket Ouvert</span>
                </a>
            </li>
            @endif
            @if(Auth::user()->typeUser === 0 || Auth::user()->can('voir catégories ticket'))
            <li>
                <a class="{{ request()->routeIs('category_ticket.index') ? 'active' : '' }}" href="{{ route('category_ticket.index') }}">
                    <i class="bi bi-circle"></i><span>Catégorie Ticket</span>
                </a>
            </li>
            @endif
            @if(Auth::user()->typeUser === 0 || Auth::user()->can('créer catégories ticket'))
            <li>
                <a href="{{ route('category_ticket.index', ['showModal' => 'create']) }}">
                    <i class="bi bi-circle"></i><span>Ajouter Catégorie</span>
                </a>
            </li>
            @endif
        </ul>
    </li>
    @endif

    @if(Auth::user()->typeUser === 0 || Auth::user()->can('voir utilisateurs') || Auth::user()->can('ajouter utilisateurs') || Auth::user()->can('voir rôles') || Auth::user()->can('créer rôles') )
    <li class="nav-item">
        <a class="nav-link collapsed {{ request()->is('users*') || request()->routeIs('users.index') || request()->routeIs('users.create') || request()->routeIs('roles.index') || request()->routeIs('roles.create') ? 'active' : '' }}" data-bs-target="#icons-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-people"></i><span>Utilisateur</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="icons-nav" class="nav-content collapse {{ request()->is('users*') || request()->routeIs('roles*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
            @if(Auth::user()->typeUser === 0 || Auth::user()->can('voir utilisateurs'))
            <li>
                <a class="{{ request()->routeIs('users.index') ? 'active' : '' }}" href="{{ route('users.index') }}">
                    <i class="bi bi-circle"></i><span>Liste Utilisateur</span>
                </a>
            </li>
            @endif
            @if(Auth::user()->typeUser === 0 || Auth::user()->can('ajouter utilisateurs'))
            <li>
                <a class="{{ request()->routeIs('users.create') ? 'active' : '' }}" href="{{ route('users.create') }}">
                    <i class="bi bi-circle"></i><span>Ajouter Utilisateur</span>
                </a>
            </li>
            @endif
            @if(Auth::user()->typeUser === 0 || Auth::user()->can('voir rôles'))
            <li>
                <a class="{{ request()->routeIs('roles.index') ? 'active' : '' }}" href="{{ route('roles.index') }}">
                    <i class="bi bi-circle"></i><span>Liste Rôle</span>
                </a>
            </li>
            @endif
            @if(Auth::user()->typeUser === 0 || Auth::user()->can('créer rôles'))
            <li>
                <a class="{{ request()->routeIs('roles.create') ? 'active' : '' }}" href="{{ route('roles.create') }}">
                    <i class="bi bi-circle"></i><span>Créer Rôle</span>
                </a>
            </li>
            @endif
        </ul>
    </li>
    @endif

    @if(Auth::user()->typeUser === 0 || Auth::user()->can('voir annonces signalées'))
        <li class="nav-item">
            <a class="nav-link collapsed {{ request()->routeIs('report.index') || request()->routeIs('report.show') ? 'active' : '' }}" href="{{ route('report.index') }}">
                <i class="bi bi-flag"></i>
                <span>Rapport</span>
            </a>
        </li>
    @endif

    <li class="nav-heading">Annexes</li>

    <li class="nav-item">
        <a class="nav-link collapsed {{ request()->routeIs('profile.edit') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
            <i class="bi bi-person"></i>
            <span>Profil</span>
        </a>
    </li><!-- End Profil Nav -->

    <li class="nav-item">
        <a class="nav-link collapsed" href="#">
            <i class="bi bi-patch-question"></i>
            <span>Aide?</span>
        </a>
    </li><!-- End Aide Nav -->

    <li class="nav-item">
        <a href="javascript:void(0);" id="logout-link" class="nav-link collapsed" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="bi bi-box-arrow-in-left"></i>
            <span>Deconnexion</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </li>

</ul>

<style>
    .sidebar-nav .nav-item .nav-link.active {
        background-color: #007bff;
        color: white;
    }

    .sidebar-nav .nav-item .nav-link.active:hover {
        background-color: #0056b3;
    }

    .sidebar-nav .nav-content .nav-link {
        padding-left: 30px;
    }
</style>
