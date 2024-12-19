@extends('admin.include.layouts.app')
@section('content')

<div class="pagetitle">
    <h1>Profil</h1>
    <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
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
              <h3 class="badge bg-info">{{ ucfirst(Auth::user()->roles->first()->name) }}</h3>
              <span>Actif depuis le {{ \Carbon\Carbon::parse(Auth::user()->created_at)->format('d M Y') }}</span>
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
                    <div class="col-lg-9 col-md-8">{{ ucfirst(Auth::user()->roles->first()->name) }}</div>
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

                <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                  <!-- Profile Edit Form -->
                  <form>
                    <div class="row mb-3">
                      <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Image Profil</label>
                      <div class="col-md-8 col-lg-9">
                        <img src="{{ asset(Auth::user()->photo_profil ) }}" alt="Profil">
                        <div class="pt-2">
                          <a href="#" class="btn btn-primary btn-sm" title="Téléverser une nouvelle photo de profil"><i class="bi bi-upload"></i></a>
                          <a href="#" class="btn btn-danger btn-sm" title="Supprimer ma photo de photo"><i class="bi bi-trash"></i></a>
                        </div>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Nom</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="name" type="text" class="form-control" id="fullName" value="{{ old('name', $user->name) }}">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Job" class="col-md-4 col-lg-3 col-form-label">Rôle</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="job" type="text" class="form-control" id="Job" value="Web Designer">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Phone" class="col-md-4 col-lg-3 col-form-label">Telephone</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="numero" type="text" class="form-control" id="Phone" value="({{ old('name', $user->pays) }}) {{ old('name', $user->numero) }}">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="email" type="email" class="form-control" id="Email" value="{{ old('name', $user->email) }}">
                      </div>
                    </div>

                    <div class="text-center">
                      <button type="submit" class="btn btn-primary">Modifier</button>
                    </div>
                  </form><!-- End Profile Edit Form -->

                </div>

                <div class="tab-pane fade pt-3" id="profile-change-password">
                  <!-- Change Password Form -->
                  <form method="POST" action="{{ route('update.password', $user->id) }}" class="needs-validation" novalidate>
                      @method('PUT')
                      @csrf
                      <div class="row mb-3">
                          <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Mot de passe actuel</label>
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
                          <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">Nouveau mot de passe</label>
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
                          <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Confirmez mot de passe</label>
                          <div class="col-md-8 col-lg-9">
                              <div class="input-group">
                                  <input name="password_confirmation" type="password" class="form-control" id="renewPassword" required>
                                  <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('renewPassword')">
                                      <i class="bi bi-eye" id="eye-icon-renew"></i>
                                  </button>
                              </div>
                          </div>
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
</script>


@endsection
