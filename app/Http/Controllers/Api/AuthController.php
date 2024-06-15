<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Client as OClient;
use GuzzleHttp\Client;

class AuthController extends Controller
{
    use ApiResponses;

    /**
     * User registration
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) return $this->errorResponse($validator->errors(), "Validation Failed.", 401);


        $password = $request->password;
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);
        $oClient = OClient::where('password_client', 1)->first();
        return $this->getTokenAndRefreshToken($oClient, $user, $password);
    }

    /**
     * Login user
     */
    public function login(Request $request)
    {
       
        $user = User::where('email', $request->email)->first();
        if (!$user) return $this->errorResponse([], "User not found.", 401);


        if (Auth::attempt(['email' =>$request->email, 'password' => $request->password])) {
            $oClient = OClient::where('password_client', 1)->first();
            return $this->getTokenAndRefreshToken($oClient,$user, $request->password);
        } else {
            return $this->unauthorizedResponse(['error' => 'Unauthorized'], "Invalid Email/Password");
        }
    }

    /**
     * Login user
     *
     * @param  LoginRequest  $request
     */
    public function me()
    {

        $user = auth()->user();
        return $this->successResponse($user, "Logged In User List.");
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return $this->successResponse([],'Successfully logged out.');
    }


    private function getTokenAndRefreshToken(OClient $oClient, $user, $password)
    {

        $oClient = OClient::where('password_client', 1)->first();
        $http = new Client;
        $response = $http->request('POST', 'http://farm-backend.test/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => $oClient->id,
                'client_secret' => $oClient->secret,
                'username' => $user->email,
                'password' => $password,
                'scope' => '*',
            ],
        ]);

        $result = json_decode((string) $response->getBody(), true);
        return $this->successResponse([
            'token' => $result, 
            'user' => $user
        ]);
    }
}
