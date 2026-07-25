@extends ("layouts/app")
@section("title", "Booking")

@section ("main")
    <!-- Breadcrumb -->
    <div class="breadcrumb-bar">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-12 col-12">
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Booking</li>
                        </ol>
                    </nav>
                    <h2 class="breadcrumb-title">Booking</h2>
                </div>
            </div>
        </div>
    </div>
    <!-- /Breadcrumb -->

    <!-- Page Content -->
    <div class="content">
        <div class="container">
        
            <div class="row">
                <div class="col-12">
                
                    <div class="card">
                        <div class="card-body">
                            <div class="booking-doc-info">
                                <a href="{{ url('/doctors/' . $doctor->user_id . '/profile') }}" class="booking-doc-img">
                                    <img src="{{ $doctor->profile_image }}"
                                        onerror="this.remove();"
                                        alt="{{ $doctor->name }}" />
                                </a>
                                <div class="booking-info">
                                    <h4><a href="{{ url('/doctors/' . $doctor->id . '/profile') }}">Dr. {{ $doctor->name }}</a></h4>
                                    
                                    {{--<div class="rating">
                                        <i class="fas fa-star filled"></i>
                                        <i class="fas fa-star filled"></i>
                                        <i class="fas fa-star filled"></i>
                                        <i class="fas fa-star filled"></i>
                                        <i class="fas fa-star"></i>
                                        <span class="d-inline-block average-rating">35</span>
                                    </div>--}}

                                    <p class="text-muted mb-0"><i class="fas fa-map-marker-alt"></i> {{ $doctor->city . ", " . $doctor->country }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Schedule Widget -->
                    <div class="card booking-schedule schedule-widget">
                    
                        <!-- Schedule Header -->
                        <div class="schedule-header">
                            <div class="row">
                                <div class="col-md-12">
                                
                                    <!-- Day Slot -->
                                    <div class="day-slot">
                                        <ul>
                                            <li class="left-arrow" style="left: -5px;">
                                                <a href="javascript:void(0);" onclick="scrollToLeft();">
                                                    <i class="fa fa-chevron-left"></i>
                                                </a>
                                            </li>

                                            <li style="width: 99%;">
                                                <div class="calendar" id="calendar">
                                                    @foreach ($calendar as $c)
                                                        <div class="day {{ $c['day'] == date('l') ? 'selected' : '' }}"
                                                            onclick="daySelected(event, '{{ $c['day'] }}', '{{ $c['date'] }}');">
                                                            <div class="date">{{ $c["day"] }}</div>
                                                            <div class="weekday">{{ $c["date"] }}</div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </li>

                                            {{--<li>
                                                <span>Mon</span>
                                                <span class="slot-date">11 Nov <small class="slot-year">2019</small></span>
                                            </li>--}}

                                            <li class="right-arrow" style="right: -20px;">
                                                <a href="javascript:void(0);" onclick="scrollToRight();">
                                                    <i class="fa fa-chevron-right"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- /Day Slot -->
                                    
                                </div>
                            </div>
                        </div>
                        <!-- /Schedule Header -->
                        
                        <!-- Schedule Content -->
                        <div class="schedule-cont">
                            <div class="row">
                                <div class="col-md-12">
                                
                                    <!-- Time Slot -->
                                    <div class="time-slot">
                                        <p class="text-danger" id="time-format-message"
                                            style="display: none;">Time is in 24-hour format.</p>

                                        <ul class="clearfix" id="available-slots">
                                            {{--<li>
                                                <a class="timing selected" href="#">
                                                    <span>10:00</span> <span>AM</span>
                                                </a>
                                            </li>--}}
                                        </ul>
                                    </div>
                                    <!-- /Time Slot -->
                                    
                                </div>
                            </div>
                        </div>
                        <!-- /Schedule Content -->
                        
                    </div>
                    <!-- /Schedule Widget -->
                    
                    <!-- Submit Section -->
                    <div class="submit-section proceed-btn text-right">
                        <a href="{{ url('/checkout') }}" class="btn btn-primary submit-btn">Proceed to Pay</a>
                    </div>
                    <!-- /Submit Section -->
                    
                </div>
            </div>
        </div>

    </div>		
    <!-- /Page Content -->

    <input type="hidden" id="timings" value="{{ json_encode($timings); }}" />

    <script>
        const calendar = document.getElementById('calendar');
        let timings = document.getElementById("timings").value;
        timings = JSON.parse(timings);
        const selectedTiming = JSON.parse(localStorage.getItem("selectedTiming"));

        const today = new Date();
        const day = today.getDate().toString().padStart(2, '0');
        const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        const month = monthNames[today.getMonth()];
        const year = today.getFullYear();
        const formatted = `${day} ${month}, ${year}`;

        renderTimingSlots(today.toLocaleDateString("en-US", {
            weekday: "long"
        }), formatted);

        function scrollToRight() {
            calendar.scrollBy({
                left: 160,
                behavior: 'smooth'
            });
        }

        function scrollToLeft() {
            calendar.scrollBy({
                left: -200,
                behavior: 'smooth'
            });
        }

        function renderTimingSlots(day, date) {
            let html = "";
            for (let a = 0; a < timings.length; a++) {
                if (timings[a].day == day) {

                    let flag = false;
                    if (selectedTiming != null && typeof selectedTiming.id !== "undefined" && selectedTiming.id == timings[a].id) {
                        flag = true;
                    }

                    html += `<li>
                        <a class="timing ` + (flag ? "selected" : "") + `" href="javascript:void(0);"
                            data-id="` + timings[a].id + `"
                            onclick="selectTiming('` + timings[a].id + `', '` + date + `', '` + timings[a].day + `', '` + timings[a].from + `', '` + timings[a].to + `');">
                            <span>` + timings[a].from + `</span> -
                            <span>` + timings[a].to + `</span>
                        </a>
                    </li>`;
                }
            }

            document.getElementById("available-slots").innerHTML = (html == "") ? ("No slots for " + day) : html;

            if (html == "") {
                document.getElementById("time-format-message").style.display = "none";
            } else {
                document.getElementById("time-format-message").style.display = "";
            }
        }

        function daySelected(event, day, date) {
            $(".day").attr("class", "day");
            event.currentTarget.className = "day selected";
            renderTimingSlots(day, date);
        }

        function selectTiming(id, date, day, from, to) {
            const timing = document.querySelectorAll(".timing");
            for (let a = 0; a < timing.length; a++) {
                if (timing[a].getAttribute("data-id") == id) {
                    if (Array.from(timing[a].classList).includes("selected")) {
                        timing[a].setAttribute("class", "timing");
                    } else {
                        timing[a].setAttribute("class", "timing selected");
                    }
                } else {
                    timing[a].setAttribute("class", "timing");
                }
            }

            let selectedTiming = localStorage.getItem("selectedTiming") || "{}";
            selectedTiming = JSON.parse(selectedTiming);
            
            if (selectedTiming.id == id) {
                localStorage.removeItem("selectedTiming");
            } else {
                localStorage.setItem("selectedTiming", JSON.stringify({
                    id: id,
                    date: date,
                    day: day,
                    from: from,
                    to: to
                }));
            }
        }
    </script>

    <style>
        .calendar {
            display: flex;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 10px;
            margin-top: 20px;
            margin-left: 10px;
            overflow-x: scroll;
            max-width: 100%;
            scrollbar-width: none;
        }
        .day {
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
            padding: 10px;
            text-align: center;
            transition: 0.2s;
            min-width: 140px;
        }
        .day:hover,
        .day.selected {
            background: #007bff;
            color: white;
            cursor: pointer;
        }
        .day:hover .weekday,
        .day.selected .weekday {
            color: white;
        }
        .date {
            font-size: 18px;
            font-weight: bold;
        }
        .weekday {
            color: #666;
            font-size: 14px;
        }
    </style>
@endsection