<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        //\App\Models\User::factory(40)->create();
        $arr = config('datasources.countries');
        $arr = array_flip($arr);
        for ($i = 1; $i < 42; $i++) {
//            $d = rand(2, 6);
//            $arr = [
//                1, 2, 3, 4, 5, 6
//            ];
//            $tt = array_rand($arr, $d);
//            $size = 5;
//            foreach ($tt as $k) {
//                DB::table('user_titles')->insert([
//                    'user_id'       => $i,
//                    'title_id'      => $k+1,
//                    'created_by'    => 'd',
//                    'created_date'  => now(),
//                    'modified_by'   => 'd',
//                    'modified_date' => now()
//                ]);
//                $size--;
//            }
            DB::table('users')
              ->where('id', '=', $i)
              ->update([
                  'country_id' => $arr[array_rand($arr, 1)]]);
        }
    }
}
