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
Route::get('/','AuthController@getLogin');
Route::get('/login','AuthController@getLogin');
Route::post('/login','AuthController@postLogin');
Route::get('/logout', 'AuthController@logOut');

Route::group(['middleware'=>['Webpanel']], function(){

	Route::prefix('dashboard')->group(function(){
		Route::get('/','Webpanel\Dashboards@index');
	});

	Route::prefix('vendors')->group(function(){
		Route::get('/','Webpanel\Vendor@index');
		Route::get('/create','Webpanel\Vendor@create');
		Route::put('/create','Webpanel\Vendor@store');
		Route::get('/{id}','Webpanel\Vendor@edit')->where(['id'=>'[0-9]+']);
		Route::post('/{id}','Webpanel\Vendor@update')->where(['id'=>'[0-9]+']);
		Route::get('/destroy','Webpanel\Vendor@destroy');
		Route::post('/dragsort','Webpanel\Vendor@dragsort');
		Route::get('/status/{id}','Webpanel\Vendor@status')->where(['id'=>'[0-9]+']);
		Route::get('/getdistrict','Webpanel\Vendor@getdistrict');
		Route::get('/getsubdistrict','Webpanel\Vendor@getsubdistrict');
		Route::get('/getprovinces','Webpanel\Vendor@getprovinces');
	});

	Route::prefix('client')->group(function(){
		Route::get('/','Webpanel\Client@index');
		Route::get('/create','Webpanel\Client@create');
		Route::put('/create','Webpanel\Client@store');
		Route::get('/{id}','Webpanel\Client@edit')->where(['id'=>'[0-9]+']);
		Route::post('/{id}','Webpanel\Client@update')->where(['id'=>'[0-9]+']);
		Route::get('/destroy','Webpanel\Client@destroy');
		Route::post('/dragsort','Webpanel\Client@dragsort');
		Route::get('/status/{id}','Webpanel\Client@status')->where(['id'=>'[0-9]+']);
	});

	Route::prefix('shipto')->group(function(){
		Route::get('/','Webpanel\ShipTo@index');
		Route::get('/create','Webpanel\ShipTo@create');
		Route::put('/create','Webpanel\ShipTo@store');
		Route::get('/{id}','Webpanel\ShipTo@edit')->where(['id'=>'[0-9]+']);
		Route::post('/{id}','Webpanel\ShipTo@update')->where(['id'=>'[0-9]+']);
		Route::get('/destroy','Webpanel\ShipTo@destroy');
		Route::post('/dragsort','Webpanel\ShipTo@dragsort');
		Route::get('/status/{id}','Webpanel\ShipTo@status')->where(['id'=>'[0-9]+']);
	});

	Route::prefix('item')->group(function(){
		Route::get('/','Webpanel\Item@index');
		Route::get('/create','Webpanel\Item@create');
		Route::put('/create','Webpanel\Item@store');
		Route::get('/{id}','Webpanel\Item@edit')->where(['id'=>'[0-9]+']);
		Route::post('/{id}','Webpanel\Item@update')->where(['id'=>'[0-9]+']);
		Route::get('/destroy','Webpanel\Item@destroy');
		Route::post('/dragsort','Webpanel\Item@dragsort');
		Route::get('/status/{id}','Webpanel\Item@status')->where(['id'=>'[0-9]+']);
	});

	Route::prefix('unitcount')->group(function(){
		Route::get('/','Webpanel\UnitCount@index');
		Route::get('/create','Webpanel\UnitCount@create');
		Route::put('/create','Webpanel\UnitCount@store');
		Route::get('/{id}','Webpanel\UnitCount@edit')->where(['id'=>'[0-9]+']);
		Route::post('/{id}','Webpanel\UnitCount@update')->where(['id'=>'[0-9]+']);
		Route::get('/destroy','Webpanel\UnitCount@destroy');
		Route::post('/dragsort','Webpanel\UnitCount@dragsort');
		Route::get('/status/{id}','Webpanel\UnitCount@status')->where(['id'=>'[0-9]+']);
	});

	Route::prefix('airport')->group(function(){
		Route::get('/','Webpanel\Airport@index');
		Route::get('/create','Webpanel\Airport@create');
		Route::put('/create','Webpanel\Airport@store');
		Route::get('/{id}','Webpanel\Airport@edit')->where(['id'=>'[0-9]+']);
		Route::post('/{id}','Webpanel\Airport@update')->where(['id'=>'[0-9]+']);
		Route::get('/destroy','Webpanel\Airport@destroy');
		Route::post('/dragsort','Webpanel\Airport@dragsort');
		Route::get('/status/{id}','Webpanel\Airport@status')->where(['id'=>'[0-9]+']);
	});

	Route::prefix('airline')->group(function(){
		Route::get('/','Webpanel\Airline@index');
		Route::get('/create','Webpanel\Airline@create');
		Route::put('/create','Webpanel\Airline@store');
		Route::get('/{id}','Webpanel\Airline@edit')->where(['id'=>'[0-9]+']);
		Route::post('/{id}','Webpanel\Airline@update')->where(['id'=>'[0-9]+']);
		Route::get('/destroy','Webpanel\Airline@destroy');
		Route::post('/dragsort','Webpanel\Airline@dragsort');
		Route::get('/status/{id}','Webpanel\Airline@status')->where(['id'=>'[0-9]+']);
	});

	Route::prefix('currency')->group(function(){
		Route::get('/','Webpanel\Currency@index');
		Route::get('/create','Webpanel\Currency@create');
		Route::put('/create','Webpanel\Currency@store');
		Route::get('/{id}','Webpanel\Currency@edit')->where(['id'=>'[0-9]+']);
		Route::post('/{id}','Webpanel\Currency@update')->where(['id'=>'[0-9]+']);
		Route::get('/destroy','Webpanel\Currency@destroy');
		Route::post('/dragsort','Webpanel\Currency@dragsort');
		Route::get('/status/{id}','Webpanel\Currency@status')->where(['id'=>'[0-9]+']);
	});

	Route::prefix('freight')->group(function(){
		Route::get('/','Webpanel\Freight@index');
		Route::get('/create','Webpanel\Freight@create');
		Route::put('/create','Webpanel\Freight@store');
		Route::get('/{id}','Webpanel\Freight@edit')->where(['id'=>'[0-9]+']);
		Route::post('/{id}','Webpanel\Freight@update')->where(['id'=>'[0-9]+']);
		Route::get('/destroy','Webpanel\Freight@destroy');
		Route::post('/dragsort','Webpanel\Freight@dragsort');
		Route::get('/status/{id}','Webpanel\Freight@status')->where(['id'=>'[0-9]+']);
	});

	Route::prefix('box')->group(function(){
		Route::get('/','Webpanel\Box@index');
		Route::get('/create','Webpanel\Box@create');
		Route::put('/create','Webpanel\Box@store');
		Route::get('/{id}','Webpanel\Box@edit')->where(['id'=>'[0-9]+']);
		Route::post('/{id}','Webpanel\Box@update')->where(['id'=>'[0-9]+']);
		Route::get('/destroy','Webpanel\Box@destroy');
		Route::post('/dragsort','Webpanel\Box@dragsort');
		Route::get('/status/{id}','Webpanel\Box@status')->where(['id'=>'[0-9]+']);
	});
	Route::prefix('pallets')->group(function(){
		Route::get('/','Webpanel\Pallets@index');
		Route::get('/create','Webpanel\Pallets@create');
		Route::put('/create','Webpanel\Pallets@store');
		Route::get('/{id}','Webpanel\Pallets@edit')->where(['id'=>'[0-9]+']);
		Route::post('/{id}','Webpanel\Pallets@update')->where(['id'=>'[0-9]+']);
		Route::get('/destroy','Webpanel\Pallets@destroy');
		Route::post('/dragsort','Webpanel\Pallets@dragsort');
		Route::get('/status/{id}','Webpanel\Pallets@status')->where(['id'=>'[0-9]+']);
	});

	Route::prefix('packaging')->group(function(){
		Route::get('/','Webpanel\Packaging@index');
		Route::get('/create','Webpanel\Packaging@create');
		Route::put('/create','Webpanel\Packaging@store');
		Route::get('/{id}','Webpanel\Packaging@edit')->where(['id'=>'[0-9]+']);
		Route::post('/{id}','Webpanel\Packaging@update')->where(['id'=>'[0-9]+']);
		Route::get('/destroy','Webpanel\Packaging@destroy');
		Route::post('/dragsort','Webpanel\Packaging@dragsort');
		Route::get('/status/{id}','Webpanel\Packaging@status')->where(['id'=>'[0-9]+']);
	});

	Route::prefix('po')->group(function(){
		Route::get('/','Webpanel\PO@index');
		Route::post('/payment','Webpanel\PO@payment');
		Route::get('/create','Webpanel\PO@create');
		Route::put('/create','Webpanel\PO@store');
		Route::get('view/{id}','Webpanel\PO@show')->where(['id'=>'[0-9]+']);
		Route::post('view/{id}','Webpanel\PO@changeStatus')->where(['id'=>'[0-9]+']);
		Route::get('/{id}','Webpanel\PO@edit')->where(['id'=>'[0-9]+']);
		Route::post('/{id}','Webpanel\PO@update')->where(['id'=>'[0-9]+']);
		Route::get('/cancel','Webpanel\PO@cancel');
		Route::get('/pickup','Webpanel\PO@pickup');
		Route::get('/destroyimport','Webpanel\PO@destroyimport');
		Route::post('/dragsort','Webpanel\PO@dragsort');
		Route::get('/status/{id}','Webpanel\PO@status')->where(['id'=>'[0-9]+']);
		Route::get('/getBarcode','Webpanel\PO@getBarcode');
		Route::get('/getItem','Webpanel\PO@getItem');
		Route::get('/getUnit','Webpanel\PO@getUnit');
		Route::get('/getBox','Webpanel\PO@getBox');
		Route::get('/getPack','Webpanel\PO@getPack');
		Route::get('/return','Webpanel\PO@return');
		Route::get('/po/{id}','Webpanel\PDFController@po');
		Route::get('/lrp','Webpanel\PDFController@lrp');
	});

	Route::prefix('receive')->group(function(){
		Route::get('/','Webpanel\Receive@index');
		Route::get('/getKG','Webpanel\Receive@getItem');
		Route::get('/getPC','Webpanel\Receive@getItem');
		Route::get('/getBoxAndPack','Webpanel\Receive@getBoxAndPack');
		Route::post('/confirmKG','Webpanel\Receive@confirmKG');
		Route::post('/confirmPC','Webpanel\Receive@confirmPC');
		Route::post('/confirm','Webpanel\Receive@confirm');
	});

	Route::prefix('sorting')->group(function(){
		Route::get('/','Webpanel\Sorting@index');
		Route::get('/getKG','Webpanel\Sorting@getItem');
		Route::get('/getPC','Webpanel\Sorting@getItem');
		Route::post('/sortingKG','Webpanel\Sorting@sortingKG');
		Route::post('/sortingPC','Webpanel\Sorting@sortingPC');
	});

	Route::prefix('asl')->group(function(){
		Route::get('/','Webpanel\ASL@index');
		Route::get('/getView','Webpanel\ASL@getView');
		Route::get('/create/{id}','Webpanel\ASL@create');
		Route::put('/create/{id}','Webpanel\ASL@store');
		Route::get('/getEAN','Webpanel\Packing@getEAN');
		Route::get('/CalWages','Webpanel\ASL@CalWages');
		Route::get('/getWeightEAN','Webpanel\ASL@getWeightEAN');
		Route::get('/getCostPack','Webpanel\ASL@getCostPack');
		Route::get('/getWrapCost','Webpanel\ASL@getWrapCost');
		Route::get('/getEANStock','Webpanel\ASL@getEANStock');
		Route::get('/destroyEAN','Webpanel\ASL@destroyEAN');
		Route::get('/waste','Webpanel\ASL@waste');
		Route::get('/getEANDetails','Webpanel\ASL@getEANDetails');
		Route::post('/registerWaste','Webpanel\ASL@registerWaste');
	});

	Route::prefix('setup')->group(function(){
		Route::get('/','Webpanel\Setup@index');
		Route::get('/create','Webpanel\Setup@create');
		Route::put('/create','Webpanel\Setup@store');
		Route::get('/{id}','Webpanel\Setup@edit')->where(['id'=>'[0-9]+']);
		Route::post('/{id}','Webpanel\Setup@update')->where(['id'=>'[0-9]+']);
		Route::get('/getItem','Webpanel\Setup@getItem');
		Route::get('/getUnit','Webpanel\Setup@getUnit');
		Route::get('/getBox','Webpanel\Setup@getBox');
		Route::get('/getPack','Webpanel\Setup@getPack');
		Route::get('/getVal','Webpanel\Setup@getVal');
		Route::get('/destroy','Webpanel\Setup@destroy');
		Route::get('/destroySetup','Webpanel\Setup@destroySetup');
		Route::get('/status/{id}','Webpanel\Setup@status')->where(['id'=>'[0-9]+']);
	});

	Route::prefix('itf')->group(function(){
		Route::get('/','Webpanel\ITF@index');
		Route::get('/create','Webpanel\ITF@create');
		Route::put('/create','Webpanel\ITF@store');
		Route::get('/{id}','Webpanel\ITF@edit')->where(['id'=>'[0-9]+']);
		Route::post('/{id}','Webpanel\ITF@update')->where(['id'=>'[0-9]+']);
		Route::get('/getItem','Webpanel\ITF@getItem');
		Route::get('/getTotal','Webpanel\ITF@getTotal');
		Route::get('/getUnit','Webpanel\ITF@getUnit');
		Route::get('/getBox','Webpanel\ITF@getBox');
		Route::get('/getPack','Webpanel\ITF@getPack');
		Route::get('/getVal','Webpanel\ITF@getVal');
		Route::get('/destroy','Webpanel\ITF@destroy');
		Route::get('/destroyITF','Webpanel\ITF@destroyITF');
		Route::get('/status/{id}','Webpanel\ITF@status')->where(['id'=>'[0-9]+']);
	});

	Route::prefix('packing')->group(function(){
		Route::get('/','Webpanel\Packing@index');
		Route::get('/create','Webpanel\Packing@create');
		Route::put('/create','Webpanel\Packing@store');
		Route::get('/view/{id}','Webpanel\Packing@show')->where(['id'=>'[0-9]+']);
		Route::get('/showPack/{id}','Webpanel\Packing@showPack')->where(['id'=>'[0-9]+']);
		Route::get('/{id}','Webpanel\Packing@edit')->where(['id'=>'[0-9]+']);
		Route::post('/{id}','Webpanel\Packing@update')->where(['id'=>'[0-9]+']);
		Route::get('/getUnit','Webpanel\Packing@getUnit');
		Route::get('/getVal','Webpanel\Packing@getVal');
		Route::get('/getEAN','Webpanel\Packing@getEAN');
		Route::get('/CalWages','Webpanel\Packing@CalWages');
		Route::get('/getWeightEAN','Webpanel\Packing@getWeightEAN');
		Route::get('/getCostPack','Webpanel\Packing@getCostPack');
		Route::get('/getWrapCost','Webpanel\Packing@getWrapCost');
		Route::get('/destroyEAN','Webpanel\Packing@destroyEAN');
		Route::get('/restore','Webpanel\Packing@restore');
		Route::get('/getPackVal','Webpanel\Packing@getPackVal');
		Route::get('/waste','Webpanel\Packing@waste');

		Route::get('/getEANDetails','Webpanel\Packing@getEANDetails');
		Route::get('/getEANStock','Webpanel\Packing@getEANStock');
	});

	Route::prefix('hpl')->group(function(){
		Route::get('/','Webpanel\HPL@index');
	});

	Route::prefix('inventory')->group(function(){
		Route::get('/','Webpanel\Inventory@index');
		Route::get('/view/{id}/{unit}','Webpanel\Inventory@show');
	});

	Route::prefix('clearance')->group(function(){
		Route::get('/','Webpanel\Clearance@index');
		Route::get('/create','Webpanel\Clearance@create');
		Route::put('/create','Webpanel\Clearance@store');
		Route::get('/{id}','Webpanel\Clearance@edit')->where(['id'=>'[0-9]+']);
		Route::post('/{id}','Webpanel\Clearance@update')->where(['id'=>'[0-9]+']);
		Route::get('/destroy','Webpanel\Clearance@destroy');
		Route::post('/dragsort','Webpanel\Clearance@dragsort');
		Route::get('/status/{id}','Webpanel\Clearance@status')->where(['id'=>'[0-9]+']);
	});

	Route::prefix('transport')->group(function(){
		Route::get('/','Webpanel\Transport@index');
		Route::get('/create','Webpanel\Transport@create');
		Route::put('/create','Webpanel\Transport@store');
		Route::get('/{id}','Webpanel\Transport@edit')->where(['id'=>'[0-9]+']);
		Route::post('/{id}','Webpanel\Transport@update')->where(['id'=>'[0-9]+']);
		Route::get('/destroy','Webpanel\Transport@destroy');
		Route::post('/dragsort','Webpanel\Transport@dragsort');
		Route::get('/status/{id}','Webpanel\Transport@status')->where(['id'=>'[0-9]+']);
	});

	Route::prefix('quotation')->group(function(){
		Route::get('/','Webpanel\Quotation@index');
		Route::get('/create','Webpanel\Quotation@create');
		Route::put('/create','Webpanel\Quotation@store');
		Route::get('/view/{id}','Webpanel\Quotation@show')->where(['id'=>'[0-9]+']);
		Route::get('/{id}','Webpanel\Quotation@edit')->where(['id'=>'[0-9]+']);
		Route::post('/{id}','Webpanel\Quotation@update')->where(['id'=>'[0-9]+']);
		Route::get('/getITF','Webpanel\Quotation@getITF');
		Route::get('/getShip','Webpanel\Quotation@getShip');
		Route::get('/getAir','Webpanel\Quotation@getAir');
		Route::get('/getUnit','Webpanel\Quotation@getUnit');
		Route::get('/getVal','Webpanel\Quotation@getVal');
		Route::get('/getClearance','Webpanel\Quotation@getClearance');
		Route::get('/getCost','Webpanel\Quotation@getCost');
		Route::get('/getRate','Webpanel\Quotation@getRate');
		//Route::get('/checkpacking','Webpanel\Order@checkpacking');
		Route::get('/checkpacking','Webpanel\Quotation@checkpacking');
		Route::get('/compareTransport','Webpanel\Quotation@compareTransport');
		Route::get('/cancel','Webpanel\Quotation@cancel');
		Route::get('/approve','Webpanel\Quotation@approve');
		Route::get('/destroyITF','Webpanel\Quotation@destroyITF');
		Route::get('/quotation/{id}','Webpanel\PDFController@quotation');
		Route::get('/copy/{id}','Webpanel\Quotation@copy')->where(['id'=>'[0-9]+']);
		Route::post('/copy/{id}','Webpanel\Quotation@store');
	});

	Route::prefix('order')->group(function(){
		Route::get('/','Webpanel\Order@index');
		Route::get('/create','Webpanel\Order@create');
		Route::put('/create','Webpanel\Order@store');
		Route::get('/view/{id}','Webpanel\Order@show')->where(['id'=>'[0-9]+']);
		Route::get('/{id}','Webpanel\Order@edit')->where(['id'=>'[0-9]+']);
		Route::post('/{id}','Webpanel\Order@update')->where(['id'=>'[0-9]+']);
		Route::get('/getITF','Webpanel\O';
	}