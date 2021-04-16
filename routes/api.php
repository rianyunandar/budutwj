<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiUser;
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
//cara aksesnya http://127.0.0.1:8000/api/apiuser/2/3
Route::get('apiuser/{token}/{id}', [ApiUser::class, 'getUser']);
Route::get('apiuseroff/{token}/{id}', [ApiUser::class, 'getUserOff']);
Route::get('apirombel/{token}/{id}', [ApiUser::class, 'getRombel']);
Route::get('apijurusan/{token}/{id}', [ApiUser::class, 'getJurusan']);
Route::get('apiguru/{token}/{id}', [ApiUser::class, 'getGuru']);
