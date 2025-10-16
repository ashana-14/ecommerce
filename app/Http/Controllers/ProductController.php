<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $group_ids = Auth::check() ? Auth::user()->getGroups() : [1];

        $product_data = Product::withPrices()->get();

        return view('pages.testing.productspage', compact('product_data'));

    }
}
