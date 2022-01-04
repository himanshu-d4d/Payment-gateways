<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe;
use Session;
use App\Models\Payment;


class StripeController extends Controller
{
    public function handleGet(){
        return view('home');
    }

    public function handlePost(Request $request)
    {
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $charge = Stripe\Charge::create ([
                "amount" => 100 * 150,
                "currency" => "inr",
                "source" => $request->stripeToken,
                "description" => "Making test payment." 
        ]);
        dd($charge);
        $payment = new Payment;
        $payment['token'] = $charge['id'];
        $payment['card_natwork'] = $charge['brand'];
        $payment['card_number'] = $charge['last4'];
        $payment['payment_type'] = "stripe";
        $payment->save();
        Session::flash('success', 'Payment has been successfully processed.');
          
        return response()->json(['status' => 'success', 'message' =>$charge],  200,);
    }

    public function googlePost(Request $request)
    {
        $goolePaydata = new Payment;
        $goolePaydata['card_number'] = $request['cardDetails'];
        $goolePaydata['card_natwork'] = $request['cardNetwork'];
        $goolePaydata['token'] = $request['token'];
        $goolePaydata['payment_type'] ="GooglePay";
        $goolePaydata->save();
        return response()->json(['status' => 'success', 'message' => $goolePaydata],  200,);
    }

    public function appleGet(){
        return view('apple');
     }

}
