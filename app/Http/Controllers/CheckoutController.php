<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{

    public function index(){

        //check if user is logged in and which groups belong to
        $group_ids = Auth::check() ? Auth::user()->getGroups() : [1];

       //gets user and stores in a variable
        $user = Auth::user();

        //gets all products user added to cart
        $cart_data = $user->products()->withPrices()->get();

        //check if the cart is empty
        if($cart_data->isEmpty()){
            return redirect()->route('cart.index')->with('message','Your cart is empty');
        }

        //calculate subtotal of items selected in cart
        $cart_data->calculateSubtotal();

        //load checkout page
        return view('pages.default.checkoutpage',compact('cart_data'));


    }


}
