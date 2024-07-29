@extends('layouts.app')

@section('content')
    <div class="container">
        <button class="back-button btn btn-outline-danger" onclick="window.history.back();"
            style="display: flex; margin-bottom: 20px; width: 90px; height: 38px;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Back
        </button>

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
                @if (is_array($post->ingrediant))
                    @foreach ($post->ingrediant as $ingredient)
                        <li>{{ $ingredient }}</li>
                    @endforeach
                @else
                    <li>ข้อมูลวัตถุดิบไม่ถูกต้อง</li>
                @endif
            </ol>
        </div>

        <div class="card mb-3 shadow">
            @if ($post->youtube_link)
                @php
                    $video_id = '';
                    // ดึง video_id จากลิงก์ YouTube แบบปกติ
                    if (preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $post->youtube_link, $matches)) {
                        $video_id = $matches[1];
                    }

                    // ดึง video_id จากลิงก์ YouTube แบบสั้น
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
                    @if (is_array($post->htc))
                        @foreach ($post->htc as $step)
                            <li>{{ $step }}</li>
                        @endforeach
                    @else
                        <li>ข้อมูลวิธีทำไม่ถูกต้อง</li>
                    @endif
                </ol>
            </div>
        </div>
    </div>
@endsection
