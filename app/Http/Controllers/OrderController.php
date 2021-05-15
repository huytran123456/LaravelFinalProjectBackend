<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserSocialUpdateRequest;
use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\SocialUserRequest;
use Laravel\Passport\Passport;
use Intervention\Image\Image;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    //Get detail order
    public function getDetail(int $id)
    {
        $customer = Auth::user();
        $selectFilm = [
            'f.film_id',
            'f.title',
            'f.rental_duration',
            'f.rental_rate',
            'f.replacement_cost',
        ];
        $whereFilm = [
            ['f.film_id', '=', $id],
        ];
        $this->auxiliaryModel->setTableName('film', 'f');
        $getFilm = $this->auxiliaryModel->getListFromQuery($selectFilm, $whereFilm)->first();

        return response()->json([
            'title'          => $getFilm->title,
            'price'          => $getFilm->rental_duration * $getFilm->rental_rate,
            'duration'       => $getFilm->rental_duration,
            'customer_name'  => $customer->first_name . ' ' . $customer->last_name,
            'customer_email' => $customer->email
        ]);

    }

    public function makeOrder(OrderRequest $request)
    {
        $createContent = [
            'user_id',
            'film_id',
            'price',
            'from_date',
            'to_date',
        ];
        $content = $request->only(['price', 'film_id']);
        $content['from_date'] = Carbon::now();
        $content['to_date'] = $content['from_date']->copy();
        $content['to_date']->addDay($request['duration']);
        $content['user_id'] = Auth::id();
        $content['film_id'] = $request['film_id'];
        $result = DB::table('orders')->insert($content);

        return response()->json([
            'code' => $result
        ]);

    }
}
