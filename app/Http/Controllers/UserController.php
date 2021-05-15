<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\AuxiliaryModel;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Store a newly created resource in storage.
     *
     * @param App\Http\Requests\UserRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        //Create field
        $create = [
            'first_name',
            'last_name',
            'email',
            'phone',
            'password'
        ];

        //Dont care
        $content = $request->only($create);
        //$content['password'] = md5($content['password']);
        $content['password'] = bcrypt($content['password']);
        $user = DB::table('users')
                  ->insert($content);

        return response()->json($user);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Select ,where, update column here
        $select = ['is_delete'];
        $where = [
            ['id', '=', $id],
            ['is_delete', '=', 0]
        ];
        $destroy = [
            'is_delete' => 1
        ];

        //Ignore it if you dont change your structure
        $this->auxiliaryModel->setTableName('users');
        $user = $this->auxiliaryModel->getListUsers($select, $where);
        // var_dump($user);die;
        $res = $user->update($destroy);

        return response()->json($res);
    }
}
