<?php

namespace App\Http\Controllers;

use App\Helpers\ShippingHelper;
use App\Helpers\StripeCheckout;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Support\Facades\Auth;

class CheckoutPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($payment)
    {

        //check if the user is authenticated and which group the user belongs to
        $group_ids = Auth::check() ? Auth::user()->getGroups() : [1];

        //get user who is logged in
        $user = Auth::user();

        //create variables
        $shipping_helper = new ShippingHelper();
        $stripe_checkout = new StripeCheckout();
        $order = new Order();
        $insert_data = [];
        $completed = false;

        //get products that are located in cart page
        $cart_data = $user->products()->withPrices()->get();

        //check if cart is empty, if so user will be returned to cart page
        if($cart_data->isEmpty()){
            return redirect()->route('cart.index')->with('message','Your cart is empty');
        }

        //calculate subtotal
        $cart_data->calculateSubtotal();

        //determine payment
        switch ($payment) {
            case 'stripe':
                $stripe_checkout->startCheckoutSession();
                $stripe_checkout->addEmail($user->email);
                $stripe_checkout->addProducts($cart_data);
                $stripe_checkout->addPointsCoupon();
                $stripe_checkout->enablePromoCodes();
                $shipping_data = $shipping_helper->getGroupShippingOptions();
                $stripe_checkout->addShippingOptions($shipping_data);
                $stripe_checkout->createSession();
                $insert_data = $stripe_checkout->getOrderCreateData();
                $completed = true;


                break;

            default:
                $insert_data = [
                    'payment_provider' => 'testing',
                    'payment_id' => 'testing',
                ];
                $completed = true;
                break;
        }

        //validate
        if(!$completed || empty($insert_data)){
            dd('Payment incomplete or checkout is missing');
        }

        //create order
        $order->user_id = $user->id;
        $order->order_no = '123';
        $order->subtotal = $cart_data->getSubtotal();
        $order->total = $cart_data->getTotal();
        $order->payment_provider = $insert_data['payment_provider'];
        $order->payment_id = $insert_data['payment_id'];
        $order->shipping_id = 1;
        $order->shipping_address_id = 1;
        $order->billing_address_id = 1;
        $order->payment_status = 'unpaid';
        $order->save();

        //create order details
        $records = [];

        foreach ($cart_data as $data) {
            array_push(
                $records,
                new OrderProduct(
                    [
                    'product_id' => $data->id,
                    'user_id' => $user->id,
                    'price' => $data->getPrice(),
                    'quantity' => $data->pivot->quantity,
                    ]
                    )
            );
        }

        $order->order_products()->saveMany($records);


        //redirect
        if ($payment == 'stripe'){
            return redirect($stripe_checkout->getUrl());
        }


        dd('Payment was successful during testing');

    }
}
