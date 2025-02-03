<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;

class VoiceService
{

    private const API_URL = 'https://api.elevenlabs.io/v1/';
    public function getlist()
    {
        $url = self::API_URL.'voices';
        $data = $this->submitRequest($url);

        return $data;
    }

    public function uploadVoice($name,$fullPath)
    {
        $appApiKey = supersetting('ele_api_key', null);

        $url = self::API_URL.'voices/add';
        $client = new \GuzzleHttp\Client();

        try {
            $response = $client->request('POST', $url, [
            'headers' => [
                'xi-api-key' => $appApiKey,
            ],
            'multipart' => [
                [
                    'name'     => 'name',
                    'contents' => $name // Voice name
                ],
                [
                    'name'     => 'files',
                    'contents' => fopen($fullPath, 'r'),
                    'filename' => $name.'mp3'
                ]
            ]
        ]);
        $data = $response->getBody();
        return  json_decode($data,true);

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            Log::error('An error occurred: ' . $e->getMessage());
            return null;  // Show the error response from the API
        }
    }

    public function textToSpeech($text,$voice_id,$dataa = [])
    {
        $setting = [
            'style' => @$dataa['style'] ?? 0,
            'use_speaker_boost' => @$dataa['use_speaker_boost'] ?? true,
        ];
        if(isset($dataa['stability']))
        {
            $setting['stability'] = $dataa['stability'];
        }
        if(isset($dataa['similarity_boost']))
        {
            $setting['similarity_boost'] = $dataa['similarity_boost'];
        }

        $appApiKey = supersetting('ele_api_key', null);

$url = self::API_URL.'text-to-speech/' . $voice_id . '?output_format=mp3_44100_64';

$client = new \GuzzleHttp\Client();

try {
    $response = $client->request('POST', $url, [
        'json' => [
            'text' => $text,
            'model_id' => 'eleven_multilingual_v2',
            'voice_settings' => $setting
        ],
        'headers' => [
            'Content-Type' => 'application/json',
            'xi-api-key' => $appApiKey,
        ],
    ]);
    $audioContent = $response->getBody()->getContents();

    $directory = public_path('uploads/voices');
if (!file_exists($directory)) {
    mkdir($directory, 0777, true); // Create directory if it doesn't exist
}

$fileName = 'audio_' . time() . '.mp3';
$filePath = $directory . '/' . $fileName;

// Save the file
file_put_contents($filePath, $audioContent);

// return 'https://ac8b-103-191-123-14.ngrok-free.app/uploads/voices/' . $fileName;
return asset('uploads/voices/' . $fileName);

// Return URL to access the file
// return response()->json(['audio_url' => asset('uploads/voices/' . $fileName)]);
} catch (\GuzzleHttp\Exception\ClientException $e) {
    Log::error('An error occurred: ' . $e->getMessage());
    return null;  // Show the error response from the API
}


    }

    public function submitRequest( $url,$method = 'GET')
    {
        $appApiKey = supersetting('ele_api_key', null);
        $data = [];
        try{
            $client = new \GuzzleHttp\Client();
            $response = $client->request('GET', $url, [
                'headers' => [
                    'xi-api-key' => $appApiKey,
                ],

            ]);
            $data = $response->getBody();
            $data = json_decode($data,true);
        }catch(\Exception $e)
        {

        }

        return $data;
    }

    public function yajraResponse($voices)
    {
        return DataTables::of($voices)
        ->addColumn('name', function ($row) {
            $name = @$row['name']?? '';
            $voice_id = @$row['voice_id']?? '';
            return  "<p>{$name}</p> (<ahref='javascript:;' data-href='{$voice_id}' class='custom_id copy_url'>{$voice_id}</a>)";
        })
        ->addColumn('category', function ($row) {
            return @$row['category'] ?? '';
        })
        ->addColumn('gender', function ($row) {
            return @$row['labels']['gender'] ?? 'N/A';
        })
        ->addColumn('age', function ($row) {
            return @$row['labels']['age'] ?? 'N/A';
        })
        ->addColumn('preview', function ($row) {
            $link = @$row['preview_url'] ?? '';
            return "<a href='{$link}' target='_blank'>Preview</a>";
        })
        ->rawColumns(['preview','name']) // Ensure HTML is rendered
        ->make(true);

    }
}
