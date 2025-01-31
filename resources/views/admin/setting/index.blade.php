@extends('layouts.admin')
@push('style')
    <style>
    </style>
@endpush
@section('content')
    <div class="container ">
        <div class="row">
            <div class="col-md-8">
                <form class="submitForm" action="{{ route('admin.setting.save') }}" method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h4 class="h4">CRM OAuth Information</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <h6>Redirect URI - add while creating app</h6>
                                    <p class="h6"> {{ route('crm.oauth_callback') }} </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h6>Scopes - select while creating app</h6>
                                    <p class="h6"> {{ $scopes }} </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h6>* Note - App distribution subaccount only !</h6>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="clientID" class="form-label"> Client ID</label>
                                        <input type="text" class="form-control "
                                            value="{{ $settings['crm_client_id'] ?? '' }}" id="crm_client_id"
                                            name="setting[crm_client_id]" aria-describedby="clientID" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label for="clientID" class="form-label"> Client secret</label>
                                    <input type="text" class="form-control "
                                        value="{{ $settings['crm_client_secret'] ?? '' }}" id="crm_secret_id"
                                        name="setting[crm_client_secret]" aria-describedby="secretID" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 m-2">
                                    <button id="form_submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-4 d-none" hidden>
                <div class="card">
                    <div class="card-header">
                        <h4 class="h4">CRM OAuth Connectivity</h4>
                    </div>
                    <div class="card-body">
                        <div class="ml-2">
                            <p class="mb-1 text-muted">Connectivity</p>
                            @if ($company_name && $company_id)
                                <p>company : <span style="font-weight:bold;">{{ $company_name }}</span></p>
                                <p>companyId : <span style="font-weight:bold;">{{ $company_id }}</span></p>
                            @endif
                            @php($connect = @$company_name ? 'Reconnect' : 'Connect')
                            <p style="font-weight:bold; font-size:22px"><a class="btn btn-primary"
                                    href="{{ $connecturl }}">{{ $connect }} with
                                    Agency</a></p>
                        </div>
                    </div>
                    <!--end card-body-->
                </div>
            </div>
            <div class="col-md-12 mt-3">
                <div class="card">
                    <div class="card-header">
                        <h4 class="h4">CRM Custom Menu Link for Auto Login</h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-danger">
                            <ul>
                                <li>Add below url to custom menu link enabled only for locations as an iframe.</li>
                            </ul>
                        </div>
                        <div class="copy-container">
                            <input type="text" class="form-control code_url"
                                value="{{ route('auth.check') }}?location_id={{ braceParser('[[location.id]]') }}&sessionkey={{ braceParser('[[user.sessionKey]]') }}"
                                readonly>
                            <div class="row my-2">
                                <div class="col-md-12" style="text-align: left !important">
                                    <button type="button" class="btn btn-primary script_code copy_url" data-message="Link Copied"
                                        id="kt_account_profile_details_submit">Copy URL</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-3">
                @include('components.textwebhook')
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <form class="submitForm" action="{{ route('admin.setting.save') }}" method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h4 class="h4">Eleven Labs AI Api Key</h4>
                        </div>
                        <div class="card-body">
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="" class="form-label"> API Key</label>
                                        <input type="text" class="form-control "
                                            value="{{ $settings['ele_api_key'] ?? '' }}"
                                            name="setting[ele_api_key]" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 m-2">
                                    <button id="form_submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endsection

    @push('script')
        @include('components.copyUrlScript')
        @include('components.submitForm')
    @endpush
