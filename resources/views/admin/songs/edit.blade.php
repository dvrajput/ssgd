@extends('admin.layouts.app')
@section('title', 'Edit Song')
@section('content')
    <h1 class="mt-4 mb-4">Edit Song</h1>
    <form action="{{ route('admin.songs.update', $song->song_code) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="title_en">Title (English)</label>
            <input type="text" class="form-control @error('title_en') is-invalid @enderror" id="title_en" name="title_en"
                value="{{ old('title_en', $song->title_en) }}" required>
            @error('title_en')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="lyrics_en">Lyrics (English)</label>
            <textarea class="form-control @error('lyrics_en') is-invalid @enderror" id="lyrics_en" name="lyrics_en" rows="5"
                required>{{ old('lyrics_en', $song->lyrics_en) }}</textarea>
            @error('lyrics_en')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="title_gu">Title (Gujarati)</label>
            <input type="text" class="form-control" id="title_gu" name="title_gu"
                value="{{ old('title_gu', $song->title_gu) }}">
        </div>
        <div class="form-group">
            <label for="lyrics_gu">Lyrics (Gujarati)</label>
            <textarea class="form-control" id="lyrics_gu" name="lyrics_gu" rows="5">{{ old('lyrics_gu', $song->lyrics_gu) }}</textarea>
        </div>
        
        <div class="form-group">
            <label for="sub_category_code">Category</label>
            <select class="form-control select2" id="sub_category_code" name="sub_category_code[]" multiple="multiple"
                data-placeholder="Select Categories">
                @foreach ($subCategories as $category)
                    <option value="{{ $category->sub_category_code }}">
                        {{ $category->sub_category_en }} ({{ $category->sub_category_gu }})
                    </option>
                @endforeach
            </select>
            @error('sub_category_code')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin.songs.index') }}" class="btn btn-secondary ml-2">Cancel</a>
    </form>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: 'Select Sub Categories',
                allowClear: true
            });
        });
    </script>
@endsection
