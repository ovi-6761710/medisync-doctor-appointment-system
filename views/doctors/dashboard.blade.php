@extends ("layouts/doctor")
@section("title", "Dashboard")

@section ("content")
    
    <div class="row">
        <div class="col-md-12">
            <div class="card dash-card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 col-lg-4">
                            <div class="dash-widget dct-border-rht">
                                <div class="circle-bar circle-bar1">
                                    <div class="circle-graph1" data-percent="0">
                                        <img src="{{ asset('/assets/img/icon-01.png') }}" class="img-fluid" alt="patient">
                                    </div>
                                </div>
                                <div class="dash-widget-info">
                                    <h6>Total patients</h6>
                                    <h3>{{ $patients }}</h3>
                                    <p class="text-muted">Till today</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-12 col-lg-4">
                            <div class="dash-widget">
                                <div class="circle-bar circle-bar3">
                                    <div class="circle-graph3" data-percent="0">
                                        <img src="{{ asset('/assets/img/icon-03.png') }}" class="img-fluid" alt="Patient">
                                    </div>
                                </div>
                                <div class="dash-widget-info">
                                    <h6>Appoinments</h6>
                                    <h3>{{ $bookings_count }}</h3>
                                    <p class="text-muted">Till today</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h4 class="mb-4">Patient Appoinment</h4>

            <div class="card card-table mb-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-center mb-0">
                            <thead>
                                <tr>
                                    <th>Patient Name</th>
                                    <th>Appointment Date</th>
                                    <th>Created at</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bookings as $booking)
                                    <tr>
                                        <td>
                                            <h2 class="table-avatar">
                                                <a href="{{ url('/patients/' . $booking->patient_id . '/profile') }}" class="avatar avatar-sm mr-2">
                                                    <img class="avatar-img rounded-circle" src="{{ $booking->profile_image }}"
                                                        alt="{{ $booking->first_name . ' ' . $booking->last_name }}" />
                                                </a>
                                                <a href="{{ url('/patients/' . $booking->patient_id . '/profile') }}">{{ $booking->first_name . ' ' . $booking->last_name }}</a>
                                            </h2>
                                        </td>
                                        <td>{{ $booking->day . " " . $booking->date }} <span class="d-block text-info">{{ $booking->from }}</span></td>
                                        <td>{{ $booking->created_at }}</td>
                                        <td><span class="badge badge-pill status-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>        
                    </div>
                </div>

                <div class="card-footer">
                    {!! $bookings_pagination !!}
                </div>
            </div>
        </div>
    </div>

@endsection