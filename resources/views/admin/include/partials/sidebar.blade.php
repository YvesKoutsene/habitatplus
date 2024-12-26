<ul class="sidebar-nav" id="sidebar-nav">

    <li class="nav-item">
        <!-- <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"> -->
            <a class="nav-link collapsed {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
            <i class="bi bi-grid"></i>
            <span>Tableau de bord</span>
        </a>
    </li><!-- End Dashboard Nav -->

    <li class="nav-item">
        <a class="nav-link collapsed" href="#">
            <i class="bi bi-megaphone"></i>
            <span>Annonce</span>
        </a>
    </li><!-- End Profile Page Nav -->

    <li class="nav-item">
        <a class="nav-link collapsed {{ request()->is('category_bien*') || request()->is('parameter_category*') ? 'active' : '' }}" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-tag"></i><span>Categorie Bien</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="components-nav" class="nav-content collapse {{ request()->is('category_bien*') || request()->is('parameter_category*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
            <li>
                <a class="{{ request()->routeIs('category_bien.index') ? 'active' : '' }}" href="{{ route('category_bien.index') }}">
                    <i class="bi bi-circle"></i><span>Liste Categorie</span>
                </a>
            </li>
            <li>
                <a class="{{ request()->routeIs('category_bien.create') ? 'active' : '' }}" href="{{ route('category_bien.create') }}">
                    <i class="bi bi-circle"></i><span>Créer Categorie</span>
                </a>
            </li>
            <li>
                <a class="{{ request()->routeIs('parameter_category.index') ? 'active' : '' }}" href="{{ route('parameter_category.index') }}">
                    <i class="bi bi-circle"></i><span>Paramètre Categorie</span>
                </a>
            </li>
            <li>
                <a href="{{ route('parameter_category.index', ['showModal' => 'create']) }}">
                    <i class="bi bi-circle"></i><span>Ajouter Paramètre</span>
                </a>
            </li>
        </ul>
    </li><!-- End Components Nav -->

    <li class="nav-item">
        <a class="nav-link collapsed {{ request()->is('model_subscription*') || request()->is('parameter_model*') ? 'active' : '' }}" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-credit-card-2-back"></i><span>Modèle Abonnement</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="forms-nav" class="nav-content collapse {{ request()->is('model_subscription*') || request()->is('parameter_model*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
            <li>
                <a class="{{ request()->routeIs('model_subscription.index') ? 'active' : '' }}" href="{{ route('model_subscription.index') }}">
                    <i class="bi bi-circle"></i><span>Liste Modèle</span>
                </a>
            </li>
            <li>
                <a class="{{ request()->routeIs('model_subscription.create') ? 'active' : '' }}" href="{{ route('model_subscription.create') }}">
                    <i class="bi bi-circle"></i><span>Créer Modèle</span>
                </a>
            </li>
            <li>
                <a class="{{ request()->routeIs('parameter_model.index') ? 'active' : '' }}" href="{{ route('parameter_model.index') }}">
                    <i class="bi bi-circle"></i><span>Paramètre Modèle</span>
                </a>
            </li>
            <li>
                <a href="{{ route('parameter_model.index', ['showModal' => 'create']) }}">
                    <i class="bi bi-circle"></i><span>Ajouter Paramètre</span>
                </a>
            </li>
        </ul>
    </li><!-- End Forms Nav -->

    <li class="nav-item">
        <a class="nav-link collapsed {{ request()->is('payment*') ? 'active' : '' }}" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-cash-stack"></i><span>Payement</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="tables-nav" class="nav-content collapse {{ request()->is('payment*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
            <li>
                <a href="tables-general.html">
                    <i class="bi bi-circle"></i><span>Transaction</span>
                </a>
            </li>
            <li>
                <a href="tables-data.html">
                    <i class="bi bi-circle"></i><span>Abonnement</span>
                </a>
            </li>
        </ul>
    </li><!-- End Tables Nav -->

    <li class="nav-item">
        <a class="nav-link collapsed {{ request()->is('users*') || request()->routeIs('users.index') || request()->routeIs('users.create') || request()->routeIs('roles.index') || request()->routeIs('roles.create') ? 'active' : '' }}" data-bs-target="#icons-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-people"></i><span>Utilisateur</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="icons-nav" class="nav-content collapse {{ request()->is('users*') || request()->routeIs('roles*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
            <li>
                <a class="{{ request()->routeIs('users.index') ? 'active' : '' }}" href="{{ route('users.index') }}">
                    <i class="bi bi-circle"></i><span>Liste Utilisateur</span>
                </a>
            </li>
            <li>
                <a class="{{ request()->routeIs('users.create') ? 'active' : '' }}" href="{{ route('users.create') }}">
                    <i class="bi bi-circle"></i><span>Ajouter Utilisateur</span>
                </a>
            </li>
            <li>
                <a class="{{ request()->routeIs('roles.index') ? 'active' : '' }}" href="{{ route('roles.index') }}">
                    <i class="bi bi-circle"></i><span>Liste Rôle</span>
                </a>
            </li>
            <li>
                <a class="{{ request()->routeIs('roles.create') ? 'active' : '' }}" href="{{ route('roles.create') }}">
                    <i class="bi bi-circle"></i><span>Créer Rôle</span>
                </a>
            </li>
        </ul>
    </li><!-- End Icons Nav -->

    <li class="nav-item">
        <a class="nav-link collapsed" href="#">
            <i class="bi bi-chat-quote"></i>
            <span>Ticket Ouvert</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#">
            <i class="bi bi-flag"></i>
            <span>Rapport</span>
        </a>
    </li>

    <li class="nav-heading">Annexes</li>

    <li class="nav-item">
        <a class="nav-link collapsed {{ request()->routeIs('profile.edit') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
            <i class="bi bi-person"></i>
            <span>Profil</span>
        </a>
    </li><!-- End Profile Page Nav -->

    <li class="nav-item">
        <a class="nav-link collapsed" href="#">
            <i class="bi bi-gear"></i>
            <span>Paramètre</span>
        </a>
    </li><!-- End F.A.Q Page Nav -->

    <li class="nav-item">
        <a class="nav-link collapsed" href="#">
            <i class="bi bi-question-circle"></i>
            <span>Aide?</span>
        </a>
    </li><!-- End Contact Page Nav -->

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

    .sidebar-nav .nav-item .nav-content.show {
        display: block; 
    }
</style>
