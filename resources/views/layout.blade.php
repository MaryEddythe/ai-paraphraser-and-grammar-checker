<!-- layout.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <a href="/" class="{{ $active === 'paraphrase' ? 'active' : '' }}">Paraphrase</a>
            <a href="/grammar" class="{{ $active === 'grammar' ? 'active' : '' }}">Grammar Checker</a>
        </div>

        <!-- Main Content -->
        <div class="content">
            @yield('content')
        </div>
    </div>
</body>
</html>