<!DOCTYPE html> 
<html lang="en">
	
	
	<head>

	        <meta charset="utf-8" />
	        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
	        <meta name="_token" content="{{ csrf_token() }}" />
		<title>@yield("title", "Home")</title>
		
		<!-- Favicons -->
		<link type="image/x-icon" href="{{ asset('/assets/img/favicon.png') }}" rel="icon" />
		
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="{{ asset('/assets/css/bootstrap.min.css') }}" />
		
		<!-- Fontawesome CSS -->
		<link rel="stylesheet" href="{{ asset('/assets/plugins/fontawesome/css/fontawesome.min.css') }}" />
		<link rel="stylesheet" href="{{ asset('/assets/plugins/fontawesome/css/all.min.css') }}" />

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="{{ asset('/assets/plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css') }}" />
		
		<!-- Fancybox CSS -->
		<link rel="stylesheet" href="{{ asset('/assets/plugins/fancybox/jquery.fancybox.min.css') }}" />

		<!-- Main CSS -->
		<link rel="stylesheet" href="{{ asset('/assets/css/style.css?v=1') }}" />
		<link rel="stylesheet" href="{{ asset('/css/custom.css?v=' . time()) }}" />

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			<script src="{{ asset('/assets/js/html5shiv.min.js') }}"></script>
			<script src="{{ asset('/assets/js/respond.min.js') }}"></script>
		<![endif]-->

		<script src="{{ asset('/js/react.development.js') }}"></script>
	        <script src="{{ asset('/js/react-dom.development.js') }}"></script>
	        <script src="{{ asset('/js/babel.min.js') }}"></script>
	        <script src="{{ asset('/js/axios.min.js') }}"></script>
	        <script src="{{ asset('/js/sweetalert2@11.js') }}"></script>
	        <script src="{{ asset('/js/fontawesome.js') }}"></script>

		<!-- jQuery -->
		<script src="{{ asset('/assets/js/jquery.min.js') }}"></script>
		
		<!-- Bootstrap Core JS -->
		<script src="{{ asset('/assets/js/popper.min.js') }}"></script>
		<script src="{{ asset('/assets/js/bootstrap.min.js') }}"></script>
		
		<!-- Slick JS -->
		<script src="{{ asset('/assets/js/slick.js') }}"></script>
		
		<!-- Bootstrap Tagsinput JS -->
		<script src="{{ asset('/assets/plugins/bootstrap-tagsinput/js/bootstrap-tagsinput.js') }}"></script>

		<!-- Fancybox JS -->
		<script src="{{ asset('/assets/plugins/fancybox/jquery.fancybox.min.js') }}"></script>

		<script src="{{ asset('/assets/js/circle-progress.min.js') }}"></script>

		<!-- Custom JS -->
		<script src="{{ asset('/assets/js/script.js') }}"></script>

		<link rel="stylesheet" href="{{ asset('/datetimepicker/jquery.datetimepicker.min.css') }}" />
		<script src="{{ asset('/datetimepicker/jquery.datetimepicker.full.min.js') }}"></script>

		<link rel="stylesheet" href="{{ asset('/richtext/richtext.min.css') }}" />
		<script src="{{ asset('/richtext/jquery.richtext.js') }}"></script>

        	<script src="{{ asset('/js/script.js?v=' . time()) }}"></script>
	
	</head>
	<body>

		<input type="hidden" id="base-url" value="{{ url('/') }}" />
        	<input type="hidden" id="app-name" value="{{ config('config.app_name') }}" />

        	@php
        		$user = null;
        		$unread_messages = 0;
        	@endphp

        	@if (auth()->check())
        		@php
        			$user = auth()->user();
        		@endphp

        		<input type="hidden" id="user" value="{{ json_encode([
	        		'id' => $user->id ?? 0,
	        		'name' => $user->name ?? '',
	        		'email' => $user->email ?? '',
	        		'type' => $user->type ?? ''
	        	]) }}" />
        	@endif

        	@php
		        $url = request()->url();
		    @endphp

		<script>
	    		const baseUrl = document.getElementById("base-url").value || "";
            	const appName = document.getElementById("app-name").value || "";
            	let user = null;

            	if (document.getElementById("user") != null) {
            		user = JSON.parse(document.getElementById("user").value);
            	}

            	if (user != null) {
					const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
					fetch(baseUrl + "/set-timezone", {
						method: "POST",
						headers: {
							"Content-Type": "application/json",
						},
						body: JSON.stringify({
							"_token": document.querySelector("meta[name='_token']").content,
							"timezone": timezone
						})
					});
	            }
    		</script>

		<!-- Main Wrapper -->
		<div class="main-wrapper">
		
			<!-- Header -->
			<header class="header no-print">
				<nav class="navbar navbar-expand-lg header-nav">
					<div class="navbar-header">
						<a id="mobile_btn" href="javascript:void(0);">
							<span class="bar-icon">
								<span></span>
								<span></span>
								<span></span>
							</span>
						</a>
						<a href="{{ url('/') }}" class="navbar-brand logo">
							<img src="{{ asset('/assets/img/logo.png') }}" class="img-fluid" alt="Logo" />
							<!-- {{ config("config.app_name") }} -->
						</a>
					</div>
					<div class="main-menu-wrapper">
						<div class="menu-header">
							<a href="{{ url('/') }}" class="menu-logo">
								<img src="{{ asset('/assets/img/logo.png') }}" class="img-fluid" alt="Logo" />
								<!-- {{ config("config.app_name") }} -->
							</a>
							<a id="menu_close" class="menu-close" href="javascript:void(0);">
								<i class="fas fa-times"></i>
							</a>
						</div>
						<ul class="main-nav">
							<li class="{{ $url == url('/') ? 'active' : '' }}">
								<a href="{{ url('/') }}">Home</a>
							</li>

							<li class="{{ $url == url('/search') ? 'active' : '' }}">
								<a href="{{ url('/search') }}">Doctors</a>
							</li>

							<li class="{{ $url == url('/chats') ? 'active' : '' }}">
								<a href="{{ url('/chats') }}">
									Chat

									@if ($unread_messages > 0)
										<i class="badge badge-primary">{{ $unread_messages }}</i>
									@endif
								</a>
							</li>

							<li class="login-link">
								<a href="{{ url('/login') }}">Login / Signup</a>
							</li>
						</ul>		 
					</div>		 
					<ul class="nav header-navbar-rht">
						<li class="nav-item contact-item">
							<div class="header-contact-img">
								<i class="far fa-hospital"></i>							
							</div>
							<div class="header-contact-detail">
								<p class="contact-header">Contact</p>
								<p class="contact-info-header"> {{ config("config.phone")}}</p>
							</div>
						</li>

						@if (auth()->check())

							@php
								$my_profile = get_my_profile();
							@endphp

							<li class="nav-item dropdown has-arrow logged-item">
								<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
									<span class="user-img">
										<img class="rounded-circle"
											src="{{ $my_profile->profile_image ?? '' }}"
											onerror="this.remove();"
											width="31"
											alt="{{ $my_profile->name ?? auth()->user()->name }}" />

										{{ $my_profile->name ?? auth()->user()->name }}
									</span>
								</a>
								<div class="dropdown-menu dropdown-menu-right">
									<div class="user-header">
										<div class="avatar avatar-sm">
											<img src="{{ $my_profile->profile_image ?? '' }}"
												onerror="this.remove();"
												alt="{{ $my_profile->name ?? auth()->user()->name }}" class="avatar-img rounded-circle" />
										</div>
										<div class="user-text">
											<h6>{{ $my_profile->name ?? auth()->user()->name }}</h6>
											<p class="text-muted mb-0 text-justify">{{ auth()->user()->type }}</p>
										</div>
									</div>
									<a class="dropdown-item" href="{{ url('/dashboard') }}">Dashboard</a>
									<a class="dropdown-item" href="{{ url('/profile-settings') }}">Profile Settings</a>
									<a class="dropdown-item" href="{{ url('/checkout') }}"
										style="display: none;"
										id="header-checkout-link">Checkout</a>
									<a class="dropdown-item" href="{{ url('/logout') }}">Logout</a>
								</div>
							</li>
                        @else
							<li class="nav-item">		
								<a class="nav-link header-login" href="{{ url('/login') }}">login / Signup </a>
							</li>
						@endif
					</ul>
				</nav>
			</header>
			<!-- /Header -->

            @yield ("main")

            <!-- Footer -->
			<footer class="footer no-print">
				
				<!-- Footer Top -->
				<div class="footer-top">
					<div class="container-fluid">
						<div class="row">
							<div class="col-lg-3 col-md-6">
							
								<!-- Footer Widget -->
								<div class="footer-widget footer-about">
									<div class="footer-logo">
										<img src="{{ asset('/assets/img/footer-logo.png') }}" alt="logo" height="75" />
										<!-- <span style="color: white;
											font-size: 26px;
											font-weight: bold;">{{ config("config.app_name") }}</span> -->
									</div>
									<div class="footer-about-content">
										<p>{{ config("config.app_name") }} makes healthcare accessible by connecting patients with doctors and clinics across various specializations. Find the right care, book appointments easily, and manage your health with confidence — all from one platform.</p>
										
										<div class="social-icon">
											<ul>
												<li>
													<a href="https://web.facebook.com/" target="_blank"><i class="fab fa-facebook-f"></i> </a>
												</li>

												<li>
													<a href="https://youtube.com/" target="_blank"><i class="fab fa-youtube"></i> </a>
												</li>
											</ul>
										</div>
									</div>
								</div>
								<!-- /Footer Widget -->
								
							</div>
							
							<div class="col-lg-3 col-md-6">
							
								<!-- Footer Widget -->
								<div class="footer-widget footer-menu">
									<h2 class="footer-title">For Patients</h2>
									<ul>
										<li><a href="{{ url('/search') }}"><i class="fas fa-angle-double-right"></i> Search for Doctors</a></li>
										<li><a href="{{ url('/profile-settings') }}"><i class="fas fa-angle-double-right"></i> Profile Settings</a></li>
										<li><a href="{{ url('/chats') }}"><i class="fas fa-angle-double-right"></i> Chat</a></li>

										@if (!auth()->check())
											<li><a href="{{ url('/login') }}"><i class="fas fa-angle-double-right"></i> Login</a></li>
											<li><a href="{{ url('/register') }}"><i class="fas fa-angle-double-right"></i> Register</a></li>
										@endif
									</ul>
								</div>
								<!-- /Footer Widget -->
								
							</div>
							
							<div class="col-lg-3 col-md-6">
							
								<!-- Footer Widget -->
								<div class="footer-widget footer-menu">
									<h2 class="footer-title">For Doctors</h2>
									<ul>
										<li><a href="{{ url('/my-patients') }}"><i class="fas fa-angle-double-right"></i> My Patients</a></li>
										<li><a href="{{ url('/appointments') }}"><i class="fas fa-angle-double-right"></i> Appointments</a></li>
										<li><a href="{{ url('/chats') }}"><i class="fas fa-angle-double-right"></i> Chat</a></li>
										
										@if (!auth()->check())
											<li><a href="{{ url('/login') }}"><i class="fas fa-angle-double-right"></i> Login</a></li>
											<li><a href="{{ url('/register') }}"><i class="fas fa-angle-double-right"></i> Register</a></li>
										@endif

										<li><a href="{{ url('/dashboard') }}"><i class="fas fa-angle-double-right"></i> Doctor Dashboard</a></li>
									</ul>
								</div>
								<!-- /Footer Widget -->
								
							</div>
							
							<div class="col-lg-3 col-md-6">
							
								<!-- Footer Widget -->
								<div class="footer-widget footer-contact">
									<h2 class="footer-title">Contact Us</h2>
									<div class="footer-contact-info">
										<p>
											<i class="fas fa-map-marker-alt"></i>&nbsp;
											{{ config("config.address") }}
										</p>
										<p>
											<i class="fas fa-phone-alt"></i>&nbsp;
											{{ config("config.phone") }}
										</p>
										<p class="mb-0">
											<i class="fas fa-envelope"></i>&nbsp;
											{{ config("config.email") }}
										</p>
									</div>
								</div>
								<!-- /Footer Widget -->
								
							</div>
							
						</div>
					</div>
				</div>
				<!-- /Footer Top -->
				
				<!-- Footer Bottom -->
                <div class="footer-bottom">
					<div class="container-fluid">
					
						<!-- Copyright -->
						<div class="copyright">
							<div class="row">
								<div class="col-md-12 col-lg-12">
									<div class="copyright-text">
										<p class="mb-0 text-center"><a href="https://www.github.com/"
											target="_blank">Copyright Medisync © 2026 — All rights reserved</a></p>
									</div>
								</div>
							</div>
						</div>
						<!-- /Copyright -->
						
					</div>
				</div>
				<!-- /Footer Bottom -->
				
			</footer>
			<!-- /Footer -->
		   
	   </div>
	   <!-- /Main Wrapper -->

	   <!-- Appointment Details Modal -->
	    <div class="modal fade custom-modal" id="appt_details">
	        <div class="modal-dialog modal-dialog-centered">
	            <div class="modal-content">
	                <div class="modal-header">
	                    <h5 class="modal-title">Appointment Details</h5>
	                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                        <span aria-hidden="true">&times;</span>
	                    </button>
	                </div>
	                <div class="modal-body">
	                    <ul class="info-details">
	                        <li>
	                            <div class="details-header">
	                                <div class="row">
	                                    <div class="col-md-6">
	                                        <span class="title appointment-number"></span>
	                                    </div>
	                                    <div class="col-md-6">
	                                        <div class="text-right">
	                                            <span id="topup_status"></span>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                        </li>
	                        <li>
	                            <span class="title">Status:</span>
	                            <span class="text status"></span>
	                        </li>
	                        <li>
	                            <span class="title">Confirm Date:</span>
	                            <span class="text datetime"></span>
	                        </li>
	                        <li>
	                            <span class="title">Paid Amount</span>
	                            <span class="text amount">৳</span>
	                        </li>
	                    </ul>
	                </div>
	            </div>
	        </div>
	    </div>
	    <!-- /Appointment Details Modal -->

	   	<!-- Edit Time Slot Modal -->
		<div class="modal custom-modal" id="edit_time_slot">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Edit Time Slots</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<div class="modal-body">
						<div class="hours-info">

							<div id="time-slots"></div>

							<div class="add-more mb-3">
								<a href="javascript:void(0);" class="add-hours" onclick="timing.addMoreHour();">
									<i class="fa fa-plus-circle"></i>&nbsp;
									Add More
								</a>
							</div>

							<div class="submit-section text-center">
								<button type="button" class="btn btn-primary submit-btn"
									onclick="timing.save(event);">Save Changes</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /Edit Time Slot Modal -->

		<!-- Modal -->
	    <div class="modal custom-modal" id="add-medical-record-modal">
	      <div class="modal-dialog modal-dialog-centered">
	        <div class="modal-content modal-lg">
	          <div class="modal-header">
	            <h1 class="modal-title fs-5">Medical Records</h1>
	            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
	          </div>

	          <div class="modal-body">
	            <form enctype="multipart/form-data" onsubmit="addMedicalRecord(event);"
	            	id="form-add-medical-record">
	                <div class="form-group">
	                    <label>Date</label>
	                    <input type="text" name="date" class="form-control datepicker" value="{{ date('Y-m-d') }}" />
	                </div>
	                <div class="form-group">
	                    <label>Description ( Optional )</label>
	                    <textarea class="form-control" name="description"></textarea>
	                </div>
	                <div class="form-group">
	                    <label>Upload File</label> 
	                    <input type="file" class="form-control" name="file" accept="image/*, .pdf, .doc, .docx" required />
	                </div>  
	                <div class="submit-section text-center">
	                    <button type="submit" name="submit" class="btn btn-primary submit-btn">Submit</button>
	                    <button type="button" class="btn btn-secondary submit-btn" data-dismiss="modal">Cancel</button>                         
	                </div>
	            </form>
	          </div>
	        </div>
	      </div>
	    </div>

	    <script>
	    	window.addEventListener("load", function () {
	            $("textarea[name='about']").richText();

	            $("input[name='dob']").datetimepicker({
	                timepicker: false,
	                format: "Y-m-d",
	                scrollInput: false
	            });

	            $("input[name='date']").datetimepicker({
	                timepicker: false,
	                format: "Y-m-d",
	                scrollInput: false
	            });
	        });
	    </script>
		
	</body>


</html>