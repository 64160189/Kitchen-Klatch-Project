@extends('layouts.app')

@section('content')
<style>
    .list-group-item {
        padding: 15px;
        /* เพิ่ม padding ให้ดูดีขึ้น */
    }

    .list-group-item-unread {
        background-color: #007bff;
        /* สีสำหรับแจ้งเตือนที่ยังไม่อ่าน */
        color: white;
        /* สีข้อความ */
    }

    .list-group-item-read {
        background-color: #e2e3e5;
        /* สีสำหรับแจ้งเตือนที่อ่านแล้ว */
        color: #212529;
        /* สีข้อความ */
    }

    .pagination {
        justify-content: center;
        /* จัดหน้า pagination ให้อยู่ตรงกลาง */
    }
</style>

<div class="container">
    <h2 class="text-center mb-4">แจ้งเตือน</h2>

    @if($notifications->isEmpty())
        <p class="text-center">ไม่มีแจ้งเตือน</p>
    @else
        <ul class="list-group">
            @foreach($notifications as $notification)
                <li class="list-group-item {{ $notification->is_read ? 'list-group-item-read' : 'list-group-item-unread' }}">
                    <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit"
                            style="background: none; border: none; padding: 0; color: inherit; text-decoration: underline; cursor: pointer;">
                            {{ $notification->message }}
                        </button>
                    </form>
                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                </li>
            @endforeach
        </ul>
        <!-- Pagination Links -->
        <div class="mt-4 d-flex justify-content-center">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection