@extends ("layouts/app")
@section ("title", "Dashboard")

@section ("main")

    @php
        $url = request()->url();
    @endphp

    <!-- Breadcrumb -->
    <div class="breadcrumb-bar">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-12 col-12">
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">@yield("title")</li>
                        </ol>
                    </nav>
                    <h2 class="breadcrumb-title">@yield("title")</h2>
                </div>
            </div>
        </div>
    </div>
    <!-- /Breadcrumb -->

    <!-- Page Content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
            
                <!-- Profile Sidebar -->
                <div class="col-md-5 col-lg-4 col-xl-3 theiaStickySidebar">
                    <div class="profile-sidebar">
                        <div class="widget-profile pro-widget-content">
                            <div class="profile-info-widget">
                                <a href="#" class="booking-doc-img">
                                    <img src="{{ $profile->profile_image ?? '' }}"
                                        alt="{{ $profile->name ?? '' }}"
                                        onerror="event.target.src = baseUrl + '/img/user-placeholder.png'" />
                                </a>
                                <div class="profile-det-info">
                                    <h3>{{ $profile->name ?? "" }}</h3>
                                    <div class="patient-details">
                                        <h5><i class="fas fa-birthday-cake"></i> {{ date("d M, Y", strtotime($profile->dob ?? "")) }} - {{ convert_dob_to_years($profile->dob ?? "") }} years</h5>
                                        <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> {{ $profile->city ?? "" }}, {{ $profile->country ?? "" }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="dashboard-widget">
                            <nav class="dashboard-menu">
                                <ul>
                                    <li class="{{ $url == url('/dashboard') ? 'active' : '' }}">
                                        <a href="{{ url('/') }}">
                                            <i class="fas fa-columns"></i>
                                            <span>Dashboard</span>
                                        </a>
                                    </li>
                                    <li class="{{ $url == url('/favourites') ? 'active' : '' }}">
                                        <a href="javascript:void(0);"
                                            onclick="swal.fire('Error', 'This feature is still under development', 'info');">
                                            <i class="fas fa-bookmark"></i>
                                            <span>Favourites</span>
                                        </a>
                                    </li>
                                    <li class="{{ $url == url('/appointments') ? 'active' : '' }}">
                                        <a href="{{ url('/appointments') }}">
                                            <i class="fas fa-calendar-check"></i>
                                            <span>Appointments</span>
                                        </a>
                                    </li>
                                    <li class="{{ $url == url('/profile-settings') ? 'active' : '' }}">
                                        <a href="{{ url('/profile-settings') }}">
                                            <i class="fas fa-user-cog"></i>
                                            <span>Profile Settings</span>
                                        </a>
                                    </li>
                                    <li class="{{ $url == url('/change-password') ? 'active' : '' }}">
                                        <a href="{{ url('/change-password') }}">
                                            <i class="fas fa-lock"></i>
                                            <span>Change Password</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ url('/logout') }}">
                                            <i class="fas fa-sign-out-alt"></i>
                                            <span>Logout</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>

                    </div>
                </div>
                <!-- /Profile Sidebar -->
                
                <div class="col-md-7 col-lg-8 col-xl-9">
                    @yield("content")
                </div>
            </div>
        </div>
    </div>

@endsection