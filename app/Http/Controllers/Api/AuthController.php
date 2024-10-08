<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Client;


class AuthController extends Controller
{
    
    public function register(RegisterRequest $request): JsonResponse
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $client = Client::first();

        $tokenRequest = $request->create('/oauth/token', 'POST', [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => $request->email,
            'password' => $request->password,
            'scope' => '*',
        ]);

        $response = app()->handle($tokenRequest);

        $data = json_decode($response->getContent());

        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'User has been registered successfully.',
            'data' => $data,
        ], 200);
    }
    public function login(LoginRequest $request): JsonResponse
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {    

            $client = Client::first();
    
            $tokenRequest = $request->create('/oauth/token', 'POST', [
                'grant_type' => 'password',
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'username' => $request->email,
                'password' => $request->password,
                'scope' => '*',
            ]);
    
            $response = app()->handle($tokenRequest);
    
            $data = json_decode($response->getContent());
    
            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'message' => 'User has been logged in successfully.',
                'data' => $data,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Unauthorized.',
                'errors' => 'Unauthorized',
            ], 401);
        }
    }
}