<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationFormRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class APIController extends Controller
{
    /**
     * @var bool
     */
    public $loginAfterSignUp = true;

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        try {
            JWTAuth::invalidate($request->token);

            $cookie = Cookie::forget('user-token');
            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully'
            ])->withCookie($cookie);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out'
            ], 500);
        }
    }

    /**
     * @param RegistrationFormRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegistrationFormRequest $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        if ($this->loginAfterSignUp) {
            return $this->login($request);
        }

        return response()->json([
            'success' => true,
            'data' => $user
        ], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $input = $request->only('email', 'password');
        $token = null;
        $cookie = Cookie::forget('user-token');
        if (!$token = JWTAuth::attempt($input)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], 401)->withCookie($cookie);
        }

        $cookie = cookie('user-token', $token, 60);
        return response()->json([
            'success' => true,
            'token' => $token,
        ]);
    }

    public function getStatus(Request $request)
    {

        //dd();

        $token = $request->header('user-token');
        if (!$token) {
            $token = Cookie::get('user-token');
        }
        if ($token) {
            $user = JWTAuth::setToken($token)->toUser();
            if ($user == null) {
                abort(401);
            } else {
                return response()->json([
                    "token" => $token,
                ]);
            }

        } else {
            abort(401);
        }

    }
}
