@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-3">
            @include('shared.left-sidebar')
        </div>
        <div class="col-6">
            @include('shared.alert-message')
            <div class="mt-3">
                @include('shared.user-card')
            </div>
            <hr>

            <div class="content">
                <h1>Posts</h1>

                <div id="post-container">
                    @foreach ($posts as $item)
                        <div class="post-frame card mb-4 shadow bg-secondary-subtle" onclick="goToPost({{ $item->id }})">
                            <img class="card-img-top" src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}"
                                style="max-height: 800px;">
                            <div class="card-body">
                                <h2 class="card-title">{{ $item->title }}</h2>
                                <p class="card-text">{{ Str::limit($item->description, 50) }}</p>
                                <h3 class="card-text">วัตถุดิบ:</h3>
                                <p class="card-text">
                                    {{ Str::limit(is_array($item->ingrediant) ? implode(', ', $item->ingrediant) : $item->ingrediant, 50) }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($posts->hasMorePages())
                    <div class="d-flex justify-content-center">
                        <button id="load-more" class="btn btn-danger" data-page="{{ $posts->currentPage() + 1 }}"
                            data-user="{{ $user->id }}">
                            Load More
                        </button>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-3">
            @include('shared.search-bar')
            @include('shared.follow-box')
        </div>
    </div>

    <script>
        // Load more script
        document.addEventListener('DOMContentLoaded', function() {
            const loadMoreButton = document.getElementById('load-more');
            if (loadMoreButton) {
                loadMoreButton.addEventListener('click', function() {
                    const page = loadMoreButton.getAttribute('data-page');
                    const userId = loadMoreButton.getAttribute('data-user');
                    fetch(`/users/${userId}/posts?page=${page}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            const postContainer = document.getElementById('post-container');
                            data.data.forEach(post => {
                                const postFrame = document.createElement('div');
                                postFrame.classList.add('post-frame', 'card', 'mb-4', 'shadow',
                                    'bg-secondary-subtle');
                                postFrame.setAttribute('onclick', `goToPost(${post.id})`);

                                postFrame.innerHTML = `
                                <img class="card-img-top" src="/storage/${post.image}" alt="${post.title}" style="max-height: 800px;">
                                <div class="card-body">
                                    <h2 class="card-title">${post.title}</h2>
                                    <p class="card-text">${post.description.substring(0, 50)}...</p>
                                    <h3 class="card-text">วัตถุดิบ:</h3>
                                    <p class="card-text">${Array.isArray(post.ingrediant) ? post.ingrediant.join(', ').substring(0, 50) : post.ingrediant.substring(0, 50)}...</p>
                                </div>
                            `;
                                postContainer.appendChild(postFrame);
                            });
                            if (data.current_page < data.last_page) {
                                loadMoreButton.setAttribute('data-page', data.current_page + 1);
                            } else {
                                loadMoreButton.remove();
                            }
                        });
                });
            }
        });

        // go to post script
        function goToPost(id) {
            window.location.href = `/post/${id}`;
        }
    </script>
@endsection
