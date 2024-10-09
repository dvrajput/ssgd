@extends('admin.layouts.app')
@section('title', 'Create Playlist')
@section('content')
    <h1 class="mt-4 mb-4">Add New Playlist</h1>
    <form action="{{ route('admin.playlists.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="playlist_name">Title (English)</label>
            <input type="text" class="form-control @error('playlist_name') is-invalid @enderror" id="playlist_name"
                name="playlist_name" value="PAD 1" required>
            @error('playlist_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="song_code">Song</label>
            <select class="form-control select2" id="song_code" name="song_code[]" multiple="multiple"
                data-placeholder="Select Songs">
                @foreach ($songs as $song)
                    <option value="{{ $song->song_code }}">
                        {{ $song->title_en }}
                    </option>
                @endforeach
            </select>
            @error('song_code')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('admin.playlists.index') }}" class="btn btn-secondary ml-2">Cancel</a>
    </form>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: 'Select Songs',
                allowClear: true
            });
        });
    </script>
@endsection
