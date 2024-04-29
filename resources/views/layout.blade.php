<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"></head>
    <link rel="stylesheet" href="{{ asset('css/homepage-animation.css') }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.0/font/bootstrap-icons.css" rel="stylesheet">


    <style>
        @import url('https://fonts.googleapis.com/css2?family=Modak&display=swap');
        body {
            margin: 0;
            padding: 0;
        }
        .circle-container {
            position: relative;
            border-radius: 50%;
            overflow: hidden;
        }
        .photo {
            cursor: pointer;
        }
        .photo:hover {
            opacity: 0.7;
            border: 4px solid black;
        }
        .group.selected,.filter.selected {
            background-color: #A9A9A9!important;
        }
    }
    </style>
<body>
    <div>
        @yield('main')
    </div>
</body>
</html>