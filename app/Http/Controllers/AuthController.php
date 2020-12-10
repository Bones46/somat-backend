<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $this->loginValidator($request->all())->validate();

        $credentials = $request->only(['username', 'password']);
        $remember = $request->get('remember', false);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $tokenResult = $user->createToken('secret');
            $token = $tokenResult->token;

            if (!$remember) {
                $token->expires_at = Carbon::now()->addHours(1);
                $token->save();
            }
            $resp['profile'] = $user;
            $resp['access_type'] = 'Bearer';
            $resp['access_token'] = $tokenResult->accessToken;
            $resp['expires_at'] = Carbon::parse($tokenResult->token->expires_at)->toDateTimeString();

            return response()->json(['status'=>'success','data'=>$resp], 200);
        } else {
            return response()->json(['errors'=>['password'=>['Invalid password.']]], 422);
        }
    }

    /**
     * Get a validator for an incoming login request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function loginValidator(array $data)
    {
        return Validator::make($data, [
            'username'    => 'required|exists:'.config('schoolconnect.database.user_table').',username',
            'password'    => 'required',
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json(['message'=>'Successfully logged out.'],200);
    }
}
