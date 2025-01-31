<section>
    <form method="post" action="{{ route('admin.password.update') }}">
        @csrf
        @method('put')

        <div class="mb-3">
            <label for="current_password" class="form-label">{{ __('Current Password') }}</label>
            <input id="current_password" name="current_password" type="password"
                class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                placeholder="Enter current password" autocomplete="current-password" required>
            @error('current_password', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">{{ __('New Password') }}</label>
            <input id="password" name="password" type="password"
                class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                placeholder="Enter new password" autocomplete="new-password" required>
            @error('password', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
            <input id="password_confirmation" name="password_confirmation" type="password"
                class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                placeholder="Confirm new password" autocomplete="new-password" required>
            @error('password_confirmation', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex align-items-center">
            <button type="submit" class="btn btn-primary me-3">{{ __('Save') }}</button>

            @if (session('status') === 'password-updated')
                <span class="text-success small" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2000)">
                    {{ __('Saved.') }}
                </span>
            @endif
        </div>
    </form>
</section>
