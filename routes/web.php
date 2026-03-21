<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CompanySettingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentRequestController;
use App\Http\Controllers\Portal\AuthController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Website\HomeController;
use Laravel\Cashier\Http\Controllers\WebhookController;

use App\Http\Controllers\Owner\OwnerDashboardController;
use App\Http\Controllers\Owner\OwnerNotificationController;
use App\Http\Controllers\Owner\OwnerPlanController;
use App\Http\Controllers\Owner\OwnerSettingController;
use App\Http\Controllers\Owner\OwnerSubscriptionController;
use App\Http\Controllers\Owner\OwnerSupportController;
use App\Http\Controllers\Owner\OwnerUserController;


// Website Routes
Route::get('/', [HomeController::class, 'index'])->name('pricing');
Route::post('/select-plan', [HomeController::class, 'select'])->name('select.plan')->middleware('throttle:10,1');

Route::view('/features', 'website.features')->name('features');
Route::view('/pricing-page', 'website.pricing')->name('pricing.page');
Route::view('/how-it-works', 'website.how')->name('how.it.works');
Route::view('/blog', 'website.blog')->name('blog');

Route::prefix('legal')->name('legal.')->group(function () {

    Route::view('/terms', 'legal.terms')->name('terms');
    Route::view('/privacy', 'legal.privacy')->name('privacy');
});

// Stripe Webhook (No Auth Required)
Route::post('/stripe/webhook', [WebhookController::class, 'handleWebhook']);


// Checkout
Route::middleware(['auth'])->group(function () {
    Route::get('/subscriptions/checkout', [SubscriptionController::class, 'checkout'])->name('subscriptions.checkout');
});


// Tenant Routes
Route::middleware(['auth', 'active', 'verified', 'subscription', 'tenant'])->group(function () {

    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('/subscriptions/portal', [SubscriptionController::class, 'portal'])->name('subscriptions.portal');
    Route::post('/subscriptions/swap', [SubscriptionController::class, 'swap'])->name('subscriptions.swap');
    Route::post('/subscriptions/cancel', [SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
    Route::post('/subscriptions/resume', [SubscriptionController::class, 'resume'])->name('subscriptions.resume');
    Route::get('/subscriptions/invoices', [SubscriptionController::class, 'invoices'])->name('subscriptions.invoices');

    // Payment Method Management
    Route::post('/billing/default', [SubscriptionController::class, 'setDefaultPaymentMethod'])->name('billing.default');
    Route::delete('/billing/remove', [SubscriptionController::class, 'removePaymentMethod'])->name('billing.remove');

    // Route::get('/subscriptions/invoices/{invoice}', [SubscriptionController::class, 'downloadInvoice'])->name('subscriptions.invoice.download');



    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User Management Routes
    Route::delete('/users/bulk-delete', [UserController::class, 'bulkDelete'])->name('users.bulk-delete');
    route::resource('/users', UserController::class);

    // Client Management Routes
    Route::delete('/clients/bulk-delete', [ClientController::class, 'bulkDelete'])->name('clients.bulk-delete');
    Route::resource('clients', ClientController::class);

    // Template Management Routes
    Route::post('/templates/{template}/duplicate', [TemplateController::class, 'duplicate'])->name('templates.duplicate');
    Route::resource('templates', TemplateController::class);

    // Document Request Management Routes
    Route::post('document-requests/{documentRequest}/send', [DocumentRequestController::class, 'send'])->name('document-requests.send');
    Route::post('document-requests/{documentRequest}/resend', [DocumentRequestController::class, 'resend'])->name('document-requests.resend');

    Route::post('/document-requests/{documentRequest}/link', [DocumentRequestController::class, 'generateLink'])->name('document-requests.link');
    Route::get('/requests/download/{upload}', [DocumentRequestController::class, 'download'])->name('requests.download');
    Route::get('/requests/{request}/download-all', [DocumentRequestController::class, 'downloadAll'])->name('requests.downloadAll');

    Route::resource('document-requests', DocumentRequestController::class);
    //
    Route::get('/company-settings', [CompanySettingController::class, 'show'])->name('company-settings.show');
    Route::post('/company-settings', [CompanySettingController::class, 'store'])->name('company-settings.store');
    Route::put('/company-settings', [CompanySettingController::class, 'update'])->name('company-settings.update');
    // 
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('roles/manage', [RoleController::class, 'manage'])->name('roles.manage');
    Route::post('roles/manage', [RoleController::class, 'updatePermissions'])->name('roles.manage.update');


    Route::get('/support', [SupportController::class, 'index'])->name('support.index');
    Route::post('/support', [SupportController::class, 'store'])->name('support.store');



    Route::get('/settings/2mfa', function () {
        return view('auth.two-factor');
    })->name('settings.2mfa');
});


/*
|--------------------------------------------------------------------------     
| Client Portal Routes (No Auth, Token-Based)
|-------------------------------------------------------------------------- 
*/

Route::get('/portal/{token}', [PortalController::class, 'access'])->middleware('throttle:portal-access')->name('portal.access');
Route::post('/portal/{token}/upload', [PortalController::class, 'upload'])->middleware('throttle:portal-uploads')->name('portal.upload');
Route::get('/client/activate/{token}', [ClientController::class, 'activate'])->name('client.activate');

/*
|--------------------------------------------------------------------------
| Portal Authentication Routes
|--------------------------------------------------------------------------
*/

Route::prefix('portal')->name('portal.')->group(function () {

    // Auth
    Route::get('{token}/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('{token}/login', [AuthController::class, 'login']);

    Route::get('{token}/activate', [AuthController::class, 'showActivate'])
        ->name('activate');

    Route::post('{token}/activate', [AuthController::class, 'activate']);

    Route::post('logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::delete('/delete/{id}', [PortalController::class, 'deleteUpload'])
        ->name('delete');
});



/*
|--------------------------------------------------------------------------
| Onwer Routes
|--------------------------------------------------------------------------
*/
Route::prefix('owner')->name('owner.')->middleware(['auth', 'verified', 'role:owner'])->group(function () {

    Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');

    Route::delete('users/bulk-delete', [OwnerUserController::class, 'bulkDelete'])->name('users.bulk-delete');
    Route::resource('users', OwnerUserController::class);

    Route::resource('plans', OwnerPlanController::class);

    Route::resource('subscriptions', OwnerSubscriptionController::class);

    Route::resource('supports', OwnerSupportController::class);

    Route::get('/notifications', [OwnerNotificationController::class, 'index'])->name('notifications');

    Route::get('/settings', [OwnerSettingController::class, 'index'])->name('settings');

    Route::post('/settings', [OwnerSettingController::class, 'update'])->name('settings.update');
});
