<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SongSubCateRel;
use App\Models\SubCategory;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CategoriesController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SubCategory::query(); // Customize your query as needed

            return DataTables::of($data)
                ->make(true);
        }
        return view('user.category.index');
    }

    public function show(Request $request, string $code)
    {
        // Log::info("Received sub_category_code: " . $code);
        $subCategory = DB::table('sub_categories')->where('sub_category_code', $code)->first();

        if (!$subCategory) {
            abort(404, 'SubCategory not found.');
        }

        if ($request->ajax()) {
            $data = DB::table('song_sub_cate_rels')
                ->where('sub_category_code', $code)
                ->join('songs', 'songs.song_code', '=', 'song_sub_cate_rels.song_code')
                ->select('songs.song_code', 'songs.title_en', 'songs.title_gu')
                ->get();
            return DataTables::of($data)->make(true);
        }
        return view('user.category.show', compact('subCategory'));
    }
}
