<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Storage;
use Validator;
use App\Models\Patient;
use App\Models\Booking;

class PatientController extends Controller
{
    public function write_review()
    {
        return response()->json([
            "status" => "info",
            "message" => get_premium_message()
        ]);
    }

    public function add_medical_record()
    {
        return response()->json([
            "status" => "info",
            "message" => get_premium_message()
        ]);
    }

    public function add_prescription()
    {
        $user = auth()->user();
        $id = request()->id ?? 0;

        if (!is_numeric($id) || !Patient::is_my_patient($user->id, $id))
        {
            if (request()->wantsJson())
            {
                return response()->json([
                    "status" => "error",
                    "message" => "Patient not found."
                ]);
            }
            abort(404);
        }

        if (request()->isMethod("post"))
        {
            return response()->json([
                "status" => "info",
                "message" => get_premium_message()
            ]);
        }

        $patient = Patient::fetch_single($id);
        $my_profile = get_my_profile();

        return view("patients/add-prescription", [
            "id" => $id,
            "patient" => $patient,
            "my_profile" => $my_profile
        ]);
    }

    public function profile()
    {
        $user = auth()->user();
        $id = request()->id ?? 0;
        $timezone = request()->timezone ?? session(config("config.session_timezone_key"));

        if (!empty($timezone))
        {
            date_default_timezone_set($timezone);
        }

        if (!Patient::is_my_patient($user->id, $id))
        {
            abort(404);
        }

        $patient = Patient::fetch_single($id);
        $bookings = Booking::fetch($patient->user_id, $user->id);
        
        $bookings_arr = [];
        foreach ($bookings as $booking)
        {
            array_push($bookings_arr, Booking::map($booking));
        }

        return view("patients/profile", [
            "patient" => $patient,
            "bookings" => $bookings_arr,
            "bookings_pagination" => $bookings->withPath(url("/patients/" . $id . "/profile"))
                ->links("pagination::bootstrap-5")
                ->render()
        ]);
    }

    public function my()
    {
        $user = auth()->user();
        if ($user->type != "doctor")
        {
            abort(401);
        }
        
        $profile_obj = get_my_profile();
        $timezone = session(config("config.session_timezone_key")) ?? "";

        if (!empty($timezone))
        {
            date_default_timezone_set($timezone);
        }

        $doctor_patients = DB::table("doctor_patients")
            ->select("doctor_patients.doctor_id", "doctor_patients.patient_id",
                "doctor_patients.updated_at",
                "patients_profile.name AS patient_name", "patients_profile.email AS patient_email",
                "patients_profile.gender AS patient_gender",
                "patients_profile.phone AS patient_phone",
                "patients_profile.dob AS patient_dob",
                "patients_profile.profile_image AS patient_profile_image",
                "patients_profile.city AS patient_city",
                "patients_profile.country AS patient_country",
                "patients_profile.blood_group AS patient_blood_group")
            ->join("patients_profile", "patients_profile.user_id", "=", "doctor_patients.patient_id")
            ->where("doctor_patients.doctor_id", "=", $user->id)
            ->orderBy("doctor_patients.id")
            ->paginate();

        $arr = [];
        foreach ($doctor_patients as $c)
        {
            $obj = [
                "id" => $c->id ?? 0,
                "patient_id" => $c->patient_id ?? 0,
                "name" => $c->patient_name ?? "",
                "email" => $c->patient_email ?? "",
                "phone" => $c->patient_phone ?? "",
                "gender" => $c->patient_gender ?? "",
                "dob" => $c->patient_dob ?? "",
                "city" => $c->patient_city ?? "",
                "country" => $c->patient_country ?? "",
                "blood_group" => $c->patient_blood_group ?? "",
                "profile_image" => $c->patient_profile_image ?? "",
                "updated_at" => date("d M, Y h:i:s a", strtotime($c->updated_at . " UTC"))
            ];

            if ($obj["profile_image"] && Storage::exists("public/" . $obj["profile_image"]))
            {
                $obj["profile_image"] = url("/storage/" . $obj["profile_image"]);
            }

            array_push($arr, (object) $obj);
        }

        return view("doctors/my-patients", [
            "patients" => $arr,
            "profile" => $profile_obj,
            "pagination" => $doctor_patients->withPath("/my-patients")->links()->render()
        ]);
    }
}
