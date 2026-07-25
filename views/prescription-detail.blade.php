@extends ("layouts/app")
@section("title", "Prescription #" . $prescription->id)

@section ("main")

    <!-- Breadcrumb -->
    <div class="breadcrumb-bar no-print">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-12 col-12">
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Prescription #{{ $prescription->id }}</li>
                        </ol>
                    </nav>
                    <h2 class="breadcrumb-title">Prescription #{{ $prescription->id }}</h2>
                </div>
            </div>
        </div>
    </div>
    <!-- /Breadcrumb -->

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="prescription-page">

                        <!-- Header -->
                        <div class="header">
                            <h1>Prescription</h1>
                            <p>Issued on: {{ $prescription->created_at }}</p>
                        </div>

                        <!-- Doctor & Patient Info -->
                        <div class="info-section">

                            @if ($prescription->doctor != null)
                                <div class="info-box">
                                    <h2>Doctor</h2>
                                    <p><strong>Name:</strong> Dr. {{ $prescription->doctor->name }}</p>
                                    <p>
                                        <strong>Specialty:</strong>

                                        @foreach (explode(",", $prescription->doctor->specializations) as $specialization)
                                            {{ $specialization }} |
                                        @endforeach
                                    </p>
                                    <p><strong>Contact:</strong> {{ $prescription->doctor->phone }}</p>
                                </div>
                            @endif

                            @if ($prescription->patient != null)
                                <div class="info-box">
                                    <h2>Patient</h2>
                                    <p><strong>Name:</strong> {{ $prescription->patient->name }}</p>
                                    <p><strong>Age:</strong> {{ $prescription->patient->age }}</p>
                                    <p><strong>Gender:</strong> {{ ucfirst($prescription->patient->gender) }}</p>
                                </div>
                            @endif

                        </div>

                        <!-- Prescription Table -->
                        <h2 class="section-title">Prescription Details</h2>
                        <table>
                            <thead>
                                <tr>
                                    <th>Medicine</th>
                                    <th>Quantity</th>
                                    <th>Days</th>
                                    <th>Timing</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $prescription->name }}</td>
                                    <td>{{ $prescription->quantity }}</td>
                                    <td>{{ $prescription->days }}</td>
                                    <td>
                                        @foreach ($prescription->times as $time)
                                            {{ ucfirst($time) }} |
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Notes -->
                        <h2 class="section-title">Additional Notes</h2>
                        <p class="notes">Take medications exactly as prescribed. Do not skip doses. Store medicines in a cool, dry place away from sunlight. Keep out of reach of children.</p>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .prescription-page {
            max-width: 800px;
            margin: 2rem auto;
            background-color: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f7f9fc;
            color: #333;
        }

        .prescription-page .header {
            text-align: center;
            margin-bottom: 2rem;
            color: #005577;
        }

        .prescription-page .header h1 {
            margin: 0;
            font-size: 2rem;
        }

        .prescription-page .header p {
            color: #888;
            font-size: 0.9rem;
        }

        .prescription-page .info-section {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            justify-content: space-between;
            margin-bottom: 2rem;
        }

        .prescription-page .info-box {
            flex: 1;
            min-width: 250px;
            color: #005577;
        }

        .prescription-page .info-box h2 {
            margin-bottom: 0.5rem;
        }

        .prescription-page .info-box p {
            color: #333;
        }

        .prescription-page .section-title {
            margin-top: 2rem;
            margin-bottom: 1rem;
            color: #005577;
        }

        .prescription-page table {
            width: 100%;
            border-collapse: collapse;
        }

        .prescription-page table th,
        .prescription-page table td {
            border: 1px solid #ddd;
            padding: 0.75rem;
            text-align: left;
        }

        .prescription-page table th {
            background-color: #f0f4f8;
        }

        .prescription-page .notes {
            font-style: italic;
            color: #333;
        }
    </style>

@endsection