<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{


    public function index()
    {
        // check if the user is authenticated and which group the user belongs to
        $group_ids = Auth::check() ? Auth::user()->getGroups() : [1];

        // get all products from the product table. Store the information in a variable named product_data
        $product_data = Product::withPrices()->get();

        // pass the information to the page named productspage
        return view('pages.default.productspage', compact('product_data'));

    }
}
