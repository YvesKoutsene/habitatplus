@if (session('success') || session('error') || $errors->any())
    <div class="alert-container">
        @if (session('success'))
            <div class="alert alert-primary bg-primary text-light border-0 alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-1"></i>
                {{ session('success') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-octagon me-1"></i>
                {{ session('error') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-warning bg-warning border-0 alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-1"></i>
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <style>
        .alert-container {
            position: fixed;
            top: 20px; /* Ajustez la distance du haut */
            right: 20px; /* Ajustez la distance de la droite */
            z-index: 1050; /* Assurez-vous que l'alerte est au-dessus des autres éléments */
            width: auto; /* Largeur automatique pour éviter de prendre tout l'espace */
        }

        .alert {
            margin-bottom: 10px; /* Espacement entre les alertes si plusieurs sont affichées */
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.4.1.js"></script>

    <script>
        $(document).ready(function() {
            $('.alert').addClass('show');

            $('.alert .btn-close').click(function() {
                $(this).closest('.alert').removeClass('show');
            });

            setTimeout(function() {
                $('.alert').removeClass('show');
                setTimeout(function() {
                    $('.alert').remove();
                }, 300);
            }, 4000);
        });
    </script>

@endif
