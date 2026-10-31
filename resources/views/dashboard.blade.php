@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="card">
        <h2>Halo, {{ auth()->user()->nama }}</h2>
        <p>Anda masuk sebagai <strong>{{ auth()->user()->role }}</strong>.</p>
    </div>
@endsection