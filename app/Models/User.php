<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use DB;
use Storage;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function map($user)
    {
        $obj = [
            "id" => $user->id ?? 0,
            "user_id" => $user->user_id ?? 0,
            "name" => $user->name ?? "",
            "email" => $user->email ?? "",
            "profile_image" => $user->profile_image ?? "",
            "type" => $user->type ?? "",
            "created_at" => date("d M, Y", strtotime($user->created_at . " UTC"))
        ];

        if ($obj["profile_image"] && Storage::exists("public/" . $obj["profile_image"]))
        {
            $obj["profile_image"] = url("/storage/" . $obj["profile_image"]);
        }

        return (object) $obj;
    }
}
