<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShopListController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::query();

        $items = $query->paginate(12)->withQueryString();

        $categories = Category::pluck('name');

        return view("shop", compact("items", "categories"));
    }
}
