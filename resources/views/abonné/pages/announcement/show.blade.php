@extends('abonné.include.layouts.ap')
@section('content')

<div class="container-fluid px-3 mt4">
    <h4 class="text-black-50 mb-2">
        <strong>
            {{ $bien->categorieBien->titre !== null ? $bien->categorieBien->titre : 'N/A' }} en
            {{ $bien->type_offre !== null ? $bien->type_offre : 'N/A' }} à
            {{ number_format($bien->prix, 0, ',', ' ') }} FCFA
        </strong>
    </h4>
    <div class="card mb-4 h-100 w-100 shadow-sm border-0 rounded-lg overflow-hidden">
        <div id="bienCarousel" class="carousel slide" data-bs-ride="carousel">
            <ol class="carousel-indicators">
                @if($bien->photos && count($bien->photos) > 0)
                @foreach($bien->photos as $index => $photo)
                <li data-bs-target="#bienCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}"></li>
                @endforeach
                @endif
            </ol>
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
        <div class="d-flex justify-content-center gap-2 mt-3">
            @if($bien->photos && count($bien->photos) > 0)
            @foreach($bien->photos as $index => $photo)
            <img src="{{ asset($photo->url_photo) }}" class="img-thumbnail" style="cursor: pointer;" alt="Photo miniature" data-bs-target="#bienCarousel" data-bs-slide-to="{{ $index }}">
            @endforeach
            @else
            <img src="{{ asset('/storage/images/annonces/default_main_image.jpg') }}" class="img-thumbnail" style="cursor: pointer;" alt="Photo miniature">
            @endif
        </div>
        @if($bien->statut == 'bloqué')
            <br><p class="card-title fw-bold text-danger text-truncate">
                <i class="bi bi-exclamation-triangle-fill text-danger"></i> Cette annonce est bloquée par la plateforme Habitat+
            </p>
        @endif

        @php
        $createdAt = \Carbon\Carbon::parse($bien->datePublication);
        $now = \Carbon\Carbon::now();
        $diffInDays = $createdAt->diffInDays($now);
        $diffInMonths = $createdAt->diffInMonths($now);
        $diffInYears = $createdAt->diffInYears($now);
        $diffInHours = $createdAt->diffInHours($now);
        $diffInMins = $createdAt->diffInMinutes($now);
        @endphp

        <div class="d-flex align-items-center justify-content-between mt-4 p-3 border-top">
            @if($bien->statut == 'publié')
            <div class="d-flex align-items-start gap-3">
                <img src="{{ asset($bien->user->photo_profil) }}" class="rounded-circle" style="width: 35px; height: 35px; object-fit: cover;" alt="Photo de profil">
                <div>
                    <h6 class="mb-0">{{ $bien->user->name }}</h6>
                    <p class="text-black-50 mb-0 small">A publié il y a
                        @if ($diffInYears > 0)
                        {{ $diffInYears }} an{{ $diffInYears > 1 ? 's' : '' }}
                        @elseif ($diffInMonths > 0)
                        {{ $diffInMonths }} mois
                        @elseif ($diffInDays > 7)
                        {{ floor($diffInDays / 7) }} semaine{{ floor($diffInDays / 7) > 1 ? 's' : '' }}
                        @elseif ($diffInDays > 0)
                        {{ $diffInDays }} jour{{ $diffInDays > 1 ? 's' : '' }}
                        @elseif ($diffInHours > 0)
                        {{ $diffInHours }} heure{{ $diffInHours > 1 ? 's' : '' }}
                        @else
                        {{ $diffInMins }} minute{{ $diffInMins > 1 ? 's' : '' }}
                        @endif
                    </p>
                </div>
            </div>

            <!---
            <span class="like-icon position-absolute top-10 end-0 m-4 p-2">
                <i class="bi bi-heart text-danger fs-5" title="Favoris"></i>
            </span>
            -->

            @endif
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between">
                <span class="fw-bold card-title text-black-50 text-truncate">
                    <strong>
                        <i class="bi bi-geo-alt-fill text-danger"></i> {{ $bien->lieu !== null ? $bien->lieu : 'N/A' }}
                    </strong>
                </span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
                <span id="descriptionText" class="text-black-50">
                    {{ Str::limit($bien->description !== null ? $bien->description : 'Aucune description', 25) }}
                </span>
                @if(strlen($bien->description) > 25)
                <button class="btn btn-link btn-sm" id="toggleDescription" style="text-decoration: none; color: #007bff;">
                    Voir plus
                </button>
                @endif
            </li>
        </ul>
        <h5 class="mt-4 text-center">Caractéristiques du bien</h5>
        <ul class="list-group list-group-flush">
            @if($bien->valeurs->isEmpty())
                <li class="list-group-item d-flex justify-content-between">
                    <span class="fw-bold">Aucune caractéristique</span>
                </li>
            @else
                @foreach($bien->valeurs as $valeur)
                <li class="list-group-item d-flex justify-content-between">
                    <span class="fw-bold">{{ $valeur->associationCategorie->parametre->nom_parametre }} :</span>
                    <span>{{ $valeur->valeur }}</span>
                </li>
                @endforeach
            @endif
        </ul>
        <h5 class="text-success text-center mt-4">Contact et Signalement</h5>
        <div class="d-flex flex-column gap-3 p-3">

            @php
            $url = url()->current();
            $message = "Je suis intéressé par votre annonce de bien \"{$bien->titre}\" publiée sur Habitat+. Lien de l'annonce : {$url}";
            $encodedMessage = urlencode($message);
            @endphp

            @auth
            <a href="https://wa.me/{{ $bien->user->numero }}?text={{ $encodedMessage }}" class="btn btn-success d-flex align-items-center justify-content-center gap-2" target="_blank">
                <i class="bi bi-whatsapp fs-5"></i>
                <span>WhatsApp</span>
            </a>
            <button class="btn btn-outline-danger d-flex align-items-center justify-content-center gap-2" data-bs-toggle="modal" data-bs-target="#reportModal">
                <i class="bi bi-flag fs-5"></i>
                <span>Signaler cette annonce</span>
            </button>
            @else
            <a href="{{ route('acceuil',['showModal' => 'create']) }}" class="btn btn-warning d-flex align-items-center justify-content-center gap-2">
                <i class="bi bi-lock fs-5"></i>
                <span>Connectez-vous pour contacter le propriétaire</span>
            </a>
            <a href="{{ route('acceuil',['showModal' => 'create']) }}" class="btn btn-outline-danger d-flex align-items-center justify-content-center gap-2">
                <i class="bi bi-lock fs-5"></i>
                <span>Connectez-vous pour signaler cette annonce</span>
            </a>
            @endauth

        </div>
    </div>
</div>

<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" title="Fermer"></button>
                </div>
                <form action="{{ route('announcement.report', ['bien' => $bien->id]) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <textarea name="motif" id="motif" rows="4" class="form-control" placeholder="Donnez le motif du signalement..." maxlength="200" required></textarea>
                    </div>
                    <div class="d-flex justify-content-end gap-3">
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-send"></i> Envoyer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

<style>
    body {
        font-family: 'Roboto', sans-serif;
    }

    .carousel img {
        border-bottom: 3px solid #007bff;
        object-fit: cover;
        width: 100%;
        height: 400px;
    }

    .card {
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        padding: 20px;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
    }

    .card-title {
        font-size: 1rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .btn {
        border-radius: 25px;
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
        background-color: #fafafa;
        border: none;
        padding: 18px;
        border-radius: 8px;
        transition: background-color 0.3s ease;
    }

    .list-group-item:hover {
        background-color: #eaeaea;
    }

    .img-thumbnail {
        width: 70px;
        height: 70px;
        border-radius: 10px;
        transition: transform 0.3s ease-in-out, border 0.3s ease;
    }

    .img-thumbnail:hover {
        transform: scale(1.1);
        border: 2px solid #007bff;
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

    .border-top {
        border-top: 2px solid #007bff;
    }

    .btn-primary, .btn-outline-primary, .btn-success {
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

    .btn-success {
        background-color: #25D366;
        border-color: #25D366;
    }

    .btn-success:hover {
        background-color: #128C7E;
        border-color: #128C7E;
    }

    .like-icon {
        background-color: #ffffff;
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        padding: 20px;
    }

    .like-icon:hover {
        transform: scale(1.2);
    }

    .yes{
        background-color: #007bff;
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        width: 2.5rem;
        height: 2.5rem;
        background-color: rgba(0, 0, 0, 0.5);
        border-radius: 50%;
        border: 2px solid white;
    }

    .carousel-control-prev-icon::before,
    .carousel-control-next-icon::before {
        color: white;
        font-size: 1.5rem;
    }

    .carousel-control-prev:hover .carousel-control-prev-icon,
    .carousel-control-next:hover .carousel-control-next-icon {
        background-color: rgba(255, 255, 255, 0.8);
        color: black;
    }
</style>

<script>
    document.getElementById('toggleDescription')?.addEventListener('click', function() {
        var descriptionText = document.getElementById('descriptionText');
        var toggleButton = document.getElementById('toggleDescription');

        if (toggleButton.innerText === 'Voir plus') {
            descriptionText.innerText = '{{ $bien->description }}';
            toggleButton.innerText = 'Voir moins';
        } else {
            descriptionText.innerText = '{{ Str::limit($bien->description, 25) }}';
            toggleButton.innerText = 'Voir plus';
        }
    });
</script>

@endsection
