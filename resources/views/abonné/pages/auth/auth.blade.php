<div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-body">
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" title="Fermer"></button>
                </div>
                <ul class="nav nav-tabs d-flex justify-content-center mb-3" id="authTabs" role="tablist">
                    <div class="row">
                        <div class="col-auto">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#loginSection" role="tab" aria-controls="loginSection" aria-selected="false">
                                    CONNEXION
                                </button>
                            </li>
                        </div>
                        <div class="col-auto">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#registerSection" role="tab" aria-controls="registerSection" aria-selected="true">
                                    INSCRIPTION
                                </button> <br>
                            </li>
                        </div>
                    </div>
                </ul>

                <div class="tab-content" id="authTabContent">
                    <div class="tab-pane fade" id="registerSection" role="tabpanel" aria-labelledby="register-tab">
                        <form action="{{ route('register') }}" method="POST"  onsubmit="return handleFormSubmit()">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label text-black">Nom et Prénom(s)<span class="text-danger" title="obligatoire">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="name" name="name" placeholder="Ex: Kevin Lupin" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label text-black">Email<span class="text-danger" title="obligatoire">*</span></label>
                                <input type="email" class="form-control form-control-sm" id="email" name="email" placeholder="Ex: kevinlupin25@gmail.com" required>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label text-black">Téléphone<span class="text-danger" title="obligatoire">*</span></label>
                                <div class="row">
                                    <div class="col-5 col-md-5 mb-2 mb-md-0">
                                        <div class="dropdown">
                                            <input type="text" id="searchIndicatif" class="form-control form-control-sm" placeholder="Rechercher un indicatif" onfocus="showDropdown()" oninput="filterOptions()" required>
                                            <div class="dropdown-menu" id="dropdownMenu" style="max-height: 200px; overflow-y: auto;">
                                                <div id="indicatifsList"></div>
                                            </div>
                                            <input type="hidden" name="pays" id="indicatif">
                                        </div>
                                    </div>
                                    <div class="col-7 col-md-7">
                                        <input type="text" name="numero" class="form-control form-control-sm" id="numero00" required oninput="validateInput00()" placeholder="Ex: 98560265">
                                        <div class="invalid-feedback">Veuillez entrer votre numéro!</div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="register_password02" class="form-label text-black">Mot de passe<span class="text-danger" title="obligatoire">*</span></label>
                                <div class="input-group">
                                    <input type="password" name="password" class="form-control form-control-sm" placeholder="Ex: XoxoXxx ox" id="register_password02" required>
                                    <button type="button" class="btn btn-outline-secondary rounded" onclick="togglePassword02('register_password02', 'eye-icon-password')">
                                        <i class="bi bi-eye" id="eye-icon-password"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation02" class="form-label text-black">Confirmer mot de passe<span class="text-danger" title="obligatoire">*</span></label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" class="form-control form-control-sm" id="password_confirmation02" placeholder="Ex: XoxoXxx ox" required>
                                    <button type="button" class="btn btn-outline-secondary rounded" onclick="togglePassword02('password_confirmation02', 'eye-icon-confirmation')">
                                        <i class="bi bi-eye" id="eye-icon-confirmation"></i>
                                    </button>
                                </div>
                                <div id="passwordMessage02" class="text-danger mt-2" style="display: none;">Les mots de passe ne correspondent pas.</div>
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
                        <form action="{{ route('login') }}" method="POST" onsubmit="showLoading()">
                            @csrf
                            <div class="mb-3">
                                <label for="login_email" class="form-label text-black">Email<span class="text-danger" title="obligatoire">*</span></label>
                                <input type="email" class="form-control form-control-sm" id="login_email" name="email" placeholder="Ex: kevinlupin25@gmail.com" required>
                            </div>
                            <div class="mb-3">
                                <label for="login_password" class="form-label text-black">Mot de passe<span class="text-danger" title="obligatoire">*</span></label>
                                <div class="input-group">
                                    <input type="password" name="password" class="form-control form-control-sm" placeholder="Ex: XoxoXxx ox" id="login_password" required>
                                    <button type="button" class="btn btn-outline-secondary rounded" onclick="togglePassword02('login_password', 'eye-icon-login')">
                                        <i class="bi bi-eye" id="eye-icon-login"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="">
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

@if(request()->get('showModal') === 'create')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var myModal = new bootstrap.Modal(document.getElementById('authModal'), {
            keyboard: false
        });
        myModal.show();
    });
</script>
@endif

<style>
    .modal-content {
        border-radius: 20px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        /*background-color: #007bff;*/
        background-color: whitesmoke;
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
        background-color: #d0ff00;
        /*color: white;*/
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
                const indicatifsList = document.getElementById("indicatifsList");

                data.sort((a, b) => {
                    const nameA = a.translations.fra?.common || a.name.common;
                    const nameB = b.translations.fra?.common || b.name.common;
                    return nameA.localeCompare(nameB);
                });

                data.forEach(country => {
                    const indicatif = country.idd.root + (country.idd.suffixes ? country.idd.suffixes[0] : '');
                    const nameInFrench = country.translations.fra?.common || country.name.common;

                    const listItem = document.createElement("div");
                    listItem.textContent = `${indicatif} - ${nameInFrench}`;
                    listItem.classList.add("dropdown-item");

                    listItem.onclick = () => {
                        const selectedIndicatif = `${indicatif} - ${nameInFrench}`;
                        document.getElementById("searchIndicatif").value = selectedIndicatif;
                        document.getElementById("indicatif").value = indicatif;
                        document.getElementById("dropdownMenu").classList.remove("show");
                    };

                    indicatifsList.appendChild(listItem);
                });
            })
            .catch(error => console.error('Erreur lors de la récupération des données:', error));
    });

    function filterOptions() {
        const searchInput = document.getElementById('searchIndicatif').value.toLowerCase();
        const dropdownItems = document.querySelectorAll('.dropdown-item');

        dropdownItems.forEach(item => {
            const text = item.textContent.toLowerCase();
            item.style.display = text.includes(searchInput) ? '' : 'none';
        });

        const dropdownMenu = document.getElementById("dropdownMenu");
        dropdownMenu.classList.add("show");
    }

    function showDropdown() {
        document.getElementById("dropdownMenu").classList.add("show");
    }

    document.addEventListener("click", function(event) {
        const dropdown = document.querySelector('.dropdown');
        if (!dropdown.contains(event.target)) {
            document.getElementById("dropdownMenu").classList.remove("show");
        }
    });

    function togglePassword02(inputId, iconId) {
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

    function validateInput00() {
        const input = document.getElementById('numero00');
        input.value = input.value.replace(/[^0-9]/g, '');
    }

    function validatePasswords02() {
        const register_password = document.getElementById('register_password02').value;
        const password_confirmation = document.getElementById('password_confirmation02').value;
        const message = document.getElementById('passwordMessage02');

        if (register_password !== password_confirmation) {
            message.style.display = 'block';
            return false;
        } else {
            message.style.display = 'none';
            return true;
        }
    }

    function handleFormSubmit() {
        if (!validatePasswords02()) {
            return false;
        }

        showLoading();
        return true;
    }

</script>
