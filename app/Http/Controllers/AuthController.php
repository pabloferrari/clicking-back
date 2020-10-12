<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Models\User;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{

    public function login(LoginRequest $request)
    {
        
        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
            // $token->expires_at = Carbon::now()->addYear(1);
        }
        $token->save();
        
        $user = User::with(['roles'])->find($user->id);
        return response()->json([
            'data' => [
                'access_token' => $tokenResult->accessToken,
            'token_type'   => 'Bearer',
            'expires_at'   => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
            'user'         => $user
            ]
        ]);
    }
    
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Successfully logged out']);
    }
    
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function test(Request $request)
    {
        return response()->json($request->user());
    }

    public function unauthorized(Request $request)
    {
        return response()->json(["message" => "Unauthenticated"], 401);
    }
}
