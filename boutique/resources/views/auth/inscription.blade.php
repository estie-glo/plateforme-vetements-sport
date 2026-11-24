@extends('layouts.app')
@section('titre', 'Inscription')
@section('contenu')
<h1>Créer un compte</h1>
<form class="carte" method="POST" action="{{ route('inscription') }}">
  @csrf
  <label for="nom">Nom</label><input id="nom" name="nom" value="{{ old('nom') }}">
  <label for="email">E-mail</label><input id="email" type="email" name="email" value="{{ old('email') }}">
  <label for="telephone">Téléphone (facultatif)</label><input id="telephone" name="telephone" value="{{ old('telephone') }}">
  <label for="password">Mot de passe (8 caractères minimum)</label><input id="password" type="password" name="password">
  <p><button type="submit">Créer mon compte</button></p>
</form>
<p>Déjà inscrit ? <a href="{{ route('login') }}">Se connecter</a></p>
@endsection
