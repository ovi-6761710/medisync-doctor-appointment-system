@extends ("layouts/app")
@section ("title", $patient->name)

@section ("main")

    <!-- Breadcrumb -->
    <div class="breadcrumb-bar">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-12 col-12">
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('/my-patients') }}">Patients</a></li>
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
                <div class="col-md-5 col-lg-4 col-xl-3 theiaStickySidebar dct-dashbd-lft">
                
                    <!-- Profile Widget -->
                    <div class="card widget-profile pat-widget-profile">
                        <div class="card-body">
                            <div class="pro-widget-content">
                                <div class="profile-info-widget">
                                    <a href="{{ url('/patients/' . $patient->user_id . '/profile') }}" class="booking-doc-img">
                                        <img src="{{ $patient->profile_image ?? '' }}" alt="{{ $patient->name ?? '' }}" />
                                    </a>
                                    <div class="profile-det-info">
                                        <h3>{{ $patient->name ?? "" }}</h3>
                                        
                                        <div class="patient-details">
                                            <h5><b>Patient ID :</b> PT{{ $patient->user_id ?? "" }}</h5>
                                            <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> {{ ($patient->city ?? "") . ", " . ($patient->country ?? "") }}s</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="patient-info">
                                <ul>
                                    <li>Phone <span>{{ $patient->phone ?? "" }}</span></li>
                                    <li>Age <span>{{ convert_dob_to_years($patient->dob ?? "") }} Years, {{ $patient->gender ?? "" }}</span></li>
                                    <li>Blood Group <span>{{ $patient->blood_group ?? "" }}</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- /Profile Widget -->
                    
                </div>

                <div class="col-md-7 col-lg-8 col-xl-9 dct-appoinment">
                    @yield("content")
                </div>
            </div>
        </div>
    </div>

@endsection