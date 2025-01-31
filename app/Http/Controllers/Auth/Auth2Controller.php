<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helper\CRM;

class Auth2Controller extends Controller
{
    public function crmCallback(Request $request)
    {
        $code = $request->code ?? null;
        if ($code) {
            $user_id = auth()->user()->id;
            $code = CRM::crm_token($code, '');
            $code = json_decode($code);
            $user_type = $code->userType ?? null;
            $main = route('dashboard');
            if ($user_type) {
                $token = $user->owntoken ?? null;
                list($connected, $con) = CRM::go_and_get_token($code, '', $user_id, $token);
                if ($connected) {
                    return redirect($main)->with('success', 'Connected Successfully');
                }
                return redirect($main)->with('error', json_encode($code));

            }
            return response()->json(['message' => 'Not allowed to connect']);
        }
    }
}
