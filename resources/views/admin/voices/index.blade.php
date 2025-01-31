@extends('layouts.admin')
@push('style')
    <style>
        #voicesTable_paginate{
            display:flex;
            justify-content: end;
        }

        #voicesTable_wrapper label{
            display:flex;
        }
        #voicesTable_length select {
            width: 120px !important;
        }

        #voicesTable_filter input {
            width: 200px !important;
            height:30px;
        }
        #voicesTable_filter{
            margin-bottom:20px;
        }
        #voicesTable_filter label{
            justify-content: end;
        }
    </style>
@endpush
@section('content')
    <div class="container ">
        <div class="row">
            <div class="col-md-12">
                <form class="submitForm" action="{{ route('admin.setting.save') }}" method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h4 class="h4">List of voices</h4>
                        </div>
                        <div class="card-body">
                            <table id="voicesTable" class="table table-bordered table-striped nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Select</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Gender</th>
                                        <th>Age</th>
                                        <th>Preview</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    @endsection

    @push('script')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    @include('components.submitForm')
    @include('components.copyUrlScript')

    <script>
          $(document).ready(function() {
            let savedVoiceId = "{{$savedVoiceId }}";
        $('#voicesTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('voices.list') }}",
            columns: [
                {
                    data: 'voice_id',
                    name: 'voice_id',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        let checked = (data == savedVoiceId) ? 'checked' : '';
                        return `<input type="radio" name="selected_voice" class="select-voice" data-voice-id="${data}" ${checked}>`;                    }
                },
                { data: 'name', name: 'name' },
                { data: 'category', name: 'category' },
                { data: 'gender', name: 'gender' },
                { data: 'age', name: 'age' },
                {
                    data: 'preview_url',
                    name: 'preview_url',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `<a href="${data}" target="_blank" class="btn btn-primary btn-sm">Preview</a>`;
                    }
                }
            ]
        });

        $(document).on('change', '.select-voice', function() {
            let voiceId = $(this).data('voice-id');

            if (confirm("Are you sure you want to select this voice?")) {
                $.ajax({
                    url: "{{ route('save.selected.voice') }}",
                    type: "POST",
                    data: {
                        voice_id: voiceId,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        alert("Voice selected successfully!");
                    },
                    error: function(xhr) {
                        alert("Error selecting voice. Please try again.");
                    }
                });
            }
        });
    });
    </script>
    @endpush
