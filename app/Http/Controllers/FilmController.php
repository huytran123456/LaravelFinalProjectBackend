<?php

namespace App\Http\Controllers;

use App\Models\AuxiliaryModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FilmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'la.name as language_name'
        ];
        $whereFilm = [];
        $joinTable = [
            ['language as la', 'la.language_id', '=', 'f.language_id'],
        ];

        // Don't care
        $model = new AuxiliaryModel('film');
        $resultQuery = $model->getListFromQuery('f', $selectFilm, $whereFilm, $joinTable)
                             ->get();
        $result = collect($resultQuery)->toArray();

        return response()->json($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
