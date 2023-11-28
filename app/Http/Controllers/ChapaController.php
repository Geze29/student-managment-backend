<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Chapa\Chapa\Facades\Chapa as Chapa;
use Illuminate\Routing\Route;

class ChapaController extends Controller
{
    protected $reference;

    public function __construct(){
        $this->reference = Chapa::generateReference();
    }

    public function initialize($amount , $email , $fname , $lname){
        $reference = $this->reference;

        $data = [
            'amount' => $amount,
            'email' => $email,
            'tx_ref' => $reference,
            'currency' => "ETB",
            'callback_url' => route('callback',[$reference]),
            'return_url' => 'http://localhost:5173/enrollment?status=completed',
            'first_name' => $fname,
            'last_name' => $lname,
            "customization" => [
                "title" => '',
                "description" => "Time to pay"
             ]
        ];

        $payment = Chapa::initializePayment($data);

        if ($payment['status'] !== 'success') {
            // notify something went wrong
            return;
        }

        return $payment['data']['checkout_url'];
    }

    public function callback($reference) {
        
        $data = Chapa::verifyTransaction($reference);
        //if payment is successful
        if ($data['status'] ==  'success') {
            return response()->json(['message'=>'successfully payed','data'=>$data],200);
        } else{
            return response()->json(['message'=>'failed','data'=>$data],400);
        }
    }

    public function pay(Request $request) {
        
        $request->validate([
            'fname' => 'required',
            'lname' => 'required',
            'amount' => 'required',
            'email' => 'required',
        ]); 

       $url = $this->initialize($request['amount'], $request['email'], $request['fname'], $request['lname']);      

       return response([
           'url' => explode(' ', $url)[0],
       ]);
    }
}
