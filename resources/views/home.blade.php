@extends('layouts.app')

@section('content')
    <style>
        .left-sidebar {
            position: fixed;
            bottom: 0px;
            top: 100px;
        }

        .main-content {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .content-area {
            width: 70%;
            min-width: 300px;
        }

        .post-frame:hover {
            cursor: pointer;
        }
    </style>

    <div class="container-fluid">
        <div class="row">
            <div class="col-3 left-sidebar bg-danger"
                style="bottom: 0; display: flex; flex-direction: column; align-items: center;">
                <h2>Search from ingredients (Position: Fixed)</h2>
            </div>

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
                                    <p class="card-text">{{ Str::limit(implode(', ', $item->ingrediant), 50) }}</p>
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
    </div>

    <script>
        // Load more script
        document.addEventListener('DOMContentLoaded', function() {
            const loadMoreButton = document.getElementById('load-more');
            if (loadMoreButton) {
                loadMoreButton.addEventListener('click', function() {
                    const page = loadMoreButton.getAttribute('data-page');
                    fetch(`/posts?page=${page}`)
                        .then(response => response.json())
                        .then(data => {
                            const postContainer = document.getElementById('post-container');
                            data.data.forEach(post => {
                                const postFrame = document.createElement('div');
                                postFrame.classList.add('post-frame', 'card', 'mb-4', 'shadow',
                                    'bg-secondary-subtle');

                                postFrame.setAttribute('onclick',
                                    `goToPost(${post.id})`); // Onclick function

                                postFrame.innerHTML = `
                                <img class="card-img-top" src="/storage/${post.image}" alt="${post.title}" style="width:100%; height:auto;">
                                <div class="card-body">
                                    <h2 class="card-title">${post.title}</h2>
                                    <p class="card-text">${post.description.substring(0, 50)}...</p>
                                    <h3 class="card-text">วัตถุดิบ:</h3>
                                    <p class="card-text">${post.ingrediant.join(', ').substring(0, 50)}...</p>
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

        function goToPost(id) {
            window.location.href = `/post/${id}`;
        }
    </script>
@endsection
