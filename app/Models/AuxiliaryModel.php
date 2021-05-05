<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AuxiliaryModel extends Model
{
    use HasFactory;

    protected $table = '';

    //Init table name for this model
    public function __construct(string $tableName)
    {
        $this->table = $tableName;
    }

    public function getListFromQuery(string $aliasName,array $selectArray, array $whereArray = [], array $joinTable = [])
    {

        $queryResult = DB::table($this->getTable(). ' as '.$aliasName)
                         ->select($selectArray)
                         ->where($whereArray);
        if ($joinTable !== []) {
            foreach ($joinTable as $j) {
                $queryResult = $queryResult->leftJoin($j[0], $j[1], $j[2], $j[3]);
            }
        }

        return $queryResult;
    }

}
