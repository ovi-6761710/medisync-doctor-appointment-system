@extends ("layouts/patient")
@section ("title", "Profile Settings")

@section ("content")

    <div class="card">
        <div class="card-body">
            
            <!-- Profile Settings Form -->
            <form onsubmit="updateProfile();" enctype="multipart/form-data">
                <div class="row form-row">
                    <div class="col-12 col-md-12">
                        <div class="form-group">
                            <div class="change-avatar">
                                <div class="profile-img">
                                    <img src="{{ $profile->profile_image ?? '' }}"
                                        alt="{{ $profile->name ?? '' }}"
                                        id="profile-image"
                                        onerror="event.target.src = baseUrl + '/img/user-placeholder.png'" />
                                </div>
                                <div class="upload-img">
                                    <div class="change-photo-btn">
                                        <span><i class="fa fa-upload"></i> Upload Photo</span>
                                        <input type="file" name="profile_image" accept="image/*" class="upload"
                                            onchange="onProfileImageSelected(event);" />
                                    </div>
                                    <small class="form-text text-muted">Allowed JPG or PNG.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label>Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ $profile->name ?? '' }}" required />
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $profile->email ?? '' }}" />
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{ $profile->phone ?? '' }}" />
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label>Date of Birth</label>
                            <div class="cal-icon">
                                <input type="text" name="dob"
                                    class="form-control datetimepicker"
                                    value="{{ $profile->dob ?? '' }}"
                                    autocomplete="off" />
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label>Gender</label>
                            <select class="form-control select" name="gender">
                                <option value="">Select gender</option>
                                <option value="male" {{ ($profile->gender ?? '') == "male" ? "selected" : "" }}>Male</option>
                                <option value="female" {{ ($profile->gender ?? '') == "female" ? "selected" : "" }}>Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label>Blood Group</label>
                            <select class="form-control select" name="blood_group">
                                <option value="">Select blood group</option>
                                <option value="A-" {{ ($profile->blood_group ?? '') == "A-" ? "selected" : "" }}>A-</option>
                                <option value="A+" {{ ($profile->blood_group ?? '') == "A+" ? "selected" : "" }}>A+</option>
                                <option value="B-" {{ ($profile->blood_group ?? '') == "B-" ? "selected" : "" }}>B-</option>
                                <option value="B+" {{ ($profile->blood_group ?? '') == "B+" ? "selected" : "" }}>B+</option>
                                <option value="AB-" {{ ($profile->blood_group ?? '') == "AB-" ? "selected" : "" }}>AB-</option>
                                <option value="AB+" {{ ($profile->blood_group ?? '') == "AB+" ? "selected" : "" }}>AB+</option>
                                <option value="O-" {{ ($profile->blood_group ?? '') == "O-" ? "selected" : "" }}>O-</option>
                                <option value="O+" {{ ($profile->blood_group ?? '') == "O+" ? "selected" : "" }}>O+</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                        <label>Address</label>
                            <input type="text" name="address" class="form-control" value="{{ $profile->address ?? '' }}" />
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label>City</label>
                            <input type="text" class="form-control" name="city" value="{{ $profile->city ?? '' }}" />
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label>State</label>
                            <input type="text" class="form-control" name="state" value="{{ $profile->state ?? '' }}" />
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label>Country</label>
                            <input type="text" class="form-control" name="country" value="{{ $profile->country ?? '' }}" />
                        </div>
                    </div>
                </div>
                <div class="submit-section">
                    <button type="submit" name="submit" class="btn btn-primary submit-btn">Save Changes</button>
                </div>
            </form>
            <!-- /Profile Settings Form -->
            
        </div>
    </div>

    <script>
        window.addEventListener("load", function () {
            $("input[name='dob']").datetimepicker({
                timepicker: false,
                format: "Y-m-d",
                scrollInput: false
            });
        });

        async function updateProfile() {
            event.preventDefault();
            const form = event.target;

            try {
                form.submit.setAttribute("disabled", "disabled");
                const formData = new FormData(form);

                const response = await axios.post(
                    baseUrl + "/update-profile",
                    formData,
                    {
                        headers: {
                            Authorization: "Bearer " + localStorage.getItem(accessTokenKey)
                        }
                    }
                )

                if (response.data.status == "success") {
                    swal.fire("Update profile", response.data.message, "success");

                    var file = document.querySelector("input[name='profile_image']").files
                    if (file.length > 0) {
                        var fileReader = new FileReader()
            
                        fileReader.onload = function (event) {
                            document.getElementById("profile-image").setAttribute("src", event.target.result)
                        }
            
                        fileReader.readAsDataURL(file[0])
                    }
                } else {
                    swal.fire("Error", response.data.message, "error");
                }
            } catch (exp) {
                swal.fire("Error", exp.message, "error")
            } finally {
                form.submit.removeAttribute("disabled");
            }
        }
    </script>

@endsection