@extends ("layouts/app")
@section("title", "Page not found")

@section ("main")

    <!-- Breadcrumb -->
    <div class="breadcrumb-bar">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-12 col-12">
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ request()->path() }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- /Breadcrumb -->

    <!-- Page Content -->
    <div class="content">
        <div class="container">

            <!-- Doctor Details Tab -->
            <div class="card">
                <div class="card-body pt-0">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div class="error-404">404</div>
                            <div class="message">Oops! The page you're looking for doesn't exist.</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <style>
        .error-404 {
          font-size: 120px;
          font-weight: bold;
          color: #ff6b6b;
          animation: float 2s ease-in-out infinite;
        }

        .message {
          font-size: 24px;
          color: #333;
          margin-top: 10px;
          animation: fadeIn 2s ease;
        }

        @keyframes float {
          0%, 100% {
            transform: translateY(0px);
          }
          50% {
            transform: translateY(-20px);
          }
        }

        @keyframes fadeIn {
          0% {
            opacity: 0;
            transform: translateY(20px);
          }
          100% {
            opacity: 1;
            transform: translateY(0);
          }
        }
    </style>

@endsection