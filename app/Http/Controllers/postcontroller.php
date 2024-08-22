<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PostModel;
use Illuminate\Support\Facades\Auth;


class postcontroller extends Controller
{
    public function showPost(){
        // Fetch posts ordered by id in descending order and paginate
        $posts = DB::table('post_models')->orderBy('id', 'desc')->paginate(5);

        // Decode the JSON fields for each post
        foreach ($posts as $post) {
            $post->ingrediant = json_decode($post->ingrediant, true);
            $post->htc = json_decode($post->htc, true);
        }

        return view('home', compact('posts'));
    }

    public function fetchPosts(Request $request){
        // Fetch posts ordered by id in descending order and paginate
        $posts = DB::table('post_models')->orderBy('id', 'desc')->paginate(5);

        // Decode the JSON fields for each post
        foreach ($posts as $post) {
            $post->ingrediant = json_decode($post->ingrediant, true);
            $post->htc = json_decode($post->htc, true);
        }

        // Return the posts as JSON
        return response()->json($posts);
    }

    public function storePost(Request $request) {
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

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }

        PostModel::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'image' => $imagePath ?? '',
            'ingrediant' => json_encode($ingrediants, JSON_UNESCAPED_UNICODE),
            'htc' => json_encode($htc, JSON_UNESCAPED_UNICODE),
            'youtube_link' => $request->input('youtube_link'),
            'user_id' => Auth::id(),
        ]);

        return redirect('/')->with('success', 'Post created successfully.');
    }

    public function showFullPost($id){
        $post = PostModel::with('user')->find($id);

        if (!$post) {
            return redirect('/')->with('error', 'Post not found.');
        }

        // Check if $post->ingrediant is a string before decoding
        if (is_string($post->ingrediant)) {
            $post->ingrediant = json_decode($post->ingrediant, true);
        }

        // Check if $post->htc is a string before decoding
        if (is_string($post->htc)) {
            $post->htc = json_decode($post->htc, true);
        }

        return view('posts/fullpost', compact('post'));
    }
    
    public function deletePost($id){
        $post = PostModel::findOrFail($id);

        // Check if the authenticated user is the owner or an admin
        if (!(Auth::user()->is_admin || Auth::user()->id == $post->user_id)) {
            return redirect('/')->with('error', 'you do not have permission to do that.');
        }

        $post->delete();

        return redirect('/')->with('success', 'Post deleted successfully.');
    }

    public function editPost($id) {
        $post = PostModel::findOrFail($id);

        // Check if the authenticated user is the owner
        if (Auth::user()->id !== $post->user_id) {
            return redirect('/')->with('error', 'you do not have permission to do that.');
        }

        $post->ingrediant = json_decode($post->ingrediant, true);
        $post->htc = json_decode($post->htc, true);
        return view('posts.edit_post', compact('post'));
    }

    public function updatePost(Request $request, $id) {
        $post = PostModel::findOrFail($id);

        // Check if the authenticated user is the owner
        if (Auth::user()->id !== $post->user_id) {
            return redirect('/')->with('error', 'you do not have permission to do that.');
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

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }

        $post->title = $request->input('title');
        $post->description = $request->input('description');
        if (isset($imagePath)) {
            $post->image = $imagePath;
        }
        $post->ingrediant = json_encode($ingrediants, JSON_UNESCAPED_UNICODE);
        $post->htc = json_encode($htc, JSON_UNESCAPED_UNICODE);
        $post->youtube_link = $request->input('youtube_link');
        $post->save();

        return redirect()->route('post.show', ['id' => $post->id])->with('success', 'Post updated successfully.');
    }

    public function titleSearch(Request $request){
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

        // Return the view with the search results
        return view('posts/search_title_results', compact('posts', 'search', 'sort', 'order'));
    }

    public function fentchTitle(Request $request){
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

    public function titleSearchPredictions(Request $request) {
        $search = $request->get('search');
        $results = PostModel::where('title', 'like', "%$search%")
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get(['id', 'title']);

        return response()->json($results);
    }

    public function searchByIngredients(Request $request) {
        $ingredients = explode(',', $request->get('ingredients'));
        $sort = $request->sort ?? 'id';
        $order = $request->order ?? 'desc';

        // Search posts by ingredients
        $posts = PostModel::where(function($query) use ($ingredients) {
            foreach ($ingredients as $ingredient) {
                $query->where('ingrediant', 'like', "%{$ingredient}%");
            }
        })->orderBy($sort, $order)->paginate(5);

        // Decode JSON fields for each post
        foreach ($posts as $post) {
            $post->ingrediant = json_decode($post->ingrediant, true);
            $post->htc = json_decode($post->htc, true);
        }

        // Return the view with the search results
        return view('posts.search_ingredients_results', compact('posts', 'sort', 'order', 'ingredients'));
    }

    public function fentchIngredients(Request $request) {
        $ingredients = array_filter(explode(',', $request->get('ingredients')));
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
    
        // Search posts by ingredients
        $posts = PostModel::where(function($query) use ($ingredients) {
            foreach ($ingredients as $ingredient) {
                $query->where('ingrediant', 'like', "%{$ingredient}%");
            }
        })->orderBy($sort, $order)->paginate(5);
    
        // Decode JSON fields for each post
        foreach ($posts as $post) {
            $post->ingrediant = json_decode($post->ingrediant, true);
            $post->htc = json_decode($post->htc, true);
        }
    
        // Return the posts as JSON
        return response()->json($posts);
    }    

    public function ingredientsSearchPredictions(Request $request) {
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

    public function storeIngredients(Request $request) {
        $ingredients = $request->input('ingredients');
        session(['ingredients' => $ingredients]);
        return response()->json(['status' => 'success']);
    }

}
