<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Pelaporan Keamanan Makanan MBG')</title>

    .pagination { display: flex; gap: 6px; list-style: none; padding: 0; margin: 0; flex-wrap: wrap; }
    .pagination li a, .pagination li span { display: block; padding: 6px 12px; border: 1px solid #cbd2d9; border-radius: 6px; text-decoration: none; color: #1f2933; background: #fff; }
    .pagination li.active span { background: #1f4e79; color: #fff; border-color: #1f4e79; }
    .pagination li.disabled span { color: #9aa5b1; background: #f4f6f8; }

    <style>
        * { box-sizing: border-box; }
        body { font-family: system-ui, sans-serif; margin: 0; background: #f4f6f8; color: #1f2933; }
        header { background: #1f4e79; color: #fff; padding: 12px 24px; display: flex; justify-content: space-between; align-items: center; }
        header a { color: #fff; text-decoration: none; }
        main { max-width: 960px; margin: 24px auto; padding: 0 16px; }
        .card { background: #fff; border-radius: 8px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,.1); }
        .alert { padding: 10px 14px; border-radius: 6px; margin-bottom: 14px; }
        .alert-error { background: #fde8e8; color: #9b1c1c; }
        .alert-success { background: #def7ec; color: #03543f; }
        label { display: block; margin: 12px 0 4px; font-weight: 600; }
        input, select, textarea { width: 100%; padding: 8px 10px; border: 1px solid #cbd2d9; border-radius: 6px; font: inherit; }
        button, .btn { background: #1f4e79; color: #fff; border: 0; padding: 9px 16px; border-radius: 6px; cursor: pointer; text-decoration: none; display: inline-block; font: inherit; }
        .btn-light { background: #e4e7eb; color: #1f2933; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 8px; border-bottom: 1px solid #e4e7eb; }
        .badge { background: #e4e7eb; padding: 2px 8px; border-radius: 10px; font-size: 13px; }
    </style>
</head>
<body>
    <header>
        <div style="display:flex; gap:24px; align-items:center;">
            <a href="{{ route('dashboard') }}"><strong>MBG Report</strong></a>

            @auth
                <nav style="display:flex; gap:16px;">
                    @if (auth()->user()->role === 'pelapor')
                        <a href="{{ route('laporan.create') }}">Buat laporan</a>
                        <a href="{{ route('laporan.index') }}">Laporan saya</a>
                    @else
                        <a href="{{ route('tinjauan.index') }}">Daftar laporan</a>
                        <a href="{{ route('rekap.index') }}">Rekap</a>
                    @endif
                </nav>
            @endauth
        </div>

        @auth
            <form method="POST" action="{{ route('logout') }}" style="display:flex; gap:12px; align-items:center;">
                @csrf
                <span>{{ auth()->user()->nama }} <span class="badge">{{ auth()->user()->role }}</span></span>
                <button type="submit" class="btn-light">Logout</button>
            </form>
        @endauth
    </header>

    <main>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @yield('content')
    </main>
</body>
</html>