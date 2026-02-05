<?php

namespace App\Http\Controllers;

use App\Helpers\StripeCheckoutSuccess;

class CheckoutSuccessController extends Controller
{
    public function index($id)
    {
        $stripe_checkout = new StripeCheckoutSuccess();
        $successful = $stripe_checkout->updateCheckoutOrder($id);
        if (!$successful) {
            abort(404);
        }

        return view('pages.default.checkout-successpage');

    }




}
