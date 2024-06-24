@extends('layout') <!-- header from layout -->
@section('title')
    Home
@endsection <!-- title from layout -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        .container {
            display: flex;
            flex-grow: 1;
            overflow: hidden;
            margin-top: 60px;
        }

        .left-sidebar {
            background-color: lightgreen;
            position: fixed;
            top: 60px;
            left: 0;
            bottom: 0;
            width: 30%;
            overflow-y: auto;
        }

        .main-content {
            margin-left: 30%;
            padding: 10px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .content-area {
            width: 70%;
            min-width: 300px;
        }

        .post-frame {
            margin-bottom: 20px;
            background: #F2F2F2;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        .post-frame img {
            display: block;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="left-sidebar">
            <h2>Search from ingredients (Position: Fixed)</h2>
        </div>
        <div class="main-content">
            <div class="content-area">
                <h1>Posts</h1>
                <div id="post-container">
                    @foreach ($posts as $item)
                        <div class="post-frame">
                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}"
                                style="width:100%; height:auto;">
                            <h2>{{ $item->title }}</h2>
                            <p>{{ Str::limit($item->description, 50) }}</p>
                            <h3>วัตถุดิบ:</h3>
                            <p>{{ Str::limit(implode(', ', $item->ingrediant), 50) }}</p>
                        </div>
                    @endforeach
                </div>
                @if ($posts->hasMorePages())
                    <div class="d-flex justify-content-center">
                        <button id="load-more" class="btn btn-primary" data-page="{{ $posts->currentPage() + 1 }}">Load
                            More</button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
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
                                postFrame.classList.add('post-frame');
                                postFrame.innerHTML = `
                                    <img src="/storage/${post.image}" alt="${post.title}" style="width:100%; height:auto;">
                                    <h2>${post.title}</h2>
                                    <p>${post.description.substring(0, 50)}...</p>
                                    <h3>วัตถุดิบ:</h3>
                                    <p>${post.ingrediant.join(', ').substring(0, 50)}...</p>
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
    </script>
</body>

</html>
