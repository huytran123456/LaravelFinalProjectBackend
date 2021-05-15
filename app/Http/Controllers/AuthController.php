<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserSocialUpdateRequest;
use App\Models\User;
use http\Env\Request;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\SocialUserRequest;
use Laravel\Passport\Passport;
use Intervention\Image\Image;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{

    /**
     * @param AuthRequest $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function login(AuthRequest $request)
    {
        //Custom your response user detail here if login successful

        //Be careful if you want to change anything below!
        //You can custom $credentials if you want.
        //However,original $credentials is recommended because of OAuth2.
        $credentials = $request->only('email', 'password') + ['is_delete' => 0];

        return $this->authPassport($credentials, $request['remember_me']);
    }

    //Login function

    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function authPassport(array $credentials, bool $remember_me = false)
    {
        $user = [
            'first_name',
            'last_name',
            'phone',
            'email',
        ];
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'code'    => 0,
                'message' => 'Invalid login']);
        }
        if (!$remember_me) {
            //JWT lifetime is 12 months if remember me is true
            Passport::personalAccessTokensExpireIn(now()->addDay(30));
        }

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
        $selectUser = ['u.user_id'];
        //Where
        $whereUser = [
            ['u.email', '=', $request->email],
            ['u.is_delete', '<>', '1']
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
        $this->auxiliaryModel->setTableName('users', 'u');
        $content = $request->only($insertUser);
        $userDetect = $this->auxiliaryModel->getListFromQuery($selectUser, $whereUser)->first();
        //If social user has the same email with manual user
        if ($userDetect !== null) {
            //Select
            $social = $request->only(['social_platform', 'email']);

            return $this->insert_social_user($userDetect, $social);
        }
        //If social user's email is not exist in database
        $content['password'] = bcrypt($content['password']);
        $user = DB::table('users')
                  ->insert($content);

        return response()->json([
            'code'     => $user,
            'message'  => 'Insert new user.',
            'new_user' => 1,
        ]);
    }

    //Insert social user
    public function insert_social_user(object $userDetect, array $social)
    {
        //Select
        //var_dump($social['social_platform']);die;
        $selectSocialUser = ['user_id'];
        //Where
        $whereSocialUser = [
            ['user_id', '=', $userDetect->user_id],
            ['is_delete', '<>', '1'],
            ['social_platform', '=', $social['social_platform']],
            ['email', '=', $social['email']],
        ];
        //Where user
        $whereUser = [
            ['user_id', '=', $userDetect->user_id],
            ['is_delete', '<>', '1'],
        ];
        //Insert
        $insertUser = [
            'user_id',
            'social_id_token',
            'social_platform',
        ];
        //Select
        $this->auxiliaryModel->setTableName('social_users', 'su');
        $userSocialDetect = $this->auxiliaryModel->getListFromQuery($selectSocialUser, $whereSocialUser)->first();
        //var_dump($userSocialDetect);die;
        $social_user = 0;
        if ($userSocialDetect === null) {
            $content = $social + ['user_id' => $userDetect->user_id];
            $social_user = DB::table('social_users')
                             ->insert($content);
        }
        $message = ($social_user === 1) ? 'Insert new user' : 'Social user has exist';

        return response()->json([
            'code'    => $social_user,
            'message' => $message,
        ]);
    }

    //Reset social password
    public function resetSocialPassword(UserSocialUpdateRequest $request)
    {
        //Select ,where, update column here
        $selectUser = [
            'email',
        ];
        $whereUser = [
            ['is_delete', '<>', 1]
        ];
        $updateUser = [
            'password'
        ];
        $this->auxiliaryModel->setTableName('users');
        $userDetect = $this->auxiliaryModel->getListFromQuery($selectUser, $whereUser);
        $requestContent = $request->only($updateUser);
        $requestContent['password'] = bcrypt($requestContent['password']);
        //remove null on content
        $result = $userDetect->update($requestContent);
        if ($result) {
            return $this->authPassport($request->only('email', 'password') + ['is_delete' => 0]);
        }

        return response()->json([
            'code'    => 0,
            'message' => 'Update social password failed!',
        ]);
    }
}
