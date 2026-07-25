@extends ("layouts/app")
@section ("title", "Invoice")

@section ("main")

    <!-- Breadcrumb -->
    <div class="breadcrumb-bar no-print">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-12 col-12">
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Invoice #{{ $invoice->id }}</li>
                        </ol>
                    </nav>
                    <h2 class="breadcrumb-title">Invoice #{{ $invoice->id }}</h2>
                </div>
            </div>
        </div>
    </div>
    <!-- /Breadcrumb -->
    
    <!-- Page Content -->
    <div class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="ribbon-box">
                        <div class="ribbon">Medisync</div>
                        <div class="invoice-content">
                            <div class="invoice-item">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="invoice-logo">
                                            <img src="{{ asset('/assets/img/logo.png') }}" alt="logo" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="invoice-details">
                                            <strong>Order:</strong> #{{ $invoice->id }} <br>
                                            <strong>Issued:</strong> {{ $invoice->created_at }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Invoice Item -->
                            <div class="invoice-item">
                                <div class="row">
                                    @if ($invoice->doctor != null)
                                        <div class="col-md-6">
                                            <div class="invoice-info">
                                                <strong class="customer-text">Invoice From</strong>
                                                <p class="invoice-details invoice-details-two">
                                                    Dr. {{ $invoice->doctor->name }} <br>
                                                    {{ $invoice->doctor->address }}<br>
                                                    {{ $invoice->doctor->city . ", " . $invoice->doctor->country }}<br>
                                                </p>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($invoice->patient != null)
                                        <div class="col-md-6">
                                            <div class="invoice-info invoice-info2">
                                                <strong class="customer-text">Invoice To</strong>
                                                <p class="invoice-details">
                                                    {{ $invoice->patient->name }} <br>
                                                    {{ $invoice->patient->address }}<br>
                                                    {{ $invoice->patient->city . ", " . $invoice->patient->country }}<br>
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <!-- /Invoice Item -->
                            
                            <!-- Invoice Item -->
                            <div class="invoice-item">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="invoice-info">
                                            <strong class="customer-text">Payment Method</strong>
                                            <p class="invoice-details invoice-details-two">
                                                Virtual account
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /Invoice Item -->
                            
                            <!-- Invoice Item -->
                            <div class="invoice-item invoice-table-wrap">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="invoice-table table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Description</th>
                                                        <th class="text-right">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            {{ ucfirst($invoice->type) }}

                                                            @if ($invoice->type == "booking" && $invoice->booking != null)
                                                                <br /><br />Day: {{ $invoice->booking->day }} <br />
                                                                Date: {{ $invoice->booking->date }} <br />
                                                                Time: {{ $invoice->booking->from }} <br />
                                                                Number: {{ $invoice->booking->number }}
                                                            @endif
                                                        </td>
                                                        <td class="text-right">৳{{ $invoice->amount }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xl-4 ml-auto">
                                        <div class="table-responsive">
                                            <table class="invoice-table-two table">
                                                <tbody>
                                                    <tr>
                                                        <th>Total Amount:</th>
                                                        <td><span>৳{{ $invoice->amount }}</span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /Invoice Item -->
                            
                            <!-- Invoice Information -->
                            <div class="other-info">
                                <h4>Important Information</h4>
                                <p class="text-muted mb-0">Thank you for booking your appointment with us. We look forward to serving your healthcare needs. If you need to modify or cancel your appointment, please do so at least 24 hours in advance.</p>
                            </div>
                            <!-- /Invoice Information -->

                            <div class="no-print mt-5">
                                <div class="row">
                                    <div class="offset-md-4 col-md-4">
                                        <button type="button" class="btn btn-primary btn-block"
                                            onclick="window.print();">Print</button>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>      
    <!-- /Page Content -->

    <style>
        .ribbon-box {
            position: relative;
            overflow: hidden;
        }

        .box-content {
            padding: 20px;
        }

        .ribbon {
            position: absolute;
            top: 15px;
            left: -40px;
            transform: rotate(-45deg);
            background-color: #e63946;
            color: #fff;
            padding: 5px 40px;
            font-weight: bold;
            font-size: 14px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
    </style>

@endsection