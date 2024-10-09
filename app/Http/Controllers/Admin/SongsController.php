<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Configuration;
use App\Models\Song;
use App\Models\SongCategoryRel;
use App\Models\SongSubCateRel;
use App\Models\SubCategory;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class SongsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     // $songs = Song::paginate(10);
    //     $songs = Song::all();
    //     return view('admin.songs.index', compact('songs'));
    //     // return view('admin.songs.index', compact('songs'));
    // }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Song::orderBy('id', 'desc'); // Customize your query as needed

            return DataTables::of($data)
                // ->addColumn('action', function ($row) {
                //     $editButton = '<a href="' . route('admin.songs.edit', $row->id) . '" class="btn btn-warning btn-sm ml-2">Edit</a>';
                //     $viewButton = '<a href="' . route('admin.songs.show', $row->id) . '" class="btn btn-info btn-sm">View</a>';

                //     // Delete button with form
                //     $deleteButton = '
                //     <form action="' . route('admin.songs.destroy', $row->id) . '" method="POST" style="display:inline-block;" class="delete-form">
                //         ' . csrf_field() . '
                //         ' . method_field('DELETE') . '
                //         <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to delete this song?\')">Delete</button>
                //     </form>
                // ';

                //     return $viewButton . $editButton . $deleteButton;
                // })
                ->make(true);
        }
        return view('admin.songs.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $subCategories = SubCategory::all();
        return view('admin.songs.create', compact('subCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'title_en' => 'required|string|max:255',
            'lyrics_en' => 'required|string',
            'title_gu' => 'nullable|string|max:255',
            'lyrics_gu' => 'nullable|string',
            'sub_category_code' => 'required|array',
            'sub_category_code.*' => 'exists:sub_categories,sub_category_code',
        ]);

        //get song prefix from configuraton table
        $config = Configuration::where('key', 'song_prefix')->value('value');

        // Find the last song with the same prefix
        $lastSong = Song::where('song_code', 'LIKE', "$config%")
            ->orderBy('id', 'desc')
            ->first();

        // Determine the new song code
        if ($lastSong) {
            // Extract the numeric part and increment it
            $lastNumber = intval(substr($lastSong->song_code, strlen($config)));
            $newSongCode = $config . ($lastNumber + 1);
        } else {
            // No song exists with this prefix, start with the prefix followed by 1
            $newSongCode = $config . '1';
        }

        $song = Song::create([
            'song_code' => $newSongCode,
            'title_en' => $request->title_en,
            'lyrics_en' => $request->lyrics_en,
            'title_gu' => $request->title_gu,
            'lyrics_gu' => $request->lyrics_gu,
        ]);

        foreach ($request->sub_category_code as $subCategoryCode) {
            SongSubCateRel::create([
                'song_code' => $song->song_code,
                'sub_category_code' => $subCategoryCode,
            ]);
        }

        return redirect()->route('admin.songs.index')->with('success', 'Song added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $song_code)
    {
        $song = Song::where('song_code', $song_code)->firstOrFail();
        return view('admin.songs.show', compact('song'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $song_code)
    {
        // dd($song_code);
        $song = Song::where('song_code', $song_code)->firstOrFail();
        // dump($song->toArray());
        // $song = Song::findOrFail($song_code);
        $subCategories = SubCategory::all();
        // dd($subCategories->toArray());
        // Pass the song data to the edit view
        return view('admin.songs.edit', compact('song', 'subCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $song_code)
    {
        $request->validate([
            'title_en' => 'required|string|max:255',
            'lyrics_en' => 'required|string',
            'title_gu' => 'nullable|string|max:255',
            'lyrics_gu' => 'nullable|string',
            'sub_category_code' => 'required|array', // Ensure categories are passed as an array
            'sub_category_code.*' => 'exists:sub_categories,sub_category_code', // Each category must exist in the categories table
        ]);

        $song = Song::findOrFail($song_code);

        $song->update([
            'title_en' => $request->title_en,
            'lyrics_en' => $request->lyrics_en,
            'title_gu' => $request->title_gu,
            'lyrics_gu' => $request->lyrics_gu,
        ]);

        $song->subCategories()->sync($request->sub_category_code);
        // return redirect()->route('songs.index')->with('success', 'Song updated successfully!');
        return redirect()->route('admin.songs.index')->with('success', 'Song updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $song_code)
    {
        $song = Song::findOrFail($song_code);

        // Delete related entries in the song_category_rels table
        $song->categories()->detach();

        // Delete the song
        $song->delete();

        // Redirect with a success message
        return redirect()->back()->with('success', 'Song deleted successfully');
    }

    public function changeLanguage($locale)
    {
        Session::put('locale', $locale);
        App::setLocale($locale);

        return redirect()->back();
    }
}
