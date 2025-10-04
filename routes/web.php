<?php





use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ApprovalRuleController;

Route::get('/', function () {
    return view('landing'); // custom login front view (landing)
})->name('landing');

Route::middleware(['auth'])->group(function(){
    Route::get('/dashboard', function(){
        return view('dashboard');
    })->name('dashboard');

    Route::resource('expenses', ExpenseController::class)->only(['index','create','store','show']);
    Route::get('approvals', [ApprovalController::class,'index'])->name('approvals.index');
    Route::post('approvals/{expense}/approve', [ApprovalController::class,'approve'])->name('approvals.approve');
    Route::post('approvals/{expense}/reject', [ApprovalController::class,'reject'])->name('approvals.reject');

    Route::prefix('admin')->middleware('admin')->group(function(){
        Route::get('users/create', [AdminUserController::class,'create'])->name('admin.users.create');
        Route::post('users', [AdminUserController::class,'store'])->name('admin.users.store');

        Route::get('rules/create', [ApprovalRuleController::class,'create'])->name('admin.rules.create');
        Route::post('rules', [ApprovalRuleController::class,'store'])->name('admin.rules.store');
    });
});

require __DIR__.'/auth.php';


// use App\Http\Controllers\ProfileController;
// use Illuminate\Support\Facades\Route;

// /*
// |--------------------------------------------------------------------------
// | Web Routes
// |--------------------------------------------------------------------------
// |
// | Here is where you can register web routes for your application. These
// | routes are loaded by the RouteServiceProvider and all of them will
// | be assigned to the "web" middleware group. Make something great!
// |
// */

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// require __DIR__.'/auth.php';
