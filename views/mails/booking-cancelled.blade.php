<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Appointment Cancelled</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f8f9fa;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .card {
      background: #fff;
      padding: 2rem;
      border-radius: 16px;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
      max-width: 400px;
      width: 100%;
      text-align: center;
    }

    .card h2 {
      color: #dc3545;
      font-size: 1.75rem;
      margin-bottom: 0.5rem;
    }

    .card p {
      color: #555;
      margin: 0.75rem 0;
      font-size: 1rem;
    }

    .icon {
      font-size: 3rem;
      color: #dc3545;
      margin-bottom: 1rem;
    }

    .card a {
      display: inline-block;
      margin-top: 1.5rem;
      padding: 0.6rem 1.2rem;
      background-color: #dc3545;
      color: #fff;
      text-decoration: none;
      border-radius: 8px;
      font-weight: 600;
    }

    .card a:hover {
      background-color: #c82333;
    }
  </style>
</head>
<body>
  <div class="card">
    <div class="icon">❌</div>
    <h2>Appointment Cancelled</h2>
    <p>Your appointment with Dr. {{ $doctor->name }} has been cancelled.</p>
    <p>If you wish to reschedule, please use the button below.</p>
    <a href="{{ url('/') }}">Book Again</a>
  </div>
</body>
</html>
