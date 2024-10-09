@extends('user.layouts.app')
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
            {{-- <h3 class="mb-0">Category List</h3>
            <a href="{{ route('user.categories.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Create
                Category</a> --}}
        </div>

        <!-- DevExtreme DataGrid container -->
        <table id="userTable" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>{{ __('English Category') }}</th>
                    <th>{{ __('Gujarati Category') }}</th>
                    {{-- <th>Action</th> --}}
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
                ajax: '{{ route('user.categories.index') }}',
                columns: [{
                        data: 'sub_category_en',
                        name: 'sub_category_en',
                        render: function(data, type, row) {
                            return `<a href="{{ url('categories') }}/${row.sub_category_code}" style="color: black; text-decoration: none;">${data}</a>`;
                        }
                    },
                    {
                        data: 'sub_category_gu',
                        name: 'sub_category_gu',
                        render: function(data, type, row) {
                            return `<a href="{{ url('categories') }}/${row.sub_category_code}" style="color: black; text-decoration: none;">${data}</a>`;
                        }
                    },
                    // {
                    //     data: 'action',
                    //     name: 'action',
                    //     orderable: false,
                    //     searchable: false,
                    //     render: function(data, type, row) {
                    //         return `
                //         <a href="/categories/${row.sub_category_code}" class="btn btn-sm btn-info" data-toggle="tooltip" title="View">
                //             <i class="fas fa-eye"></i>
                //         </a>
                //     `;
                    //     }
                    // }

                ],
                dom: 'Bfrtip',
                buttons: [
                    // 'copy', 'csv', 'excel', 'pdf'
                ]
            });
        });
    </script>
@endsection
