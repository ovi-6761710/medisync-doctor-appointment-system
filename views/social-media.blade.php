@extends ("layouts/" . auth()->user()->type)
@section("title", "Social media")

@section ("content")

    <div class="card">
        <div class="card-body">
        
            <!-- Social Form -->
            <form onsubmit="saveSocialMedia(event);" id="form-social-media">
                <div class="row">
                    <div class="col-md-12 col-lg-8">
                        <div class="form-group">
                            <label>Facebook URL</label>
                            <input type="text" name="facebook" value="{{ $social_media->facebook ?? '' }}" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-8">
                        <div class="form-group">
                            <label>Twitter URL</label>
                            <input type="text" name="twitter" value="{{ $social_media->twitter ?? '' }}" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-8">
                        <div class="form-group">
                            <label>Instagram URL</label>
                            <input type="text" name="instagram" value="{{ $social_media->instagram ?? '' }}" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-8">
                        <div class="form-group">
                            <label>Linkedin URL</label>
                            <input type="text" name="linkedin" value="{{ $social_media->linkedin ?? '' }}" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-8">
                        <div class="form-group">
                            <label>Youtube URL</label>
                            <input type="text" name="youtube" value="{{ $social_media->youtube ?? '' }}" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="submit-section">
                    <button type="submit" name="submit" class="btn btn-primary submit-btn">Save Changes</button>
                </div>
            </form>
            <!-- /Social Form -->
            
        </div>
    </div>

    <script>
        async function saveSocialMedia(event) {
            event.preventDefault();
            const form = document.getElementById("form-social-media");
            form.submit.setAttribute("disabled", "disabled");

            try {
                const formData = new FormData(form);

                const response = await axios.post(
                    baseUrl + "/social-media",
                    formData,
                    {
                        headers: {
                            Authorization: "Bearer " + localStorage.getItem(accessTokenKey)
                        }
                    }
                );

                if (response.data.status == "success") {
                    swal.fire("Social media", response.data.message, "success");
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