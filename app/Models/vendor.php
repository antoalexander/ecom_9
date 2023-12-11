<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vendor extends Model
{
    use HasFactory;

    public function vendorbusinessdetails(){
        return $this->belongsTo('App\Models\VendorsBusinessDetail','id','vendor_id');
    }

    public static function  getVendorDetails($vendorid)
    {
        $getVendorShop = VendorsBusinessDetail::select('shop_name')->where('vendor_id',$vendorid)->first()->toArray();
        return $getVendorShop['shop_name'];
    }
}
