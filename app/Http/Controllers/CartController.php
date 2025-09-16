<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Item;
use App\Models\Variant;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $cart = Cart::firstOrCreate(
            ['user_id' => $userId, 'status' => 'active'],
            ['items' => []]
        );

        return view('cart', ['cart' => $cart]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id'    => ['required', 'integer', 'exists:items,id'],
            'variant_id' => ['nullable', 'integer', 'exists:variants,id'],
            'quantity'   => ['required', 'integer', 'min:1'],
        ]);

        $user = $request->user();

        $item = Item::with('variants')->findOrFail($validated['item_id']);

        $variant = null;
        if ($item->variants()->exists()) {
            if (empty($validated['variant_id'])) {
                return response()->json(['error' => 'Please select a variant'], 400);
            }
            $variant    = Variant::findOrFail($validated['variant_id']);
            $stock      = $variant->stock;
            $price      = $variant->price;
            $attributes = $variant->attributes;
        } else {
            $stock      = $item->stock;
            $price      = $item->price;
            $attributes = [];
        }

        if ($stock <= 0) {
            return response()->json(['error' => 'Item is out of stock'], 400);
        }

        if ($validated['quantity'] > $stock) {
            return response()->json(['error' => 'Not enough stock'], 400);
        }

        $cart = Cart::firstOrCreate(
            ['user_id' => $user->id, 'status' => 'active'],
            ['items' => []]
        );

        $items = $cart->items;
        $found = false;

        $uniqueKey = $item->id . '-' . ($variant?->id ?? 0);

        foreach ($items as &$cartItem) {
            $existingKey = $cartItem['item_id'] . '-' . ($cartItem['variant_id'] ?? 0);

            if ($existingKey === $uniqueKey) {
                $newQuantity = $cartItem['quantity'] + $validated['quantity'];

                if ($newQuantity > $stock) {
                    return response()->json(['error' => 'Cannot add more than available stock'], 400);
                }

                $cartItem['quantity'] = $newQuantity;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $items[] = [
                'item_id'    => $item->id,
                'variant_id' => $variant?->id,
                'name'       => $item->name,
                'attributes' => $attributes,
                'quantity'   => $validated['quantity'],
                'price'      => $price,
            ];
        }

        $cart->items = $items;
        $cart->save();

        return response()->json([
            'success' => true,
            'cart' => $cart
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'item_id'    => ['required', 'integer', 'exists:items,id'],
            'variant_id' => ['nullable', 'integer', 'exists:variants,id'],
            'quantity' => ['required', 'integer', 'min:0']
        ]);

        $userId = $request->user()->id;
        $cart = Cart::where('user_id', $userId)->where('status', 'active')->first();
        if (!$cart) return response()->json(['message' => 'Cart not found'], 404);

        $itemId = $request->item_id;
        $variantId = $request->variant_id ?? null;
        $items = $cart->items ?? [];
        $updated = false;

        foreach ($items as $key => &$cartItem) {
            if (
                $cartItem['item_id'] == $itemId &&
                ($cartItem['variant_id'] ?? null) == $variantId
            ) {

                $stock = $variantId
                    ? Variant::find($variantId)->stock
                    : Item::find($itemId)->stock;

                if ($request->quantity > $stock) {
                    return response()->json(['error' => 'Cannot exceed available stock'], 400);
                }

                if ($request->quantity <= 0) {
                    unset($items[$key]);
                } else {
                    $cartItem['quantity'] = $request->quantity;
                }
                $updated = true;
                break;
            }
        }

        if (!$updated) {
            return response()->json(['message' => 'Item not found in cart'], 404);
        }

        $cart->items = array_values($items);
        $cart->save();

        return response()->json([
            'success' => true,
            'cart' => $cart,
            'stock' => $stock,
            'total' => collect($cart->items)->sum(fn($i) => $i['quantity'] * $i['price']),
        ]);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'item_id'    => ['required', 'integer', 'exists:items,id'],
            'variant_id' => ['nullable', 'integer', 'exists:variants,id'],
        ]);

        $userId = $request->user()->id;
        $cart = Cart::where('user_id', $userId)->where('status', 'active')->first();
        if (!$cart) return response()->json(['message' => 'Cart not found'], 404);

        $itemId = $request->item_id;
        $variantId = $request->variant_id ?? null;
        $items = $cart->items ?? [];

        $items = array_values(array_filter($items, function ($cartItem) use ($itemId, $variantId) {
            return !($cartItem['item_id'] == $itemId &&
                ($cartItem['variant_id'] ?? null) == $variantId);
        }));

        $cart->items = $items;
        $cart->save();

        return response()->json($cart);
    }
}
