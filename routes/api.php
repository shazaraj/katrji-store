<?php

use App\Http\Controllers\API\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('studyYears',[HomeController::class , 'getStudyYears']);
Route::get('Years',[HomeController::class , 'getYears']);
Route::get('Subjects',[HomeController::class , 'getSubjects']);
Route::get('generalQuestion',[HomeController::class , 'getGeneralQuestion']);
Route::get('importantWebsites',[HomeController::class , 'getImportantWebsites']);
Route::get('collageInfo',[HomeController::class , 'getCollageInfo']);
Route::get('librarySections',[HomeController::class , 'getLibrarySections']);
Route::get('libraryMaterial',[HomeController::class , 'getLibraryMaterials']);
Route::get('notifications',[HomeController::class , 'getNotifications']);
