@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Search Results for "{{ $search }}"</h2>
        @if ($posts->isEmpty())
            <p>No posts found.</p>
        @else
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
                        {{ Str::limit(is_array($item->ingrediant) ? implode(', ', $item->ingrediant) : $item->ingrediant, 50) }}
                    </div>
                </div>
            @endforeach
        </div>
        @endif
    </div>
@endsection
