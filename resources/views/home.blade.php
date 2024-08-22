@extends('layouts.app')

@section('show_ingredients_bar', true)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-3 left-sidebar">
                <div class="fridge sticky-top border bg-light">
                    <h2>รายการวัตถุดิบของคุณ:</h2>
                    <table class="igd-input">
                        <tr>
                            <th width="100%" style="position: relative;">
                                <input type="text" id="ingredient-input" class="form-control"
                                    placeholder="เพิ่มวัตถุดิบของคุณ...">
                                <ul id="ingredients-suggestions" class="list-group position-absolute w-100"
                                    style="top: 100%; z-index: 1000;"></ul>
                            </th>
                            <th>
                                <button id="add-ingredient" class="btn btn-danger">+</button>
                            </th>
                        </tr>
                    </table>
                    @php
                        $ingredients = session('ingredients', []);
                    @endphp

                    <ul id="ingredient-list" class="list-unstyled mt-2">
                        @foreach ($ingredients as $ingredient)
                            <li class="ingredient-item">
                                {{ $ingredient }}
                                <button data-ingredient="{{ $ingredient }}">&times;</button>
                            </li>
                        @endforeach
                    </ul>
                    <button id="search-recipes" class="btn btn-danger w-100 mt-2">Search from this list</button>
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
                                    alt="{{ $item->title }}" style="max-height: 800px;">
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
                        <div class="d-flex justify-content-center">
                            <button id="load-more" class="btn btn-danger" data-page="{{ $posts->currentPage() + 1 }}">
                                Load More
                            </button>
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



        // go to post function
        function goToPost(id) {
            window.location.href = `/post/${id}`;
        }
    </script>
@endsection
