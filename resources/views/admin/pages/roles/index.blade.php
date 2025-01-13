@extends('admin.include.layouts.app')
@section('content')

@php
use Carbon\Carbon;
@endphp

<div class="pagetitle">
    <h1>Rôles</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i></a></li>
            <li class="breadcrumb-item">Rôles</li>
            <li class="breadcrumb-item active">Liste Rôles</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Liste des rôles</h5>
                        @if(Auth::user()->typeUser === 0 || Auth::user()->can('créer rôles'))
                            <a href="{{ route('roles.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Créer rôle
                            </a>
                        @endif
                    </div>

                    @if(!$roles->isEmpty())
                        <div class="d-flex mb-3 justify-content-between">
                            <form action="{{ route('roles.index') }}" method="GET" class="d-flex">
                                <select name="perPage" class="form-select me-2" onchange="this.form.submit()">
                                    <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5 entrées/page</option>
                                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 entrées/page</option>
                                    <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 entrées/page</option>
                                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 entrées/page</option>
                                </select>
                            </form>
                            <form action="{{ route('roles.index') }}" method="GET" class="d-flex">
                                <input type="text" name="search" class="form-control me-2" placeholder="Taper ici pour chercher..." value="{{ request()->get('search') }}">
                                <button type="submit" class="btn btn-primary" title="Rechercher"><i class="bi bi-search"></i></button>
                            </form>
                        </div>
                    @endif

                    @if($roles->isEmpty())
                        <div class="alert alert-info">
                            Aucun rôle disponible pour le moment.
                            @if(Auth::user()->typeUser === 0 || Auth::user()->can('créer rôles'))
                                <a href="{{ route('roles.create') }}" class="alert-link"> Créer rôle</a>
                            @endif
                        </div>
                    @else
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nom</th>
                                    <th scope="col">Permissions associées</th>
                                    <th scope="col">Date création</th>
                                    <th scope="col">Statut</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roles as $role)
                                <tr>
                                    <td>{{ $role->id }}</td>
                                    <td>{{ ucfirst($role->name) }}</td>
                                    <td>
                                        @if($role->permissions->isNotEmpty())
                                            @foreach($role->permissions->take(1) as $permission)
                                                <span class="badge bg-success">{{ ucfirst($permission->name) }}</span>
                                            @endforeach
                                            @if($role->permissions->count() >1)
                                                <button class="btn btn-sm btn-link p-0" data-bs-toggle="modal" data-bs-target="#permissionsModal{{ $role->id }}"> <!--class="btn btn-link"-->
                                                    Voir plus ({{ $role->permissions->count() - 1 }})
                                                </button>
                                            @endif
                                        @else
                                            <span class="text-muted">Aucune permission associée</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($role->created_at)->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge {{ $role->statut == 'actif' ? 'bg-success' : 'bg-danger' }}">
                                            {{ ucfirst($role->statut) }}
                                        </span>
                                    </td>

                                    <td>
                                    <div class="d-flex">
                                        @if(Auth::user()->typeUser === 0 || Auth::user()->can('désactiver/réactiver rôles'))
                                            @if($role->statut == 'actif')
                                            <form action="{{ route('roles.suspend', $role->id) }}" method="POST" class="me-2">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Désactiver">
                                                    <i class="bi bi-slash-circle"></i>
                                                </button>
                                            </form>
                                            @else
                                            <form action="{{ route('roles.reactivate', $role->id) }}" method="POST" class="me-2">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Réactiver">
                                                    <i class="bi bi-check-circle"></i>
                                                </button>
                                            </form>
                                            @endif
                                        @endif

                                        @if(Auth::user()->typeUser === 0 || Auth::user()->can('editer rôles'))
                                        <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning btn-sm me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Modifier">
                                                <i class="bi bi-pencil-square"></i>
                                        </a>
                                        @endif

                                        @if(Auth::user()->typeUser === 0 || Auth::user()->can('supprimer rôles'))
                                            @if($role && $role->users->isEmpty())
                                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteConfirmation{{ $role->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                            @endif
                                        @endif

                                        <!-- Modal de confirmation de suppression -->
                                        <div class="modal fade" id="deleteConfirmation{{ $role->id }}" tabindex="-1" aria-labelledby="deleteConfirmationLabel{{ $role->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                                        <h5 class="modal-title" id="deleteConfirmationLabel{{ $role->id }}">Confirmation de Suppression</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Êtes-vous sûr de vouloir supprimer le rôle "{{ $role->name }}" ? Cette action est irréversible.
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Annuler</button>
                                                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i> Supprimer</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Fin de la Modal -->
                                    </div>
                                </td>
                            </tr>

                                <!-- Modale pour afficher toutes les permissions -->
                                <div class="modal fade" id="permissionsModal{{ $role->id }}" tabindex="-1" aria-labelledby="permissionsModalLabel{{ $role->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="permissionsModalLabel{{ $role->id }}">Permissions pour {{ ucfirst($role->name) }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <ul class="list-group">
                                                    @foreach($role->permissions as $permission)
                                                        <li class="list-group-item">{{ ucfirst($permission->name) }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Fermer</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nom</th>
                                    <th scope="col">Permissions associées</th>
                                    <th scope="col">Date création</th>
                                    <th scope="col">Statut</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </tfoot>
                        </table>

                        <!-- Pagination personnalisée -->
                    <nav aria-label="...">
                            <ul class="pagination justify-content-end">
                                <li class="page-item {{ $roles->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $roles->previousPageUrl() }}" tabindex="-1" aria-disabled="{{ $roles->onFirstPage() }}">Précédent</a>
                                </li>

                                @for ($i = 1; $i <= $roles->lastPage(); $i++)
                                    <li class="page-item {{ $i == $roles->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $roles->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor

                                <li class="page-item {{ $roles->hasMorePages() ? '' : 'disabled' }}">
                                    <a class="page-link" href="{{ $roles->nextPageUrl() }}">Suivant</a>
                                </li>
                            </ul>
                    </nav>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
