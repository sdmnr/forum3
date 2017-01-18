<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/


Route::get('/home', function () {
    return view('welcome');
})->name('home');

Route::post('/signup', [
    'uses' => 'UserController@postSignUp',
    'as' => 'signup'
]);

Route::post('/signin', [
    'uses' => 'UserController@postSignIn',
    'as' => 'signin'
]);

Route::get('/logout', [
    'uses' => 'UserController@getLogout',
    'as' => 'logout'
]);

Route::get('/account', [
    'uses' => 'UserController@getAccount',
    'as' => 'account'
]);

Route::post('/upateaccount', [
    'uses' => 'UserController@postSaveAccount',
    'as' => 'account.save'
]);

Route::get('/userimage/{filename}', [
    'uses' => 'UserController@getUserImage',
    'as' => 'account.image'
]);

Route::get('/dashboard', [
    'uses' => 'PostController@getDashboard',
    'as' => 'dashboard',
    'middleware' => 'auth'
]);

Route::get('/index', [
    'uses' => 'PostController@getDashboard',
    'as' => 'dashboard',
    'middleware' => 'auth'
]);

Route::get('/', [
    'uses' => 'PostController@getDashboard',
    'as' => 'dashboard',
    'middleware' => 'auth'
]);

Route::get('/allQuestions', [
    'uses' => 'PostController@getallQuestions',
    'as' => 'allQuestions',
    'middleware' => 'auth'
]);//get all qts

Route::get('/myQuestions', [
    'uses' => 'PostController@getmyQuestions',
    'as' => 'myQuestions',
    'middleware' => 'auth'
]);//get my qts

Route::get('/myResponses', [
    'uses' => 'PostController@getmyResponses',
    'as' => 'myResponses',
    'middleware' => 'auth'
]);//get the posts i responded to

Route::get('/likedQuestions', [
    'uses' => 'PostController@getlikedQuestions',
    'as' => 'likedQuestions',
    'middleware' => 'auth'
]);//get liked qts

Route::post('/createpost', [
    'uses' => 'PostController@postCreatePost',
    'as' => 'post.create',
    'middleware' => 'auth'
]);//make a new post 

Route::post('/createresponse', [
    'uses' => 'PostController@postCreateResponse',
    'as' => 'response.create',
    'middleware' => 'auth'
]);//make a reply to a post

Route::get('/view-post/{post_id}', [
    'uses' => 'PostController@getViewPost',
    'as' => 'post.view',
    'middleware' => 'auth'
]);//open a post to view

Route::get('/delete-post/{post_id}', [
    'uses' => 'PostController@getDeletePost',
    'as' => 'post.delete',
    'middleware' => 'auth'
]);//delete a post

Route::get('/delete-response/{response_id}/{post_id}', [
    'uses' => 'PostController@getDeleteResponse',
    'as' => 'response.delete',
    'middleware' => 'auth'
]);//delete a response

// Route::post('/reply', [
//     'uses' => 'PostController@postReplyPost',
//     'as' => 'reply'
// ]);//make a reply to a post

Route::post('/edit', [
    'uses' => 'PostController@postEditPost',
    'as' => 'edit'
]);//edit a post

Route::post('/like', [
    'uses' => 'PostController@postLikePost',
    'as' => 'like'
]);//for liking a post

Route::post('/rlike', [
    'uses' => 'PostController@postLikeResponse',
    'as' => 'rlike'
]);//for liking a response

Route::get('/about_us', [
    'uses' => 'Controller@getAboutUs',
    'as' => 'about_us'
]);
