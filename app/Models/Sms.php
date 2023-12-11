<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sms extends Model
{
    use HasFactory;

    public static function sendSms($message,$mobile){
        /*Code for SMS Script Starts*/
        $request ="";
        $param['authorization']="0hsjZ9FTGbnq06rJ1C8fdpUXSEPLWv2aDRuMkyei7mKBwVNHx4vw0gKcTrhemqFS8b6Do59XNzp1RyYi";
        $param['sender_id'] = 'FSTSMS';
        $param['message']= 'This is the test SMS from Stack Developers Youtube Channel';
        $param['numbers']= '9800000000';
        $param['language']="english";
        $param['route']="p";

        foreach($param as $key=>$val) {
            $request.= $key."=".urlencode($val);
            $request.= "&";
        }
        $request = substr($request, 0, strlen($request)-1);

        $url ="https://www.fast2sms.com/dev/bulk?".$request;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $curl_scraped_page = curl_exec($ch);
        curl_close($ch);
        /*Code for SMS Script Ends*/
    }
}
