@extends('layouts.app')

@section('content')
    <div class="container">

        {{-- CONTENT --}}
        <div class="col row justify-content-center">
            <div class="col">
                <div class="row justify-content-center">
                    {{-- HEAD --}}
                    <div class="row col-9">
                        <div class="col-8 mb-3">
                            <h4 class="fw-bold">บัญชีที่ติดตาม</h4>
                            {{-- SEARCH --}}
                            <form class="d-flex mb-1 mt-1 position-relative" role="search" method="get"
                                action="{{ route('search.following') }}">
                                <input class="form-control me-2" id="user-search-input" type="search" name="search"
                                    placeholder="ค้นหาชื่อหรือไอดีผู้ใช้(ไม่ต้องใส่ #)" aria-label="Search"
                                    autocomplete="off" value="{{ $search ?? '' }}">
                                <button class="btn btn-danger" type="submit">ค้นหา</button>
                                <ul id="user-suggestions" class="list-group position-absolute w-100"
                                    style="top: 100%; z-index: 1000;"></ul>
                            </form>
                        </div>

                        <div class="col mb-3"> {{-- SORT BY DROPDOWN --}}
                            <label for="sort-by" class="form-label fw-semibold">จัดเรียงตาม :</label>
                            <select id="sort-by" class="form-select" onchange="sortPosts()">
                                <option value="id_desc"
                                    {{ request('sort') === 'id' && request('order') === 'desc' ? 'selected' : '' }}>เวลา
                                    (ใหม่ที่สุด - เก่าที่สุด)</option>
                                <option value="id_asc"
                                    {{ request('sort') === 'id' && request('order') === 'asc' ? 'selected' : '' }}>เวลา
                                    (เก่าที่สุด - ใหม่ที่สุด)</option>
                                <option value="name_asc"
                                    {{ request('sort') === 'name' && request('order') === 'asc' ? 'selected' : '' }}>
                                    ชื่อ
                                    (A-Z)</option>
                                <option value="name_desc"
                                    {{ request('sort') === 'name' && request('order') === 'desc' ? 'selected' : '' }}>
                                    ชื่อ
                                    (Z-A)</option>
                            </select>
                        </div>
                    </div>

                    {{-- DATA --}}
                    <div class="card col-9 mt-2 shadow">
                        @foreach ($followingUsers as $user)
                            <div class="card-body row">
                                <div class="rounded border col bg-white">
                                    <div class="d-flex align-items-center">
                                        <img class="m-2 avatar-sm rounded-circle border border-dark"
                                            src="{{ $user->getImageURL() }}" alt="{{ $user->name }}" style="width: 15%;">
                                        {{-- USER DATA --}}
                                        <div class="ms-1">
                                            <h3 class="mb-0 text-dark">
                                                <a href="{{ route('users.show', ['user' => $user->id]) }}"
                                                    class="text-decoration-none text-primary">{{ $user->name }}</a>
                                            </h3>
                                            <span class="fs-6 text-muted">#{{ $user->id }}</span></br></br>
                                            <!-- Statistics Section -->
                                            <div class="d-flex justify-content-start">
                                                <a class="fw-light nav-link fs-6 me-3 text-muted">
                                                    {{ $user->followers()->count() }} ผู้ติดตาม
                                                </a>
                                                <a class="fw-light nav-link fs-6 me-3 text-muted">
                                                    {{ $user->followings()->count() }} การติดตาม
                                                </a>
                                                <a class="fw-light nav-link fs-6 text-muted">
                                                    {{ $user->posts()->count() }} โพสต์
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="ms-1 col-1 btn btn-warning"
                                style="display: flex; justify-content: center; align-items: center;">แบน</div> --}}

                                @if (Auth::user()->follows($user))
                                    <!-- Unfollow button -->
                                    <button type="button"
                                        class="ms-1 col-1 btn btn-danger d-flex justify-content-center align-items-center"
                                        onclick="document.getElementById('unfollowForm-{{ $user->id }}').submit()"
                                        data-user-id="{{ $user->id }}">
                                        Unfollow
                                    </button>

                                    <!-- Hidden Unfollow Form -->
                                    <form id="unfollowForm-{{ $user->id }}"
                                        action="{{ route('users.unfollow', $user->id) }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    {{-- PAGE --}}
                    <div class="mt-4 d-flex justify-content-center">

                        @if ($followingUsers->lastPage() > 1)
                            <div>
                                {{-- Always show the first page --}}
                                <a href="{{ $followingUsers->appends(['sort' => request('sort'), 'order' => request('order')])->url(1) }}"
                                    class="btn btn-outline-danger m-1 {{ $followingUsers->currentPage() == 1 ? 'active' : '' }}">1</a>

                                {{-- Show "..." after the first page if the current page is more than 4 --}}
                                @if ($followingUsers->currentPage() > 5)
                                    <span class="m-1">...</span>
                                @endif

                                {{-- Show pages around the current page (±3) --}}
                                @for ($i = max(2, $followingUsers->currentPage() - 3); $i <= min($followingUsers->lastPage() - 1, $followingUsers->currentPage() + 3); $i++)
                                    <a href="{{ $followingUsers->appends(['sort' => request('sort'), 'order' => request('order')])->url($i) }}"
                                        class="btn btn-outline-danger m-1 {{ $followingUsers->currentPage() == $i ? 'active' : '' }}">
                                        {{ $i }}
                                    </a>
                                @endfor

                                {{-- Show "..." before the last page if the current page is less than total pages minus 3 --}}
                                @if ($followingUsers->currentPage() < $followingUsers->lastPage() - 4)
                                    <span class="m-1">...</span>
                                @endif

                                {{-- Always show the last page --}}
                                <a href="{{ $followingUsers->appends(['sort' => request('sort'), 'order' => request('order')])->url($followingUsers->lastPage()) }}"
                                    class="btn btn-outline-danger m-1 {{ $followingUsers->currentPage() == $followingUsers->lastPage() ? 'active' : '' }}">
                                    {{ $followingUsers->lastPage() }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        // Sort posts script
        function sortPosts() {
            const sortBy = document.getElementById('sort-by').value.split('_');
            const sort = sortBy[0];
            const order = sortBy[1];
            const search = "{{ $search ?? '' }}";
            window.location.href = `/following/users/search?search=${search}&sort=${sort}&order=${order}`;
        }

        // Predictive search script for user search in the admin panel
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('user-search-input');
            const suggestionsBox = document.getElementById('user-suggestions');

            searchInput.addEventListener('input', function() {
                const query = searchInput.value.trim();

                if (query.length > 0) {
                    fetch(`/following/users/search/predictions?search=${query}`)
                        .then(response => response.json())
                        .then(data => {
                            suggestionsBox.innerHTML = '';
                            if (data.length > 0) {
                                data.forEach(user => {
                                    const suggestionItem = document.createElement('li');
                                    suggestionItem.classList.add('list-group-item',
                                        'list-group-item-action');
                                    suggestionItem.textContent =
                                        `${user.id} - ${user.name}`; // Display ID and name
                                    suggestionItem.addEventListener('click', function() {
                                        searchInput.value = user
                                            .name; // Fill the input with the selected user's name
                                        suggestionsBox.innerHTML = '';
                                    });
                                    suggestionsBox.appendChild(suggestionItem);
                                });
                            } else {
                                const noResultsItem = document.createElement('li');
                                noResultsItem.classList.add('list-group-item');
                                noResultsItem.textContent = 'No results found';
                                suggestionsBox.appendChild(noResultsItem);
                            }
                        });
                } else {
                    suggestionsBox.innerHTML = '';
                }
            });

            // Hide suggestions when clicking outside
            document.addEventListener('click', function(event) {
                if (!suggestionsBox.contains(event.target) && event.target !== searchInput) {
                    suggestionsBox.innerHTML = '';
                }
            });
        });
    </script>
@endsection
