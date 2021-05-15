<?php

namespace App\Http\Controllers;

use App\Models\AuxiliaryModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class FilmController extends Controller
{
    /**
     * Display a listing of the resource.
     * /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        //Select film
        $selectFilm = [
            'f.film_id',
            'f.title',
            'f.description',
            'f.release_year',
            'f.length',
            'f.rental_duration',
            'f.rental_rate',
            'f.replacement_cost',
            'f.rating',
            'f.special_features',
            'fc.category_id',
            'la.name as language_name',
            DB::raw("CONCAT(
                '[',
                GROUP_CONCAT(
                    JSON_OBJECT(
                        'actor', a . actor_id,
                        'actor_first_name', a . first_name,
                        'actor_last_name',a.last_name)), ']') as actors")
        ];
        $whereFilm = [];
        $joinTable = [
            ['language as la', 'la.language_id', '=', 'f.language_id'],
            ['film_actor as fa', 'fa.film_id', '=', 'f.film_id'],
            ['actor as a', 'a.actor_id', '=', 'fa.actor_id'],
            ['film_category as fc', 'f.film_id', '=', 'fc.film_id'],
        ];
        $groupBy = [
            'f.film_id'
        ];
        // Don't care
        $this->auxiliaryModel->setTableName('film', 'f');
        $resultQuery = $this->auxiliaryModel->getListFromQuery($selectFilm, $whereFilm, $joinTable)
                                            ->groupBy($groupBy)
                                            ->get();
        $result = collect($resultQuery);
        $fc = config('datasources.category');
        $fc = collect($fc)->keyBy('category_id');
        $result = $result->map(function ($x) use ($fc) {
            $t = collect(json_decode($x->actors));
            $x->actors = $t;
            $des = $fc->get($x->category_id);
            $x->category_id = $des['name'];

            return $x;
        }, $result);

        return response()->json($result);
    }

}
