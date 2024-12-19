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
            <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Liste Rôles</a></li>
            <li class="breadcrumb-item active">Ajouter Rôle</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <!-- Formulaire de création de rôle -->
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Formulaire de création de rôle</h5>
                    <form method="POST" action="{{ route('roles.store') }}" class="needs-validation" novalidate >
                        @csrf
                        <!-- Nom du rôle -->
                        <div class="mb-3">
                            <label for="roleName" class="form-label">Nom<span class="text-danger">*</span></label>
                            <input
                                type="text"
                                class="form-control @error('name') is-invalid @enderror"
                                id="roleName"
                                name="name"
                                placeholder="Nom du rôle"
                                value="{{ old('name') }}"
                                required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Permissions -->
                        <div class="mb-3">
                            <label class="form-label">Permissions<span class="text-danger">*</span></label>
                            <div class="accordion" id="permissionsAccordion">
                                @foreach($permissions as $parentPermission)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading{{ $parentPermission->id }}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapse{{ $parentPermission->id }}" aria-expanded="false"
                                                aria-controls="collapse{{ $parentPermission->id }}">
                                            {{ ucfirst($parentPermission->name) }}
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $parentPermission->id }}" class="accordion-collapse collapse"
                                         aria-labelledby="heading{{ $parentPermission->id }}" data-bs-parent="#permissionsAccordion">
                                        <div class="accordion-body">
                                            <div class="row">
                                                @foreach($parentPermission->children as $childPermission)
                                                <div class="col-6 mb-2">
                                                    <div class="form-check">
                                                        <input
                                                            class="form-check-input @error('permissions') is-invalid @enderror"
                                                            type="checkbox"
                                                            name="permissions[]"
                                                            id="permission{{ $childPermission->id }}"
                                                            value="{{ $childPermission->id }}"
                                                            {{ is_array(old('permissions')) && in_array($childPermission->id, old('permissions')) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="permission{{ $childPermission->id }}">
                                                            {{ ucfirst($childPermission->name) }}
                                                        </label>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @error('permissions')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Boutons -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Créer</button>
                            <button type="reset" class="btn btn-secondary">Réinitialiser</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Liste des permissions choisies -->
        <div class="col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Liste de permissions choisies</h5>
                    <div id="selected-permissions" class="mb-3">
                        <span class="text-muted">Aucune permission sélectionnée pour le moment.</span>
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn btn-outline-info" onclick="previewSelectedPermissions()">Prévisualiser</button>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</section>

<script>
    function previewSelectedPermissions() {
        const checkboxes = document.querySelectorAll('input[name="permissions[]"]:checked');
        const selectedPermissions = Array.from(checkboxes).map(checkbox => checkbox.nextElementSibling.innerText);
        const previewDiv = document.getElementById('selected-permissions');

        if (selectedPermissions.length > 0) {
            previewDiv.innerHTML = `<ul class="list-group">` +
                selectedPermissions.map(permission => `<li class="list-group-item">${permission}</li>`).join('') +
                `</ul>`;
        } else {
            previewDiv.innerHTML = '<span class="text-muted">Aucune permission sélectionnée pour le moment.</span>';
        }
    }

    // Validation Bootstrap
    (function () {
        'use strict'
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            });
        });
    })();
</script>

@endsection