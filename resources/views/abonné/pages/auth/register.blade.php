<!-- Register Modal -->
<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header justify-content-center">
                <h5 class="modal-title" id="registerModalLabel">Inscription</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label text-black">Nom</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Votre nom complet" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label text-black">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Votre adresse email" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label text-black">Téléphone</label>
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Votre numéro de téléphone" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label text-black">Mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Créer un mot de passe" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label text-black">Confirmer mot de passe</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirmer votre mot de passe" required>
                    </div>
                    <div class="form-check terms-check">
                        <input type="checkbox" class="form-check-input" id="terms" required>
                        <label class="form-check-label text-black" for="terms">
                            J'ai lu et j'accepte les <a href="#">termes et conditions</a>.
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-4">S'inscrire</button>
                </form>
            </div>
            <div class="modal-footer text-black">
                <p>Vous avez déjà un compte ? <a href="{{ route('login') }}">Se connecter</a></p>
            </div>
        </div>
    </div>
</div>

@include('abonné.pages.auth.login')

<style>
    .modal-content {
        border-radius: 15px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        border-bottom: none;
        position: relative;
    }

    .modal-header h5 {
        font-size: 1.5rem;
        font-weight: bold;
        color: #0056b3;
    }

    .modal-header .btn-close {
        background: none;
        border: none;
        position: absolute;
        right: 10px;
        top: 10px;
        font-size: 1.2rem;
        color: #aaa;
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
        padding: 0.75rem 1.5rem;
    }

    .btn-secondary {
        background: none;
        color: #0056b3;
        font-weight: bold;
        border: none;
    }

    .btn-secondary:hover {
        text-decoration: underline;
        background: none;
    }

    .terms-check {
        margin-top: 1rem;
    }

    .modal-footer {
        border-top: none;
        justify-content: center;
    }

    .modal-footer a {
        font-weight: bold;
        color: #0056b3;
    }

    .modal-footer a:hover {
        text-decoration: underline;
    }
</style>
