@extends('admin.include.layouts.app')
@section('content')

<div class="pagetitle">
    <h1>Utilisateurs</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i></a></li>
            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Liste Utilisateurs</a></li>
            <li class="breadcrumb-item active">Ajouter Utilisateur</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row justify-content-center">
        <div class="col-lg-6">
        <form method="POST" action="{{ route('users.store') }}" class="needs-validation" enctype="multipart/form-data" novalidate>
        @csrf
            <!-- Carte Photo de Profil -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Photo de profil</h5>
                    <div class="row mb-3">
                        <div class="col-sm-12 text-center">
                            <img id="image-preview-img" alt="Aperçu de l'image" class="img-thumbnail"  width="150">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="formFile" class="col-sm-4 col-form-label"> </label>
                        <div class="col-sm-4 text-center">
                              <label for="uploadImage" class="btn btn-primary btn-sm" title="Téléverser une image">
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
                    <!-- Formulaire -->
                        <!-- Nom -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom<span class="text-danger" title="obligatoire">*</span></label>
                            <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}" placeholder="Nom complet de l'utilisateur" required>
                            @error('name')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email<span class="text-danger" title="obligatoire">*</span></label>
                            <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}" required placeholder="Email de l'utilisateur">
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
                                        <option value="{{ old('pays') }}">Choisir indicatif...</option>
                                    </select>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="numero" class="form-control" id="numero" required oninput="validateInput()" value="{{ old('numero') }}" required placeholder="Numero de téléphone de l'utilisateur">
                                    <div class="invalid-feedback">Veuillez entrer le numéro!</div>
                                </div>
                            </div>
                        </div>

                        <!-- Mot de passe -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe<span class="text-danger" title="obligatoire">*</span></label>
                            <div class="input-group">
                                <input type="password" name="password" class="form-control" id="password" required placeholder="Mot de passe de l'utilisateur">
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password')">
                                    <i class="bi bi-eye" id="eye-icon-password"></i>
                                </button>
                            </div>
                            @error('password')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirmation mot de passe -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmer mot de passe<span class="text-danger" title="obligatoire">*</span></label>
                            <div class="input-group">
                                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" required placeholder="Confirmation de mot de passe">
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password_confirmation')">
                                    <i class="bi bi-eye" id="eye-icon-confirmation"></i>
                                </button>
                            </div>
                        </div>
                    

                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Rôle du compte</h5>
                        <!-- Rôle -->
                    <div class="mb-3">
                        <label for="role" class="form-label">Rôle<span class="text-danger">*</span></label>
                        <select name="role" class="form-select" required>
                            <option value="">Choisir un rôle...</option>
                            @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                        <!-- Boutons -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Ajouter</button>
                        <!--
                        <button type="reset" class="btn btn-secondary">Réinitialiser</button> 
                        -->
                    </div>
                </div>
        </div>
        </form>
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
            })
            .catch(console.error);
    });
</script>

@endsection
