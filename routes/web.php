<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DoccureController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\MessagesController;

// use App\Http\Middleware\User;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get("/preview-email", [UserController::class, "preview_email"]);

Route::post("/set-timezone", [UserController::class, "set_timezone"]);
Route::get("/checkout", [DoccureController::class, "checkout"]);
Route::post("/fetch-booking-details", [DoccureController::class, "fetch_booking_details"]);
Route::get("/doctors/{id}/booking", [DoccureController::class, "booking"]);
Route::get("/doctors/{id}/profile", [DoccureController::class, "doctor_profile"]);

Route::get("/search", [DoccureController::class, "search"]);
Route::get("/", [UserController::class, "home"]);
Route::any("/login", [UserController::class, "login"])
    ->name("login");
Route::any("/register", [UserController::class, "register"]);

Route::group([
    "middleware" => ["auth"]
], function () {
    // Route::get("/messages/attachments/{id}/download", [MessagesController::class, "download_attachment"]);
    Route::get("/messages/buffer-attachment/{id}", [MessagesController::class, "buffer_attachment"]);
    Route::post("/messages/fetch-users", [MessagesController::class, "fetch_users"]);
    Route::post("/messages/fetch", [MessagesController::class, "fetch"]);
    Route::post("/messages/send", [MessagesController::class, "send"]);
    Route::get("/chats/{id?}", [MessagesController::class, "index"]);
    
    Route::any("/social-media", [UserController::class, "social_media"]);
    Route::any("/change-password", [UserController::class, "change_password"]);
    Route::get("/favourites", [UserController::class, "fetch_favourites"]);
    Route::post("/favourites/toggle", [UserController::class, "toggle_favourite"]);

    Route::post("/reviews/reply", [PatientController::class, "reply_review"]);
    Route::post("/reviews/write", [PatientController::class, "write_review"]);

    Route::post("/medical-records/remove", [PatientController::class, "remove_medical_record"]);
    Route::get("/medical-records/{id}/download", [PatientController::class, "download_medical_record"]);
    Route::post("/medical-records/add", [PatientController::class, "add_medical_record"]);
    Route::post("/prescriptions/remove", [PatientController::class, "remove_prescription"]);
    Route::get("/prescriptions/{id}", [PatientController::class, "single_prescription"]);
    Route::any("/patients/{id}/prescriptions/add", [PatientController::class, "add_prescription"]);
    Route::get("/patients/{id}/profile", [PatientController::class, "profile"]);
    Route::get("/my-patients", [PatientController::class, "my"]);
    Route::get("/invoices/{id}/detail", [InvoiceController::class, "detail"]);
    Route::post("/invoices/detail", [InvoiceController::class, "detail"]);
    Route::get("/invoices", [InvoiceController::class, "my"]);

    Route::get("/booking-success/{id}", [DoccureController::class, "booking_success"]);
    Route::post("/checkout", [DoccureController::class, "checkout"]);

    Route::get("/dashboard", [DoccureController::class, "dashboard"]);
    Route::get("/profile-settings", [DoccureController::class, "profile_settings"]);
    Route::post("/update-profile", [DoccureController::class, "update_profile"]);
    Route::post("/remove-clinic-image", [DoccureController::class, "remove_clinic_image"]);
    Route::any("/schedule-timings", [DoccureController::class, "schedule_timings"]);
    Route::any("/remove-schedule-timings", [DoccureController::class, "remove_schedule_timings"]);

    Route::post("/appointments/accept", [DoccureController::class, "accept_appointment"]);
    Route::post("/appointments/cancel", [DoccureController::class, "cancel_appointment"]);
    Route::post("/appointments/complete", [DoccureController::class, "complete_appointment"]);
    Route::any("/appointments", [DoccureController::class, "appointments"]);

    Route::get("/logout", [UserController::class, "logout"]);
});
