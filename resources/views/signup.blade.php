@extends('layout')
@section('title')
    Signup
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
        width: 100%;
    }
</style>
@section('main') 
    <h1 style="text-align: center; margin-bottom: 20px; font-size: 64px;">SIGN UP</h1>
    <form action="/signup" method="POST" style="width: 100%;">
        @csrf
        <div style="display:flex; justify-content: center; margin-bottom: 20px; width:100%">
            @foreach($avatars as $key => $avatar)
                <div class="avatar-container" style="position: relative; margin-right: 10px; cursor:pointer" onclick="toggleCheck(this, {{$avatar->id}})">
                    <img src="{{$avatar->image_url}}" alt="Avatar" class="avatar" style="height:100px; width:auto; border-radius: 50%">
                    <div class="checkmark" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); display: {{$key === 0 ? 'block' : 'none'}}; color:white; font-size: 80px">
                        &#10003;
                    </div>
                </div>
            @endforeach
            <div id="previewContainer" style="position: relative; cursor:pointer">
                <img id="previewImage" class="avatar" style="height:100px; border-radius: 50%">
                <div class="checkmark" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); display: none; color:white; font-size: 80px">
                    &#10003;
                </div>
            </div>
            <div style="position: relative; display: inline-block;">
                <div id="addImage" style="display: flex; justify-content: center; align-items: center; margin-right: 10px; height: 100px; width: 100px; border-radius: 50%; color: white; font-size: 90px; background-color: #333; cursor:pointer;">
                    +
                </div>
            </div>
        </div>
        <div style="max-width: 550px; display: flex; flex-direction:column; margin: 0 auto;">
            <input type="text" name="avatar_url" id="avatarInput" placeholder="Enter image URL" style="width: 100%; padding: 10px; margin-bottom: 5px; border: 4px solid black; border-radius: 52px; box-sizing: border-box; display: none;">
            <input type="text" name="username" value="{{ old('username') }}" placeholder="USERNAME" style="width: 100%; padding: 10px; margin-bottom: 5px; border: 4px solid black; border-radius: 52px; box-sizing: border-box; margin-top: 10px;" required>
            @error('username')
                <small style="color: white;">{{ $message }}</small>
            @enderror
            <input type="password" name="password" value="{{ old('password') }}" placeholder="PASSWORD" style="width: 100%; padding: 10px; margin-top: 10px; margin-bottom: 5px; border: 4px solid black; border-radius: 52px; box-sizing: border-box;" required>
            @error('password')
                <small style="color: white;">{{ $message }}</small>
            @enderror
            <input type="submit" value="SIGN UP" style="width: 100%; padding: 10px; margin-top: 10px; margin-bottom: 15px; background-color: #74447c; color: #fff; border: none; border-radius: 52px; cursor: pointer;">
            <a href="{{ route('login') }}"><button type="button" style="width: 100%; padding: 10px; background-color: #b88ebc; color: #fff; border: none; border-radius: 52px; cursor: pointer;">LOGIN</button></a>
        </div>
    </form>
    <script>
        var selectImage = 1;
        function toggleCheck(container,avatarId) {
            const checkmark = container.querySelector('.checkmark');
            const allContainers = document.querySelectorAll('.avatar-container');
            if (selectImage!==avatarId){
                allContainers.forEach((container, index) => {
                    if (index+1 === selectImage) {
                        container.querySelector('.checkmark').style.display = 'none';
                    } 
                    else if (index+1 === avatarId) {
                        container.querySelector('.checkmark').style.display = 'block';
                    }
                });
                selectImage = avatarId;
                avatarInput.value = selectImage;
            }
        }

        const addButton = document.getElementById('addImage');
        const avatarInput = document.getElementById('avatarInput');

        if (avatarInput.style.display === 'none') {
            avatarInput.value = selectImage;
        }

        addButton.addEventListener('click', function() {
            avatarInput.style.display = 'block';
        });

        const previewContainer = document.getElementById('previewContainer');
        const previewImage = document.getElementById('previewImage');
    
        avatarInput.addEventListener('input', function() {
            const imageUrl = this.value.trim();
            if (imageUrl) {
                previewImage.src = imageUrl;
                previewImage.style.display = 'block';
                previewContainer.style.marginRight = '10px';
                previewContainer.querySelector('.checkmark').style.display = 'block';
                previewContainer.classList.add('avatar-container');
                const allContainers = document.querySelectorAll('.avatar-container');
                allContainers.forEach((container, index) => {
                    if (index+1 === selectImage) {
                        container.querySelector('.checkmark').style.display = 'none';
                    } 
                });
                selectImage = {{ count($avatars) + 1 }};
                previewContainer.onclick = function() {
                    if (selectImage!=={{ count($avatars) + 1 }}){
                        allContainers.forEach((container, index) => {
                            if (index+1 === selectImage) {
                                container.querySelector('.checkmark').style.display = 'none';
                            } 
                        });
                        previewContainer.querySelector('.checkmark').style.display = 'block';
                        selectImage = {{ count($avatars) + 1 }};
                    }
                };
            } else {
                previewImage.src = '';
                previewImage.style.display = 'none';
                previewContainer.style.marginRight = '0px';
                previewContainer.querySelector('.checkmark').style.display = 'none';
            }
        });
    </script>
@endsection