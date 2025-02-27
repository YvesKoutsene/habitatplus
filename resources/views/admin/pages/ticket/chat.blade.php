@extends('admin.include.layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Tickets</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i></a></li>
                <li class="breadcrumb-item">Tickets Ouverts</li>
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
                            <h5 class="card-title">
                                Ticket #{{ $ticket->id }} -
                                <small>
                                    <span class="text-white badge {{ $classe = ($ticket->statut == 'ouvert') ? 'bg-primary' : (($ticket->statut == 'fermé') ? 'bg-warning' : 'bg-danger')}}">
                                        {{ ucfirst($ticket->statut) }}
                                    </span>
                                </small>
                                - Objet : {{ $ticket->titre }}
                            </h5>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title text-center">Discussions</h5>
                            <div class="messages">
                                @foreach($ticket->messages as $message)
                                    @if($message->user_id === auth()->id())
                                        <div class="right message">
                                            <img src="{{ asset($message->user->photo_profil ?? 'default-avatar.png') }}"
                                                 alt="Profil" class="avatar">
                                            <p><strong>Moi</strong> -
                                                @if($message->user->typeUser === 0)
                                                    Super Admin
                                                @else
                                                    {{ $message->user->roles->first() ? ucfirst($message->user->roles->first()->name) : 'Abonné' }}
                                                @endif
                                            </p>
                                            <p class="message-content">"{{ $message->message }}"</p>
                                            <span class="timestamp">{{ $message->created_at->format('H:i') }}</span>
                                        </div>
                                    @else
                                        <div class="left message">
                                            <img src="{{ asset($message->user->photo_profil ?? 'default-avatar.png') }}"
                                                 alt="Profil" class="avatar">
                                            <p><strong>{{ $message->user->email }}</strong> -
                                                @if($message->user->typeUser === 0)
                                                    Super Admin
                                                @else
                                                    {{ $message->user->roles->first() ? ucfirst($message->user->roles->first()->name) : 'Abonné' }}
                                                @endif
                                            </p>
                                            <p class="message-content">"{{ $message->message }}"</p>
                                            <span class="timestamp">{{ $message->created_at->format('H:i') }}</span>
                                        </div>
                                    @endif
                                @endforeach

                                <form id="messageForm">
                                    <div class="input-group">
                                        <input type="text" id="message" name="message" class="form-control" placeholder="Entrer le message..." autocomplete="on">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-send"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
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

        const channel = pusher.subscribe("private-ticket." + ticketId);

        channel.bind("chat", function(data) {
            let userId = {{ auth()->id() }};
            let messageHtml = "";

            if (data.user_id === userId) {
                messageHtml = `
                    <div class="right message">
                        <img src="${data.user_photo}" alt="Profil" class="avatar">
                        <p><strong>Moi</strong> - ${data.user_role}</p>
                        <p class="message-content">"${data.message}"</p>
                        <span class="timestamp">${data.timestamp}</span>
                    </div>
                `;
            } else {
                messageHtml = `
                    <div class="left message">
                        <img src="${data.user_photo}" alt="Profil" class="avatar">
                        <p><strong>${data.user_email}</strong> - ${data.user_role}</p>
                        <p class="message-content">"${data.message}"</p>
                        <span class="timestamp">${data.timestamp}</span>
                    </div>
                `;
            }

            $(".messages").append(messageHtml);
        });

        $("#messageForm").submit(function(event) {
            event.preventDefault();

            let messageInput = $("#message");
            let message = messageInput.val().trim();

            if (message === "") return;

            $.post("/ticket/" + ticketId + "/message", {
                _token: "{{ csrf_token() }}",
                message: message,
            }).done(function(res) {
                console.log("Message envoyé : ", res);
                messageInput.val(""); // Efface le champ après l’envoi
            }).fail(function() {
                alert("Erreur lors de l’envoi du message.");
            });
        });
    </script>

@endsection

<style>
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
        margin: 0;
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
        width: 42px;
        height: 42px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 10px;
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
