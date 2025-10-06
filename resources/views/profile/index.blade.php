@extends('layout.profile_base')

@section('content')

    <div class="row justify-content-center pt-4 pb-5">
        <div class="col-lg-10">
            <h1 class="mb-4 text-primary">Profile Settings</h1>

            <ul class="nav nav-tabs nav-justified border-0 mb-4" id="profileTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="true">
                        <i class="fas fa-user-circle me-2"></i> Profile
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button" role="tab" aria-controls="password" aria-selected="false">
                        <i class="fas fa-lock me-2"></i> Password
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="privacy-tab" data-bs-toggle="tab" data-bs-target="#privacy" type="button" role="tab" aria-controls="privacy" aria-selected="false">
                        <i class="fas fa-eye-slash me-2"></i> Privacy
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-danger" id="danger-tab" data-bs-toggle="tab" data-bs-target="#danger" type="button" role="tab" aria-controls="danger" aria-selected="false">
                        <i class="fas fa-trash-alt me-2"></i> Danger Zone
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="profileTabsContent">
                <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    @include('profile.sections.profile-info')
                </div>
                <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                    @include('profile.sections.change-password')
                </div>
                <div class="tab-pane fade" id="privacy" role="tabpanel" aria-labelledby="privacy-tab">
                    @include('profile.sections.privacy-settings')
                </div>
                <div class="tab-pane fade" id="danger" role="tabpanel" aria-labelledby="danger-tab">
                    @include('profile.sections.danger-zone')
                </div>
            </div>
        </div>
    </div>

@endsection

