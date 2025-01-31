@extends('layouts.admin')
@push('style')
    <style>

    </style>
@endpush
@section('content')
    <div class="container ">
        @include('location.components.topbar')
        <div class="row">
            <div class="col-md-12">
              @include('components.textwebhook')
            </div>
        </div>
    @endsection

    @push('script')
        @include('components.copyUrlScript')
    @endpush
