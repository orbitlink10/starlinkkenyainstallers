<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    private const WHATSAPP_PHONE = '254700123456';

    public function show(Request $request, Product $product): View
    {
        abort_unless($product->is_active, 404);

        $imageUrl = $product->image_path
            ? asset('storage/'.$product->image_path)
            : 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?auto=format&fit=crop&w=1200&q=80';

        return view('shop.product', [
            'product' => $product,
            'imageUrl' => $imageUrl,
            'cartCount' => $this->cartCount($request),
            'whatsappUrl' => $this->whatsappUrlForProduct($product),
        ]);
    }

    public function addToCart(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->is_active, 404);

        $validated = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        $quantityToAdd = (int) ($validated['quantity'] ?? 1);
        $stockLimit = (int) ($product->quantity ?? $product->stock ?? 0);

        $cart = $request->session()->get('cart', []);
        $itemKey = (string) $product->id;
        $existingQuantity = (int) ($cart[$itemKey]['quantity'] ?? 0);
        $newQuantity = $existingQuantity + $quantityToAdd;

        if ($stockLimit > 0) {
            $newQuantity = min($newQuantity, $stockLimit);
        }

        $cart[$itemKey] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'price' => (float) $product->price,
            'quantity' => $newQuantity,
            'image_path' => $product->image_path,
        ];

        $request->session()->put('cart', $cart);

        return redirect()
            ->route('shop.product.show', $product)
            ->with('success', $product->name.' added to cart.');
    }

    public function cart(Request $request): View
    {
        $cart = $request->session()->get('cart', []);
        $items = collect(array_values($cart))->map(function (array $item): array {
            $item['line_total'] = (float) $item['price'] * (int) $item['quantity'];
            $item['image_url'] = !empty($item['image_path'])
                ? asset('storage/'.$item['image_path'])
                : 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?auto=format&fit=crop&w=720&q=80';

            return $item;
        });

        $total = (float) $items->sum('line_total');

        return view('shop.cart', [
            'items' => $items,
            'total' => $total,
            'cartCount' => $this->cartCount($request),
            'checkoutWhatsappUrl' => $this->whatsappUrlForCart($items, $total),
        ]);
    }

    public function removeFromCart(Request $request, Product $product): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        unset($cart[(string) $product->id]);
        $request->session()->put('cart', $cart);

        return redirect()
            ->route('shop.cart.index')
            ->with('success', $product->name.' removed from cart.');
    }

    private function cartCount(Request $request): int
    {
        $cart = $request->session()->get('cart', []);

        return (int) collect($cart)->sum(fn (array $item): int => (int) ($item['quantity'] ?? 0));
    }

    private function whatsappUrlForProduct(Product $product): string
    {
        $message = sprintf(
            'Hello, I want to order %s at KES %s.',
            $product->name,
            number_format((float) $product->price, 2)
        );

        return 'https://wa.me/'.self::WHATSAPP_PHONE.'?text='.urlencode($message);
    }

    private function whatsappUrlForCart($items, float $total): string
    {
        if ($items->isEmpty()) {
            return 'https://wa.me/'.self::WHATSAPP_PHONE;
        }

        $lines = $items->map(
            fn (array $item): string => sprintf(
                '- %s x%d (KES %s)',
                $item['name'],
                (int) $item['quantity'],
                number_format((float) $item['line_total'], 2)
            )
        )->implode("\n");

        $message = "Hello, I want to order:\n".$lines."\nTotal: KES ".number_format($total, 2);

        return 'https://wa.me/'.self::WHATSAPP_PHONE.'?text='.urlencode($message);
    }
}

