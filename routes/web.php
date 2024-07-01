<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\JobApplicantsController;
use App\Http\Controllers\admin\listJobController;
use App\Http\Controllers\admin\UsersController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobController;
use Illuminate\Support\Facades\Route;

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
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/jobs', [JobController::class, 'index'])->name('jobs');
Route::get('/jobs/detail/{id}', [JobController::class, 'detail'])->name('jobDetail');
Route::post('/apply-job', [JobController::class, 'applyJob'])->name('applyJob');
Route::post('/save-job', [JobController::class, 'saveJob'])->name('saveJob');

// Forget password
Route::get('/forget-password', [AccountController::class, 'forgetPassword'])->name('account.forgetPassword');
Route::post('/process-forget-password', [AccountController::class, 'processForgetPassword'])->name('account.processForgetPassword');
Route::get('/reset-password/{token}', [AccountController::class, 'resetPassword'])->name('account.resetPassword');
Route::post('/process/reset-password', [AccountController::class, 'processResetPassword'])->name('account.processResetPassword');
// Forget password

// first step
// Route::get('/account/register', [AccountController::class, 'registation'])->name('account.registation');
// second step
// Route::post('/account/process-register', [AccountController::class, 'processRegistation'])->name('account.processRegistation');
// third step
// Route::get('/account/login', [AccountController::class, 'login'])->name('account.login');
// four step
// Route::post('/account/authenticate', [AccountController::class, 'authenticate'])->name('account.authenticate');
// five step
// Route::get('/account/profile', [AccountController::class, 'profile'])->name('account.profile');
// six step
// Route::get('/account/logout', [AccountController::class, 'logout'])->name('account.logout');

// Admin session

Route::group(['prefix'=>'admin', 'middleware' => 'checkRole'], function(){
    // User detail from admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/users', [UsersController::class, 'index'])->name('admin.users');
    Route::get('/users/{id}', [UsersController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/update/{id}', [UsersController::class, 'update'])->name('admin.users.update');
    Route::delete('/users', [UsersController::class, 'deleteUser'])->name('admin.users.delete');
    // User detail from admin

    // Job detail from admin
    Route::get('/jobs', [listJobController::class, 'index'])->name('admin.job');
    Route::get('/jobs/{id}', [listJobController::class, 'editJob'])->name('admin.jobs.edit');
    Route::put('/jobs/update/{id}', [listJobController::class, 'updateJob'])->name('admin.jobs.update');
    Route::delete('/jobs', [listJobController::class, 'deleteJob'])->name('admin.jobs.delete');
    // Job detail from admin

    // Job applicate detail from admin
    Route::get('/job-appli', [JobApplicantsController::class, 'index'])->name('admin.jobapp');
    Route::get('/job-appli/{id}', [JobApplicantsController::class, 'editJobapp'])->name('admin.job-appli.edit');
    Route::put('/job-appli/update/{id}', [JobApplicantsController::class, 'updateJobapp'])->name('admin.job-appli.update');
    Route::delete('/job-appli', [JobApplicantsController::class, 'deleteJobapp'])->name('admin.job-appli.delete');
    // Job applicate detail from admin

});
// Admin session

Route::group(['account'], function(){
    Route::group(['middleware' => 'guest'], function(){
        // dont forget go to kernel.php and then ctrl+ click on guest
        // this session set on login and cant move to other page set on redirect to redirect(route('account.profile'));
        Route::get('/account/register', [AccountController::class, 'registation'])->name('account.registation');
        Route::post('/account/process-register', [AccountController::class, 'processRegistation'])->name('account.processRegistation');
        Route::post('/account/authenticate', [AccountController::class, 'authenticate'])->name('account.authenticate');
        Route::get('/account/login', [AccountController::class, 'login'])->name('account.login');
    });

    Route::group(['middleware' => 'auth'], function(){
        // dont forget go to kernel.php and then ctrl+ click on auth
        // this session set on login and cant move to other page set on login to route('account.login')
        Route::get('/account/profile', [AccountController::class, 'profile'])->name('account.profile');
        Route::put('/account/update-profile', [AccountController::class, 'updateProfile'])->name('account.updateProfile');
        Route::post('/account/update-profile-pic', [AccountController::class, 'updateProfilePic'])->name('account.updateProfilePic');
        Route::get('/account/logout', [AccountController::class, 'logout'])->name('account.logout');
        Route::get('/account/create-job', [AccountController::class, 'createJob'])->name('account.createJob');
        Route::post('/account/save-job', [AccountController::class, 'saveJob'])->name('account.saveJob');
        Route::get('/account/my-job', [AccountController::class, 'myJob'])->name('account.myJob');
        Route::get('/account/my-job/edit/{jobId}', [AccountController::class, 'editJob'])->name('account.editJob');
        Route::put('/account/update-job/{jobId}', [AccountController::class, 'updateJob'])->name('account.updateJob');
        Route::delete('/delete-job/{id}', [AccountController::class, 'delete'])->name('delete.job');
        Route::get('/account/my-job-applications', [AccountController::class, 'myJobApplication'])->name('account.myJobApplication');
        Route::delete('/account/remove-job', [AccountController::class, 'removeJob'])->name('account.removeJob');
        Route::get('/account/saved-job', [AccountController::class, 'savedJob'])->name('account.savedJob');
        Route::delete('/account/remove-saved-job', [AccountController::class, 'removeSavedJob'])->name('account.removeSavedJob');
        Route::post('/account/update-password', [AccountController::class, 'updatePassword'])->name('account.updatePassword');
    });
});
