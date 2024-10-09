@extends('admin.layouts.app')
@section('title', 'Create Song')
@section('content')
    <h1 class="mt-4 mb-4">Add New Song</h1>
    <form action="{{ route('admin.songs.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="title_en">Title (English)</label>
            <input type="text" class="form-control @error('title_en') is-invalid @enderror" id="title_en" name="title_en"
                value="{{ old('title_en') }}" required>
            @error('title_en')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="lyrics_en">Lyrics (English)</label>
            <textarea class="form-control @error('lyrics_en') is-invalid @enderror" id="lyrics_en" name="lyrics_en" rows="5"
                required>{{ old('lyrics_en') }}</textarea>
            @error('lyrics_en')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="title_gu">Title (Gujarati)</label>
            <input type="text" class="form-control" id="title_gu" name="title_gu" value="{{ old('title_gu') }}">
        </div>
        <div class="form-group">
            <label for="lyrics_gu">Lyrics (Gujarati)</label>
            <textarea class="form-control" id="lyrics_gu" name="lyrics_gu" rows="5">{{ old('lyrics_gu') }}</textarea>
        </div>
        <div class="form-group">
            <label for="sub_category_code">Sub Category</label>
            <select class="form-control select2" id="sub_category_code" name="sub_category_code[]" multiple="multiple"
                data-placeholder="Select Sub Categories">
                @foreach ($subCategories as $scategory)
                    <option value="{{ $scategory->sub_category_code }}">
                        {{ $scategory->sub_category_en }} ({{ $scategory->sub_category_gu }})
                    </option>
                @endforeach
            </select>
            @error('sub_category_code')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
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
