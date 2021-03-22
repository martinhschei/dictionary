<?php

Route::post('/dictionary/send', 'WordLookupController@create');

Route::get('/dictionary/lookup/{word}', 'WordLookupController@show');
