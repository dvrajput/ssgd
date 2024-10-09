<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Song;
use App\Models\SongPlaylistRel;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class SongsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Song::query(); // Customize your query as needed

            return DataTables::of($data)

                ->make(true);
        }
        return view('user.songs.index');
    }

    public function show(string $songCode)
    {
        // $song = Song::findOrFail($id);
        $song = Song::where('song_code', $songCode)->firstOrFail();

        // dump($song->toArray());

        $playlists = SongPlaylistRel::where('song_code', $songCode)->get();

        // dump($playlists->toArray());
        $playlistCodes = $playlists->pluck('playlist_code');

        $songsInPlaylists = Song::whereIn('song_code', function ($query) use ($playlistCodes) {
            $query->select('song_code')
                ->from('song_playlist_rels')
                ->whereIn('playlist_code', $playlistCodes);
        })->get();

        // dump($song->toArray());
        // dump($songsInPlaylists->toArray());
        return view('user.songs.show', compact('song','songsInPlaylists'));
    }
}
