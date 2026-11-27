@extends('layouts.app')
@section('titre', 'Connexion')
@section('contenu')
<div style="max-width:440px;margin:30px auto">
  <h1 style="text-align:center">Content de te revoir 👋</h1>
  <form class="card" method="POST" action="{{ route('login') }}">
    @csrf
    <label for="email">E-mail</label><input id="email" type="email" name="email" value="{{ old('email') }}">
    <label for="password">Mot de passe</label><input id="password" type="password" name="password">
    <p style="margin:20px 0 0"><button type="submit" style="width:100%">Se connecter</button></p>
  </form>
  <p class="muted" style="text-align:center">Pas de compte ? <a href="{{ route('inscription') }}">S'inscrire</a></p>
</div>
@endsection
