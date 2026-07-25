<?php

function get_premium_message()
{
    return "This feature is under development";
}

function validate_keys($arr, $allowed_keys)
{
    $keys = array_keys($arr);
    $extraKeys = array_diff($keys, $allowed_keys);
    $missingKeys = array_diff($allowed_keys, $keys);

    return empty($extraKeys) && empty($missingKeys);

    /*if (!empty($extraKeys) || !empty($missingKeys))
    {
        $message = "Invalid keys in educations.";
        if (!empty($extraKeys))
        {
            $message .= " Extras: " . implode(", ", $extraKeys);
        }

        if (!empty($missingKeys))
        {
            $message .= " Missing: " . implode(", ", $missingKeys);
        }

        return response()->json([
            'status' => 'error',
            'message' => $message
        ]);
    }*/
}

function map_timing($c)
{
    $obj = [
        "id" => $c->id ?? 0,
        "day" => $c->day ?? "",
        "from" => convert_time_for_slot($c->from ?? ""),
        "to" => convert_time_for_slot($c->to ?? ""),
        "user_id" => $c->user_id ?? 0,
        "name" => $c->name ?? "",
        "profile_image" => $c->profile_image ?? "",
        "gender" => $c->gender ?? "",
        "state" => $c->state ?? "",
        "country" => $c->country ?? "",
        "fee" => $c->fee ?? 0
    ];

    if ($obj["profile_image"] && Storage::exists("public/" . $obj["profile_image"]))
    {
        $obj["profile_image"] = url("/storage/" . $obj["profile_image"]);
    }

    return (object) $obj;
}

function map_doctor($c)
{
    $obj = [
        "id" => $c->id ?? 0,
        "user_id" => $c->user_id ?? 0,
        "name" => $c->name ?? "",
        "gender" => $c->gender ?? "",
        "dob" => $c->dob ?? "",
        "profile_image" => $c->profile_image ?? "",
        "about" => $c->about ?? "",
        "clinic_name" => $c->clinic_name ?? "",
        "clinic_address" => $c->clinic_address ?? "",
        "clinic_images" => $c->clinic_images ?? "[]",
        "email" => $c->email ?? "",
        "phone" => $c->phone ?? "",
        "address" => $c->address ?? "",
        "city" => $c->city ?? "",
        "state" => $c->state ?? "",
        "country" => $c->country ?? "",
        "fee" => $c->fee ?? 0,
        "services" => $c->services ?? "",
        "specializations" => $c->specializations ?? "",
        "educations" => $c->educations ?? "[]",
        "experiences" => $c->experiences ?? "[]",
        "awards" => $c->awards ?? "[]",
        "ratings" => (double) ($c->ratings ?? 0),
        "reviews" => (int) ($c->reviews ?? 0),
        "is_favourite" => (bool) ($c->is_favourite ?? false)
        // "is_favourite" => (bool) data_get($c, "is_favourite", false)
    ];

    if ($obj["profile_image"] && \Storage::exists("public/" . $obj["profile_image"]))
    {
        $obj["profile_image"] = url("/storage/" . $obj["profile_image"]);
    }

    $clinic_images = json_decode($obj["clinic_images"], true);
    $clinic_images = is_array($clinic_images) ? $clinic_images : [];

    foreach ($clinic_images as $key => $image)
    {
        if ($image && \Storage::exists("public/" . $image))
        {
            $clinic_images[$key] = url("/storage/" . $image);
        }
    }
    $obj["clinic_images"] = $clinic_images;

    $educations = json_decode($obj["educations"], true);
    $educations = is_array($educations) ? $educations : [];
    foreach ($educations as $key => $value)
    {
        $educations[$key] = (object) $value;
    }
    $obj["educations"] = $educations;

    $experiences = json_decode($obj["experiences"], true);
    $experiences = is_array($experiences) ? $experiences : [];
    foreach ($experiences as $key => $value)
    {
        $experiences[$key] = (object) $value;
    }
    $obj["experiences"] = $experiences;

    $awards = json_decode($obj["awards"], true);
    $awards = is_array($awards) ? $awards : [];
    foreach ($awards as $key => $value)
    {
        $awards[$key] = (object) $value;
    }
    $obj["awards"] = $awards;

    return (object) $obj;
}

function map_patient($c)
{
    $obj = [
        "id" => $c->id ?? 0,
        "user_id" => $c->user_id ?? 0,
        "name" => $c->name ?? "",
        "email" => $c->email ?? "",
        "phone" => $c->phone ?? "",
        "gender" => $c->gender ?? "",
        "dob" => $c->dob ?? "",
        "profile_image" => $c->profile_image ?? "",
        "address" => $c->address ?? "",
        "blood_group" => $c->blood_group ?? "",
        "city" => $c->city ?? "",
        "state" => $c->state ?? "",
        "country" => $c->country ?? "",
    ];

    if ($obj["profile_image"] && \Storage::exists("public/" . $obj["profile_image"]))
    {
        $obj["profile_image"] = url("/storage/" . $obj["profile_image"]);
    }
    
    return (object) $obj;
}

function get_my_profile()
{
    $profile_obj = null;
    $user = auth()->user();

    if ($user->type == "doctor")
    {
        $profile = \DB::table("doctors_profile")
            ->where("user_id", "=", $user->id)
            ->get();

        if (count($profile) > 0)
        {
            $profile_obj = map_doctor($profile[0]);
        }
    }
    else if ($user->type == "patient")
    {
        $profile = \DB::table("patients_profile")
            ->where("user_id", "=", $user->id)
            ->get();

        if (count($profile) > 0)
        {
            $profile_obj = map_patient($profile[0]);
        }
    }

    return $profile_obj;
}

function convert_dob_to_years($dob)
{
    if (!$dob) {
        return null;
    }

    try {
        $dobDate = new DateTime($dob);
        $now = new DateTime();
        $age = $dobDate->diff($now)->y;
        return $age;
    } catch (Exception $e) {
        return null;
    }
}

function isValidTime($time)
{
    $d = DateTime::createFromFormat('H:i', $time);
    return $d && $d->format('H:i') === $time;
}

function convert_time_for_slot($time)
{
    $dt = DateTime::createFromFormat('H:i:s', $time);
    return $dt->format('H:i');
}