<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProfileLink;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $users = User::with('profile')
            ->withCount('profile')
            ->latest()
            ->get();

        $profiles = UserProfile::with('user')
            ->withCount('links')
            ->latest()
            ->get();

        $latestLinks = ProfileLink::with('profile.user')
            ->latest()
            ->limit(50)
            ->get();

        $products = Product::with(['colors', 'sizes'])
            ->latest()
            ->get()
            ->map(fn (Product $product) => $product->toCatalogArray())
            ->all();

        return view('admin.dashboard', [
            'users' => $users,
            'profiles' => $profiles,
            'latestLinks' => $latestLinks,
            'products' => $products,
        ]);
    }
}
