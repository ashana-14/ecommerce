<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class DetailController extends Controller
{

    public function index($id)
    {

        // check if the user is authenticated and which group the user belongs to
        $group_id = Auth::check() ? Auth::user()->getGroups() : [1];

        // gets one product from the product table based on product id
        $data = Product::singleProduct($id)->withPrices()->get()->first();

        // pass the information to the page name details page
        return view('pages.default.detailspage', compact('data'));

    }
}



