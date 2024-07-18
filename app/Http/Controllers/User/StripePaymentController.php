<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe;

class StripePaymentController extends Controller
{
    public function stripe()
    {
        return view('user.stripe');
    }
}
