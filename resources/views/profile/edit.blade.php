@extends('layouts.admin')

@push('style')
    <!-- Add custom Bootstrap styles here if needed -->
@endpush

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <header class="my-4">
                    <h2 class="h4 fw-semibold text-dark">
                        {{ __('Profile') }}
                    </h2>
                </header>
            </div>

            <div class="col-12">
                <div class="row gy-4">
                    <!-- Update Profile Information Section -->
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">{{ __('Update Profile Information') }}</h5>
                                <hr>
                                @include('profile.partials.update-profile-information-form')
                            </div>
                        </div>
                    </div>

                    <!-- Update Password Section -->
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">{{ __('Update Password') }}</h5>
                                <hr>
                                @include('profile.partials.update-password-form')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <!-- Add custom scripts here if needed -->
@endpush
