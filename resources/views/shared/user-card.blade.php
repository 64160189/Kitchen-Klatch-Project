<div class="card bg-white border-dark">
    <div class="px-3 pt-4 pb-2">
        <!-- User Info Section -->
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <img class="me-3 avatar-sm rounded-circle border border-dark" src="{{ $user->getImageURL() }}"
                    alt="{{ $user->name }}" style="width: 150px;">
                <div>
                    <h3 class="card-title mb-0 text-dark">
                        <a href="{{ route('users.show', ['user' => $user->id]) }}"
                            class="text-decoration-none text-primary">{{ $user->name }}</a>
                    </h3>
                    <span class="fs-6 text-muted">{{ $user->email }}</span>
                </div>
            </div>
            @auth()
                @if (Auth::id() === $user->id)
                    <div>
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-outline-secondary">Edit</a>
                    </div>
                @endif
            @endauth
        </div>

        <!-- Bio Section -->
        <div class="px-2 mt-4">
            <h5 class="fs-5 text-dark">Bio:</h5>
            <p class="fs-6 fw-light bg-light p-3 rounded border border-secondary text-dark">
                {{ $user->bio }}
            </p>
            <hr class="border-secondary">

            <!-- Statistics Section -->
            <div class="d-flex justify-content-start">
                <a href="#" class="fw-light nav-link fs-6 me-3 text-muted">
                    <span class="fas fa-user me-1"></span> {{ $user->followers()->count() }} Followers
                </a>
                <a href="#" class="fw-light nav-link fs-6 me-3 text-muted">
                    <span class="fas fa-brain me-1"></span> {{ $user->followings()->count() }} Following
                </a>
                <a href="#" class="fw-light nav-link fs-6 text-muted">
                    <span class="fas fa-comment me-1"></span> {{ $user->posts()->count() }} Posts
                </a>
            </div>

            @auth()
                @if (Auth::id() !== $user->id)
                    <div class="mt-3">
                        @if (Auth::user()->follows($user))
                            <form method="POST" action="{{ route('users.unfollow', $user->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">Unfollow</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('users.follow', $user->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-secondary btn-sm">Follow</button>
                            </form>
                        @endif
                    </div>
                @endif
            @endauth
        </div>
    </div>
</div>
