@extends('layouts.app')

@section('content')
    <style>
        .back-button {
            display: flex;
            width: 50px;
            height: 38px;
        }
    </style>

    <div class="container">
        <div class="row">
            <!-- left sidebar (menu) -->
            <div class="col-2">
                <div class="fixed-bottom m-2">
                    <!-- (menu) -->
                    <div class="btn-group dropup mb-2" role="group">
                        <button type="button" class="btn btn-outline-danger rounded" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="16" fill="currentColor"
                                class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                <path
                                    d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
                            </svg>
                        </button>
                        <ul class="dropdown-menu">
                            @if (Auth::check() && Auth::user()->id == $post->user_id)
                                <!-- edit -->
                                <li><a class="dropdown-item" href="{{ route('post.edit', $post->id) }}">แก้ไขสูตรอาหาร</a>
                                </li>
                                <!-- delete -->
                                <li>
                                    <form id="deleteForm" action="{{ route('post.destroy', $post->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                            data-bs-target="#deleteConfirmationModal">ลบสูตรอาหาร</button>
                                    </form>
                                </li>
                            @else
                                <li><a class="dropdown-item" href="#">รายงานสูตรอาหารนี้</a></li>
                            @endif
                        </ul>
                    </div>
                    <!-- (back) -->
                    <div>
                        <button class="back-button btn btn-danger" onclick="window.history.back();">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-3">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- content area -->
            <div class="col-8">
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

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
                            if (
                                !$video_id &&
                                preg_match('/youtu\\.be\\/([^\\?\\&]+)/', $post->youtube_link, $matches)
                            ) {
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
                                    <p>..{{ $step }}</p>
                                @endforeach
                            @else
                                <li>ข้อมูลวิธีทำไม่ถูกต้อง</li>
                            @endif
                        </ol>
                    </div>
                </div>


                <div class="mb-3">
                    @include('shared.user-card', ['user' => $post->user])
                </div>

                <div class="comments-box">
                    @include('shared.comments-box', ['post' => $post])
                </div>
            </div>
            <!-- right sidebar (user)
                <div class="col-3 post-user border">
                    {{-- @include('shared.user-card', ['user' => $post->user]) --}}
                </div>
                -->
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Delete Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    คุณจะลบสูตรนี้จริงๆ ใช่หรือไม่?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteButton">Yes</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        //go to user function
        function goToUser(userId) {
            window.location.href = `/users/${userId}`;
        };

        //delete post confirmation 
        document.addEventListener('DOMContentLoaded', function() {
            const confirmDeleteButton = document.getElementById('confirmDeleteButton');
            confirmDeleteButton.addEventListener('click', function() {
                document.getElementById('deleteForm').submit();
            });
        });
    </script>
@endsection
