<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
class phonepayController extends Controller
{
   public function phonepay() {
    //   reqvest paramiter set
    $data = [
        "merchantId" => "PGTESTPAYUAT",
        "merchantTransactionId" => uniqid(),
        "merchantUserId" => "MUID123",
        "amount" => 10000,
        "redirectUrl" =>route("response"),
        "redirectMode" => "REDIRECT",
        "callbackUrl" => route ("response"),
        "mobileNumber" => "9999999999",
        "paymentInstrument" => ["type" => "PAY_PAGE"],
    ];
     $encode=base64_encode(json_encode($data));
     $salt=1;
     $saltkey ='099eb0cd-02cf-4e2a-8aca-3e6c6aff0399';
     $string =$encode.'/pg/v1/pay'.$saltkey;
     $SHA256 = hash("sha256",$string);
     $finalXHeader= $SHA256."###".$salt;
   //dd($VERIFY);
   //return json_encode(['request' =>$encode]);
   $response = curl::to('https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay')
    ->withHeader('Content-Type:application/json')
    ->withHeader('X-VERIFY:'.$finalXHeader)
    ->withData(json_encode(['request' =>$encode]))
    ->post();
    
    dd(json_decode($response) );
   }

   public function response(Request $request) {
   $res = $request->all();
   dd($res);
   }
}
