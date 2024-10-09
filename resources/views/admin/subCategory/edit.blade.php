@extends('admin.layouts.app')
@section('title', 'Edit Sub Category')

@section('content')
    <div class="container-fluid">
        <h1 class="mt-4 mb-4">Edit Category</h1>
        <form action="{{ route('admin.subCategories.update', $subCategory->sub_category_code) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group row">
                <!-- Title (English) -->
                <div class="col-md-6 col-12 mb-3">
                    <label for="sub_category_en" class="col-form-label">Title (English)</label>
                    <input type="text" class="form-control @error('sub_category_en') is-invalid @enderror" id="sub_category_en"
                        name="sub_category_en" value="{{ old('sub_category_en', $subCategory->sub_category_en) }}" required>
                    @error('sub_category_en')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Title (Gujarati) -->
                <div class="col-md-6 col-12 mb-3">
                    <label for="sub_category_gu" class="col-form-label">Title (Gujarati)</label>
                    <input type="text" class="form-control" id="sub_category_gu" name="sub_category_gu"
                        value="{{ old('sub_category_gu', $subCategory->sub_category_gu) }}">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-6 col-12">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('admin.subCategories.index') }}" class="btn btn-secondary ml-2">Cancel</a>
                </div>
            </div>
        </form>



        <h3 class="mt-4">Associated Songs ({{ $subCategory->sub_category_en }})</h3>
        <table id="associatedSongsTable" class="table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Title (English)</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>

        <h3 class="mt-4">Remaining Songs ({{ $subCategory->sub_category_en }})</h3>
        <table id="remainingSongsTable" class="table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Title (English)</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // DataTable for associated songs
            $('#associatedSongsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.subCategories.associated_songs', $subCategory->sub_category_code) }}',
                columns: [{
                        data: 'song_code',
                        name:'song_code'
                    },
                    {
                        data: 'title_en',
                        name: 'title_en'
                    },
                    {
                        data: 'song_code',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            // console.log('a',data);
                            
                            return `
                                <form action="{{ route('admin.subCategories.removeSong', '') }}/${data}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="sub_category_code" value="{{ $subCategory->sub_category_code }}">
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Remove</button>
                                </form>
                            `;
                        }
                    }
                ]
            });

            // DataTable for remaining songs
            $('#remainingSongsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.subCategories.remaining_songs', $subCategory->sub_category_code) }}',
                columns: [{
                        data: 'song_code',
                        name:'song_code'
                    },
                    {
                        data: 'title_en',
                        name: 'title_en'
                    },
                    {
                        data: 'song_code',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                                <form action="{{ route('admin.subCategories.addSong') }}" method="POST" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="song_code" value="${data}">
                                    <input type="hidden" name="sub_category_code" value="{{ $subCategory->sub_category_code }}">
                                    <button type="submit" class="btn btn-sm btn-success">Add</button>
                                </form>
                            `;
                        }
                    }
                ]
            });
        });
    </script>
@endsection
