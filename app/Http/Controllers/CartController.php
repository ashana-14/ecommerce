<?php

namespace App\Http\Controllers;

use App\Models\Cart;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //check if the user is authenticated and which group the user belongs to
        $group_ids = Auth::check() ? Auth::user()->getGroups() : [1];

        //gets user and stores in a variable
        $user = Auth::user();

        //gets all products user added to cart
        $cart_data = $user->products()->withPrices()->get();

        //calculate all products in cart
        $cart_data->calculateSubtotal();

        return view('pages.default.cartpage', compact('cart_data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //checks for the product added to the cart by user. If found, quantity would be updated; however, data will be inserted if its not found
        Cart::updateOrCreate(
            ['user_id' => Auth::id(), 'product_id' => $request->product_id],
            ['quantity' => DB::raw('quantity + ' . $request->quantity), 'update_at'=> now()]
        );

        //Sends a message to user that item(s) were added to cart
        return redirect()->route('cart.index')->with('message', 'Product added to cart');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //product is removed from cart
        Cart::destroy($id);

        return redirect()->route('cart.index')->with('message', 'Product removed from cart');
    }


    public function addToCartFromStore(Request $request)
    {
        //increases product by 1 based on quantity selected by user
        Cart::updateOrCreate(
            ['user_id' => Auth::id(), 'product_id' => $request->id],
            ['quantity' => DB::raw('quantity + ' . 1), 'update_at'=> now()]
        );

        return redirect()->route('cart.index')->with('message', 'Product added to cart');

    }

}
