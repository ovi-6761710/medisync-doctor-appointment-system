@extends ("layouts/" . auth()->user()->type)
@section("title", "Favourites")

@section ("content")

    <div class="row row-grid">
        @foreach ($favourites as $favourite)
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="profile-widget">
                    <div class="doc-img">
                        <a href="{{ url('/doctors/' . $favourite->doctor?->id . '/profile') }}">
                            <img class="img-fluid" alt="{{ $favourite->doctor?->name }}"
                                src="{{ $favourite->doctor?->profile_image }}"
                                onerror="this.src = baseUrl + '/img/user-placeholder.png'" />
                        </a>
                        <a href="javascript:void(0)" class="fav-btn fav-btn-highlight"
                            onclick="toggleFavourite(event, '{{ $favourite->user_id }}');">
                            <i class="far fa-bookmark"></i>
                        </a>
                    </div>
                    <div class="pro-content">
                        <h3 class="title">
                            <a href="{{ url('/doctors/' . $favourite->doctor?->id . '/profile') }}">{{ $favourite->doctor?->name }}</a>
                        </h3>
                        <p class="speciality">{{ $favourite->doctor?->specializations }}</p>
                        <div class="rating">
                            @for ($a = 1; $a <= 5; $a++)
                                @if ($a <= $favourite->doctor?->ratings)
                                    <i class="fas fa-star filled"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                            <span class="d-inline-block average-rating">({{ $favourite->doctor?->reviews }})</span>
                        </div>
                        <ul class="available-info">
                            <li>
                                <i class="fas fa-map-marker-alt"></i> {{ $favourite->doctor?->city . ", " . $favourite->doctor?->country }}
                            </li>
                        </ul>
                        <div class="row row-sm">
                            <div class="col-6">
                                <a href="{{ url('/doctors/' . $favourite->doctor?->id . '/profile') }}" class="btn view-btn">View Profile</a>
                            </div>
                            <div class="col-6">
                                <a href="{{ url('/doctors/' . $favourite->doctor?->id . '/booking') }}" class="btn book-btn">Book Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

@endsection