@extends('layout')
@section('title')
    Albums of {{ $groupName }}
@endsection
@section('main') 
    <div style="width:100%; height:60vh; overflow:hidden; display:flex; align-items:center; margin:0px">
        <img src="{{ $group['images'][0]['url'] }}" alt="Group Cover" style="width:100%; height:auto">
        <a href="{{ route('home') }}">
            <button style="position: absolute;
                top: 20px;
                left: 20px;
                padding: 10px;
                background-color: white;
                font-size: 24px;
                width: 50px;
                border: 0px;
                border-radius: 20px;
                cursor: pointer;
                z-index:1">
                ←
            </button>
        </a>
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
    </div>
    {{-- {{dd($group);}} --}}
    {{-- {{dd($albums);}} --}}
    <div class="row mt-5" style="width:75%; margin: 0 auto;">
        @foreach($albums as $album)
            <div class="col-md-3">
                <div class="card mb-4 shadow-sm">
                    <img src="{{ $album['images'][0]['url'] }}" class="card-img-top" alt="Album Cover">
                    <div class="card-body">
                        <h5 class="card-title">{{ $album['name'] }}</h5>
                        <p class="card-text">{{ $album['release_date'] }}</p>
                        <div class="btn-group d-flex align-items-center" style="width:100%; 
                        @if (Auth::check()) justify-content: space-between; 
                        @else justify-content: center; 
                        @endif ">
                            <a href="{{ route('albumDetail', ['group' => $groupName, 'albumID' => $album['id'], 'commentIndex' => 0]) }}">
                                <button type="button" class="btn btn-sm btn-outline-secondary">View Details</button>
                            </a>
                            @if (Auth::check())
                                    {{-- {{dd($favorites);}} --}}
                                    @if($favorites->contains('album_id', $album['id']))
                                        <form action="{{ route('removeFavoriteAlbum', ['group' => $groupName, 'album_id' => $album['id']]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-secondary" onclick="return confirm('Are you sure you want to remove from favorites?')">X</button>
                                        </form>
                                        {{-- <button type="button" class="btn btn-sm btn-outline-secondary" onclick="return alert('Already in favorites!')">X</button> --}}
                                    @else
                                        <form action="{{ route('addFavoriteAlbum', ['group' => $groupName, 'album_id' => $album['id'], 'user_id' => Auth::id()]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-secondary" onclick="return confirm('Are you sure you want to add to favorites?')">&hearts;</button>
                                        </form>
                                    @endif
                                </form>
                            @endif
                        </div>
                        @if (session('albumError') && $album['id']==session('album_id')) 
                            <div class="alert alert-danger mt-3" role="alert">
                                {{session('albumError')}}
                            </div>
                        @elseif (session('albumSuccess') && $album['id']==session('album_id')) 
                            <div class="alert alert-success mt-3" role="alert">
                                {{session('albumSuccess')}}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
