<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;
use App\Models\Social;
class SocialController extends Controller
{
    public function getInfo($social)
    {
        return Socialite::driver($social)->redirect();
    }
    public function checkInfo($social)
    {
        $info= Socialite::driver($social)->user();
        dd($info);
    }
    public function callback_facebook()
    {
        $provider = Socialite::driver('facebook')->user();
        return "Có cái nịt";
    }
}
