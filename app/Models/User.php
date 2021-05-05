<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Table name for Model
     * @var string
     */
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    //Alias name for table
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'first_name',
        'email',
        'last_name',
        'phone',
        'password',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Refactoring
     * @param array $selectArray
     * @param array $whereArray
     * @return \Illuminate\Database\Query\Builder
     */
    public function getListUsers(array $selectArray, array $whereArray = [], array $joinTable = [])
    {

        $users = DB::table($this->alias())
                   ->select($selectArray)
                   ->where($whereArray);
        if ($joinTable !== []) {
            foreach ($joinTable as $j) {
                $users = $users->leftJoin($j[0], $j[1], $j[2], $j[3]);
            }
        }

        return $users;
    }

    /**
     * Alias name for table
     * @return string
     */
    protected function alias(): string
    {
        return $this->getTable() . ' as u';
    }
}
