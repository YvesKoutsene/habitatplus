@extends('admin.include.layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Tickets</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route('tckt.index') }}">Tickets</a></li>
                <li class="breadcrumb-item active">Détails du Ticket</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card mb-3">
                    <div class="card-body d-flex align-items-center gap-3">
                        <img src="{{ asset($ticket->user->photo_profil) }}" alt="Profil" class="rounded-circle border shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
                        <div>
                            <h5 class="card-title mb-0">{{ $ticket->user->email }}</h5>
                            @if($ticket->statut == 'ouvert')
                                <small class="text-muted d-block">A ouvert ce ticket le {{ \Carbon\Carbon::parse($ticket->created_at)->translatedFormat('d F Y') }}.</small>
                            @elseif($ticket->statut == 'clôturé')
                                <small class="text-muted d-block">
                                    A ouvert ce ticket le {{ \Carbon\Carbon::parse($ticket->created_at)->translatedFormat('d F Y') }} et clôturé le
                                    {{ \Carbon\Carbon::parse($ticket->updated_at)->translatedFormat('d F Y') }}.
                                </small>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Informations du Ticket</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between"><strong>Objet :</strong> {{ $ticket->titre }}</li>
                            <li class="list-group-item d-flex justify-content-between"><strong>Catégorie ticket :</strong> {{ $ticket->categorie->nom_categorie }}</li>
                            <li class="list-group-item d-flex justify-content-between"><strong>Statut :</strong> <span class="badge {{ $classe = ($ticket->statut == 'ouvert') ? 'bg-primary' : (($ticket->statut == 'clôturé') ? 'bg-danger' : 'bg-warning') }}">{{ ucfirst($ticket->statut) }}</span></li>
                            <li class="list-group-item d-flex justify-content-between">
                            <span id="descriptionText" class="text-black-50">
                                {{ Str::limit($ticket->description !== null ? $ticket->description : 'Aucune description', 25) }}
                            </span>
                                @if(strlen($ticket->description) > 25)
                                    <button class="btn btn-link btn-sm" id="toggleDescription" style="text-decoration: none; color: #007bff;">
                                        Voir plus
                                    </button>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Pièce jointe</h5>
                        @if($ticket->piece_jointe)
                            <div class="d-flex justify-content-center gap-3">
                                <a href="{{ asset($ticket->piece_jointe) }}" target="_blank" class="btn btn-outline-primary">
                                    <i class="bi bi-eye"></i> Voir pièce jointe
                                </a>
                                <a href="{{ asset($ticket->piece_jointe) }}" download class="btn btn-outline-success">
                                    <i class="bi bi-download"></i> Télécharger
                                </a>
                            </div>
                        @else
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span class="text-black-50">Aucune pièce jointe disponible pour ce ticket.</span>
                                </li>
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .carousel img {
            height: 400px;
            object-fit: cover;
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

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            width: 3rem;
            height: 3rem;
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
                descriptionText.innerText = '{{ $ticket->description }}';
                toggleButton.innerText = 'Voir moins';
            } else {
                descriptionText.innerText = '{{ Str::limit($ticket->description, 25) }}';
                toggleButton.innerText = 'Voir plus';
            }
        });
    </script>

@endsection
