<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PostModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\User;
use App\Models\Feed;
use App\Models\Notification;
use App\Models\Report;

class postcontroller extends Controller
{
    public function showPost()
    {
        // Fetch posts ordered by id in descending order and paginate
        $posts = PostModel::with('user')->orderBy('id', 'desc')->paginate(5);

        // Decode the JSON fields for each post
        foreach ($posts as $post) {
            $post->ingrediant = json_decode($post->ingrediant, true);
            $post->htc = json_decode($post->htc, true);
        }

        //random recomand
        $randomPost1 = PostModel::with('user')->inRandomOrder()->first();
        // Decode JSON fields for the random post
        if ($randomPost1) {
            $randomPost1->ingrediant = is_array(json_decode($randomPost1->ingrediant)) ? implode(', ', json_decode($randomPost1->ingrediant)) : $randomPost1->ingrediant;
            $randomPost1->htc = json_decode($randomPost1->htc, true);
        }
        //random recomand
        $randomPost2 = PostModel::with('user')->inRandomOrder()->first();
        // Decode JSON fields for the random post
        if ($randomPost2) {
            $randomPost2->ingrediant = is_array(json_decode($randomPost2->ingrediant)) ? implode(', ', json_decode($randomPost2->ingrediant)) : $randomPost2->ingrediant;
            $randomPost2->htc = json_decode($randomPost2->htc, true);
        }

        return view('home', compact('posts', 'randomPost1', 'randomPost2'));
    }

    public function fetchPosts(Request $request)
    {
        // Fetch posts ordered by id in descending order and paginate
        $posts = PostModel::with('user')->orderBy('id', 'desc')->paginate(5);

        // Decode the JSON fields for each post
        foreach ($posts as $post) {
            $post->ingrediant = json_decode($post->ingrediant, true);
            $post->htc = json_decode($post->htc, true);
        }

        // Return the posts as JSON
        return response()->json($posts);
    }

    public function storePost(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'ingrediant' => 'required|string',
            'htc' => 'required|string',
            'youtube_link' => 'nullable|url',
            'time_to_cook' => 'required|integer|min:1',  // Validation for time
            'level_of_cook' => 'required|integer|between:1,5', // Validation for level
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
            'time_to_cook' => $request->input('time_to_cook'),
            'level_of_cook' => $request->input('level_of_cook'),
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

        return view('posts/fullpost', compact('post'));
    }

    public function deletePost(Request $request, $id)
    {
        $post = PostModel::findOrFail($id);

        // Check if the authenticated user is the owner or an admin
        if (!(Auth::user()->is_admin || Auth::user()->id == $post->user_id)) {
            return redirect('/')->with('error', "You don't have permission to do that.");
        }

        if (!(Auth::user()->is_admin)) {
            // Delete the post
            $post->delete();
            return redirect('/')->with('success', "Post deleted successfully.");
        } else {
            $reason = $request->input('reason'); // Get the reason from the request
            $postTitle = $post->title; // Save the post title before deletion
            $postImage = $post->image; // Save the post image before deletion

            // Create a notification for the post owner with post details
            Notification::create([
                'user_id' => $post->user_id,
                'post_id' => $post->id, // Save post ID for reference (even if deleted)
                'post_title' => $postTitle, // Save the post title before deletion
                'post_image' => $postImage, // Save the post image before deletion
                'message' => "Your post was deleted by an admin. Reason: {$reason}",
                'is_read' => false,
            ]);

            // Delete the post
            $post->delete();
            return response()->json(['success' => true, 'message' => 'Post deleted successfully.']);
        }
    }


    public function editPost($id)
    {
        $post = PostModel::findOrFail($id);

        // Check if the authenticated user is the owner
        if (Auth::user()->id !== $post->user_id) {
            return redirect('/')->with('error', "you don't have permission to do that.");
        }

        $post->ingrediant = json_decode($post->ingrediant, true);
        $post->htc = json_decode($post->htc, true);
        return view('posts.edit_post', compact('post'));
    }

    public function updatePost(Request $request, $id)
    {
        $post = PostModel::findOrFail($id);

        // Check if the authenticated user is the owner
        if (Auth::user()->id !== $post->user_id) {
            return redirect('/')->with('error', "you don't have permission to do that.");
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'ingrediant' => 'required|string',
            'htc' => 'required|string',
            'youtube_link' => 'nullable|url',
            'time_to_cook' => 'required|integer|min:1',  // Validation for time
            'level_of_cook' => 'required|integer|between:1,5', // Validation for level
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
            'time_to_cook' => $request->input('time_to_cook'),
            'level_of_cook' => $request->input('level_of_cook'),
        ]);

        return redirect()->route('post.show', ['id' => $id])->with('success', 'Post updated successfully.');
    }

    public function titleSearch(Request $request)
    {
        $search = $request->search;
        $sort = $request->sort ?? 'id';
        $order = $request->order ?? 'desc';

        // Search posts by title
        $posts = PostModel::where('title', 'like', "%$search%")->orderBy($sort, $order)->paginate(5);

        // Decode JSON fields for each post
        foreach ($posts as $post) {
            $post->ingrediant = json_decode($post->ingrediant, true);
            $post->htc = json_decode($post->htc, true);
        }

        $results = $posts->total();

        // Return the view with the search results
        return view('posts/search_title_results', compact('posts', 'search', 'sort', 'order', 'results'));
    }

    public function fentchTitle(Request $request)
    {
        $search = $request->input('search', '');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');

        // Search posts by title
        $posts = PostModel::where('title', 'like', "%$search%")
            ->orderBy($sort, $order)
            ->paginate(5);

        // Decode JSON fields for each post
        foreach ($posts as $post) {
            $post->ingrediant = json_decode($post->ingrediant, true);
            $post->htc = json_decode($post->htc, true);
        }

        // Return the posts as JSON
        return response()->json($posts->toArray());
    }

    public function titleSearchPredictions(Request $request)
    {
        $search = $request->get('search');
        $results = PostModel::where('title', 'like', "%$search%")
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get(['id', 'title']);

        return response()->json($results);
    }

    public function searchByIngredients(Request $request)
    {
        $inputIngredients = $request->input('ingredients', '');
        $ingredientsArray = array_filter(explode(',', $inputIngredients));

        $sort = $request->input('sort', 'matching');
        $order = $request->input('order', 'desc');

        if (empty($ingredientsArray)) {
            // If no ingredients are provided, fetch all posts
            $posts = PostModel::orderBy('id', $order)->paginate(5);
        } else {
            // Fetch posts that have ingredients similar to the input ingredients
            $allPosts = PostModel::where(function ($query) use ($ingredientsArray) {
                foreach ($ingredientsArray as $ingredient) {
                    $query->orWhere('ingrediant', 'like', '%' . trim($ingredient) . '%');
                }
            })->get();

            // Calculate matching count for each post
            foreach ($allPosts as $post) {
                $postIngredients = json_decode($post->ingrediant, true);
                $matches = array_filter($ingredientsArray, function ($ingredient) use ($postIngredients) {
                    foreach ((array) $postIngredients as $postIngredient) {
                        if (stripos($postIngredient, $ingredient) !== false) {
                            return true;
                        }
                    }
                    return false;
                });
                $post->matching = count($matches);
            }

            // Sort the posts by matching count or any other selected sort option
            $sortedPosts = $allPosts->sortBy([
                [$sort, $order]
            ]);

            // Paginate the sorted posts manually
            $page = LengthAwarePaginator::resolveCurrentPage();
            $perPage = 5;
            $posts = new LengthAwarePaginator(
                $sortedPosts->forPage($page, $perPage)->values(),
                $sortedPosts->count(),
                $perPage,
                $page,
                ['path' => LengthAwarePaginator::resolveCurrentPath()]
            );
        }

        // Decode JSON fields for each post
        foreach ($posts as $post) {
            $post->ingrediant = json_decode($post->ingrediant, true);
            $post->htc = json_decode($post->htc, true);
        }

        $results = $posts->total();

        // Return the view with the search results
        return view('posts.search_ingredients_results', compact('posts', 'sort', 'order', 'ingredientsArray', 'results'));
    }

    public function fentchIngredients(Request $request)
    {
        $inputIngredients = $request->input('ingredients', '');
        $ingredientsArray = array_filter(explode(',', $inputIngredients));

        $sort = $request->input('sort', 'matching');
        $order = $request->input('order', 'desc');

        if (empty($ingredientsArray)) {
            // If no ingredients are provided, fetch all posts
            $posts = PostModel::orderBy('id', $order)->paginate(5);
        } else {
            // Fetch posts that have ingredients similar to the input ingredients
            $allPosts = PostModel::where(function ($query) use ($ingredientsArray) {
                foreach ($ingredientsArray as $ingredient) {
                    $query->orWhere('ingrediant', 'like', '%' . trim($ingredient) . '%');
                }
            })->get();

            // Calculate matching count for each post
            foreach ($allPosts as $post) {
                $postIngredients = json_decode($post->ingrediant, true);
                $matches = array_filter($ingredientsArray, function ($ingredient) use ($postIngredients) {
                    foreach ((array) $postIngredients as $postIngredient) {
                        if (stripos($postIngredient, $ingredient) !== false) {
                            return true;
                        }
                    }
                    return false;
                });
                $post->matching = count($matches);
            }

            // Sort the posts by matching count or any other selected sort option
            $sortedPosts = $allPosts->sortBy([
                [$sort, $order]
            ]);

            // Paginate the sorted posts manually
            $page = LengthAwarePaginator::resolveCurrentPage();
            $perPage = 5;
            $posts = new LengthAwarePaginator(
                $sortedPosts->forPage($page, $perPage)->values(),
                $sortedPosts->count(),
                $perPage,
                $page
            );
        }

        // Decode JSON fields for each post
        foreach ($posts as $post) {
            $post->ingrediant = json_decode($post->ingrediant, true);
            $post->htc = json_decode($post->htc, true);
        }

        // Return the posts as JSON
        return response()->json($posts);
    }

    public function ingredientsSearchPredictions(Request $request)
    {
        $search = $request->get('search');

        // Assuming 'ingrediant' is a comma-separated string
        $results = PostModel::where('ingrediant', 'LIKE', "%$search%")
            ->select('ingrediant')
            ->distinct()
            ->get();

        // Splitting the ingredient field to return individual ingredients
        $suggestions = [];
        foreach ($results as $result) {
            $ingredients = explode(',', str_replace(['[', ']', '"'], '', $result->ingrediant));
            foreach ($ingredients as $ingredient) {
                $ingredient = trim($ingredient);
                if (stripos($ingredient, $search) !== false && !in_array($ingredient, $suggestions)) {
                    $suggestions[] = $ingredient;
                }
                if (count($suggestions) >= 5) {
                    break 2;
                }
            }
        }

        return response()->json($suggestions);
    }

    public function storeIngredients(Request $request)
    {
        $ingredients = $request->input('ingredients');
        session(['ingredients' => $ingredients]);
        return response()->json(['status' => 'success']);
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

    public function randomRecipe(Request $request)
    {
        $ingredients = $request->input('ingredients', []);

        if (empty($ingredients)) {
            return redirect()->back()->with('error', 'ไม่พบวัตถุดิบ!');
        }

        // Fetch posts that have at least one matching ingredient
        $matchingPosts = PostModel::where(function ($query) use ($ingredients) {
            foreach ($ingredients as $ingredient) {
                $query->orWhere('ingrediant', 'like', '%' . trim($ingredient) . '%');
            }
        })->get();

        if ($matchingPosts->isEmpty()) {
            return redirect()->back()->with('error', 'ไม่พบสูตรอาหารที่ตรงกับสิ่งที่คุณตามหาอยู่!');
        }

        // Calculate matching count for each post
        foreach ($matchingPosts as $post) {
            $postIngredients = json_decode($post->ingrediant, true);
            $matches = array_filter($ingredients, function ($ingredient) use ($postIngredients) {
                foreach ((array) $postIngredients as $postIngredient) {
                    if (stripos($postIngredient, $ingredient) !== false) {
                        return true;
                    }
                }
                return false;
            });
            $post->matching = count($matches);
        }

        // Sort posts by matching count (descending) and take top 10
        $topPosts = $matchingPosts->sortByDesc('matching')->take(10);

        // Randomly select one post from the top 10
        $randomPost = $topPosts->random();

        return response()->json(['postId' => $randomPost->id]);
    }

    public function report(Request $request, $id)
    {
        $request->validate([
            'reportReason' => 'required|string',
            'additionalInfo' => 'nullable|string',
        ]);
        Report::create([
            'post_id' => $id,
            'user_id' => auth()->id(),
            'reason' => $request->input('reportReason'),
            'additional_info' => $request->input('additionalInfo'),
        ]);
        return redirect()->back()->with('success', 'รายงานของคุณถูกส่งแล้ว');
    }

    public function showReportedPosts(Request $request)
    {
        $reportedPosts = PostModel::where('is_reported', true)->paginate(10); // แก้ไขให้ตรงกับโครงสร้างฐานข้อมูลของคุณ
        return view('admin.reported-posts', compact('reportedPosts'));
    }
}
