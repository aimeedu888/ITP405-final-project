@extends('layout')
@section('title')
    {{ $album['name'] }} by {{ $groupName}}
@endsection
@section('main') 
    <div style="width:100%; height:60vh; overflow:hidden; display:flex; align-items:center; margin:0px">
        <img src="{{ $album['images'][0]['url'] }}" alt="Album Cover" style="width:100%; height:auto">
        <a href="{{ route('groupDetail', ['group' => $groupName]) }}">
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
            <form method='POST' action="{{ route('viewFavoritePage') }}">
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
    {{-- {{dd($album);}} --}}
    {{-- {{dd($tracks);}} --}}
    <div class="row mt-2" style="width:75%; margin: 0 auto;">
        <small>TOTAL TRACKS: {{$album['total_tracks']}}</small>
        @if (Auth::check())
            <ul class="col-md-5 mt-2" style="list-style-type:none;">
        @else
            <small class="text-danger">login to view comments</small>
            <ul class="row mt-2" style="list-style-type:none;">
        @endif
            {{-- album --}}
            @if (Auth::check())
                <li style="display:flex; flex-direction:column">
                    <iframe src="https://open.spotify.com/embed/album/{{Str::replaceFirst('spotify:album:', '', $album['uri'])}}" width="300" height="380" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>
                    <div style="display:flex; flex-direction:row; justify-content: center; width:300px; margin-top:30px; margin-bottom:30px;">
                        <form action="{{ route('albumDetail', ['group' => $groupName, 'albumID' => $album['id'], 'commentIndex' => 0]) }}" method="GET">
                            @csrf
                            <button style="height:50px; width:200px; margin-left:0px; border:0px; pointer:cursor; border-radius: 20px">VIEW COMMENTS</button>
                        </form>
                        @if($favorite_album_exist->isEmpty())
                            <form action="{{ route('addFavoriteAlbum', ['group' => $groupName, 'album_id' => $album['id'], 'user_id' => Auth::id()]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary" style="height:50px; width:50px; margin-left:10px; border: 2px solid #6c757d; border-radius: 20px" onclick="return confirm('Are you sure you want to add to favorites?')">&hearts;</button>
                            </form>
                        @else
                            <form action="{{ route('removeFavoriteAlbum', ['group' => $groupName, 'album_id' => $album['id']]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-secondary" style="height:50px; width:50px; margin-left:10px; border:0px; border-radius: 20px" onclick="return confirm('Are you sure you want to remove from favorites?')">X</button>
                            </form>
                        @endif
                    </div>
                    @if (session('albumError') && $album['id']==session('album_id')) 
                        <div class="alert alert-danger" role="alert" style="width:300px">
                            {{session('albumError')}}
                        </div>
                    @elseif (session('albumSuccess') && $album['id']==session('album_id')) 
                        <div class="alert alert-success" role="alert" style="margin-top:15px;">
                            {{session('albumSuccess')}}
                        </div>
                    @else 
                        <div style="margin-bottom:30px;"></div>
                    @endif
                </li>
            @else
                <li class="col-lg-4 mb-2" style="display:flex; justify-content: center;">
                    <iframe src="https://open.spotify.com/embed/album/{{Str::replaceFirst('spotify:album:', '', $album['uri'])}}" width="300" height="380" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>
                </li>
            @endif
            {{-- tracks --}}
            @foreach($tracks as $track)
                @if (Auth::check())
                    <li style="display:flex; flex-direction:column">
                        <iframe src="https://open.spotify.com/embed/track/{{Str::replaceFirst('spotify:track:', '', $track['uri'])}}" width="300" height="380" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>
                        <div style="display:flex; flex-direction:row; justify-content: center; width:300px">
                            <form action="{{ route('albumDetail', ['group' => $groupName, 'albumID' => $album['id'], 'commentIndex' => ($loop->index + 1)]) }}" method="GET">
                                @csrf
                                <button style="height:50px; width:200px; margin-left:0px; border:0px; pointer:cursor; border-radius: 20px">VIEW COMMENTS</button>
                            </form>
                            @if (Auth::check())
                                    {{-- {{dd($favorite_tracks);}} --}}
                                    @if($favorite_tracks->contains('track_id', $track['id']))
                                        <form action="{{ route('removeFavoriteTrack', ['group' => $groupName, 'album_id' => $album['id'], 'track_id' => $track['id'], 'commentIndex' => $commentIndex]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-secondary" style="height:50px; width:50px; margin-left:10px; border:0px; border-radius: 20px" onclick="return confirm('Are you sure you want to remove from favorites?')">X</button>
                                        </form>
                                    @else
                                        <form action="{{ route('addFavoriteTrack', ['group' => $groupName, 'album_id' => $album['id'], 'track_id' => $track['id'], 'user_id' => Auth::id(), 'commentIndex' => $commentIndex]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-secondary" style="height:50px; width:50px; margin-left:10px; border: 2px solid #6c757d; border-radius: 20px" onclick="return confirm('Are you sure you want to add to favorites?')">&hearts;</button>
                                        </form>
                                    @endif
                            @endif
                        </div>
                        @if (session('trackError') && $track['id']==session('track_id')) 
                            <div class="alert alert-danger" role="alert" style="width:300px">
                                {{session('trackError')}}
                            </div>
                        @elseif (session('trackSuccess') && $track['id']==session('track_id')) 
                            <div class="alert alert-success" role="alert" style="margin-top:15px;">
                                {{session('trackSuccess')}}
                            </div>
                        @else 
                            <div style="margin-bottom:30px;"></div>
                        @endif
                    </li>
                @else
                    <li class="col-lg-4" style="display:flex; justify-content: center;">
                        <iframe src="https://open.spotify.com/embed/track/{{Str::replaceFirst('spotify:track:', '', $track['uri'])}}" width="300" height="380" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>
                    </li>
                @endif
            @endforeach
        </ul>

        <!-- Comment Section -->
        @if (Auth::check())
            <div class="col-md-7 mt-2" style="background-color: #EFEFEF; border-radius:15px; height:100%; padding: 30px 30px;">
                <div>
                    @if ($commentIndex==0)
                        <h1 class="comments-title">COMMENTS of "{{$album['name']}}"</h1>
                    @else
                        <h1 class="comments-title">COMMENTS of "{{$tracks[$commentIndex-1]['name']}}"</h1>
                    @endif
                    @if ($comments->isEmpty())
                        <p style="padding-left:5px; margin-bottom:5px">No comment yet. Be the first one to comment out!</p>
                    @else
                        @foreach ($comments as $comment)
                            <div>
                                {{-- avatar --}}
                                <div style="width: 60px; height: 60px; float: left; margin-bottom: 0px;">	
                                    <img src="{{$comment->user->avatar->image_url}}" alt="user avatar" style="width: 60px; height: 60px; border-radius: 50%;">
                                </div>
                                <div style="margin-left: 80px;">
                                    {{-- username --}}
                                    <span style="display: inline-block; width: 49%; margin-bottom: 15px; font-size: 13px; color: #383b43;">
                                        {{$comment->username}}
                                    </span>
                                    {{-- time --}}
                                    <span style="display: inline-block; width: 49%; margin-bottom: 15px; text-align: right; font-size: 11px; color: ##383b43;">
                                        <i class="fa fa-clock-o"></i>
                                    @if ($comment->created_at==$comment->updated_at)
                                        {{$comment->created_at}}
                                    @else
                                        uodated at {{$comment->updated_at}}
                                    @endif
                                    </span>
                                    {{-- content --}}
                                    <div style="display: flex; align-items:center; flex-direction:row; font-size: 13px; color: #7a8192; background: #f6f6f7; border: 1px solid #edeff2; border-radius:10px; padding: 15px 20px 20px 20px;" class="comment">
                                        <p style="width:90%; margin-bottom:0px">{!! $comment->comment !!}</p>
                                        @if ($commentIndex==0)
                                            {{-- edit/delete for album --}}
                                            @if ($comment->user_id == Auth::id())
                                                <form method="POST" action="{{ route('editAlbumComment', ['group' => $groupName, 'album_id' => $album['id'], 'commentIndex' => $commentIndex, 'comment_id' => ($comment->id)]) }}" style="display: flex; ">
                                                    @csrf
                                                    <button type="submit" style="border: none; background: none; cursor: pointer;">
                                                        <i class="bi bi-pen-fill"></i>
                                                    </button>
                                                    <textarea name="newComment" style="color: #383b43; width: 200px; height: 70px; padding: 5px 10px; border: 1.5px solid #edeff2; border-radius: 10px;"></textarea>
                                                </form>
                                                <form method="POST" action="{{ route('removeAlbumComment', ['group' => $groupName, 'album_id' => $album['id'], 'commentIndex' => $commentIndex, 'comment_id' => ($comment->id)]) }}" style="display: flex; ">
                                                    @csrf
                                                    <button type="submit" style="border: none; background: none; cursor: pointer;">
                                                        <i class="material-icons">&#xe872;</i>
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            {{-- edit/delete for track --}}
                                            @if ($comment->user_id == Auth::id())
                                                <form method="POST" action="{{ route('editTrackComment', ['group' => $groupName, 'album_id' => $album['id'], 'commentIndex' => $commentIndex, 'comment_id' => ($comment->id)]) }}" style="display: flex; ">
                                                    @csrf
                                                    <button type="submit" style="border: none; background: none; cursor: pointer;">
                                                        <i class="bi bi-pen-fill"></i>
                                                    </button>
                                                    <textarea name="newComment" style="color: #383b43; width: 200px; height: 70px; padding: 5px 10px; border: 1.5px solid #edeff2; border-radius: 10px;"></textarea>
                                                </form>
                                                <form method="POST" action="{{ route('removeTrackComment', ['group' => $groupName, 'album_id' => $album['id'], 'commentIndex' => $commentIndex, 'comment_id' => ($comment->id)]) }}" style="display: flex; ">
                                                    @csrf
                                                    <button type="submit" style="border: none; background: none; cursor: pointer;">
                                                        <i class="material-icons">&#xe872;</i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                    @if (session('commentError') && (session('comment_id')==$comment->id))
                                        <div class="alert alert-danger mb-1" role="alert" style="margin-top:5px; width:100%">
                                            {{session('commentError')}}
                                        </div>
                                    @elseif (session('singleCommentSuccess') && (session('comment_id')==$comment->id))
                                        <div class="alert alert-success mb-1" role="alert" style="margin-top:5px; width:100%">
                                            {{session('singleCommentSuccess')}}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif
                    {{-- create comment --}}
                    @if ($commentIndex==0)
                        <form class="mt-3" method="POST" action="{{ route('albumComment', ['group' => $groupName, 'album_id' => $album['id'], 'commentIndex' => $commentIndex]) }}">
                    @else
                        <form class="mt-3" method="POST" action="{{ route('trackComment', ['group' => $groupName, 'album_id' => $album['id'], 'commentIndex' => $commentIndex, 'track_id' => $tracks[$commentIndex-1]['id']]) }}">
                    @endif
                            @csrf							
                                <textarea required="required" placeholder="your comment" name='comment'
                                    style="font-size: 13px;
                                        color: #383b43;
                                        width: 100%;
                                        height: 70px;
                                        padding: 5px 10px;
                                        border: 1px solid #edeff2;
                                        border-radius: 10px;">{{ old('comment') }}</textarea>
                                @error('comment')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                                @error('newComment')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                                @if (session('commentSuccess')) 
                                    <div class="alert alert-success mb-1" role="alert" style="margin-top:5px; width:100%">
                                        {{session('commentSuccess')}}
                                    </div>
                                @endif
                                <button class="btn btn-secondary pull-right mt-2" style="width:100%">send</button>
                        </form>
                </div>
            </div>
        @endif
    </div>
@endsection