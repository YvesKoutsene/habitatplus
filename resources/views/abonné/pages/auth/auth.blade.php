<div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <ul class="nav nav-tabs justify-content-center mb-3" id="authTabs" role="tablist">

                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#loginSection" role="tab" aria-controls="loginSection" aria-selected="false">
                            CONNEXION
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-4" id="register-tab" data-bs-toggle="tab" data-bs-target="#registerSection" role="tab" aria-controls="registerSection" aria-selected="true">
                            INSCRIPTION
                        </button> <br>
                    </li>
                </ul>

                <div class="tab-content" id="authTabContent">
                    <div class="tab-pane fade" id="registerSection" role="tabpanel" aria-labelledby="register-tab">
                        <form action="{{ route('register') }}" method="POST" onsubmit="return validatePasswords()">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label text-black">Nom<span class="text-danger" title="obligatoire">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="name" name="name" placeholder="Votre nom complet" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label text-black">Email<span class="text-danger" title="obligatoire">*</span></label>
                                <input type="email" class="form-control form-control-sm" id="email" name="email" placeholder="Votre adresse email" required>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label text-black">Téléphone<span class="text-danger" title="obligatoire">*</span></label>
                                <div class="row">
                                    <div class="col-md-5">
                                        <select id="inputState" class="form-select form-control form-select-sm" name="pays">
                                            <option value="">Choisir un indicatif</option>
                                        </select>
                                    </div>
                                    <div class="col-md-7">
                                        <input type="text" name="numero" class="form-control form-control-sm" id="numero" required oninput="validateInput()" placeholder="Numero de téléphone">
                                        <div class="invalid-feedback">Veuillez entrer votre numéro!</div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="register_password" class="form-label text-black">Mot de passe<span class="text-danger" title="obligatoire">*</span></label>
                                <div class="input-group">
                                    <input type="password" name="password" class="form-control form-control-sm" placeholder="Votre mot de passe" id="register_password" required>
                                    <button type="button" class="btn btn-outline-secondary rounded" onclick="togglePassword('register_password', 'eye-icon-password')">
                                        <i class="bi bi-eye" id="eye-icon-password"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label text-black">Confirmer mot de passe<span class="text-danger" title="obligatoire">*</span></label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" class="form-control form-control-sm" id="password_confirmation" placeholder="Confirmez votre mot de passe" required>
                                    <button type="button" class="btn btn-outline-secondary rounded" onclick="togglePassword('password_confirmation', 'eye-icon-confirmation')">
                                        <i class="bi bi-eye" id="eye-icon-confirmation"></i>
                                    </button>
                                </div>
                                <div id="passwordMessage" class="text-danger mt-2" style="display: none;">Les mots de passe ne correspondent pas.</div>
                            </div>
                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="terms" required>
                                <label class="form-check-label text-black" for="terms">
                                    J'accepte les <a href="#" class="text-primary">termes et conditions</a>.
                                </label>
                            </div>

                            <li class="mb-3">
                                <hr class="dropdown-divider">
                            </li>

                            <div class="d-flex justify-content-center gap-3 me-2 mb-3">
                                <button type="submit" class="btn btn-primary w-50 rounded-pill px-2 py-1">S'inscrire</button>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane fade show active" id="loginSection" role="tabpanel" aria-labelledby="login-tab">
                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="login_email" class="form-label text-black">Email<span class="text-danger" title="obligatoire">*</span></label>
                                <input type="email" class="form-control form-control-sm" id="login_email" name="email" placeholder="Votre adresse email" required>
                            </div>
                            <div class="mb-3">
                                <label for="login_password" class="form-label text-black">Mot de passe<span class="text-danger" title="obligatoire">*</span></label>
                                <div class="input-group">
                                    <input type="password" name="password" class="form-control form-control-sm" placeholder="Votre mot de passe" id="login_password" required>
                                    <button type="button" class="btn btn-outline-secondary rounded" onclick="togglePassword('login_password', 'eye-icon-login')">
                                        <i class="bi bi-eye" id="eye-icon-login"></i>
                                    </button>
                                </div>
                            </div>
                            <div class=""> <!-- mb-3 -->
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="rememberMe" value="true">
                                    <label class="form-check-label text-black" for="rememberMe">Se rappeler de moi</label>
                                </div>
                            </div>
                            <div class="mb-3 d-flex justify-content-end">
                                <a href="#" class="text-primary">Mot de passe oublié ?</a>
                            </div>
                            <li class="mb-3">
                                <hr class="dropdown-divider">
                            </li>
                            <div class="d-flex justify-content-center gap-3 me-2 mb-3">
                                <button type="submit" class="btn btn-primary w-50 rounded-pill px-2 py-1">Se connecter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .modal-content {
        border-radius: 20px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        background-color: #007bff;
        border-bottom: none;
        color: white;
        text-align: center;
        padding: 1rem 1.5rem;
    }

    .nav-tabs .nav-link {
        color: #555;
        border: none;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .nav-tabs .nav-link.active {
        /*background-color: #007bff;*/
        background-color: #c8ff00;
        //color: white;
        color: black;
        font-weight: bold;
        box-shadow: 0 3px 6px rgba(0, 123, 255, 0.3);
    }

    .nav-tabs .nav-link:hover {
        color: #007bff;
        background-color: #f0f0f0;
    }

    .form-control {
        border-radius: 50px;
        border: 1px solid #ddd;
        padding: 0.75rem 1rem;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 6px rgba(0, 123, 255, 0.3);
    }

    .btn-primary {
        border-radius: 50px;
        font-weight: bold;
        padding: 0.75rem 1.5rem;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        fetch('https://restcountries.com/v3.1/all')
            .then(response => response.json())
            .then(data => {
                const selectElement = document.getElementById("inputState");

                data.sort((a, b) => {
                    const nameA = a.translations.fra?.common || a.name.common;
                    const nameB = b.translations.fra?.common || b.name.common;
                    return nameA.localeCompare(nameB);
                });

                data.forEach(country => {
                    const indicatif = country.idd.root + (country.idd.suffixes ? country.idd.suffixes[0] : '');
                    const nameInFrench = country.translations.fra?.common || country.name.common;
                    const option = document.createElement("option");
                    option.value = indicatif;
                    option.textContent = `${indicatif} - ${nameInFrench}`;
                    selectElement.appendChild(option);
                });
            })
            .catch(error => console.error('Erreur lors de la récupération des données:', error));
    });

    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

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

    function validateInput() {
        const input = document.getElementById('numero');
        input.value = input.value.replace(/[^0-9]/g, '');
    }

    function validatePasswords() {
        const register_password = document.getElementById('register_password').value;
        const password_confirmation = document.getElementById('password_confirmation').value;
        const message = document.getElementById('passwordMessage');

        if (register_password !== password_confirmation) {
            message.style.display = 'block';
            return false;
        } else {
            message.style.display = 'none';
            return true;
        }
    }

</script>
