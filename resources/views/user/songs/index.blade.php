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
        {{-- <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Song List</h3>
        </div> --}}

        <!-- DevExtreme DataGrid container -->
        <table id="songsTable" class="display text-center" style="width:100%">
            <thead>
                <tr>
                    <th>{{ __('English Title') }}</th>
                    <th>{{ __('Gujarati Title') }}</th>
                </tr>
            </thead>
        </table>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#songsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('user.songs.index') }}',
                columns: [{
                        data: 'title_en',
                        name: 'title_en',
                        render: function(data, type, row) {
                            return `<a href="{{ url('songs') }}/${row.song_code}" style="color: black; text-decoration: none;">${data}</a>`;
                        }
                    },
                    // {
                    //     data: 'lyrics_en',
                    //     name: 'lyrics_en'
                    // },
                    {
                        data: 'title_gu',
                        name: 'title_gu',
                        render: function(data, type, row) {
                            return `<a href="{{ url('songs') }}/${row.song_code}" style="color: black; text-decoration: none;">${data}</a>`;
                        }
                    },
                    // {
                    //     data: 'lyrics_gu',
                    //     name: 'lyrics_gu'
                    // },
                    // {
                    //     data: 'action',
                    //     name: 'action',
                    //     orderable: false,
                    //     searchable: false,
                    //     render: function(data, type, row) {
                    //         return `
                //             <a href="{{ url('songs') }}/${row.song_code}" class="btn btn-sm btn-info" data-toggle="tooltip" title="View">
                //                 <i class="fas fa-eye"></i>
                //             </a>

                //         `;
                    //     }
                    // }

                ],
                dom: 'Bfrtip',
                buttons: [
                    // 'copy', 'csv', 'excel', 'pdf'
                ]
            });

            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endsection
