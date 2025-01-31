<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Jobs\TextConvertJob;
use Illuminate\Support\Facades\Log;


use Illuminate\Support\Str;

class WebhookController extends Controller
{
    public function convertTextToSpeech(Request $request)
    {
        $data = $request->all();
        $message = @$data['customData']['message'] ?? null;
        // return count($chunks);
        if($message)
        {
            $locationId = @$data['location']['id'] ?? null;
            $contactId = @$data['contact_id'] ?? null;
            $voice_id = @$data['customData']['voice_id'] ?? $this->setVoice($locationId);
            if($voice_id)
            {
                $chunks = str_split($message, 999);
                dispatch((new TextConvertJob($voice_id, $chunks,$contactId,$locationId)))->delay(5);
            }
            else
            {
                return 'Voice id is required. Send voice id as a parameter or set voice id in setting.';
            }

        }
        else
        {
            return 'Message is required.';

        }

        return 1;
    }

    public function setVoice($locationId)
    {
      $user = User::where('location_id',$locationId)->first();

      return $user ? $user->custom_voice_id : ( supersetting('custom_voice_id') ??  null);

    }
}
