<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Str;
use Storage;
use Validator;
use Mail;
use App\Models\User;

class UserController extends Controller
{
    public function social_media()
    {
        $user = auth()->user();

        $social_media = DB::table("social_media")
            ->where("user_id", "=", $user->id)
            ->first();

        if (request()->isMethod("post"))
        {
            $facebook = request()->facebook ?? "";
            $twitter = request()->twitter ?? "";
            $instagram = request()->instagram ?? "";
            $linkedin = request()->linkedin ?? "";
            $youtube = request()->youtube ?? "";

            if ($social_media == null)
            {
                DB::table("social_media")
                    ->insertGetId([
                        "user_id" => $user->id,
                        "facebook" => $facebook,
                        "twitter" => $twitter,
                        "instagram" => $instagram,
                        "linkedin" => $linkedin,
                        "youtube" => $youtube,
                        "created_at" => now()->utc(),
                        "updated_at" => now()->utc()
                    ]);
            }
            else
            {
                DB::table("social_media")
                    ->where("id", "=", $social_media->id)
                    ->update([
                        "facebook" => $facebook,
                        "twitter" => $twitter,
                        "instagram" => $instagram,
                        "linkedin" => $linkedin,
                        "youtube" => $youtube,
                        "updated_at" => now()->utc()
                    ]);
            }

            return response()->json([
                "status" => "success",
                "message" => "Social media has been updated."
            ]);
        }

        $profile = get_my_profile();

        return view("social-media", [
            "profile" => $profile,
            "social_media" => $social_media
        ]);
    }

    public function change_password()
    {
        if (request()->isMethod("post"))
        {
            $validator = Validator::make(request()->all(), [
                "password" => "required",
                "new_password" => "required",
                "confirm_password" => "required"
            ]);

            if ($validator->fails())
            {
                return response()->json([
                    "status" => "error",
                    "message" => $validator->errors()->first()
                ]);
            }

            $user = auth()->user();
            $password = request()->password ?? "";
            $new_password = request()->new_password ?? "";
            $confirm_password = request()->confirm_password ?? "";

            if (!password_verify($password, $user->password))
            {
                return response()->json([
                    "status" => "error",
                    "message" => "In-correct password."
                ]);
            }

            if ($new_password != $confirm_password)
            {
                return response()->json([
                    "status" => "error",
                    "message" => "Password mis-match."
                ]);
            }

            DB::table("users")
                ->where("id", "=", $user->id)
                ->update([
                    "password" => password_hash($new_password, PASSWORD_DEFAULT),
                    "updated_at" => now()->utc()
                ]);

            return response()->json([
                "status" => "success",
                "message" => "Password has been changed."
            ]);
        }

        $profile = get_my_profile();

        return view("change-password", [
            "profile" => $profile
        ]);
    }

    public function toggle_favourite()
    {
        return response()->json([
            "status" => "info",
            "message" => get_premium_message()
        ]);
    }

    public function set_timezone()
    {
        $timezone = request()->timezone ?? "";
        session([
            config("config.session_timezone_key") => $timezone
        ]);
    }

    public function login()
    {
        if (request()->isMethod("post"))
        {
            $validator = Validator::make(request()->all(), [
                "email" => "required",
                "password" => "required"
            ]);
    
            if ($validator->fails())
            {
                return response()->json([
                    "status" => "error",
                    "message" => $validator->errors()->first()
                ]);
            }
    
            $email = request()->email ?? "";
            $password = request()->password ?? "";
    
            $user = User::where("email", "=", $email)
                ->whereNull("deleted_at")
                ->first();
    
            if ($user == null)
            {
                return response()->json([
                    "status" => "error",
                    "message" => "Email does not exist."
                ]);
            }
    
            if (!password_verify($password, $user->password))
            {
                return response()->json([
                    "status" => "error",
                    "message" => "In-correct password."
                ]);
            }
    
            /*if (is_null($user->email_verified_at))
            {
                return response()->json([
                    "status" => "error",
                    "message" => "Email not verified."
                ]);
            }*/

            if (request()->is("api/*"))
            {
                $token = $user->createToken($this->token_secret)->plainTextToken;

                return response()->json([
                    "status" => "success",
                    "message" => "Login successfully.",
                    "access_token" => $token
                ]);
            }
            else
            {
                if (auth()->attempt([
                    "email" => $email,
                    "password" => $password
                ], true))
                {
                    return response()->json([
                        "status" => "success",
                        "message" => "Login successfully."
                    ]);
                }
            }

            return response()->json([
                "status" => "error",
                "message" => "Invalid credentials."
            ]);
        }

        return view("login");
    }

    public function logout()
    {
        if (request()->is("api/*"))
        {
            $user = auth()->user();

            // $user->tokens()->delete();

            $user->currentAccessToken()->delete();

            // $user->tokens()->where("id", $token_id)->delete();

            return response()->json([
                "status" => "success",
                "message" => "Logout successfully."
            ]);
        }

        auth()->logout();
        return redirect("/");
    }

    public function register()
    {
        if (request()->isMethod("post"))
        {
            $validator = Validator::make(request()->all(), [
                "name" => "required",
                "email" => "required",
                "password" => "required",
                "type" => "required"
            ]);
    
            if ($validator->fails())
            {
                return response()->json([
                    "status" => "error",
                    "message" => $validator->errors()->first()
                ]);
            }
    
            $name = request()->name ?? "";
            $email = request()->email ?? "";
            $password = request()->password ?? "";
            $type = request()->type ?? "";

            if (!in_array($type, ["doctor", "patient"]))
            {
                return response()->json([
                    "status" => "error",
                    "message" => "Invalid type '" . $type . "'."
                ]);
            }
    
            $user = DB::table("users")
                ->where("email", "=", $email)
                ->first();
    
            if ($user != null)
            {
                return response()->json([
                    "status" => "error",
                    "message" => "Email already exists."
                ]);
            }

            $setting_verify_email = DB::table("settings")
                ->where("key", "=", "verify_email")
                ->where("value", "=", "yes")
                ->first();

            if ($setting_verify_email == null)
            {
                $user_arr["email_verified_at"] = now()->utc();
            }
            else
            {
                $verification_code = Str::random(6);
                $user_arr["verification_code"] = $verification_code;

                $message = '<p>Your verification code is: <b style="font-size: 30px;">' . $verification_code . '</b></p>';
                $this->send_mail($email, $name, "Email verification", $message);
            }
    
            $user_arr = [
                "name" => $name,
                "email" => $email,
                "password" => password_hash($password, PASSWORD_DEFAULT),
                "type" => $type,
                "created_at" => now()->utc(),
                "updated_at" => now()->utc()
            ];

            DB::table("users")
                ->insertGetId($user_arr);

            if ($setting_verify_email == null)
            {
                return response()->json([
                    "status" => "success",
                    "message" => "Account has been created. Please login now.",
                    "verification" => false
                ]);
            }
            else
            {
                return response()->json([
                    "status" => "success",
                    "message" => "Please check your email, a verification code has been sent to you.",
                    "verification" => true
                ]);
            }
        }

        return view("register");
    }

    public function home()
    {
        $specialities = DB::table("specialities")
            ->orderBy("name", "asc")
            ->get();

        $doctors_profile = DB::table("doctors_profile")
            ->inRandomOrder()
            ->paginate();

        $doctors_arr = [];
        foreach ($doctors_profile as $doctor)
        {
            array_push($doctors_arr, map_doctor($doctor));
        }

        return view("home", [
            "specialities" => $specialities,
            "doctors" => $doctors_arr
        ]);
    }
}
