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
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Utilisateur</th>
                                <th scope="col">Email</th>
                                <th scope="col">Téléphone</th>
                                <th scope="col">Rôle</th>
                                <th scope="col">Crée le</th>
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
                                <span class="badge bg-primary">{{ ucfirst($user->roles->first()->name) }}</span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d M Y') }}</td>
                            <td>
                                <span class="badge {{ $user->statut == 'actif' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($user->statut) }}
                                </span>
                            </td>

                            <td>
                                <div class="d-flex">

                                    @if(Auth::user()->typeUser === 0 || Auth::user()->can('editer utilisateurs'))
                                        @if(!$user->roles->pluck('name')->contains('Abonné'))
                                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Modifier">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                        @endif
                                    @endif

                                    @if(Auth::user()->typeUser === 0 || Auth::user()->can('suspendre/réactiver utilisateurs'))
                                        @if(auth()->id() !== $user->id)
                                            @if($user->statut == 'actif')
                                            <form action="{{ route('users.suspend', $user->id) }}" method="POST" class="me-2">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Suspendre">
                                                    <i class="bi bi-slash-circle"></i>
                                                </button>
                                            </form>
                                            @else
                                            <form action="{{ route('users.reactivate', $user->id) }}" method="POST" class="me-2">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Réactiver">
                                                    <i class="bi bi-check-circle"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endif

                                    @if(Auth::user()->typeUser === 0)
                                        @if($user->typeUser === 1)
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-placement="top" title="Réinitialiser"> <!--data-bs-target="#deleteConfirmation{{ $user->id }}" -->
                                            <i class="bi bi-arrow-clockwise"></i>
                                        </button>
                                        @endif
                                    @endif

                                    <!-- Modal de confirmation de suppression -->
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
                                    <!-- Fin de la Modal -->
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
                                <th scope="col">Rôle</th>
                                <th scope="col">Crée le</th>
                                <th scope="col">Statut</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </tfoot>
                    </table>
                    <!-- Pagination personnalisée -->
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

@endsection
