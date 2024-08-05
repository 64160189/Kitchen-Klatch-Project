@extends('layouts.app')

@section('content')
    <style>
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
            <div class="col-3 left-sidebar bg-danger">
                <div class="sticky-top" style="top: 15%;">
                    <h2>Search from ingredients (Position: Fixed)</h2>
                </div>
            </div>

            <div class="col-9 main-content">
                <div class="content-area">
                    @include('shared.alert-message')

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
                                    {{ Str::limit(is_array($item->ingrediant) ? implode(', ', $item->ingrediant) : $item->ingrediant, 50) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if ($posts->hasMorePages())
                        <div id="load-more-trigger" class="d-flex justify-content-center">
                            <!-- This will be the trigger for loading more posts -->
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        // Load more script
        document.addEventListener('DOMContentLoaded', function() {
            const loadMoreTrigger = document.getElementById('load-more-trigger');
            let currentPage = {{ $posts->currentPage() + 1 }};
            const lastPage = {{ $posts->lastPage() }};

            if (loadMoreTrigger) {
                const observer = new IntersectionObserver(entries => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting && currentPage <= lastPage) {
                            loadMorePosts();
                        }
                    });
                }, {
                    root: null, // Use the viewport as the container
                    rootMargin: '0px',
                    threshold: 1.0 // Trigger when 100% of the target is visible
                });

                observer.observe(loadMoreTrigger);
            }

            function loadMorePosts() {
                fetch(`/posts?page=${currentPage}`)
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
                        currentPage++;
                        if (currentPage > lastPage) {
                            loadMoreTrigger.remove(); // Remove the trigger if no more pages
                        }
                    });
            }
        });

        //go to post script
        function goToPost(id) {
            window.location.href = `/post/${id}`;
        }
    </script>
@endsection
