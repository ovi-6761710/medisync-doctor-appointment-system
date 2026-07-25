const accessTokenKey = "LaravelAuthenticationAccessToken"

const globalState = {
    state: {
        user: null
    },

    listeners: [],

    listen (callBack) {
        this.listeners.push(callBack)
    },

    setState (newState) {
        this.state = {
            ...this.state,
            ...newState
        }

        for (let a = 0; a < this.listeners.length; a++) {
            this.listeners[a](this.state, newState)
        }
    }
}

window.addEventListener("load", function () {
    if (localStorage.getItem("selectedTiming") !== null) {
        $("#header-checkout-link").show();
    }
});

async function toggleFavourite(event = null, id) {
    let node = null;
    if (event != null) {
        node = event.currentTarget;
        node.setAttribute("disabled", "disabled");
    }

    try {
        const formData = new FormData();
        formData.append("id", id);

        const response = await axios.post(
            baseUrl + "/favourites/toggle",
            formData,
            {
                headers: {
                    Authorization: "Bearer " + localStorage.getItem(accessTokenKey)
                }
            }
        );

        if (response.data.status == "success") {
            const action = response.data.action;
            if (node != null) {
                if (action == "added") {
                    node.className = "btn btn-white fav-btn fav-btn-highlight";
                } else if (action == "removed") {
                    node.className = "btn btn-white fav-btn";
                }
            }
        } else {
            swal.fire("Error", response.data.message, response.data.status);
        }
    } catch (exp) {
        console.log(exp.message);
    } finally {
        if (node != null) {
            node.removeAttribute("disabled");
        }
    }
}

function openBase64File(base64String, fileType) {
    // Decode base64 to binary data
    const byteCharacters = atob(base64String);
    const byteNumbers = new Array(byteCharacters.length);
    
    for (let i = 0; i < byteCharacters.length; i++) {
        byteNumbers[i] = byteCharacters.charCodeAt(i);
    }
    
    const byteArray = new Uint8Array(byteNumbers);
    const blob = new Blob([byteArray], { type: fileType });

    // Create a link pointing to the Blob
    const blobURL = URL.createObjectURL(blob);
    window.open(blobURL, '_blank');
}

function onProfileImageSelected(event) {
    var file = event.currentTarget.files;
    if (file.length > 0) {
        var fileReader = new FileReader()

        fileReader.onload = function (e) {
            document.getElementById("profile-image").setAttribute("src", e.target.result)
        }

        fileReader.readAsDataURL(file[0])
    }
}