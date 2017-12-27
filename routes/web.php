<?php

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

/*Route::get('/', function () {
    //return view('main')->with(['some_var1' => 'test']);

    //$comments = ['some_var1' => 'test1','some_var2' => 'test2'];
    $comments = DB::table('comments')->get();

    //automatic json
    //return $comments;

    return view('main', compact('comments'));
});

Route::get('/comment/{id}', function ($id) {
   //dd($id);
    $comment = DB::table('comments')->find($id);

    return response()->json($comment);
});*/

Route::get('/comments','CommentController@index');

Route::get('/comments/ajax','CommentController@commentsTreeAjax');

Route::get('/get/comment/{id}', 'CommentController@getComment');

Route::get('/comment/create','CommentController@create');

Route::post('/comment/add', 'CommentController@addComment');

Route::post('/comment/edit', 'CommentController@editComment');

Route::post('/comment/remove/{id}', 'CommentController@removeComment');
