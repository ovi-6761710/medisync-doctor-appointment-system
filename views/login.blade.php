@extends ("layouts/app")
@section("title", "Login")

@section ("main")

    <!-- Page Content -->
    <div class="content">
        <div class="container-fluid">
            
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    
                    <!-- Login Tab Content -->
                    <div class="account-content">
                        <div class="row align-items-center justify-content-center">
                            <div class="col-md-7 col-lg-6 login-left">
                                <img src="{{ asset('assets/img/login-banner.png') }}" class="img-fluid" alt="Doccure Login" />	
                            </div>
                            <div class="col-md-12 col-lg-6 login-right">
                                <div class="login-header">
                                    <h3>Login <span>Doccure</span></h3>
                                </div>
                                <form onsubmit="doLogin()">
                                    <div class="form-group form-focus">
                                        <input type="email" name="email" class="form-control floating" />
                                        <label class="focus-label">Email</label>
                                    </div>
                                    <div class="form-group form-focus">
                                        <input type="password" name="password" class="form-control floating" />
                                        <label class="focus-label">Password</label>
                                    </div>
                                    <div class="text-right">
                                        <a class="forgot-link" href="{{ url('/forgot-password') }}">Forgot Password ?</a>
                                    </div>
                                    <button class="btn btn-primary btn-block btn-lg login-btn" type="submit" name="submit">Login</button>
                                    
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
                                    
                                    <div class="text-center dont-have">Don't have an account? <a href="{{ url('/register') }}">Register</a></div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /Login Tab Content -->
                        
                </div>
            </div>

        </div>

    </div>		
    <!-- /Page Content -->

    <script>
        async function doLogin() {
            event.preventDefault()
            const form = event.target

            try {
                const formData = new FormData(form)
                form.submit.setAttribute("disabled", "disabled")

                const response = await axios.post(
                    baseUrl + "/login",
                    formData
                )

                if (response.data.status == "success") {
                    const accessToken = response.data.access_token
                    localStorage.setItem(accessTokenKey, accessToken)

                    const urlSearchParams = new URLSearchParams(window.location.search)
                    const redirect = urlSearchParams.get("redirect") || ""
                    if (redirect == "") {
                        window.location.href = baseUrl
                    } else {
                        window.location.href = redirect
                    }
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