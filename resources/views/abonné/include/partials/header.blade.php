<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habitat+ Header</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <style>
        .bg-gradient {
            background: linear-gradient(90deg, #007bff, #0056b3);
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

    </style>
</head>
<body>
<header class="bg-gradient text-white py-3 shadow-sm">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="logo d-flex align-items-center">
            <a href="" class="d-flex align-items-center text-white text-decoration-none h4">
                <img src="\assets/img/logo.png" alt="Logo" class="me-2 logo-img">
                <span class="d-none d-lg-block fw-bold text-black">Habitat+</span>
            </a>
        </div>

        <div class="search-bar mx-auto">
            <form action="{{ url('/recherche') }}" method="GET" class="d-flex">
                <input type="text" name="query" class="form-control me-2" placeholder="Rechercher...">
                <button type="submit" class="btn shadow-sm">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>

        <nav>

            <ul class="nav">
                <li class="nav-item mb-2">
                    <a class="nav-link text-black fw-bold px-3 d-flex align-items-center me-2" href="{{ auth()->check() ? '#' : route('login') }}">
                        @auth
                        <i class="bi bi-person-circle me-2 text-black"></i>{{ auth()->user()->name }}
                        @else
                        <i class="bi bi-person-circle me-2 text-black"></i>Se connecter
                        @endauth
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white fw-bold px-3 d-flex align-items-center bg-danger btn-publish me-2" href="">
                        <i class="bi bi-megaphone me-2"></i>Publier annonce
                    </a>
                </li>
            </ul>

        </nav>
    </div>
</header>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
