<?php

use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomFieldController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\MessageController as AdminMessageController;
use App\Http\Controllers\Admin\SocialPostController;
use App\Http\Controllers\Admin\WellnessPlanController;
use App\Http\Controllers\Admin\TenantSettingsController;
use App\Http\Controllers\Patient\MessageController as PatientMessageController;
use App\Http\Controllers\Patient\PatientDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

// ─── Rutas públicas ───────────────────────────────────────────────
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/servicios', [PublicController::class, 'services'])->name('services');
Route::get('/productos', [PublicController::class, 'products'])->name('products');

// Redirección post-login según rol
Route::get('/dashboard', function () {
    if (auth()->user()->hasRole('patient')) {
        return redirect()->route('patient.dashboard');
    }
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ─── Perfil ───────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ─── Portal del Paciente ──────────────────────────────────────────
Route::middleware(['auth', 'verified', 'role:patient'])
    ->prefix('mi-cuenta')
    ->name('patient.')
    ->group(function () {
        Route::get('/', [PatientDashboardController::class, 'index'])->name('dashboard');
        Route::get('/mis-citas', [PatientDashboardController::class, 'appointments'])->name('appointments');
        Route::get('/reservar', [PatientDashboardController::class, 'book'])->name('book');
        Route::post('/reservar', [PatientDashboardController::class, 'storeAppointment'])->name('book.store');
        Route::get('/mi-plan', [PatientDashboardController::class, 'wellness'])->name('wellness');
        Route::get('/mi-plan/{plan}', [PatientDashboardController::class, 'wellnessShow'])->name('wellness.show');
        Route::get('/mi-perfil', [PatientDashboardController::class, 'profile'])->name('profile');
        Route::post('/mi-perfil', [PatientDashboardController::class, 'updateProfile'])->name('profile.update');

        // Mensajes
        Route::get('/mensajes', [PatientMessageController::class, 'index'])->name('messages');
        Route::post('/mensajes', [PatientMessageController::class, 'store'])->name('messages.store');
    });

// ─── Panel Administrativo ─────────────────────────────────────────
Route::middleware(['auth', 'verified', 'role:admin|staff'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Catálogos
        Route::resource('categories', CategoryController::class);
        Route::resource('services', ServiceController::class);
        Route::resource('products', ProductController::class);

        // Campos personalizados
        Route::resource('custom-fields', CustomFieldController::class)
            ->parameters(['custom-fields' => 'customField']);

        // Citas
        Route::resource('appointments', AppointmentController::class);
        Route::post('appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])
            ->name('appointments.status');
        Route::post('appointments/{appointment}/record', [AppointmentController::class, 'addRecord'])
            ->name('appointments.record');

        // Pacientes
        Route::resource('patients', PatientController::class)->only(['index', 'show', 'create', 'store', 'edit', 'update']);

        // Redes Sociales
        Route::get('redes-sociales', [SocialPostController::class, 'index'])->name('social-posts.index');
        Route::get('redes-sociales/configurar', [SocialPostController::class, 'settings'])->name('social-posts.settings');
        Route::post('redes-sociales/configurar', [SocialPostController::class, 'saveSettings'])->name('social-posts.settings.save');
        Route::get('redes-sociales/crear', [SocialPostController::class, 'create'])->name('social-posts.create');
        Route::post('redes-sociales', [SocialPostController::class, 'store'])->name('social-posts.store');
        Route::get('redes-sociales/{socialPost}', [SocialPostController::class, 'show'])->name('social-posts.show');
        Route::delete('redes-sociales/{socialPost}', [SocialPostController::class, 'destroy'])->name('social-posts.destroy');

        // Mensajes
        Route::get('mensajes', [AdminMessageController::class, 'index'])->name('messages.index');
        Route::get('mensajes/{patient}', [AdminMessageController::class, 'show'])->name('messages.show');
        Route::post('mensajes/{patient}', [AdminMessageController::class, 'store'])->name('messages.store');

        // Planes de bienestar IA
        Route::resource('wellness', WellnessPlanController::class)
            ->parameters(['wellness' => 'wellness']);
        Route::post('wellness/generate', [WellnessPlanController::class, 'generate'])
            ->name('wellness.generate');
        Route::post('wellness/{wellness}/status', [WellnessPlanController::class, 'updateStatus'])
            ->name('wellness.status');

        // Configuración de la clínica
        Route::get('configuracion', [TenantSettingsController::class, 'edit'])->name('settings.edit');
        Route::post('configuracion', [TenantSettingsController::class, 'update'])->name('settings.update');
    });

require __DIR__ . '/auth.php';
