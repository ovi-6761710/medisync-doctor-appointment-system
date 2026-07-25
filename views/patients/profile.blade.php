@extends ("layouts/patient-profile")
@section ("title", $patient->name . " | Profile")

@section ("content")

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
                    <div class="text-right">
                        <a href="{{ url('/patients/' . $patient->user_id . '/prescriptions/add') }}"
                            class="add-new-btn"
                            style="color: black;">Add Prescription</a>
                    </div>
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
                    <div class="text-right">
                        <button type="button" class="add-new-btn no-border"
                            style="border: none;
                                color: black;"
                                onclick="$('#add-medical-record-modal').modal('show');">
                            Add Medical Records
                        </button>
                    </div>
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

    <input type="hidden" id="patient-id" value="{{ $patient->user_id }}" />

    <script>
        const patientId = parseInt(document.getElementById("patient-id").value || 0);

        async function addMedicalRecord(event) {
            event.preventDefault();

            const form = document.getElementById("form-add-medical-record");
            form.submit.setAttribute("disabled", "disabled");

            try {
                const formData = new FormData(form);
                formData.append("id", patientId);

                const response = await axios.post(
                    baseUrl + "/medical-records/add",
                    formData
                )

                if (response.data.status == "success") {
                    swal.fire("Medical Record", response.data.message, "success")
                        .then(function () {
                            window.location.reload();
                        });
                } else {
                    swal.fire("Error", response.data.message, response.data.status);
                }
            } catch (exp) {
                console.log(exp.message);
            } finally {
                form.submit.removeAttribute("disabled");
            }
        }
    </script>

@endsection