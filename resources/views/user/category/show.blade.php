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
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <h3>
            <a href="{{ route('user.categories.index') }}"><i class="fas fa-arrow-left"></i></a>
            &nbsp;&nbsp;{{ $subCategory->{'sub_category_' . app()->getLocale()} }}
        </h3>

        <table id="categorySongTable" class="display text-center" style="width:100%">
            <thead>
                <tr>
                    {{-- <th>Code</th> --}}
                    {{-- <th>English Title</th>
                    <th>Gujarati Title</th> --}}
                    <th>{{ __('English Title') }}</th>
                    <th>{{ __('Gujarati Title') }}</th>
                    {{-- <th>Action</th> --}}
                </tr>
            </thead>
        </table>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            const categoryCode = "{{ $subCategory->sub_category_code }}"
            // console.log(categoryCode);
            // const categoryCode = "{{ $subCategory->sub_category_code }}";
            // console.log('AJAX URL:', '{{ route('user.categories.show', ':id') }}'.replace(':id', categoryCode));
            const ajaxUrl = '{{ route('user.categories.show', ':id') }}'.replace(':id', categoryCode);
            // console.log('AJAX URL:', ajaxUrl);

            $('#categorySongTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: ajaxUrl,
                // headers: {
                //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                // },
                columns: [
                    // {
                    //     data: 'song_code',
                    //     name: 'song_code',
                    //     render: function(data, type, row) {
                    //         return `<a href="{{ url('songs') }}/${row.song_code}" style="color: black; text-decoration: none;">${data}</a>`;
                    //     }
                    // }, 
                    {
                        data: 'title_en',
                        name: 'title_en',
                        render: function(data, type, row) {
                            return `<a href="{{ url('songs') }}/${row.song_code}" style="color: black; text-decoration: none;">${data}</a>`;
                        }
                    },
                    {
                        data: 'title_gu',
                        name: 'title_gu',
                        render: function(data, type, row) {
                            return `<a href="{{ url('songs') }}/${row.song_code}" style="color: black; text-decoration: none;">${data}</a>`;
                        }
                    },
                    // {
                    //     data: 'action',
                    //     name: 'action',
                    //     orderable: false,
                    //     searchable: false,
                    //     render: function(data, type, row) {
                    //         return `
                //                 <a href="{{ url('songs') }}/${row.song_code}" class="btn btn-sm btn-info" data-toggle="tooltip" title="View">
                //                     <i class="fas fa-eye"></i>
                //                 </a>
                //                 `;
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
