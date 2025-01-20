<h2 class="mb-4 text-black-50">Mon Profil</h2>
<div class="card shadow-lg border-0 rounded-lg overflow-hidden">
    <div class="bg-primary text-white text-center py-5">
        <div class="profile-picture mb-4">
            <img src="{{ asset(Auth::user()->photo_profil) }}"
                 alt="Photo de profil"
                 class="img-thumbnail rounded-circle shadow-sm"
                 style="width: 120px; height: 120px; border: 1px solid white;">
        </div>
        <h4 class="mb-1">{{ auth()->user()->name }}</h4>
        <p class="mb-1">{{ auth()->user()->email }}</p>
        <!--
            <p class="mb-1">
                ({{ auth()->user()->pays }}) {{ auth()->user()->numero }}
            </p>
        -->
        @php
        $createdAt = \Carbon\Carbon::parse(Auth::user()->created_at);
        $now = \Carbon\Carbon::now();
        $diffInDays = $createdAt->diffInDays($now);
        $diffInMonths = $createdAt->diffInMonths($now);
        $diffInYears = $createdAt->diffInYears($now);
        $diffInHours = $createdAt->diffInHours($now);
        @endphp

        <p class="small text-light mb-1">
            Actif depuis
            @if ($diffInYears > 0)
            <strong>{{ $diffInYears }} an{{ $diffInYears > 1 ? 's' : '' }}</strong>
            @elseif ($diffInMonths > 0)
            <strong>{{ $diffInMonths }} mois</strong>
            @elseif ($diffInDays > 7)
            <strong>{{ floor($diffInDays / 7) }} semaine{{ floor($diffInDays / 7) > 1 ? 's' : '' }}</strong>
            @elseif ($diffInDays > 0)
            <strong>{{ $diffInDays }} jour{{ $diffInDays > 1 ? 's' : '' }}</strong>
            @else
            <strong>{{ $diffInHours }} heure{{ $diffInHours > 1 ? 's' : '' }}</strong>
            @endif
        </p>
    </div>
    <div class="card-body text-center">
        <div class="row justify-content-center">
            <div class="col-md-5 mb-3">
                <button class="btn btn-primary btn-block shadow-sm" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                    <i class="bi bi-pencil-square me-2"></i> Modifier informations
                </button>
            </div>
            <div class="col-md-5 mb-3">
                <button class="btn btn-warning btn-block shadow-sm" data-bs-toggle="modal" data-bs-target="#editPasswordModal">
                    <i class="bi bi-lock me-2"></i> Changer mot de passe
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        /*max-width: 500px;*/
        margin: 30px auto;
        border-radius: 15px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: scale(1.02);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
    }

    .profile-picture img {
        transition: transform 0.3s ease;
        border-radius: 50%;
    }

    .profile-picture img:hover {
        transform: scale(1.3);
    }

    .btn {
        border-radius: 25px;
        font-size: 1rem;
        font-weight: 600;
        padding: 12px 20px;
        transition: background-color 0.3s ease, transform 0.3s ease;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        transform: translateY(-3px);
    }

    .btn-warning {
        background-color: #ffc107;
        border-color: #ffc107;
    }

    .btn-warning:hover {
        background-color: #e0a800;
        transform: translateY(-3px);
    }

    .text-light {
        opacity: 0.9;
    }

    .text-light strong {
        font-weight: bold;
    }
</style>
