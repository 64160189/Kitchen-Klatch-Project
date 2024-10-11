@extends('layouts.app')

@section('content')
<div class="container">
    <h1>ตั้งค่ารหัสผ่านใหม่</h1>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        <div class="mb-3">
            <label for="password" class="form-label">รหัสผ่านใหม่</label>
            <input type="password" class="form-control" id="password" name="password" required>
            @error('password')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password-confirm" class="form-label">ยืนยันรหัสผ่านใหม่</label>
            <input type="password" class="form-control" id="password-confirm" name="password_confirmation" required>
        </div>

        <button type="submit" class="btn btn-danger">ตั้งค่ารหัสผ่าน</button>
    </form>
</div>
@endsection