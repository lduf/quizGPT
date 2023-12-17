<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Quiz GPT')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    @vite('resources/css/app.css')
    <style>
        /* Ajoutez vos styles personnalisés ici */
        .navbar-brand {
            font-size: 1.5em;
            font-weight: bold;
            color: white;
            text-shadow: 1px 1px 2px black;
        }
        .alert {
            margin-top: 10px;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease-in-out;
        }
    </style>
</head>
<body class="bg-gray-100">
<nav class="bg-blue-600 text-white p-4 shadow-md">
    <div class="container mx-auto flex justify-between items-center">
        <a href="{{ url('/') }}" class="navbar-brand">Quiz GPT</a>
        <!-- Ajoutez ici d'autres liens de navigation si nécessaire -->
    </div>
</nav>

@if(session('success'))
    <div class="container mx-auto alert bg-green-100 border-l-4 border-green-500 text-green-700">
        <p>{{ session('success') }}</p>
    </div>
@endif

@if(session('error'))
    <div class="container mx-auto alert bg-red-100 border-l-4 border-red-500 text-red-700">
        <p>{{ session('error') }}</p>
    </div>
@endif

<main class="container mx-auto p-4">
    @yield('content')
</main>
</body>
</html>
