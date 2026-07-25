@extends ("layouts/doctor")
@section("title", "Schedule timings")

@section ("content")
                    
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Schedule Timings</h4>
                    <div class="profile-box">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card schedule-widget mb-0">
                                
                                    <!-- Schedule Header -->
                                    <div class="schedule-header">
                                    
                                        <!-- Schedule Nav -->
                                        <div class="schedule-nav">
                                            <ul class="nav nav-tabs nav-justified">
                                                @foreach ($days as $day)
                                                    <li class="nav-item">
                                                        <a class="nav-link {{ date('l') == $day ? 'active' : '' }}" data-toggle="tab" href="#slot_{{ $day }}">{{ $day }}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <!-- /Schedule Nav -->
                                        
                                    </div>
                                    <!-- /Schedule Header -->
                                    
                                    <!-- Schedule Content -->
                                    <div class="tab-content schedule-cont">

                                        @foreach ($days as $day)
                                            <!-- Slot -->
                                            <div id="slot_{{ $day }}" class="tab-pane fade show {{ date('l') == $day ? 'active' : '' }}">
                                                <h4 class="card-title d-flex justify-content-between">
                                                    <span>Time Slots</span> 
                                                    
                                                    <a href="javascript:void(0);" class="edit-link" onclick="timing.editSlots('{{ $day }}');">
                                                        <i class="fa fa-edit mr-1"></i>
                                                        Edit
                                                    </a>
                                                </h4>
                                                
                                                <!-- Slot List -->
                                                <div class="doc-times">
                                                    @foreach ($timings as $timing)
                                                        @if ($timing->day == $day)
                                                            <div class="doc-slot-list">
                                                                {{ convert_time_for_slot($timing->from) }} - {{ convert_time_for_slot($timing->to) }}
                                                                <a href="javascript:void(0);" class="delete_schedule"
                                                                    onclick="timing.deleteSlot(event);"
                                                                    data-timing-id="{{ $timing->id }}">
                                                                    <i class="fa fa-times"></i>
                                                                </a>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                                <!-- /Slot List -->
                                                
                                            </div>
                                            <!-- / Slot -->
                                        @endforeach

                                    </div>
                                    <!-- /Schedule Content -->
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let  = "";
        const slots = [];

        const timing = {
            selectedDay: "",
            availableSlots: [
                "00:00", "00:30", "01:00", "01:30", "02:00", "02:30", "03:00", "03:30", "04:00", "04:30",
                "05:00", "05:30", "06:00", "06:30", "07:00", "07:30", "08:00", "08:30", "09:00", "09:30",
                "10:00", "10:30", "11:00", "11:30", "12:00", "12:30", "13:00", "13:30", "14:00", "14:30",
                "15:00", "15:30", "16:00", "16:30", "17:00", "17:30", "18:00", "18:30", "19:00", "19:30",
                "20:00", "20:30", "21:00", "21:30", "22:00", "22:30", "23:00", "23:30"
            ],
            editSlots(day) {
                this.selectedDay = day;
                $("#edit_time_slot").modal("show");
            },
            async deleteSlot(event) {
                const node = event.currentTarget;
                node.parentElement.remove();

                const id = node.getAttribute("data-timing-id");
                if (id > 0) {
                    try {
                        const formData = new FormData();
                        formData.append("id", id);

                        const response = await axios.post(
                            baseUrl + "/remove-schedule-timings",
                            formData
                        );

                        if (response.data.status == "success") {
                            //
                        } else {
                            swal.fire("Error", response.data.message, "error");
                        }
                    } catch (exp) {
                        console.log(exp.message);
                    }
                }
            },
            addMoreHour() {
                let html = "";

                html += `<div class="row form-row hours-cont">
                    <div class="col-12 col-md-10">
                        <div class="row form-row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label>Start Time</label>
                                    <select name="from" class="form-control">
                                        <option value="">Select time</option>`;

                                        for (let a = 0; a < this.availableSlots.length; a++) {
                                            html += `<option value="` + this.availableSlots[a] + `">` + this.availableSlots[a] + `</option>`
                                        }
                                        
                                    html += `</select>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label>End Time</label>
                                    <select name="to" class="form-control">
                                        <option value="">Select time</option>`

                                        for (let a = 0; a < this.availableSlots.length; a++) {
                                            html += `<option value="` + this.availableSlots[a] + `">` + this.availableSlots[a] + `</option>`
                                        }

                                    html += `</select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12 col-md-2">
                        <label class="d-md-block d-sm-none d-none">&nbsp;</label>
                        <a href="javascript:void(0);" onclick="timing.removeSlot(event);" class="btn btn-danger trash">
                            <i class="far fa-trash-alt"></i>
                        </a>
                    </div>
                </div>`;

                document.getElementById("time-slots").insertAdjacentHTML("beforeend", html);
            },
            removeSlot(event) {
                const node = event.target;
                node.parentElement.parentElement.remove();
            },
            async save(event) {
                const node = event.target;
                node.setAttribute("disabled", "disabled");

                try {
                    const formData = new FormData();
                    formData.append("day", this.selectedDay);

                    const slots = [];
                    const hoursCont = document.querySelectorAll(".hours-cont")

                    for (let a = 0; a < hoursCont.length; a++) {
                        const from = hoursCont[a].querySelector("[name='from']").value || "";
                        const to = hoursCont[a].querySelector("[name='to']").value || "";

                        slots.push({
                            from: from,
                            to: to
                        });
                    }
                    formData.append("slots", JSON.stringify(slots));

                    const response = await axios.post(
                        baseUrl + "/schedule-timings",
                        formData
                    );

                    if (response.data.status == "success") {
                        $('#edit_time_slot').modal('hide');
                        const inserted = response.data.inserted;

                        const docTimes = document.querySelector("#slot_" + this.selectedDay + " .doc-times");
                        let html = "";
                        if (docTimes != null) {
                            for (let a = 0; a < inserted.length; a++) {
                                html += `<div class="doc-slot-list">
                                    ` + inserted[a].from + ` - ` + inserted[a].to + `
                                    <a href="javascript:void(0)" class="delete_schedule"
                                        onclick="timing.deleteSlot(event);"
                                        data-timing-id="` + inserted[a].id + `">
                                        <i class="fa fa-times"></i>
                                    </a>
                                </div>`;
                            }
                            docTimes.insertAdjacentHTML("beforeend", html);
                        }

                        swal.fire("Schedule timing", response.data.message, "success");
                    } else {
                        swal.fire("Error", response.data.message, "error");
                    }
                } catch (exp) {
                    console.log(exp.message);
                } finally {
                    node.removeAttribute("disabled");
                }
            }
        };
    </script>

@endsection