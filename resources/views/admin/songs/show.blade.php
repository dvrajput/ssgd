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
        <!-- Header -->
        
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3><a href="{{ route('admin.songs.index') }}"><i
                        class="fas fa-arrow-left"></i></a>&nbsp;&nbsp;{{ $song->{'title_' . app()->getLocale()} }}</h3>
            {{-- <a href="{{ route('admin.songs.index') }}" class="btn btn-primary"><i class="fas fa-arrow-left"></i>
                Back</a> --}}
        </div>

        <div class="d-flex justify-content-between mb-3">

            {{-- <a href="{{ route('songs.index') }}" class="btn btn-primary mr-2">Back Home</a> --}}
        </div>

        <center>
            <p>{!! nl2br($song->{'lyrics_' . app()->getLocale()}) !!}</p>
        </center>

    </div>
@endsection
