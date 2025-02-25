<footer class="footer" id="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <h2>Habitat+</h2>
            </div>

            <div class="col-md-3">
                <h5>À propos</h5>
                <p>
                    Votre plateforme de confiance, dédiée à vous accompagner dans la recherche de biens immobiliers parfaitement adaptés à vos besoins et à vos aspirations.
                </p>
            </div>

            <div class="col-md-3">
                <h5>Nous contacter</h5>
                <ul class="list-unstyled">
                    <li>Email : <a href="mailto:info@dis.tg">contact@dis.tg</a></li>
                    <li>Téléphone : <a href="tel:+22891456282">+228 93816766</a></li>
                    <li>Adresse : Nukafu, Bld Jean Paul II en face de SOMAYAF Jean Paul II Lomé, Togo</li>
                </ul>
            </div>

            <div class="col-md-3">
                <h5>Assistance</h5>
                <ul class="list-unstyled">
                    @auth
                        <li>
                            Si vous rencontrez des problèmes, n'hésitez pas à <a  class="text-warning" href="{{ route('ticket.create') }}">signaler un problème</a>.
                        </li>
                    @else
                        <li>
                            Si vous rencontrez des problèmes, n'hésitez pas à <a href="javascript:void(0);" onclick="showAuthModal()" class="text-warning">signaler un problème</a>.
                        </li>
                    @endauth
                </ul>
            </div>

            <div class="col-md-3">
                <h5>Nous suivre</h5>
                <ul class="list-inline footer-links">
                    <li class="list-inline-item">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </li>
                    <li class="list-inline-item">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                    </li>
                    <li class="list-inline-item">
                        <a href="#"><i class="fab fa-twitter"></i></a>
                    </li>
                    <!--
                    <li class="list-inline-item">
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                    </li>
                    -->
                </ul>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-6">
                &copy; 2024 <strong>Habitat+</strong>. Tous droits réservés.
            </div>
            <div class="col-md-6 text-end">
                <ul class="list-inline footer-links">
                    <li class="list-inline-item">
                        <a href="#">Politique de confidentialité</a>
                    </li>
                    <li class="list-inline-item">
                        <a href="#">Conditions d'utilisation</a>
                    </li>

                </ul>
            </div>
        </div>
    </div>
</footer>

<style>
    body {
        color: #fff;
        margin: 0;
        padding: 0;
    }

    .footer {
        /*background: linear-gradient(135deg, #198754, #145b37);*/
        background: linear-gradient(135deg, #2b2d30, #145b37);
        padding: 40px 0;
        font-size: 14px;
    }

    .footer h2,
    .footer h5 {
        color: #fff;
        margin-bottom: 20px;
    }

    .footer p,
    .footer a {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .footer a:hover {
        color: #ffdd57;
    }

    .footer-links a {
        margin-right: 15px;
        font-size: 20px;
        display: inline-block;
        transition: transform 0.3s ease, color 0.3s ease;
    }

    .footer-links a:hover {
        transform: scale(1.2);
        color: #ffdd57;
    }

    .footer hr {
        border: none;
        height: 1px;
        background: rgba(255, 255, 255, 0.2);
        margin: 30px 0;
    }

    .footer .list-inline-item {
        margin-right: 10px;
    }

    .footer .text-end {
        text-align: end;
    }

    @media (max-width: 768px) {
        .footer .text-end {
            text-align: start;
            margin-top: 20px;
        }
    }
</style>

