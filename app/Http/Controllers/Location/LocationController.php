<?php

namespace App\Http\Controllers\Location;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        return view('location.setting.index',get_defined_vars());
    }

    public function voices()
    {
        $user = loginUser();
        $savedVoiceId = $user->custom_voice_id ?? supersetting('custom_voice_id');

        return view('location.voices.index',get_defined_vars());
    }
}
