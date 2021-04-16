<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\ExchangeRate;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Enums\CryptoCurrencyEnum;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'message' => ['login' => ['Login credentials are invalid.']],
                ], Response::HTTP_UNAUTHORIZED);
            }
        } catch (JWTException $e) {
            return response()->json([
                'message' => ['login' => [$e->getMessage()]],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'token' => $token,
            'user' => auth()->user(),
        ], Response::HTTP_OK);
    }

    public function register(Request $request)
    {
        $input = $request->only('name', 'email', 'password');
        $validator = Validator::make($input, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);

            return response()->json([
                'message' => 'User Registration successful',
                'user' => $user
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json([
                'message' => ['register' => [$e->getMessage()]],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function profile()
    {
        return response()->json($this->guard()->user(), Response::HTTP_OK);
    }

    public function logout()
    {
        $this->guard()->logout();

        return response()->json(['message' => 'Successfully logged out'], Response::HTTP_NO_CONTENT);
    }

    public function getExchangeRate()
    {
        $btcExchangeRate = ExchangeRate::where('currency_id', CryptoCurrencyEnum::Bitcoin)->get(['price', 'amount'])->first();
        return response()->json($btcExchangeRate, Response::HTTP_OK);
    }

    public function guard()
    {
        return Auth::guard('api');
    }
}
