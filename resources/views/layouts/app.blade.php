<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Kitchen klatch</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        .nav-link {
            position: relative;
            transition: color 0.3s ease;
            /* เพิ่มแอนิเมชันในการเปลี่ยนสี */
        }

        .nav-link:hover {
            color: #dc3545;
            /* สีเมื่อ hover */
        }

        #notification-count {
            font-size: 0.55rem;
            /* ขนาดของตัวเลข */
            background-color: #dc3545;
            /* สีพื้นหลังของตัวเลขแจ้งเตือน */
            color: white;
            /* สีตัวเลข */
            border-radius: 50%;
            /* ทำให้เป็นวงกลม */
            position: absolute;
            /* ใช้ตำแหน่งแบบ absolute */
            top: -1px;
            /* เลื่อนขึ้น */
            left: -1px;
            /* เลื่อนขวา */
        }

        .dropdown-menu {
            background-color: #ffffff;
            /* สีพื้นหลังเมนู dropdown */
            border: 1px solid #dee2e6;
            /* ขอบของ dropdown */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* เงา */
        }

        .dropdown-item {
            transition: background-color 0.2s ease;
            /* แอนิเมชันสำหรับ background */
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
            /* สีเมื่อ hover บนรายการ */
        }

        .list-group-item-unread {
            background-color: #c9c9ca;
            /* สีสำหรับแจ้งเตือนที่ยังไม่อ่าน */
            color: #212529;
            /* สีข้อความ */
        }

        .list-group-item-read {
            background-color: #e2e3e5;
            /* สีสำหรับแจ้งเตือนที่อ่านแล้ว */
            color: #212529;
            /* สีข้อความ */
            border-left: 4px solid #28a745;
            /* เส้นข้างซ้ายเพื่อแยก */
        }

        .main-content {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .content-area {
            width: 75%;
            min-width: 300px;
        }

        .post-frame:hover {
            cursor: pointer;
        }

        .left-sidebar {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .fridge {
            width: 20%;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 20%;
            z-index: 1000;
        }

        .ingredient-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #ffcccc;
            padding: 5px 10px;
            border-radius: 5px;
            margin-bottom: 5px;
        }

        .ingredient-item button {
            background: none;
            border: none;
            color: #ff0000;
            font-size: 1.2rem;
        }
    </style>

</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm sticky-top">
            <div class="container-fluid">
                <a class="navbar-brand text-danger fw-bold fs-4" href="{{ url('/') }}">
                    {{ config('app.name', 'KitchenKlatch') }}

                    <button class="btn btn-outline-danger ms-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                            class="bi bi-house-door-fill" viewBox="0 1 16 16">
                            <path
                                d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5" />
                        </svg>
                    </button>
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
                            <!-- Notification Dropdown -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="notificationsDropdown" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <!-- Material Icons Bell Icon -->
                                    <span class="material-icons">notifications_none</span>
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
                                                    href="{{ route('notifications.read', $notification->id) }}">
                                                    {{ $notification->message }}
                                                    <span
                                                        class="text-muted">({{ $notification->created_at->diffForHumans() }})</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    @else
                                        <li>
                                            <a class="dropdown-item" href="#">ไม่มีการแจ้งเตือน</a>
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
                                    <a class="dropdown-item" href="/create_post">
                                        {{ __('แบ่งปันสูตรอาหาร') }}
                                    </a>
                                    <a class="dropdown-item bg-danger-subtle" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                        {{ __('ออกจากระบบ') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <script>
            // notification script
            document.addEventListener('DOMContentLoaded', function () {
                const notificationCountElement = document.getElementById('notification-count');
                // ตรวจสอบว่าจำนวนแจ้งเตือนมีค่ามากกว่า 0
                if (notificationCountElement) {
                    const initialCount = parseInt(notificationCountElement.innerText);
                    const notificationLinks = document.querySelectorAll('.dropdown-item');
                    notificationLinks.forEach(link => {
                        link.addEventListener('click', function () {
                            // ลดจำนวนแจ้งเตือนเมื่อคลิก
                            notificationCountElement.innerText = initialCount - 1;
                            initialCount--; // ลดค่าของจำนวนแจ้งเตือน
                        });
                    });
                }
            });
            
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
        </script>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>

</html>
