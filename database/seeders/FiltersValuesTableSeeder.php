<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductsFiltersValue;

class FiltersValuesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $filtervalueRecords = [
            ['id'=>1,'filter_id'=>1,'filter_value'=>'cotton','status'=>1],
            ['id'=>2,'filter_id'=>1,'filter_value'=>'polyester','status'=>1],
            ['id'=>3,'filter_id'=>2,'filter_value'=>'4 GB','status'=>1],
            ['id'=>4,'filter_id'=>2,'filter_value'=>'8 GB','status'=>1]           
        ];

        ProductsFiltersValue::insert($filtervalueRecords);
    }
}
