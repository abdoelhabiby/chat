<?php

use App\User;
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



// chat messages

Route::middleware('auth:api')->get('/chat/fetch_messages/{friend}','ChatMessageController@fetchChatMessages');
Route::middleware('auth:api')->get('/chat/conversation/{friend}','ChatMessageController@conversation');
Route::middleware('auth:api')->post('/chat/conversation/{conversation}/{friend}','ChatMessageController@saveMessage');
Route::middleware('auth:api')->put('/chat/conversation/{conversation}/{friend}/seen','ChatMessageController@updateConversationMessagesSeen');




Route::middleware('auth:api')->put('/chat/user/{user}/online', 'UserOnlineController@online');

Route::middleware('auth:api')->put('/chat/user/{user}/offline', 'UserOfflineController@offline');

