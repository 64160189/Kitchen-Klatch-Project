<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PostModel;

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

    public function storePost(Request $request){
        // Validate the request data
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'ingredient' => 'required|string',
            'htc' => 'required|string',
        ]);

        // Convert ingredients and htc to JSON format
        $ingredients = explode(PHP_EOL, $request->input('ingredient'));
        $htc = explode(PHP_EOL, $request->input('htc'));

        // Handle the image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }
        
        // Create a new post
        PostModel::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'image' => $imagePath ?? '',
            'ingrediant' => json_encode($ingredients),
            'htc' => json_encode($htc),
        ]);
        
        // Redirect to a success page or home
        return redirect('/')->with('success', 'Post created successfully.');
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

}
