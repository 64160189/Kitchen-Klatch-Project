@extends('layout') <!-- header from layout -->
@section('title')
    Home
@endsection <!-- title from layout -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        .container {
            display: flex;
            flex-grow: 1;
            overflow: hidden;
            margin-top: 60px;
        }

        .left-sidebar {
            background-color: lightgreen;
            position: fixed;
            top: 60px;
            left: 0;
            bottom: 0;
            width: 30%;
            overflow-y: auto;
        }

        .main-content {
            margin-left: 30%;
            padding: 10px;
            overflow-y: auto;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .content-area {
            width: 70%;
            min-width: 300px;
        }

        .post-frame {
            margin-bottom: 20px;
            background: #F2F2F2;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        .post-frame img {
            display: block;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="left-sidebar">
            <h2>Search from ingrediants (Position: Fixed)</h2>
        </div>
        <div class="main-content">
            <div class="content-area">
                <h1>Posts</h1>

                @foreach ($post as $item)
                    <div class="post-frame">
                        <img src="{{ asset($item['image']) }}" alt="{{ $item['title'] }}"
                            style="width:100%; height:auto;">
                        <h2>{{ $item['title'] }}</h2>
                        <p>{{ $item['description'] }}</p>
                        <h3>วัตถุดิบ:</h3>
                        <ul>
                            @foreach ($item['ingredient'] as $ingredient)
                                <li>{{ $ingredient }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</body>

</html>
