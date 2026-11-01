
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Pelaporan Keamanan Makanan MBG')</title>

    <style>
        :root {
            --pink: #f9c5d1;
            --pink-hover: #f4aebf;
            --berry: #5a2433;
            --matcha: #dcefd5;
            --matcha-deep: #2f4f2f;
            --bg: #fffafb;
            --text: #3d2b30;
            --line: #f3e2e6;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: system-ui, sans-serif;
            margin: 0;
            background: var(--bg);
            color: var(--text);
        }

        main {
            max-width: 960px;
            margin: 24px auto;
            padding: 0 16px;
        }

        .card {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(90, 36, 51, .10);
        }

        .teks-panjang,
        .card p {
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        header {
            background: var(--pink);
            color: var(--berry);
            padding: 12px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid var(--matcha);
        }

        header a {
            color: var(--berry);
            text-decoration: none;
        }

        .alert {
            padding: 10px 14px;
            border-radius: 6px;
            margin-bottom: 14px;
        }

        .alert-error {
            background: #fde4e8;
            color: #9b2237;
        }

        .alert-success {
            background: #e3f3dc;
            color: #2f5a2f;
        }

        label {
            display: block;
            margin: 12px 0 4px;
            font-weight: 600;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #ecd3d9;
            border-radius: 6px;
            font: inherit;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: 2px solid var(--matcha);
            border-color: #8fc388;
        }

        button,
        .btn {
            background: var(--pink);
            color: var(--berry);
            border: 0;
            padding: 9px 16px;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font: inherit;
            white-space: nowrap;
        }

        button:hover,
        .btn:hover {
            background: var(--pink-hover);
        }

        .btn-light {
            background: var(--matcha);
            color: var(--matcha-deep);
        }

        .btn-light:hover {
            background: #cbe5c1;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            text-align: left;
            padding: 8px;
            border-bottom: 1px solid var(--line);
        }

        .badge {
            background: var(--matcha);
            color: var(--matcha-deep);
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 13px;
        }

        .pagination {
            margin-top: 14px;
        }

        .pagination-info {
            margin: 0 0 8px;
            font-size: 14px;
        }

        .pagination ul {
            list-style: none;
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            padding: 0;
            margin: 0;
        }

        .pagination a,
        .pagination span {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            background: var(--matcha);
            color: var(--matcha-deep);
            text-decoration: none;
        }

        .pagination a:hover {
            background: #cbe5c1;
        }

        .pagination .active span {
            background: var(--pink);
            color: var(--berry);
            font-weight: 600;
        }

        .pagination .disabled span {
            background: #f6f1f2;
            color: #b9a8ad;
        }
    </style>
</head>

<body>

    <header>

        <div style="display: flex; gap: 24px; align-items: center;">

            <a href="{{ route('dashboard') }}">
                <strong>MBG Report</strong>
            </a>

            @auth
                <nav style="display: flex; gap: 16px;">

                    <a href="{{ route('dashboard') }}">
                        Dashboard
                    </a>

                    @if (auth()->user()->role === 'pelapor')

                        <a href="{{ route('laporan.create') }}">
                            Buat laporan
                        </a>

                        <a href="{{ route('laporan.index') }}">
                            Laporan saya
                        </a>

                    @else

                        <a href="{{ route('tinjauan.index') }}">
                            Daftar laporan
                        </a>

                        <a href="{{ route('rekap.index') }}">
                            Rekap
                        </a>

                    @endif

                </nav>
            @endauth

        </div>

        @auth
            <form
                method="POST"
                action="{{ route('logout') }}"
                style="display: flex; gap: 12px; align-items: center;"
            >
                @csrf

                <span>
                    {{ auth()->user()->nama }}

                    <span class="badge">
                        {{ auth()->user()->role }}
                    </span>
                </span>

                <button type="submit" class="btn-light">
                    Logout
                </button>

            </form>
        @endauth

    </header>

    <main>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')

    </main>

</body>
</html>
