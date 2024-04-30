<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class DonationController extends Controller
{

    public function createCheckoutSession(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $checkout_session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Donation',
                    ],
                    'unit_amount' => $request->amount * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => $request->recurring ? 'subscription' : 'payment',
            'success_url' => 'https://requiem.moe/?donate&session_id={CHECKOUT_SESSION_ID}&success=true',
            'cancel_url' => 'https://requiem.moe/',
        ]);

        return response()->json(['id' => $checkout_session->id]);
    }
}