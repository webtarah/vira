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

use App\User;
use Illuminate\Support\Facades\Gate;

function makeArray($start, $end){
    for($i=$start;$i<$end;$i++){
        yield $i;
    }
}

Route::get('/','siteController@index');
/*
Route::get('user/{user}',function (User $user){
    $response=Gate::inspect('viewUser',$user);
    if($response->allowed()){
        return $user;
    }else{
        return $response->message();
    }
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');*/

Route::get('search', 'Search\SearchController@search')->name('search');
Route::post('search_filter', 'Search\SearchController@filter')->name('searchFilter');