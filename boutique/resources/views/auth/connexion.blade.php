@extends('layouts.app')
@section('titre', 'Connexion')
@section('contenu')
<h1>Connexion</h1>
<form class="carte" method="POST" action="{{ route('login') }}">
  @csrf
  <label for="email">E-mail</label><input id="email" type="email" name="email" value="{{ old('email') }}">
  <label for="password">Mot de passe</label><input id="password" type="password" name="password">
  <p><button type="submit">Se connecter</button></p>
</form>
<p>Pas de compte ? <a href="{{ route('inscription') }}">S'inscrire</a></p>
@endsection
