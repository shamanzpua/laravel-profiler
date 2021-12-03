<?php
use shamanzpua\LaravelProfiler\Middleware\AuthCodeMiddleware;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => [AuthCodeMiddleware::NAME]], function () {
    Route::get('show-profiler-logs', "LogController@show");
    Route::delete('delete-profiler-logs', "LogController@delete");
});
