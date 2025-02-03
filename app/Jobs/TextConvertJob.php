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

class TextConvertJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $voice_id;
    public $chunks;
    public $contactId;
    public $locationId;
    public $audioLinks;
    public $currentIndex;
    public $data;
    /**
     * Create a new job instance.
     */
    public function __construct($voice_id, $chunks,$contactId,$locationId,$data=[],$audioLinks = [], $currentIndex = 0)
    {
        $this->voice_id = $voice_id;
        $this->chunks = $chunks;
        $this->contactId = $contactId;
        $this->locationId = $locationId;
        $this->audioLinks = $audioLinks;
        $this->currentIndex = $currentIndex;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(VoiceService $voiceService)
    {
        try{
            if (isset($this->chunks[$this->currentIndex])) {
                $message = $this->chunks[$this->currentIndex];
                $link = $voiceService->textToSpeech($message, $this->voice_id,$this->data);

                if ($link) {
                    $this->audioLinks[] = $link;
                }
            }

            // Check if more chunks are left
            if ($this->currentIndex + 1 < count($this->chunks)) {
                dispatch(new TextConvertJob(
                    $this->voice_id,
                    $this->chunks,
                    $this->contactId,
                    $this->locationId,
                    $this->data,
                    $this->audioLinks,
                    $this->currentIndex + 1
                ))->delay(1);
            } else {
                dispatch(new VoiceUploadJob($this->audioLinks, $this->contactId, $this->locationId))->delay(3);
            }
        }catch(\Exception $e)
        {
            // dd($e);
            Log::error('An error occurred: sdsd ' . $e->getMessage());
            return false;
        }
    }
}
