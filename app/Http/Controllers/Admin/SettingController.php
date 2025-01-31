<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Helper\CRM;
use App\Models\Setting;


class SettingController extends Controller
{
    public function index(Request $request)
    {
        $settings = Setting::pluck('value','key')->toArray();
        $connecturl = CRM::directConnect();
        $scopes = CRM::$scopes;
        $authuser = auth::user();
        $company_name = null;
        $company_id = null;
        if(@$authuser->crmauth->company_id)
        {
           list($company_name,$company_id) =  CRM::getCompany($authuser);
        }

        return view('admin.setting.index',get_defined_vars());
    }

    public function save(Request $request)
    {
        $user = loginUser();
        foreach($request->setting ?? [] as $key=>$value){

            save_settings($key,$value);
        }

        return response()->json(['success' => true, 'message' => 'Data saved successfully']);
    }

    public function getVoices(Request $request)
    {
        // $list = $voiceService->getlist();
        // dd($list);
        $savedVoiceId = supersetting('custom_voice_id');
        return view('admin.voices.index',get_defined_vars());
    }

    public function userProfile(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email',
            'user_id' => 'required|integer',
            'password' => 'nullable|min:6',
        ]);

        try {
            $userId = $request->user_id;
            $username = $request->username;
            $email = $request->email;
            $password = $request->password;
            $user = User::findOrFail($userId);
            $userExist = User::where('email', $email)
                ->where('id', '<>', $userId)
                ->first();

            if ($userExist) {
                if (!empty($password)) {
                    $user->password = bcrypt($password);
                    $user->save();
                    return response()->json(['status' => 'Success', 'message' => 'Password updated successfully']);
                } else {
                    return response()->json(['status' => 'Error', 'message' => 'Password is required'], 400);
                }
            } else {
                $user->email = $email;
                if (!empty($password)) {
                    $user->password = bcrypt($password);
                }
                $user->name = $username;
                $user->save();
                return response()->json(['status' => 'Success', 'message' => 'User profile updated successfully']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'Error', 'message' => $e->getMessage()], 500);
        }
    }
}
