<?php

namespace App\Http\Controllers\Admin;

use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Models\Playlist;
use App\Models\Song;
use App\Models\SongPlaylistRel;
use Illuminate\Http\Request;

class PlaylistsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Playlist::query(); // Customize your query as needed

            return DataTables::of($data)
                ->make(true);
        }
        return view('admin.playlists.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $songs=Song::select('song_code','title_en')->get();
        return view('admin.playlists.create',compact('songs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'playlist_name' => 'required|string|max:255'
        ]);

        //get playlist prefix from configuraton table
        $config = Configuration::where('key', 'playlist_prefix')->value('value');

        // Find the last playlist with the same prefix
        $lastPlaylist = Playlist::where('playlist_code', 'LIKE', "$config%")
            ->orderBy('playlist_code', 'desc')
            ->first();

        // Determine the new playlist code
        if ($lastPlaylist) {
            // Extract the numeric part and increment it
            $lastNumber = intval(substr($lastPlaylist->playlist_code, strlen($config)));
            $newPlaylistCode = $config . ($lastNumber + 1);
        } else {
            // No playlist exists with this prefix, start with the prefix followed by 1
            $newPlaylistCode = $config . '1';
        }

        $playlist = Playlist::create([
            'playlist_code' => $newPlaylistCode,
            'playlist_name' => $request->playlist_name
        ]);

        foreach ($request->song_code as $songCode) {
            SongPlaylistRel::create([
                'song_code' => $songCode,
                'playlist_code' => $playlist->playlist_code,
            ]);
        }

        return redirect()->route('admin.playlists.index')->with('success', 'Playlist added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
