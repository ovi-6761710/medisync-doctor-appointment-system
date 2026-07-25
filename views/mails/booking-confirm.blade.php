<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Appointment Confirmation</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0; padding:0; font-family:Arial, sans-serif; background-color:#f4f4f4;">
  <table align="center" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px; margin:auto; background-color:#ffffff; border-collapse:collapse;">
    <tr>
      <td align="center" bgcolor="#2c3e50" style="padding:20px 0;">
        <h1 style="color:#ffffff; margin:0;">Appointment Confirmation</h1>
      </td>
    </tr>

    <tr>
      <td style="padding:20px;">
        <p style="font-size:16px; margin:0 0 15px;">
          Dear {{ $to == "doctor" ? $booking->email : $doctor->email }},
        </p>

        @if ($to == 'patient')
          <p style="font-size:16px; margin:0 0 15px;">
            Your appointment with <strong>Dr. {{ $doctor->name }}</strong> has been confirmed.
          </p>
        @elseif ($to == 'doctor')
          <p style="font-size:16px; margin:0 0 15px;">
            You have a new appointment with <strong>{{ $booking->first_name . " " . $booking->last_name }}</strong>.
          </p>
        @endif

        <table cellpadding="0" cellspacing="0" width="100%" style="margin-top:15px; font-size:15px;">
          <tr>
            <td style="padding:8px 0;"><strong>Date:</strong></td>
            <td style="padding:8px 0;">{{ $booking->date }}</td>
          </tr>
          <tr>
            <td style="padding:8px 0;"><strong>Time:</strong></td>
            <td style="padding:8px 0;">{{ $booking->from }}</td>
          </tr>
          <tr>
            <td style="padding:8px 0;"><strong>Location:</strong></td>
            <td style="padding:8px 0;">{{ $doctor->address }}</td>
          </tr>
          <tr>
            <td style="padding:8px 0;"><strong>City:</strong></td>
            <td style="padding:8px 0;">{{ $doctor->city }}</td>
          </tr>
          <tr>
            <td style="padding:8px 0;"><strong>State:</strong></td>
            <td style="padding:8px 0;">{{ $doctor->state }}</td>
          </tr>
          <tr>
            <td style="padding:8px 0;"><strong>Country:</strong></td>
            <td style="padding:8px 0;">{{ $doctor->country }}</td>
          </tr>
        </table>

        <p style="font-size:15px; margin:20px 0 0;">
          @if ($to == 'patient')
            Please arrive 10 minutes early and bring any relevant documents.
          @else
            Make sure you're prepared and review the patient's details in advance.
          @endif
        </p>

        <p style="font-size:15px;">
          If you need to cancel or reschedule, contact us at least 24 hours in advance.
        </p>
      </td>
    </tr>

    <tr>
      <td align="center" bgcolor="#ecf0f1" style="padding:20px;">
        <p style="font-size:13px; margin:0;">
          Need help? Contact us at <a href="mailto:{ config('config.email') }}">{{ config("config.email") }}</a><br>
          Website | <a href="{{ url('/') }}">{{config('config.app_name') }}</a>
        </p>
      </td>
    </tr>
  </table>
</body>
</html>