@extends ("layouts/app")
@section("title", "Dashboard")

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
                <div class="col-md-5 col-lg-4 col-xl-3 theiaStickySidebar">
                    
                    <!-- Profile Sidebar -->
                    <div class="profile-sidebar">
                        <div class="widget-profile pro-widget-content">
                            <div class="profile-info-widget">
                                <a href="#" class="booking-doc-img">
                                    <img src="{{ $profile->profile_image ?? '' }}"
                                        alt="{{ $profile->name ?? '' }}"
                                        onerror="event.target.src = baseUrl + '/img/user-placeholder.png'" />
                                </a>
                                <div class="profile-det-info">
                                    <h3>Dr. {{ $profile->name ?? "" }}</h3>
                                    
                                    <div class="patient-details">
                                        <h5 class="mb-0">{!! $profile->about ?? "" !!}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="dashboard-widget">
                            <nav class="dashboard-menu">
                                <ul>
                                    <li class="{{ $url == url('/dashboard') ? 'active' : '' }}">
                                        <a href="{{ url('/dashboard') }}">
                                            <i class="fas fa-columns"></i>
                                            <span>Dashboard</span>
                                        </a>
                                    </li>
                                    <li class="{{ $url == url('/appointments') ? 'active' : '' }}">
                                        <a href="{{ url('/appointments') }}">
                                            <i class="fas fa-calendar-check"></i>
                                            <span>Appointments</span>
                                        </a>
                                    </li>
                                    <li class="{{ $url == url('/my-patients') ? 'active' : '' }}">
                                        <a href="{{ url('/my-patients') }}">
                                            <i class="fas fa-user-injured"></i>
                                            <span>My Patients</span>
                                        </a>
                                    </li>
                                    <li class="{{ $url == url('/schedule-timings') ? 'active' : '' }}">
                                        <a href="{{ url('/schedule-timings') }}">
                                            <i class="fas fa-hourglass-start"></i>
                                            <span>Schedule Timings</span>
                                        </a>
                                    </li>
                                    {{--<li class="{{ $url == url('/invoices') ? 'active' : '' }}">
                                        <a href="{{ url('/invoices') }}">
                                            <i class="fas fa-file-invoice"></i>
                                            <span>Invoices</span>
                                        </a>
                                    </li>--}}
                                    <li class="{{ $url == url('/profile-settings') ? 'active' : '' }}">
                                        <a href="{{ url('/profile-settings') }}">
                                            <i class="fas fa-user-cog"></i>
                                            <span>Profile Settings</span>
                                        </a>
                                    </li>
                                    <li class="{{ $url == url('/social-media') ? 'active' : '' }}">
                                        <a href="{{ url('/social-media') }}">
                                            <i class="fas fa-share-alt"></i>
                                            <span>Social Media</span>
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
                    <!-- /Profile Sidebar -->
                    
                </div>
                
                <div class="col-md-7 col-lg-8 col-xl-9">
                    @yield("content")
                </div>
            </div>

        </div>

    </div>		
    <!-- /Page Content -->

@endsection