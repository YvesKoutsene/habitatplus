<header class="bg-gradient text-white py-2 shadow-sm fixed-top header " id="header" >
    <div class="container d-flex justify-content-between align-items-center">
        <div class="logo d-flex align-items-center">
            <a href="{{ route('acceuil') }}" class="d-flex align-items-center text-white text-decoration-none h4">
                <img src="\assets/img/logo.png" alt="Logo" class="me-2 logo-img">
                <span class="d-none d-lg-block fw-bold text-black">Habitat+</span>
            </a>
        </div>

        <div class="search-bar mx-auto">
            <form action="" method="GET" class="d-flex">
                <input type="text" name="query" class="form-control me-2" placeholder="Rechercher sur habitat+">
                <button type="submit" class="btn shadow-sm">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>

        <nav>
            <ul class="nav">
                <li class="nav-item dropdown me-2">
                    @auth
                    <a class="nav-link dropdown-toggle text-black-50 px-2 py-1 d-flex align-items-center me-2" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ asset(Auth::user()->photo_profil) }}" alt="Profil" class="rounded-circle me-1" style="width: 25px; height: 25px; object-fit: cover; border: 2px solid #f8f9fa;">
                        {{ auth()->user()->name }}
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="userDropdown">
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('dashboard') }}">
                                <i class="bi bi-person me-2"></i> Mon Espace
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
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
                    <a class="nav-link text-black-50 d-flex align-items-center px-2 py-1" href="#" data-bs-toggle="modal" data-bs-target="#authModal">
                        <i class="bi bi-person-circle me-2 text-black-50"></i>Se Connecter
                    </a>
                    @endauth
                </li>
                @auth
                <li class="nav-item ">
                    <a class="nav-link text-white d-flex align-items-center bg-danger btn-publish px-2 py-1" href="{{ route('announcement.create') }}">
                        <i class="bi bi-megaphone me-2"></i> Publier Annonce
                    </a>
                </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link text-white d-flex align-items-center bg-danger btn-publish px-2 py-1" href="javascript:void(0);" onclick="showAuthModal()">
                            <i class="bi bi-megaphone me-2"></i> Publier Annonce
                        </a>
                    </li>
                @endauth
            </ul>
        </nav>
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
        height: 40px;
        width: auto;
    }

    .search-bar .form-control {
        border: none;
        background: #f8f9fa;
        border-radius: 50px;
        padding: 0.5rem 1rem;
    }

    .search-bar .form-control:focus {
        box-shadow: 0 0 8px rgba(0, 123, 255, 0.5);
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

    .nav .nav-link i {
        font-size: 1.2rem;
    }

    @media (max-width: 768px) {
        .search-bar {
            display: none;
        }

        .nav {
            flex-direction: column;
            align-items: flex-start;
        }

        .nav .nav-link {
            padding: 0.5rem 0;
        }
    }

    .btn-publish {
        font-weight: 600;
        border-radius: 50px;
        padding: 0.5rem 1rem;
    }

    .dropdown-menu {
        border-radius: 15px;
        padding: 0.5rem 0;
        background-color: #f9f9f9;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .dropdown-item {
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
        color: #333;
        transition: all 0.2s ease;
    }

    .dropdown-item:hover {
        background-color: #007bff;
        color: white;
        border-radius: 10px;
    }

    .dropdown-divider {
        margin: 0.5rem 0;
        border-top: 1px solid #e5e5e5;
    }

    .dropdown-menu-end {
        right: 0;
        left: auto;
    }

    .nav-link.dropdown-toggle::after {
        margin-left: 0.5rem;
    }

</style>

<script>
    function showAuthModal() {
        const authModal = new bootstrap.Modal(document.getElementById('authModal'));
        authModal.show();

        const loginTab = new bootstrap.Tab(document.getElementById('login-tab'));
        loginTab.show();
    }
</script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
