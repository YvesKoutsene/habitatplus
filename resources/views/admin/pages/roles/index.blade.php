@extends('admin.include.layouts.app')
@section('content')

@php
use Carbon\Carbon;
@endphp

<div class="pagetitle">
    <h1>Rôles</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
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
                        <a href="{{ route('roles.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Ajouter un rôle
                        </a>
                    </div>

                    @if($roles->isEmpty())
                        <div class="alert alert-info">
                            Aucun rôle disponible pour le moment. <a href="{{ route('roles.create') }}" class="alert-link">Créer un rôle</a>.
                        </div>
                    @else
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nom</th>
                                    <th scope="col">Permissions associées</th>
                                    <th scope="col">Date création</th>
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
                                            @foreach($role->permissions->take(3) as $permission)
                                                <span class="badge bg-success">{{ ucfirst($permission->name) }}</span>
                                            @endforeach
                                            @if($role->permissions->count() > 3)
                                                <button class="btn btn-link" data-bs-toggle="modal" data-bs-target="#permissionsModal{{ $role->id }}">
                                                    Voir plus ({{ $role->permissions->count() - 3 }})
                                                </button>
                                            @endif
                                        @else
                                            <span class="text-muted">Aucune permission associée</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($role->created_at)->format('d M Y') }}</td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning btn-sm me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Modifier">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="{{ route('roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce rôle ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Supprimer">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
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
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
