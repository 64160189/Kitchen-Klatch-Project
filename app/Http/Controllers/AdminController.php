<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PostModel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

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
    public function adminHome()
    {

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
            'AllPosts',
            'AllUsers',
            'todayUsers',
            'last7dayUsers',
            'last30dayUsers',
            'todayPosts',
            'last7dayPosts',
            'last30dayPosts',
            'Ulabels',
            'Udata',
            'Plabels',
            'Pdata'
        ));
    }

    public function usersTable(Request $request)
    {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');

        $users = User::orderBy($sort, $order)->paginate(5);
        $AllUsers = $users->total();

        return view('admin/usersTable', compact('users', 'AllUsers'));
    }

    public function postsTable(Request $request)
    {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');

        $posts = PostModel::with('user')->orderBy($sort, $order)->paginate(5);
        $AllPosts = $posts->total();

        // Decode JSON fields for each post
        foreach ($posts as $post) {
            $post->ingrediant = json_decode($post->ingrediant, true);
            $post->htc = json_decode($post->htc, true);
        }

        return view('admin/postsTable', compact('posts', 'AllPosts'));
    }

    public function userSearch(Request $request)
    {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $search = $request->input('search', '');

        $users = User::where(function ($query) use ($search) {
            $query->where('name', 'like', "%$search%")
                ->orwhere('id', 'like', "%$search%");
        })->orderBy($sort, $order)->paginate(5)->appends(['sort' => $sort, 'order' => $order, 'search' => $search]);

        $AllUsers = $users->total();

        // Return view with search results, current sorting, and search term
        return view('admin/usersTable', compact('users', 'AllUsers', 'sort', 'order', 'search'));
    }

    public function userSearchPredictions(Request $request)
    {
        $search = $request->get('search');
        $results = User::where('name', 'like', "%$search%")
            ->orWhere('id', 'like', "%$search%")
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get(['id', 'name']); // Fetch both 'id' and 'name'

        return response()->json($results);
    }

    public function postSearch(Request $request)
    {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $search = $request->input('search', '');

        $posts = PostModel::with('user')->where(function ($query) use ($search) {
            $query->where('title', 'like', "%$search%")
                ->orwhere('id', 'like', "%$search%");
        })->orderBy($sort, $order)->paginate(5)->appends(['sort' => $sort, 'order' => $order, 'search' => $search]);

        $AllPosts = $posts->total();

        // Return view with search results, current sorting, and search term
        return view('admin/postsTable', compact('posts', 'AllPosts', 'sort', 'order', 'search'));
    }

    public function postSearchPredictions(Request $request)
    {
        $search = $request->get('search');
        $results = PostModel::with('user')->where('title', 'like', "%$search%")
            ->orWhere('id', 'like', "%$search%")
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get(['id', 'title']); // Fetch both 'id' and 'title'

        return response()->json($results);
    }

    public function viewReportedPosts() {
        // ดึงโพสต์ที่ถูกรายงานจากฐานข้อมูล
        $reportedPosts = DB::table('post_models')
            ->join('reports', 'post_models.id', '=', 'reports.post_id')
            ->select('post_models.*', 'reports.reason', 'reports.additional_info', 'reports.user_id as reported_by')
            ->get()
            ->map(function ($post) {
                // เปลี่ยนเหตุผลการรายงานตามเงื่อนไข
                switch ($post->reason) {
                    case 'inappropriate_content':
                        $post->reason = 'เนื้อหาที่ไม่เหมาะสม: โพสต์มีข้อความหรือภาพที่เป็นการล่วงละเมิด เหยียดหยาม หรือสร้างความเกลียดชัง';
                        break;
                    case 'inappropriate_image_video':
                        $post->reason = 'ภาพหรือวิดีโอที่ไม่เหมาะสม: การใช้ภาพหรือวิดีโอที่ล่อแหลม รุนแรง หรือผิดกฎหมาย';
                        break;
                    case 'copyright_infringement':
                        $post->reason = 'การละเมิดลิขสิทธิ์: โพสต์ที่ใช้รูปภาพ วิดีโอ หรือเนื้อหาสูตรอาหารที่ละเมิดลิขสิทธิ์โดยไม่ได้รับอนุญาต';
                        break;
                    case 'spam':
                        $post->reason = 'สแปม: โพสต์ที่มีลักษณะเป็นการโฆษณาสินค้าหรือบริการที่ไม่เกี่ยวข้องกับอาหารซ้ำ ๆ หรือส่งเป็นจำนวนมาก';
                        break;
                    case 'scam':
                        $post->reason = 'การหลอกลวง: โพสต์ที่มีเจตนาให้ข้อมูลผิด ๆ หรือเป็นการหลอกลวงผู้ใช้งาน เช่น สูตรอาหารที่ไม่ถูกต้องหรือเป็นอันตราย';
                        break;
                    case 'off_topic':
                        $post->reason = 'เนื้อหาไม่ตรงประเด็น: โพสต์ที่ไม่เกี่ยวข้องกับเนื้อหาอาหาร เช่น โพสต์เกี่ยวกับหัวข้ออื่นที่ไม่เกี่ยวข้องกับแอป';
                        break;
                    case 'privacy_violation':
                        $post->reason = 'การละเมิดความเป็นส่วนตัว: โพสต์ที่เปิดเผยข้อมูลส่วนบุคคลของผู้อื่น เช่น ชื่อ ที่อยู่ หรือข้อมูลส่วนบุคคลอื่นๆ โดยไม่ได้รับอนุญาต';
                        break;
                    case 'offensive_language':
                        $post->reason = 'เนื้อหาที่ไม่สุภาพ: โพสต์ที่ใช้ภาษาหยาบคายหรือลามกอนาจาร';
                        break;
                    case 'misinformation':
                        $post->reason = 'การบิดเบือนข้อมูล: โพสต์ที่นำเสนอข้อมูลเกี่ยวกับอาหารที่ไม่ถูกต้อง ซึ่งอาจก่อให้เกิดความสับสนหรืออันตราย';
                        break;
                }
                return $post;
            });
    
        return view('admin.reportedPosts', compact('reportedPosts'));
    }

    public function deleteUser(Request $request, $id)
    {
        // Find the user by ID
        $user = User::findOrFail($id);

        // Check if the authenticated user is an admin
        if (!Auth::user()->is_admin) {
            return redirect('/')->with('error', "You don't have permission to do that.");
        }

        // Delete the user
        $user->delete();

        return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
    }
}
