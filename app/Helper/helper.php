<?php

use App\Models\Setting;
use App\Helper\gCache;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;

function supersetting($key, $default = '', $keys_contain = null)
{
    Cache::forget($key);
    try {
        $setting = gCache::get($key, function () use ($default, $key, $keys_contain) {
            $setting = Setting::when($keys_contain, function ($q) use ($key, $keys_contain) {
                return $q->where('key', 'LIKE', $keys_contain)->pluck('value', 'key');
            }, function ($q) use ($key) {
                return $q->where(['key' => $key])->first();
            });

            $value = $keys_contain ? $setting : ($setting->value ?? $default);
            gCache::put($key, $value);
            return $value;
        });
        return $setting;
    } catch (\Exception $e) {
        return null;
    }

}

function braceParser($value)
{
    return str_replace(['[', ']'], ['{', '}'], $value);
}

function loginUser($user = null)
{
    if (auth()->check()) {
        $user = auth()->user();

    } else {
        if (!$user) {
            $user = User::find($user);
        }

    }
    return $user;
}

function save_settings($key, $value = '')
{
    $value = is_array($value) ? json_encode($value) : $value;
    $setting = Setting::updateOrCreate(
        ['key' => $key],
        [
            'value' => $value,
            'key' => $key,
        ]
    );
    gCache::del($key);
    gCache::put($key, $value);
    return $setting;
}

function isLocal()
{
    return strpos($_SERVER['DOCUMENT_ROOT'], 'htdocs') !== false || strpos($_SERVER['SERVER_NAME'], 'localhost') !== false;
}

function humanNumber($value)
{
    $format = $value;
    try {
        $format = number_format($value);
    } catch (\Throwable $th) {
        //throw $th;
    }
    return $format;
}



function setKeyValueJson($key, $value, $jsonData)
{
    $jsonData->$key = $value;
    return $jsonData;
}


function customDate($date, $format, $type = null)
{
    try {
        if($type=='time')
        {
         return Carbon::parse($date)->toIso8601String();
        }
        return Carbon::parse($date)->format($format);
    } catch (\Throwable $th) {
        //throw $th;
    }
    return $date;
}


