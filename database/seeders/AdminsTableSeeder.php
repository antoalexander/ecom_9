<?php

namespace Database\Seeders;
 
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;


class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $adminRecords = [
      ['id'=>2,'name'=>'John','type'=>'vendor','vendor_id'=>1,'mobile'=>'9700000000','email'=>'john@admin.com','password'=>'$2a$12$B3apSj6r.vVcsjFr4tvbo.2n61Hg47xot.s8V9t1Bh..gCmejNyha
','image'=>'','status'=>0],

        ];
        Admin::insert($adminRecords);
    }
}
