@extends('layout')
@section('title')
    Login
@endsection
<style>
    body {
        background-image: url("login.jpeg");
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center bottom;
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>
@section('main') 
    <h1 style="text-align: center; margin-bottom: 20px; font-size: 64px;">LOGIN</h1>
    <form action="/login" method="POST" style="width: 100%; max-width: 550px">
        @csrf
        <input type="text" name="username" value="{{ old('username') }}" placeholder="USERNAME" style="width: 100%; padding: 10px; margin-bottom: 15px; border: 4px solid black; border-radius: 52px; box-sizing: border-box;" required>
        <input type="password" name="password" value="{{ old('password') }}" placeholder="PASSWORD" style="width: 100%; padding: 10px; margin-bottom: 5px; border: 4px solid black; border-radius: 52px; box-sizing: border-box;" required>
        @session('error')
            <small style="color: white;">{{ session('error') }}</small>
        @endsession
        <input type="submit" value="LOGIN" style="width: 100%; padding: 10px; margin-top: 10px; margin-bottom: 15px; background-color: #74447c; color: #fff; border: none; border-radius: 52px; cursor: pointer;">
        <a href="{{ route('signup') }}"><button type="button" style="width: 100%; padding: 10px; background-color: #b88ebc; color: #fff; border: none; border-radius: 52px; cursor: pointer;">SIGN UP</button></a>
    </form>
@endsection