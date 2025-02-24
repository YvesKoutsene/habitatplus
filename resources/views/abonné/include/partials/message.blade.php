@if (session('success') || session('error') || $errors->any())
<div class="alert-container">
    @if (session('success'))
    <div class="alert alert-primary bg-primary text-light border-0 alert-dismissible fade show shadow-lg" role="alert">
        <i class="bi bi-check-circle me-1"></i>
        <strong>Succès :</strong> {{ session('success') }}
        <!--<button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>-->
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show shadow-lg" role="alert">
        <i class="bi bi-exclamation-octagon me-1"></i>
        <strong>Erreur :</strong> {{ session('error') }}
        <!--<button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>-->
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-warning bg-warning text-dark border-0 alert-dismissible fade show shadow-lg" role="alert">
        <i class="bi bi-exclamation-triangle me-1"></i>
        <strong>Attention :</strong><br>
        @foreach ($errors->all() as $error)
        - {{ $error }}<br>
        @endforeach
        <!--<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>-->
    </div>
    @endif
</div>

<style>
    .alert-container {
        position: fixed;
        top: 5px;
        right: 20px;
        z-index: 1050;
        width: auto;
        max-width: 400px;
    }

    .alert {
        border-radius: 8px;
        padding: 12px 18px;
        font-size: 0.95rem;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.15);
        opacity: 0;
        transform: translateY(-10px);
        transition: opacity 0.5s ease-out, transform 0.5s ease-out;
        margin-bottom: 5px; /* Ajout d'espacement entre les alertes */
    }

    .alert.show {
        opacity: 1;
        transform: translateY(0);
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $('.alert').each(function(index) {
            $(this).delay(500 * index).queue(function() {
                $(this).addClass('show').dequeue();
            });
        });

        $('.alert .btn-close').click(function() {
            $(this).closest('.alert').fadeOut(300, function() {
                $(this).remove();
            });
        });

        setTimeout(function() {
            $('.alert').fadeOut(500, function() {
                $(this).remove();
            });
        }, 5000);
    });
</script>
@endif
