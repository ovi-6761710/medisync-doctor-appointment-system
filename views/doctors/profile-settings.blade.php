@extends ("layouts/doctor")
@section("title", "Profile settings")

@section ("content")

    <form onsubmit="updateProfile();" enctype="multipart/form-data">
        <!-- Basic Information -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Basic Information</h4>
                <div class="row form-row">
                    <div class="col-md-12">
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
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ $profile->name ?? '' }}" class="form-control" required />
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $profile->email ?? '' }}" />
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="number" name="phone" value="{{ $profile->phone ?? '' }}" class="form-control" />
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Gender</label>
                            <select class="form-control select" name="gender">
                                <option value="">Select</option>
                                <option value="male" {{ ($profile->gender ?? '') == "male" ? "selected" : "" }}>Male</option>
                                <option value="female" {{ ($profile->gender ?? '') == "female" ? "selected" : "" }}>Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-0">
                            <label>Date of Birth</label>
                            <input type="text" name="dob"
                                value="{{ $profile->dob ?? '' }}"
                                class="form-control"
                                autocomplete="off" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Basic Information -->
        
        <!-- About Me -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">About Me</h4>
                <div class="form-group mb-0">
                    <label>Biography</label>
                    <textarea class="form-control" rows="5" name="about">{{ $profile->about ?? '' }}</textarea>
                </div>
            </div>
        </div>
        <!-- /About Me -->
        
        <!-- Clinic Info -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Clinic Info</h4>
                <div class="row form-row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Clinic Name</label>
                            <input type="text" class="form-control" name="clinic_name" value="{{ $profile->clinic_name ?? '' }}" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Clinic Address</label>
                            <input type="text" class="form-control" name="clinic_address" value="{{ $profile->clinic_address ?? '' }}" />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Clinic Images</label>
                            <input type="file" multiple name="clinic_images[]" accept="image/*, video/*" />
                        </div>
                        <div class="upload-wrap">
                            @if (isset($profile->clinic_images))
                                @foreach ($profile->clinic_images as $image)
                                    <div class="upload-images" style="margin-right: 10px;
                                        position: relative;">
                                        <img src="{{ $image }}" alt="Upload Image" style="width: 100px;
                                            height: 100px;
                                            object-fit: cover;
                                            border-radius: 5px;" />
                                        
                                        <a href="javascript:void(0);" class="btn btn-icon btn-danger btn-sm"
                                            onclick="removeClinicImage(this, '{{ $image }}');"
                                            style="position: absolute;
                                                right: 0px;">
                                            <i class="far fa-trash-alt"></i>
                                        </a>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Clinic Info -->

        <!-- Contact Details -->
        <div class="card contact-card">
            <div class="card-body">
                <h4 class="card-title">Contact Details</h4>
                <div class="row form-row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" name="address" class="form-control" value="{{ $profile->address ?? '' }}" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">City</label>
                            <input type="text" name="city" class="form-control" value="{{ $profile->city ?? '' }}" />
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">State / Province</label>
                            <input type="text" name="state" class="form-control" value="{{ $profile->state ?? '' }}" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Country</label>
                            <input type="text" name="country" class="form-control" value="{{ $profile->country ?? '' }}" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Contact Details -->
        
        <!-- Pricing -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Fee (BDT)</h4>
                
                <div class="card-body" style="padding: 0px !important;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group" style="margin-bottom: 0px !important;">
                                <input type="number" min="0" step="1" name="fee" class="form-control" value="{{ $profile->fee ?? 0 }}" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Pricing -->
        
        <!-- Services and Specialization -->
        <div class="card services-card">
            <div class="card-body">
                <h4 class="card-title">Services and Specialization</h4>
                <div class="form-group">
                    <label>Services</label>

                    <div class="dropdown-list">
                        <input type="text" class="dropdown-input form-control" placeholder="Enter Services"
                            name="services"
                            value="{{ $profile->services ?? '' }}"
                            id="services"
                            autocomplete="off" />

                        <div class="dropdown-items">
                            @foreach ($services as $service)
                                <div>{{ $service->name ?? "" }}</div>
                            @endforeach
                        </div>
                    </div>

                    {{--<input type="text" data-role="tagsinput" class="input-tags form-control" placeholder="Enter Services"
                        name="services"
                        value="{{ $profile->services ?? '' }}"
                        id="services" />
                    <small class="form-text text-muted">Note : Type & Press enter to add new services</small>--}}
                </div> 
                <div class="form-group mb-0">
                    <label>Specializations</label>

                    <div class="dropdown-list">
                        <input type="text" class="dropdown-input form-control" placeholder="Enter Specializations"
                            name="specializations"
                            value="{{ $profile->specializations ?? '' }}"
                            id="specialist"
                            autocomplete="off" />

                        <div class="dropdown-items">
                            @foreach ($specialities as $speciality)
                                <div>{{ $speciality->name ?? "" }}</div>
                            @endforeach
                        </div>
                    </div>

                    {{--<input class="input-tags form-control" type="text" data-role="tagsinput" placeholder="Enter Specialization"
                        name="specializations"
                        value="{{ $profile->specializations ?? '' }}"
                        id="specialist" />
                    <small class="form-text text-muted">Note : Type & Press enter to add new specialization</small>--}}
                </div> 
            </div>              
        </div>
        <!-- /Services and Specialization -->

        <input type="hidden" id="educations-data" value="{{ json_encode($profile->educations ?? []); }}" />
    
        <!-- Education -->
        <div class="card" id="education-app"></div>
        <!-- /Education -->

        <script type="text/babel">
            function Education() {

                const [data, setData] = React.useState(JSON.parse(document.getElementById("educations-data").value || "[]") || []);

                return (
                    <>
                        <div className="card-body">
                            <h4 className="card-title">Education</h4>
                            <div className="education-info">
                                { data.map(function (d, index) {
                                    return (
                                        <div className="row form-row education-cont"
                                            key={ `education-${ index }` }>
                                            <div className="col-12 col-md-6 col-lg-4">
                                                <div className="form-group">
                                                    <label>Degree</label>
                                                    <input type="text" className="form-control degree"
                                                        value={ d.degree }
                                                        onChange={ function (event) {
                                                            const temp = [ ...data ];
                                                            temp[index].degree = event.target.value;
                                                            setData(temp);
                                                        } } />
                                                </div> 
                                            </div>
                                            <div className="col-12 col-md-6 col-lg-4">
                                                <div className="form-group">
                                                    <label>College/Institute</label>
                                                    <input type="text" className="form-control institute"
                                                        value={ d.institute }
                                                        onChange={ function (event) {
                                                            const temp = [ ...data ];
                                                            temp[index].institute = event.target.value;
                                                            setData(temp);
                                                        } } />
                                                </div> 
                                            </div>
                                            <div className="col-12 col-md-6 col-lg-3">
                                                <div className="form-group">
                                                    <label>Year of Completion</label>
                                                    <input type="number" className="form-control year"
                                                        value={ d.year }
                                                        onChange={ function (event) {
                                                            const temp = [ ...data ];
                                                            temp[index].year = event.target.value;
                                                            setData(temp);
                                                        } } />
                                                </div> 
                                            </div>

                                            <div className="col-12 col-md-6 col-lg-1">
                                                <button type="button" className="remove-data" onClick={ function () {
                                                    const temp = [ ...data ];
                                                    temp.splice(index, 1);
                                                    setData(temp);
                                                } }>
                                                    <i className="fa fa-close"></i>
                                                </button>
                                            </div>
                                        </div>
                                    );
                                }) }
                            </div>
                            <div className="add-more">
                                <a href="#" className="add-education" onClick={ function (event) {
                                    event.preventDefault();
                                    const temp = [ ...data ];
                                    temp.push({
                                        degree: "",
                                        institute: "",
                                        year: ""
                                    });
                                    setData(temp);
                                } }>
                                    <i className="fa fa-plus-circle"></i> Add More
                                </a>
                            </div>
                        </div>
                    </>
                );
            }

            ReactDOM.createRoot(
                document.getElementById("education-app")
            ).render(<Education />);
        </script>

        <input type="hidden" id="experiences-data" value="{{ json_encode($profile->experiences ?? []); }}" />

        <!-- Experience -->
        <div class="card" id="experience-app"></div>
        <!-- /Experience -->

        <script type="text/babel">
            function Experience() {

                const [data, setData] = React.useState(JSON.parse(document.getElementById("experiences-data").value || "[]") || []);

                return (
                    <>
                        <div className="card-body">
                            <h4 className="card-title">Experience</h4>

                            <div className="experience-info">
                                { data.map(function (d, index) {
                                    return (
                                        <div className="row form-row experience-cont"
                                            key={ `experience-${ index }` }>
                                            <div className="col-12 col-md-6 col-lg-3">
                                                <div className="form-group">
                                                    <label>Hospital Name</label>
                                                    <input type="text" className="form-control name"
                                                        value={ d.name }
                                                        onChange={ function (event) {
                                                            const temp = [ ...data ];
                                                            temp[index].name = event.target.value;
                                                            setData(temp);
                                                        } } />
                                                </div> 
                                            </div>
                                            <div className="col-12 col-md-6 col-lg-3">
                                                <div className="form-group">
                                                    <label>From</label>
                                                    <input type="number" className="form-control from"
                                                        value={ d.from }
                                                        onChange={ function (event) {
                                                            const temp = [ ...data ];
                                                            temp[index].from = event.target.value;
                                                            setData(temp);
                                                        } } />
                                                </div> 
                                            </div>
                                            <div className="col-12 col-md-6 col-lg-3">
                                                <div className="form-group">
                                                    <label>To</label>
                                                    <input type="number" className="form-control to"
                                                        value={ d.to }
                                                        onChange={ function (event) {
                                                            const temp = [ ...data ];
                                                            temp[index].to = event.target.value;
                                                            setData(temp);
                                                        } } />
                                                </div> 
                                            </div>
                                            <div className="col-12 col-md-6 col-lg-2">
                                                <div className="form-group">
                                                    <label>Designation</label>
                                                    <input type="text" className="form-control designation"
                                                        value={ d.designation }
                                                        onChange={ function (event) {
                                                            const temp = [ ...data ];
                                                            temp[index].designation = event.target.value;
                                                            setData(temp);
                                                        } } />
                                                </div>
                                            </div>

                                            <div className="col-12 col-md-6 col-lg-1">
                                                <button type="button" className="remove-data" onClick={ function () {
                                                    const temp = [ ...data ];
                                                    temp.splice(index, 1);
                                                    setData(temp);
                                                } }>
                                                    <i className="fa fa-close"></i>
                                                </button>
                                            </div>
                                        </div>
                                    );
                                } ) }
                            </div>

                            <div className="add-more">
                                <a href="#" className="add-experience" onClick={ function (event) {
                                    event.preventDefault();
                                    const temp = [ ...data ];
                                    temp.push({
                                        name: "",
                                        from: "",
                                        to: "",
                                        designation: ""
                                    });
                                    setData(temp);
                                } }>
                                    <i className="fa fa-plus-circle"></i> Add More
                                </a>
                            </div>
                        </div>
                    </>
                );
            }

            ReactDOM.createRoot(
                document.getElementById("experience-app")
            ).render(<Experience />);
        </script>

        <input type="hidden" id="awards-data" value="{{ json_encode($profile->awards ?? []); }}" />
        
        <!-- Awards -->
        <div class="card" id="awards-app"></div>
        <!-- /Awards -->

        <script type="text/babel">
            function Awards() {

                const [data, setData] = React.useState(JSON.parse(document.getElementById("awards-data").value || "[]") || []);

                return (
                    <>
                        <div className="card-body">
                            <h4 className="card-title">Awards</h4>

                            <div className="awards-info">
                                { data.map(function (d, index) {
                                    return (
                                        <div className="row form-row awards-cont"
                                            key={ `award-${ index }` }>
                                            <div className="col-12 col-md-5">
                                                <div className="form-group">
                                                    <label>Award</label>
                                                    <input type="text" className="form-control award"
                                                        value={ d.award }
                                                        onChange={ function (event) {
                                                            const temp = [ ...data ];
                                                            temp[index].award = event.target.value;
                                                            setData(temp);
                                                        } } />
                                                </div> 
                                            </div>

                                            <div className="col-12 col-md-5">
                                                <div className="form-group">
                                                    <label>Year</label>
                                                    <input type="text" className="form-control year"
                                                        value={ d.year }
                                                        onChange={ function (event) {
                                                            const temp = [ ...data ];
                                                            temp[index].year = event.target.value;
                                                            setData(temp);
                                                        } } />
                                                </div> 
                                            </div>

                                            <div className="col-12 col-md-1">
                                                <button type="button" className="remove-data" onClick={ function () {
                                                    const temp = [ ...data ];
                                                    temp.splice(index, 1);
                                                    setData(temp);
                                                } }>
                                                    <i className="fa fa-close"></i>
                                                </button>
                                            </div>
                                        </div>
                                    );
                                } ) }
                            </div>

                            <div className="add-more">
                                <a href="#" className="add-award" onClick={ function (event) {
                                    event.preventDefault();
                                    const temp = [ ...data ];
                                    temp.push({
                                        award: "",
                                        year: ""
                                    });
                                    setData(temp);
                                } }>
                                    <i className="fa fa-plus-circle"></i> Add More
                                </a>
                            </div>
                        </div>
                    </>
                );
            }

            ReactDOM.createRoot(
                document.getElementById("awards-app")
            ).render(<Awards />);
        </script>
        
        <div class="submit-section submit-btn-bottom">
            <button type="submit" name="submit" class="btn btn-primary submit-btn">Save Changes</button>
        </div>
    </form>

    <script>

        window.addEventListener("load", function () {
            document.querySelectorAll('.dropdown-list').forEach(dropdown => {
                const input = dropdown.querySelector('.dropdown-input');
                const list = dropdown.querySelector('.dropdown-items');

                // Show the dropdown list on input click
                input.addEventListener('click', function () {
                    list.style.display = 'block';
                });

                // Hide dropdown when clicking outside
                document.addEventListener('click', function (e) {
                    if (!dropdown.contains(e.target)) {
                        list.style.display = 'none';
                    }
                });

                // Handle selection from dropdown list
                list.addEventListener('click', function (e) {
                    if (e.target && e.target.tagName === 'DIV') {
                        const selected = e.target.textContent;
                        let current = input.value.split(',').map(i => i.trim()).filter(Boolean);

                        if (!current.includes(selected)) {
                            current.push(selected);
                            input.value = current.join(', ');
                        }

                        list.style.display = 'none'; // Optional: close after selection
                    }
                });
            });
        });

        async function removeClinicImage(node, path) {
            node.parentElement.remove();

            try {
                const formData = new FormData();
                formData.append("path", path);

                const response = await axios.post(
                    baseUrl + "/doctors/remove-clinic-image",
                    formData,
                    {
                        headers: {
                            Authorization: "Bearer " + localStorage.getItem(accessTokenKey)
                        }
                    }
                );

                if (response.data.status == "success") {
                    //
                } else {
                    swal.fire("Error", response.data.message, "error");
                }
            } catch (exp) {
                console.log(exp.message);
            }
        }

        async function updateProfile() {
            event.preventDefault();
            const form = event.target;

            try {
                form.submit.setAttribute("disabled", "disabled");
                const formData = new FormData(form);

                const education = document.querySelectorAll(".education-cont");
                const educationArr = [];
                for (let a = 0; a < education.length; a++) {
                    const degree = education[a].querySelector(".degree").value || "";
                    const institute = education[a].querySelector(".institute").value || "";
                    const year = education[a].querySelector(".year").value || "";

                    educationArr.push({
                        degree: degree,
                        institute: institute,
                        year: year
                    });
                }
                formData.append("educations", JSON.stringify(educationArr));

                const experience = document.querySelectorAll(".experience-cont");
                const experiencesArr = [];
                for (let a = 0; a < experience.length; a++) {
                    const name = experience[a].querySelector(".name").value || "";
                    const from = experience[a].querySelector(".from").value || "";
                    const to = experience[a].querySelector(".to").value || "";
                    const designation = experience[a].querySelector(".designation").value || "";

                    experiencesArr.push({
                        name: name,
                        from: from,
                        to: to,
                        designation: designation
                    });
                }
                formData.append("experiences", JSON.stringify(experiencesArr));

                const awards = document.querySelectorAll(".awards-cont");
                const awardsArr = [];
                for (let a = 0; a < awards.length; a++) {
                    const award = awards[a].querySelector(".award").value || "";
                    const year = awards[a].querySelector(".year").value || "";

                    awardsArr.push({
                        award: award,
                        year: year
                    });
                }
                formData.append("awards", JSON.stringify(awardsArr));

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

    <style>
        .dropdown-list {
            position: relative;
            display: contents;
            width: 300px;
        }

        .dropdown-items {
            position: absolute;
            background-color: white;
            border: 1px solid #ccc;
            width: 100%;
            max-width: fit-content;
            max-height: 150px;
            overflow-y: auto;
            display: none;
            z-index: 100;
        }

        .dropdown-items div {
            padding: 8px;
            cursor: pointer;
        }

        .dropdown-items div:hover {
            background-color: #f0f0f0;
        }

        .remove-data {
            position: relative;
            top: 50%;
            transform: translateY(-50%);
            background-color: red;
            color: white;
            padding: 5px 8px;
            border-radius: 5px;
            cursor: pointer;
            border: none;
        }
    </style>

@endsection