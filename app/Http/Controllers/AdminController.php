<?php

namespace App\Http\Controllers;

use App\Models\ProfileLink;
use App\Models\User;
use App\Models\UserProfile;
use App\Support\ProductCatalog;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $users = User::with('profile')
            // ->withCount('profile')
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

        $products = array_values(ProductCatalog::all());

        return view('admin.dashboard', [
            'users' => $users,
            'profiles' => $profiles,
            'latestLinks' => $latestLinks,
            'products' => $products,
        ]);
    }
}
