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
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Controlling selected data and conditions
        $select = [
            'u.id',
            'u.first_name',
            'u.last_name',
            'u.email',
            'u.phone',
            'u.dob',
            'u.gender_id',
            'u.country_id',
            DB::raw("CONCAT(
                '[',
                GROUP_CONCAT(
                    JSON_OBJECT(
                        'title_id', t . title_id,
                        'title_name', t . title_name)), ']') as titles")
        ];
        $where = [
            ['u.is_delete', '=', 0],
        ];
        $joinTable = [
            ['user_titles as ut', 'u.id', '=', 'ut.user_id'],
            ['titles as t', 'ut.title_id', '=', 't.title_id']
        ];
        $groupBy = [
            'u.id'
        ];

        // Don't care
        $this->auxiliaryModel->setTableName('users', 'u');
        $resultQuery = $this->auxiliaryModel->getListFromQuery($select, $where, $joinTable)
                                            ->groupBy($groupBy)
                                            ->get();
        $result = collect($resultQuery);
        $genders = config('datasources.genders');
        $countries = config('datasources.countries');
        $genders = collect($genders)->keyBy('gender_id');
        $result = $result->map(function ($x) use  ($genders, $countries) {
            $t = collect(json_decode($x->titles));
            $t = $t->sortBy('title_id')->values()->all();
            $x->titles = $t;
            $des = $genders->get($x->gender_id);
            $x->gender = $des['gender_name'];
            $x->country = $countries[$x->country_id];

            return $x;
        }, $result);

        return response()->json($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param App\Http\Requests\UserRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        //Select
        $selectUser = ['email'];
        //Where
        $whereUser = [
            ['email', '=', $request->email],
            ['is_delete', '<>', '1']
        ];
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
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //Get user id
        $user = $request->user();
        //Select ,where column here
        $select = [
//            'title_id',
//            'title_name'
            DB::raw("CONCAT(
                '[',
                GROUP_CONCAT(
                    JSON_OBJECT(
                        'title_id', t . title_id,
                        'title_name', t . title_name)), ']') as titles")
        ];
        $where = [
            ['ut.user_id', '=', $user->id]
        ];
        $joinTable = [
            ['titles as t', 'ut.title_id', '=', 't.title_id']
        ];
        $groupBy = [
            'ut.user_id'
        ];

        //Don't care
        $this->auxiliaryModel->setTableName('user_titles', 'ut');
        $result = $this->auxiliaryModel->getListFromQuery($select, $where, $joinTable)
                                       ->groupBy($groupBy)
                                       ->first();
        $genders = config('datasources.genders');
        $countries = config('datasources.countries');
        $genders = collect($genders)->keyBy('gender_id');
        $t = collect(json_decode($result->titles));
        $user->titles = $t->sortBy('title_id')->values()->all();
        $gender = $genders->get($user->gender_id);
        $user->gender = $gender['gender_name'];
        $user->country = $countries[$user->country_id];

        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param App\Http\Requests\UserUpdateRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request)
    {
        //Get id from user
        $id = $request->user()->id;
        //Select ,where, update column here
        $select = ['first_name',
            'last_name',
            'phone'
        ];
        $where = [
            ['id', '=', $id],
            ['is_delete', '=', 0]
        ];
        $update = [
            'first_name',
            'last_name',
            'phone'
        ];


        //Ignore it if you dont change your structure
        $this->auxiliaryModel->setTableName('users');
        $findUser = $this->auxiliaryModel->getListFromQuery($select, $where);
        $requestContent = $request->only($update);
        //remove null on content
        $requestContent = array_diff($requestContent, [null, ""]);
        $result = $findUser->update($requestContent);


        return response()->json($result);
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
