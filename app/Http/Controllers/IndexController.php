<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\VoiceService;

class IndexController extends Controller
{
    public function getVoices(Request $request,VoiceService $voiceService)
    {
        $list = $voiceService->getlist();

        return $voiceService->yajraResponse(@$list['voices'] ?? []);
    }

    public function saveSelectedVoice(Request $request)
    {
        $user = loginUser();
        if($user->role == 1)
        {
            save_settings('custom_voice_id',$request->voice_id);
        }
        else{
            $user->custom_voice_id = $request->voice_id;
            $user->save();
        }

        return response()->json(['success' => true, 'message' => 'Data saved successfully']);
    }
}
