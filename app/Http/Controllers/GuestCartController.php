<?php

namespace App\Http\Controllers;

use App\Mail\GuestCheckoutSummaryMail;
use App\Notifications\NewProductOrderNotification;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class GuestCartController extends Controller
{
    private const CART_SESSION_KEY = 'guest_cart';

    /**
     * @return array<string, array<string, mixed>>
     */
    private function products(): array
    {
        return Product::with(['colors', 'sizes', 'images'])
            ->where('is_active', true)
            ->get()
            ->mapWithKeys(fn (Product $product) => [(string) $product->id => $product->toCatalogArray()])
            ->all();
    }

    private function buildVariantKey(string $productId, string $color, string $size): string
    {
        return $productId.'::'.$color.'::'.$size;
    }

    /**
     * @return array{product_id: string, color: string, size: string}|null
     */
    private function parseVariantKey(string $variantKey): ?array
    {
        $parts = explode('::', $variantKey, 3);

        if (count($parts) !== 3) {
            return null;
        }

        return [
            'product_id' => $parts[0],
            'color' => $parts[1],
            'size' => $parts[2],
        ];
    }

    /**
     * @return array<string, int>
     */
    private function cartFromSession(Request $request): array
    {
        return $request->session()->get(self::CART_SESSION_KEY, []);
    }

    /**
     * @return array{items: array<int, array<string, mixed>>, total: float, count: int}
     */
    private function cartSummary(Request $request): array
    {
        $products = $this->products();
        $cart = $this->cartFromSession($request);

        $items = [];
        $total = 0.0;
        $count = 0;

        foreach ($cart as $variantKey => $quantity) {
            $parsed = $this->parseVariantKey((string) $variantKey);

            if ($parsed === null) {
                if (! isset($products[(string) $variantKey])) {
                    continue;
                }

                $legacyProduct = $products[(string) $variantKey];
                $parsed = [
                    'product_id' => (string) $variantKey,
                    'color' => (string) ($legacyProduct['colors'][0] ?? 'Default'),
                    'size' => (string) ($legacyProduct['sizes'][0] ?? 'Standard'),
                ];
            }

            if (! isset($products[$parsed['product_id']])) {
                continue;
            }

            $product = $products[$parsed['product_id']];
            $qty = max((int) $quantity, 0);

            if ($qty === 0) {
                continue;
            }

            $lineTotal = $product['price'] * $qty;
            $total += $lineTotal;
            $count += $qty;

            $items[] = [
                'id' => $product['id'],
                'variant_key' => (string) $variantKey,
                'name' => $product['name'],
                'price' => $product['price'],
                'color' => $parsed['color'],
                'size' => $parsed['size'],
                'quantity' => $qty,
                'line_total' => $lineTotal,
            ];
        }

        return [
            'items' => $items,
            'total' => $total,
            'count' => $count,
        ];
    }

    public function index(Request $request): View
    {
        $summary = $this->cartSummary($request);
        $products = array_values($this->products());
        $categories = collect($products)
            ->pluck('category')
            ->unique()
            ->values()
            ->all();

        return view('shop.index', [
            'products' => $products,
            'categories' => $categories,
            'cartCount' => $summary['count'],
        ]);
    }

    public function showCart(Request $request): View
    {
        $summary = $this->cartSummary($request);

        return view('shop.cart', [
            'items' => $summary['items'],
            'total' => $summary['total'],
            'cartCount' => $summary['count'],
        ]);
    }

    public function add(Request $request, string $product): RedirectResponse
    {
        $products = $this->products();

        if (! isset($products[$product])) {
            return back()->withErrors(['cart' => 'Product not found.']);
        }

        $selectedColor = (string) $request->input('color', '');
        $selectedSize = (string) $request->input('size', '');

        $request->validate([
            'color' => ['required', Rule::in($products[$product]['colors'])],
            'size' => ['required', Rule::in($products[$product]['sizes'])],
        ]);

        $variantKey = $this->buildVariantKey($product, $selectedColor, $selectedSize);
        $cart = $this->cartFromSession($request);
        $cart[$variantKey] = ((int) ($cart[$variantKey] ?? 0)) + 1;

        $request->session()->put(self::CART_SESSION_KEY, $cart);

        return back()->with('status', $products[$product]['name'].' ('.$selectedColor.', '.$selectedSize.') added to cart.');
    }

    public function remove(Request $request, string $product): RedirectResponse
    {
        $cart = $this->cartFromSession($request);

        $color = (string) $request->input('color', '');
        $size = (string) $request->input('size', '');
        $variantKey = $this->buildVariantKey($product, $color, $size);

        if (! isset($cart[$variantKey])) {
            foreach (array_keys($cart) as $key) {
                $parsed = $this->parseVariantKey((string) $key);

                if ($parsed !== null && $parsed['product_id'] === $product) {
                    $variantKey = (string) $key;
                    break;
                }
            }
        }

        if (! isset($cart[$variantKey])) {
            return back();
        }

        $cart[$variantKey] = max(((int) $cart[$variantKey]) - 1, 0);

        if ($cart[$variantKey] === 0) {
            unset($cart[$variantKey]);
        }

        $request->session()->put(self::CART_SESSION_KEY, $cart);

        return back()->with('status', 'Cart updated.');
    }

    public function checkout(Request $request): RedirectResponse
    {
        $summary = $this->cartSummary($request);

        if ($summary['count'] === 0) {
            return back()->withErrors(['cart' => 'Your cart is empty.']);
        }

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'min:2'],
            'customer_email' => ['required', 'email'],
        ]);

        $customerName = trim((string) $validated['customer_name']);
        $customerEmail = trim((string) $validated['customer_email']);
        $adminEmails = config('app.admin_emails');

        if (empty($adminEmails)) {
            $adminEmails = [config('mail.from.address', env('MAIL_FROM_ADDRESS', 'hello@example.com'))];
        }

        try {
            Mail::to($customerEmail, $customerName)->send(
                new \App\Mail\GuestOrderReceiptMail($summary['items'], $summary['total'], $customerName, $customerEmail)
            );

            foreach ($adminEmails as $adminEmail) {
                Mail::to($adminEmail)->send(
                    new GuestCheckoutSummaryMail($summary['items'], $summary['total'], $customerName, $customerEmail)
                );
            }

            User::whereIn('email', $adminEmails)->get()->each(function (User $admin) use ($summary, $customerName, $customerEmail): void {
                $admin->notify(new NewProductOrderNotification(
                    $customerName,
                    $customerEmail,
                    $summary['items'],
                    (float) $summary['total'],
                ));
            });
        } catch (\Throwable $exception) {
            report($exception);

            return back()
                ->withInput()
                ->withErrors(['cart' => 'Order could not be emailed right now. Please check mail settings and try again.']);
        }

        $request->session()->forget(self::CART_SESSION_KEY);

        return redirect()->route('cart.index')->with('status', 'Checkout submitted. Receipt sent to '.$customerEmail.' and order details sent to '.implode(', ', $adminEmails).'.');
    }
}
