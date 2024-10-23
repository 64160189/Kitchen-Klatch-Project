@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 align="center">แก้ไขสูตรอาหารของคุณ</h2>
        <form class="form-group" method="POST" enctype="multipart/form-data" action="{{ route('post.update', $post->id) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="title" class="form-label">ชื่อเมนู</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="โปรดใส่ชื่อเมนูของคุณ"
                    value="{{ $post->title }}" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">คำอธิบาย</label>
                <textarea class="form-control" id="description" name="description" cols="75" rows="5"
                    placeholder="โปรดใส่คำอธิบายให้เมนูของคุณ" required>{{ $post->description }}</textarea>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">รูปภาพ ( .jpeg, .png, .jpg, .gif, .svg )</label>
                <input type="file" class="form-control" id="image" name="image">
                @if ($post->image)
                    <img src="{{ asset('storage/' . $post->image) }}" alt="Post Image" width="500">
                @endif
            </div>

            <div class="mb-3">
                <label for="ingrediant" class="form-label">วัตถุดิบ</label>
                <textarea class="form-control" id="ingrediant" name="ingrediant" cols="75" rows="5"
                    placeholder="ใช้ 'การเว้นบรรทัด' เป็นการขั้นระหว่างวัตถุดิบแต่ละอย่าง เช่น 
ข้าวสวย
กระเทียม
ต้นหอม
ไข่" required>{{ is_array($post->ingrediant) ? implode("\n", $post->ingrediant) : $post->ingrediant }}</textarea>
            </div>
            <div class="mb-3">
                <label for="htc" class="form-label">วิธีทำ</label>
                <textarea class="form-control" id="htc" name="htc" cols="75" rows="5"
                    placeholder="ใช้ 'การเว้นบรรทัด' เป็นการขั้นระหว่างขั้นตอนแต่ละขั้น เช่น
1.ผัดกระเทียม
2.ผัดข้าวกับไข่
3.ปรุงรส"
                    required>{{ is_array($post->htc) ? implode("\n", $post->htc) : $post->htc }}</textarea>
            </div>

            <div class="mb-3">
                <label for="time_to_cook" class="form-label">เวลาที่ใช้ในการทำ (นาที)</label>
                <input type="number" class="form-control" id="time_to_cook" name="time_to_cook" value="{{ $post->time_to_cook }}" min="1" required>
            </div>
        
            <div class="mb-3">
                <label for="level_of_cook" class="form-label">ระดับความยากของการทำ</label>
                <select class="form-select" id="level_of_cook" name="level_of_cook" required>
                    <option value="1" {{ $post->level_of_cook == 1 ? 'selected' : '' }}>ง่ายมาก</option>
                    <option value="2" {{ $post->level_of_cook == 2 ? 'selected' : '' }}>ค่อนข้างง่าย</option>
                    <option value="3" {{ $post->level_of_cook == 3 ? 'selected' : '' }}>ปานกลาง</option>
                    <option value="4" {{ $post->level_of_cook == 4 ? 'selected' : '' }}>ค่อนข้างยาก</option>
                    <option value="5" {{ $post->level_of_cook == 5 ? 'selected' : '' }}>ยาก</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="youtube_link" class="form-label">ลิงค์วิดีโอ YouTube "ถ้ามี"</label>
                <input type="text" class="form-control" id="youtube_link" name="youtube_link"
                    placeholder="โปรดใส่ลิงค์วิดีโอ YouTube เท่านั้น (ถ้ามี) *คลิปที่มีลิขสิทธิ์อาจจะไม่ขึ้นนะครับ"
                    value="{{ $post->youtube_link }}">
            </div>
            <input type="submit" value="บันทึก" class="btn btn-danger">
            <a onclick="window.history.back();" class="btn btn-light">ออก</a>
        </form>
    </div>
@endsection
