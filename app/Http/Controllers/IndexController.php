<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\VoiceService;
use App\Models\LocationVoice;

class IndexController extends Controller
{
    public function getVoices(Request $request,VoiceService $voiceService)
    {
        $list = $voiceService->getlist();
        // dd($list['voices']);
        $voices = [];
        $user = loginUser();
        $custom = LocationVoice::when($user->role != 1,function($q)use($user){
            $q->where('location_id',$user->location_id);
        })->pluck('voice_id')->toArray();

        if($request->voice_type == 'own')
        {
            $filteredArray = array_values(array_filter($list['voices'], function ($item) use ($custom) {
                return in_array($item['voice_id'], $custom);
            }));

        }else{
            $filteredArray = array_values(array_filter($list['voices'], function ($item) use ($custom) {
                return !in_array($item['voice_id'], $custom);
            }));
        }

        return $voiceService->yajraResponse(@$filteredArray ?? []);
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
