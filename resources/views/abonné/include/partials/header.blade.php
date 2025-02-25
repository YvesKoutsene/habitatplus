<header class="bg-gradient text-white py-2 shadow-sm fixed-top" id="header">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="logo d-flex align-items-center">
            <a href="{{ route('acceuil') }}" class="d-flex align-items-center text-white text-decoration-none h4">
                <img src="/assets/img/logo.png" alt="Logo" class="me-2 logo-img">
                <span class="d-none d-lg-block fw-bold text-black">Habitat+</span>
            </a>
        </div>
        <div class="search-bar mx-auto">
            <form action="{{ route('acceuil') }}" method="GET" class="d-flex" onsubmit="return validateSearch()">
                <input type="text" id="search-input" name="search" class="form-control me-2" placeholder="Rechercher sur Habitat+" value="{{ request()->get('search') }}" required>
                <button type="submit" class="btn shadow-sm">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>

        <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="offcanvas"
                @auth
                data-bs-target="#mobileMenu" aria-controls="mobileMenu"
                @else
                href="javascript:void(0);" onclick="showAuthModal()">
                @endauth>
            <i class="bi bi-person-circle text-black-50" style="font-size: 1.5rem;"></i>
        </button>

        <nav class="d-none d-md-block">
            <ul class="nav">
                <li class="nav-item dropdown me-2">
                    @auth
                    <a class="nav-link d-flex flex-column align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ asset(Auth::user()->photo_profil) }}" alt="Profil" class="rounded-circle mb-1 user-img" style="width: 25px; height: 25px; object-fit: cover; border: 2px solid #f8f9fa;">
                        <span class="text-black-50 small"><strong>{{ auth()->user()->name }}</strong></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item d-flex align-items-center" href="{{ route('dashboard') }}"><i class="bi bi-person me-2"></i> Mon compte</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item d-flex align-items-center text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i> Déconnexion
                                </button>
                            </form>
                        </li>
                    </ul>
                    @else
                    <a class="nav-link d-flex flex-column align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#authModal">
                        <i class="bi bi-person-circle me-2 text-black-50"></i>
                        <span class="text-black-50 small"><strong>Se connecter</strong></span>
                    </a>
                    @endauth
                </li>
                <li class="nav-item" style="padding-top: 15px">
                    @auth
                    <a class="nav-link text-white d-flex align-items-center bg-danger btn-publish px-2 py-1" href="{{ route('announcement.create') }}">
                        <i class="bi bi-megaphone me-2"></i> Publier annonce
                    </a>
                    @else
                    <a class="nav-link text-white d-flex align-items-center bg-danger btn-publish px-2 py-1" href="javascript:void(0);" onclick="showAuthModal()">
                        <i class="bi bi-megaphone me-2"></i> Publier annonce
                    </a>
                    @endauth
                </li>
            </ul>
        </nav>

        <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
            <div class="offcanvas-header bg-gradient">
                <div class="logo d-flex align-items-center" id="mobileMenuLabel">
                    <a href="{{ route('acceuil') }}" class="d-flex align-items-center text-white text-decoration-none">
                        <img src="/assets/img/logo.png" alt="Logo" class="me-2 logo-img">
                        <span class="fw-bold text-black">Habitat+</span>
                    </a>
                </div>
            </div>
            <div class="offcanvas-body d-flex flex-column justify-content-between">
                <ul class="nav flex-column text-center">
                    @auth
                    <div class="user-profile text-center my-3">
                        <img src="{{ asset(Auth::user()->photo_profil) }}" alt="Profil" class="rounded-circle shadow-sm user-img">
                        <h5 class="mt-2 text-black-50 small"><strong>{{ auth()->user()->name }}</strong></h5>
                    </div>
                    <ul class="nav flex-column align-items-center">
                        <li class="nav-item mb-3">
                            <a class="nav-link text-dark fw-semibold d-flex align-items-center justify-content-center px-4 py-2 border rounded shadow-sm"
                               href="{{ route('dashboard') }}" style="width: auto; min-width: 150px;">
                                <i class="bi bi-person me-2"></i> Mon compte
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white d-flex align-items-center justify-content-center bg-danger rounded-pill shadow-sm px-4 py-2"
                               href="{{ route('announcement.create') }}" style="width: auto; min-width: 150px;">
                                <i class="bi bi-megaphone me-2"></i> Publier annonce
                            </a>
                        </li>
                    </ul>
                    @else
                    <div class="user-profile text-center my-3">
                        <i class="bi bi-person-circle text-secondary" style="font-size: 80px;"></i>
                    </div>
                    <ul class="nav flex-column align-items-center">
                        <li class="nav-item mb-3">
                            <a class="nav-link btn btn-primary rounded-pill text-white fw-semibold px-4" href="#" data-bs-toggle="modal" data-bs-target="#authModal">
                                <i class="bi bi-box-arrow-in-right me-2"></i> Se connecté
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white d-flex align-items-center justify-content-center bg-danger rounded-pill shadow-sm px-4 py-2"
                               href="javascript:void(0);" onclick="showAuthModal()" style="width: auto; min-width: 150px;">
                                <i class="bi bi-megaphone me-2"></i> Publier annonce
                            </a>
                        </li>
                    </ul>
                    @endauth
                </ul>
            </div>
            @auth
            <div class="offcanvas-footer text-center py-3 bg-light">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger rounded-pill shadow-sm px-4">
                        <i class="bi bi-box-arrow-right me-2"></i> Déconnexion
                    </button>
                </form>
            </div>
            @endauth
        </div>

    </div>
</header>

<style>
    .bg-gradient {
        background: linear-gradient(90deg, #007bff, #0056b3);
        background-color: white;
        opacity: 1;
    }

    header {
        z-index: 1030;
    }

    .logo-img {
        height: 35px;
        width: auto;
    }

    .search-bar .form-control {
        border: none;
        background: #f8f9fa;
        border-radius: 50px;
        padding: 0.5rem 1rem;
    }

    .search-bar button {
        border: none;
        background: #fff;
        color: #007bff;
        border-radius: 50%;
        transition: background 0.3s ease, transform 0.3s ease;
    }

    .search-bar button:hover {
        background: #f0f0f0;
        transform: scale(1.1);
    }

    .nav .nav-link {
        font-size: 1rem;
        transition: color 0.3s ease, transform 0.3s ease;
    }

    .nav .nav-link:hover {
        color: #ffdd57;
        transform: scale(1.1);
    }

    .btn-publish {
        font-weight: 10;
        border-radius: 50px;
        padding: 0.5rem 1rem;
    }

    .dropdown-menu {
        border-radius: 15px;
        background-color: #f9f9f9;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .dropdown-item:hover {
        background-color: #007bff;
        color: white;
        border-radius: 10px;
    }

    .offcanvas {
        width: 50%;
        max-width: 320px;
    }

    .user-img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border: 1px solid white;

    }

    .offcanvas-footer {
        border-top: 1px solid #ddd;
    }
</style>

<script>
    function showAuthModal() {
        const authModal = new bootstrap.Modal(document.getElementById('authModal'));
        authModal.show();
    }

    function validateSearch() {
        const searchInput = document.getElementById('search-input').value.trim();
        return searchInput.length > 0;
    }
</script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
