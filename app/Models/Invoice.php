<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use DB;
use Storage;

class Invoice extends Model
{
    private static function map($collect)
    {
        $arr = [];
        foreach ($collect as $c)
        {
            $obj = [
                "id" => $c->id ?? 0,
                "amount" => $c->amount ?? 0,
                "type" => $c->type ?? "",
                "user_id" => $c->user_id ?? 0,
                "name" => $c->name ?? "",
                "profile_image" => $c->profile_image ?? "",
                "phone" => $c->phone ?? "",
                "email" => $c->email ?? "",
                "booking" => null,
                "doctor" => null,
                "patient" => null,
                "created_at" => date("d M, Y", strtotime(($c->created_at ?? "") . " UTC"))
            ];

            if ($obj["profile_image"] && Storage::exists("public/" . $obj["profile_image"]))
            {
                $obj["profile_image"] = url("/storage/" . $obj["profile_image"]);
            }

            if (isset($c->booking_id) && $c->booking_id > 0)
            {
                $obj["booking"] = (object) [
                    "id" => $c->booking_id ?? 0,
                    "from" => convert_time_for_slot($c->from ?? ""),
                    "to" => convert_time_for_slot($c->to ?? ""),
                    "day" => $c->day ?? "",
                    "date" => $c->date ?? "",
                    "fee" => $c->fee ?? 0,
                    "number" => $c->number ?? 0
                ];
            }

            if (isset($c->patient_name) && !empty($c->patient_name))
            {
                $obj["patient"] = (object) [
                    "id" => $c->patient_id ?? 0,
                    "name" => $c->patient_name ?? "",
                    "address" => $c->patient_address ?? "",
                    "city" => $c->patient_city ?? "",
                    "country" => $c->patient_country ?? "",
                    "profile_image" => $c->patient_profile_image ?? ""
                ];

                if ($obj["patient"]->profile_image && Storage::exists("public/" . $obj["patient"]->profile_image))
                {
                    $obj["patient"]->profile_image = url("/storage/" . $obj["patient"]->profile_image);
                }
            }

            if (isset($c->doctor_name) && !empty($c->doctor_name))
            {
                $obj["doctor"] = (object) [
                    "id" => $c->doctor_id ?? 0,
                    "name" => $c->doctor_name ?? "",
                    "address" => $c->doctor_address ?? "",
                    "city" => $c->doctor_city ?? "",
                    "country" => $c->doctor_country ?? "",
                    "profile_image" => $c->doctor_profile_image ?? ""
                ];

                if ($obj["doctor"]->profile_image && Storage::exists("public/" . $obj["doctor"]->profile_image))
                {
                    $obj["doctor"]->profile_image = url("/storage/" . $obj["doctor"]->profile_image);
                }
            }

            array_push($arr, (object) $obj);
        }
        return $arr;
    }

    public static function fetch_by_user($user_id)
    {
        $invoices = DB::table("invoices")
            ->select("invoices.*", "users.user_id", "users.name", "users.profile_image", "users.phone", "users.email",
                "bookings.from", "bookings.to")
            ->leftJoin("bookings", "bookings.id", "=", "invoices.booking_id");
    
        if ($user->type == "doctor")
        {
            $invoices = $invoices->join("patients_profile AS users", "users.user_id", "=", "invoices.patient_id")
                ->where("invoices.doctor_id", "=", $user_id);
        }
        else if ($user->type == "patient")
        {
            $invoices = $invoices->join("doctors_profile AS users", "users.id", "=", "invoices.doctor_id")
                ->where("invoices.patient_id", "=", $user_id);
        }

        $invoices = $invoices->orderBy("invoices.id", "desc")
            ->paginate();

        $arr = self::map($invoices);
        $total = $invoices->total();

        return [
            "invoices" => $arr,
            "total" => $total
        ];
    }

    public static function fetch_single($id, $user_id)
    {
        $invoices = DB::table("invoices")
            ->select("invoices.*", "patients_profile.name AS patient_name",
                "patients_profile.profile_image AS patient_profile_image",
                "patients_profile.address AS patient_address", "patients_profile.city AS patient_city",
                "patients_profile.country AS patient_country",
                "doctors_profile.name AS doctor_name", "doctors_profile.profile_image AS doctor_profile_image",
                "doctors_profile.address AS doctor_address", "doctors_profile.city AS doctor_city",
                "doctors_profile.country AS doctor_country",
                "bookings.day", "bookings.date", "bookings.from", "bookings.to", "bookings.fee", "bookings.number")
            ->join("patients_profile", "patients_profile.user_id", "=", "invoices.patient_id")
            ->join("doctors_profile", "doctors_profile.user_id", "=", "invoices.doctor_id")
            ->leftJoin("bookings", "bookings.id", "=", "invoices.booking_id")
            ->where("invoices.id", "=", $id)
            ->where(function ($query) use ($user_id) {
                $query->where("invoices.doctor_id", "=", $user_id)
                    ->orWhere("invoices.patient_id", "=", $user_id);
            })
            ->get();

        if (count($invoices) <= 0)
        {
            return null;
        }

        return self::map($invoices)[0];
    }
}
