@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('auth_header', "¡Cuenta deshabilitada!")

@section('auth_body')
    <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border: 1px solid #f5c6cb; border-radius: 5px;">
        <p>¡Oh no! Parece que tu cuenta ha sido deshabilitada. Si crees que esto es un error, por favor contacta con el administrador.</p>
        <p>Disculpa las posibles molestias causadas.</p>
    </div>
@endsection

@section('auth_footer')
    <a href="{{ route('login') }}" style="text-decoration: none; color: #007bff;">Volver al inicio de sesión</a>
@endsection
