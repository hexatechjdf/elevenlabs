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
@php($voice_type = $voice_type ?? null)
@php($user = loginUser())
    <div class="container ">
        @if($user->role != 1)
        @include('location.components.topbar')
        @endif
        <div class="row">
            <div class="col-md-12">
                <form class="submitForm" action="{{ route('admin.setting.save') }}" method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h4 class="h4">List of voices</h4>
                            @if($voice_type == 'own')
                            <button class="btn btn-warning upload_voice" type="button">Upload Voice</button>
                            @endif
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

        <!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Upload MP3 File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="uploadForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="" class="form-label">Name:</label>
                        <input type="text" class="form-control name"  name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="mp3File" class="form-label">Select MP3 File:</label>
                        <input type="file" class="form-control" id="mp3File" name="mp3File" accept="audio/mp3" required>
                    </div>
                    <button type="submit" class="btn btn-success">Upload</button>
                </form>
            </div>
        </div>
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
            var voicesTable = $('#voicesTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
            url: "{{ route('voices.list') }}",
            data: function(d) {
                d.voice_type  = '{{$voice_type}}'; // Example: Sending category filter
                }
            },
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
                        return data ? `<a href="${data}" target="_blank" class="btn btn-primary btn-sm">Preview</a>` : '-';
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

        $(document).on('click','.upload_voice',function(){
           $('#uploadModal').modal('show');
           return false;
        });

        $("#uploadForm").submit(function (e) {
        e.preventDefault();

        var formData = new FormData();
        var file = $("#mp3File")[0].files[0]; // Get selected file
        formData.append("name", $('.name').val());
        formData.append("file", file);

        $.ajax({
            url: "{{ route('location.own.voice.submit') }}", // Laravel route
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}" // CSRF Token for Laravel
            },
            success: function (response) {
                toastr.success(response.success);
                $("#uploadModal").modal("hide");
                voicesTable.ajax.reload(null, false);
            },
            error: function (xhr) {
                toastr.error('Something went wrong');
            }
        });
    });
    });
    </script>
    @endpush
