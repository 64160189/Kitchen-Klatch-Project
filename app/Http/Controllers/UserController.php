<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function show(User $user)
    {
        $posts = $user->posts()->with('user')->orderBy('id', 'desc')->paginate(5);
        foreach ($posts as $post) {
            $post->ingrediant = json_decode($post->ingrediant, true);
            $post->htc = json_decode($post->htc, true);
        }
    
        // Log or inspect data for troubleshooting
        \Log::info($posts->toArray());
    
        return view('users.show', compact('user', 'posts'));
    }

    // load more their posts
    public function fetchUserPosts(User $user, Request $request)
    {
        $type = $request->input('type', 'my'); // Default to 'my' if type is not provided

        // Fetch posts based on type
        if ($type === 'shared') {
            // Logic to fetch shared posts (update this based on your application logic)
            $posts = $user->sharedPosts()->orderBy('id', 'desc')->paginate(5);
        } else {
            // Default to fetching the user's own posts
            $posts = $user->posts()->orderBy('id', 'desc')->paginate(5);
        }

        // Decode JSON fields
        foreach ($posts as $post) {
            $post->ingrediant = json_decode($post->ingrediant, true);
            $post->htc = json_decode($post->htc, true);
        }

        return response()->json($posts);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $editing = true;
        $posts = $user->posts()->orderBy('id', 'desc')->paginate(5);
        return view('users.edit', compact('user', 'editing', 'posts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(User $user)
    {
        $validated = request()->validate([
            'name' => 'required|min:3|max:40',
            'bio' => 'nullable|min:1|max:255',
            'image' => 'image|nullable'
        ]);

        if (request()->has('image')) {
            // ถ้ามีการอัปโหลดภาพใหม่
            $imagePath = request()->file('image')->store('profile', 'public');
            $validated['image'] = $imagePath;

            // ตรวจสอบให้แน่ใจว่า user->image ไม่เป็น null ก่อนลบ
            if ($user->image) {
                // ลบรูปภาพเก่า
                Storage::disk('public')->delete($user->image);
            }
        }

        // อัปเดตข้อมูลผู้ใช้
        $user->update($validated);

        return redirect()->route('profile')->with('success', 'Profile updated successfully!');
    }

    public function profile()
    {
        return $this->show(auth()->user());
    }

    public function showSidebar()
    {
        $user = Auth::user();
        $followingUsers = $user->followings()->limit(5)->get(); // Retrieve up to 5 followed users
        $hasMoreFollowings = $user->followings()->count() > 5; // Check if there are more than 5

        return view('home', compact('followingUsers', 'hasMoreFollowings'));
    }

    public function showAllFollowings(Request $request)
    {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');

        // Get the currently authenticated user’s followings
        $followingUsers = auth()->user()->followings()
            ->orderBy($sort, $order)
            ->paginate(5); // Paginate with 5 users per page

        return view('users.followingTable', compact('followingUsers'));
    }

    public function userSearch(Request $request)
    {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $search = $request->input('search', '');

        $followingUsers = auth()->user()->followings()
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('id', 'like', "%$search%");
            })
            ->orderBy($sort, $order)
            ->paginate(5) // Paginate with 5 users per page
            ->appends(['sort' => $sort, 'order' => $order, 'search' => $search]);

        return view('users.followingTable', compact('followingUsers', 'sort', 'order', 'search'));
    }

    public function userSearchPredictions(Request $request)
    {
        $search = $request->get('search');
        $results = auth()->user()->followings()->where('name', 'like', "%$search%")
            ->orWhere('id', 'like', "%$search%")
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get(['id', 'name']); // Fetch both 'id' and 'name'

        return response()->json($results);
    }
}
