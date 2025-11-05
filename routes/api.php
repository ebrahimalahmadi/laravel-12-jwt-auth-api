<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//I Use apiPrefix: 'api/v1' in  middleware 


// Test Route
Route::get('test', function () {
    return response()->json(['message' => 'API connected successfully']);
});
