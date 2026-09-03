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
    // var_dump($homeProducts);
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
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::post('/profile/links', [ProfileController::class, 'addLink'])->name('profile.links.add');
    Route::delete('/profile/links/{link}', [ProfileController::class, 'removeLink'])->name('profile.links.remove');

    Route::prefix('corporate/cards')->name('corporate.cards.')->group(function () {
        Route::get('/', [CorporateCardController::class, 'index'])->name('index');
        Route::post('/order', [CorporateCardController::class, 'order'])->name('order');
        Route::post('/reorder', [CorporateCardController::class, 'reorder'])->name('reorder');
        Route::post('/{cardId}/profile', [CorporateCardController::class, 'updateProfile'])->name('profile.update');
        Route::post('/employees/{user}/deactivate', [CorporateCardController::class, 'deactivateEmployee'])->name('employees.deactivate');
        Route::delete('/employees/{user}', [CorporateCardController::class, 'deleteEmployee'])->name('employees.delete');
    });
});

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::post('/notifications/{notification}/read', [AdminController::class, 'markNotificationRead'])->name('notifications.read');
        Route::get('/cards', [CardGenerationController::class, 'index'])->name('cards.index');
        Route::post('/cards/generate', [CardGenerationController::class, 'generate'])->name('cards.generate');
        Route::put('/cards/{card}', [CardGenerationController::class, 'update'])->name('cards.update');
        Route::get('/corporate-admins/create', [AdminController::class, 'createCorporateAdmin'])->name('corporate-admins.create');
        Route::post('/corporate-admins', [AdminController::class, 'storeCorporateAdmin'])->name('corporate-admins.store');
        Route::post('/corporate-admins/{admin}/add-cards', [AdminController::class, 'addCardsToCorporateAdmin'])->name('corporate-admins.add-cards');
        Route::post('/corporate-admins/{admin}/toggle', [AdminController::class, 'toggleCorporateAdmin'])->name('corporate-admins.toggle');
        Route::delete('/corporate-admins/{admin}', [AdminController::class, 'destroyCorporateAdmin'])->name('corporate-admins.destroy');
        Route::post('/users/{user}/duplicate', [AdminController::class, 'duplicateUser'])->name('users.duplicate');
        Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.destroy');
        Route::post('/users/{user}/profile-builder/toggle', [AdminController::class, 'toggleProfileBuilder'])->name('users.profile-builder.toggle');
        Route::post('/users/qr/download', [AdminController::class, 'downloadSelectedProfileQrs'])->name('users.qr.download');
        Route::get('/users/{user}/profile/qr', [AdminController::class, 'downloadProfileQr'])->name('users.profile.qr.download');
        Route::get('/users/{user}/profile/edit', [ProfileController::class, 'editAdminUserProfile'])->name('users.profile.edit');
        Route::post('/users/{user}/profile', [ProfileController::class, 'updateUserProfile'])->name('users.profile.update');
        Route::post('/users/{user}/profile/links', [ProfileController::class, 'addUserProfileLink'])->name('users.profile.links.add');
        Route::delete('/users/{user}/profile/links/{link}', [ProfileController::class, 'removeUserProfileLink'])->name('users.profile.links.remove');

        Route::resource('products', ProductController::class)->except(['show']);
        Route::get('/profileedit/{cardId}', [ProfileController::class, 'editUserProfile'])->name('profile.edituserprofile');
    });


Route::get('/profile/{cardId}', [ProfileController::class, 'showPublic'])->name('profile.public.alias');
Route::get('/p/{cardId}', [ProfileController::class, 'showPublic'])->name('profile.public');
Route::post('/p/{cardId}/share', [ProfileController::class, 'sharePublicProfile'])->name('profile.public.share');

Route::get('/test-zip', function () {
    return [
        'php_version' => PHP_VERSION,
        'zip_loaded' => extension_loaded('zip'),
        'zip_class' => class_exists('ZipArchive'),
    ];
});
