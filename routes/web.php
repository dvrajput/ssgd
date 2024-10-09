<?php

use App\Http\Controllers\Admin\CategoriesController as AdminCategoriesController;
use App\Http\Controllers\Admin\PlaylistsController as AdminPlaylistsController;
use App\Http\Controllers\Admin\SongsController as AdminSongController;
use App\Http\Controllers\Admin\SubCategoryController as AdminSubCategoryController;
use App\Http\Controllers\User\SongsController as UserSongController;
use App\Http\Controllers\User\CategoriesController as UserCategoriesController;
use App\Http\Controllers\User\SubCategoryController as UserSubCategoryController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // return view('welcome');
    return redirect()->route('user.songs.index');
});

// Auth::routes(['register' => false]);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Route::get('/dashboard', function () {
    //     return view('dashboard');
    // })->name('dashboard');
    Route::prefix('admin/')->name('admin.')->group(function () {
        Route::resource('songs', AdminSongController::class);
        Route::resource('categories', AdminCategoriesController::class);
        // Route::get('categories/{id}/associated-songs', [AdminCategoriesController::class, 'fetchAssociatedSongs'])->name('categories.associated_songs');
        // Route::get('categories/{id}/remaining-songs', [AdminCategoriesController::class, 'fetchRemainingSongs'])->name('categories.remaining_songs');
        // Route::post('song_category_rel', [AdminCategoriesController::class, 'addSongToCategory'])->name('categories.addSong');
        // Route::delete('song_category_rel/{song_code}', [AdminCategoriesController::class, 'removeSongFromCategory'])->name('categories.removeSong');
        Route::resource('playlists', AdminPlaylistsController::class);

        Route::resource('subCategories', AdminSubCategoryController::class);
        Route::get('subCategories/{id}/associated-songs', [AdminSubCategoryController::class, 'fetchAssociatedSongs'])->name('subCategories.associated_songs');
        Route::get('subCategories/{id}/remaining-songs', [AdminSubCategoryController::class, 'fetchRemainingSongs'])->name('subCategories.remaining_songs');
        Route::post('song_category_rel', [AdminSubCategoryController::class, 'addSongToCategory'])->name('subCategories.addSong');
        Route::delete('song_category_rel/{song_code}', [AdminSubCategoryController::class, 'removeSongFromCategory'])->name('subCategories.removeSong');
    });
});

Route::name('user.')->group(function () {
    Route::resource('songs', UserSongController::class);
    Route::resource('categories', UserCategoriesController::class);
});
Route::get('/language/{locale}', function ($locale) {
    session()->put('locale', $locale);
    // dump($locale);
    return redirect()->back();
})->name('locale');
