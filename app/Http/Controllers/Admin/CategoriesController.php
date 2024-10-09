<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CateSubCateRel;
use App\Models\Configuration;
use App\Models\Song;
use App\Models\SongCategoryRel;
use App\Models\SubCategory;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Category::query(); // Customize your query as needed

            return DataTables::of($data)
                //     ->addColumn('action', function ($row) {
                //         $editButton = '<a href="' . route('admin.categories.edit', $row->id) . '" class="btn btn-warning btn-sm ml-2">Edit</a>';
                //         $viewButton = '<a href="' . route('admin.categories.show', $row->id) . '" class="btn btn-info btn-sm">View</a>';

                //         // Delete button with form
                //         $deleteButton = '
                //     <form action="' . route('admin.categories.destroy', $row->id) . '" method="POST" style="display:inline-block;" class="delete-form">
                //         ' . csrf_field() . '
                //         ' . method_field('DELETE') . '
                //         <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to delete this categories?\')">Delete</button>
                //     </form>
                // ';

                //         return $viewButton . $editButton . $deleteButton;
                //     })
                // <a href="' . route('categories.show', $row->id) . '" class="btn btn-info btn-sm">View</a>

                ->make(true);
        }
        // return redirect()->route('');
        return view('admin.category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_en' => 'required|string',
            'category_gu' => 'required|string'
        ]);

        //get category prefix from configuraton table
        $config = Configuration::where('key', 'category_prefix')->value('value');
        // dd($config);
        // Find the last category with the same prefix
        $lastCategory = Category::where('category_code', 'LIKE', "$config%")
            ->orderBy('category_code', 'desc')
            ->first();

        // Determine the new category code
        if ($lastCategory) {
            // Extract the numeric part and increment it
            $lastNumber = intval(substr($lastCategory->category_code, strlen($config)));
            $newCategoryCode = $config . ($lastNumber + 1);
        } else {
            // No category exists with this prefix, start with the prefix followed by 1
            $newCategoryCode = $config . '1';
        }

        Category::create([
            'category_code' => $newCategoryCode,
            'category_en' => $request->category_en,
            'category_gu' => $request->category_gu
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category added successfully!');
    }


    // public function show(Request $request, string $category_code)
    // {
    //     // $category = Category::findOrFail($category_code);
    //     $category = Category::where('category_code', $category_code)->firstOrFail();

    //     if ($request->ajax()) {
    //         $data = SongCategoryRel::where('category_code', $category_code)
    //             ->join('songs', 'songs.song_code', '=', 'song_category_rels.song_code')
    //             ->select('songs.song_code', 'songs.title_en') // only select necessary fields
    //             ->get();

    //         return DataTables::of($data)
    //             ->make(true);
    //     }
    //     // dd($category);
    //     return view('admin.category.show', compact('category'));
    // }


    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $category_code)
    {
        // $category = Category::findOrFail($category_code);
        $category = Category::where('category_code', $category_code)->firstOrFail();

        if ($request->ajax()) {
            $data = CateSubCateRel::where('cate_sub_cate_rels.category_code', $category_code)
                ->join('sub_categories', 'sub_categories.sub_category_code', '=', 'cate_sub_cate_rels.sub_category_code')
                ->select('sub_categories.sub_category_code', 'sub_categories.sub_category_en', 'sub_categories.sub_category_gu') // Adjust fields as needed
                ->get();
            // dd
            return DataTables::of($data)
                ->make(true);
        }
        // dd($category);
        return view('admin.category.show', compact('category'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $category_code)
    {
        $category = Category::where('category_code', $category_code)->firstOrFail();

        // Pass the category data to the edit view
        return view('admin.category.edit', compact('category'));
    }

    public function fetchAssociatedSongs(Request $request, string $category_code)
    {
        $data = SongCategoryRel::where('category_code', $category_code)
            ->join('songs', 'songs.song_code', '=', 'song_category_rels.song_code')
            ->select('songs.song_code', 'songs.title_en');
        // dd($category_code);
        return DataTables::of($data)->make(true);
    }

    public function fetchRemainingSongs(Request $request, string $category_code)
    {
        $data = Song::whereNotIn('song_code', function ($query) use ($category_code) {
            $query->select('song_code')
                ->from('song_category_rels')
                ->where('category_code', $category_code);
        })->select('song_code', 'title_en');

        return DataTables::of($data)->make(true);
    }

    // public function fetchRemainingSongs(Request $request, string $id)
    // {
    //     $data = Song::whereNotIn('id', function ($query) use ($id) {
    //         $query->select('song_id')
    //             ->from('song_category_rels')
    //             ->where('category_id', $id);
    //     })->select('id', 'title_en');

    //     return DataTables::of($data)->make(true);
    // }

    // public function addSongToCategory(Request $request)
    // {
    //     $request->validate([
    //         'song_code' => 'required|exists:songs,song_code',
    //         'category_code' => 'required|exists:categories,category_code',
    //     ]);

    //     // Create the relationship
    //     SongCategoryRel::create([
    //         'song_code' => $request->song_code,
    //         'category_code' => $request->category_code,
    //     ]);

    //     return redirect()->back()->with('success', 'Song added to category successfully');
    // }

    public function addSongToCategory(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'song_code' => 'required|exists:songs,song_code',
            'category_code' => 'required|exists:categories,category_code',
        ]);

        // Create the relationship
        SongCategoryRel::create([
            'song_code' => $request->song_code,
            'category_code' => $request->category_code,
        ]);

        return redirect()->back()->with('success', 'Song added to category successfully');
    }

    public function removeSongFromCategory(Request $request, $song_code)
    {
        // dump($song_code);
        $relationship = SongCategoryRel::where('song_code', $song_code)
            ->where('category_code', $request->category_code)
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
    public function update(Request $request, string $category_code)
    {
        $request->validate([
            'category_en' => 'required|string',
            'category_gu' => 'required|string'
        ]);

        $category = Category::where('category_code', $category_code)->firstOrFail();

        $category->update([
            'category_en' => $request->category_en,
            'category_gu' => $request->category_gu
        ]);

        return redirect()->back()->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $category_code)
    {
        // dd($category_code);
        $category = Category::where('category_code', $category_code)->firstOrFail();
        // dd($category);
        // $category->songs()->detach();
        $subCategories = CateSubCateRel::where('category_code', $category_code)->pluck('sub_category_code');

        if ($subCategories->isNotEmpty()) {
            SubCategory::whereIn('sub_category_code', $subCategories)->delete();
        }

        // Delete the categories
        $category->delete();

        // Return a response indicating success
        return redirect()->back()->with(['success' => 'Category deleted successfully']);
    }
}
