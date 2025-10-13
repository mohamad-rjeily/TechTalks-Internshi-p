<div class="card border-danger shadow-sm">
    <div class="card-header bg-danger text-white">
        <h5 class="mb-0">Danger Zone</h5>
    </div>
    <div class="card-body text-danger">
        <p class="card-text">Once you delete your account, all data will be permanently removed. This action cannot be undone.</p>
        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
            Delete My Account
        </button>
    </div>
</div>

<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAccountModalLabel">Confirmation of Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete your account? This action is irreversible.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('delete_user_account') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger">Yes, delete my account</button>
                </form>
            </div>
        </div>
    </div>
</div>