@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 align="center">แบ่งปันสูตรอาหารของคุณ</h2>
        <form class="form-group" method="POST" enctype="multipart/form-data" action="/insert">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">ชื่อเมนู</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="โปรดใส่ชื่อเมนูของคุณ"
                    required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">คำอธิบาย</label>
                <textarea class="form-control" id="description" name="description" cols="75" rows="5"
                    placeholder="โปรดใส่คำอธิบายให้เมนูของคุณ" required></textarea>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">รูปภาพ ( .jpeg, .png, .jpg, .gif, .svg )</label>
                <input type="file" class="form-control" id="image" name="image" required>
            </div>

            <div class="mb-3">
                <label for="ingredient" class="form-label">วัตถุดิบ</label>
                <textarea class="form-control" id="ingredient" name="ingredient" cols="75" rows="5"
                    placeholder="ใช้ 'การเว้นบรรทัด' เป็นการขั้นระหว่างวัตถุดิบแต่ละอย่าง เช่น 
ข้าวสวย
กระเทียม
ต้นหอม
ไข่" required></textarea>
            </div>
            <div class="mb-3">
                <label for="htc" class="form-label">วิธีทำ</label>
                <textarea class="form-control" id="htc" name="htc" cols="75" rows="5"
                    placeholder="ใช้ 'การเว้นบรรทัด' เป็นการขั้นระหว่างขั้นตอนแต่ละขั้น เช่น
1.ผัดกระเทียม
2.ผัดข้าวกับไข่
3.ปรุงรส"
                    required></textarea>
            </div>
            <div class="mb-3">
                <label for="youtube_link" class="form-label">ลิงค์วิดีโอ YouTube "ถ้ามี"</label>
                <input type="text" class="form-control" id="youtube_link" name="youtube_link"
                    placeholder="โปรดใส่ลิงค์วิดีโอ YouTube เท่านั้น (ถ้ามี) *คลิปที่มีลิขสิทธิ์อาจจะไม่ขึ้นนะครับ">
            </div>
            <input type="submit" value="เผยแพร่" class="btn btn-danger">
            <a onclick="window.history.back();" class="btn btn-light">ออก</a>
        </form>

    </div>
@endsection
