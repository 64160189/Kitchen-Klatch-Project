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

                <div class="btn-group mb-3" role="group" aria-label="Post View Type">
                    <button type="button" class="btn btn-primary" id="my-posts" title="แสดงโพสต์ของฉัน">โพสต์ของฉัน</button>
                    <button type="button" class="btn btn-secondary" id="shared-posts"
                        title="แสดงโพสต์ที่แชร์">โพสต์ที่แชร์</button>
                </div>

                <div id="post-container">
                    @foreach ($posts as $item)
                        <div class="post-frame card mb-4 shadow bg-secondary-subtle"
                            onclick="goToPost({{ $item->id }})">
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
                            data-user="{{ $user->id }}" title="โหลดโพสต์เพิ่มเติม">
                            โหลดเพิ่มเติม
                        </button>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-3">
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loadMoreButton = document.getElementById('load-more');
            const myPostsButton = document.getElementById('my-posts');
            const sharedPostsButton = document.getElementById('shared-posts');
            const postContainer = document.getElementById('post-container');

            let currentPostType = 'my'; // Initial state to show user posts
            let currentPage = 1;

            function loadPosts() {
                const userId = loadMoreButton ? loadMoreButton.getAttribute('data-user') : '';
                const page = currentPage;
                const type = currentPostType;

                fetch(`/users/${userId}/posts?page=${page}&type=${type}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok.');
                        return response.json();
                    })
                    .then(data => {
                        if (!data || !data.data) {
                            console.error('No data received or incorrect data format.');
                            return;
                        }

                        if (currentPage === 1) postContainer.innerHTML =
                        ''; // Clear old posts if it's the first page

                        data.data.forEach(post => {
                            const postFrame = document.createElement('div');
                            postFrame.classList.add('post-frame', 'card', 'mb-4', 'shadow',
                                'bg-secondary-subtle');
                            postFrame.setAttribute('onclick', `goToPost(${post.id})`);

                            postFrame.innerHTML = `
                    <img class="card-img-top" src="/storage/${post.image}" alt="${post.title}" style="max-height: 800px;">
                    <div class="card-body">
                        <h2 class="card-title">${post.title}</h2>
                        <p class="card-text">${post.description ? post.description.substring(0, 50) + '...' : ''}</p>
                        <h3 class="card-text">วัตถุดิบ:</h3>
                        <p class="card-text">${Array.isArray(post.ingrediant) ? post.ingrediant.join(', ').substring(0, 50) + '...' : post.ingrediant ? post.ingrediant.substring(0, 50) + '...' : ''}</p>
                    </div>
                `;
                            postContainer.appendChild(postFrame);
                        });

                        if (data.current_page < data.last_page) {
                            currentPage++;
                            if (loadMoreButton) {
                                loadMoreButton.setAttribute('data-page', currentPage);
                            }
                        } else if (loadMoreButton) {
                            loadMoreButton.remove(); // Remove the load more button if no more pages
                        }
                    })
                    .catch(error => {
                        console.error('Error loading posts:', error);
                    });
            }

            loadPosts();

            if (loadMoreButton) {
                loadMoreButton.addEventListener('click', loadPosts);
            }

            if (myPostsButton) {
                myPostsButton.addEventListener('click', function() {
                    currentPostType = 'my';
                    currentPage = 1;
                    postContainer.innerHTML = ''; // Clear old posts
                    loadPosts(); // Load new posts
                });
            }

            if (sharedPostsButton) {
                sharedPostsButton.addEventListener('click', function() {
                    currentPostType = 'shared';
                    currentPage = 1;
                    postContainer.innerHTML = ''; // Clear old posts
                    loadPosts(); // Load new posts
                });
            }
        });

        // go to post script
        function goToPost(id) {
            window.location.href = `/post/${id}`;
        }
    </script>
@endsection
