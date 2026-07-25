@extends ("layouts/" . auth()->user()->type)
@section("title", "Appointments")

@section ("content")

    @php
        $type = auth()->user()->type;
    @endphp

    @if ($type == "patient")

        <div class="card">
            <div class="card-body pt-0">
                <div class="user-tabs">
                    <ul class="nav nav-tabs nav-tabs-bottom nav-justified flex-wrap">
                        <li class="nav-item">
                            <a class="nav-link active" href="#pat_appointments" data-toggle="tab">Appointments</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#pres" data-toggle="tab"><span>Prescription <i class="premium"></i></span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#medical" data-toggle="tab"><span class="med-records">Medical Records <i class="premium"></i></span></a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content">
                    
                    <!-- Appointment Tab -->
                    <div id="pat_appointments" class="tab-pane fade show active">
                        <div class="card card-table mb-0">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover table-center mb-0">
                                        <thead>
                                            <tr>
                                                <th>Doctor</th>
                                                <th>Appt Date</th>
                                                <th>Booking Date</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($bookings as $booking)
                                                <tr>
                                                    <td>
                                                        <h2 class="table-avatar">
                                                            <a href="{{ url('/doctors/' . $booking->doctor->id . '/profile') }}" class="avatar avatar-sm mr-2">
                                                                <img class="avatar-img rounded-circle" src="{{ $booking->doctor->profile_image }}"
                                                                    alt="{{ $booking->doctor->name }}" />
                                                            </a>
                                                            <a href="{{ url('/doctors/' . $booking->doctor->id . '/profile') }}">Dr. {{ $booking->doctor->name }} <span>Dental</span></a>
                                                        </h2>
                                                    </td>
                                                    <td>{{ $booking->day . " " . $booking->date }} <span class="d-block text-info">{{ $booking->from }}</span></td>
                                                    <td>{{ $booking->created_at }}</td>
                                                    <td>${{ $booking->fee }}</td>
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
                    <!-- /Appointment Tab -->
                    
                    <!-- Prescription Tab -->
                    <div class="tab-pane fade" id="pres">
                        <div class="card card-table mb-0">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover table-center mb-0">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Name</th>
                                                <th>Quantity</th>
                                                <th>Days</th>
                                                <th>Times</th>
                                                <th>Doctor</th>
                                                <th></th>
                                            </tr>     
                                        </thead>
                                        <tbody>
                                            
                                        </tbody>    
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Prescription Tab -->

                    <!-- Medical Records Tab -->
                    <div class="tab-pane fade" id="medical">
                        <div class="card card-table mb-0">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover table-center mb-0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Date </th>
                                                <th>Description</th>
                                                <th>Attachment</th>
                                                <th>Created</th>
                                                <th></th>
                                            </tr>     
                                        </thead>

                                        <tbody>
                                            
                                        </tbody>    
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Medical Records Tab -->
                            
                </div>
            </div>
        </div>

    @elseif ($type == "doctor")
        <div class="appointments">
            @foreach ($bookings as $booking)
                <div class="appointment-list">
                    <div class="profile-info-widget">

                        <a href="{{ url('/patients/' . $booking->patient_id . '/profile') }}" class="booking-doc-img">
                            <img src="{{ $booking->profile_image }}"
                                alt="{{ $booking->first_name . ' ' . $booking->last_name }}"
                                style="width: 50px;
                                height: 50px;
                                object-fit: cover;
                                border-radius: 5px;
                                display: block;
                                margin-bottom: 10px;" />
                        </a>

                        <div class="profile-det-info">
                            <h3>
                                <a href="{{ url('/patients/' . $booking->patient_id . '/profile') }}">
                                    {{ $booking->first_name . ' ' . $booking->last_name }}
                                </a>
                            </h3>
                            
                            <div class="patient-details">
                                <h5><i class="far fa-clock"></i> {{ $booking->day . " " . $booking->date . " " . $booking->from }}</h5>
                                <h5><i class="fas fa-map-marker-alt"></i> {{ $booking->city . ", " . $booking->country }}</h5>
                                <h5><i class="fas fa-envelope"></i> {{ $booking->email }}</h5>
                                <h5 class="mb-0"><i class="fas fa-phone"></i> {{ $booking->phone }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="appointment-action">
                        <a href="javascript:void(0);"
                            class="btn btn-sm bg-info-light"
                            onclick="viewAppointmentDetail('{{ json_encode($booking) }}');">
                            <i class="far fa-eye"></i> View
                        </a>&nbsp;

                        @if ($booking->status == "created")
                            <button type="button" class="btn btn-sm bg-success-light"
                                onclick="accept(event, '{{ $booking->id }}');">
                                <i class="fas fa-check"></i> Accept
                            </button>
                        @elseif ($booking->status == "accepted")
                            <button type="button" class="btn btn-sm bg-danger-light"
                                onclick="cancel(event, '{{ $booking->id }}');">
                                <i class="fas fa-times"></i> Cancel
                            </button>&nbsp;

                            <button type="button" class="btn btn-sm bg-info-light"
                                onclick="complete(event, '{{ $booking->id }}');">
                                <i class="fas fa-check"></i> Complete
                            </button>
                        @else
                            <span class="status-{{ $booking->status }}">{{ ucwords($booking->status) }}</span>
                        @endif

                        @if ($booking->invoice_id > 0)
                            &nbsp;<br /><br /><a href="{{ url('/invoices/' . $booking->invoice_id . '/detail') }}"
                                class="btn btn-secondary btn-sm">Invoice</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {!! $bookings_pagination !!}
    @endif

    <script>
        function viewAppointmentDetail(data) {
            data = JSON.parse(data);

            $("#appt_details").modal("show");
            $("#appt_details .appointment-number").html("#" + data.id);
            $("#appt_details .datetime").html(data.day + " " + data.date + " at " + data.from);
            $("#appt_details .status").html(data.status[0].toUpperCase() + data.status.substr(1));
            $("#appt_details .amount").html("$" + data.fee);

            $("#topup_status").html(data.status[0].toUpperCase() + data.status.substr(1));
            $("#topup_status").attr("class", "status-" + data.status);
        }

        function accept(event, id) {
            const node = event.currentTarget;

            swal.fire({
                title: "Are you sure you want to accept this appointment ?",
                showCancelButton: true,
                confirmButtonText: "Yes"
            }).then(async function (result) {
                if (result.isConfirmed) {
                    node.setAttribute("disabled", "disabled");

                    try {
                        const formData = new FormData();
                        formData.append("id", id);

                        const response = await axios.post(
                            baseUrl + "/appointments/accept",
                            formData,
                            {
                                headers: {
                                    Authorization: "Bearer " + localStorage.getItem(accessTokenKey)
                                }
                            }
                        );

                        if (response.data.status == "success") {
                            window.location.reload();
                        } else {
                            swal.fire("Error", response.data.message, "error");
                            node.removeAttribute("disabled");
                        }
                    } catch (exp) {
                        console.log(exp.message);
                        node.removeAttribute("disabled");
                    }
                }
            });
        }

        function cancel(event, id) {
            const node = event.currentTarget;

            swal.fire({
                title: "Are you sure you want to cancel this appointment ?",
                showCancelButton: true,
                confirmButtonText: "Yes"
            }).then(async function (result) {
                if (result.isConfirmed) {
                    node.setAttribute("disabled", "disabled");

                    try {
                        const formData = new FormData();
                        formData.append("id", id);

                        const response = await axios.post(
                            baseUrl + "/appointments/cancel",
                            formData,
                            {
                                headers: {
                                    Authorization: "Bearer " + localStorage.getItem(accessTokenKey)
                                }
                            }
                        );

                        if (response.data.status == "success") {
                            window.location.reload();
                        } else {
                            swal.fire("Error", response.data.message, "error");
                            node.removeAttribute("disabled");
                        }
                    } catch (exp) {
                        console.log(exp.message);
                        node.removeAttribute("disabled");
                    }
                }
            });
        }

        function complete(event, id) {
            const node = event.currentTarget;

            swal.fire({
                title: "Are you sure you want to mark this appointment as complete ?",
                showCancelButton: true,
                confirmButtonText: "Yes"
            }).then(async function (result) {
                if (result.isConfirmed) {
                    node.setAttribute("disabled", "disabled");

                    try {
                        const formData = new FormData();
                        formData.append("id", id);

                        const response = await axios.post(
                            baseUrl + "/appointments/complete",
                            formData,
                            {
                                headers: {
                                    Authorization: "Bearer " + localStorage.getItem(accessTokenKey)
                                }
                            }
                        );

                        if (response.data.status == "success") {
                            window.location.reload();
                        } else {
                            swal.fire("Error", response.data.message, "error");
                            node.removeAttribute("disabled");
                        }
                    } catch (exp) {
                        console.log(exp.message);
                        node.removeAttribute("disabled");
                    }
                }
            });
        }
    </script>

@endsection