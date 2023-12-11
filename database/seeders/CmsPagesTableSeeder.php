<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CmsPage;

class CmsPagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cmsPagesRecords = [
           ['id'=>1, 'title'=>'About us','description'=>'About us Content is coming soon',
           'url'=>'about-us','meta_title'=>'About us','meta_description'=>'Desc','meta_keywords'=>'about us','status'=>1],
           ['id'=>2, 'title'=>'Privacy Policy','description'=>'Privacy Policy Content is coming soon',
           'url'=>'privacy-policy','meta_title'=>'Privacy Policy','meta_description'=>'Desc','meta_keywords'=>'Privacy Policy','status'=>1],
        ];

        CmsPage::insert($cmsPagesRecords);
    }
}
