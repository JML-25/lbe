<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LanguageByExample{{ isset($title) ? ' — ' . $title : '' }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @livewireStyles
</head>
<body>

<nav class="main-nav">
    <span class="nav-brand">LanguageByExample</span>
    <a href="{{ route('cards.index') }}"
       class="{{ request()->routeIs('cards.index') ? 'active' : '' }}">
        Gérer les fiches
    </a>
    <a href="{{ route('cards.import') }}"
       class="{{ request()->routeIs('cards.import') ? 'active' : '' }}">
        Importer
    </a>
    <a href="{{ route('cards.review') }}"
       class="{{ request()->routeIs('cards.review') ? 'active' : '' }}">
        Réviser
    </a>
    <a href="{{ route('offline') }}"
       class="{{ request()->routeIs('offline') ? 'active' : '' }}">
        Réviser offline
    </a>
</nav>

<div class="container">
    {{ $slot }}
</div>

@livewireScripts
</body>
</html>
