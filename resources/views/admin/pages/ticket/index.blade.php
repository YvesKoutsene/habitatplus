@extends('admin.include.layouts.app')

@section('content')

    <div class="pagetitle">
        <h1>Tickets</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i></a></li>
                <li class="breadcrumb-item">Tickets</li>
                <li class="breadcrumb-item active">Tickets Ouverts</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Liste des Tickets Ouverts</h5>
                        </div>
                        @if(!$tickets->isEmpty())
                            <div class="d-flex mb-3 justify-content-between">
                                <form action="{{ route('tckt.index') }}" method="GET" class="d-flex">
                                    <select name="perPage" class="form-select me-2" onchange="this.form.submit()">
                                        <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5 entrées/page</option>
                                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 entrées/page</option>
                                        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 entrées/page</option>
                                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 entrées/page</option>
                                    </select>
                                </form>
                                <form action="{{ route('tckt.index') }}" method="GET" class="d-flex">
                                    <input type="text" name="search" class="form-control me-2" placeholder="Taper ici pour chercher..." value="{{ request()->get('search') }}">
                                    <button type="submit" class="btn btn-primary" title="Rechercher"><i class="bi bi-search"></i></button>
                                </form>
                            </div>
                        @endif
                        @if($tickets->isEmpty())
                            <div class="alert alert-info">
                                Aucun ticket ouvert pour le moment.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead>
                                    <tr>
                                       <th>#</th>
                                        <th>Objet</th>
                                        <th>Ouvert par</th>
                                        <th>Description</th>
                                        <th>Ouvert le</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($tickets as $ticket)
                                        <tr>
                                            <td>{{ $ticket->id}}</td>
                                            <td>
                                                @if(strlen($ticket->titre) > 8)
                                                    {{ ucfirst(substr($ticket->titre, 0, 8)) }}...
                                                    <button type="button" class="btn btn-sm btn-link p-0" data-bs-toggle="modal" data-bs-target="#descriptionModal{{ $ticket->id }}">
                                                        Lire suite
                                                    </button>
                                                    <div class="modal fade" id="descriptionModal{{ $ticket->id }}" tabindex="-1" aria-labelledby="descriptionModalLabel{{ $ticket->id }}" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="descriptionModalLabel{{ $ticket->id }}">Objet complet</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    {{ ucfirst($ticket->titre) }}
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Fermer</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    {{ ucfirst($ticket->titre) }}
                                                @endif
                                            </td>
                                            <td>
                                                <img src="{{ asset($ticket->user->photo_profil) }}" alt="Profil" class="rounded-circle" style="width: 35px; height: 35px; object-fit: cover;">
                                                | {{ $ticket->user->name }}
                                            </td>
                                            <td>
                                                @if(strlen($ticket->description) > 8)
                                                    {{ ucfirst(substr($ticket->description, 0, 8)) }}...
                                                    <button type="button" class="btn btn-sm btn-link p-0" data-bs-toggle="modal" data-bs-target="#descriptionModal{{ $ticket->id }}">
                                                        Lire suite
                                                    </button>
                                                    <div class="modal fade" id="descriptionModal{{ $ticket->id }}" tabindex="-1" aria-labelledby="descriptionModalLabel{{ $ticket->id }}" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="descriptionModalLabel{{ $ticket->id }}">Description complete</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    {{ ucfirst($ticket->description) }}
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Fermer</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    {{ ucfirst($ticket->description) }}
                                                @endif
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($ticket->created_at)->translatedFormat('d F Y') }}</td>
                                            <td>
                                                <span class="badge {{ $classe = ($ticket->statut == 'ouvert') ? 'bg-primary' : (($ticket->statut == 'fermé') ? 'bg-warning' : 'bg-danger')}}">
                                                    {{ ucfirst($ticket->statut) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    @if(Auth::user()->typeUser === 0 || Auth::user()->can('voir tickets'))
                                                        <a href="{{ route('tckt.show', $ticket->id) }}" class="btn btn-sm btn-outline-info me-2" title="Détails du ticket">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    @endif
                                                    @if(Auth::user()->typeUser === 0 || Auth::user()->can('répondre tickets'))
                                                        <a href="{{ route('message.ticket', $ticket->id) }}"  class="btn btn-sm btn-secondary me-2" title="Traiter ce ticket">
                                                            <i class="bi bi-chat-dots"></i>
                                                        </a>
                                                    @endif
                                                    @if(Auth::user()->typeUser === 0 || Auth::user()->can('clôturer tickets'))
                                                        <button type="button" class="btn btn-sm btn-warning me-2" title="Clôturer ce ticket" data-bs-toggle="modal" data-bs-target="#confirmCloseModal{{ $ticket->id }}">
                                                            <i class="bi bi-slash-circle"></i>
                                                        </button>
                                                        <div class="modal fade" id="confirmCloseModal{{ $ticket->id }}" tabindex="-1" aria-labelledby="confirmCloseModalLabel{{ $ticket->id }}" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                                                        <h5 class="modal-title" id="confirmCloseModalLabel{{ $ticket->id }}">Confirmation de clôture</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        Êtes-vous sûr de vouloir clôturer ce ticket ? Cette action est irreversible.
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Annuler</button>
                                                                        <form action="{{ route('tckt.close', $ticket->id) }}" method="POST" onsubmit="showLoading()">
                                                                            @csrf
                                                                            @method('PUT')
                                                                            <button type="submit" class="btn btn-danger"><i class="bi bi-check-circle"></i> Clôturer</button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Objet</th>
                                        <th>Ouvert par</th>
                                        <th>Description</th>
                                        <th>Ouvert le</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <nav aria-label="...">
                                <ul class="pagination justify-content-end">
                                    <li class="page-item {{ $tickets->onFirstPage() ? 'disabled' : '' }}">
                                        <a class="page-link" href="{{ $tickets->previousPageUrl() }}" tabindex="-1" aria-disabled="{{ $tickets->onFirstPage() }}">Précédent</a>
                                    </li>

                                    @for ($i = 1; $i <= $tickets->lastPage(); $i++)
                                        <li class="page-item {{ $i == $tickets->currentPage() ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $tickets->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endfor
                                    <li class="page-item {{ $tickets->hasMorePages() ? '' : 'disabled' }}">
                                        <a class="page-link" href="{{ $tickets->nextPageUrl() }}">Suivant</a>
                                    </li>
                                </ul>
                            </nav>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </section>
    <script>
        function showConfirmModal(id) {
            let blockModal = document.getElementById('blockModal' + id);
            let confirmModal = new bootstrap.Modal(document.getElementById('confirmBlockModal' + id));

            let motif = document.getElementById('motif' + id).value.trim();

            if (!motif) {
                alert("Veuillez saisir un motif avant de bloquer cette annonce.");
                return;
            }

            let bsBlockModal = bootstrap.Modal.getInstance(blockModal);
            bsBlockModal.hide();

            setTimeout(() => confirmModal.show(), 300);
        }

        function submitBlockForm(id) {
            showLoading(); // Afficher l'effet de chargement
            document.getElementById('blockForm' + id).submit();
        }

    </script>

@endsection
