<?php

use Illuminate\Support\Facades\Route;


// // List all controllers
// Route::get('controller', function () {
//     $directory = app_path('Http/Controllers');
//     $controllers = [];
//     $files = File::allFiles($directory);
//     foreach ($files as $file) {
//         if ($file->getExtension() === 'php') {
//             $relativePath = str_replace($directory . DIRECTORY_SEPARATOR, '', $file->getPathname());
//             $controllerName = str_replace(['/', '\\'], '\\', substr($relativePath, 0, -4));
//             $controllers[] = $controllerName;
//         }
//     }
//     return response()->json($controllers);
// });

// // List all models
// Route::get('model', function () {
//     $directory = app_path('Models');
//     $models = [];
//     if (File::exists($directory)) {
//         $files = File::allFiles($directory);
//         foreach ($files as $file) {
//             if ($file->getExtension() === 'php') {
//                 $relativePath = str_replace($directory . DIRECTORY_SEPARATOR, '', $file->getPathname());
//                 $modelName = str_replace(['/', '\\'], '\\', substr($relativePath, 0, -4));
//                 $models[] = $modelName;
//             }
//         }
//     }
//     return response()->json($models);
// });

// // List all views
// Route::get('views', function () {
//     $directory = resource_path('views/admin/pages');
//     $views = [];
//     if (File::exists($directory)) {
//         $files = File::allFiles($directory);
//         foreach ($files as $file) {
//             $extension = $file->getExtension();
//             if ($extension === 'php' || $extension === 'json') {
//                 $relativePath = str_replace($directory . DIRECTORY_SEPARATOR, '', $file->getPathname());
//                 if ($extension === 'php' && substr($relativePath, -10) === '.blade.php') {
//                     $viewName = str_replace(['/', '\\'], '.', substr($relativePath, 0, -10));
//                 } elseif ($extension === 'json') {
//                     $viewName = str_replace(['/', '\\'], '.', substr($relativePath, 0, -5));
//                 } else {
//                     continue;
//                 }
//                 $views[] = $viewName;
//             }
//         }
//     }
//     return response()->json($views);
// });

// // List all migrations
// Route::get('migrations', function () {
//     $directory = database_path('migrations');
//     $migrations = [];
//     if (File::exists($directory)) {
//         $files = File::allFiles($directory);
//         foreach ($files as $file) {
//             if ($file->getExtension() === 'php') {
//                 $migrations[] = pathinfo($file->getFilename(), PATHINFO_FILENAME);
//             }
//         }
//     }
//     return response()->json($migrations);
// });

// // List all route modules
// Route::get('route-modules', function () {
//     $directory = base_path('routes/modules');
//     $modules = [];
//     if (File::exists($directory)) {
//         $files = File::allFiles($directory);
//         foreach ($files as $file) {
//             if ($file->getExtension() === 'php') {
//                 $relativePath = str_replace($directory . DIRECTORY_SEPARATOR, '', $file->getPathname());
//                 $moduleName = str_replace(['/', '\\'], '.', substr($relativePath, 0, -4));
//                 $modules[] = $moduleName;
//             }
//         }
//     }
//     return response()->json($modules);
// });

// // Delete module files
// Route::get('delete-module-controller-migration-routes-views', function () {
//     $moduleName = request('module');
//     if (!$moduleName) {
//         return response()->json(['error' => 'Module name is required'], 400);
//     }

//     $controllerPath = app_path("Http/Controllers/admin/{$moduleName}Controller.php");
//     $migrationPattern = database_path("migrations/*_create_{$moduleName}_table.php");
//     $routePath = base_path("routes/modules/{$moduleName}.php");
//     $viewPath = resource_path("views/admin/pages/{$moduleName}");
//     $modelPath = app_path("Models/{$moduleName}.php");

//     if (File::exists($controllerPath)) {
//         File::delete($controllerPath);
//     }

//     foreach (glob($migrationPattern) as $file) {
//         File::delete($file);
//     }

//     if (File::exists($routePath)) {
//         File::delete($routePath);
//     }

//     if (File::isDirectory($viewPath)) {
//         File::deleteDirectory($viewPath);
//     }

//     if (File::exists($modelPath)) {
//         File::delete($modelPath);
//     }

//     return response()->json(['success' => 'Module files deleted successfully']);
// });






Route::get('', function () {
    if (session()->has('id')) {
        return redirect('/admin/dashboard');
    } else {
        return redirect('/admin');
    }
});


// Route::get('/', [App\Http\Controllers\Auth\LoginController::class, 'index']);
Route::get('/admin', [App\Http\Controllers\Auth\LoginController::class, 'index']);
Route::get('/admin/login', [App\Http\Controllers\Auth\LoginController::class, 'index'])->name('login');
Route::post('/admin/auth/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::get('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout']);
Route::get('/admin/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/admin/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/admin/reset-password/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/admin/reset-password', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

Route::any('/admin/invoice/generate-invoice/{id}', [App\Http\Controllers\admin\InvoiceController::class, 'generateInvoice']);








Route::get('admin/subcategory-list', [App\Http\Controllers\CommonController::class, 'subcategoryList']);
// unit-list
Route::get('admin/unit-list', [App\Http\Controllers\CommonController::class, 'unitList']);


Route::group(['prefix' => 'admin', 'middleware' => ['auth','CheckSession']], function () {

      // Dashboard
    Route::get('dashboard/list', function () {return redirect('admin/dashboard');});
    Route::get('dashboard', [App\Http\Controllers\admin\DashboardController::class, 'dashboard']);


    /* * User Management
     */
    // Route::get('/', 'UserController@index');
    Route::prefix('user')->group(function () {
        Route::get('list', [App\Http\Controllers\admin\UserController::class, 'index']);
        Route::any('add', [App\Http\Controllers\admin\UserController::class, 'addUser']);
        Route::any('edit/{id}', [App\Http\Controllers\admin\UserController::class, 'editUser']);
        Route::any('delete/{id}', [App\Http\Controllers\admin\UserController::class, 'deleteUser']);
        Route::any('update-status/{id}/{status}', [App\Http\Controllers\admin\UserController::class, 'updateStatus']);

    // Route::any('/users/change-password/{id}', 'UserController@checkPassword')->name('change-password');
        Route::any('change-password/{id}', [App\Http\Controllers\admin\UserController::class, 'checkPassword'])->name('change-password');


    });

        /**
     * Roles
     */
    Route::prefix('role')->group(function () {
        Route::get('list', [App\Http\Controllers\admin\RoleController::class, 'index']);
        Route::any('permissions/{role_id}', [App\Http\Controllers\admin\RoleController::class, 'permissions']);
        Route::any('edit/{role_id}', [App\Http\Controllers\admin\RoleController::class, 'edit']);
        Route::any('add', [App\Http\Controllers\admin\RoleController::class, 'add']);
    });

        /**
     * Settings
     */
    Route::any('/settings', [App\Http\Controllers\admin\SettingsController::class, 'index']);
    Route::any('/settings/list', [App\Http\Controllers\admin\SettingsController::class, 'index']);
    Route::any('/settings/master', [App\Http\Controllers\admin\SettingsController::class, 'master']);
    // admin/settings/bank
    Route::any('/settings/bank', [App\Http\Controllers\admin\SettingsController::class, 'bank']);



    /**
     * Module
     */
    Route::prefix('module')->group(function () {
        Route::get('list', [App\Http\Controllers\admin\ModuleController::class, 'index']);
        Route::any('add', [App\Http\Controllers\admin\ModuleController::class, 'add']);
        Route::any('template', [App\Http\Controllers\admin\ModuleController::class, 'template']);
        Route::any('edit/{id}', [App\Http\Controllers\admin\ModuleController::class, 'edit']);
        Route::any('delete/{id}', [App\Http\Controllers\admin\ModuleController::class, 'delete']);
        Route::any('update-status/{id}/{status}', [App\Http\Controllers\admin\ModuleController::class, 'updateStatus']);
        // delete-icon
        Route::any('delete-icon/{id}', [App\Http\Controllers\admin\ModuleController::class, 'deleteIcon']);
        // publish
        Route::any('publish/{id}', [App\Http\Controllers\admin\ModuleController::class, 'publish']);

        // left-menu
        Route::any('left-menu/{id}', [App\Http\Controllers\admin\ModuleController::class, 'leftMenu']);

        // accordion
        Route::any('accordion/{id}', [App\Http\Controllers\admin\ModuleController::class, 'accordion']);

        // sort-order
        Route::any('sort-order', [App\Http\Controllers\admin\ModuleController::class, 'sortOrder']);
    });


    // Dynamic Form
    Route::prefix('dynamic-form')->group(function () {
        Route::get('list', [App\Http\Controllers\admin\DynamicFormController::class, 'index']);
        Route::any('add', [App\Http\Controllers\admin\DynamicFormController::class, 'add']);
        Route::any('edit/{id}', [App\Http\Controllers\admin\DynamicFormController::class, 'edit']);
        Route::any('delete/{id}', [App\Http\Controllers\admin\DynamicFormController::class, 'delete']);
        Route::any('update-status/{id}/{status}', [App\Http\Controllers\admin\DynamicFormController::class, 'updateStatus']);
    });


    // new-module




    // add routes from modules folder
    $modules = glob(base_path('routes/modules/*.php'));
    foreach ($modules as $module) {
        $moduleName = basename($module, '.php');
        if (file_exists($module)) {
            require_once $module;
        }
    }

});

Route::get('optimize', function () {
    Artisan::call('optimize:clear');
    return 'optimized';
});

Route::get('migrate-refresh', function () {
    Artisan::call('migrate:fresh --seed');
    return 'migrated and seeded';
});

Route::get('migrate', function () {
    Artisan::call('migrate');
    return 'migrated';
});

// Mail

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;


Route::get('/send-test-email', function () {
    $to = "shubham.bramhane75@gmail.com";
    Mail::send('emails.test', [], function ($message) use ($to) {
        $message->to($to)
            ->subject('Test Email');
    });

    return 'Test email sent!';
});

Route::get('/test',function () {
    dd(modulesListNew());
});


