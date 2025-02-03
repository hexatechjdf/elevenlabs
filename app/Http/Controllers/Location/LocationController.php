<?php

namespace App\Http\Controllers\Location;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UploadVoiceRequest;
use App\Services\VoiceService;
use App\Models\LocationVoice;

class LocationController extends Controller
{
    public function index()
    {
        return view('location.setting.index',get_defined_vars());
    }

    public function voices()
    {
        $savedVoiceId = $user->custom_voice_id ?? supersetting('custom_voice_id');

        return view('voices.index',get_defined_vars());
    }

    public function ownvoices()
    {
        $savedVoiceId = $user->custom_voice_id ?? supersetting('custom_voice_id');
        $voice_type = 'own';

        return view('voices.index',get_defined_vars());
    }

    public function ownvoicesSubmit(UploadVoiceRequest $request,VoiceService $voiceService)
    {
        $user = loginUser();
        $file = $request->file('file');
        $filePath = $file->store('public/mp3');
        $fileName = $file->getClientOriginalName();
        $fullPath = storage_path('app/' . $filePath);

        $data = $voiceService->uploadVoice($request->name,$fullPath);
        if(isset($data['voice_id']))
        {
            LocationVoice::create([
                'voice_id' => $data['voice_id'],
                'location_id' => $user->location_id,
            ]);
        }

        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
        return response()->json(['success' => 'Successfully Updaetd']);
    }
}
