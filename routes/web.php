<?php
Route::get('/', function () {
    return view('welcome');
});

Route::group([
  'prefix'    => 'api'
], function () {
  Route::get('createDB', 'pdfmController@createDB');
  Route::get('updateDB', 'pdfmController@updateDB');
  Route::post('calcDB', 'pdfmController@calcDB');
  Route::post('filterDB', 'pdfmController@filterDB');
  Route::get('searchDB', 'pdfmController@searchDB');
});
