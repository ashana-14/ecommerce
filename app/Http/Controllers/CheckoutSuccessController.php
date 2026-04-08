<?php

namespace App\Http\Controllers;

use App\Events\OrderPaid;
use App\Helpers\StripeCheckoutSuccess;
use App\Helpers\TierHelper;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class CheckoutSuccessController extends Controller
{
    public function index($id)
    {
        $stripe_checkout = new StripeCheckoutSuccess();
        $successful = $stripe_checkout->updateCheckoutOrder($id);
        if (!$successful) {
            abort(404);
        }

        // FEATURE::Tiers - Event/Listener to update user after an order
        $order = Order::findOrFail($stripe_checkout->order_id);
        OrderPaid::dispatch($order);

        // FEATURE::Tiers - Checkout success
        $tier_helper = new TierHelper(Auth::user()->load('tier'));
        $tier_helper->checkTierProgress();

        return view('pages.default.checkout-successpage', compact('tier_helper'));

    }




}
