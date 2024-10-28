<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Kitchen klatch</title>

    {{-- link to css --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        .fridge {
            width: 20%;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 13%;
            z-index: 1000;
        }
    </style>
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm sticky-top">
            <div class="container-fluid">
                <a class="btn btn-outline-danger me-2" data-bs-toggle="offcanvas" href="#offcanvasExample"
                    role="button" aria-controls="offcanvasExample">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                        <path
                            d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
                    </svg>
                </a>
                <a class="navbar-brand text-danger fw-bold fs-4" href="{{ url('/') }}">
                    {{ config('app.name', 'KitchenKlatch') }}
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    {{-- search bar --}}
                    <form class="d-flex mb-1 mt-1 position-relative" role="search" method="get"
                        action="{{ route('title.search') }}">
                        <input class="form-control me-2" id="search-input" type="search" name="search"
                            placeholder="ค้นหาชื่อเมนู" value="{{ isset($search) ? $search : '' }}" aria-label="Search"
                            autocomplete="off">
                        <button class="btn btn-danger" type="submit">ค้นหา</button>
                        <ul id="title-suggestions" class="list-group position-absolute w-100"
                            style="top: 100%; z-index: 1000;"></ul>
                    </form>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item ">
                                    <a class="nav-link btn btn-danger bg-danger"
                                        href="{{ route('login') }}">{{ __('เข้าสู่ระบบ') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link btn btn-light"
                                        href="{{ route('register') }}">{{ __('ลงทะเบียน') }}</a>
                                </li>
                            @endif
                        @else
                            {{-- create post --}}
                            <a href="/create_post" class="nav-item btn btn-outline-secondary p-2" title="โพสต์สูตรอาหาร"
                                data-bs-toggle="tooltip">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                    class="bi bi-plus-circle" viewBox="0 0 16 16">
                                    <path
                                        d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                                </svg>
                            </a>

                            <!-- Notification Dropdown -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="notificationsDropdown" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <!-- Material Icons Bell Icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-bell" viewBox="0 0 16 16">
                                        <path
                                            d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2M8 1.918l-.797.161A4 4 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4 4 0 0 0-3.203-3.92zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5 5 0 0 1 13 6c0 .88.32 4.2 1.22 6" />
                                    </svg>
                                    <!-- Badge for unread notifications -->
                                    @if (auth()->user()->notifications()->where('is_read', false)->count())
                                        <span class="badge bg-danger" id="notification-count">
                                            {{ auth()->user()->notifications()->where('is_read', false)->count() }}
                                        </span>
                                    @endif
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown">
                                    @if (auth()->user()->notifications->isNotEmpty())
                                        @foreach (auth()->user()->notifications as $notification)
                                            <li>
                                                <a class="dropdown-item {{ $notification->is_read ? 'list-group-item-read' : 'list-group-item-unread' }}"
                                                    href="{{ route('notifications.read', $notification->id) }}"
                                                    data-notifiable-type="{{ $notification->notifiable_type }}"
                                                    data-post-title="{{ $notification->post_title }}"
                                                    data-post-reason="{{ $notification->message }}">
                                                    {{ Str::limit($notification->message, 45) }}
                                                    <span class="text-muted">({{ $notification->created_at->diffForHumans() }})</span>
                                                 </a>
                                                 
                                            </li>
                                        @endforeach
                                    @else
                                        <li>
                                            <a class="dropdown-item" href="#">No notifications</a>
                                        </li>
                                    @endif
                                </ul>
                            </li>

                            {{-- profile dropdown --}}
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

                                    @if (Auth::user()->is_admin)
                                        <a class="dropdown-item" href="{{ route('admin.home') }}">
                                            {{ __('แอดมิน') }}
                                        </a>
                                    @endif

                                    <a class="dropdown-item" href="{{ route('profile') }}">
                                        {{ __('โปรไฟล์') }}
                                    </a>
                                    <a class="dropdown-item bg-danger-subtle" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                        {{ __('ออกจากระบบ') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        {{-- side bar --}}
        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample"
            aria-labelledby="offcanvasExampleLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title fs-3 fw-bold text-danger" id="offcanvasExampleLabel">KitchenKlatch</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                    <li class="nav-item">
                        <a class="nav-link active fw-bold" aria-current="page">หน้าหลัก</a>
                    </li>
                    <li class="nav-item">
                        @guest
                        @else
                            @if (Auth::user()->is_admin)
                                <a class="nav-link btn bg-secondary-subtle mb-1" href="{{ route('admin.home') }}">
                                    {{ __('แดชบอร์ดแอดมิน') }}
                                </a>
                            @endif
                        @endguest
                        <a class="nav-link btn bg-secondary-subtle mb-1" href="/">โพสต์ทั้งหมด</a>
                        <a class="nav-link btn bg-secondary-subtle" href="{{ route('following.posts') }}">การติดตาม</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active fw-bold mt-4" aria-current="page">เกี่ยวกับคุณ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn bg-secondary-subtle mb-1" href="{{ route('profile') }}">โปรไฟล์</a>
                        <a class="nav-link btn bg-secondary-subtle mb-1" href="/create_post">โพสต์สูตรอาหาร</a>
                    </li>
                </ul>
            </div>
        </div>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <script>
        // predict search script
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-input');
            const suggestionsBox = document.getElementById('title-suggestions');

            searchInput.addEventListener('input', function() {
                const query = searchInput.value.trim();

                if (query.length > 0) {
                    fetch(`/title/predictions?search=${query}`)
                        .then(response => response.json())
                        .then(data => {
                            suggestionsBox.innerHTML = '';
                            if (data.length > 0) {
                                data.forEach(post => {
                                    const suggestionItem = document.createElement('li');
                                    suggestionItem.classList.add('list-group-item',
                                        'list-group-item-action');
                                    suggestionItem.textContent = post.title;
                                    suggestionItem.addEventListener('click', function() {
                                        searchInput.value = post.title;
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

            document.addEventListener('click', function(event) {
                if (!suggestionsBox.contains(event.target) && event.target !== searchInput) {
                    suggestionsBox.innerHTML = '';
                }
            });
        });

        // ask for ingredients bar
        @if (View::hasSection('show_ingredients_bar'))
            // ingredients search script
            document.addEventListener('DOMContentLoaded', function() {
                const ingredientInput = document.getElementById('ingredient-input');
                const addIngredientButton = document.getElementById('add-ingredient');
                const ingredientList = document.getElementById('ingredient-list');
                const searchRecipesButton = document.getElementById('search-recipes');
                const randomRecipeButton = document.getElementById('random-recipe');
                const loadingAnimation = document.getElementById('loading-animation');
                let ingredients = @json($ingredients); // Load the ingredients from PHP

                if (ingredients && ingredients.length > 0) {
                    updateIngredientList();
                }

                addIngredientButton.addEventListener('click', function() {
                    const ingredient = ingredientInput.value.trim();
                    if (ingredient && !ingredients.includes(ingredient)) {
                        ingredients.push(ingredient);
                        updateIngredientList();
                    }
                    ingredientInput.value = '';
                });

                ingredientList.addEventListener('click', function(e) {
                    if (e.target.tagName === 'BUTTON') {
                        const ingredient = e.target.getAttribute('data-ingredient');
                        ingredients = ingredients.filter(i => i !== ingredient);
                        updateIngredientList();
                    }
                });

                searchRecipesButton.addEventListener('click', function() {
                    fetch('/store-ingredients', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify({
                            ingredients: ingredients
                        })
                    }).then(() => {
                        window.location.href =
                            `/ingredients/search?ingredients=${ingredients.join(',')}`;
                    });
                });

                //random recipe
                randomRecipeButton.addEventListener('click', function() {
                    // Set the background color
                    document.getElementById('loading-animation').style.setProperty('--loading-bg-color',
                        '#ffa806');
                    // Set the loading text
                    document.querySelector('.loading-text').textContent = 'Calculating...';
                    // Show the loading animation
                    loadingAnimation.style.display = 'block';

                    fetch('/random-recipe', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            },
                            body: JSON.stringify({
                                ingredients: ingredients
                            })
                        }).then(response => response.json())
                        .then(data => {
                            if (data.postId) {
                                // Wait for 1.5 seconds before redirecting
                                setTimeout(() => {
                                    // Hide the loading animation just before redirecting
                                    loadingAnimation.style.display = 'none';
                                    // Redirect after the delay
                                    window.location.href = `/post/${data.postId}`;
                                }, 1500);
                            } else {
                                // Hide the loading animation if there's an error
                                loadingAnimation.style.display = 'none';
                                alert('No matching recipes found.');
                            }
                        }).catch(error => {
                            // Hide the loading animation if there's an error
                            loadingAnimation.style.display = 'none';
                            console.error('Error:', error);
                        });
                });


                function updateIngredientList() {
                    ingredientList.innerHTML = '';
                    ingredients.forEach(ingredient => {
                        const li = document.createElement('li');
                        li.classList.add('ingredient-item');
                        li.innerHTML =
                            `${ingredient} <button data-ingredient="${ingredient}">&times;</button>`;
                        ingredientList.appendChild(li);
                    });
                }
            });

            // Load ingredient list from PHP variable
            document.addEventListener('DOMContentLoaded', function() {
                const ingredients = @json($ingredients);
                if (ingredients && ingredients.length > 0) {
                    updateIngredientList(ingredients);
                }

                function updateIngredientList(ingredients) {
                    const ingredientList = document.getElementById('ingredient-list');
                    ingredientList.innerHTML = '';
                    ingredients.forEach(ingredient => {
                        const li = document.createElement('li');
                        li.classList.add('ingredient-item');
                        li.innerHTML =
                            `${ingredient} <button data-ingredient="${ingredient}">&times;</button>`;
                        ingredientList.appendChild(li);
                    });
                }
            });

            // ingredients predict search script
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('ingredient-input');
                const suggestionsBox = document.getElementById('ingredients-suggestions');

                searchInput.addEventListener('input', function() {
                    const query = searchInput.value.trim();

                    if (query.length > 0) {
                        fetch(`/ingredients/predictions?search=${query}`)
                            .then(response => response.json())
                            .then(data => {
                                suggestionsBox.innerHTML = '';
                                if (data.length > 0) {
                                    data.forEach(ingredient => {
                                        const suggestionItem = document.createElement('li');
                                        suggestionItem.classList.add('list-group-item',
                                            'list-group-item-action');
                                        suggestionItem.textContent = ingredient;
                                        suggestionItem.addEventListener('click', function() {
                                            searchInput.value = ingredient;
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

                document.addEventListener('click', function(event) {
                    if (!suggestionsBox.contains(event.target) && event.target !== searchInput) {
                        suggestionsBox.innerHTML = '';
                    }
                });
            });
        @endif

        //hover create post
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
</body>

</html>
