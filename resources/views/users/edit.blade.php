@extends('layouts.app')

@section('content')
    <style>
        .post-frame:hover {
            cursor: pointer;
        }
    </style>

    <div class="row">
        <div class="col-3">
            @include('shared.left-sidebar')
        </div>
        <div class="col-6">
            @include('shared.success-message')
            <div class="mt-3">
                @include('shared.user-edit-card')
            </div>
            <hr>

            <div class="col-3"></div>

            <div class="col main-content">
                <div class="content-area">
                    <h1>Posts</h1>

                    <div id="post-container">
                        @foreach ($posts as $item)
                            <div class="post-frame card mb-4 shadow bg-secondary-subtle"
                                onclick="goToPost({{ $item->id }})">
                                <img class="card-img-top" src="{{ asset('storage/' . $item->image) }}"
                                    alt="{{ $item->title }}" style="width:100%; height:auto;">
                                <div class="card-body">
                                    <h2 class="card-title">{{ $item->title }}</h2>
                                    <p class="card-text">{{ Str::limit($item->description, 50) }}</p>
                                    <h3 class="card-text">วัตถุดิบ:</h3>
                                    <p class="card-text">
                                        @if (is_array($item->ingrediant))
                                            {{ implode(', ', $item->ingrediant) }}
                                        @else
                                            {{ $item->ingrediant }} <!-- ถ้าข้อมูลเป็นสตริงอยู่แล้ว -->
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if ($posts->hasMorePages())
                        <div class="d-flex justify-content-center">
                            <button id="load-more" class="btn btn-danger" data-page="{{ $posts->currentPage() + 1 }}">Load
                                More</button>
                        </div>
                    @endif
                </div>
            </div>

        </div>
        <div class="col-3">
            @include('shared.search-bar')
            @include('shared.follow-box')
        </div>
    </div>
@endsection
