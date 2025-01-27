@extends('abonné.include.layouts.ap')
@section('content')
<div class="container mt-4">
    <div class="card shadow-lg mb-4">
        <!-- Section 1: Carrousel des photos -->
        <div id="bienCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @if($bien->photos && count($bien->photos) > 0)
                @foreach($bien->photos as $index => $photo)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    <img src="{{ asset($photo->url_photo) }}" class="d-block w-100 rounded" alt="Photo du bien">
                </div>
                @endforeach
                @else
                <div class="carousel-item active">
                    <img src="{{ asset('/storage/images/annonces/default_main_image.jpg') }}" class="d-block w-100 rounded" alt="Image par défaut">
                </div>
                @endif
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#bienCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Précédent</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#bienCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Suivant</span>
            </button>
        </div>

        <!-- Miniatures des photos -->
        <div class="d-flex justify-content-center gap-2 mt-3">
            @if($bien->photos && count($bien->photos) > 0)
            @foreach($bien->photos as $index => $photo)
            <img src="{{ asset($photo->url_photo) }}" class="img-thumbnail" style="cursor: pointer;" alt="Photo miniature" data-bs-target="#bienCarousel" data-bs-slide-to="{{ $index }}">
            @endforeach
            @else
            <img src="{{ asset('/storage/images/annonces/default_main_image.jpg') }}" class="img-thumbnail" style="cursor: pointer;" alt="Photo miniature">
            @endif
        </div>

        @php
        $createdAt = \Carbon\Carbon::parse($bien->created_at);
        $now = \Carbon\Carbon::now();
        $diffInDays = $createdAt->diffInDays($now);
        $diffInMonths = $createdAt->diffInMonths($now);
        $diffInYears = $createdAt->diffInYears($now);
        $diffInHours = $createdAt->diffInHours($now);
        @endphp

        <!-- Informations du propriétaire -->
        <div class="d-flex align-items-center justify-content-between mt-4 p-3 border-top">
            <div class="d-flex align-items-start gap-3">
                <img src="{{ asset($bien->user->photo_profil) }}" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;" alt="Photo de profil">
                <div>
                    <h6 class="mb-0">{{ $bien->user->name }}</h6>
                    <p class="text-muted mb-0">Publié il y a
                        @if ($diffInYears > 0)
                        {{ $diffInYears }} an{{ $diffInYears > 1 ? 's' : '' }}
                        @elseif ($diffInMonths > 0)
                        {{ $diffInMonths }} mois
                        @elseif ($diffInDays > 7)
                        {{ floor($diffInDays / 7) }} semaine{{ floor($diffInDays / 7) > 1 ? 's' : '' }}
                        @elseif ($diffInDays > 0)
                        {{ $diffInDays }} jour{{ $diffInDays > 1 ? 's' : '' }}
                        @else
                        {{ $diffInHours }} heure{{ $diffInHours > 1 ? 's' : '' }}
                        @endif
                    </p>
                </div>
            </div>
            <a href="mailto:{{ $bien->user->email }}" class="btn btn-outline-primary"><i class="bi bi-send fs-5"></i> Contacter</a>
        </div>

        <!-- Détails du bien -->
        <h5 class="mt-4 text-center">Caractéristiques du bien</h5>
        <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between">
                <span class="fw-bold card-title text-danger text-truncate">
                    <strong>
                        {{ $bien->categorieBien->titre }} en
                        {{ $bien->type_offre }} à
                        {{ number_format($bien->prix, 0, ',', ' ') }} FCFA |
                        <i class="bi bi-geo-alt-fill text-primary"></i> {{ $bien->lieu }}
                    </strong>
                </span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
                <span class="fw-bold">
                    {{ $bien->description }}
                </span>
            </li>
            @foreach($bien->valeurs as $valeur)
            <li class="list-group-item d-flex justify-content-between">
                <span class="fw-bold">{{ $valeur->associationCategorie->parametre->nom_parametre }} :</span>
                <span>{{ $valeur->valeur }}</span>
            </li>
            @endforeach
        </ul>

        <!-- Section Contact et Signalement -->
        <h5 class="text-success text-center mt-4">Contact et Signalement</h5>
        <div class="d-flex flex-column gap-3 p-3">
            <a href="https://web.whatsapp.com/send?phone={{ $bien->user->numero }}" class="btn btn-success d-flex align-items-center justify-content-center gap-2" target="_blank">
                <i class="bi bi-whatsapp fs-5"></i>
                <span>Contacter via WhatsApp</span>
            </a>
            <button class="btn btn-outline-danger d-flex align-items-center justify-content-center gap-2" data-bs-toggle="modal" data-bs-target="#reportModal">
                <i class="bi bi-flag fs-5"></i>
                <span>Signaler cette annonce</span>
            </button>
        </div>
    </div>
</div>

<!-- Modal de signalement -->
<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportModalLabel">Signaler cette annonce</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="motif" class="form-label">Motif du signalement :</label>
                        <textarea name="motif" id="motif" rows="4" class="form-control" placeholder="Décrivez le problème..."></textarea>
                    </div>
                    <div class="d-flex justify-content-end gap-3">
                        <button type="submit" class="btn btn-danger">Envoyer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .carousel img {
        height: 400px;
        object-fit: cover;
    }

    .card {
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
    }

    .btn-primary, .btn-outline-primary {
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }

    .btn-outline-primary:hover {
        background-color: #0056b3;
        color: #fff;
    }

    .list-group-item {
        padding: 15px 20px;
        border: none;
        background-color: #f9f9f9;
    }

    .list-group-item:hover {
        background-color: #eef1f5;
    }

    .img-thumbnail {
        width: 70px;
        height: 70px;
        border-radius: 8px;
        transition: transform 0.3s ease-in-out;
    }

    .img-thumbnail:hover {
        transform: scale(1.1);
    }

    @media (max-width: 768px) {
        .carousel img {
            height: auto;
        }

        .img-thumbnail {
            width: 50px;
            height: 50px;
        }

        .card {
            padding: 15px;
        }
    }

    .like-icon {
        background-color: #ffffff;
        border-radius: 50%;
        cursor: pointer;
        transition: transform 0.2s ease;
    }

    .like-icon:hover {
        transform: scale(1.2);
    }
</style>
@endsection
