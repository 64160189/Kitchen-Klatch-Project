// resources/views/fullPost.blade.php
@extends('layout') <!-- header from layouts/app -->
@section('title')
    {{ $post->title }}
@endsection <!-- title from layout -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        .container {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            overflow: hidden;
            margin-top: 60px;
        }

        .back-button {
            display: flex;
            margin-bottom: 20px;
            width: 90px;
            height: 38px;
        }
    </style>
</head>

<body>
    <div class="container">
        <button class="back-button btn btn-outline-danger" onclick="window.history.back();"><svg
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0px 0px 24px 24px" stroke-width="1.5"
                stroke="currentColor" class="size-3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>Back</button>

        <div class="card mb-3 shadow">
            <img class="card-img-top" src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}"
                style="width:100%; height:auto; max-height:1000px">
            <div class="card-body">
                <h1 class="card-title">{{ $post->title }}</h1>
                <p class="card-text">{{ $post->description }}</p>
            </div>
        </div>

        <div class="card card-body mb-3 shadow">
            <h3 class="card-title">วัตถุดิบ:</h3>
            <ol class="card-text">
                @foreach ($post->ingrediant as $step)
                    <li>{{ $step }}</li>
                @endforeach
            </ol>
        </div>

        <div class="card mb-3 shadow">
            @if ($post->youtube_link)
                @php
                    $video_id = ''; //create $video_id for if condition
                    // Extract the video link from standard YouTube link
                    if (preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $post->youtube_link, $matches)) {
                        $video_id = $matches[1];
                    }

                    // Extract the video link from shortened YouTube link
                    if (!$video_id && preg_match('/youtu\\.be\\/([^\\?\\&]+)/', $post->youtube_link, $matches)) {
                        $video_id = $matches[1];
                    }
                @endphp
                @if ($video_id)
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item card-img-top"
                            src="https://www.youtube.com/embed/{{ $video_id }}" allowfullscreen width="100%"
                            height="400px"></iframe>
                    </div>
                @endif
            @endif

            <div class="card-body">
                <h3 class="card-title">วิธีทำ:</h3>
                <ol class="card-text">
                    @foreach ($post->htc as $step)
                        <p>..{{ $step }}<br></p>
                    @endforeach
                </ol>
            </div>
        </div>

    </div>
</body>

</html>
