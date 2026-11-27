@extends('layouts.app')
@section('titre', 'Inscription')
@section('contenu')
<div style="max-width:440px;margin:30px auto">
  <h1 style="text-align:center">Crée ton compte</h1>
  <form class="card" method="POST" action="{{ route('inscription') }}">
    @csrf
    <label for="nom">Nom</label><input id="nom" name="nom" value="{{ old('nom') }}">
    <label for="email">E-mail</label><input id="email" type="email" name="email" value="{{ old('email') }}">
    <label for="telephone">Téléphone (facultatif)</label><input id="telephone" name="telephone" value="{{ old('telephone') }}">
    <label for="password">Mot de passe (8 caractères minimum)</label><input id="password" type="password" name="password">
    <p style="margin:20px 0 0"><button type="submit" style="width:100%">Créer mon compte</button></p>
  </form>
  <p class="muted" style="text-align:center">Déjà inscrit ? <a href="{{ route('login') }}">Se connecter</a></p>
</div>
@endsection
