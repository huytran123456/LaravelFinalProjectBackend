<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePassRequest;
use App\Http\Requests\EditInfoRequest;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;

class UserInfoController extends Controller
{
    //Send mail
    public function sendMail(UserUpdateRequest $request)
    {
        $to = $request['email'];
        $hashId = md5($to);
        $qrCode = rand(0, 999999);
        $details = [
            'address' => $hashId,
            'qrCode'  => $qrCode,
        ];
        $timeNow = Carbon::now();
        $insertVerify = [
            'email'        => $request['email'],
            'hash_id'      => $hashId,
            'qrCode'       => $qrCode,
            'created_date' => $timeNow,
            'expired_date' => Carbon::parse($timeNow)->addHour(),
        ];
        $result = DB::table('verify')->insert($insertVerify);
        if ($result === false) {
            return response()->json([
                'code'    => 0,
                'message' => 'Fail',
            ]);
        }
        Mail::to($to)->send(new \App\Mail\MyMail($details));

        return response()->json([
            'code'    => 1,
            'message' => 'Email was send!',
        ]);
    }

    //Change pass
    public function changePass(ChangePassRequest $request)
    {
        $selectUser = [
            'email',
        ];
        $whereUser = [
            ['hash_id', '=', $request['hash_mail']],
            ['qrCode', '=', $request['qr_code']],
            //Check expired or not
            ['created_date', '<', Carbon::now()],
            ['expired_date', '>', Carbon::now()]
        ];
        $updateUser = [
            'password' => bcrypt($request['new_pass']),
        ];

        $this->auxiliaryModel->setTableName('verify');
        $result = $this->auxiliaryModel->getListFromQuery($selectUser, $whereUser)->first();
        if ($result === null) {
            return response()->json([
                'code'    => 0,
                'message' => 'Changing pass fail',
            ]);
        }
        $this->auxiliaryModel->setTableName('users');
        $updateResult = $this->auxiliaryModel->getListFromQuery(['password'], [['email', '=', $result->email]])
                                             ->update($updateUser);

        return response()->json([
            'code' => $updateResult,
        ]);
    }

    public function getInfoUser(Request $request)
    {
        $user = $request->user()->email;
        $selectUser = [
            'first_name',
            'last_name',
            'phone',
            'avatar',
            'email'
        ];
        $whereUser = [
            ['email', '=', $user],
            ['is_delete', '<>', 1]
        ];
        $this->auxiliaryModel->setTableName('users');
        $findUser = $this->auxiliaryModel->getListFromQuery($selectUser, $whereUser)->first();
        $avatar = utf8_encode(Storage::disk('avatar')->get($findUser->avatar));

        return response()
            ->json([
                'code'       => 1,
                'img'        => $avatar,
                'first_name' => $findUser->first_name,
                'last_name'  => $findUser->last_name,
                'email'      => $findUser->email,
                'phone'      => $findUser->phone,
            ]);
    }

    public function editInfoUser(EditInfoRequest $request)
    {
        //Get id from user
        $email_user = $request['email'];
        //Select ,where, update column here
        $selectUser = [
            'first_name',
            'last_name',
            'phone',
            'avatar',
        ];
        $whereUser = [
            ['email', '=', $email_user],
            ['is_delete', '<>', 1]
        ];
        $updateUser = [
            'first_name',
            'last_name',
            'phone',
            'avatar',
        ];


        //Ignore it if you dont change your structure
        $this->auxiliaryModel->setTableName('users');
        $findUser = $this->auxiliaryModel->getListFromQuery($selectUser, $whereUser);
        $updateContent = $request->only($updateUser);

//        $img_name = $request['avatar']->getClientOriginalName();
        $updateContent['avatar'] = md5($request['email']) . time() . '.' . $request['avatar']->extension();
        $image = Image::make($request->file('avatar'));

        $image->resize(225, null, function ($constraint) {
            $constraint->aspectRatio();
        })->encode();
        Storage::disk('avatar')->put($updateContent['avatar'], $image);
//        dd($updateContent);
        $result = $findUser->update($updateContent);


        return response()->json($result);
    }
}
