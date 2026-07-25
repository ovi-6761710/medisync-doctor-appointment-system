@extends ("layouts/patient-profile")
@section ("title", $patient->name . " | Add Prescription")

@section ("content")

    <div class="card">
        <div class="card-header">
            <h4 class="card-title mb-0">Add Prescription</h4>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="biller-info">
                        <h4 class="d-block">Dr. {{ $my_profile->name ?? "" }}</h4>
                        <span class="d-block text-sm text-muted">
                            {{ str_replace(",", ", ", $my_profile->specializations ?? "") }}
                        </span>
                        <span class="d-block text-sm text-muted">{{ $my_profile->city . ", " . $my_profile->country }}</span>
                    </div>
                </div>
                <div class="col-sm-6 text-sm-right">
                    <div class="billing-info">
                        <h4 class="d-block">{{ date("d F, Y") }}</h4>
                    </div>
                </div>
            </div>

            <div id="items-app"></div>
            <input type="hidden" id="my-profile" value="{{ json_encode($my_profile) }}" />
            <input type="hidden" id="id" value="{{ $id }}" />

            <script type="text/babel">
                function Items() {
                    const id = document.getElementById("id").value;

                    const [data, setData] = React.useState([]);
                    const [saving, setSaving] = React.useState(false);
                    const [myProfile, setMyProfile] = React.useState(JSON.parse(document.getElementById("my-profile").value));

                    function addData() {
                        const temp = [ ...data ];
                        temp.push({
                            name: "",
                            quantity: "",
                            days: "",
                            times: []
                        });
                        setData(temp);
                    }

                    async function addPrescription() {
                        setSaving(true);

                        try {
                            const formData = new FormData();
                            formData.append("id", id);
                            formData.append("items", JSON.stringify(data));

                            const response = await axios.post(
                                baseUrl + "/patients/" + id + "/prescriptions/add",
                                formData
                            )

                            if (response.data.status == "success") {
                                swal.fire("Save Prescription", response.data.message, "success");
                            } else {
                                swal.fire("Error", response.data.message, response.data.status);
                            }
                        } catch (exp) {
                            console.log(exp.message);
                        } finally {
                            setSaving(false);
                        }
                    }

                    function toggleTime(event, index) {
                        const value = event.currentTarget.value;
                        const temp = [ ...data ];
                        if (event.currentTarget.checked) {
                            temp[index].times.push(value);
                        } else {
                            for (let a = 0; a < temp[index].times.length; a++) {
                                if (temp[index].times[a] == value) {
                                    temp[index].times.splice(a, 1);
                                    break;
                                }
                            }
                        }
                        setData(temp);
                    }

                    React.useEffect(function () {
                        addData();
                    }, []);

                    const styles = {
                        minWidth80: {
                            minWidth: "80px"
                        },
                        minWidth100: {
                            minWidth: "100px"
                        },
                        minWidth200: {
                            minWidth: "200px"
                        }
                    };

                    return (
                        <>
                            <div className="add-more-item text-right">
                                <a href="#" onClick={ function (event) {
                                    event.preventDefault();
                                    addData();
                                } }><i className="fas fa-plus-circle"></i> Add Item</a>
                            </div>

                            <div className="card card-table">
                                <div className="card-body">
                                    <div className="table-responsive">
                                        <table className="table table-hover table-center">
                                            <thead>
                                                <tr>
                                                    <th style={ styles.minWidth200 }>Name</th>
                                                    <th style={ styles.minWidth100 }>Quantity</th>
                                                    <th style={ styles.minWidth100 }>Days</th>
                                                    <th style={ styles.minWidth100 }>Time</th>
                                                    <th style={ styles.minWidth80 }></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                { data.map(function (d, index) {
                                                    return (
                                                        <tr key={ `data-${ index }` }>
                                                            <td>
                                                                <input className="form-control" type="text" name="name"
                                                                    value={ d.degree }
                                                                    onChange={ function (event) {
                                                                        const temp = [ ...data ];
                                                                        temp[index].name = event.target.value;
                                                                        setData(temp);
                                                                    } } />
                                                            </td>
                                                            <td>
                                                                <input className="form-control" type="number" name="quantity"
                                                                    value={ d.degree }
                                                                    onChange={ function (event) {
                                                                        const temp = [ ...data ];
                                                                        temp[index].quantity = parseFloat(event.target.value);
                                                                        setData(temp);
                                                                    } } />
                                                            </td>
                                                            <td>
                                                                <input className="form-control" type="number" min="1" step="1" name="days"
                                                                    value={ d.degree }
                                                                    onChange={ function (event) {
                                                                        const temp = [ ...data ];
                                                                        temp[index].days = parseInt(event.target.value);
                                                                        setData(temp);
                                                                    } } />
                                                            </td>
                                                            <td>
                                                                <div className="form-check form-check-inline">
                                                                    <label className="form-check-label">
                                                                        <input className="form-check-input" type="checkbox" value="morning"
                                                                            onClick={ function (event) {
                                                                                toggleTime(event, index);
                                                                            } } /> Morning
                                                                    </label>
                                                                </div>
                                                                <div className="form-check form-check-inline">
                                                                    <label className="form-check-label">
                                                                        <input className="form-check-input" type="checkbox" value="afternoon"
                                                                            onClick={ function (event) {
                                                                                toggleTime(event, index);
                                                                            } } /> Afternoon
                                                                    </label>
                                                                </div>
                                                                <div className="form-check form-check-inline">
                                                                    <label className="form-check-label">
                                                                        <input className="form-check-input" type="checkbox" value="evening"
                                                                            onClick={ function (event) {
                                                                                toggleTime(event, index);
                                                                            } } /> Evening
                                                                    </label>
                                                                </div>
                                                                <div className="form-check form-check-inline">
                                                                    <label className="form-check-label">
                                                                        <input className="form-check-input" type="checkbox" value="night"
                                                                            onClick={ function (event) {
                                                                                toggleTime(event, index);
                                                                            } } /> Night
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <a href="#" className="btn bg-danger-light trash"
                                                                    onClick={ function (event) {
                                                                        event.preventDefault();
                                                                        const temp = [ ...data ];
                                                                        temp.splice(index, 1);
                                                                        setData(temp);
                                                                    } }><i className="far fa-trash-alt"></i></a>
                                                            </td>
                                                        </tr>
                                                    );
                                                }) }
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div className="row">
                                <div className="col-md-12 text-right">
                                    <div className="signature-wrap">
                                        {/*<div className="signature">
                                            Click here to sign
                                        </div>*/}
                                        
                                        <div className="sign-name">
                                            <p className="mb-0">( Dr. { myProfile.name } )</p>
                                            <span className="text-muted">Signature</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div className="row">
                                <div className="col-md-12">
                                    <div className="submit-section">
                                        <button type="button" className="btn btn-primary submit-btn"
                                            onClick={ function () {
                                                addPrescription();
                                            } }>Save</button>

                                        <button type="reset" className="btn btn-secondary submit-btn"
                                            onClick={ function () {
                                                setData([]);
                                            } }>Clear</button>
                                    </div>
                                </div>
                            </div>
                        </>
                    );
                }

                ReactDOM.createRoot(
                    document.getElementById("items-app")
                ).render(<Items />);
            </script>
        </div>
    </div>

@endsection