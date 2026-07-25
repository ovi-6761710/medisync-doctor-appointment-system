<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Storage;
use Validator;
use App\Models\User;

class MessagesController extends Controller
{
    public function fetch_users()
    {
        return response()->json([
            "status" => "info",
            "message" => get_premium_message()
        ]);
    }

    public function index()
    {
        $id = request()->id ?? 0;
        return view("chats", [
            "id" => $id
        ]);
    }
}
