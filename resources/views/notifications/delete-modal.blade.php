@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-8">
                <h2>รายละเอียดการแจ้งเตือน</h2>
                <!-- notifications/delete-modal.blade.php -->
                <div class="card" id="notificationCard" tabindex="-1" aria-labelledby="notificationModalLabel"
                    aria-hidden="true">
                    <div class="card-content">
                        <img class="card-img-top" src="{{ asset('storage/' . $postImage) }}" alt="{{ $postTitle }}"
                            style="width:100%; height:auto; max-height:1000px">
                        <div class="card-body fs-5">
                            ชื่อโพสต์: {{ $postTitle }}
                        </div>
                        <div class="card-footer">
                            {{ $message }}
                        </div>
                    </div>
                </div>
                <a class="btn btn-danger mt-3" href="/">กลับไปหน้าหลัก</a>
            </div>
        </div>
    </div>
@endsection
