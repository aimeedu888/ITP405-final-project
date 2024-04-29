@extends('layout')
@section('title')
    Logout
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
    <a href="{{ route('login') }}">
        <button style="position: absolute;
        top: 20px;
        right: 20px;
        padding: 10px;
        background-color: white;
        font-size: 24px;
        border: 2px solid black;
        border-radius: 20px;
        cursor: pointer;">
            LOGIN
        </button>
    </a>
    <h1 style="text-align: center; margin-bottom: 20px; font-size: 64px; color: #f9f5ec;">YOU ARE SUCCESSFULLY LOGOUT</h1>
@endsection