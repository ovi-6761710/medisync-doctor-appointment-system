<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use DB;
use Storage;

class Patient extends Model
{
    public static function fetch_single($id)
    {
        $patient = DB::table("patients_profile")
            ->where("user_id", "=", $id)
            ->first();

        if ($patient == null)
        {
            return null;
        }

        return map_patient($patient);
    }

    public static function is_my_patient($my_id, $patient_id)
    {
        return DB::table("doctor_patients")
            ->where("doctor_id", "=", $my_id)
            ->where("patient_id", "=", $patient_id)
            ->exists();
    }
}
