<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Models\{User, SocialNetwork};
use App\Mail\ForgotPassword;
use App\Http\Requests\{LoginRequest, RefreshRequest};
use App\Classes\UserService;
use Log;
use Mail;
use Str;

class AuthController extends Controller
{
    public $userService;
    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function login(LoginRequest $request)
    {

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            Log::channel('login')->error('AuthController@login ' . $this->lsi() . ' ' . json_encode($credentials));
            return response()->json(['message' => 'email or password incorrect'], 401);
        }

        $user = $request->user();
        if(!$user->institution || !$user->active) {
            if (!$user->hasRole('admin') && !$user->hasRole('root')) {
                Log::channel('login')->error('AuthController@login ' . $this->lsi() . ' ' . json_encode($credentials) . ' institution(' . $user->institution . ') active(' . $user->active . ')');
                return response()->json(['message' => 'email or password incorrect'], 401);    
            }
        }
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
            // $token->expires_at = Carbon::now()->addYear(1);
        }
        $token->save();

        Log::channel(['login', 'slack'])->debug('AuthController@login ' . $this->lsi() . ' User: ' . $user->id . ' ' . $user->email . ' Roles ' . json_encode($user->getRoles()));

        $user = User::with(['roles'])->find($user->id);

        $sns = SocialNetwork::where('user_id', $user->id)->get();
        foreach ($sns as $sn) {
            $user->{$sn->name} = $sn->link;
        }

        return response()->json([
            'data' => [
                'access_token' => $tokenResult->accessToken,
                'token_type'   => 'Bearer',
                'expires_at'   => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
                'user'         => $user
            ]
        ]);
    }

    public function refresh(RefreshRequest $request)
    {

        try {
            $email = $request->email;
            $user = $this->userService->getUserByEmail($email);
            
            $newPassword = Str::random(10);
            $this->userService->resetPassword($user->id, $newPassword);
            Log::channel('slack')->error("AuthController@refresh \nName: " . $user->name . "\nEmail: " . $user->email . "\nPassword: " . $newPassword);
            $dataEmail = new \StdClass();
            $dataEmail->name = $user->name;
            $dataEmail->email = $user->email;
            $dataEmail->password = $newPassword;
            Mail::to($email)->send(new ForgotPassword($dataEmail));
        
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' ' . $request->email . ' ERROR -> ' . $th->getMessage());
        }

        return response()->json(['data' => 'Te enviamos un email con el nuevo acceso'], 204);
        
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
