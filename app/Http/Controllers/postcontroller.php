<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PostModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Feed;

class PostController extends Controller
{
    public function showPost()
    {
        $posts = PostModel::orderBy('id', 'desc')->paginate(5);

        foreach ($posts as $post) {
            $post->ingrediant = json_decode($post->ingrediant, true) ?? [];
            $post->htc = json_decode($post->htc, true) ?? [];
        }

        return view('home', compact('posts'));
    }


    public function storePost(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'ingrediant' => 'required|string',
            'htc' => 'required|string',
            'youtube_link' => 'nullable|url'
        ]);

        $ingrediants = explode(PHP_EOL, $request->input('ingrediant'));
        $htc = explode(PHP_EOL, $request->input('htc'));

        $imagePath = $request->hasFile('image') ? $request->file('image')->store('images', 'public') : '';

        PostModel::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'image' => $imagePath,
            'ingrediant' => json_encode($ingrediants, JSON_UNESCAPED_UNICODE),
            'htc' => json_encode($htc, JSON_UNESCAPED_UNICODE),
            'youtube_link' => $request->input('youtube_link'),
            'user_id' => Auth::id(),
        ]);

        return redirect('/')->with('success', 'Post created successfully.');
    }

    public function showFullPost($id)
    {
        $post = PostModel::with('user')->find($id);

        if (!$post) {
            return redirect('/')->with('error', 'Post not found.');
        }

        $post->ingrediant = json_decode($post->ingrediant, true) ?? [];
        $post->htc = json_decode($post->htc, true) ?? [];

        return view('posts.fullpost', compact('post'));
    }

    public function deletePost($id)
    {
        $post = PostModel::findOrFail($id);

        if (!(Auth::user()->is_admin || Auth::user()->id == $post->user_id)) {
            return redirect('/')->with('error', "you don't have permission to do that.");
        }

        $post->delete();

        return redirect('/')->with('success', 'Post deleted successfully.');
    }

    public function editPost($id)
    {
        $post = PostModel::findOrFail($id);

        if (Auth::user()->id !== $post->user_id) {
            return redirect('/')->with('error', 'You do not have permission to do that.');
        }

        $post->ingrediant = json_decode($post->ingrediant, true) ?? [];
        $post->htc = json_decode($post->htc, true) ?? [];
        return view('posts.edit_post', compact('post'));
    }

    public function updatePost(Request $request, $id)
    {
        $post = PostModel::findOrFail($id);

        if (Auth::user()->id !== $post->user_id) {
            return redirect('/')->with('error', 'You do not have permission to do that.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'ingrediant' => 'required|string',
            'htc' => 'required|string',
            'youtube_link' => 'nullable|url'
        ]);

        $ingrediants = explode(PHP_EOL, $request->input('ingrediant'));
        $htc = explode(PHP_EOL, $request->input('htc'));

        $imagePath = $request->hasFile('image') ? $request->file('image')->store('images', 'public') : $post->image;

        $post->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'image' => $imagePath,
            'ingrediant' => json_encode($ingrediants, JSON_UNESCAPED_UNICODE),
            'htc' => json_encode($htc, JSON_UNESCAPED_UNICODE),
            'youtube_link' => $request->input('youtube_link'),
        ]);

        return redirect()->route('post.show', ['id' => $id])->with('success', 'Post updated successfully.');
    }

    public function fetchUserPosts($user_id)
    {
        $posts = PostModel::where('user_id', $user_id)->orderBy('id', 'desc')->paginate(5);

        foreach ($posts as $post) {
            $post->ingrediant = json_decode($post->ingrediant, true) ?? [];
            $post->htc = json_decode($post->htc, true) ?? [];
        }

        return response()->json($posts->toArray());
    }

    public function titleSearch(Request $request)
    {
        $query = $request->input('query');
        $posts = PostModel::where('title', 'like', '%' . $query . '%')->orderBy('id', 'desc')->paginate(5);

        foreach ($posts as $post) {
            $post->ingrediant = json_decode($post->ingrediant, true) ?? [];
            $post->htc = json_decode($post->htc, true) ?? [];
        }

        return response()->json($posts->toArray());
    }

    public function searchByIngredients(Request $request)
    {
        $query = $request->input('query');
        $posts = PostModel::where('ingrediant', 'like', '%' . $query . '%')->orderBy('id', 'desc')->paginate(5);

        foreach ($posts as $post) {
            $post->ingrediant = json_decode($post->ingrediant, true) ?? [];
            $post->htc = json_decode($post->htc, true) ?? [];
        }

        return response()->json($posts->toArray());
    }

    public function fetchTitle(Request $request)
    {
        $query = $request->input('query');
        $posts = PostModel::where('title', 'like', '%' . $query . '%')->orderBy('id', 'desc')->paginate(5);

        foreach ($posts as $post) {
            $post->ingrediant = json_decode($post->ingrediant, true) ?? [];
            $post->htc = json_decode($post->htc, true) ?? [];
        }

        return response()->json($posts->toArray());
    }

    public function fetchIngredients(Request $request)
    {
        $query = $request->input('query');
        $posts = PostModel::where('ingrediant', 'like', '%' . $query . '%')->orderBy('id', 'desc')->paginate(5);

        foreach ($posts as $post) {
            $post->ingrediant = json_decode($post->ingrediant, true) ?? [];
            $post->htc = json_decode($post->htc, true) ?? [];
        }

        return response()->json($posts->toArray());
    }

    public function titleSearchPredictions(Request $request)
    {
        // Implement title search predictions logic here
    }

    public function ingredientsSearchPredictions(Request $request)
    {
        // Implement ingredients search predictions logic here
    }

    public function storeIngredients(Request $request)
    {
        $ingredients = $request->input('ingredients');
        $request->session()->put('ingredients', $ingredients);

        return response()->json(['success' => true]);
    }
    public function shareToFeed(Request $request, $postId)
{
    // ดึงข้อมูลผู้ใช้และโพสต์
    $user = Auth::user();
    $post = PostModel::findOrFail($postId);

    // ตรวจสอบว่าผู้ใช้ได้แชร์โพสต์นี้แล้วหรือยัง
    $alreadyShared = Feed::where('user_id', $user->id)->where('post_id', $post->id)->exists();

    if (!$alreadyShared) {
        // บันทึกโพสต์ลงฟีดของผู้ใช้
        Feed::create([
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);

        return redirect()->back()->with('success', 'แชร์โพสต์ไปยังฟีดของคุณแล้ว!');
    } else {
        return redirect()->back()->with('error', 'คุณได้แชร์โพสต์นี้แล้ว!');
    }
}
public function fetchPosts(Request $request, $userId)
{
    $type = $request->query('type', 'my');
    $user = User::findOrFail($userId);

    if ($type === 'my') {
        // ดึงโพสต์ของผู้ใช้
        $posts = PostModel::where('user_id', $userId)->paginate(5);
    } elseif ($type === 'shared') {
        // ดึงโพสต์ที่ผู้ใช้แชร์
        $posts = $user->sharedPosts()->paginate(5); // สมมติว่ามีความสัมพันธ์สำหรับโพสต์ที่แชร์
    }

    // ตรวจสอบว่าเป็นการร้องขอแบบ Ajax
    if ($request->ajax()) {
        return response()->json($posts);
    }

    return view('user.posts', compact('posts', 'user'));
}


}
