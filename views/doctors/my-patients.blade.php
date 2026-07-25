@extends ("layouts/doctor")
@section("title", "My Patients")

@section ("content")

    <div class="row row-grid">

        @foreach ($patients as $patient)
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card widget-profile pat-widget-profile">
                    <div class="card-body">
                        <div class="pro-widget-content">
                            <div class="profile-info-widget">
                                <a href="patient-profile.html" class="booking-doc-img">
                                    <img src="{{ $patient->profile_image }}"
                                        alt="{{ $patient->name }}"
                                        onerror="event.target.src = baseUrl + '/img/user-placeholder.png'" />
                                </a>
                                <div class="profile-det-info">
                                    <h3><a href="{{ url('/patients/' . $patient->patient_id . '/profile') }}">{{ $patient->name }}</a></h3>
                                    
                                    <div class="patient-details">
                                        <h5><b>Patient ID :</b> P{{ $patient->patient_id }}</h5>
                                        <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> {{ $patient->city . ", " . $patient->country }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="patient-info">
                            <ul>
                                <li>Phone <span>{{ $patient->phone }}</span></li>
                                <li>Age <span>{{ convert_dob_to_years($patient->dob) }} Years, {{ ucfirst($patient->gender) }}</span></li>
                                <li>Blood Group <span>{{ $patient->blood_group }}</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

    </div>

@endsection