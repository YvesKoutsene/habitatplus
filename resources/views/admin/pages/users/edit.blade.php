@extends('admin.include.layouts.app')
@section('content')

<div class="pagetitle">
    <h1>Modifier Utilisateur</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i></a></li>
            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Liste Utilisateurs</a></li>
            <li class="breadcrumb-item active">Modifier Utilisateur</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <form method="POST" action="{{ route('users.update', $user->id) }}" class="needs-validation" enctype="multipart/form-data" novalidate>
                @csrf
                @method('PUT')

                <!-- Carte Photo de Profil -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Photo de profil</h5>
                        <div class="row mb-3">
                            <div class="col-sm-12 text-center">
                                <img id="image-preview-img" src="{{ $user->photo_profil }}" alt="Aperçu de l'image" class="img-thumbnail"  width="150">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="formFile" class="col-sm-4 col-form-label"> </label>
                            <div class="col-sm-4 text-center">
                                <label for="uploadImage" class="btn btn-primary btn-sm" title="Téléverser une nouvelle image">
                                    <i class="bi bi-upload"></i> Téléverser
                                </label>
                                <input type="file" name="photo_profil" id="uploadImage"  onchange="previewImage(this)" class="d-none" accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Carte Informations sur l'Utilisateur -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Information sur l'utilisateur</h5>

                        <!-- Nom -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom<span class="text-danger" title="obligatoire">*</span></label>
                            <input type="text" name="name" class="form-control" id="name" value="{{ old('name', $user->name) }}" required placeholder="Nom de l'utilisateur">
                            @error('name')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email<span class="text-danger" title="obligatoire">*</span></label>
                            <input type="email" name="email" class="form-control" id="email" value="{{ old('email', $user->email) }}" required placeholder="Email de l'utilisateur">
                            @error('email')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Téléphone -->
                        <div class="mb-3">
                            <label for="inputState" class="form-label">Téléphone<span class="text-danger" title="obligatoire">*</span></label>
                            <div class="row">
                                <div class="col-md-4">
                                    <select id="countryCode" name="pays" class="form-select" required>
                                        <option value="{{ old('pays', $user->pays) }}">{{ old('pays', $user->pays) }}</option>
                                    </select>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="numero" class="form-control" id="numero" required oninput="validateInput()" value="{{ old('numero', $user->numero) }}" placeholder="Numero de téléphone de l'utilisateur">
                                    <div class="invalid-feedback">Veuillez entrer le numéro!</div>
                                </div>
                            </div>
                        </div>
                        <!--
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Mot de passe actuel<span class="text-info" title="Obligatoire pour changer le mot de passe">*</span></label>
                            <div class="input-group">
                                <input type="password" name="current_password" class="form-control" id="current_password" placeholder="Actuel mot de passe de l'utilisateur">
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('current_password')">
                                    <i class="bi bi-eye" id="eye-icon-current-password"></i>
                                </button>
                            </div>
                            @error('current_password')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Nouveau mot de passe<span class="text-info" title="Laisser vide si pas de changement">*</span></label>
                            <div class="input-group">
                                <input type="password" name="password" class="form-control" id="password" placeholder="Nouveau mot de passe de l'utilisateur">
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password')">
                                    <i class="bi bi-eye" id="eye-icon-password"></i>
                                </button>
                            </div>
                            @error('password')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmer le nouveau mot de passe<span class="text-info" title="Laisser vide si pas de changement">*</span></label>
                            <div class="input-group">
                                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Confirmation du nouveau mot de passe">
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password_confirmation')">
                                    <i class="bi bi-eye" id="eye-icon-confirmation"></i>
                                </button>
                            </div>
                        </div>
                        -->

                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Rôle du compte</h5>
                        <!-- Rôle -->
                        <div class="mb-3">
                            <label for="role" class="form-label">Rôle<span class="text-danger">*</span></label>
                            <select name="role" class="form-select" {{ auth()->id() === $user->id ? 'disabled' : '' }} required>
                                <option value="">Choisir un rôle...</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ $user->hasRole($role->id) ? 'selected' : '' }}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                            @if(auth()->id() === $user->id)
                                <input type="hidden" name="role" value="{{ $user->roles->first()->id }}">
                            @endif
                            @error('role')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Boutons -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-check2-circle"></i> Mettre à jour</button>
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Retour
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Script -->
<script>
    // Aperçu de l'image
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('image-preview-img').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Validation des champs de téléphone
    function validateInput() {
        const input = document.getElementById('numero');
        input.value = input.value.replace(/[^0-9]/g, '');
    }

    // Toggle visibilité du mot de passe
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(inputId === 'password' ? 'eye-icon-password' : 'eye-icon-confirmation');

        if (input.type === "password") {
            input.type = "text";
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = "password";
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }

    // Charger la liste des indicatifs téléphoniques
    document.addEventListener("DOMContentLoaded", function() {
        fetch('https://restcountries.com/v3.1/all')
            .then(response => response.json())
            .then(data => {
                const selectElement = document.getElementById("countryCode");
                data.sort((a, b) => a.name.common.localeCompare(b.name.common));
                data.forEach(country => {
                    const indicatif = country.idd.root + (country.idd.suffixes ? country.idd.suffixes[0] : '');
                    if (indicatif) {
                        const option = document.createElement("option");
                        option.value = indicatif;
                        option.textContent = `${indicatif} - ${country.name.common}`;
                        selectElement.appendChild(option);
                    }
                });
                selectElement.value = "{{ old('pays', $user->pays) }}"; // Pré-sélectionner le pays
            })
            .catch(console.error);
    });
</script>

@endsection
