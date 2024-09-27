<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PostModel;
use App\Models\User;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function adminHome() {

        $AllPosts = DB::table('post_models')->count();
        $AllUsers = DB::table('users')->count();

        //user count
        $todayUsers = DB::table('users')->whereDate('created_at', '=', Carbon::today())->count();
        $last7dayUsers = DB::table('users')->whereDate('created_at', '>=', Carbon::today()->subdays(7))->count();
        $last30dayUsers = DB::table('users')->whereDate('created_at', '>=', Carbon::today()->subday(30))->count();

        //post count
        $todayPosts = DB::table('post_models')->whereDate('created_at', '=', Carbon::today())->count();
        $last7dayPosts = DB::table('post_models')->whereDate('created_at', '>=', Carbon::today()->subday(7))->count();
        $last30dayPosts = DB::table('post_models')->whereDate('created_at', '>=', Carbon::today()->subday(30))->count();

        // Initialize data for the last 7 days
        $usersByDay = collect([]);
        $postsByDay = collect([]);
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::today()->subDays($i)->format('Y-m-d');
            $usersByDay[$date] = 0; // Set initial count to 0
            $postsByDay[$date] = 0;
            }

            //USERS
        // Fetch users created in the last 7 days, grouped by date
        $usersData = DB::table('users')
            ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as date'), DB::raw('count(*) as count'))
            ->whereBetween('created_at', [Carbon::today()->subDays(7), Carbon::now()])
            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'))
            ->orderBy('date', 'asc')
            ->get();
        // Merge fetched data into the initialized usersByDay array
        foreach ($usersData as $data) {
            $usersByDay[$data->date] = $data->count;
        }
        // Convert to a format suitable for Chart.js
        $Ulabels = $usersByDay->keys();
        $Udata = $usersByDay->values();

            //POSTS
        // Fetch posts created in the last 7 days, grouped by date
        $postsData = DB::table('post_models')
            ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as date'), DB::raw('count(*) as count'))
            ->whereBetween('created_at', [Carbon::today()->subDays(7), Carbon::now()])
            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'))
            ->orderBy('date', 'asc')
            ->get();
        // Merge fetched data into the initialized usersByDay array
        foreach ($postsData as $data) {
            $postsByDay[$data->date] = $data->count;
        }
        // Convert to a format suitable for Chart.js
        $Plabels = $postsByDay->keys();
        $Pdata = $postsByDay->values();


        //dd($Plabels, $Pdata);

        return view('admin/adminHome', compact(
            'AllPosts', 'AllUsers', 'todayUsers', 'last7dayUsers', 'last30dayUsers',
            'todayPosts', 'last7dayPosts', 'last30dayPosts', 'Ulabels', 'Udata', 'Plabels', 'Pdata'
        ));
    }

    public function usersTable(Request $request) {
        $sort = $request->input('sort', 'id'); 
        $order = $request->input('order', 'desc');

        $users = User::orderBy($sort,$order)->paginate(5);
        $AllUsers = $users->total();

        return view('admin/usersTable', compact('users', 'AllUsers'));
    }

    public function postsTable(Request $request) {
        $sort = $request->input('sort', 'id'); 
        $order = $request->input('order', 'desc'); 
        
        $posts = PostModel::with('user')->orderBy($sort,$order)->paginate(5);
        $AllPosts = $posts->total();

        // Decode JSON fields for each post
        foreach ($posts as $post) {
            $post->ingrediant = json_decode($post->ingrediant, true);
            $post->htc = json_decode($post->htc, true);
        }

        return view('admin/postsTable', compact('posts', 'AllPosts'));
    }

    public function userSearch(Request $request) {
        $sort = $request->input('sort', 'id'); 
        $order = $request->input('order', 'desc');
        $search = $request->input('search','');

        $users = User::where(function($query) use ($search){
            $query->where('name','like',"%$search%")
            ->orwhere('id', 'like', "%$search%");
        })->orderBy($sort,$order)->paginate(5)->appends(['sort' => $sort, 'order' => $order, 'search' => $search]);

        $AllUsers = $users->total();

         // Return view with search results, current sorting, and search term
        return view('admin/usersTable', compact('users', 'AllUsers', 'sort', 'order', 'search'));
    }

    public function userSearchPredictions(Request $request) {
        $search = $request->get('search');
        $results = User::where('name', 'like', "%$search%")
            ->orWhere('id', 'like', "%$search%")
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get(['id', 'name']); // Fetch both 'id' and 'name'
    
        return response()->json($results);
    }    

    public function postSearch(Request $request) {
        $sort = $request->input('sort', 'id'); 
        $order = $request->input('order', 'desc');
        $search = $request->input('search','');

        $posts = PostModel::with('user')->where(function($query) use ($search){
            $query->where('title','like',"%$search%")
            ->orwhere('id', 'like', "%$search%");
        })->orderBy($sort,$order)->paginate(5)->appends(['sort' => $sort, 'order' => $order, 'search' => $search]);

        $AllPosts = $posts->total();

         // Return view with search results, current sorting, and search term
        return view('admin/postsTable', compact('posts', 'AllPosts', 'sort', 'order', 'search'));
    }

    public function postSearchPredictions(Request $request) {
        $search = $request->get('search');
        $results = PostModel::with('user')->where('title', 'like', "%$search%")
            ->orWhere('id', 'like', "%$search%")
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get(['id', 'title']); // Fetch both 'id' and 'title'
    
        return response()->json($results);
    }    

    
}
