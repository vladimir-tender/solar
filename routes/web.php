<?php

Route::get('/comments','CommentController@index');

Route::get('/comments/ajax','CommentController@commentsTreeAjax');

Route::get('/get/comment/{id}', 'CommentController@getComment');

Route::get('/comment/create','CommentController@create');

Route::post('/comment/add', 'CommentController@addComment');

Route::post('/comment/edit', 'CommentController@editComment');

Route::post('/comment/remove/{id}', 'CommentController@removeComment');
