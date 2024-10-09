@extends('admin.layouts.app')
@section('title', 'View Playlist')
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
            <h3 class="mb-0">Playlist List</h3>
            <a href="{{ route('admin.playlists.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Create Playlist</a>
        </div>

        <!-- DevExtreme DataGrid container -->
        <table id="playlistsTable" class="display text-center" style="width:100%">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#playlistsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.playlists.index') }}',
                columns: [{
                        data: 'playlist_code',
                        name: 'playlist_code'
                    },
                    {
                        data: 'playlist_name',
                        name: 'playlist_name'
                    },
                   
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                                <a href="{{ url('admin/playlists') }}/${row.playlist_code}" class="btn btn-sm btn-info" data-toggle="tooltip" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="/admin/playlists/${row.playlist_code}/edit" class="btn btn-sm btn-warning" data-toggle="tooltip" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ url('admin/playlists') }}/${row.playlist_code}" method="POST" style="display:inline;">
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

            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endsection