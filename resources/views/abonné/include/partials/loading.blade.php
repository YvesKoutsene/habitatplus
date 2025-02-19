<style>
    @keyframes wave {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-10px);
        }
        25%, 75% {
            opacity: 0.6;
        }
    }

    .wave-animation {
        display: inline-block;
        animation: wave 1.5s ease-in-out infinite;
        /*text-shadow: 2px 2px 5px rgba(255, 255, 255, 0.8);*/
    }

    @keyframes backgroundAnimation {
        0% { background-color: rgba(0, 0, 0, 0.4); } /*6 8 6*/
        50% { background-color: rgba(0, 0, 0, 0.6); }
        100% { background-color: rgba(0, 0, 0, 0.4); }
    }

    #loadingScreen {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        background-color: rgba(0, 0, 0, 0.6);
        animation: backgroundAnimation 3s infinite;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 10550;
        backdrop-filter: blur(5px);
    }

    h {
        color: white;
        text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
    }

</style>

<div id="loadingScreen" class="d-none">
    <div class="text-center">
        <h5 class="mb-4 fw-bold h text-white">
            <span class="wave-animation" style="animation-delay: 0s;">Traitement</span>
            <span class="wave-animation" style="animation-delay: 0.1s;">en</span>
            <span class="wave-animation" style="animation-delay: 0.2s;">cours,</span>
            <span class="wave-animation" style="animation-delay: 0.3s;">veuillez</span>
            <span class="wave-animation" style="animation-delay: 0.4s;">patienter</span>
        </h5>
        <div class="spinner-grow text-primary"></div>
        <div class="spinner-grow text-secondary"></div>
        <div class="spinner-grow text-success"></div>
        <div class="spinner-grow text-danger"></div>
        <div class="spinner-grow text-warning"></div>
        <div class="spinner-grow text-info"></div>
        <div class="spinner-grow text-light"></div>
        <div class="spinner-grow text-dark"></div>
    </div>
</div>

<script>
    function showLoading() {
        document.getElementById('loadingScreen').classList.remove('d-none');
    }

    function hideLoading() {
        document.getElementById('loadingScreen').classList.add('d-none');
    }

</script>

