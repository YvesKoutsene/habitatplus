@extends('admin.include.layouts.app')
@section('content')

<div class="pagetitle">
    <h1>Profil</h1>
    <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i></a></li>
          <li class="breadcrumb-item">Utilisateurs</li>
          <li class="breadcrumb-item active">Profil</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section profile">
      <div class="row">
        <div class="col-xl-4">
          <div class="card">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
              <img src="{{ asset(Auth::user()->photo_profil ) }}" alt="Profil" class="rounded-circle">
              <h2>{{ Auth::user()->name }}</h2>
                <h3 class="badge bg-info">
                    @if(Auth::user()->typeUser === 0)
                        Super Admin
                    @else
                        {{ Auth::user()->roles->first() ? ucfirst(Auth::user()->roles->first()->name) : 'Aucun rôle' }}
                    @endif
                </h3>
              <span>Actif depuis {{ \Carbon\Carbon::parse(Auth::user()->created_at)->format('d M Y') }}</span>
            </div>
          </div>
        </div>

        <div class="col-xl-8">

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">

                <li class="nav-item">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Aperçu</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Editer Profil</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Modifier Passe</button>
                </li>

              </ul>
              <div class="tab-content pt-2">

                <div class="tab-pane fade show active profile-overview" id="profile-overview">
                  <h5 class="card-title">A propos</h5>
                  <p class="small fst-italic">Description du rôle...</p>

                  <h5 class="card-title">Details Profils</h5>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Nom</div>
                    <div class="col-lg-9 col-md-8">{{ Auth::user()->name }}</div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Rôle</div>
                    <div class="col-lg-9 col-md-8">
                        @if(Auth::user()->typeUser === 0)
                            Super Admin
                        @else
                            {{ Auth::user()->roles->first() ? ucfirst(Auth::user()->roles->first()->name) : 'Aucun rôle' }}
                        @endif
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Téléphone</div>
                    <div class="col-lg-9 col-md-8">({{ Auth::user()->pays }}) {{ Auth::user()->numero }}</div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Email</div>
                    <div class="col-lg-9 col-md-8">{{ Auth::user()->email }}</div>
                  </div>

                </div>

                <div class="tab-pane fade profile-edit pt-3" id="profile-edit"
                  <!-- Profile Edit Form -->
                  <form method="POST" action="{{ route('update.profile', auth()->user()->id) }}" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="row mb-3">
                      <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Image Profil</label>
                      <div class="col-md-8 col-lg-9">
                          <img id="profilePreview" src="{{ asset(Auth::user()->photo_profil) }}" alt="Profil" class="img-fluid rounded" style="max-height: 150px;">
                          <div class="pt-2">
                              <label for="uploadImage" class="btn btn-primary btn-sm" title="Téléverser une photo">
                                  <i class="bi bi-upload"></i> Téléverser
                              </label>
                              <input type="file" name="photo_profil" id="uploadImage" class="d-none" accept="image/*">
                          </div>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Nom<span class="text-danger" title="obligatoire">*</span></label>
                      <div class="col-md-8 col-lg-9">
                        <input name="name" type="text" class="form-control" id="fullName" value="{{ old('name', auth()->user()->name) }}" required>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Job" class="col-md-4 col-lg-3 col-form-label">Rôle</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="job" type="text" class="form-control" id="Job" value="{{ Auth::user()->typeUser === 0 ? 'Super Admin' : (Auth::user()->roles->first() ? ucfirst(Auth::user()->roles->first()->name) : 'Aucun rôle') }}"
                               disabled>
                      </div>
                    </div>

                    <div class="row mb-3">
                        <label for="Phone" class="col-md-4 col-lg-3 col-form-label">Telephone<span class="text-danger" title="obligatoire">*</span></label>
                            <div class="col-md-4">
                                <select id="countryCode" name="pays" class="form-select" required>
                                    <option value="{{ old('pays', auth()->user()->pays) }}">{{ old('pays', auth()->user()->pays) }}</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <input type="text" name="numero" class="form-control" id="numero" required oninput="validateInput()" value="{{ old('numero', auth()->user()->numero) }}">
                                <div class="invalid-feedback">Veuillez entrer le numéro!</div>
                            </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email<span class="text-danger" title="obligatoire">*</span></label>
                      <div class="col-md-8 col-lg-9">
                        <input name="email" type="email" class="form-control" id="Email" value="{{ old('name', auth()->user()->email) }}" required>
                      </div>
                    </div>

                    <div class="text-center">
                      <button type="submit" class="btn btn-primary">Modifier</button>
                    </div>
                  </form><!-- End Profile Edit Form -->
                </div>

                <div class="tab-pane fade pt-3" id="profile-change-password">
                  <!-- Change Password Form -->
                  <form method="POST" action="{{ route('update.password', auth()->user()->id) }}" onsubmit="return validatePasswords()">
                      @method('PUT')
                      @csrf

                      <div class="row mb-3">
                          <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Mot de passe actuel<span class="text-info" title="Obligatoire pour changer le mot de passe">*</span></label>
                          <div class="col-md-8 col-lg-9">
                              <div class="input-group">
                                  <input name="current_password" type="password" class="form-control" id="currentPassword" required>
                                  <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('currentPassword')">
                                      <i class="bi bi-eye" id="eye-icon-current"></i>
                                  </button>
                              </div>
                          </div>
                      </div>

                      <div class="row mb-3">
                          <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">Nouveau mot de passe<span class="text-info" title="Laisser vide si pas de changement">*</span></label>
                          <div class="col-md-8 col-lg-9">
                              <div class="input-group">
                                  <input name="password" type="password" class="form-control" id="newPassword" required>
                                  <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('newPassword')">
                                      <i class="bi bi-eye" id="eye-icon-new"></i>
                                  </button>
                              </div>
                          </div>
                      </div>

                      <div class="row mb-3">
                          <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Confirmer mot de passe<span class="text-info" title="Laisser vide si pas de changement">*</span></label>
                          <div class="col-md-8 col-lg-9">
                              <div class="input-group">
                                  <input name="password_confirmation" type="password" class="form-control" id="renewPassword" required>
                                  <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('renewPassword')">
                                      <i class="bi bi-eye" id="eye-icon-renew"></i>
                                  </button>
                              </div>
                          </div>
                          <div id="passwordMessage" class="text-danger mt-2" style="display: none;">Les mots de passe ne correspondent pas.</div>
                      </div>
                      <div class="text-center">
                          <button type="submit" class="btn btn-primary">Changer</button>
                      </div>
                  </form><!-- End Change Password Form -->
                </div>

              </div><!-- End Bordered Tabs -->

            </div>
          </div>

        </div>
</section>

<script>
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(inputId === 'currentPassword' ? 'eye-icon-current' : inputId === 'newPassword' ? 'eye-icon-new' : 'eye-icon-renew');

        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = "password";
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }

    document.getElementById('uploadImage').addEventListener('change', function(event) {
      const file = event.target.files[0];
      if (file) {
          const reader = new FileReader();
          reader.onload = function(e) {
              document.getElementById('profilePreview').src = e.target.result;
          };
          reader.readAsDataURL(file);
      }
    });

    // Validation des champs de téléphone
    function validateInput() {
        const input = document.getElementById('numero');
        input.value = input.value.replace(/[^0-9]/g, '');
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
                selectElement.value = "{{ old('pays', auth()->user()->pays) }}";
            })
            .catch(console.error);
    });

    function validatePasswords() {
        const newPassword = document.getElementById('newPassword').value;
        const renewPassword = document.getElementById('renewPassword').value;
        const message = document.getElementById('passwordMessage');

        if (newPassword !== renewPassword) {
            message.style.display = 'block';
            return false;
        } else {
            message.style.display = 'none';
            return true;
        }
    }

</script>
@endsection
