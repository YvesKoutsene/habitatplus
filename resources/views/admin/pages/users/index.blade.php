@extends('admin.include.layouts.app')
@section('content')

@php
use Carbon\Carbon;
@endphp

<div class="pagetitle">
    <h1>Utilisateurs</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i></a></li>
            <li class="breadcrumb-item">Utilisateurs</li>
            <li class="breadcrumb-item active">Liste Utilisateurs</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Liste des utilisateurs</h5>
                        @if(Auth::user()->typeUser === 0 || Auth::user()->can('ajouter utilisateurs'))
                            <a href="{{ route('users.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Ajouter utilisateur
                            </a>
                        @endif
                    </div>

                    @if(!$users->isEmpty())
                        <div class="d-flex mb-3 justify-content-between">
                            <form action="{{ route('users.index') }}" method="GET" class="d-flex">
                                <select name="perPage" class="form-select me-2" onchange="this.form.submit()">
                                    <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5 entrées/page</option>
                                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 entrées/page</option>
                                    <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 entrées/page</option>
                                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 entrées/page</option>
                                </select>
                            </form>
                            <form action="{{ route('users.index') }}" method="GET" class="d-flex">
                                <input type="text" name="search" class="form-control me-2" placeholder="Taper ici pour chercher..." value="{{ request()->get('search') }}">
                                <button type="submit" class="btn btn-primary" title="Rechercher"><i class="bi bi-search"></i></button>
                            </form>
                        </div>
                    @endif

                    @if($users->isEmpty())
                    <div class="alert alert-info">
                        Aucun utilisateur disponible pour le moment.
                        @if(Auth::user()->typeUser === 0 || Auth::user()->can('ajouter utilisateurs'))
                            <a href="{{ route('users.create') }}" class="alert-link"> Ajouter utilisateur</a>
                        @endif
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                            <tr>
                                <th scope="col">Utilisateur</th>
                                <th scope="col">Email</th>
                                <th scope="col">Téléphone</th>
                                <th scope="col">Profil</th>
                                <th scope="col">Crée le</th>
                                <th scope="col">Vérifié le</th>
                                <th scope="col">Statut</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>
                                    <img src="{{ asset($user->photo_profil) }}" alt="Profil" class="rounded-circle" style="width: 35px; height: 35px; object-fit: cover;">
                                    | {{ $user->name }}
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>({{ $user->pays }}) {{ $user->numero }}</td>
                                <td>
                                <span class="badge bg-primary">
                                    {{ ucfirst($user->roles->first()) ? ucfirst($user->roles->first()->name) : 'Abonné' }}
                                </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($user->created_at)->translatedFormat('d F Y') }}</td>
                                <td>
                                    {!! $user->email_verified_at ? \Carbon\Carbon::parse($user->email_verified_at)->translatedFormat('d F Y') : '<span class="badge bg-info">Non vérifié</span>' !!}
                                </td>
                                <td>
                                <span class="badge {{ $user->statut == 'actif' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($user->statut) }}
                                </span>
                                    @if($user->statut == 'suspendu' && $user->motifBlocage != '')
                                    <span>
                                        <button type="button" class="btn btn-sm btn-link p-0" data-bs-toggle="modal" data-bs-target="#motifModal{{ $user->id }}">
                                            Motif
                                        </button>
                                        <div class="modal fade" id="motifModal{{ $user->id }}" tabindex="-1" aria-labelledby="motifModalLabel{{ $user->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="motifModalLabel{{ $user->id }}">Motif de suspension du compte de l'utilisateur "{{ ucfirst($user->name) }}"</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        {{ ucfirst($user->motifBlocage) }}
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
                                        @if(Auth::user()->typeUser === 0 || Auth::user()->can('editer utilisateurs'))
                                            @if($user->typeUser !== 2)
                                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Modifier">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            @endif
                                        @endif
                                        @if(Auth::user()->typeUser === 0 || Auth::user()->can('suspendre/réactiver utilisateurs'))
                                        @if(auth()->id() !== $user->id)
                                            @if($user->statut == 'actif')
                                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#blockModal{{ $user->id }}" title="Suspendre ce compte">
                                                <i class="bi bi-slash-circle"></i>
                                            </button>
                                            <div class="modal fade" id="blockModal{{ $user->id }}" tabindex="-1" aria-labelledby="blockModalLabel{{ $user->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="blockModalLabel{{ $user->id }}">Suspension du compte de l'utilisateur "{{ $user->name }}"</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                        </div>
                                                        <form id="blockForm{{ $user->id }}" action="{{ route('users.suspend', $user->id) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="motif{{ $user->id }}" class="form-label">Motif<span class="text-danger" title="Obligatoire">*</span></label>
                                                                    <textarea name="motif" class="form-control" id="motif{{ $user->id }}" rows="3" maxlength="200" placeholder="Donnez la raison de suspension de ce compte." required></textarea>
                                                                    <div class="invalid-feedback">
                                                                        Veuillez fournir une description valide.
                                                                    </div>
                                                                    <small class="text-muted">Ne pas dépasser 200 caractères maximum.</small>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Annuler</button>
                                                                <button type="button" class="btn btn-danger" onclick="showConfirmModal({{ $user->id }})"><i class="bi bi-check-circle"></i> Suspendre</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal fade" id="confirmBlockModal{{ $user->id }}" tabindex="-1" aria-labelledby="confirmBlockModalLabel{{ $user->id }}" aria-hidden="true">
                                                <div class="modal-dialog  modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <i class="bi bi-exclamation-triangle me-1"></i>
                                                            <h5 class="modal-title" id="confirmBlockModalLabel{{ $user->id }}">Confirmation du suspension</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Êtes-vous sûr de vouloir suspendre le compte de l'utilisateur "{{ $user->name }}" ? Ce compte ne sera plus accessible.</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Annuler</button>
                                                            <button type="button" class="btn btn-danger" onclick="submitBlockForm({{ $user->id }})"><i class="bi bi-check-circle"></i> Confirmer</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @else
                                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#confirmReactivateModal{{ $user->id }}">
                                                <i class="bi bi-check-circle" title="Réactiver ce compte"></i>
                                            </button>
                                            <div class="modal fade" id="confirmReactivateModal{{ $user->id }}" tabindex="-1" aria-labelledby="confirmReactivateModalLabel{{ $user->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <i class="bi bi-exclamation-triangle me-1"></i>
                                                            <h5 class="modal-title" id="confirmReactivateModalLabel{{ $user->id }}">Confirmation de réactivation.</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Êtes-vous sûr de vouloir réactiver le compte de l'utilisateur "{{ $user->name }}" ? Ce compte sera à nouveau accessible.
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Annuler</button>
                                                            <form action="{{ route('users.reactivate', $user->id) }}" method="POST">
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
                                        @if(Auth::user()->typeUser === 0)
                                            @if($user->typeUser === 1)
                                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-placement="top" title="Réinitialiser"> <!--data-bs-target="#deleteConfirmation{{ $user->id }}" -->
                                                <i class="bi bi-arrow-clockwise"></i>
                                            </button>
                                            @endif
                                        @endif
                                        <div class="modal fade" id="deleteConfirmation{{ $user->id }}" tabindex="-1" aria-labelledby="deleteConfirmationLabel{{ $user->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                                        <h5 class="modal-title" id="deleteConfirmationLabel{{ $user->id }}">Confirmation de Suppression</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Êtes-vous sûr de vouloir supprimer l'utilisateur "{{ $user->name }}" ? Cette action est irréversible.
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Annuler</button>
                                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i> Supprimer</button>
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
                                <th scope="col">Utilisateur</th>
                                <th scope="col">Email</th>
                                <th scope="col">Téléphone</th>
                                <th scope="col">Profil</th>
                                <th scope="col">Crée le</th>
                                <th scope="col">Vérifié le</th>
                                <th scope="col">Statut</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <nav aria-label="...">
                            <ul class="pagination justify-content-end">
                                <li class="page-item {{ $users->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $users->previousPageUrl() }}" tabindex="-1" aria-disabled="{{ $users->onFirstPage() }}">Précédent</a>
                                </li>

                                @for ($i = 1; $i <= $users->lastPage(); $i++)
                                    <li class="page-item {{ $i == $users->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $users->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor

                                <li class="page-item {{ $users->hasMorePages() ? '' : 'disabled' }}">
                                    <a class="page-link" href="{{ $users->nextPageUrl() }}">Suivant</a>
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
        document.getElementById('blockForm' + id).submit();
    }
</script>

@endsection
