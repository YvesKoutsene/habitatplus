<ul class="sidebar-nav" id="sidebar-nav">

    <li class="nav-item">
        <a class="nav-link " href="{{ route('dashboard') }}">
            <i class="bi bi-grid"></i>
            <span>Dashboard</span>
        </a>
    </li><!-- End Dashboard Nav -->

    <li class="nav-item">
        <a class="nav-link collapsed" href="#">
            <i class="bi bi-megaphone"></i>
            <span>Annonce</span>
        </a>
    </li><!-- End Profile Page Nav -->

    <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-tag"></i><span>Categorie Bien</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li>
                <a href="{{ route('category_bien.index') }}">
                    <i class="bi bi-circle"></i><span>Liste Categorie</span>
                </a>
            </li>
            <li>
                <a href="{{ route('category_bien.create') }}">
                    <i class="bi bi-circle"></i><span>Créer Categorie</span>
                </a>
            </li>
            <li>
                <a href="{{ route ('parameter_category.index') }}">
                    <i class="bi bi-circle"></i><span>Paramètre Categorie</span>
                </a>
            </li>
            <!--
            <li>
                <a href="{{ route ('parameter_category.create') }}">
                    <i class="bi bi-circle"></i><span>Ajouter Paramètre</span>
                </a>
            </li>
            -->
        </ul>
    </li><!-- End Components Nav -->

    <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-credit-card-2-back"></i><span>Modèle Abonnement</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li>
                <a href="#">
                    <i class="bi bi-circle"></i><span>Liste Modèle</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="bi bi-circle"></i><span>Créer Modèle</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="bi bi-circle"></i><span>Paramètre Modèle</span>
                </a>
            </li>
            <!--
            <li>
                <a href="#">
                    <i class="bi bi-circle"></i><span>Ajouter Paramètre</span>
                </a>
            </li>
            -->
        </ul>
    </li><!-- End Forms Nav -->

    <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-cash-stack"></i><span>Payement</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
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
        <a class="nav-link collapsed" data-bs-target="#icons-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-people"></i><span>Utilisateur</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="icons-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li>
                <a href="{{ route('roles.index') }}">
                    <i class="bi bi-circle"></i><span>Liste Rôle</span>
                </a>
            </li>
            <li>
                <a href="{{ route('roles.create') }}">
                    <i class="bi bi-circle"></i><span>Créer Rôle</span>
                </a>
            </li>
            <li>
                <a href="{{ route('users.index') }}">
                    <i class="bi bi-circle"></i><span>Liste Utilisateur</span>
                </a>
            </li>
            <li>
                <a href="{{ route('users.create') }}">
                    <i class="bi bi-circle"></i><span>Ajouter Utilisateur</span>
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
        <a class="nav-link collapsed" href="{{ route('profile.edit') }}">
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
