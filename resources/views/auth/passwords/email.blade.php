@extends('layouts.app')

@section('content')
<div class="container">
    <h1>รีเซ็ตรหัสผ่าน</h1>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">อีเมล</label>
            <input type="email" class="form-control" id="email" name="email" required>
            @error('email')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="btn btn-danger">ส่งลิงก์รีเซ็ตรหัสผ่าน</button>
    </form>
</div>
@endsection
