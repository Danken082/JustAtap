<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\GuestCartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CorporateCardController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CardGenerationController;

Route::resource('cards', CardGenerationController::class);
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $homeProducts = Product::with(['colors', 'sizes', 'images'])
        ->where('is_active', true)
        ->latest()
        ->take(3)
        ->get()
        ->map(fn (Product $product) => $product->toCatalogArray());

    return view('welcome', ['homeProducts' => $homeProducts]);
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.attempt');
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');

    Route::get('/shop', [GuestCartController::class, 'index'])->name('shop.index');
    Route::get('/cart', [GuestCartController::class, 'showCart'])->name('cart.index');
    Route::post('/cart/add/{product}', [GuestCartController::class, 'add'])->name('cart.add');
    Route::post('/cart/remove/{product}', [GuestCartController::class, 'remove'])->name('cart.remove');
    Route::post('/checkout', [GuestCartController::class, 'checkout'])->name('cart.checkout');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/edit', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/personal-info', [ProfileController::class, 'updatePersonalInfo'])->name('profile.personal-info.update');
    Route::post('/profile/links', [ProfileController::class, 'addLink'])->name('profile.links.add');
    Route::delete('/profile/links/{link}', [ProfileController::class, 'removeLink'])->name('profile.links.remove');

    Route::prefix('corporate/cards')->name('corporate.cards.')->group(function () {
        Route::get('/', [CorporateCardController::class, 'index'])->name('index');
        Route::post('/order', [CorporateCardController::class, 'order'])->name('order');
        Route::post('/{cardId}/profile', [CorporateCardController::class, 'updateProfile'])->name('profile.update');
    });
});

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/cards', [CardGenerationController::class, 'index'])->name('cards.index');
        Route::post('/cards/generate', [CardGenerationController::class, 'generate'])->name('cards.generate');
        Route::put('/cards/{card}', [CardGenerationController::class, 'update'])->name('cards.update');
        Route::post('/users/{user}/duplicate', [AdminController::class, 'duplicateUser'])->name('users.duplicate');
        Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.destroy');
        Route::post('/users/{user}/profile-builder/toggle', [AdminController::class, 'toggleProfileBuilder'])->name('users.profile-builder.toggle');
        Route::get('/users/{user}/profile/edit', [ProfileController::class, 'editAdminUserProfile'])->name('users.profile.edit');
        Route::post('/users/{user}/profile', [ProfileController::class, 'updateUserProfile'])->name('users.profile.update');
        Route::post('/users/{user}/profile/links', [ProfileController::class, 'addUserProfileLink'])->name('users.profile.links.add');
        Route::delete('/users/{user}/profile/links/{link}', [ProfileController::class, 'removeUserProfileLink'])->name('users.profile.links.remove');

        Route::resource('products', ProductController::class)->except(['show']);
        Route::get('/profileedit/{cardId}', [ProfileController::class, 'editUserProfile'])->name('profile.edituserprofile');
    });


Route::get('/p/{cardId}', [ProfileController::class, 'showPublic'])->name('profile.public');
