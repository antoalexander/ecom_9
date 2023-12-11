<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VendorsBusinessDetail;

class VendorsBusinessDetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vendorRecords = [
          ['id'=>'1','vendor_id'=>'1','shop_name'=>'John Electronic Shop','shop_address'=>'1234-SCF','shop_city'=>'New Delhi','shop_state'=>'Delhi','shop_country'=>'India','shop_pincode'=>100001,'shop_mobile'=>'970000000','shop_website'=>'sitemakers.com','shop_email'=>'john@admin.com','address_proof'=>'pan card','address_proof_image'=>'test.jpg','business_license_number'=>'1225752',
              'gst_number'=>'126587','pan_number'=>'0124568'],
        ];
        VendorsBusinessDetail::insert($vendorRecords);
    }
}
