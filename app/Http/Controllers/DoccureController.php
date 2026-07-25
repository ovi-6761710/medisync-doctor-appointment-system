<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Str;
use Storage;
use Validator;
use Mail;
use App\Models\User;
use App\Models\Booking;
use App\Models\Patient;
use App\Mail\BookingConfirm;
use App\Mail\BookingCancelled;

class DoccureController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $profile_obj = get_my_profile();

        if ($user->type == "doctor")
        {
            $patients = DB::table("doctor_patients")
                ->where("doctor_id", "=", $user->id)
                ->count();

            $bookings_count = DB::table("bookings")
                ->where("doctor_id", "=", $user->id)
                ->count();

            $bookings = DB::table("bookings")
                ->select("bookings.*", "doctors_profile.id AS doctor_profile_id")
                ->where(function ($query) use ($user) {
                    $query->where("bookings.patient_id", "=", $user->id)
                        ->orWhere("bookings.doctor_id", "=", $user->id);
                })
                ->join("doctors_profile", "doctors_profile.user_id", "=", "bookings.doctor_id")
                ->orderBy("bookings.id", "desc")
                ->paginate();

            $bookings_arr = [];
            foreach ($bookings as $booking)
            {
                array_push($bookings_arr, Booking::map($booking));
            }

            $bookings_pagination = $bookings->withPath(url("/appointments"))->links("pagination::bootstrap-5")->render();

            return view("doctors/dashboard", [
                "profile" => $profile_obj,
                "patients" => $patients,
                "bookings_count" => $bookings_count,
                "bookings" => $bookings_arr,
                "bookings_pagination" => $bookings_pagination
            ]);
        }

        if ($user->type == "patient")
        {
            $bookings = Booking::fetch($user->id);

            $bookings_arr = [];
            foreach ($bookings as $booking)
            {
                array_push($bookings_arr, Booking::map($booking));
            }

            $bookings_pagination = $bookings->withPath(url("/appointments"))->links("pagination::bootstrap-5")->render();

            return view("appointments", [
                "bookings" => $bookings_arr,
                "bookings_pagination" => $bookings_pagination,
                "profile" => $profile_obj,
            ]);
        }
    }

    public function profile_settings()
    {
        $user = auth()->user();
        $profile_obj = get_my_profile();

        if ($user->type == "doctor")
        {
            if (request()->is("api/*"))
            {
                //
            }

            $services = DB::table("services")
                ->orderBy("name", "asc")
                ->get();

            $specialities = DB::table("specialities")
                ->orderBy("name", "asc")
                ->get();

            return view("doctors/profile-settings", [
                "profile" => $profile_obj,
                "services" => $services,
                "specialities" => $specialities
            ]);
        }

        if ($user->type == "patient")
        {
            return view("patients/profile-settings", [
                "profile" => $profile_obj
            ]);
        }
    }

    public function doctor_profile()
    {
        $user = auth()->check() ? auth()->user() : null;
        $id = request()->id ?? 0;
        $timezone = request()->timezone ?? session(config("config.session_timezone_key"));

        if (!empty($timezone))
        {
            date_default_timezone_set($timezone);
        }

        $collect = DB::table("doctors_profile");
        if ($user != null)
        {
            $collect = $collect->select("doctors_profile.*");
                // ->leftJoin("favourites", function ($query) use ($user) {
                //     $query->on("favourites.user_id", "=", "doctors_profile.user_id")
                //         ->where("favourites.my_id", "=", $user->id);
                // });
        }
        
        $collect = $collect->where("doctors_profile.id", "=", $id)
            ->get();

        if (count($collect) <= 0)
        {
            abort(404);
        }

        $doctor = map_doctor($collect[0]);

        $timings = DB::table("timings")
            ->select("timings.*", "doctors_profile.name", "doctors_profile.gender", "doctors_profile.state",
                "doctors_profile.profile_image", "doctors_profile.country", "doctors_profile.fee")
            ->join("doctors_profile", "doctors_profile.user_id", "=", "timings.user_id")
            ->where("timings.user_id", "=", $doctor->user_id)
            ->orderBy("timings.from", "asc")
            ->get();

        $today_timings = [];
        $today_day = date("l");

        $timings_arr = [];
        foreach ($timings as $timing)
        {
            $timing_obj = map_timing($timing);
            if ($timing_obj->day == $today_day)
            {
                array_push($today_timings, $timing_obj);
            }

            array_push($timings_arr, $timing_obj);
        }

        $grouped = [];
        foreach ($timings_arr as $timing)
        {
            $day = $timing->day;

            if (!isset($grouped[$day]))
            {
                $grouped[$day] = [];
            }

            array_push($grouped[$day], (object) [
                "from" => $timing->from,
                "to" => $timing->to
            ]);
        }

        $social_media = DB::table("social_media")
            ->where("user_id", "=", $doctor->user_id)
            ->first();

        return view("doctors/profile", [
            "doctor" => $doctor,
            "social_media" => $social_media,
            "timings" => $grouped,
            "today_timings" => $today_timings,
            "has_reviewed" => false,
            "reviews" => [],
            "total" => 0,
            "pages" => 0
        ]);
    }

    public function update_profile()
    {
        $validator = Validator::make(request()->all(), [
            "name" => "required"
        ]);

        if ($validator->fails())
        {
            return response()->json([
                "status" => "error",
                "message" => $validator->errors()->first()
            ]);
        }

        $user = auth()->user();
        $name = request()->name ?? "";
        $email = request()->email ?? "";
        $phone = request()->phone ?? "";
        $gender = request()->gender ?? "";
        $dob = request()->dob ?? "";
        $address = request()->address ?? "";
        $city = request()->city ?? "";
        $state = request()->state ?? "";
        $country = request()->country ?? "";
        $profile_image = request()->file("profile_image");

        if ($profile_image && stripos($profile_image->getMimeType(), "image") === false)
        {
            return response()->json([
                "status" => "error",
                "message" => "Please select image only."
            ]);
        }

        // if (!empty($dob))
        {
            $date = \DateTime::createFromFormat('Y-m-d', $dob);
            $isValid = $date && $date->format('Y-m-d') === $dob;

            if (!$isValid)
            {
                return response()->json([
                    "status" => "error",
                    "message" => "Invalid date of birth '" . $dob . "'."
                ]);
            }
        }

        if (!empty($gender) && !in_array($gender, ["male", "female"]))
        {
            return response()->json([
                "status" => "error",
                "message" => "Invalid gender '" . $gender . "'."
            ]);
        }

        $profile = null;

        if ($user->type == "doctor")
        {
            $profile = DB::table("doctors_profile")
                ->where("user_id", "=", $user->id)
                ->first();
        }
        else if ($user->type == "patient")
        {
            $profile = DB::table("patients_profile")
                ->where("user_id", "=", $user->id)
                ->first();
        }

        $profile_obj = [
            "name" => $name,
            "email" => $email,
            "phone" => $phone,
            "gender" => $gender,
            "dob" => $dob,
            "address" => $address,
            "city" => $city,
            "state" => $state,
            "country" => $country,
            "updated_at" => now()->utc()
        ];

        if ($profile_image)
        {
            if ($profile != null && $profile->profile_image && Storage::exists("public/" . $profile->profile_image))
            {
                Storage::delete("public/" . $profile->profile_image);
            }

            $file_path = "users/" . uniqid() . "." . $profile_image->getClientOriginalExtension();
            $profile_image->storeAs("/public", $file_path);

            $profile_obj["profile_image"] = $file_path;
            chmod(storage_path("app/public/users"), 0755);
        }

        if ($user->type == "doctor")
        {
            $about = request()->about ?? "";
            $clinic_name = request()->clinic_name ?? "";
            $clinic_address = request()->clinic_address ?? "";
            $fee = (int) (request()->fee ?? 0);
            $services = request()->services ?? "";
            $specializations = request()->specializations ?? "";
            $educations = json_decode(request()->educations ?? "[]", true) ?? [];
            $experiences = json_decode(request()->experiences ?? "[]", true) ?? [];
            $awards = json_decode(request()->awards ?? "[]", true) ?? [];

            $allowed_keys = ["degree", "institute", "year"];
            foreach ($educations as $education)
            {
                $are_keys_valid = validate_keys($education, $allowed_keys);
                if (!$are_keys_valid)
                {
                    if (isset($profile_obj["profile_image"]) && Storage::exists("public/" . $profile_obj["profile_image"]))
                    {
                        Storage::delete("public/" . $profile_obj["profile_image"]);
                    }

                    return response()->json([
                        'status' => 'error',
                        'message' => "Invalid keys in educations."
                    ]);
                }
            }

            $allowed_keys = ["name", "from", "to", "designation"];
            foreach ($experiences as $experience)
            {
                $are_keys_valid = validate_keys($experience, $allowed_keys);
                if (!$are_keys_valid)
                {
                    if (isset($profile_obj["profile_image"]) && Storage::exists("public/" . $profile_obj["profile_image"]))
                    {
                        Storage::delete("public/" . $profile_obj["profile_image"]);
                    }

                    return response()->json([
                        'status' => 'error',
                        'message' => "Invalid keys in experiences."
                    ]);
                }
            }

            $allowed_keys = ["award", "year"];
            foreach ($awards as $award)
            {
                $are_keys_valid = validate_keys($award, $allowed_keys);
                if (!$are_keys_valid)
                {
                    if (isset($profile_obj["profile_image"]) && Storage::exists("public/" . $profile_obj["profile_image"]))
                    {
                        Storage::delete("public/" . $profile_obj["profile_image"]);
                    }

                    return response()->json([
                        'status' => 'error',
                        'message' => "Invalid keys in awards."
                    ]);
                }
            }

            if (request()->hasFile("clinic_images"))
            {
                foreach (request()->file("clinic_images") as $file)
                {
                    // Validate each file
                    if ($file->isValid())
                    {
                        $mime = $file->getMimeType();
                        
                        if (stripos($mime, 'image') === false && stripos($mime, 'video') === false)
                        {
                            if (isset($profile_obj["profile_image"]) && Storage::exists("public/" . $profile_obj["profile_image"]))
                            {
                                Storage::delete("public/" . $profile_obj["profile_image"]);
                            }

                            return response()->json([
                                "status" => "error",
                                "message" => "Please select image or video only for clinic."
                            ]);
                        }
                    }
                }
            }

            // Get array from comma-separated input
            $inputServices = explode(',', $services); // or $input['services']
            $inputServices = array_map('trim', $inputServices); // Clean up whitespace

            // Fetch valid services from DB
            $validServices = DB::table('services')->pluck('name')->map('trim')->toArray();

            // Check for invalid entries
            $invalidServices = array_diff($inputServices, $validServices);

            if (!empty($invalidServices))
            {
                if (isset($profile_obj["profile_image"]) && Storage::exists("public/" . $profile_obj["profile_image"]))
                {
                    Storage::delete("public/" . $profile_obj["profile_image"]);
                }

                return response()->json([
                    "status" => "error",
                    "message" => "Invalid service(s): " . implode(', ', $invalidServices)
                ]);
            }

            // Get array from comma-separated input
            $inputSpecializations = explode(',', $specializations); // or $input['specializations']
            $inputSpecializations = array_map('trim', $inputSpecializations); // Clean up whitespace

            // Fetch valid specializations from DB
            $validSpecializations = DB::table('specialities')->pluck('name')->map('trim')->toArray();

            // Check for invalid entries
            $invalidSpecializations = array_diff($inputSpecializations, $validSpecializations);

            if (!empty($invalidSpecializations))
            {
                if (isset($profile_obj["profile_image"]) && Storage::exists("public/" . $profile_obj["profile_image"]))
                {
                    Storage::delete("public/" . $profile_obj["profile_image"]);
                }
                
                return response()->json([
                    "status" => "error",
                    "message" => "Invalid specialization(s): " . implode(', ', $invalidSpecializations)
                ]);
            }

            $profile_obj["about"] = $about;
            $profile_obj["clinic_name"] = $clinic_name;
            $profile_obj["clinic_address"] = $clinic_address;
            $profile_obj["fee"] = $fee;
            $profile_obj["services"] = $services;
            $profile_obj["specializations"] = $specializations;
            $profile_obj["educations"] = json_encode($educations);
            $profile_obj["experiences"] = json_encode($experiences);
            $profile_obj["awards"] = json_encode($awards);

            if (request()->hasFile("clinic_images"))
            {
                $clinic_images = [];

                if ($profile != null)
                {
                    $clinic_images = json_decode($profile->clinic_images ?? "[]") ?? [];
                }

                foreach (request()->file("clinic_images") as $file)
                {
                    // Validate each file
                    if ($file->isValid())
                    {
                        $file_path = "clinics/" . $user->id . "/" . uniqid() . "." . $file->getClientOriginalExtension();
                        $file->storeAs("/public", $file_path);
                        array_push($clinic_images, $file_path);

                        chmod(storage_path("app/public/clinics"), 0755);
                        chmod(storage_path("app/public/clinics/" . $user->id), 0755);
                    }
                }
                if (count($clinic_images) > 0)
                {
                    $profile_obj["clinic_images"] = json_encode($clinic_images);
                }
            }

            if ($profile == null)
            {
                $profile_obj["user_id"] = $user->id;
                $profile_obj["created_at"] = now()->utc();

                DB::table("doctors_profile")
                    ->insertGetId($profile_obj);
            }
            else
            {
                DB::table("doctors_profile")
                    ->where("id", "=", $profile->id)
                    ->update($profile_obj);
            }
        }
        else if ($user->type == "patient")
        {
            $blood_group = request()->blood_group ?? "";
            $profile_obj["blood_group"] = $blood_group;

            if ($profile == null)
            {
                $profile_obj["user_id"] = $user->id;
                $profile_obj["created_at"] = now()->utc();

                DB::table("patients_profile")
                    ->insertGetId($profile_obj);
            }
            else
            {
                DB::table("patients_profile")
                    ->where("id", "=", $profile->id)
                    ->update($profile_obj);
            }
        }

        return response()->json([
            "status" => "success",
            "message" => "Profile has been updated."
        ]);
    }

    public function remove_clinic_image()
    {
        $validator = Validator::make(request()->all(), [
            "path" => "required"
        ]);

        if ($validator->fails())
        {
            return response()->json([
                "status" => "error",
                "message" => $validator->errors()->first()
            ]);
        }

        $user = auth()->user();
        $path = request()->path ?? "";

        $profile = DB::table("doctors_profile")
            ->where("user_id", "=", $user->id)
            ->first();

        if ($profile == null)
        {
            return response()->json([
                "status" => "error",
                "message" => "Profile not found."
            ]);
        }

        $clinic_images = json_decode($profile->clinic_images ?? "[]") ?? [];
        foreach ($clinic_images as $key => $image)
        {
            if (url("/storage/" . $image) == $path)
            {
                array_splice($clinic_images, $key, 1);

                if ($image && Storage::exists("public/" . $image))
                {
                    Storage::delete("public/" . $image);
                }
            }
        }

        DB::table("doctors_profile")
            ->where("id", "=", $profile->id)
            ->update([
                "clinic_images" => $clinic_images,
                "updated_at" => now()->utc()
            ]);
        
        return response()->json([
            "status" => "success",
            "message" => "Image has been removed."
        ]);
    }

    public function search()
    {
        $user = auth()->check() ? auth()->user() : null;
        
        $location = request()->location ?? "";
        $search = request()->search ?? "";
        $gender = json_decode(request()->gender ?? "[]", false);
        $specialities = json_decode(request()->specialities ?? "[]", false);

        $collect = DB::table("doctors_profile")
            ->select("doctors_profile.*");

        if (!empty($location))
        {
            $collect = $collect->where(function ($query) use ($location) {
                $query->orWhere("city", "LIKE", "%" . $location . "%")
                    ->orWhere("state", "LIKE", "%" . $location . "%")
                    ->orWhere("country", "LIKE", "%" . $location . "%")
                    ->orWhere("address", "LIKE", "%" . $location . "%");
            });
        }

        if (count($gender) > 0)
        {
            $collect = $collect->whereIn("doctors_profile.gender", $gender);
        }

        if (count($specialities) > 0)
        {
            $collect = $collect->where(function ($query) use ($specialities) {
                foreach ($specialities as $speciality)
                {
                    $query->orWhere("doctors_profile.specializations", "LIKE", "%" . $speciality . "%");
                }
            });
        }

        $collect = $collect->orderBy("doctors_profile.id", "desc")
            ->paginate();

        $total = $collect->total();

        $doctors = [];
        foreach ($collect as $c)
        {
            array_push($doctors, map_doctor($c));
        }

        $db_specialities = DB::table("specialities")
            ->orderBy("name", "asc")
            ->get();

        return view("patients/search", [
            "doctors" => $doctors,
            "total" => $total,
            "pagination" => $collect->withPath(url("/search"))->links("pagination::bootstrap-5")->render(),
            "specialities" => $db_specialities,
            "location" => $location,
            "search" => $search,
            "gender" => $gender,
            "searched_specialities" => $specialities
        ]);
    }

    public function booking()
    {
        $id = request()->id ?? 0;

        $profile = DB::table("doctors_profile")
            ->where("id", "=", $id)
            ->get();

        if (count($profile) <= 0)
        {
            abort(404);
        }

        $profile = map_doctor($profile[0]);

        $timings = DB::table("timings")
            ->where("user_id", "=", $profile->user_id)
            ->get();

        $timings_arr = [];
        foreach ($timings as $timing)
        {
            $obj = [
                "id" => $timing->id ?? 0,
                "day" => $timing->day ?? "",
                "from" => convert_time_for_slot($timing->from ?? ""),
                "to" => convert_time_for_slot($timing->to ?? ""),
            ];

            array_push($timings_arr, (object) $obj);
        }

        $timezone = session(config("config.session_timezone_key")) ?? "";

        if (empty($timezone))
        {
            $today = new \DateTime();
        }
        else
        {
            $today = new \DateTime("now", new \DateTimeZone($timezone));
        }

        $oneYearLater = (clone $today)->modify('+1 year');

        $interval = new \DateInterval('P1D'); // 1 day
        $period = new \DatePeriod($today, $interval, $oneYearLater);

        $calendar = [];
        foreach ($period as $date)
        {
            $weekday = $date->format('l');         // Full weekday name (e.g., Monday)
            $formatted = $date->format('d F, Y');

            array_push($calendar, [
                "day" => $weekday,
                "date" => $formatted
            ]);
        }
        
        return view("doctors/booking", [
            "doctor" => $profile,
            "timings" => $timings_arr,
            "calendar" => $calendar
        ]);
    }

    public function schedule_timings()
    {
        $user = auth()->user();
        $days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];

        if (request()->isMethod("post"))
        {
            $validator = Validator::make(request()->all(), [
                "day" => "required",
                "slots" => "required"
            ]);

            if ($validator->fails())
            {
                return response()->json([
                    "status" => "error",
                    "message" => $validator->errors()->first()
                ]);
            }

            $doctor = DB::table("doctors_profile")
                ->where("user_id", "=", $user->id)
                ->first();

            if ($doctor == null)
            {
                return response()->json([
                    "status" => "error",
                    "message" => "Please set your profile first."
                ]);
            }

            $day = request()->day ?? "";
            $slots = json_decode(request()->slots ?? "[]", true) ?? [];

            if (!in_array($day, $days))
            {
                return response()->json([
                    "status" => "error",
                    "message" => "Invalid day '" . $day . "'."
                ]);
            }

            foreach ($slots as $slot)
            {
                if (!isValidTime($slot["from"]))
                {
                    return response()->json([
                        "status" => "error",
                        "message" => "Invalid time '" . $slot["from"] . "'."
                    ]);
                }

                if (!isValidTime($slot["to"]))
                {
                    return response()->json([
                        "status" => "error",
                        "message" => "Invalid time '" . $slot["to"] . "'."
                    ]);
                }
            }

            $inserted = [];
            foreach ($slots as $slot)
            {
                $obj = [
                    "user_id" => $user->id,
                    "day" => $day,
                    "from" => $slot["from"],
                    "to" => $slot["to"],
                    "created_at" => now()->utc(),
                    "updated_at" => now()->utc()
                ];

                $obj["id"] = DB::table("timings")
                    ->insertGetId($obj);

                array_push($inserted, (object) $obj);
            }

            foreach ($inserted as $insert)
            {
                unset($insert->user_id);
                unset($insert->updated_at);
                unset($insert->updated_at);
            }

            return response()->json([
                "status" => "success",
                "message" => "Schedule timing has been saved.",
                "inserted" => $inserted
            ]);
        }

        $profile_obj = get_my_profile();

        $timings = DB::table("timings")
            ->where("user_id", "=", $user->id)
            ->get();

        return view("doctors/schedule-timings", [
            "timings" => $timings,
            "days" => $days,
            "profile" => $profile_obj
        ]);
    }

    public function remove_schedule_timings()
    {
        $validator = Validator::make(request()->all(), [
            "id" => "required"
        ]);

        if ($validator->fails())
        {
            return response()->json([
                "status" => "error",
                "message" => $validator->errors()->first()
            ]);
        }

        $user = auth()->user();
        $id = request()->id ?? 0;

        $timing = DB::table("timings")
            ->where("id", "=", $id)
            ->where("user_id", "=", $user->id)
            ->first();

        if ($timing == null)
        {
            return response()->json([
                "status" => "error",
                "message" => "Slot not found."
            ]);
        }

        DB::table("timings")
            ->where("id", "=", $timing->id)
            ->delete();

        return response()->json([
            "status" => "success",
            "message" => "Slot has been removed."
        ]);
    }

    public function checkout()
    {
        if (request()->isMethod("post"))
        {
            $validator = Validator::make(request()->all(), [
                "id" => "required",
                "date" => "required",
                "first_name" => "required"
            ]);

            if ($validator->fails())
            {
                return response()->json([
                    "status" => "error",
                    "message" => $validator->errors()->first()
                ]);
            }

            $user = auth()->user();
            $id = request()->id ?? 0;
            $date = request()->date ?? "";
            $first_name = request()->first_name ?? "";
            $last_name = request()->last_name ?? "";
            $email = request()->email ?? "";
            $phone = request()->phone ?? "";

            $timing = DB::table("timings")
                ->select("timings.*", "doctors_profile.fee")
                ->join("doctors_profile", "doctors_profile.user_id", "=", "timings.user_id")
                ->where("timings.id", "=", $id)
                ->first();

            if ($timing == null)
            {
                return response()->json([
                    "status" => "error",
                    "message" => "Timing not found."
                ]);
            }

            $doctor = DB::table("doctors_profile")
                ->where("user_id", "=", $timing->user_id)
                ->first();

            if ($doctor == null)
            {
                return response()->json([
                    "status" => "error",
                    "message" => "Doctor profile is not set up."
                ]);
            }

            $patient = DB::table("patients_profile")
                ->where("user_id", "=", $user->id)
                ->first();

            if ($patient == null)
            {
                return response()->json([
                    "status" => "error",
                    "message" => "Your profile is not set up."
                ]);
            }

            // Create DateTime object
            $datetime = \DateTime::createFromFormat("d M, Y", $date);

            // Get actual day name
            $actualDay = $datetime->format("l"); // Full day name

            // Compare
            if ($actualDay !== $timing->day)
            {
                return response()->json([
                    "status" => "error",
                    "message" => "Date and time mis-match."
                ]);
            }

            $next_number = DB::table("bookings")
                ->where("doctor_id", "=", $doctor->user_id)
                ->where("date", "=", $date)
                ->where("from", "=", $timing->from)
                ->orderBy("id", "desc")
                ->value("number") ?? 1;

            $booking_id = DB::table("bookings")
                ->insertGetId([
                    "patient_id" => $user->id,
                    "doctor_id" => $timing->user_id,
                    "day" => $timing->day,
                    "date" => $date,
                    "from" => $timing->from,
                    "to" => $timing->to,
                    "first_name" => $first_name,
                    "last_name" => $last_name,
                    "email" => $email,
                    "phone" => $phone,
                    "address" => $patient->address ?? "",
                    "state" => $patient->state ?? "",
                    "city" => $patient->city ?? "",
                    "country" => $patient->country ?? "",
                    "profile_image" => $patient->profile_image ?? "",
                    "fee" => $timing->fee,
                    "number" => $next_number,
                    "doctor" => json_encode([
                        "id" => $doctor->id ?? 0,
                        "name" => $doctor->name ?? "",
                        "email" => $doctor->email ?? "",
                        "phone" => $doctor->phone ?? "",
                        "profile_image" => $doctor->profile_image ?? "",
                        "address" => $doctor->address ?? "",
                        "city" => $doctor->city ?? "",
                        "state" => $doctor->state ?? "",
                        "country" => $doctor->country ?? ""
                    ]),
                    "created_at" => now()->utc(),
                    "updated_at" => now()->utc()
                ]);

            $invoice_id = DB::table("invoices")
                ->insertGetId([
                    "doctor_id" => $doctor->user_id,
                    "patient_id" => $user->id,
                    "amount" => $timing->fee,
                    "type" => "booking",
                    "booking_id" => $booking_id,
                    "created_at" => now()->utc(),
                    "updated_at" => now()->utc()
                ]);

            DB::table("bookings")
                ->where("id", "=", $booking_id)
                ->update([
                    "invoice_id" => $invoice_id
                ]);

            return response()->json([
                "status" => "success",
                "message" => "Booking has been made. Waiting for confirmation from doctor.",
                "id" => $booking_id
            ]);
        }

        return view("patients/checkout");
    }

    public function fetch_booking_details()
    {
        $validator = Validator::make(request()->all(), [
            "id" => "required"
        ]);

        if ($validator->fails())
        {
            return response()->json([
                "status" => "error",
                "message" => $validator->errors()->first()
            ]);
        }

        $user = auth()->user();
        $id = request()->id ?? 0;

        $timing = DB::table("timings")
            ->select("timings.*", "doctors_profile.name", "doctors_profile.gender", "doctors_profile.state",
                "doctors_profile.profile_image", "doctors_profile.country", "doctors_profile.fee")
            ->join("doctors_profile", "doctors_profile.user_id", "=", "timings.user_id")
            ->where("timings.id", "=", $id)
            ->first();

        if ($timing == null)
        {
            return response()->json([
                "status" => "error",
                "message" => "Timing not found."
            ]);
        }

        $timing = map_timing($timing);

        $user_obj = null;
        if ($user != null)
        {
            $user_obj = (object) [
                "id" => $user->id ?? 0,
                "name" => $user->name ?? "",
                "email" => $user->email ?? "",
                "phone" => $user->phone ?? "",
                "balance" => $user->balance ?? 0
            ];
        }

        $profile = get_my_profile();
        $profile->balance = $user->balance ?? 0;

        return response()->json([
            "status" => "success",
            "message" => "Data has been fetched.",
            "data" => (object) $timing,
            "user" => $profile
        ]);
    }

    public function complete_appointment()
    {
        $validator = Validator::make(request()->all(), [
            "id" => "required"
        ]);

        if ($validator->fails())
        {
            return response()->json([
                "status" => "error",
                "message" => $validator->errors()->first()
            ]);
        }

        $user = auth()->user();
        $id = request()->id ?? 0;

        $booking = Booking::fetch_by_id($id, 0, $user->id, "accepted");

        if ($booking == null)
        {
            return response()->json([
                "status" => "error",
                "message" => "Booking not found."
            ]);
        }

        DB::table("bookings")
            ->where("id", "=", $booking->id)
            ->update([
                "status" => "completed",
                "updated_at" => now()->utc()
            ]);

        return response()->json([
            "status" => "success",
            "message" => "Appointment has been completed."
        ]);
    }

    public function cancel_appointment()
    {
        $validator = Validator::make(request()->all(), [
            "id" => "required"
        ]);

        if ($validator->fails())
        {
            return response()->json([
                "status" => "error",
                "message" => $validator->errors()->first()
            ]);
        }

        $user = auth()->user();
        $id = request()->id ?? 0;

        $booking = Booking::fetch_by_id($id, 0, $user->id, "accepted");

        if ($booking == null)
        {
            return response()->json([
                "status" => "error",
                "message" => "Booking not found."
            ]);
        }

        DB::table("bookings")
            ->where("id", "=", $booking->id)
            ->update([
                "status" => "cancelled",
                "updated_at" => now()->utc()
            ]);

        if (config("app.env") == "production")
        {
            Mail::to($booking->email)->send(new BookingCancelled($booking));
        }

        return response()->json([
            "status" => "success",
            "message" => "Booking has been cancelled."
        ]);
    }

    public function accept_appointment()
    {
        $validator = Validator::make(request()->all(), [
            "id" => "required"
        ]);

        if ($validator->fails())
        {
            return response()->json([
                "status" => "error",
                "message" => $validator->errors()->first()
            ]);
        }

        $user = auth()->user();
        $id = request()->id ?? 0;

        $booking = Booking::fetch_by_id($id, 0, $user->id, "created");

        if ($booking == null)
        {
            return response()->json([
                "status" => "error",
                "message" => "Booking not found."
            ]);
        }

        DB::table("bookings")
            ->where("id", "=", $booking->id)
            ->update([
                "status" => "accepted",
                "updated_at" => now()->utc()
            ]);

        $doctor_patient = DB::table("doctor_patients")
            ->where("doctor_id", "=", $user->id)
            ->where("patient_id", "=", $booking->patient_id)
            ->first();

        if ($doctor_patient == null)
        {
            DB::table("doctor_patients")
                ->insertGetId([
                    "doctor_id" => $user->id,
                    "patient_id" => $booking->patient_id,
                    "booking_id" => $booking->id,
                    "created_at" => now()->utc(),
                    "updated_at" => now()->utc()
                ]);
        }
        else
        {
            DB::table("doctor_patients")
                ->where("id", "=", $doctor_patient->id)
                ->update([
                    "booking_id" => $booking->id,
                    "updated_at" => now()->utc()
                ]);
        }

        if (config("app.env") == "production")
        {
            Mail::to($booking->email)->send(new BookingConfirm($booking, "patient"));

            if ($booking->doctor != null)
            {
                Mail::to($booking->doctor->email)->send(new BookingConfirm($booking, "doctor"));
            }
        }

        return response()->json([
            "status" => "success",
            "message" => "Appointment has been confirmed."
        ]);
    }

    public function appointments()
    {
        $user = auth()->user();
        $status = request()->status ?? "";
        $timezone = session(config("config.session_timezone_key")) ?? "";

        if (!empty($timezone))
        {
            date_default_timezone_set($timezone);
        }

        $bookings_arr = [];
        $bookings_pagination = "";

        if ($user->type == "patient")
        {
            $bookings = Booking::fetch($user->id);

            foreach ($bookings as $booking)
            {
                array_push($bookings_arr, Booking::map($booking));
            }

            $bookings_pagination = $bookings->withPath(url("/appointments"))->links("pagination::bootstrap-5")->render();
        }
        else if ($user->type == "doctor")
        {
            $bookings = DB::table("bookings")
                ->select("bookings.*", "doctors_profile.id AS doctor_profile_id")
                ->where(function ($query) use ($user) {
                    $query->where("bookings.patient_id", "=", $user->id)
                        ->orWhere("bookings.doctor_id", "=", $user->id);
                });

            if (!empty($status))
            {
                $bookings = $bookings->where("bookings.status", "=", $status);
            }

            $bookings = $bookings->join("doctors_profile", "doctors_profile.user_id", "=", "bookings.doctor_id")
                ->orderBy("bookings.id", "desc")
                ->paginate();

            foreach ($bookings as $booking)
            {
                array_push($bookings_arr, Booking::map($booking));
            }

            $bookings_pagination = $bookings->withPath(url("/appointments"))->links("pagination::bootstrap-5")->render();
        }

        $profile_obj = get_my_profile();

        return view("appointments", [
            "bookings" => $bookings_arr,
            "bookings_pagination" => $bookings_pagination,
            
            "profile" => $profile_obj,
            // "total" => $bookings->total(),
            // "pages" => $bookings->lastPage()
        ]);
    }

    public function booking_success()
    {
        $user = auth()->user();
        $id = request()->id ?? 0;

        $booking = Booking::fetch_by_id($id, $user->id);

        if ($booking == null)
        {
            abort(404);
        }

        $invoice_id = 0;
        $invoice = DB::table("invoices")
            ->where("booking_id", "=", $booking->id)
            ->first();

        if ($invoice != null)
        {
            $invoice_id = $invoice->id ?? 0;
        }

        return view("patients/booking-success", [
            "booking" => $booking,
            "invoice_id" => $invoice_id
        ]);
    }
}
