<div class="card shadow-sm">
    <div class="card-header">
        <h5 class="mb-0">Change Password</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('change_password') }}">
            @csrf
            <div class="mb-3">
                <label for="current_password" class="form-label">Current Password</label>
                <input type="password" class="form-control" id="current_password" name="current_password" required><br>
                @if (session('current_password'))
                    <div class="alert alert-danger">
                        {{ session('current_password') }}
                    </div>
                @endif
                @error('current_password')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="new_password" class="form-label">New Password</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required minlength="8"><br>
                @error('new_password')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Password</button><br>
            @if (session('password_changed'))
                <div class="alert alert-success mt-3">
                    {{ session('password_changed') }}
                </div>
            @endif
        </form>
    </div>
</div>