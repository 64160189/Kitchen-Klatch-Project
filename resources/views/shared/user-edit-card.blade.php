<div class="card">
    <div class="px-3 pt-4 pb-2">
        <form enctype="multipart/form-data" method="POST" action="{{ route('users.update', $user->id) }}">
            @csrf
            @method('put')
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <!-- Adjusted CSS here to maintain circular shape -->
                    <img class="me-3 avatar-sm rounded-circle" src="{{ $user->getImageURL() }}"
                        alt="{{ $user->name }}'s Avatar"
                        style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover;">
                    <div>
                        <input name="name" value="{{ $user->name }}" type="text" class="form-control"
                            placeholder="Name" required>
                        @error('name')
                            <span class="text-danger fs-6">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div>
                    @auth
                        @if (Auth::id() === $user->id)
                            <a href="{{ route('users.show', $user->id) }}" class="btn btn-link">View</a>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="mt-3">
                <label for="image">Profile Picture:</label>
                <input name="image" class="form-control" type="file" id="image" accept="image/*">
                @error('image')
                    <span class="text-danger fs-6">{{ $message }}</span>
                @enderror
            </div>
            <div class="px-2 mt-4">
                <h5 class="fs-5">Bio:</h5>
                <div class="mb-3">
                    <textarea name="bio" class="form-control" id="bio" rows="3" required>{{ $user->bio }}</textarea>
                    @error('bio')
                        <span class="d-block fs-6 text-danger mt-2">{{ $message }}</span>
                    @enderror
                </div>
                <button class="btn btn-dark btn-sm mb-3">Save</button>
                <div class="d-flex justify-content-start">
                    <a href="#" class="fw-light nav-link fs-6 me-3">
                        <span class="fas fa-user me-1"></span> 0 Followers
                    </a>
                    <a href="#" class="fw-light nav-link fs-6 me-3">
                        <span class="fas fa-brain me-1"></span> 0 Following
                    </a>
                    <a href="#" class="fw-light nav-link fs-6">
                        <span class="fas fa-comment me-1"></span> 2 Posts
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
