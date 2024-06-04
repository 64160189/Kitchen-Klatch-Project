@extends('layout') <!-- header from layout -->
@section('title')
    posting
@endsection <!-- title from layout -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <style>
        .container {
            display: flex;
            flex-grow: 1;
            overflow: hidden;
            margin-top: 80px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>แบ่งปันสูตรอาหารของคุณ</h2>
        <form class="form-group" method="POST" enctype="multipart/form-data" action="/insert">
            <div class="mb-3">
                <label for="title" class="form-label">ชื่อเมนู</label>
                <input type="text" class="form-control" id="title" name="title"
                    placeholder="โปรดใส่ชื่อเมนูของคุณ" required>
            </div>
            @error('title')
                <div class="my-2">
                    <span>{{ message }}</span>
                </div>
            @enderror
            <div class="mb-3">
                <label for="description" class="form-label">คำอธิบาย</label>
                <textarea class="form-control" id="description" name="description" cols="75" rows="5"
                    placeholder="โปรดใส่คำอธิบายให้เมนูของคุณ" required></textarea>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">รูปภาพ</label>
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
            <input type="submit" value="เผยแพร่" class="btn btn-danger">
            <a href="/" class="btn btn-light">ออก</a>
        </form>
    </div>
</body>

</html>
