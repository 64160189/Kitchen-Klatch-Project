<div class="card bg-white border-dark">
    <div class="px-3 pt-4 pb-2">
        <!-- User Info Section -->
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <img class="me-3 avatar-sm rounded-circle border border-dark" src="{{ $user->getImageURL() }}"
                    alt="Avatar" style="width: 30%;">
                <div>
                    <h3 class="card-title mb-0 text-dark">
                        <a href="#" class="text-decoration-none text-primary">{{ $user->name }}</a>
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
                    <span class="fas fa-user me-1"></span> 0 Followers
                </a>
                <a href="#" class="fw-light nav-link fs-6 me-3 text-muted">
                    <span class="fas fa-brain me-1"></span> 0
                </a>
                <a href="#" class="fw-light nav-link fs-6 text-muted">
                    <span class="fas fa-comment me-1"></span> 2
                </a>
            </div>

            @auth()
                @if (Auth::id() !== $user->id)
                    <div class="mt-3">
                        <button class="btn btn-secondary btn-sm">Follow</button>
                    </div>
                @endif
            @endauth
        </div>
    </div>
</div>
