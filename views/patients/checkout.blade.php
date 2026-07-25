@extends ("layouts/app")
@section("title", "Checkout")

@section ("main")

    <!-- Breadcrumb -->
    <div class="breadcrumb-bar">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-12 col-12">
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                        </ol>
                    </nav>
                    <h2 class="breadcrumb-title">Checkout</h2>
                </div>
            </div>
        </div>
    </div>
    <!-- /Breadcrumb -->
    
    <!-- Page Content -->
    <div class="content">
        <div class="container">
            <div id="app"></div>
        </div>
    </div>		
    <!-- /Page Content -->

    <script type="text/babel">

        function App() {

            const [loading, setLoading] = React.useState(false);
            const [data, setData] = React.useState(null);
            const [user, setUser] = React.useState(null);
            const [submitting, setSubmitting] = React.useState(false);
            const [selectedTiming, setSelectedTiming] = React.useState(null);

            async function onInit() {
                setLoading(true);
                let temp = JSON.parse(localStorage.getItem("selectedTiming") || "{}") || {};
                setSelectedTiming(temp);

                try {
                    const formData = new FormData();
                    formData.append("id", temp?.id || 0);

                    const response = await axios.post(
                        baseUrl + "/fetch-booking-details",
                        formData
                    )

                    if (response.data.status == "success") {
                        setData(response.data.data);
                        setUser(response.data.user);
                    } else {
                        swal.fire("Error", response.data.message, "error");
                    }
                } catch (exp) {
                    console.log(exp.message);
                } finally {
                    setLoading(false);
                }
            }

            async function doCheckout() {
                if (selectedTiming == null) {
                    return;
                }

                if (!document.getElementById("terms_accept").checked) {
                    swal.fire("Terms and Conditions", "Please accept terms and conditions first.", "info");
                    return;
                }
                
                setSubmitting(true);
                try {
                    const form = document.getElementById("form-checkout");
                    const formData = new FormData(form);
                    formData.append("id", selectedTiming?.id || 0);
                    formData.append("date", selectedTiming?.date || "");

                    const response = await axios.post(
                        baseUrl + "/checkout",
                        formData
                    )

                    if (response.data.status == "success") {
                        const id = response.data.id;
                        localStorage.removeItem("selectedTiming");
                        window.location.href = baseUrl + "/booking-success/" + id;
                    } else {
                        swal.fire("Error", response.data.message, "error");
                    }
                } catch (exp) {
                    console.log(exp.message);
                } finally {
                    setSubmitting(false);
                }
            }
            
            React.useEffect(function () {
                onInit();
            }, []);

            return (
                <>
                    <div className="row">
                        <div className="col-md-7 col-lg-8">
                            <div className="card">
                                <div className="card-body">
                                
                                    <form onSubmit={ function (event) {
                                        event.preventDefault();
                                        doCheckout();
                                    } } id="form-checkout">
                                        <div className="info-widget">
                                            <h4 className="card-title">Personal Information</h4>
                                            <div className="row">
                                                <div className="col-md-6 col-sm-12">
                                                    <div className="form-group card-label">
                                                        <label>First Name</label>
                                                        <input className="form-control" name="first_name" type="text"
                                                            defaultValue={ user?.name } />
                                                    </div>
                                                </div>
                                                <div className="col-md-6 col-sm-12">
                                                    <div className="form-group card-label">
                                                        <label>Last Name</label>
                                                        <input className="form-control" name="last_name" type="text"
                                                            defaultValue={ user?.name } />
                                                    </div>
                                                </div>
                                                <div className="col-md-6 col-sm-12">
                                                    <div className="form-group card-label">
                                                        <label>Email</label>
                                                        <input className="form-control" name="email" type="email"
                                                            defaultValue={ user?.email } />
                                                    </div>
                                                </div>
                                                <div className="col-md-6 col-sm-12">
                                                    <div className="form-group card-label">
                                                        <label>Phone</label>
                                                        <input className="form-control" name="phone" type="text"
                                                            defaultValue={ user?.phone } />
                                                    </div>
                                                </div>
                                            </div>

                                            { (!loading && user == null) && (
                                                <div className="exist-customer">
                                                    Existing Customer?
                                                    <a href={ `${ baseUrl }/login` }>Click here to login</a>
                                                </div>
                                            ) }
                                        </div>
                                        
                                        <div className="payment-widget">
                                            <h4 className="card-title">Payment Method</h4>
											
											<div className="payment-list mb-3">
												<label className="payment-radio credit-card-option">
													<input type="radio" defaultChecked />
													<span className="checkmark"></span>
													Virtual balance (৳{ user?.balance })
												</label>

                                                {/*<a href={ baseUrl + "/balance" }>Add balance</a>*/}
											</div>

                                            <div className="terms-accept">
                                                <div className="custom-checkbox">
                                                    <label>
                                                        <input type="checkbox" id="terms_accept" />&nbsp;
                                                        I have read and accept <a href={ `${ baseUrl }/terms-conditions` }>Terms &amp; Conditions</a>
                                                    </label>
                                                </div>
                                            </div>
                                            
                                            <div className="submit-section mt-4">
                                                <button type="submit" className="btn btn-primary submit-btn"
                                                    disabled={ submitting }>Confirm and Pay</button>
                                            </div>
                                        </div>
                                    </form>
                                    
                                </div>
                            </div>
                            
                        </div>
                        
                        <div className="col-md-5 col-lg-4 theiaStickySidebar">
                        
                            <div className="card booking-card">
                                <div className="card-header">
                                    <h4 className="card-title">Booking Summary</h4>
                                </div>
                                
                                <div className="card-body">
                                    <div className="booking-doc-info">
                                        <a href={ `${ baseUrl }/doctors/${ data?.user_id }/profile` }
                                            className="booking-doc-img">
                                            <img src={ data?.profile_image } onError={ function (event) {
                                                event.target.remove();
                                            } } alt="User Image" />
                                        </a>

                                        <div className="booking-info">
                                            <h4>
                                                <a href={ `${ baseUrl }/doctors/${ data?.user_id }/profile` }>Dr. { data?.name }</a>
                                            </h4>
                                            
                                            {/*<div className="rating">
                                                <i className="fas fa-star filled"></i>
                                                <i className="fas fa-star filled"></i>
                                                <i className="fas fa-star filled"></i>
                                                <i className="fas fa-star filled"></i>
                                                <i className="fas fa-star"></i>
                                                <span className="d-inline-block average-rating">35</span>
                                            </div>*/}

                                            <div className="clinic-details">
                                                <p className="doc-location">
                                                    <i className="fas fa-map-marker-alt"></i>&nbsp;
                                                    { data?.state }, { data?.country }
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div className="booking-summary mt-3">
                                        <div className="booking-item-wrap">
                                            <ul className="booking-date">
                                                <li>Day <span>{ data?.day }</span></li>
                                                <li>Date <span>{ selectedTiming?.date }</span></li>
                                                <li>Time (24-hour) <span>{ data?.from }</span></li>
                                            </ul>
                                            <ul className="booking-fee">
                                                <li>Fee <span>৳{ data?.fee }</span></li>
                                            </ul>
                                            <div className="booking-total">
                                                <ul className="booking-total-list">
                                                    <li>
                                                        <span>Total</span>
                                                        <span className="total-cost">৳{ data?.fee }</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </>
            );
        }

        ReactDOM.createRoot(
            document.getElementById("app")
        ).render(<App />);
    </script>

@endsection