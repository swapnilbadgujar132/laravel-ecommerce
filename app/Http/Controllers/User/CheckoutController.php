<?php

namespace App\Http\Controllers\User;

use PDF; 
use Stripe;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\BillingAddress;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Jobs\pdfGenerate;
use App\Models\Product;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Stripe\Charge;

use function PHPUnit\Framework\fileExists;
use function PHPUnit\Framework\isNull;

class CheckoutController extends Controller
{
    function index()
    {
        if(Cart::whereUserId(auth()->id())->count() <= 0){
            return redirect()->route('user.shop')->with('error','Your cart is empty');
        }
        $billing_address = BillingAddress::whereUserId(auth()->id())->first();
        if (!$billing_address) {
            BillingAddress::create([
                'user_id' => auth()->id(),
                'address1' =>  ' ',
                'address2' => ' ',
                'zip_code' => ' ',
                'company' => ' ',
                'city' => ' ',
                'phone' => ' ',
            ]);
            return view('user.checkout', compact('billing_address'));
        }
        return view('user.checkout', compact('billing_address'));
    }

    function update_billing_address(Request $request)
    {
        
        $validate = $request->validate([
            'address1' => 'required',
            'address2' => 'required',
            'zip_code' => 'required',
            'city' => 'required',
            'phone' => 'required',
        ]);

        $billing_address = BillingAddress::whereUserId(auth()->id())->first();
        if ($billing_address) {
            BillingAddress::where('user_id', auth()->id())->update([
                'user_id' => auth()->id(),
                'address1' =>  $request->address1,
                'address2' => $request->address2,
                'zip_code' => $request->zip_code,
                'company' => $request->company ?? ' ',
                'city' => $request->city,
                'phone' => $request->phone,
            ]);
        }
        return redirect()->route('user.payment')->with('success', 'billing address add successfully');
    }

    function payment()
    {
        if(Cart::whereUserId(auth()->id())->count() <= 0){
            return redirect()->route('user.shop')->with('error','Your cart is empty');
        }
        $billing_address = BillingAddress::whereUserId(auth()->id())->first();
        return view('user.payment', compact('billing_address'));
    }

    function checkout_submit_cash_on_delivery(Request $request)
    {
        $order = new Order();
        $transaction = new Transaction();
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $randomString = substr(str_shuffle($characters), 0, 10);

        $total_amount = Cart::whereUserId(auth()->id())->sum('sub_total');
        $product_ids = Cart::whereUserId(auth()->id())->pluck('product_id');
        $order->uuid = $randomString;
        $order->transaction_id = 'null';
        $order->user_id = auth()->id();
        $order->total_amount = $total_amount;
        $order->payment_status = 'unpaid';
        $order->order_status = 'pending';
        $order->product_id = json_encode($product_ids);
        $order->payment_method = $request->payment_method;
        $order->save();

        $transaction->order_id = $order->uuid;
        $transaction->user_id = auth()->id();
        $transaction->payment_status = 'unpaid';
        $transaction->order_status = 'pending';
        $transaction->total_amount = $total_amount;
        $transaction->save();

         // return   $order;
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

    
    public function orderCancel($uuid)
    {
        if ($uuid == '0') {
            return redirect()->back()->with('error', 'Order cancellation unsuccessful');
        }

        $delete = Order::where('uuid', $uuid)->delete();

        if ($delete) {
            return redirect()->back()->with('success', 'Order cancelled successfully');
        } else {
            return redirect()->back()->with('error', 'Order cancellation failed');
        }
    }

    public function order()
    {
        $orders = Order::whereUserId(auth()->id())->latest()->get();
        return view('user.order', compact('orders'));
    }

    public function stripePost(Request $request)
    {
        // $total_amount = Cart::whereUserId(auth()->id())->sum('sub_total');

        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $charge = Stripe\Charge::create([
            "amount" => 100 * 100,
            "currency" => "usd",
            "source" => $request->stripeToken,
            "description" => "Payment Successfully From " . Auth::user()->name,
        ]);

        Session::flash('success' ,'payment succesfully');
        return back();
        // if ($charge->status) {

        //     $order = new Order();
        //     $transaction = new Transaction();
        //     $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        //     $randomString = substr(str_shuffle($characters), 0, 10);

        //     $product_ids = Cart::whereUserId(auth()->id())->pluck('product_id');
        //     $order->uuid = $randomString;
        //     $order->transaction_id = $charge->id;
        //     $order->user_id = auth()->id();
        //     $order->total_amount = $total_amount;
        //     $order->payment_status = $charge->status == 'succeeded' ? 'paid' : 'unpaid';
        //     $order->order_status = 'pending';
        //     $order->product_id = json_encode($product_ids);
        //     $order->payment_method = $request->payment_method;
        //     $order->save();

        //     $transaction->order_id = $order->uuid;
        //     $transaction->user_id = auth()->id();
        //     $transaction->payment_status = $charge->status == 'succeeded' ? 'paid' : 'unpaid';
        //     $transaction->order_status = 'pending';
        //     $transaction->total_amount = $total_amount;
        //     $transaction->save();

        //     Cart::whereUserId(auth()->id())->delete();

        //     return redirect()->route('user.order')->with('success', 'Order place successfully');
        // }
    }

    function checkout_submit_back_transfer(Request $request)
    {
        $order = new Order();
        $transaction = new Transaction();
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $randomString = substr(str_shuffle($characters), 0, 10);

        $total_amount = Cart::whereUserId(auth()->id())->sum('sub_total');
        $product_ids = Cart::whereUserId(auth()->id())->pluck('product_id');
        $order->uuid = $randomString;
        $order->transaction_id = $request->transaction;
        $order->user_id = auth()->id();
        $order->total_amount = $total_amount;
        $order->payment_status = 'unpaid';
        $order->order_status = 'pending';
        $order->product_id = json_encode($product_ids);
        $order->payment_method = $request->payment_method;
        $order->save();

        $transaction->order_id = $order->uuid;
        $transaction->user_id = auth()->id();
        $transaction->payment_status = 'unpaid';
        $transaction->order_status = 'pending';
        $transaction->total_amount = $total_amount;
        $transaction->save();
        
        dd($transaction);
        // product details 
        // user ditals

        $pdf = PDF::loadView('invoices.invoice', $data);

        // Download the PDF file
        return $pdf->download('invoice.pdf');

        Cart::whereUserId(auth()->id())->delete();

        return redirect()->route('user.order')->with('success', 'Order place successfully');
    }

    
    public function stripe()
    {
        return  view('User.stripe');
    }

    public function generateInvoice($orderuuid)
    {
        $pdfFile='invoices/invoice-'.$orderuuid.'.pdf';
        if (Storage::exists($pdfFile)) {
          return response()->download(Storage::path($pdfFile));
        } else {
           return response()->json(['error'=>'File Not Found'],404);
        }
        
    }
}
