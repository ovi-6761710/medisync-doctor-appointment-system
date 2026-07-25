<div class="card">
    <div class="card-body">
        <div class="doctor-widget">
            <div class="doc-info-left">
                <div class="doctor-img">
                    <a href="{{ url('/doctors/' . $doctor->id . '/profile') }}">
                        <img src="{{ $doctor->profile_image }}"
                            onerror="this.src = baseUrl + '/img/user-placeholder.png'"
                            class="img-fluid" alt="{{ $doctor->name }}" />
                    </a>
                </div>
                <div class="doc-info-cont">
                    <h4 class="doc-name"><a href="{{ url('/doctors/' . $doctor->id . '/profile') }}">Dr. {{ $doctor->name }}</a></h4>
                    <p class="doc-speciality">{!! $doctor->about ?? "" !!}</p>

                    @foreach (explode(",", $doctor->specializations) as $specialization)
                        <h5 class="doc-department">
                            <!-- <img src="assets/img/specialities/specialities-05.png" class="img-fluid" alt="{{ $specialization }}" /> -->
                            {{ $specialization }} | 
                        </h5>
                    @endforeach

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

                    <div class="clinic-details">
                        <p class="doc-location"><i class="fas fa-map-marker-alt"></i> {{ $doctor->city . ", " . $doctor->country }}</p>
                        <ul class="clinic-gallery">
                            {{--@foreach ($doctor->clinic_images as $image)
                                <li>
                                    <a href="{{ $image }}" data-fancybox="gallery">
                                        <img src="{{ $image }}" style="width: 100px;
                                            height: 100px;
                                            object-fit: cover;
                                            border-radius: 5px;" />
                                    </a>
                                </li>
                            @endforeach--}}
                        </ul>
                    </div>
                    <div class="clinic-services">
                        @foreach (explode(",", $doctor->services) as $service)
                            <span>{{ $service }}</span>
                        @endforeach
                    </div>

                    @if (isset($social_media) && $social_media != null)
                        <div class="row mt-3 social-media">
                            <div class="col-md-12"> 
                                @if (!empty($social_media->facebook))
                                    <a href="{{ $social_media->facebook }}"
                                        target="_blank"
                                        style="color: #1877F2;">
                                        <i class="fa-brands fa-facebook"></i>
                                    </a>
                                @endif

                                @if (!empty($social_media->twitter))
                                    <a href="{{ $social_media->twitter }}"
                                        target="_blank"
                                        style="color: #1DA1F2;">
                                        <i class="fa-brands fa-twitter"></i>
                                    </a>
                                @endif

                                @if (!empty($social_media->instagram))
                                    <a href="{{ $social_media->instagram }}"
                                        target="_blank"
                                        style="color: #E1306C;">
                                        <i class="fa-brands fa-instagram"></i>
                                    </a>
                                @endif

                                @if (!empty($social_media->linkedin))
                                    <a href="{{ $social_media->linkedin }}"
                                        target="_blank"
                                        style="color: #0A66C2;">
                                        <i class="fa-brands fa-linkedin"></i>
                                    </a>
                                @endif

                                @if (!empty($social_media->youtube))
                                    <a href="{{ $social_media->youtube }}"
                                        target="_blank"
                                        style="color: #FF0000;">
                                        <i class="fa-brands fa-youtube"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="doc-info-right">
                <div class="clini-infos">
                    <ul>
                        <li><i class="fas fa-map-marker-alt"></i> {{ $doctor->state . ", " . $doctor->country }}</li>

                        @if (!empty($doctor->email))
                            <li><i class="fas fa-envelope"></i> {{ $doctor->email }}</li>
                        @endif

                        @if (!empty($doctor->phone))
                            <li><i class="fas fa-phone"></i> {{ $doctor->phone }}</li>
                        @endif

                        <li><i class="far fa-money-bill-alt"></i> ৳{{ $doctor->fee }}</li>
                    </ul>
                </div>

                <div class="doctor-action">
                    <button type="button" class="btn btn-white fav-btn {{ $doctor->is_favourite ? 'fav-btn-highlight' : '' }}"
                        onclick="toggleFavourite(event, '{{ $doctor->user_id }}');">
                        <i class="far fa-bookmark"></i>
                    </button>&nbsp;&nbsp;

                    <a href="{{ url('/chats/' . $doctor->user_id) }}" class="btn btn-white msg-btn">
                        <i class="far fa-comment-alt"></i>
                    </a>
                </div>

                <div class="clinic-booking">
                    <a class="view-pro-btn" href="{{ url('/doctors/' . $doctor->id . '/profile') }}">View Profile</a>
                    <a class="apt-btn" href="{{ url('/doctors/' . $doctor->id . '/booking') }}">Book Appointment</a>
                </div>
            </div>
        </div>
    </div>
</div>