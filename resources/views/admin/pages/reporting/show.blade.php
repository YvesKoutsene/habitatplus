@extends('admin.include.layouts.app')

@section('content')

<div class="pagetitle">
    <h1>Rapports</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i></a></li>
            <li class="breadcrumb-item">Rapports</li>
            <li class="breadcrumb-item active">Signalements</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Liste des signalements pour l'annonce "{{ $bien->titre }}"</h5>
                    </div>

                    @if(!$signalements->isEmpty())
                    <div class="d-flex mb-3 justify-content-between">
                        <form action="{{ route('report.show', ['bien' => $bien->id]) }}" method="GET" class="d-flex">
                            <select name="perPage" class="form-select me-2" onchange="this.form.submit()">
                                <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5 entrées/page</option>
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 entrées/page</option>
                                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 entrées/page</option>
                                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 entrées/page</option>
                            </select>
                        </form>
                        <form action="{{ route('report.show', ['bien' => $bien->id]) }}" method="GET" class="d-flex">
                            <input type="text" name="search" class="form-control me-2" placeholder="Taper ici pour chercher..." value="{{ request()->get('search') }}">
                            <button type="submit" class="btn btn-primary" title="Rechercher"><i class="bi bi-search"></i></button>
                        </form>
                    </div>
                    @endif

                    @if($signalements->isEmpty())
                    <div class="alert alert-info">
                        Aucun signalement pour cette annonce.
                    </div>
                    @else
                    <table class="table table-hover table-striped">
                        <thead>
                        <tr>
                            <th>Signaleur</th>
                            <th>Motif signalement</th>
                            <th>Signalé le</th>
                            <th>Statut signaleur</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($signalements as $signal)
                        <tr>
                            <td>
                                <img src="{{ asset($signal->user->photo_profil) }}" alt="Profil" class="rounded-circle" style="width: 35px; height: 35px; object-fit: cover;">
                                | {{ $signal->user->name }}
                            </td>
                            <td>
                                @if(strlen($signal->motif) > 10)
                                {{ ucfirst(substr($signal->motif, 0, 10)) }}...
                                <button type="button" class="btn btn-sm btn-link p-0" data-bs-toggle="modal" data-bs-target="#descriptionModal{{ $signal->id }}">
                                    Lire la suite
                                </button>
                                <div class="modal fade" id="descriptionModal{{ $signal->id }}" tabindex="-1" aria-labelledby="descriptionModalLabel{{ $signal->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="descriptionModalLabel{{ $signal->id }}">Motif complet du signaleur "{{$signal->user->name}}"</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                {{ ucfirst($signal->motif) }}
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Fermer</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @else
                                {{ ucfirst($signal->motif) }}
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($signal->created_at)->translatedFormat('d F Y') }}</td>
                            <td>
                                <span class="badge {{ $signal->user->statut == 'actif' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($signal->user->statut) }}
                                </span>

                                @if($signal->user->statut == 'suspendu' && $signal->user->motif_blocage != '')
                                    <span>
                                        <button type="button" class="btn btn-sm btn-link p-0" data-bs-toggle="modal" data-bs-target="#motifModal{{ $signal->user->id }}">
                                            Motif
                                        </button>
                                        <div class="modal fade" id="motifModal{{ $signal->user->id }}" tabindex="-1" aria-labelledby="motifModalLabel{{ $signal->user->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="motifModalLabel{{ $signal->user->id }}">Motif de suspension du compte de l'utilisateur "{{ ucfirst($signal->user->name) }}"</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        {{ ucfirst($signal->user->motif_blocage) }}
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
                                    @if(Auth::user()->typeUser === 0 || Auth::user()->can('suspendre/réactiver utilisateurs'))
                                        @if($signal->user->statut == 'actif')
                                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#blockModal{{ $signal->user->id }}" title="Suspendre ce compte">
                                                <i class="bi bi-slash-circle"></i>
                                            </button>
                                            <div class="modal fade" id="blockModal{{ $signal->user->id }}" tabindex="-1" aria-labelledby="blockModalLabel{{ $signal->user->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="blockModalLabel{{ $signal->user->id }}">Suspension du compte de l'utilisateur "{{ $signal->user->name }}"</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                        </div>
                                                        <form id="blockForm{{ $signal->user->id }}" action="{{ route('users.suspend', $signal->user->id) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="motif{{ $signal->user->id }}" class="form-label">Motif<span class="text-danger" title="Obligatoire">*</span></label>
                                                                    <textarea name="motif" class="form-control" id="motif{{ $signal->user->id }}" rows="3" maxlength="200" placeholder="Donnez la raison de suspension de ce compte." required></textarea>
                                                                    <div class="invalid-feedback">
                                                                        Veuillez fournir une description valide.
                                                                    </div>
                                                                    <small class="text-muted">Ne pas dépasser 200 caractères maximum.</small>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Annuler</button>
                                                                <button type="button" class="btn btn-danger" onclick="showConfirmModal({{ $signal->user->id }})"><i class="bi bi-check-circle"></i> Suspendre</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal fade" id="confirmBlockModal{{ $signal->user->id }}" tabindex="-1" aria-labelledby="confirmBlockModalLabel{{ $signal->user->id }}" aria-hidden="true">
                                                <div class="modal-dialog  modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <i class="bi bi-exclamation-triangle me-1"></i>
                                                            <h5 class="modal-title" id="confirmBlockModalLabel{{ $signal->user->id }}">Confirmation du suspension</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Êtes-vous sûr de vouloir suspendre le compte de l'utilisateur "{{ $signal->user->name }}" ? Ce compte ne sera plus accessible.</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Annuler</button>
                                                            <button type="button" class="btn btn-danger" onclick="submitBlockForm({{ $signal->user->id }})"><i class="bi bi-check-circle"></i> Confirmer</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#confirmReactivateModal{{ $signal->user->id }}">
                                                <i class="bi bi-check-circle" title="Réactiver ce compte"></i>
                                            </button>
                                            <div class="modal fade" id="confirmReactivateModal{{ $signal->user->id }}" tabindex="-1" aria-labelledby="confirmReactivateModalLabel{{ $signal->user->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <i class="bi bi-exclamation-triangle me-1"></i>
                                                            <h5 class="modal-title" id="confirmReactivateModalLabel{{ $signal->user->id }}">Confirmation de réactivation.</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Êtes-vous sûr de vouloir réactiver le compte de l'utilisateur "{{ $signal->user->name }}" ? Ce compte sera à nouveau accessible.
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Annuler</button>
                                                            <form action="{{ route('users.reactivate', $signal->user->id) }}" method="POST" onsubmit="showLoading()">
                                                                @csrf
                                                                @method('PATCH')
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
                            <th>Signaleur</th>
                            <th>Motif signalement</th>
                            <th>Signalé le</th>
                            <th>Statut signaleur</th>
                            <th>Actions</th>
                        </tr>
                        </tfoot>
                    </table>

                    <nav aria-label="Pagination">
                        <ul class="pagination justify-content-end">
                            <li class="page-item {{ $signalements->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $signalements->previousPageUrl() }}" tabindex="-1">Précédent</a>
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
            alert("Veuillez saisir un motif avant de suspendre ce compte.");
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
