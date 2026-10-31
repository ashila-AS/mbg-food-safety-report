@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="card" style="max-width: 420px; margin: 40px auto;">
        <h2>Login</h2>

        @if ($errors->any())
            <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login.attempt') }}">
            @csrf
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <div style="margin-top: 18px;">
                <button type="submit">Masuk</button>
            </div>
        </form>
    </div>
@endsection