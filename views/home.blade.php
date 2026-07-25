@extends ("layouts/app")
@section("title", "Home")

@section ("main")

    <!-- Home Banner -->
    <section class="section section-search">
        <div class="container-fluid">
            <div class="banner-wrapper">
                <div class="banner-header text-center">
                    <h1>Search Doctor, Make an Appointment</h1>
                    <p></p>
                </div>
                 
                <!-- Search -->
                <div class="search-box">
                    <form method="GET" action="{{ url('/search') }}">
                        <div class="form-group search-location">
                            <input type="text" class="form-control" placeholder="Search Location" name="location" />
                            <span class="form-text">Based on your Location</span>
                        </div>
                        <div class="form-group search-info">
                            <input type="text" class="form-control" placeholder="Search Doctors" name="search" />
                            <span class="form-text">Ex : Dental etc</span>
                        </div>
                        <button type="submit" class="btn btn-primary search-btn"><i class="fas fa-search"></i> <span>Search</span></button>
                    </form>
                </div>
                <!-- /Search -->
                
            </div>
        </div>
    </section>
    <!-- /Home Banner -->
      
    <!-- Clinic and Specialities -->
    <section class="section section-specialities">
        <div class="container-fluid">
            <div class="section-header text-center">
                <h2>Clinic and Specialities</h2>
                <p class="sub-title">Discover clinics and medical professionals across a wide range of specialities — all in one place.</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-9">
                    <!-- Slider -->
                    <div class="specialities-slider slider">
                        @foreach ($specialities as $speciality)
                            <div class="speicality-item text-center">
                                <div class="speicality-img">
                                    <i class="speicality-icon fa {{ $speciality->icon ?? '' }}"></i>
                                    <span><i class="fa fa-circle" aria-hidden="true"></i></span>
                                </div>

                                <p>{{ $speciality->name ?? "" }}</p>
                            </div>
                        @endforeach
                    </div>
                    <!-- /Slider -->
                    
                </div>
            </div>
        </div>   
    </section>   
    <!-- Clinic and Specialities -->
  
    <!-- Popular Section -->
    <section class="section section-doctor">
        <div class="container-fluid">
           <div class="row">
                <div class="col-lg-4">
                    <div class="section-header ">
                        <h2>Book Our Doctor</h2>
                        <p>From Search to Booking — It's Seamless</p>
                    </div>
                    <div class="about-content">
                        <p>Finding the right doctor has never been easier. Our platform lets you browse verified healthcare professionals, check their availability, and book appointments—all from the comfort of your home. Whether it’s a quick consultation or a specialist visit, we simplify the process so you can focus on your well-being.</p>
                        <p>With real-time scheduling, clear profiles, and trusted reviews, you can confidently choose the doctor that’s right for you. Say goodbye to long waiting lines and unanswered calls—book your doctor online in just a few easy steps.</p>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="doctor-slider slider">
                    
                        @foreach ($doctors as $doctor)
                            <div class="profile-widget">
                                <div class="doc-img">
                                    <a href="{{ url('/doctors/' . $doctor->id . '/profile') }}">
                                        <img class="img-fluid" alt="{{ $doctor->name }}"
                                            src="{{ $doctor->profile_image }}" />
                                    </a>
                                </div>
                                <div class="pro-content">
                                    <h3 class="title">
                                        <a href="{{ url('/doctors/' . $doctor->id . '/profile') }}">{{ $doctor->name }}</a>
                                    </h3>
                                    
                                    <p class="speciality">
                                        @foreach (explode(",", $doctor->specializations) as $specialization)
                                            {{ $specialization }} |
                                        @endforeach
                                    </p>

                                    <div class="rating">
                                        @for ($a = 1; $a <= 5; $a++)
                                            @if ($a <= $doctor->ratings)
                                                <i class="fas fa-star filled"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor

                                        <span class="d-inline-block average-rating">({{ $doctor->reviews }})</span>
                                    </div>
                                    <ul class="available-info">
                                        <li>
                                            <i class="fas fa-map-marker-alt"></i> {{ $doctor->city . ", " . $doctor->country }}
                                        </li>
                                        <li>
                                            <i class="far fa-money-bill-alt"></i> ${{ $doctor->fee }}
                                        </li>
                                    </ul>
                                    <div class="row row-sm">
                                        <div class="col-6">
                                            <a href="{{ url('/doctors/' . $doctor->id . '/profile') }}" class="btn view-btn">View Profile</a>
                                        </div>
                                        <div class="col-6">
                                            <a href="{{ url('/doctors/' . $doctor->id . '/booking') }}" class="btn book-btn">Book Now</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <!-- Doctor Widget -->
                        {{--<div class="profile-widget">
                            <div class="doc-img">
                                <a href="doctor-profile.html">
                                    <img class="img-fluid" alt="User Image" src="assets/img/doctors/doctor-01.jpg">
                                </a>
                                <a href="javascript:void(0)" class="fav-btn">
                                    <i class="far fa-bookmark"></i>
                                </a>
                            </div>
                            <div class="pro-content">
                                <h3 class="title">
                                    <a href="doctor-profile.html">Ruby Perrin</a> 
                                    <i class="fas fa-check-circle verified"></i>
                                </h3>
                                <p class="speciality">MDS - Periodontology and Oral Implantology, BDS</p>
                                <div class="rating">
                                    <i class="fas fa-star filled"></i>
                                    <i class="fas fa-star filled"></i>
                                    <i class="fas fa-star filled"></i>
                                    <i class="fas fa-star filled"></i>
                                    <i class="fas fa-star filled"></i>
                                    <span class="d-inline-block average-rating">(17)</span>
                                </div>
                                <ul class="available-info">
                                    <li>
                                        <i class="fas fa-map-marker-alt"></i> Florida, USA
                                    </li>
                                    <li>
                                        <i class="far fa-clock"></i> Available on Fri, 22 Mar
                                    </li>
                                    <li>
                                        <i class="far fa-money-bill-alt"></i> $300 - $1000 
                                        <i class="fas fa-info-circle" data-toggle="tooltip" title="Lorem Ipsum"></i>
                                    </li>
                                </ul>
                                <div class="row row-sm">
                                    <div class="col-6">
                                        <a href="doctor-profile.html" class="btn view-btn">View Profile</a>
                                    </div>
                                    <div class="col-6">
                                        <a href="booking.html" class="btn book-btn">Book Now</a>
                                    </div>
                                </div>
                            </div>
                        </div>--}}
                        <!-- /Doctor Widget -->
                        
                    </div>
                </div>
           </div>
        </div>
    </section>
    <!-- /Popular Section -->

    <script>
        // window.addEventListener("load", function () {
            if($('.specialities-slider').length > 0) {
                $('.specialities-slider').slick({
                    dots: true,
                    autoplay:false,
                    infinite: true,
                    variableWidth: true,
                    prevArrow: false,
                    nextArrow: false
                });
            }

            if($('.doctor-slider').length > 0) {
                $('.doctor-slider').slick({
                    dots: false,
                    autoplay:false,
                    infinite: false,
                    variableWidth: true,
                });
            }

            if($('.features-slider').length > 0) {
                $('.features-slider').slick({
                    dots: true,
                    infinite: true,
                    centerMode: true,
                    slidesToShow: 3,
                    speed: 500,
                    variableWidth: true,
                    arrows: false,
                    autoplay:false,
                    responsive: [{
                          breakpoint: 992,
                          settings: {
                            slidesToShow: 1
                          }

                    }]
                });
            }
        // });
    </script>

@endsection