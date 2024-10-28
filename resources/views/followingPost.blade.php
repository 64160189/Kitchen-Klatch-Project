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
                    <button id="random-recipe" class="btn btn-warning w-100 mt-2">Random 1 Recipe from This List</button>
                </div>
            </div>

            <div class="col-9 main-content">
                <div class="content-area">
                    @include('shared.alert-message')

                    <h3>โพสต์ของผู้ที่คุณติดตาม</h3>

                    <div id="post-container">
                        @foreach ($posts as $item)
                            <div class="post-frame card mb-4 bg-secondary-subtle"
                                onclick="goToPost({{ $item->id }})">
                                <img class="card-img-top" src="{{ asset('storage/' . $item->image) }}"
                                    alt="{{ $item->title }}" style="max-height: 800px;">
                                <div class="card-body">

                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-clock" viewBox="0 0 16 16">
                                        <path
                                            d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z" />
                                        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0" />
                                    </svg>
                                    @if ($item->time_to_cook)
                                        <span class="text-muted">{{ $item->time_to_cook }} นาที | </span>
                                    @else
                                        <span class="text-muted">-- | </span>
                                    @endif

                                    @if ($item->level_of_cook == 1)
                                        <span class="card-text text-muted">ง่ายมาก</span>
                                    @elseif ($item->level_of_cook == 2)
                                        <span class="card-text text-muted">ค่อนข้างง่าย</span>
                                    @elseif ($item->level_of_cook == 3)
                                        <span class="card-text text-muted">ปานกลาง</span>
                                    @elseif ($item->level_of_cook == 4)
                                        <span class="card-text text-muted">ค่อนข้างยาก</span>
                                    @else
                                        <span class="card-text text-muted">ยาก</span>
                                    @endif

                                    <h3 class="card-title">{{ Str::limit($item->title, 50) }}</h3>
                                    <span class="card-text">{{ Str::limit($item->description, 50) }}</span><br>
                                    <span class="card-text">วัตถุดิบ: <span
                                            class="text-muted">{{ Str::limit(is_array($item->ingrediant) ? implode(', ', $item->ingrediant) : $item->ingrediant, 50) }}</span></span><br>

                                    {{-- user's data --}}
                                    <img class="avatar-sm rounded-circle border border-dark mt-1"
                                        src="{{ $item->user->getImageURL() }}" style="width: 5%;">
                                    <a href="{{ route('users.show', ['user' => $item->user->id]) }}"
                                        class="fw-semibold text-decoration-none text-danger">{{ Str::limit($item->user->name, 20) }}
                                        <span class="text-muted">#{{ $item->user->id }}</span></a>
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

    {{-- loading animation --}}
    @include('shared.loading')

    <script>
        // Load more script
        document.addEventListener('DOMContentLoaded', function() {
            const loadMoreButton = document.getElementById('load-more');
            if (loadMoreButton) {
                loadMoreButton.addEventListener('click', function() {
                    const page = loadMoreButton.getAttribute('data-page');
                    fetch(`/following/posts?page=${page}`)
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
                                        
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock" viewBox="0 0 16 16">
                                            <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z"></path>
                                            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0"></path>
                                        </svg>
                                        ${post.time_to_cook ? `<span>${post.time_to_cook} นาที | </span>` : `<span>-- | </span>`} 
                                        
                                        ${post.level_of_cook == 1 ? `<span class="card-text text-muted">ง่ายมาก</span>` :
                                         post.level_of_cook == 2 ? `<span class="card-text text-muted">ค่อนข้างง่าย</span>` :
                                         post.level_of_cook == 3 ? `<span class="card-text text-muted">ปานกลาง</span>` :
                                         post.level_of_cook == 4 ? `<span class="card-text text-muted">ค่อนข้างยาก</span>` :
                                          '<span class="card-text text-muted">ยาก</span>'} 

                                        <h3 class="card-title">${post.title}</h3>
        
                                        <span class="card-text">${post.description ? post.description.substring(0, 50) + '...' : ''}</span><br>
                                        
                                        <span class="card-text">วัตถุดิบ: <span class="text-muted">${Array.isArray(post.ingrediant) ? post.ingrediant.join(', ').substring(0, 50) + '...' : post.ingrediant ? post.ingrediant.substring(0, 50) + '...' : ''}</span></span>

                                        <div class="d-flex align-items-center mt-2">
                                            <img class="avatar-sm rounded-circle border border-dark"
                                                 src="${post.user && post.user.image_url ? post.user.getImageURL() : '/default-avatar.png'}" style="width: 5%;">
                                            <a href="/users/${post.user ? post.user.id : ''}" class="fw-semibold text-decoration-none text-danger ms-2">
                                            ${post.user ? post.user.name.substring(0, 20) : 'Unknown'}
                                                <span class="text-muted">#${post.user ? post.user.id : '--'}</span>
                                            </a>
                                        </div>
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
