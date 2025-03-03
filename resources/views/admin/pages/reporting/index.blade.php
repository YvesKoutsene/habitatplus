@extends('admin.include.layouts.app')

@section('content')

<div class="pagetitle">
    <h1>Rapports</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i></a></li>
            <li class="breadcrumb-item">Rapports</li>
            <li class="breadcrumb-item active">Annonces Signalées</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Annonces signalées</h5>
                    </div>

                    @if(!$signalements->isEmpty())
                    <div class="d-flex mb-3 justify-content-between">
                        <form action="{{ route('report.index') }}" method="GET" class="d-flex">
                            <select name="perPage" class="form-select me-2" onchange="this.form.submit()">
                                <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5 entrées/page</option>
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 entrées/page</option>
                                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 entrées/page</option>
                                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 entrées/page</option>
                            </select>
                        </form>
                        <form action="{{ route('report.index') }}" method="GET" class="d-flex">
                            <input type="text" name="search" class="form-control me-2" placeholder="Taper ici pour chercher..." value="{{ request()->get('search') }}">
                            <button type="submit" class="btn btn-primary" title="Rechercher"><i class="bi bi-search"></i></button>
                        </form>
                    </div>
                    @endif

                    @if($signalements->isEmpty())
                    <div class="alert alert-info">
                        Aucune annonce de bien signalée pour le moment.
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                            <tr>
                                <th>Annonce signalée</th>
                                <th>Propriétaire</th>
                                <th>Prix (FCFA)</th>
                                <th>Caractéristiques</th>
                                <th>Total signalement</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($signalements as $signal)
                            <tr>
                                <td>
                                    @if(strlen($signal->bien->titre) > 8)
                                    {{ ucfirst(substr($signal->bien->titre, 0, 8)) }}...
                                    <button type="button" class="btn btn-sm btn-link p-0" data-bs-toggle="modal" data-bs-target="#descriptionModal{{ $signal->bien->id }}">
                                        Lire suite
                                    </button>
                                    <div class="modal fade" id="descriptionModal{{ $signal->bien->id }}" tabindex="-1" aria-labelledby="descriptionModalLabel{{ $signal->bien->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="descriptionModalLabel{{ $signal->bien->id }}">Titre complet</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    {{ ucfirst($signal->bien->titre) }}
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Fermer</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                    {{ ucfirst($signal->bien->titre) }}
                                    @endif
                                </td>
                                <td>
                                    <img src="{{ asset($signal->bien->user->photo_profil) }}" alt="Profil" class="rounded-circle" style="width: 35px; height: 35px; object-fit: cover;">
                                    | {{ $signal->bien->user->name }}
                                </td>
                                <td>{{ number_format($signal->bien->prix, 0, ',', ' ') }}</td>
                                <td>
                                    @if($signal->bien->valeurs->isNotEmpty())
                                    @foreach($signal->bien->valeurs->take(1) as $valeur)
                                    @if($valeur)
                                    <span class="badge bg-success">
                                    {{ $valeur->associationCategorie->parametre->nom_parametre }}
                                </span> :
                                    <span class="badge bg-warning">
                                    {{ $valeur->valeur }}
                                </span>
                                    @endif
                                    @endforeach
                                    @if($signal->bien->valeurs->count() > 1)
                                    <button type="button" class="btn btn-sm btn-link p-0" data-bs-toggle="modal" data-bs-target="#parametersModal{{ $signal->bien->id }}">
                                        Voir plus ({{ $signal->bien->valeurs->count() - 1 }})
                                    </button>
                                    <div class="modal fade" id="parametersModal{{ $signal->bien->id }}" tabindex="-1" aria-labelledby="parametersModalLabel{{ $signal->bien->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="parametersModalLabel{{ $signal->bien->id }}">Valeur du bien {{ ucfirst($signal->bien->titre) }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <ul class="list-group">
                                                        @foreach($signal->bien->valeurs as $valeur)
                                                        @if($valeur)
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            {{ $valeur->associationCategorie->parametre->nom_parametre }}
                                                            <span class="badge bg-primary rounded-pill">
                                                            {{ $valeur->valeur }}
                                                        </span>
                                                        </li>
                                                        @endif
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        <i class="bi bi-x-circle"></i> Fermer
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @else
                                    <span class="text-muted">Aucune valeur associée</span>
                                    @endif
                                </td>
                                <td>{{ $signal->total_signals }}</td>
                                <td>
                                <span class="badge {{ $classe = ($signal->bien->statut == 'publié') ? 'bg-primary' : (($signal->bien->statut == 'terminé') ? 'bg-warning' : 'bg-danger') }}">
                                    {{ ucfirst($signal->bien->statut) }}
                                </span>
                                    @if($signal->bien->statut == 'bloqué' && $signal->bien->motifBlocage != '')
                                    <span>
                                        <button type="button" class="btn btn-sm btn-link p-0" data-bs-toggle="modal" data-bs-target="#motifModal{{ $signal->bien->id }}">
                                            Motif
                                        </button>

                                        <div class="modal fade" id="motifModal{{ $signal->bien->id }}" tabindex="-1" aria-labelledby="motifModalLabel{{ $signal->bien->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="motifModalLabel{{ $signal->bien->id }}">Motif de suspension de l'annonce "{{ ucfirst($signal->bien->titre) }}"</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        {{ ucfirst($signal->bien->motifBlocage) }}
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Fermer</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex">
                                        @if(Auth::user()->typeUser === 0 || Auth::user()->can('voir annonces'))
                                        <a href="{{ route('announcement.details', $signal->bien->id) }}" class="btn btn-sm btn-outline-info me-2" title="Détails de l'annonce">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @endif
                                        @if(Auth::user()->typeUser === 0 || Auth::user()->can('voir signalements d\'une annonce'))
                                        <a href="{{ route('report.show', $signal->bien->id) }}" class="btn btn-sm btn-info me-2" title="Information signalement">
                                            <i class="bi bi-flag"></i>
                                        </a>
                                        @endif
                                        @if(Auth::user()->typeUser === 0 || Auth::user()->can('suspendre/réactiver annonces'))
                                            @if($signal->bien->statut == 'publié')
                                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#blockModal{{ $signal->bien->id }}" title="Bloquer cette annonce">
                                                    <i class="bi bi-slash-circle"></i>
                                                </button>
                                                <!-- Modal pour le motif de blocage -->
                                                <div class="modal fade" id="blockModal{{ $signal->bien->id }}" tabindex="-1" aria-labelledby="blockModalLabel{{ $signal->bien->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="blockModalLabel{{ $signal->bien->id }}">Blocage de l'annonce "{{ $signal->bien->titre }}"</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                            </div>
                                                            <form id="blockForm{{ $signal->bien->id }}" action="{{ route('announcement.block', $signal->bien->id) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label for="motif{{ $signal->bien->id }}" class="form-label">Motif<span class="text-danger" title="Obligatoire">*</span></label>
                                                                        <textarea name="motif" class="form-control" id="motif{{ $signal->bien->id }}" rows="3" maxlength="200" placeholder="Donnez la raison de suspension de cette annonce." required></textarea>
                                                                        <div class="invalid-feedback">
                                                                            Veuillez fournir une description valide.
                                                                        </div>
                                                                        <small class="text-muted">Ne pas dépasser 200 caractères maximum.</small>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Annuler</button>
                                                                    <button type="button" class="btn btn-danger" onclick="showConfirmModal({{ $signal->bien->id }})"><i class="bi bi-check-circle"></i> Bloquer</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal fade" id="confirmBlockModal{{ $signal->bien->id }}" tabindex="-1" aria-labelledby="confirmBlockModalLabel{{ $signal->bien->id }}" aria-hidden="true">
                                                    <div class="modal-dialog  modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <i class="bi bi-exclamation-triangle me-1"></i>
                                                                <h5 class="modal-title" id="confirmBlockModalLabel{{ $signal->bien->id }}">Confirmation du blocage</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Êtes-vous sûr de vouloir bloquer l'annonce "{{ $signal->bien->titre }}" ? Elle deviendra invisible par les abonnés.</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Annuler</button>
                                                                <button type="button" class="btn btn-danger" onclick="submitBlockForm({{ $signal->bien->id }})"><i class="bi bi-check-circle"></i> Confirmer</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif($signal->bien->statut == 'bloqué')
                                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#confirmReactivateModal{{ $signal->bien->id }}">
                                                    <i class="bi bi-check-circle" title="Réactiver cette annonce"></i>
                                                </button>
                                                <div class="modal fade" id="confirmReactivateModal{{ $signal->bien->id }}" tabindex="-1" aria-labelledby="confirmReactivateModalLabel{{ $signal->bien->id }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <i class="bi bi-exclamation-triangle me-1"></i>
                                                                <h5 class="modal-title" id="confirmReactivateModalLabel{{ $signal->bien->id }}">Confirmation de réactivation.</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Êtes-vous sûr de vouloir réactiver l'annonce "{{ $signal->bien->titre }}" ? Elle redeviendra visible par les abonnés.
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Annuler</button>
                                                                <form action="{{ route('announcement.reactivate', $signal->bien->id) }}" method="POST" onsubmit="showLoading()">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <button type="submit" class="btn btn-danger"><i class="bi bi-check-circle"></i> Réactiver</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Annonce signalée</th>
                                <th>Propriétaire</th>
                                <th>Prix (FCFA)</th>
                                <th>Caractéristiques</th>
                                <th>Total signalement</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <nav aria-label="...">
                        <ul class="pagination justify-content-end">
                            <li class="page-item {{ $signalements->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $signalements->previousPageUrl() }}" tabindex="-1" aria-disabled="{{$signalements->onFirstPage() }}">Précédent</a>
                            </li>

                            @for ($i = 1; $i <= $signalements->lastPage(); $i++)
                            <li class="page-item {{ $i == $signalements->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $signalements->url($i) }}">{{ $i }}</a>
                            </li>
                            @endfor
                            <li class="page-item {{ $signalements->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link" href="{{ $signalements->nextPageUrl() }}">Suivant</a>
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
            alert("Veuillez saisir un motif avant de bloquer l'annonce.");
            return;
        }

        let bsBlockModal = bootstrap.Modal.getInstance(blockModal);
        bsBlockModal.hide();

        setTimeout(() => confirmModal.show(), 300);
    }

    function submitBlockForm(id) {
        showLoading();
        document.getElementById('blockForm' + id).submit();
    }
</script>

@endsection
