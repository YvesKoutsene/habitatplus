@if(auth()->check())
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" title="Fermer"></button>
                </div>
                <form method="POST" action="{{ route('update.profile', auth()->user()->id) }}" enctype="multipart/form-data" onsubmit="showLoading()">
                    @csrf
                    @method('PUT')
                    <div class="text-center mb-4 position-relative">
                        <img src="{{ asset(Auth::user()->photo_profil) }}"
                             alt="Photo de profil"
                             class="img-thumbnail rounded-circle shadow-sm"
                             id="profilePreview"
                             style="width: 100px; height: 100px; border: 1px solid white;">

                        <input type="file" name="photo_profil" class="form-control mt-2" id="photoInput" accept="image/*" onchange="previewImage(event)" style="display: none;">
                        <label for="photoInput" class="position-absolute" style="bottom: 5px; right: 5px; cursor: pointer;">
                            <i class="bi bi-pencil-fill" style="background-color: rgba(0, 0, 0, 0.6); border-radius: 50%; padding: 5px;" title="Modifier profil"></i>
                        </label>
                        <p class="mt-2 text-black">Photo de profil</p>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label text-black">Nom et Prénom(s)<span class="text-danger" title="obligatoire">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="name" name="name" value="{{ auth()->user()->name }}" required placeholder="Ex: Kevin Lupin">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label text-black">Email<span class="text-danger" title="obligatoire">*</span></label>
                        <input type="email" class="form-control form-control-sm" id="email" name="email" value="{{ auth()->user()->email }}" placeholder="Ex: kevinlupin25@gmail.com" disabled>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label text-black">Téléphone<span class="text-danger" title="obligatoire">*</span></label>
                        <div class="row">
                            <div class="col-5 col-md-5 mb-2 mb-md-0">
                                <select id="countryCode" name="pays" class="form-select form-control form-select-sm" required>
                                    <option value="{{ old('pays', auth()->user()->pays) }}">{{ old('pays', auth()->user()->pays) }}</option>
                                </select>
                            </div>
                            <div class="col-7 col-md-7">
                                <input type="text" name="numero" class="form-control" id="number" required oninput="validateInput()" value="{{ old('numero', auth()->user()->numero) }}" placeholder="Ex: 98560265">
                            </div>
                        </div>
                    </div>

                    <li class="mb-3">
                        <hr class="dropdown-divider">
                    </li>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-2 py-1 me-2">Enregistrer</button>
                        <button type="button" class="btn btn-secondary px-2 py-1" data-bs-dismiss="modal">Annuler</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editPasswordModal" tabindex="-1" aria-labelledby="editPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" title="Fermer"></button>
                </div>
                <form method="POST" action="{{ route('update.password', auth()->user()->id) }}" onsubmit="return handleFormSubmit()">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="currentPassword" class="form-label text-black">Mot de passe actuel<span class="text-info" title="Obligatoire pour changer le mot de passe">*</span></label>
                        <div class="input-group">
                            <input name="current_password" type="password" id="currentPassword" class="form-control form-control-sm" placeholder="Ex: XoxoXxx ox" required>
                            <button type="button" class="btn btn-outline-secondary rounded" onclick="togglePassword('currentPassword')">
                                <i class="bi bi-eye" id="eye-icon-current"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="newPassword" class="form-label text-black">Nouveau mot de passe<span class="text-info" title="Laisser vide si pas de changement">*</span></label>
                        <div class="input-group">
                            <input name="password" type="password" id="newPassword" class="form-control form-control-sm" placeholder="Ex: XxxxXxx x" required>
                            <button type="button" class="btn btn-outline-secondary rounded" onclick="togglePassword('newPassword')">
                                <i class="bi bi-eye" id="eye-icon-new"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="renewPassword" class="form-label text-black">Confirmer mot de passe<span class="text-info" title="Laisser vide si pas de changement">*</span></label>
                        <div class="input-group">
                            <input name="password_confirmation" type="password" id="renewPassword" class="form-control form-control-sm" placeholder="Ex: XxxxXxx x" required>
                            <button type="button" class="btn btn-outline-secondary rounded" onclick="togglePassword('renewPassword')">
                                <i class="bi bi-eye" id="eye-icon-renew"></i>
                            </button>
                        </div>
                        <div id="passwordMessage01" class="text-danger mt-2" style="display: none;">Les mots de passe ne correspondent pas.</div>
                    </div>

                    <li class="mb-3">
                        <hr class="dropdown-divider">
                    </li>

                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary px-2 py-1 me-2" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-warning px-2 py-1">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

<style>
    .modal-content {
        border-radius: 20px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }
    .img-thumbnail {
        transition: transform 0.3s ease;
    }
    .img-thumbnail:hover {
        transform: scale(1.1);
    }
</style>

<script>
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('profilePreview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        fetch('https://restcountries.com/v3.1/all')
            .then(response => response.json())
            .then(data => {
                const selectElement = document.getElementById("countryCode");
                data.sort((a, b) => a.name.common.localeCompare(b.name.common));
                data.forEach(country => {
                    const indicatif = country.idd.root + (country.idd.suffixes ? country.idd.suffixes[0] : '');
                    const option = document.createElement("option");
                    option.value = indicatif;
                    option.textContent = `${indicatif} - ${country.name.common}`;
                    selectElement.appendChild(option);
                });
                selectElement.value = "{{ old('pays', Auth::check() ? Auth::user()->pays : '') }}";
            })
            .catch(console.error);
    });

    function validateInput() {
        const input = document.getElementById('number');
        input.value = input.value.replace(/[^0-9]/g, '');
    }

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

    function validatePasswords() {
        const register_password = document.getElementById('newPassword').value;
        const password_confirmation = document.getElementById('renewPassword').value;
        const message = document.getElementById('passwordMessage01');

        if (register_password !== password_confirmation) {
            message.style.display = 'block';
            return false;
        } else {
            message.style.display = 'none';
            return true;
        }
    }

    function handleFormSubmit() {
        if (!validatePasswords()) {
            return false;
        }

        showLoading();
        return true;
    }
</script>
