<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\PostModel;
use App\Models\Notification; // เพิ่มการนำเข้า Notification
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, $postId)
    {
        $post = PostModel::find($postId);
        if (!auth()->check()) {
            return redirect()->back()->with('error', 'คุณต้องเข้าสู่ระบบก่อนจึงจะสามารถคอมเมนต์ได้!');
        }

        $request->validate([
            'content' => 'required|string|max:255',
        ]);

        if (!$post) {
            return redirect()->back()->with('error', 'ไม่พบโพสต์');
        }

        $comment = new Comment();
        $comment->post_id = $postId;
        $comment->content = $request->input('content');
        $comment->user_id = auth()->id(); // ตั้งค่า user_id ให้กับความคิดเห็น
        $comment->save();

        $userName = $comment->user->name;
        // สร้างการแจ้งเตือนสำหรับเจ้าของโพสต์
        $notification = new Notification();
        $notification->user_id = $post->user_id; // เจ้าของโพสต์
        $notification->post_id = $postId;
        $notification->message = "{$userName} ได้แสดงความคิดเห็นในโพสต์ของคุณ: " . $post->title; // เปลี่ยนข้อความที่แสดง
        $notification->is_read = false; // การแจ้งเตือนยังไม่ได้อ่าน
        $notification->notifiable_type = "comment";
        $notification->notifiable_id = 1;
        $notification->save();

        return redirect()->route('post.show', $post->id)->with('success', 'คอมเมนต์โพสต์สำเร็จ!');
    }


    public function show($postId)
    {
        $post = PostModel::with([
            'comments' => function ($query) {
                $query->orderBy('created_at', 'desc')->with('user');
            }
        ])->find($postId);

        if (!$post) {
            return response()->json(['message' => 'โพสต์ไม่พบ.'], 404);
        }

        return view('post.show', compact('post'));
    }



}
