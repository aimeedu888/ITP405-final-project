@extends('layout')
@section('title')
    Favorites
@endsection
@section('main') 
    {{-- {{dd($favorite_albums)}}
    {{dd($favorite_tracks)}} --}}
    <button style="position: absolute;
        top: 20px;
        left: 20px;
        padding: 10px;
        background-color: white;
        font-size: 24px;
        width: 50px;
        border: 1.5px solid black;
        border-radius: 20px;
        cursor: pointer;
        z-index:1"
        onclick="goBack()">
        ←
    </button>
    <div class="container mt-5">
        <h1 class="text-center mb-5">FAVORITE ALBUMS & TRACKS</h1>
        
        <!-- Group selection -->
        <div class="d-flex mb-3">
            <button type="button" class="btn btn-dark me-4">
                SELECT GROUPS
            </button>
            <div class="d-flex">
                @foreach($groups as $group)
                    <button type="button" class="group btn me-2" style="background-color:#D3D4D5;" id="{{$group->spotify_id}}">
                        {{$group->name}}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Group selection -->
        <div class="d-flex mb-3">
            <button type="button" class="btn btn-dark me-4">
                SHOW
            </button>
            <div class="d-flex">
                <button type="button" class="filter btn me-2 selected" style="background-color:#D3D4D5;" id="default-filter">ALBUM</button>
                <button type="button" class="filter btn me-2" style="background-color:#D3D4D5;">TRACK</button>
            </div>
        </div>
        <div id="error-message" class="text-danger"></div>
    </div>
    <div id="favorite-container" class="row mt-5" style="width:75%; margin: 0 auto;"></div>
    <input type="hidden" id="userId" value="{{ Auth::id() }}">
    <input type="hidden" id="csrfToken" value="{{ csrf_token() }}">

    <script>
        function goBack() {
            window.history.back();
        }

        var selectedGroupIds = [];
        var groups = document.querySelectorAll('.group');
        
        groups.forEach(function(button) {
            button.addEventListener('click', function() {
                button.classList.toggle('selected');
                if (button.classList.contains('selected')) {
                    selectedGroupIds.push(button.id.trim());
                } 
                else {
                    var index = selectedGroupIds.indexOf(button.id.trim());
                    if (index !== -1) {
                        selectedGroupIds.splice(index, 1);
                    }
                }
                if (selectedGroupIds.length === 0) {
                    document.getElementById('favorite-container').innerHTML = '';
                    document.getElementById('error-message').innerText = 'Please select a group';
                }
                else {
                    document.getElementById('error-message').innerText = '';
                    handleButtonClick();
                }
            });
        });

        var selectedFilter = document.getElementById('default-filter');
        var fileters = document.querySelectorAll('.filter');
    
        fileters.forEach(function(button) {
            button.addEventListener('click', function() {
                if (selectedFilter) {
                    selectedFilter.classList.remove('selected');
                }
                button.classList.add('selected');
                selectedFilter = button;
                if (selectedGroupIds.length !== 0) {
                    handleButtonClick();
                }
            });
        });

        if (selectedGroupIds.length === 0) {
            document.getElementById('error-message').innerText = 'Please select a group';
        }

        var userId = document.getElementById('userId').value;
        var csrfToken = document.getElementById('csrfToken').value;

        function handleButtonClick() {
            var filterStr = selectedFilter.textContent.trim();
            var url = '/favorite/' + userId + '/' + selectedGroupIds + '/' + filterStr;
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if (filterStr==="ALBUM"){
                    displayAlbums(data.albums,csrfToken);
                }
                else if (filterStr==="TRACK"){
                    displayTracks(data.tracks,csrfToken);
                }
                else {
                    document.getElementById('error-message').innerText = 'Please choose a valid option!';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('error-message').innerText = 'Error: ' + error.message;
            });
        }

        function displayAlbums(albums,csrfToken) {
            var albumsContainer = document.getElementById('favorite-container');
            albumsContainer.innerHTML = '';
            if (albums.length===0){
                document.getElementById('error-message').innerText = 'Nothing under your selected group right now. Start by adding one!';
                return;
            }
            albums.forEach(function(album) {
                var wrapper = document.createElement('div');
                wrapper.className = 'col-md-3';
                var card = document.createElement('div');
                card.className = 'card mb-4 shadow-sm';
                wrapper.appendChild(card);

                var albumRoutes = {
                    albumDetail: "{{ route('albumDetail', ['group' => ':group', 'albumID' => ':albumID', 'commentIndex' => ':commentIndex']) }}",
                    removeFavoriteAlbum: "{{ route('removeFavoriteAlbum', ['group' => ':group', 'album_id' => ':albumID']) }}",
                };

                var cardContent = `
                    <img src="${album.images[0].url}" class="card-img-top" alt="Album Cover">
                    <div class="card-body">
                        <h5 class="card-title">${album.name}</h5>
                        <p class="card-text mb-1">released at: ${album.release_date}</p>
                        <p class="card-text mb-1">added at: ${album.added_to_favorite_at}</p>
                        <p class="card-text">By: ${album.artists[0].name}</p>
                        <div class="btn-group d-flex align-items-center" style="width:100%; justify-content: space-between; ">
                            <a href="${albumRoutes.albumDetail.replace(':group', album.artists[0].name).replace(':albumID', album.id).replace(':commentIndex', 0)}">
                                <button type="button" class="btn btn-sm btn-outline-secondary">View Details</button>
                            </a>
                            <form action="${albumRoutes.removeFavoriteAlbum.replace(':group', album.artists[0].name).replace(':albumID', album.id)}" method="POST">
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <button type="submit" class="btn btn-sm btn-secondary" onclick="return confirm('Are you sure you want to remove from favorites?')">X</button>
                            </form>
                        </div>
                    </div>
                `;

                card.innerHTML = cardContent;
                albumsContainer.appendChild(wrapper);
            });
        }

        function displayTracks(tracks,csrfToken) {
            var albumsContainer = document.getElementById('favorite-container');
            albumsContainer.innerHTML = '';
            if (tracks.length===0){
                document.getElementById('error-message').innerText = 'Nothing under your selected group right now. Start by adding one!';
                return;
            }
            tracks.forEach(function(track) {
                var wrapper = document.createElement('div');
                wrapper.className = 'col-md-3';
                var card = document.createElement('div');
                card.className = 'card mb-4 shadow-sm';
                wrapper.appendChild(card);

                var removeFavoriteTrack = "{{ route('removeFavoriteTrack', ['group' => ':group', 'album_id' => ':albumID', 'track_id' => ':trackID', 'commentIndex' => ':commentIndex']) }}";
                var cardContent = `
                    <img src="${track.album.images[0].url}" class="card-img-top" alt="Album Cover">
                    <div class="card-body">
                        <h5 class="card-title">${track.name}</h5>
                        <p class="card-text mb-1">released at: ${track.album.release_date}</p>
                        <p class="card-text mb-1">added at: ${track.added_to_favorite_at}</p>
                        <div class="btn-group d-flex align-items-center" style="width:100%; justify-content: space-between; ">
                            <p class="card-text">By: ${track.artists[0].name}</p>
                            <form action="${removeFavoriteTrack.replace(':group', track.artists[0].name).replace(':albumID', track.album.id).replace(':trackID', track.id).replace(':commentIndex', 0)}" method="POST">
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <button type="submit" class="btn btn-sm btn-secondary" onclick="return confirm('Are you sure you want to remove from favorites?')">X</button>
                            </form>
                        </div>
                    </div>
                `;
                card.innerHTML = cardContent;
                albumsContainer.appendChild(wrapper);
            });
        }

    </script>
    
@endsection