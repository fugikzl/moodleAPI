<?php

namespace App\Models;

use App\Services\Moodle\MoodleFunctions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoodleTokenInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "ws_token"
    ]; 

    protected $hidden = [
        "ws_token"
    ];

    public static function isUserStored(string $ws_token) : bool
    {
        return self::where("ws_token",$ws_token)->count() > 0 ? true : false;
    }

    public static function getOrStoreUser(string $ws_token)
    {
        if(self::isUserStored($ws_token)){
            return MoodleTokenInfo::where("ws_token",$ws_token)->first->user_id;
        }else{
            $tokenInfo = MoodleTokenInfo::create([
                "ws_token" => $ws_token,
                "user_id" => MoodleFunctions::getUserInfo($ws_token)["user_id"]
            ]);

            return $tokenInfo->user_id;
        }
    }
}
