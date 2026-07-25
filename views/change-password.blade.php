@extends ("layouts/" . auth()->user()->type)
@section("title", "Change Password")

@section ("content")

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 col-lg-6">
                
                    <!-- Change Password Form -->
                    <form onsubmit="changePassword(event);" id="form-change-password">
                        <div class="form-group">
                            <label>Old Password</label>
                            <input type="password" name="password" autocomplete="off" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label>New Password</label>
                            <input type="password" name="new_password" autocomplete="off" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label>Confirm Password</label>
                            <input type="password" name="confirm_password" autocomplete="off" class="form-control" />
                        </div>
                        <div class="submit-section">
                            <button type="submit" name="submit" class="btn btn-primary submit-btn">Change password</button>
                        </div>
                    </form>
                    <!-- /Change Password Form -->
                    
                </div>
            </div>
        </div>
    </div>

    <script>
        async function changePassword(event) {
            event.preventDefault();
            const form = document.getElementById("form-change-password");
            form.submit.setAttribute("disabled", "disabled");

            try {
                const formData = new FormData(form);

                const response = await axios.post(
                    baseUrl + "/change-password",
                    formData,
                    {
                        headers: {
                            Authorization: "Bearer " + localStorage.getItem(accessTokenKey)
                        }
                    }
                );

                if (response.data.status == "success") {
                    swal.fire("Change password", response.data.message, "success");
                } else {
                    swal.fire("Error", response.data.message, "error");
                }
            } catch (exp) {
                console.log(exp.message);
            } finally {
                form.submit.removeAttribute("disabled");
            }
        }
    </script>

@endsection