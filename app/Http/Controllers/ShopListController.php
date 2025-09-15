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
        $query = Item::with('category', 'variants');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('sku', 'LIKE', "%{$search}%")
                    ->orWhere('name', 'LIKE', "%{$search}%")
                    ->orWhereHas('category', fn($q2) => $q2->where('name', 'LIKE', "%{$search}%"));
            });
        }

        if ($category = $request->input('category')) {
            $query->where('category_id', $category);
        }

        $items = $query->paginate(12)->withQueryString();
        $categories = Category::pluck('name', 'id');

        return view('index', compact('items', 'categories'));
    }

    public function items(Request $request)
    {
        $query = Item::with('category', 'variants');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('sku', 'LIKE', "%{$search}%")
                    ->orWhere('name', 'LIKE', "%{$search}%")
                    ->orWhereHas('category', fn($q2) => $q2->where('name', 'LIKE', "%{$search}%"));
            });
        }

        if ($category = $request->input('category')) {
            $query->where('category_id', $category);
        }

        $items = $query->paginate(12)->withQueryString();
        $categories = Category::pluck('name', 'id');

        return view('components.item-list', compact('items', 'categories'))->render();
    }
}
