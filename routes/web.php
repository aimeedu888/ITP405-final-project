<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\YouTubeController;
use App\Http\Controllers\DetailController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FavController;
use App\Http\Controllers\CommentController;

Route::get('/', [YouTubeController::class,'getYouTubeFans'])->name('home');
Route::get('/detail/{group}', [DetailController::class,'groupDetail'])->name('groupDetail');
Route::get('/detail/{group}/{albumID}/{commentIndex}', [DetailController::class,'albumDetail'])->name('albumDetail');
Route::view('/login', 'login')->name('login');
Route::view('/signup', 'signup', ['avatars' => DB::table('avatars')->get()])->name('signup');   
Route::post('/login', [AuthController::class,'login']);
Route::post('/signup', [AuthController::class,'register']);

Route::middleware(['auth'])->group(function(){
    Route::get('/logout', [AuthController::class,'logout'])->name('logout');
    // favorite
    Route::post('/addfavorite/album/{group}/{album_id}/{user_id}', [FavController::class,'addAlbum'])->name('addFavoriteAlbum');
    Route::post('/addfavorite/track/{group}/{album_id}/{track_id}/{user_id}/{commentIndex}', [FavController::class,'addTrack'])->name('addFavoriteTrack');
    Route::post('/removefavorite/album/{group}/{album_id}', [FavController::class,'removeAlbum'])->name('removeFavoriteAlbum');
    Route::post('/removefavorite/track/{group}/{album_id}/{track_id}/{commentIndex}', [FavController::class,'removeTrack'])->name('removeFavoriteTrack');
    Route::post('/favorite/{username}/{groupIds}/{filter}', [FavController::class,'viewByFilter'])->name('viewfavorite');
    Route::post('/favorite', [FavController::class,'viewPage'])->name('viewFavoritePage');
    // comment for album
    Route::post('/comment/add/album/{group}/{album_id}/{commentIndex}', [CommentController::class,'albumComment'])->name('albumComment');
    Route::post('/comment/remove/album/{group}/{album_id}/{commentIndex}/{comment_id}', [CommentController::class,'removeAlbumComment'])->name('removeAlbumComment');
    Route::post('/comment/edit/album/{group}/{album_id}/{commentIndex}/{comment_id}', [CommentController::class,'editAlbumComment'])->name('editAlbumComment');
    // comment for track
    Route::post('/comment/add/track/{group}/{album_id}/{commentIndex}/{track_id}', [CommentController::class,'trackComment'])->name('trackComment');
    Route::post('/comment/remove/track/{group}/{album_id}/{commentIndex}/{comment_id}', [CommentController::class,'removeTrackComment'])->name('removeTrackComment');
    Route::post('/comment/edit/track/{group}/{album_id}/{commentIndex}/{comment_id}', [CommentController::class,'editTrackComment'])->name('editTrackComment');
});