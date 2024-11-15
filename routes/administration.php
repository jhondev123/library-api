<?php
 use App\Http\Middleware\Auth\AuthenticateAdmin;

 Route::prefix('admin')
     ->middleware(AuthenticateAdmin::class)
     ->group(function(){

     Route::get('/ping', static function () {
         return response()->json(['message' => 'pong']);
     });



 });


