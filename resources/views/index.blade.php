@extends('layout')
@section('title')
    Home
@endsection
<style>
    #main {
        background-image: url("homepage.png");
        background-size: cover;
        background-repeat: no-repeat;
        overflow: hidden;
    }
</style>
@section('main') 
    {{-- <ul>
        @foreach ($groups as $group)
            <li>group name: {{$group->name}}, youtube fans: {{$group->youtube_fans}}</li>
        @endforeach
    </ul> --}}
    
    <div style="position: relative; width: 100%; height: 101vh;" id="main">
        <div></div>
        @if (Auth::check())
            <form method='POST' action="{{ route('viewFavoritePage', ['username' => Auth::user()->name]) }}">
                @csrf
                <button type='submit' style="position: absolute;
                    top: 20px;
                    right: 150px;
                    padding: 10px;
                    background-color: white;
                    font-size: 24px;
                    border: 2px solid black;
                    border-radius: 20px;
                    cursor: pointer;
                    z-index:1">
                    FAVORITES
                </button>
        </form>
            <a href="{{ route('logout') }}">
        @else
            <a href="{{ route('login') }}">
        @endif
            <button style="position: absolute;
            top: 20px;
            right: 20px;
            padding: 10px;
            background-color: white;
            font-size: 24px;
            border: 2px solid black;
            border-radius: 20px;
            cursor: pointer;">
            @if (Auth::check())
                LOGOUT
            @else
                LOGIN
            @endif
            </button>
        </a>
        <div class="circle-container photo" style="
            width: 25vw;
            max-width: 170px;
            height: 25vw;
            max-height: 170px;
            top:50%; left:50%;
            transform:translate(-50%, -50%);">
            <a href="{{ route('groupDetail', ['group' => $groups[0]->name]) }}">
                <img src="{{ 'homepage/'.$groups[0]->name.'.png' }}" alt="{{ $groups[0]->name.' image' }}" style="width:100%">
            </a>
        </div>
        <div class="circle-container" style="
            width: 23vw;
            max-width: 155px;
            height: 23vw;
            max-height: 155px;
            top:50%; left:50%;
            animation: moveCircle2 0.5s ease forwards;
            background-color: black;">
                <img src="{{ 'homepage/'.$groups[0]->name.' logo.png' }}" alt="{{ $groups[0]->name.' logo' }}" style="width:100%">
                <span style="position: absolute; color: white; bottom: 35px; left: 0; right: 0; font-size: 10px; text-align: center;">YouTube Fans: {{ sprintf('%.1f', $groups[0]->youtube_fans / 1000000) }}M</span>
        </div>
        <div class="circle-container photo" style="
            width: 21vw;
            max-width: 140px;
            height: 21vw;
            max-height: 140px;
            top:50%; left:50%;
            animation: moveCircle3 0.45s ease forwards;
            animation-delay: 0.5s;
            visibility: hidden;
            display: flex;
            justify-content: center;">
            <a href="{{ route('groupDetail', ['group' => $groups[1]->name]) }}">
                <img src="{{ 'homepage/'.$groups[1]->name.'.png' }}" alt="{{ $groups[0]->name.' image' }}" style="height:100%">
            </a>
        </div>
        <div class="circle-container" style="
            width: 19vw;
            max-width: 125px;
            height: 19vw;
            max-height: 125px;
            top:50%; left:50%;
            animation: moveCircle4 0.45s ease forwards;
            animation-delay: 0.95s;
            visibility: hidden;
            background-color: black;">
                <img src="{{ 'homepage/'.$groups[1]->name.' logo.png' }}" alt="{{ $groups[1]->name.' logo' }}" style="width:100%">
                <span style="position: absolute; color: white; bottom: 15px; left: 5; right: 5; font-size: 10px; line-height: 1; text-align: center;">YouTube Fans:<br>{{ sprintf('%.1f', $groups[1]->youtube_fans / 1000000) }}M</span>
        </div>
        <div class="circle-container photo" style="
            width: 18vw;
            max-width: 120px;
            height: 18vw;
            max-height: 120px;
            top:50%; left:50%;
            animation: moveCircle5 0.4s ease forwards;
            animation-delay: 1.4s;
            visibility: hidden;
            display: flex;
            justify-content: center;">
            <a href="{{ route('groupDetail', ['group' => $groups[2]->name]) }}">
                <img src="{{ 'homepage/'.$groups[2]->name.'.png' }}" alt="{{ $groups[0]->name.' image' }}" style="height:100%">
            </a>
        </div>
        <div class="circle-container" style="
            width: 17vw;
            max-width: 110px;
            height: 17vw;
            max-height: 110px;
            top:50%; left:50%;
            animation: moveCircle6 0.4s ease forwards;
            animation-delay: 1.8s;
            visibility: hidden;
            background-color: black;">
                <img src="{{ 'homepage/'.$groups[2]->name.' logo.png' }}" alt="{{ $groups[2]->name.' logo' }}" style="width:100%">
                <span style="position: absolute; color: white; bottom: 10px; left: 0; right: 0; font-size: 10px; line-height: 1; text-align: center;">YouTube Fans:<br>{{ sprintf('%.1f', $groups[2]->youtube_fans / 1000000) }}M</span>
        </div>
        <div class="circle-container photo" style="
            width: 16vw;
            max-width: 100px;
            height: 16vw;
            max-height: 100px;
            top:50%; left:50%;
            animation: moveCircle7 0.35s ease forwards;
            animation-delay: 2.2s;
            visibility: hidden;
            display: flex;
            justify-content: center;
            display: flex;
            justify-content: center;">
            <a href="{{ route('groupDetail', ['group' => $groups[3]->name]) }}">
                <img src="{{ 'homepage/'.$groups[3]->name.'.png' }}" alt="{{ $groups[0]->name.' image' }}" style="height:100%">
            </a>
        </div>
        <div class="circle-container" style="
            width: 15vw;
            max-width: 90px;
            height: 15vw;
            max-height: 90px;
            top:50%; left:50%;
            animation: moveCircle8 0.35s ease forwards;
            animation-delay: 2.55s;
            visibility: hidden;
            background-color: black;">
                <img src="{{ 'homepage/'.$groups[3]->name.' logo.png' }}" alt="{{ $groups[3]->name.' logo' }}" style="width:100%">
                <span style="position: absolute; color: white; bottom: 10px; left: 0; right: 0; font-size: 8px; line-height: 1; text-align: center;">YouTube Fans:<br>{{ sprintf('%.1f', $groups[3]->youtube_fans / 1000000) }}M</span>
        </div>
        <div class="circle-container photo" style="
            width: 14vw;
            max-width: 80px;
            height: 14vw;
            max-height: 80px;
            top:50%; left:50%;
            animation: moveCircle9 0.3s ease forwards;
            animation-delay: 2.9s;
            visibility: hidden;
            display: flex;
            justify-content: center;">
            <a href="{{ route('groupDetail', ['group' => $groups[4]->name]) }}">
                <img src="{{ 'homepage/'.$groups[4]->name.'.png' }}" alt="{{ $groups[0]->name.' image' }}" style="height:100%;">
            </a>
        </div>
        <div class="circle-container" style="
            width: 13vw;
            max-width: 70px;
            height: 13vw;
            max-height: 70px;
            top:50%; left:50%;
            animation: moveCircle10 0.3s ease forwards;
            animation-delay: 3.2s;
            visibility: hidden;
            background-color: black;">
                <img src="{{ 'homepage/'.$groups[4]->name.' logo.png' }}" alt="{{ $groups[4]->name.' logo' }}" style="width:100%">
                <span style="position: absolute; color: white; bottom: 7px; left: 0; right: 0; font-size: 6px; line-height: 1; text-align: center;">YouTube Fans:<br>{{ sprintf('%.1f', $groups[4]->youtube_fans / 1000000) }}M</span>
        </div>
        <div class="circle-container photo" style="
            width: 12vw;
            max-width: 60px;
            height: 12vw;
            max-height: 60px;
            top:50%; left:50%;
            animation: moveCircle11 0.25s ease forwards;
            animation-delay: 3.5s;
            visibility: hidden;
            display: flex;
            justify-content: center;">
            <a href="{{ route('groupDetail', ['group' => $groups[5]->name]) }}">
                <img src="{{ 'homepage/'.$groups[5]->name.'.png' }}" alt="{{ $groups[0]->name.' image' }}" style="height:100%">
            </a>
        </div>
        <div class="circle-container" style="
            width: 11vw;
            max-width: 50px;
            height: 11vw;
            max-height: 50px;
            top:50%; left:50%;
            animation: moveCircle12 0.25s ease forwards;
            animation-delay: 3.75s;
            visibility: hidden;
            background-color: black;">
                <img src="{{ 'homepage/'.$groups[5]->name.' logo.png' }}" alt="{{ $groups[5]->name.' logo' }}" style="width:100%">
                <span style="position: absolute; color: white; bottom: 2px; left: 0; right: 0; font-size: 6px; line-height: 1; text-align: center;">YouTube Fans: {{ sprintf('%.1f', $groups[5]->youtube_fans / 1000000) }}M</span>
        </div>
    </div>

@endsection