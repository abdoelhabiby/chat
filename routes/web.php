<?php

use App\Post;
use App\User;
use App\Friend;
use Illuminate\Http\Request;
use App\Events\NewCommentEvent;
use App\Events\NewMessageEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::get('tset_form_data',function(Request $request){

    // return "test here";
    return $request->file('audio.wav');
});


Route::post('test_audio_file',function(Request $request){

    //  return dd($request->all());

    $file = request()->file('file');
    $file_name = rand(10,100000000000000) . "_" . $file->getClientOriginalName();
    $file->move(public_path('/records'), $file_name);
    // return "test here";

    $url_link = asset('records/' . $file_name);
    return response(['url_record' => $url_link]);
});



Route::group(['middleware' => 'auth'], function () {

    Route::resource('posts', PostController::class, ['except' => ['create']]);

    Route::resource('post/{post}/comments', CommentController::class, ['except' => ['create']]);


    Route::get('chat','ChatController@index');

    Route::get('record','TestController@index');

    Route::get('chat/sendmessage/{friend}','ChatMessageController@sendMessage');


    //--------------------test camera hhhhhh loool---------------


    Route::get('camera',function(){
        return view('camera');
    });



    //---------------------------------------------------------

});




Route::get('test_socket',function(){

     $user = User::where('email','m@m.com')->first();


    broadcast(new NewMessageEvent(auth()->user()))->toOthers();
    return "sended";




});



Route::get('chat_messages/{friend}','ChatMessageController@fetchChatMessages');



Route::get('test', function () {



    //  $user = User::where('email','m@m.com')->first();















    $latestPosts = \DB::table('posts')
        ->select('user_id', DB::raw('MAX(created_at) as last_post_created_at'))
        ->groupBy('user_id');

        return   $users = \DB::table('users')
        ->joinSub($latestPosts, 'latest_posts', function ($join) {
            $join->on('users.id', '=', 'latest_posts.user_id');
        })->orderBy('last_post_created_at','desc')->get();
    // return factory(Post::class,3)->create();

    // return  \Carbon\Carbon::parse(auth()->user()->created_at)->toDayDateTimeString();


  return  $notifications_unread_count = auth()->user()->unreadNotifications->count();
    $latest_notifications = auth()->user()->notifications->first();

    return $latest_notifications;

});
