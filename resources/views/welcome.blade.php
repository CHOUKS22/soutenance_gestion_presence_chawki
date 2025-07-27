<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bienvenue - IFRAN</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background: linear-gradient(-45deg, #6a11cb, #2575fc, #43cea2, #185a9d);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .fade-in {
            animation: fadeIn 1.2s ease-out forwards;
            opacity: 0;
            transform: translateY(30px);
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center text-white font-sans">

    <div class="text-center space-y-6 fade-in">
        <h1 class="text-5xl font-extrabold drop-shadow-lg">Plateforme Présence IFRAN</h1>
        <p class="text-lg text-white/90">Connexion sécurisée à votre espace personnel</p>
        <a href="{{ route('login') }}"
           class="inline-block bg-white text-indigo-700 font-semibold px-8 py-3 rounded-full shadow-lg hover:bg-gray-100 transition-all duration-300">
            Se connecter
        </a>
    </div>

</body>
</html>
