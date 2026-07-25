@extends ("layouts/app")
@section("title", "Register")

@section ("main")

    <!-- Page Content -->
    <div class="content">
        <div class="container-fluid">
            
            <div class="row">
                <div class="col-md-8 offset-md-2">
                        
                    <!-- Register Content -->
                    <div class="account-content">
                        <div class="row align-items-center justify-content-center">
                            <div class="col-md-7 col-lg-6 login-left">
                                <img src="{{ asset('assets/img/login-banner.png') }}" class="img-fluid" alt="Doccure Register" />	
                            </div>
                            <div class="col-md-12 col-lg-6 login-right">
                                <div class="login-header">
                                    <h3>Register <a href="{{ url('/login') }}">Already a user ?</a></h3>
                                </div>
                                
                                <!-- Register Form -->
                                <form onsubmit="doRegister();">
                                    <div class="form-group form-focus">
                                        <input type="text" name="name" class="form-control floating" />
                                        <label class="focus-label">Name</label>
                                    </div>
                                    <div class="form-group form-focus">
                                        <input type="email" name="email" class="form-control floating" />
                                        <label class="focus-label">Email</label>
                                    </div>
                                    <div class="form-group form-focus">
                                        <input type="password" name="password" class="form-control floating" />
                                        <label class="focus-label">Create Password</label>
                                    </div>

                                    <div class="form-group">
                                        <label class="focus-label">
                                            Doctor
                                            <input type="radio" name="type" value="doctor" />
                                        </label>&nbsp;

                                        <label class="focus-label">
                                            Patient
                                            <input type="radio" name="type" value="patient" />
                                        </label>
                                    </div>

                                    <div class="text-right">
                                        <a class="forgot-link" href="{{ url('/login') }}">Already have an account?</a>
                                    </div>

                                    <button class="btn btn-primary btn-block btn-lg login-btn" type="submit" name="submit">Signup</button>
                                    
                                    <!--<div class="login-or">
                                        <span class="or-line"></span>
                                        <span class="span-or">or</span>
                                    </div>
                                    <div class="row form-row social-login">
                                        <div class="col-6">
                                            <a href="#" class="btn btn-facebook btn-block"><i class="fab fa-facebook-f mr-1"></i> Login</a>
                                        </div>
                                        <div class="col-6">
                                            <a href="#" class="btn btn-google btn-block"><i class="fab fa-google mr-1"></i> Login</a>
                                        </div>
                                    </div>-->
                                </form>
                                <!-- /Register Form -->
                                
                            </div>
                        </div>
                    </div>
                    <!-- /Register Content -->
                        
                </div>
            </div>

        </div>

    </div>		
    <!-- /Page Content -->

    <script>
        async function doRegister() {
            event.preventDefault()
            const form = event.target

            try {
                const formData = new FormData(form)
                form.submit.setAttribute("disabled", "disabled")

                const response = await axios.post(
                    baseUrl + "/register",
                    formData
                )

                if (response.data.status == "success") {
                    const verification = response.data.verification
                    swal.fire("Register", response.data.message, "success")
                        .then(function () {
                            if (verification) {
                                window.location.href = baseUrl + "/email-verification/" + form.email.value
                            } else {
                                window.location.href = baseUrl + "/login"
                            }
                        })
                } else {
                    swal.fire("Error", response.data.message, "error")
                }
            } catch (exp) {
                swal.fire("Error", exp.message, "error")
            } finally {
                form.submit.removeAttribute("disabled")
            }
        }
    </script>

@endsection