@extends('admin.layouts.app')
@section('title', 'View Song')
@section('style')
    <style>
        .display {
            text-align: center;
            /* Center-aligns text in the table */
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.5em 1em;
            /* Padding for pagination buttons */
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Category List</h3>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Create
                Category</a>
        </div>

        <!-- DevExtreme DataGrid container -->
        <table id="userTable" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Category Title</th>
                    <th>Lyrics Category</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>

@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('#userTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.categories.index') }}',
                columns: [{
                        data: 'category_code',
                        name: 'category_code'
                    },
                    {
                        data: 'category_en',
                        name: 'category_en'
                    },
                    {
                        data: 'category_gu',
                        name: 'category_gu'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                            <a href="/admin/categories/${row.category_code}" class="btn btn-sm btn-info" data-toggle="tooltip" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="/admin/categories/${row.category_code}/edit" class="btn btn-sm btn-warning" data-toggle="tooltip" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.categories.destroy', '') }}/${row.category_code}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Delete" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        `;
                        }
                    }

                ],
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf'
                ]
            });
        });
    </script>
@endsection
