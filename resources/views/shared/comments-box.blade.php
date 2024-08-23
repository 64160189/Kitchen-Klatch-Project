<div id="comments-box-{{ $post->id }}">
    <hr>
    <h2>ความคิดเห็น</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('post.comment.store', $post->id) }}" method="POST" class="comment-form"
        data-post-id="{{ $post->id }}">
        @csrf
        <div class="mb-3">
            <textarea name="content" class="form-control" rows="2" placeholder="แสดงความคิดเห็น..."></textarea>
        </div>
        <div>
            <button type="submit" class="btn btn-primary btn-sm">โพสต์ความคิดเห็น</button>
        </div>
    </form>
    <hr>
    @if($post->comments->isEmpty())
        <p>ยังไม่มีความคิดเห็น. มาเป็นคนแรกที่แสดงความคิดเห็น!</p>
    @else
        @foreach($post->comments as $comment)
            <div class="d-flex align-items-start mb-2">
                <img style="width:35px" class="me-2 avatar-sm rounded-circle"
                    src="{{ $comment->user ? $comment->user->getImageURL() : 'https://api.dicebear.com/6.x/fun-emoji/svg?seed=default' }}"
                    alt="{{ $comment->user ? $comment->user->name : 'ผู้ใช้ที่ไม่รู้จัก' }} Avatar">
                <div class="w-100">
                    <div class="d-flex justify-content-between">
                        <a href="#"
                            class="text-decoration-none text-primary">{{ $comment->user ? $comment->user->name : 'ผู้ใช้ที่ไม่รู้จัก' }}</a>
                        <small class="fs-6 fw-light text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                    </div>
                    <p class="fs-6 mt-2 fw-light">
                        {{ $comment->content }}
                    </p>
                </div>
            </div>
        @endforeach
    @endif
</div>