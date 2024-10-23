@extends('layouts.app')

@section('content')
    <style>
        .back-button {
            display: flex;
            width: 50px;
            height: 38px;
        }
    </style>

    <div class="container">
        <div class="row">
            <!-- left sidebar (menu) -->
            <div class="col-2">
                <div class="fixed-bottom m-2">
                    <!-- (menu) -->
                    <div class="btn-group dropup mb-2" role="group">
                        <button type="button" class="btn btn-outline-danger rounded" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="16" fill="currentColor"
                                class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                <path
                                    d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
                            </svg>
                        </button>
                        <ul class="dropdown-menu">
                            @if (Auth::check() && Auth::user()->id == $post->user_id)
                                <!-- edit -->
                                <li><a class="dropdown-item" href="{{ route('post.edit', $post->id) }}">แก้ไขสูตรอาหาร</a>
                                </li>
                                <!-- delete -->
                                <li>
                                    <form id="deleteForm" action="{{ route('post.destroy', $post->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                            data-bs-target="#deleteConfirmationModal">ลบสูตรอาหาร</button>
                                    </form>
                                </li>
                            @else
                                <li>
                                    <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                        data-bs-target="#reportPostModal">
                                        รายงานสูตรอาหารนี้
                                    </button>
                                </li>
                            
                                <li>
                                    <form action="{{ route('post.shareToFeed', $post->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">แชร์ไปยังฟีดของคุณ</button>
                                    </form>
                                </li>
                                <li>
                                    <button type="button" class="dropdown-item"
                                        onclick="sharePost()">แชร์สูตรอาหาร</button>
                                </li>
                            @endif
                            <!-- share -->

                        </ul>
                    </div>
                    <!-- (back) -->
                    <div>
                        <button class="back-button btn btn-danger" onclick="window.history.back();">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-3">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- content area -->
            <div class="col-8">
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

                <div class="card mb-3 shadow">
                    <img class="card-img-top" src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}"
                        style="width:100%; height:auto; max-height:1000px">
                    <div class="card-body">
                        <h1 class="card-title">{{ $post->title }}</h1>
                        <p class="card-text">{{ $post->description }}</p>
                    </div>
                </div>

                <div class="card card-body mb-3 shadow">
                    <h3 class="card-title">วัตถุดิบ:</h3>
                    <ol class="card-text">
                        @if (is_array($post->ingrediant))
                            @foreach ($post->ingrediant as $ingredient)
                                <li>{{ $ingredient }}</li>
                            @endforeach
                        @else
                            <li>ข้อมูลวัตถุดิบไม่ถูกต้อง</li>
                        @endif
                    </ol>
                </div>

                <div class="card mb-3 shadow">
                    @if ($post->youtube_link)
                        @php
                            $video_id = '';
                            // ดึง video_id จากลิงก์ YouTube แบบปกติ
                            if (preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $post->youtube_link, $matches)) {
                                $video_id = $matches[1];
                            }

                            // ดึง video_id จากลิงก์ YouTube แบบสั้น
                            if (
                                !$video_id &&
                                preg_match('/youtu\\.be\\/([^\\?\\&]+)/', $post->youtube_link, $matches)
                            ) {
                                $video_id = $matches[1];
                            }
                        @endphp
                        @if ($video_id)
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe class="embed-responsive-item card-img-top"
                                    src="https://www.youtube.com/embed/{{ $video_id }}" allowfullscreen width="100%"
                                    height="400px"></iframe>
                            </div>
                        @endif
                    @endif

                    <div class="card-body">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-clock" viewBox="0 0 16 16">
                            <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z" />
                            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0" />
                        </svg>
                        @if ($post->time_to_cook)
                            <span class="text-muted">{{ $post->time_to_cook }} นาที | </span>
                        @else
                            <span class="text-muted">-- | </span>
                        @endif

                        @if ($post->level_of_cook == 1)
                            <span class="card-text text-muted">ง่ายมาก</span>
                        @elseif ($post->level_of_cook == 2)
                            <span class="card-text text-muted">ค่อนข้างง่าย</span>
                        @elseif ($post->level_of_cook == 3)
                            <span class="card-text text-muted">ปานกลาง</span>
                        @elseif ($post->level_of_cook == 4)
                            <span class="card-text text-muted">ค่อนข้างยาก</span>
                        @else
                            <span class="card-text text-muted">ยาก</span>
                        @endif

                        <h3 class="card-title mt-2">วิธีทำ:</h3>
                        <ol class="card-text">
                            @if (is_array($post->htc))
                                @foreach ($post->htc as $step)
                                    <p>..{{ $step }}</p>
                                @endforeach
                            @else
                                <li>ข้อมูลวิธีทำไม่ถูกต้อง</li>
                            @endif
                        </ol>
                    </div>
                </div>


                <div class="mb-3">
                    @include('shared.user-card', ['user' => $post->user])
                </div>

                <div class="comments-box">
                    @include('shared.comments-box', ['post' => $post])
                </div>
            </div>
            <!-- right sidebar (user)
                            <div class="col-3 post-user border">
                                {{-- @include('shared.user-card', ['user' => $post->user]) --}}
                            </div>
                            -->
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteButton">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Post Modal -->
    <div class="modal fade" id="reportPostModal" tabindex="-1" aria-labelledby="reportPostModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportPostModalLabel">รายงานสูตรอาหารนี้</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('post.report', $post->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <!-- Fieldset for Reporting Reasons -->
                        <fieldset class="border p-3" style="border-color: #dc3545; border-width: 2px;">
                            <legend class="w-auto" style="font-size: 1.25rem; color: #dc3545; font-weight: bold;">
                                เลือกเหตุผลในการรายงาน:</legend>
                            <div class="form-group">
                                <select class="form-control" id="reportReason" name="reportReason" required>
                                    <option value="">เลือกเหตุผล</option>
                                    <option value="inappropriate_content">เนื้อหาที่ไม่เหมาะสม:
                                        โพสต์มีข้อความหรือภาพที่เป็นการล่วงละเมิด เหยียดหยาม หรือสร้างความเกลียดชัง</option>
                                    <option value="inappropriate_image_video">ภาพหรือวิดีโอที่ไม่เหมาะสม:
                                        การใช้ภาพหรือวิดีโอที่ล่อแหลม รุนแรง หรือผิดกฎหมาย</option>
                                    <option value="copyright_infringement">การละเมิดลิขสิทธิ์: โพสต์ที่ใช้รูปภาพ วิดีโอ
                                        หรือเนื้อหาสูตรอาหารที่ละเมิดลิขสิทธิ์โดยไม่ได้รับอนุญาต</option>
                                    <option value="spam">สแปม:
                                        โพสต์ที่มีลักษณะเป็นการโฆษณาสินค้าหรือบริการที่ไม่เกี่ยวข้องกับอาหารซ้ำ ๆ
                                        หรือส่งเป็นจำนวนมาก</option>
                                    <option value="scam">การหลอกลวง: โพสต์ที่มีเจตนาให้ข้อมูลผิด ๆ
                                        หรือเป็นการหลอกลวงผู้ใช้งาน เช่น สูตรอาหารที่ไม่ถูกต้องหรือเป็นอันตราย</option>
                                    <option value="off_topic">เนื้อหาไม่ตรงประเด็น: โพสต์ที่ไม่เกี่ยวข้องกับเนื้อหาอาหาร
                                        เช่น โพสต์เกี่ยวกับหัวข้ออื่นที่ไม่เกี่ยวข้องกับแอป</option>
                                    <option value="privacy_violation">การละเมิดความเป็นส่วนตัว:
                                        โพสต์ที่เปิดเผยข้อมูลส่วนบุคคลของผู้อื่น เช่น ชื่อ ที่อยู่ หรือข้อมูลส่วนบุคคลอื่นๆ
                                        โดยไม่ได้รับอนุญาต</option>
                                    <option value="offensive_language">เนื้อหาที่ไม่สุภาพ:
                                        โพสต์ที่ใช้ภาษาหยาบคายหรือลามกอนาจาร</option>
                                    <option value="misinformation">การบิดเบือนข้อมูล:
                                        โพสต์ที่นำเสนอข้อมูลเกี่ยวกับอาหารที่ไม่ถูกต้อง
                                        ซึ่งอาจก่อให้เกิดความสับสนหรืออันตราย</option>
                                </select>
                            </div>
                        </fieldset>
                        <!-- Additional Information Section -->
                        <div class="form-group mt-3">
                            <label for="additionalInfo" style="font-weight: bold;">ข้อมูลเพิ่มเติม (ถ้ามี):</label>
                            <textarea class="form-control" id="additionalInfo" name="additionalInfo" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-danger">ส่งรายงาน</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        //go to user function
        function goToUser(userId) {
            window.location.href = `/users/${userId}`;
        };

        //delete post confirmation 
        document.addEventListener('DOMContentLoaded', function() {
            const confirmDeleteButton = document.getElementById('confirmDeleteButton');
            confirmDeleteButton.addEventListener('click', function() {
                document.getElementById('deleteForm').submit();
            });
        });

        // share post function
        function sharePost() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ $post->title }}',
                    text: '{{ $post->description }}',
                    url: window.location.href
                }).then(() => {
                    console.log('Post shared successfully.');
                }).catch((error) => {
                    console.error('Error sharing post:', error);
                });
            } else {
                alert('Sharing is not supported in this browser.');
            }
        }
    </script>
@endsection
