<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification de l'adresse e-mail</title>
    <style>
        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9fafc;
            margin: 0;
            padding: 0;
            color: #444;
            line-height: 1.5;
        }

        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }

        .email-header {
            /*background: linear-gradient(90deg, #007BFF, #0056b3);*/
            background: linear-gradient(90deg, #007BFF, rgba(0, 179, 173, 0.63));
            padding: 20px;
            text-align: center;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .email-header img {
            margin-right: 10px;
        }
        .email-header h1 {
            font-size: 24px;
            margin: 0;
        }
        .email-body {
            padding: 30px;
            font-size: 16px;
        }
        .email-body h2 {
            font-size: 22px;
            color: #333;
            margin-bottom: 10px;
        }
        .email-body p {
            margin: 15px 0;
        }
        .email-body strong {
            color: #007BFF;
        }

        .email-button {
            display: inline-block;
            background: linear-gradient(90deg, #007BFF, #0056b3);
            color: #ffffff;
            padding: 14px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            text-align: center;
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
        }
        .email-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
        }

        .url-container {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            padding: 10px;
            margin: 15px 0;
            word-break: break-word;
            font-family: monospace;
        }
        .url-container a {
            color: #007BFF;
            text-decoration: none;
            word-break: break-word;
        }

        .expiration-message {
            font-size: 14px;
            color: #e63946;
            font-weight: bold;
        }

        .email-footer {
            text-align: center;
            padding: 20px;
            background-color: #f9fafc;
            font-size: 14px;
            color: #6c757d;
        }
        .email-footer a {
            color: #007BFF;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="email-container">
    <div class="email-header">
        <img src="{{ asset('assets/img/logo.png') }}" alt="Logo">
        <h1>Habitat+</h1>
    </div>

    <div class="email-body">
        <h2>Bonjour {{ $user->name }} !</h2>
        <p>Nous sommes ravis de vous compter parmi nos utilisateurs !</p>
        <p>Pour activer votre compte, veuillez vérifier votre adresse e-mail en cliquant sur le bouton ci-dessous :</p>
        <a href="{{ $url }}" class="email-button">Vérifier mon e-mail</a>
        <p>Si le bouton ne fonctionne pas, copiez et collez ce lien dans votre navigateur :</p>
        <div class="url-container">
            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer">{{ $url }}</a>
        </div>
        <p class="expiration-message">
            Attention : Ce lien expirera dans 59 minutes<span id="expiration-time"></span>.
        </p>
        <p>Merci pour votre confiance,<br>L'équipe <strong>Habitat+</strong>.</p>
    </div>

    <div class="email-footer">
        © {{ date('Y') }} Habitat+. Tous droits réservés. <br>
        <a href="https://immobilier.dis.tg" target="_blank" rel="noopener noreferrer">https://immobilier.dis.tg</a>
    </div>
</div>

<script>
    const expires = parseInt({{ $expires }}, 10);
    const expirationElement = document.getElementById('expiration-time');

    function updateExpiration() {
        const currentTime = Math.floor(Date.now() / 1000);
        const timeLeft = expires - currentTime;

        if (timeLeft > 0) {
            const minutesLeft = Math.floor(timeLeft / 60);
            const secondsLeft = timeLeft % 60;
            expirationElement.textContent = `${minutesLeft} minute(s) et ${secondsLeft} seconde(s)`;
        } else {
            expirationElement.textContent = 'ce lien a expiré.';
        }
    }

    setInterval(updateExpiration, 1000);
    updateExpiration();
</script>

</body>
</html>
