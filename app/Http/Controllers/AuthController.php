<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\SocialUserRequest;
use Laravel\Passport\Passport;

class AuthController extends Controller
{

    /**
     * @param AuthRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(AuthRequest $request)
    {
        //Custom your response user detail here if login successful
        $user = [
            'first_name',
            'last_name'
        ];

        //Be careful if you want to change anything below!
        //You can custom $credentials if you want.
        //However,original $credentials is recommended because of OAuth2.
        $credentials = $request->only('email', 'password') + ['is_delete' => 0];
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'code'    => 0,
                'message' => 'Invalid login']);
        }
       // Passport::personalAccessTokensExpireIn(now()->addHour(1));
        $accessToken = Auth::user()->createToken('authToken');
        $auth_user = Auth::user()->only($user);

        return response()
            ->json([
                'code'         => 1,
                'user'         => $auth_user,
                'access_token' => $accessToken->accessToken,
                'token'        => $accessToken->token->expires_at]);
    }

    public function social_login(SocialUserRequest $request)
    {
        //Select
        $selectUser = ['email'];
        //Where
        $whereUser = [
            ['email', '=', $request->email],
            ['is_delete', '<>', '1']
        ];
        //Insert
        $insertUser = [
            'first_name',
            'last_name',
            'email',
            'phone',
            'password'
        ];
        //Dont care
        $userModel = new User();
        $userDetect = $userModel->getListUsers($selectUser, $whereUser)->first();
        if (!empty($userDetect)) {
            return response()->json([
                'code'    => 0,
                'message' => 'User has already existed.'
            ]);
        }
        $content = $request->only($insertUser);
        $content['password'] = bcrypt($content['password']);
        $user = DB::table('users')
                  ->insert($content);

        return response()->json([
            'code'    => 1,
            'message' => 'Insert new user.'
        ]);
    }
}
