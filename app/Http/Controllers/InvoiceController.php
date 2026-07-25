<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Str;
use Storage;
use Validator;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    private function map($c)
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
                "from" => $c->from ?? "",
                "to" => $c->to ?? "",
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
                "profile_image" => $c->doctor_profile_image ?? ""
            ];

            if ($obj["doctor"]->profile_image && Storage::exists("public/" . $obj["doctor"]->profile_image))
            {
                $obj["doctor"]->profile_image = url("/storage/" . $obj["doctor"]->profile_image);
            }
        }

        return (object) $obj;
    }

    public function my()
    {
        if (request()->isMethod("post"))
        {
            $user = auth()->user();
            $timezone = request()->timezone ?? "";

            if (!empty($timezone))
            {
                date_default_timezone_set($timezone);
            }

            $data = Invoice::fetch_by_user($user->id);
            $invoices = $data["invoices"];
            $total = $data["total"];

            return response()->json([
                "status" => "success",
                "message" => "Data has been fetched.",
                "invoices" => $invoices,
                "total" => $total
            ]);
        }

        return view("invoices/index");
    }

    public function detail()
    {
        $user = auth()->user();
        $id = request()->id ?? 0;
        $timezone = request()->timezone ?? "";

        if (!empty($timezone))
        {
            date_default_timezone_set($timezone);
        }

        $invoice = Invoice::fetch_single($id, $user->id);

        if ($invoice == null)
        {
            abort(404);
        }

        return view("invoices/detail", [
            "invoice" => $invoice
        ]);
    }
}
