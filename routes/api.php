<?php

Route::post('/create', 'WordLookUpController@create');

Route::get('/lookup/{word}', 'WordLookUpController@show');