@extends ("layouts/app")
@section ("title", $doctor->name)

@section ("main")

    @php
        $user = null;
    @endphp

    @if (auth()->check())
        @php
            $user = auth()->user();
        @endphp
    @endif

    <!-- Breadcrumb -->
    <div class="breadcrumb-bar">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-12 col-12">
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('/search') }}">Doctors</a></li>
                            <li class="breadcrumb-item" style="color: white;">{{ $doctor->name ?? "" }}</li>
                            <li class="breadcrumb-item active" aria-current="page">Profile</li>
                        </ol>
                    </nav>
                    <h2 class="breadcrumb-title">{{ $doctor->name ?? "" }}</h2>
                </div>
            </div>
        </div>
    </div>
    <!-- /Breadcrumb -->

    <!-- Page Content -->
    <div class="content">
        <div class="container">

            @include ("layouts/single-doctor", [
                "doctor" => $doctor,
                "social_media" => $social_media
            ])
            
            <!-- Doctor Details Tab -->
            <div class="card">
                <div class="card-body pt-0">
                
                    <!-- Tab Menu -->
                    <nav class="user-tabs mb-4">
                        <ul class="nav nav-tabs nav-tabs-bottom nav-justified">
                            <li class="nav-item">
                                <a class="nav-link active" href="#doc_overview" data-toggle="tab">Overview</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#doc_reviews" data-toggle="tab">
                                    Reviews <i class="premium"></i>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#doc_business_hours" data-toggle="tab">Business Hours</a>
                            </li>
                        </ul>
                    </nav>
                    <!-- /Tab Menu -->
                    
                    <!-- Tab Content -->
                    <div class="tab-content pt-0">
                    
                        <!-- Overview Content -->
                        <div role="tabpanel" id="doc_overview" class="tab-pane fade show active">
                            <div class="row">
                                <div class="col-md-12 col-lg-9">
                                
                                    <!-- About Details -->
                                    <div class="widget about-widget">
                                        <h4 class="widget-title">About Me</h4>
                                        <div>{!! $doctor->about !!}</div>
                                    </div>
                                    <!-- /About Details -->
                                
                                    <!-- Education Details -->
                                    <div class="widget education-widget">
                                        <h4 class="widget-title">Education</h4>
                                        <div class="experience-box">
                                            <ul class="experience-list">
                                                @foreach ($doctor->educations as $education)
                                                    <li>
                                                        <div class="experience-user">
                                                            <div class="before-circle"></div>
                                                        </div>
                                                        <div class="experience-content">
                                                            <div class="timeline-content">
                                                                <a href="javascript:void(0);" class="name">{{ $education->institute }}</a>
                                                                <div>{{ $education->degree }}</div>
                                                                <span class="time">{{ $education->year }}</span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- /Education Details -->
                            
                                    <!-- Experience Details -->
                                    <div class="widget experience-widget">
                                        <h4 class="widget-title">Work & Experience</h4>
                                        <div class="experience-box">
                                            <ul class="experience-list">
                                                @foreach ($doctor->experiences as $experience)
                                                    <li>
                                                        <div class="experience-user">
                                                            <div class="before-circle"></div>
                                                        </div>
                                                        <div class="experience-content">
                                                            <div class="timeline-content">
                                                                <a href="#/" class="name">{{ $experience->name }}</a>
                                                                <span class="time">{{ $experience->from }} - {{ $experience->to }}</span>
                                                                <p>{{ $experience->designation }}</p>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- /Experience Details -->
                        
                                    <!-- Awards Details -->
                                    <div class="widget awards-widget">
                                        <h4 class="widget-title">Awards</h4>
                                        <div class="experience-box">
                                            <ul class="experience-list">
                                                @foreach ($doctor->awards as $award)
                                                    <li>
                                                        <div class="experience-user">
                                                            <div class="before-circle"></div>
                                                        </div>
                                                        <div class="experience-content">
                                                            <div class="timeline-content">
                                                                <p class="exp-year">{{ $award->year }}</p>
                                                                <h4 class="exp-title">{{ $award->award }}</h4>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- /Awards Details -->
                                    
                                    <!-- Services List -->
                                    <div class="service-list">
                                        <h4>Services</h4>
                                        <ul class="clearfix">
                                            @foreach (explode(",", $doctor->services) as $service)
                                                <li>{{ $service }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <!-- /Services List -->
                                    
                                    <!-- Specializations List -->
                                    <div class="service-list">
                                        <h4>Specializations</h4>
                                        <ul class="clearfix">
                                            @foreach (explode(",", $doctor->specializations) as $specialization)
                                                <li>{{ $specialization }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <!-- /Specializations List -->

                                </div>
                            </div>
                        </div>
                        <!-- /Overview Content -->
                        
                        <!-- Reviews Content -->
                        <div role="tabpanel" id="doc_reviews" class="tab-pane fade">
                        
                            <!-- Review Listing -->
                            <div class="widget review-listing">

                            </div>
                            <!-- /Review Listing -->
                        
                            @if (!$has_reviewed)
                                <!-- Write Review -->
                                <div class="write-review">
                                    <h4>Write a review for <strong>Dr. {{ $doctor->name }}</strong></h4>
                                    
                                    <!-- Write Review Form -->
                                    <form onsubmit="writeReview(event);" id="form-review">
                                        <div class="form-group">
                                            <label>Review</label>
                                            <div class="star-rating">
                                                <input id="star-5" type="radio" name="rating" value="5">
                                                <label for="star-5" title="5 stars">
                                                    <i class="active fa fa-star"></i>
                                                </label>
                                                <input id="star-4" type="radio" name="rating" value="4">
                                                <label for="star-4" title="4 stars">
                                                    <i class="active fa fa-star"></i>
                                                </label>
                                                <input id="star-3" type="radio" name="rating" value="3">
                                                <label for="star-3" title="3 stars">
                                                    <i class="active fa fa-star"></i>
                                                </label>
                                                <input id="star-2" type="radio" name="rating" value="2">
                                                <label for="star-2" title="2 stars">
                                                    <i class="active fa fa-star"></i>
                                                </label>
                                                <input id="star-1" type="radio" name="rating" value="1">
                                                <label for="star-1" title="1 star">
                                                    <i class="active fa fa-star"></i>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Title of your review</label>
                                            <input class="form-control" type="text" name="title" placeholder="If you could say it in one sentence, what would you say?">
                                        </div>
                                        <div class="form-group">
                                            <label>Your review</label>
                                            <textarea id="review_desc" name="review" class="form-control"></textarea>
                                        </div>
                                        <hr>
                                        <div class="submit-section">
                                            <button type="submit" name="submit" class="btn btn-primary submit-btn">Add Review</button>
                                        </div>
                                    </form>
                                    <!-- /Write Review Form -->
                                    
                                </div>
                                <!-- /Write Review -->
                            @endif
                
                        </div>
                        <!-- /Reviews Content -->
                        
                        <!-- Business Hours Content -->
                        <div role="tabpanel" id="doc_business_hours" class="tab-pane fade">
                            <div class="row">
                                <div class="col-md-6 offset-md-3">
                                
                                    <!-- Business Hours Widget -->
                                    <div class="widget business-widget">
                                        <div class="widget-content">
                                            <div class="listing-hours">

                                                @php
                                                    $current_hour = date("H:i");
                                                @endphp

                                                @if (count($today_timings) > 0)
                                                    <div class="listing-day current">
                                                        <div class="day">Today <span>{{ date("d M, Y") }}</span></div>
                                                        <div class="time-items">
                                                            @foreach ($today_timings as $today_timing)
                                                                <span class="time">{{ $today_timing->from }} - {{ $today_timing->to }}</span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif

                                                @foreach ($timings as $key => $timing)
                                                    <div class="listing-day">
                                                        <div class="day">{{ $key }}</div>
                                                        <div class="time-items">
                                                            @foreach ($timing as $t)
                                                                <span class="time">{{ $t->from . " - " . $t->to }}</span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach

                                            </div>
                                        </div>
                                    </div>
                                    <!-- /Business Hours Widget -->
                            
                                </div>
                            </div>
                        </div>
                        <!-- /Business Hours Content -->
                        
                    </div>
                </div>
            </div>
            <!-- /Doctor Details Tab -->

        </div>
    </div>      
    <!-- /Page Content -->

    <input type="hidden" id="doctor-id" value="{{ $doctor->user_id }}" />

    <script>
        const doctorId = parseInt(document.getElementById("doctor-id").value ?? "0");

        async function writeReview(event) {
            event.preventDefault();

            const form = document.getElementById("form-review");
            form.submit.setAttribute("disabled", "disabled");

            try {
                const formData = new FormData(form);
                formData.append("id", doctorId);

                const response = await axios.post(
                    baseUrl + "/reviews/write",
                    formData
                );

                if (response.data.status == "success") {
                    swal.fire("Write Review", response.data.message, "success")
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

        window.addEventListener("load", function () {
            $("textarea[name='review']").richText();
        });
    </script>

@endsection