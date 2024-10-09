<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CateSubCateRel;
use App\Models\Configuration;
use App\Models\Song;
use App\Models\SongSubCateRel;
use App\Models\SubCategory;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SubCategory::query(); // Customize your query as needed

            return DataTables::of($data)
                ->make(true);
        }
        return view('admin.subCategory.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.subCategory.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'sub_category_en' => 'required|string',
            'sub_category_gu' => 'required|string',
            'category_code' => 'required|array',
            'category_code.*' => 'exists:categories,category_code',
        ]);

        //get category prefix from configuraton table
        $config = Configuration::where('key', 'sub_category_prefix')->value('value');
        // dd($config);
        // Find the last category with the same prefix
        $lastSubCategory = SubCategory::where('sub_category_code', 'LIKE', "$config%")
            ->orderBy('sub_category_code', 'desc')
            ->first();

        // Determine the new category code
        if ($lastSubCategory) {
            // Extract the numeric part and increment it
            $lastNumber = intval(substr($lastSubCategory->sub_category_code, strlen($config)));
            $newSubCategoryCode = $config . ($lastNumber + 1);
        } else {
            // No category exists with this prefix, start with the prefix followed by 1
            $newSubCategoryCode = $config . '1';
        }

        $subCate = SubCategory::create([
            'sub_category_code' => $newSubCategoryCode,
            'sub_category_en' => $request->sub_category_en,
            'sub_category_gu' => $request->sub_category_gu
        ]);

        foreach ($request->category_code as $categoryCode) {
            CateSubCateRel::create([
                'category_code' => $categoryCode,
                'sub_category_code' => $subCate->sub_category_code,
            ]);
        }

        return redirect()->route('admin.subCategories.index')->with('success', 'Sub Category added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $sub_category_code)
    {
        // dump($sub_category_code);
        // $category = Category::findOrFail($sub_category_code);
        $subCategory = SubCategory::where('sub_category_code', $sub_category_code)->firstOrFail();
        // dump($subCategory);

        if ($request->ajax()) {
            $data = SongSubCateRel::where('sub_category_code', $sub_category_code)
                ->join('songs', 'songs.song_code', '=', 'song_sub_cate_rels.song_code')
                ->select('songs.song_code', 'songs.title_en') // only select necessary fields
                ->get();

            return DataTables::of($data)
                ->make(true);
        }
        // dd($category);
        return view('admin.subCategory.show', compact('subCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $sub_category_code)
    {
        $subCategory = SubCategory::where('sub_category_code', $sub_category_code)->firstOrFail();

        // Pass the category data to the edit view
        return view('admin.subCategory.edit', compact('subCategory'));
    }

    public function fetchAssociatedSongs(Request $request, string $sub_category_code)
    {
        // dd($sub_category_code);
        $data = SongSubCateRel::where('sub_category_code', $sub_category_code)
            ->join('songs', 'songs.song_code', '=', 'song_sub_cate_rels.song_code')
            ->select('songs.song_code', 'songs.title_en');
        // dd($sub_category_code);
        return DataTables::of($data)->make(true);
    }

    public function fetchRemainingSongs(Request $request, string $sub_category_code)
    {
        $data = Song::whereNotIn('song_code', function ($query) use ($sub_category_code) {
            $query->select('song_code')
                ->from('song_sub_cate_rels')
                ->where('sub_category_code', $sub_category_code);
        })->select('song_code', 'title_en');

        return DataTables::of($data)->make(true);
    }

    public function addSongToCategory(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'song_code' => 'required|exists:songs,song_code',
            'sub_category_code' => 'required|exists:sub_categories,sub_category_code',
        ]);

        // Create the relationship
        SongSubCateRel::create([
            'song_code' => $request->song_code,
            'sub_category_code' => $request->sub_category_code,
        ]);

        return redirect()->back()->with('success', 'Song added to category successfully');
    }

    public function removeSongFromCategory(Request $request, $song_code)
    {
        // dump($song_code);
        $relationship = SongSubCateRel::where('song_code', $song_code)
            ->where('sub_category_code', $request->sub_category_code)
            ->first();
        // dump($relationship);
        if ($relationship) {
            $relationship->delete();
            return redirect()->back()->with('success', 'Song removed from category successfully');
        }

        return redirect()->back()->with('error', 'Song not found in this category');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $sub_category_code)
    {
        // dd($request->all());
        $request->validate([
            'sub_category_en' => 'required|string',
            'sub_category_gu' => 'required|string'
        ]);

        $category = SubCategory::where('sub_category_code', $sub_category_code)->firstOrFail();

        $category->update([
            'sub_category_en' => $request->sub_category_en,
            'sub_category_gu' => $request->sub_category_gu
        ]);

        return redirect()->back()->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $sub_category_code)
    {
        $category = SubCategory::where('sub_category_code', $sub_category_code)->firstOrFail();

        $category->songs()->detach();

        // Delete the categories
        $category->delete();

        // Return a response indicating success
        return redirect()->back()->with(['success' => 'Category deleted successfully']);
    }
}
