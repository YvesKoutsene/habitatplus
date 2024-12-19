@extends('admin.include.layouts.app')
@section('content')

@php
use Carbon\Carbon;
@endphp

<div class="pagetitle">
    <h1>Utilisateurs</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
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
                        <a href="{{ route('users.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Ajouter utilisateur
                        </a>
                    </div>

                    @if($users->isEmpty())
                    <div class="alert alert-info">
                        Aucun utilisateur disponible pour le moment. <a href="{{ route('users.create') }}" class="alert-link"> Ajouter utilisateur</a>.
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
                                    @if(!$user->roles->pluck('name')->contains('Abonné'))
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Modifier">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    @endif

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

                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteConfirmation{{ $user->id }}" data-bs-placement="top" title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </button>

                                    <!-- Modal de confirmation de suppression -->
                                    <div class="modal fade" id="deleteConfirmation{{ $user->id }}" tabindex="-1" aria-labelledby="deleteConfirmationLabel{{ $user->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteConfirmationLabel{{ $user->id }}">Confirmation de Suppression</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Êtes-vous sûr de vouloir supprimer l'utilisateur "{{ $user->name }}" ? Cette action est irréversible.
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Supprimer</button>
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
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@endsection