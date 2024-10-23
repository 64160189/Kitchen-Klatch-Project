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
                    <div class="row justify-content-center">
                        {{-- HEAD --}}
                        <div class="row col-9">
                            <div class="col-8 mb-3">
                                <h4 class="fw-bold">บัญชีผู้ใช้ทั้งหมด {{ $AllUsers }} บัญชี</h4>
                                {{-- SEARCH --}}
                                <form class="d-flex mb-1 mt-1 position-relative" role="search" method="get"
                                    action="{{ route('search.user') }}">
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
                            @foreach ($users as $user)
                                <div class="card-body row">
                                    <div class="rounded border col bg-white">
                                        <div class="d-flex align-items-center">
                                            <img class="m-2 avatar-sm rounded-circle border border-dark"
                                                src="{{ $user->getImageURL() }}" alt="{{ $user->name }}"
                                                style="width: 15%;">
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

                                    <!-- Delete user button -->
                                    <button type="button"
                                        class="ms-1 col-1 btn btn-danger d-flex justify-content-center align-items-center"
                                        data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal"
                                        data-user-id="{{ $user->id }}">
                                        ลบ
                                    </button>

                                    <!-- Delete Form with Unique ID -->
                                    <form id="deleteForm-{{ $user->id }}"
                                        action="{{ route('admin.delete.user', $user->id) }}" method="POST"
                                        class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            @endforeach
                        </div>

                        {{-- PAGE --}}
                        <div class="mt-4 d-flex justify-content-center">

                            @if ($users->lastPage() > 1)
                                <div>
                                    {{-- Always show the first page --}}
                                    <a href="{{ $users->appends(['sort' => request('sort'), 'order' => request('order')])->url(1) }}"
                                        class="btn btn-outline-danger m-1 {{ $users->currentPage() == 1 ? 'active' : '' }}">1</a>

                                    {{-- Show "..." after the first page if the current page is more than 4 --}}
                                    @if ($users->currentPage() > 5)
                                        <span class="m-1">...</span>
                                    @endif

                                    {{-- Show pages around the current page (±3) --}}
                                    @for ($i = max(2, $users->currentPage() - 3); $i <= min($users->lastPage() - 1, $users->currentPage() + 3); $i++)
                                        <a href="{{ $users->appends(['sort' => request('sort'), 'order' => request('order')])->url($i) }}"
                                            class="btn btn-outline-danger m-1 {{ $users->currentPage() == $i ? 'active' : '' }}">
                                            {{ $i }}
                                        </a>
                                    @endfor

                                    {{-- Show "..." before the last page if the current page is less than total pages minus 3 --}}
                                    @if ($users->currentPage() < $users->lastPage() - 4)
                                        <span class="m-1">...</span>
                                    @endif

                                    {{-- Always show the last page --}}
                                    <a href="{{ $users->appends(['sort' => request('sort'), 'order' => request('order')])->url($users->lastPage()) }}"
                                        class="btn btn-outline-danger m-1 {{ $users->currentPage() == $users->lastPage() ? 'active' : '' }}">
                                        {{ $users->lastPage() }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            //delete users script
            document.addEventListener('DOMContentLoaded', function() {
                const confirmDeleteButton = document.getElementById('confirmDeleteButton');
                let userId;

                document.querySelectorAll('[data-bs-toggle="modal"]').forEach(function(button) {
                    button.addEventListener('click', function() {
                        userId = button.getAttribute('data-user-id');
                    });
                });

                confirmDeleteButton.addEventListener('click', function() {
                    const deleteReason = document.getElementById('deleteReason').value;
                    if (deleteReason) {
                        fetch(`/delete_user/${userId}`, {
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
                window.location.href = `/admin/table/user/search?search=${search}&sort=${sort}&order=${order}`;
            }

            // Predictive search script for user search in the admin panel
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('user-search-input');
                const suggestionsBox = document.getElementById('user-suggestions');

                searchInput.addEventListener('input', function() {
                    const query = searchInput.value.trim();

                    if (query.length > 0) {
                        fetch(`/admin/table/user/search/predictions?search=${query}`)
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

        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteConfirmationModal" tabindex="-1"
            aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteConfirmationModalLabel">Delete Confirmation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        คุณต้องการลบผู้ใช้นี้จริงหรือไม่?
                        <textarea id="deleteReason" class="form-control mt-3" placeholder="ระบุเหตุผลการลบ" rows="3"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                        <button type="button" class="btn btn-danger" id="confirmDeleteButton">Yes</button>
                    </div>
                </div>
            </div>
        </div>
    @endsection
