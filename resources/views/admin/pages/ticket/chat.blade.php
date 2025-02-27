@extends('admin.include.layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Tickets</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route('tckt.index') }}">Tickets Ouverts</a></li>
                <li class="breadcrumb-item active">Traitement Ticket</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="chat">

                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title">
                                    Ticket #{{ $ticket->id }} -
                                    <small>
                                        <span class="text-white badge {{ $ticket->statut == 'ouvert' ? 'bg-primary' : ($ticket->statut == 'fermé' ? 'bg-warning' : 'bg-danger') }}">
                                            {{ ucfirst($ticket->statut) }}
                                        </span>
                                    </small>
                                    - Objet : {{ $ticket->titre }}
                                </h5>
                                @if(Auth::user()->typeUser === 0 || Auth::user()->can('clôturer tickets'))
                                    <a href="" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#confirmCloseModal{{ $ticket->id }}" title="Clôturer ce ticket">
                                        <i class="bi bi-slash-circle"> Clôturer</i>
                                    </a>
                                    <div class="modal fade" id="confirmCloseModal{{ $ticket->id }}" tabindex="-1" aria-labelledby="confirmCloseModalLabel{{ $ticket->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                                    <h5 class="modal-title" id="confirmCloseModalLabel{{ $ticket->id }}">Confirmation de clôture</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Êtes-vous sûr de vouloir clôturer ce ticket ? Cette action est irréversible.
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                    <form action="{{ route('tckt.close', $ticket->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-danger">Clôturer</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title text-center">Discussions</h5>
                            <div class="messages" id="messageContainer">
                                @php
                                    $hasTodayMessages = false;
                                @endphp

                                @foreach($messagesByDate as $date => $messages)
                                    @php
                                        $formattedDate = \Carbon\Carbon::parse($date);
                                        $header = '';

                                        if ($formattedDate->isToday()) {
                                            $header = 'Aujourd\'hui';
                                            $hasTodayMessages = true;
                                        } elseif ($formattedDate->isYesterday()) {
                                            $header = 'Hier';
                                        } else {
                                            $header = $formattedDate->translatedFormat('d F Y');
                                        }
                                    @endphp

                                    <div class="date-header">{{ $header }}</div>
                                    @foreach($messages as $message)
                                        <div class="message {{ $message->user_id === auth()->id() ? 'right' : 'left' }}">
                                            <img src="{{ asset($message->user->photo_profil ?? 'default-avatar.png') }}" alt="Profil" class="avatar">
                                            <p class="badge bg-info">
                                                <strong>{{ $message->user_id === auth()->id() ? 'Moi' : $message->user->email }}</strong> -
                                                {{ $message->user->typeUser === 0 ? 'Super Admin' : ucfirst($message->user->roles->first()->name ?? 'Abonné') }}
                                            </p>
                                            <p class="message-content mb-3">{{ $message->message }}</p>
                                            <span class="timestamp">{{ $message->created_at->format('H:i') }}</span>
                                        </div>
                                    @endforeach
                                @endforeach

                                @if(!$hasTodayMessages)
                                    <div class="date-header" style="display:none;" id="today-header">Aujourd'hui</div>
                                @endif
                            </div>

                            <form id="messageForm">
                                <div class="input-group">
                                    <input type="text" id="message" name="message" class="form-control me-2" placeholder="Entrer votre message...">
                                    <button type="submit" class="btn btn-primary rounded">
                                        <i class="bi bi-send"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>
        const ticketId = {{ $ticket->id }};
        const pusher = new Pusher("{{ config('broadcasting.connections.pusher.key') }}", {
            cluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}",
            encrypted: true
        });
        const channel = pusher.subscribe("chat." + ticketId);
        const notification = document.getElementById("newMessageNotification");

        channel.bind("chat", function(data) {
            let userId = {{ auth()->id() }};
            let messageHtml = `
                <div class="message ${data.user_id == userId ? 'right' : 'left'}">
                    <img src="${data.user_photo}" alt="Profil" class="avatar">
                    <p class="badge bg-info"><strong>${data.user_id == userId ? 'Moi' : data.user_email}</strong> - ${data.user_role}</p>
                    <p class="message-content mb-3">${data.message}</p>
                    <span class="timestamp">${data.timestamp}</span>
                </div>
            `;

            //New By Jyl
            let todayHeader = document.getElementById('today-header');
            if (todayHeader) {
                todayHeader.style.display = 'block';
            }

            $("#messageContainer").append(messageHtml);
            $("#messageContainer").scrollTop($("#messageContainer")[0].scrollHeight);
        });

        $("#messageForm").submit(function(event) {
            event.preventDefault();
            let messageInput = $("#message");
            let message = messageInput.val().trim();
            if (message === "") return;

            $.ajax({
                url: "/ticket/" + ticketId + "/message",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    message: message,
                },
                success: function() {
                    messageInput.val("");
                },
                error: function(xhr) {
                    alert("Erreur lors de l’envoi du message.");
                }
            });
        });

    </script>

@endsection

<style>

    .date-header {
        font-weight: bold;
        color: #555;
        margin: 10px 0;
        text-align: center;
        font-size: 14px;
    }

    .messages {
        display: flex;
        flex-direction: column;
        overflow-y: scroll;
        max-height: 450px;
        padding: 10px;
    }

    .message {
        margin: 10px 0;
        padding: 12px 18px;
        border-radius: 20px;
        position: relative;
        font-size: 15px;
        line-height: 1.5;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: all 0.3s ease-in-out;
    }

    .right {
        align-self: flex-end;
        background: linear-gradient(to top, #0078FF, #00A9FF);
        color: white;
        border-radius: 20px 20px 0 20px;
    }

    .left {
        align-self: flex-start;
        background: #f0f0f0;
        border-radius: 20px 20px 20px 0;
    }

    .message-content {
        margin-top: 3;
        font-weight: 400;
    }

    .timestamp {
        font-size: 12px;
        color: #aaa;
        position: absolute;
        bottom: 5px;
        right: 10px;
    }

    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .input-group {
        display: flex;
        align-items: center;
        margin-top: 20px;
        border-top: 1px solid #ddd;
        padding-top: 10px;
    }

    #message {
        border-radius: 20px;
        padding: 10px 15px;
        border: 1px solid #ddd;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .btn-primary {
        border-radius: 50%;
        padding: 10px;
        background-color: #0084FF;
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        transition: all 0.3s;
    }

    .btn-primary:hover {
        background-color: #0078E7;
    }

    .card-body {
        padding: 20px;
    }

    .card {
        border-radius: 15px;
        border: none;
        background-color: #f9f9f9;
    }

    .message:hover {
        box-shadow: 0 6px 10px rgba(0,0,0,0.15);
    }

</style>
