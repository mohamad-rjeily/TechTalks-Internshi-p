@extends('layouts.base')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="mb-4">Profile Settings</h1>

            <ul class="nav nav-tabs mb-4" id="profileTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link @if($activeTab == 'profile') active @endif" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="true">Profile</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link @if($activeTab == 'password') active @endif" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button" role="tab" aria-controls="password" aria-selected="false">Password</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link @if($activeTab == 'privacy') active @endif" id="privacy-tab" data-bs-toggle="tab" data-bs-target="#privacy" type="button" role="tab" aria-controls="privacy" aria-selected="false">Privacy</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link @if($activeTab == 'danger') active @endif" id="danger-tab" data-bs-toggle="tab" data-bs-target="#danger" type="button" role="tab" aria-controls="danger" aria-selected="false">Danger Zone</button>
                </li>
            </ul>

            <div class="tab-content" id="profileTabsContent">
                <div class="tab-pane fade @if($activeTab == 'profile') show active @endif" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    @include('profile.sections.profile-info')
                </div>

                <div class="tab-pane fade @if($activeTab == 'password') show active @endif" id="password" role="tabpanel" aria-labelledby="password-tab">
                    @include('profile.sections.change-password')
                </div>

                <div class="tab-pane fade @if($activeTab == 'privacy') show active @endif" id="privacy" role="tabpanel" aria-labelledby="privacy-tab">
                    @include('profile.sections.privacy-settings')
                </div>

                <div class="tab-pane fade @if($activeTab == 'danger') show active @endif" id="danger" role="tabpanel" aria-labelledby="danger-tab">
                    @include('profile.sections.danger-zone')
                </div>
            </div>
        </div>
    </div>
</div>