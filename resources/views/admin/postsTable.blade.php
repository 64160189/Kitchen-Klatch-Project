@extends('layouts.app')

@section('content')
    <!-- Link to the CSS file -->
    <script>
        mix.css('resources/css/app.css', 'public/css');
    </script>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <div class="container">
        <div class="row">
            {{-- SIDEBAR --}}
            <div class="sidebar col-2">
                {{-- Appear when window width <= 300 --}}
                <nav class="navbar fixed-bottom ms-2">
                    <button class="navbar-toggler bg-warning" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar"
                        aria-labelledby="offcanvasNavbarLabel">
                        <div class="offcanvas-header">
                            <h5 class="offcanvas-title fw-bold" id="offcanvasNavbarLabel">Admin Sidebar</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body">
                            <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                                <li class="nav-item">
                                    <a class="nav-link active fw-bold" aria-current="page">หน้าหลัก</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/admin/home">แดชขอร์ด</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active fw-bold mt-4" aria-current="page">ตารางข้อมูล</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/admin/table/user">บัญชีผู้ใช้ทั้งหมด</a>
                                    <a class="nav-link" href="/admin/table/post">โพสต์ทั้งหมด</a>
                                    <a class="nav-link" href="{{ route('admin.viewReportedPosts') }}">โพสต์ที่ถูกรายงานทั้งหมด</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>

                {{-- Appear when window width > 300 --}}
                <div class="sidebar-normal card">
                    <h5 class="fw-bold card-header" aria-current="page">หน้าหลัก</h5>
                    <div class="card-body">
                        <a class="nav-link" href="/admin/home">แดชขอร์ด</a>
                    </div>
                    <h5 class="fw-bold card-header" aria-current="page">ตารางข้อมูล</h5>
                    <div class="card-body">
                        <a class="nav-link" href="/admin/table/user">บัญชีผู้ใช้ทั้งหมด</a>
                        <a class="nav-link mt-2" href="/admin/table/post">โพสต์ทั้งหมด</a>
                        <a class="nav-link mt-2" href="{{ route('admin.viewReportedPosts') }}">โพสต์ที่ถูกรายงานทั้งหมด</a>
                    </div>
                </div>
            </div>

            {{-- CONTENT --}}
            <div class="col row justify-content-center">
                <div class="col">
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
                    <div class="row justify-content-center">
                        {{-- HEAD --}}
                        <div class="row col-9">
                            <div class="col-8 mb-3">
                                <h4 class="fw-bold">โพสต์ทั้งหมด {{ $AllPosts }} โพสต์</h4>
                                {{-- SEARCH --}}
                                <form class="d-flex mb-1 mt-1 position-relative" role="search" method="get"
                                    action="{{ route('search.post') }}">
                                    <input class="form-control me-2" id="post-search-input" type="search" name="search"
                                        placeholder="ค้นหาชื่อหรือไอดีโพสต์" aria-label="Search" autocomplete="off"
                                        value="{{ $search ?? '' }}">
                                    <button class="btn btn-danger" type="submit">ค้นหา</button>
                                    <ul id="post-suggestions" class="list-group position-absolute w-100"
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
                                    <option value="title_asc"
                                        {{ request('sort') === 'title' && request('order') === 'asc' ? 'selected' : '' }}>
                                        ชื่อ
                                        (A-Z)</option>
                                    <option value="title_desc"
                                        {{ request('sort') === 'title' && request('order') === 'desc' ? 'selected' : '' }}>
                                        ชื่อ
                                        (Z-A)</option>
                                </select>
                            </div>
                        </div>

                        {{-- DATA --}}
                        <div class="card col-9 mt-2">
                            @foreach ($posts as $post)
                                <div class="card-body row">
                                    <div class="rounded border col bg-white d-flex align-items-center">
                                        <div class="d-flex align-items-center">
                                            <span class="fw-bold col-1"
                                                style="max-width: 40px;">{{ Str::limit($post->id, 10) }}</span>
                                            <img class="ms-2" src="{{ asset('storage/' . $post->image) }}"
                                                alt="{{ $post->title }}" style="width: 25%; max-height: 500px;">
                                            <div class="m-2">
                                                <a href="{{ route('post.show', ['id' => $post->id]) }}"
                                                    class="fs-5 fw-semibold text-decoration-none text-black">{{ Str::limit($post->title, 30) }}</a></br>
                                                <span
                                                    class="text-muted">{{ Str::limit($post->description, 30) }}</span></br>
                                                <span>วัตถุดิบ:
                                                    <span
                                                        class="text-muted">{{ Str::limit(is_array($post->ingrediant) ? implode(', ', $post->ingrediant) : $post->ingrediant, 30) }}</span>
                                                </span></br>

                                                {{-- user's data --}}
                                                <img class="avatar-sm rounded-circle border border-dark mt-1"
                                                    src="{{ $post->user->getImageURL() }}" alt="{{ $post->user->name }}"
                                                    style="width: 10%;">
                                                <a href="{{ route('users.show', ['user' => $post->user->id]) }}"
                                                    class="fw-semibold text-decoration-none text-danger">{{ $post->user->name }}
                                                    <span class="text-muted">#{{ $post->user->id }}</span></a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete Form in Post -->
                                    <button type="button"
                                        class="ms-1 col-1 btn btn-danger d-flex justify-content-center align-items-center"
                                        data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal"
                                        data-post-id="{{ $post->id }}">
                                        ลบ
                                    </button>
                                    <!-- Delete Form with Unique ID -->
                                    <form id="deleteForm-{{ $post->id }}"
                                        action="{{ route('post.destroy', $post->id) }}" method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            @endforeach
                        </div>

                        {{-- PAGE --}}
                        <div class="mt-4 d-flex justify-content-center">

                            @if ($posts->lastPage() > 1)
                                <div>
                                    {{-- Always show the first page --}}
                                    <a href="{{ $posts->appends(['sort' => request('sort'), 'order' => request('order')])->url(1) }}"
                                        class="btn btn-outline-danger m-1 {{ $posts->currentPage() == 1 ? 'active' : '' }}">1</a>

                                    {{-- Show "..." after the first page if the current page is more than 4 --}}
                                    @if ($posts->currentPage() > 5)
                                        <span class="m-1">...</span>
                                    @endif

                                    {{-- Show pages around the current page (±3) --}}
                                    @for ($i = max(2, $posts->currentPage() - 3); $i <= min($posts->lastPage() - 1, $posts->currentPage() + 3); $i++)
                                        <a href="{{ $posts->appends(['sort' => request('sort'), 'order' => request('order')])->url($i) }}"
                                            class="btn btn-outline-danger m-1 {{ $posts->currentPage() == $i ? 'active' : '' }}">
                                            {{ $i }}
                                        </a>
                                    @endfor

                                    {{-- Show "..." before the last page if the current page is less than total pages minus 3 --}}
                                    @if ($posts->currentPage() < $posts->lastPage() - 4)
                                        <span class="m-1">...</span>
                                    @endif

                                    {{-- Always show the last page --}}
                                    <a href="{{ $posts->appends(['sort' => request('sort'), 'order' => request('order')])->url($posts->lastPage()) }}"
                                        class="btn btn-outline-danger m-1 {{ $posts->currentPage() == $posts->lastPage() ? 'active' : '' }}">
                                        {{ $posts->lastPage() }}
                                    </a>
                                </div>
                            @endif

                        </div>
                    </div>

                </div>
            </div>
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
                    <textarea id="deleteReason" class="form-control mt-3" placeholder="ระบุเหตุผลการลบ" rows="3"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteButton">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        //delete post confirmation
        document.addEventListener('DOMContentLoaded', function() {
            const confirmDeleteButton = document.getElementById('confirmDeleteButton');
            let deleteForm;
            let postId;

            // Set the post ID when delete button is clicked
            document.querySelectorAll('[data-bs-toggle="modal"]').forEach(function(button) {
                button.addEventListener('click', function() {
                    postId = button.getAttribute('data-post-id');
                });
            });

            confirmDeleteButton.addEventListener('click', function() {
                const deleteReason = document.getElementById('deleteReason').value;
                if (deleteReason) {
                    console.log(deleteReason);
                    
                    // Submit the deletion request with reason
                    fetch(`/delete_post/${postId}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            },
                            body: JSON.stringify({
                                reason: deleteReason
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Post deleted successfully, reload or update UI
                                location.reload();
                            }
                        });
                } else {
                    alert('กรุณาใส่เหตุผลก่อนลบ');
                }
            });
        });

        // Sort posts script
        function sortPosts() {
            const sortBy = document.getElementById('sort-by').value.split('_');
            const sort = sortBy[0];
            const order = sortBy[1];
            const search = "{{ $search ?? '' }}";
            window.location.href = `/admin/table/post/search?search=${search}&sort=${sort}&order=${order}`;
        }

        // Predictive search script for post search in the admin panel
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('post-search-input');
            const suggestionsBox = document.getElementById('post-suggestions');

            searchInput.addEventListener('input', function() {
                const query = searchInput.value.trim();

                if (query.length > 0) {
                    fetch(`/admin/table/post/search/predictions?search=${query}`)
                        .then(response => response.json())
                        .then(data => {
                            suggestionsBox.innerHTML = '';
                            if (data.length > 0) {
                                data.forEach(post => {
                                    const suggestionItem = document.createElement('li');
                                    suggestionItem.classList.add('list-group-item',
                                        'list-group-item-action');
                                    suggestionItem.textContent =
                                        `${post.id} - ${post.title}`; // Display ID and title
                                    suggestionItem.addEventListener('click', function() {
                                        searchInput.value = post
                                            .title; // Fill the input with the selected post's title
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
