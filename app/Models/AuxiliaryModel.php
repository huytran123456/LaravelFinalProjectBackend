<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

//This model is used for Refactoring Query.
class AuxiliaryModel extends Model
{
    use HasFactory;

    protected $table = '';
    protected string $alias;

    //Init table name for this model
    public function __construct(string $tableName = '', string $aliasName = '')
    {
        $this->table = $tableName;
        $this->alias = $aliasName;
    }

    public function getAlias()
    {
        return $this->alias;
    }

    public function setTableName(string $tableName, string $aliasName = '')
    {
        $this->table = $tableName;
        $this->alias = $aliasName;
    }

    public function getListFromQuery(array $selectArray, array $whereArray = [], array $joinTable = [])
    {
        $alias = ($this->alias === '') ? '' : ' as '. $this->alias;
        $queryResult = DB::table($this->getTable() . $alias)
                         ->select($selectArray)
                         ->where($whereArray);
        //Structure join clause Laravel
        //j[0]:table name
        //j[1]: join left condition expression
        //j[2]:operator condition
        //j[3]:join right condition expression
        if ($joinTable !== []) {
            foreach ($joinTable as $j) {
                $queryResult = $queryResult->leftJoin($j[0], $j[1], $j[2], $j[3]);
            }
        }

        return $queryResult;
    }

}
