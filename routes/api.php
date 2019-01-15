<?php

Route::post('/create', 'DictionaryController@create');

Route::get('/lookup/{word}', 'DictionaryController@show');
