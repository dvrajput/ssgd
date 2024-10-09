@extends('user.layouts.app')
@section('title', 'View Song')

@section('style')
    <style>
        .display {
            text-align: center;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.5em 1em;
        }

        .nav-tabs .nav-link.active {
            background-color: #007bff;
            color: #fff;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        {{-- <div class="d-flex justify-content-between align-items-center mb-3">
            <h3><a href="{{ route('user.songs.index') }}"><i
                        class="fas fa-arrow-left"></i></a>&nbsp;&nbsp;{{ $song->title_en }}</h3>
        </div> --}}

        <!-- Tab navigation for songs in playlist (PAD 1, PAD 2, ...) -->
        <ul class="nav nav-tabs" id="songTab" role="tablist">
            <h3><a href="{{ route('user.songs.index') }}"><i class="fas fa-arrow-left"></i></a>&nbsp;&nbsp;</h3>
            @foreach ($songsInPlaylists as $index => $playlistSong)
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $playlistSong->song_code == $song->song_code ? 'active' : '' }}"
                        id="tab-{{ $playlistSong->song_code }}" data-toggle="tab"
                        href="#content-{{ $playlistSong->song_code }}" role="tab"
                        aria-controls="content-{{ $playlistSong->song_code }}"
                        aria-selected="{{ $playlistSong->song_code == $song->song_code ? 'true' : 'false' }}">
                        {{-- {{ __('Pad ') .''. ($index + 1) }} --}}
                        {{ __('Pad') }} {{ $index + 1 }}
                    </a>
                </li>
            @endforeach
        </ul>

        <!-- Tab content for song details -->
        <div class="tab-content" id="songTabContent">
            @foreach ($songsInPlaylists as $playlistSong)
                <div class="tab-pane fade {{ $playlistSong->song_code == $song->song_code ? 'show active' : '' }}"
                    id="content-{{ $playlistSong->song_code }}" role="tabpanel"
                    aria-labelledby="tab-{{ $playlistSong->song_code }}">
                    {{-- <br> --}}
                    <div class="d-flex justify-content-between mb-3 mt-2">
                        <h3>{{ $playlistSong->{'title_' . app()->getLocale()} }}</h3>
                    </div>

                    <center>
                        <p>{!! nl2br($song->{'lyrics_' . app()->getLocale()}) !!}</p>
                    </center>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Initialize the tabs
            $('#songTab a').on('click', function(e) {
                e.preventDefault();
                $(this).tab('show');
            });
        });
    </script>
@endsection
