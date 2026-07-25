<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use DB;
use Storage;

class Booking extends Model
{
    public static function map($c)
    {
        $obj = [
            "id" => $c->id ?? 0,
            "doctor_id" => $c->doctor_id ?? 0,
            "doctor_profile_id" => $c->doctor_profile_id ?? 0,
            "patient_id" => $c->patient_id ?? 0,
            "date" => $c->date ?? "",
            "day" => $c->day ?? "",
            "from" => convert_time_for_slot($c->from ?? ""),
            "to" => convert_time_for_slot($c->to ?? ""),
            "user_id" => $c->user_id ?? 0,
            "first_name" => $c->first_name ?? "",
            "last_name" => $c->last_name ?? "",
            "email" => $c->email ?? "",
            "phone" => $c->phone ?? "",
            "address" => $c->address ?? "",
            "state" => $c->state ?? "",
            "city" => $c->city ?? "",
            "country" => $c->country ?? "",
            "profile_image" => $c->profile_image ?? "",
            "gender" => $c->gender ?? "",
            "state" => $c->state ?? "",
            "country" => $c->country ?? "",
            "fee" => $c->fee ?? 0,
            "status" => $c->status ?? "",
            "invoice_id" => $c->invoice_id ?? 0,
            "doctor" => json_decode($c->doctor),
            "created_at" => date("d M, Y", strtotime($c->created_at . " UTC"))
        ];

        if ($obj["profile_image"] && Storage::exists("public/" . $obj["profile_image"]))
        {
            $obj["profile_image"] = url("/storage/" . $obj["profile_image"]);
        }

        if ($obj["doctor"] != null && $obj["doctor"]->profile_image && Storage::exists("public/" . $obj["doctor"]->profile_image))
        {
            $obj["doctor"]->profile_image = url("/storage/" . $obj["doctor"]->profile_image);
        }

        return (object) $obj;
    }

    public static function fetch($patient_id = 0, $doctor_id = 0, $status = "")
    {
        $bookings = DB::table("bookings");

        if ($patient_id > 0)
        {
            $bookings = $bookings->where("bookings.patient_id", "=", $patient_id);
        }

        if ($doctor_id > 0)
        {
            $bookings = $bookings->where("bookings.doctor_id", "=", $doctor_id);
        }

        if (!empty($status))
        {
            $bookings = $bookings->where("bookings.status", "=", $status);
        }
            
        return $bookings->orderBy("id", "desc")->paginate();
    }

    public static function fetch_by_id($id, $patient_id = 0, $doctor_id = 0, $status = "")
    {
        $booking = DB::table("bookings")
            ->where("bookings.id", "=", $id);

        if ($patient_id > 0)
        {
            $booking = $booking->where("bookings.patient_id", "=", $patient_id);
        }

        if ($doctor_id > 0)
        {
            $booking = $booking->where("bookings.doctor_id", "=", $doctor_id);
        }

        if (!empty($status))
        {
            $booking = $booking->where("bookings.status", "=", $status);
        }
            
        $booking = $booking->first();

        if ($booking == null)
        {
            return null;
        }

        return self::map($booking);
    }
}
