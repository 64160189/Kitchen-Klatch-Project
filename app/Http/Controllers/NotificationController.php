<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function read($id)
    {
        $notification = Notification::find($id);

        if ($notification) {
            $notification->is_read = true; // เปลี่ยนสถานะเป็นอ่าน
            $notification->save(); // บันทึกการเปลี่ยนแปลง
        }

        return redirect()->route('post.show', $notification->post_id); // นำไปยังโพสต์
    }
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc') // เรียงจากใหม่ไปเก่า
            ->paginate(10); // ใช้ paginate แทน get เพื่อแบ่งหน้า

        return view('notifications.index', compact('notifications'));
    }


    public function showNotifications()
    {
        // แสดงเฉพาะ 10 การแจ้งเตือนล่าสุดที่ยังไม่อ่าน
        $notifications = auth()->user()->notifications()
            ->where('is_read', false) // เฉพาะการแจ้งเตือนที่ยังไม่อ่าน
            ->orderBy('created_at', 'desc')
            ->take(10) // จำกัดการแสดงผลที่ 10 รายการ
            ->get();

        return view('partials.notifications', compact('notifications'));
    }



}