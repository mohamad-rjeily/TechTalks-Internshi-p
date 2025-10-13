<div class="card shadow-sm">
    <div class="card-header">
        <h5 class="mb-0">Privacy Settings</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('update_privacy_settings') }}">
            @csrf

            <div class="mb-4">
                <h6 class="card-title">Email Notifications</h6>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="newsletter_opt_in" id="newsletter_opt_in" {{ $user->newsletter_opt_in ? 'checked' : '' }}>
                    <label class="form-check-label" for="newsletter_opt_in">
                        Subscribe to our newsletter and promotional emails.
                    </label>
                </div>
            </div>

            <div class="mb-4">
                <h6 class="card-title">Profile Visibility</h6>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="profile_visibility" id="public_profile" value="public" {{ $user->profile_visibility == 'public' ? 'checked' : '' }}>
                    <label class="form-check-label" for="public_profile">
                        Public (visible to everyone)
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="profile_visibility" id="private_profile" value="private" {{ $user->profile_visibility == 'private' ? 'checked' : '' }}>
                    <label class="form-check-label" for="private_profile">
                        Private (visible only to you)
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save Privacy Settings</button>

            @if (session('privacy_status'))
                <div class="alert alert-success mt-3">
                    {{ session('privacy_status') }}
                </div>
            @endif
        </form>
    </div>
</div>