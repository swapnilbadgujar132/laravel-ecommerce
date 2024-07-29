<?php

namespace App\Http\Controllers\User;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\pdfGenerate;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalController extends Controller
{
    public function paypal(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('paypal.success'),
                "cancel_url" => route('paypal.cancel')
            ],
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $request->price
                    ]
                ]
            ]
        ]);
        if(isset($response['id']) && $response['id'] != null) {
            foreach($response['links'] as $link) {
                if($link['rel'] == 'approve') {
                    $product_names = implode($request->product_name);
                    session()->put('product_name', $product_names);
                    session()->put('quantity', $request->quantity);
                    return redirect()->away($link['href']);
                }
            }
        } else {
            return redirect()->route('paypal.cancel');
        }
    }

    public function success(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request->token);
        // dd($response);

        if(isset($response['status']) && $response['status'] == 'COMPLETED') {
            
            // Order
            $order = new Order();
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            $randomString = substr(str_shuffle($characters), 0, 10);

            $product_ids = Cart::whereUserId(auth()->id())->pluck('product_id');
            $order->uuid = $randomString;
            $order->transaction_id =$response['purchase_units'][0]['payments']['captures'][0]['id'];            ;
            $order->user_id = auth()->id();
            $order->total_amount = $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'];
            $order->payment_status = 'paid';
            $order->order_status = 'pending';
            $order->product_id = json_encode($product_ids);
            $order->payment_method = 'paypal';
            $order->save();
            // 


            // Insert data into database
            $payment = new Transaction;
            $payment->order_id = $response['id'];
            $payment->user_id = auth()->id();
            // $payment->product_name = session()->get('product_name');
            // $payment->quantity = session()->get('quantity');
            $payment->total_amount = $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'];
            // $payment->currency = $response['purchase_units'][0]['payments']['captures'][0]['amount']['currency_code'];
            // $payment->payer_name = $response['payer']['name']['given_name'];
            // $payment->payer_email = $response['payer']['email_address'];
            $payment->payment_status = $response['status'];
            $payment->order_status = 'pending';
            $payment->save();

            if($response['status'] == 'COMPLETED' AND $response['payment_source']['paypal']['account_status'] == 'VERIFIED'){
                $user_data= $order->users;
                $string = $order->product_id;
                $products = json_decode($string, true);
                $product_data=[];
                foreach ($products as $key => $product) {
                   $data = Product::findorfail($product);
                   $product_data[]= $data;
                }

            pdfGenerate::dispatch($order,$user_data,$product_data);
            Cart::whereUserId(auth()->id())->delete();
            return redirect()->route('user.order')->with('success', 'Order place successfully');
            } 
            // unset($_SESSION['product_name']);
            // unset($_SESSION['quantity']);
 
        } else {
            return redirect()->route('paypal.cancel');
        }
    }

    public function cancel()
    {
        return "Payment is cancelled.";
    }
}
