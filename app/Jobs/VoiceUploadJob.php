<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\VoiceService;
use App\Helper\CRM;
use Illuminate\Support\Facades\Log;

class VoiceUploadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $audioLinks;
    public $contactId;
    public $locationId;
    /**
     * Create a new job instance.
     */
    public function __construct($audioLinks,$contactId,$locationId,$retry = 1)
    {
        $this->audioLinks = $audioLinks;
        $this->contactId = $contactId;
        $this->locationId = $locationId;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try{
                $body = [
                    "type" =>  "SMS",
                    "contactId" =>  $this->contactId,
                    'attachments' => $this->audioLinks,
                ];
                // $com_id = config('services.administrative.company_id');
                $res = CRM::crmV2Loc(1, $this->locationId, 'conversations/messages', 'post', $body);

                // if message id dont exist
                if($this->retry <=3 && ($res && !@$res->messageId))
                {
                    dispatch(new VoiceUploadJob($this->audioLinks, $this->contactId, $this->locationId,$this->retry + 1))->delay(3);
                }

                foreach($this->audioLinks as $filePath)
                {
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }

                Log::log($res);
        }catch(\Exception $e)
        {
            Log::error('An error occurred upload: ' . $e->getMessage());
            return false;
        }
    }


}
