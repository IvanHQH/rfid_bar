<?php


Route::get('/', array('before' => 'auth', 'uses' => 'HomeController@index'));
Route::get('/dashboard/{event_id?}', array('before' => 'auth', 'uses' => 'HomeController@index'));
Route::get('/login', array('uses' => 'HomeController@doLogin'));
Route::post('/login', array('uses' => 'HomeController@loginPost'));
Route::get('/logout', array('uses' => 'HomeController@doLogout'));

Route::get('/users', array('uses' => 'UsersController@index'));
Route::get('/users/datatable', array('before' => 'auth', 'uses' => 'UsersController@usersDatatables'));
Route::get('/users/get/{id}', array('before' => 'auth', 'uses' => 'UsersController@getUser'));
Route::post('/users/{id?}', array('before' => 'auth', 'uses' => 'UsersController@store'));
Route::post('/users/delete/{id}', array('before' => 'auth', 'uses' => 'UsersController@delete'));
Route::get('/users/csv', array('before' => 'auth', 'uses' => 'UsersController@usersCSV'));

Route::get('/working_day/{event_id?}', array('before' => 'auth', 'uses' => 'HomeController@workingDay'));
Route::get('/products/list', array('before' => 'auth', 'uses' => 'ProductsController@productsList'));
Route::get('/products/datatable', array('before' => 'auth', 'uses' => 'ProductsController@productsDatatables'));

//Route::get('/products/inventory', array('before' => 'auth', 'uses' => 'ProductsController@inventory'));

Route::get('/products/csv', array('before' => 'auth', 'uses' => 'ProductsController@productsCSV'));
Route::get('/products/inventory/datatable', array('before' => 'auth', 'uses' => 'ProductsController@inventoryDatatables'));
Route::get('/products/get/{id}', array('before' => 'auth', 'uses' => 'ProductsController@getProduct'));
Route::post('/products/delete/{id}', array('before' => 'auth', 'uses' => 'ProductsController@delete'));
Route::post('/products/{id?}', array('before' => 'auth', 'uses' => 'ProductsController@store'));


Route::get('/tags', array('before' => 'auth', 'uses' => 'TagsController@index'));
Route::get('/tags/datatable', array('before' => 'auth', 'uses' => 'TagsController@tagsDatatables'));
Route::get('/reports/{event_id?}', array('before' => 'auth', 'uses' => 'HomeController@ioReport'));
Route::get('/csv/reports/{event_id?}', array('before' => 'auth', 'uses' => 'HomeController@ioReportCSV'));

Route::get('/bars/list', array('before' => 'auth', 'uses' => 'BarsController@barsList'));
Route::get('/bars/get/{id}', array('before' => 'auth', 'uses' => 'BarsController@getBar'));
Route::get('/bars/datatable', array('before' => 'auth', 'uses' => 'BarsController@barsDatatables'));
Route::post('/bars/delete/{id?}', array('before' => 'auth', 'uses' => 'BarsController@postDelete'));
Route::post('/bars/{id?}', array('before' => 'auth', 'uses' => 'BarsController@postIndex'));
Route::get('/bars/csv', array('before' => 'auth', 'uses' => 'BarsController@barsCSV'));

Route::resource('product', 'ProductsController', array('except' => array('create', 'store', 'update', 'destroy')));

Route::resource('barmen', 'BarmenController');

Route::get('/sync', 'SyncController@index');
Route::post('/sync', 'SyncController@postInventory');
Route::post('/sync/desktop', 'SyncController@postDesktop');

Route::get('/journeyevents', array('before' => 'auth', 'uses' => 'JourneyEventsController@getIndex'));
Route::get('/journeyevents/csv', array('before' => 'auth', 'uses' => 'JourneyEventsController@eventsCSV'));
Route::get('/journeyevents/get/{id}', array('before' => 'auth', 'uses' => 'JourneyEventsController@getEvent'));
Route::get('/journeyevents/datatable', array('before' => 'auth', 'uses' => 'JourneyEventsController@getDatatable'));
Route::any('/journeyevents/report/{event_id?}', array('before' => 'auth', 'uses' => 'JourneyEventsController@getReport'));
Route::any('/journeyevents/csv/{event_id?}', array('before' => 'auth', 'uses' => 'JourneyEventsController@reportCSV'));
Route::post('/journeyevents/delete/{id?}', array('before' => 'auth', 'uses' => 'JourneyEventsController@postDelete'));
Route::post('/journeyevents/{id?}', array('before' => 'auth', 'uses' => 'JourneyEventsController@postIndex'))->where('id', '[0-9]+');
Route::post('/journeyevents/generate/{id?}', array('before' => 'auth', 'uses' => 'JourneyEventsController@postGenerate'));

Route::get('/family', array('before' => 'auth', 'uses' => 'FamilyController@getIndex'));
Route::get('/family/datatable', array('before' => 'auth', 'uses' => 'FamilyController@getDatatable'));
Route::post('/family/{id?}', array('before' => 'auth', 'uses' => 'FamilyController@store'));
Route::get('/family/get/{id}', array('before' => 'auth', 'uses' => 'FamilyController@getFamily'));
Route::post('/family/delete/{id}', array('before' => 'auth', 'uses' => 'FamilyController@delete'));
Route::get('/family/csv', array('before' => 'auth', 'uses' => 'FamilyController@familyCSV'));

Route::get('/report', array('before' => 'auth', 'uses' => 'ReportController@getIndex'));
Route::get('/report/datatable', array('before' => 'auth', 'uses' => 'ReportController@getDatatable'));
Route::get('/report/csv', array('before' => 'auth', 'uses' => 'ReportController@reportCSV'));
Route::get('/report/footer', array('before' => 'auth', 'uses' => 'ReportController@getFooter'));

Route::get('/import', function(){
    $path = 'C:/Temp/Feng/Labels';
    if ($handle = opendir($path)) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
                $dir[] = $entry;
            }
        }
        closedir($handle);
    }

    $labels = "";
    foreach ($dir as $d) {
        $row = 1;
        if (($handle = fopen($path . '/' . $d, "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                if ($data[0] != 'EPC' && $data[0] != '') {
                    $upc = str_replace('.csv', '', $d);
                    $label = str_replace('\'', '', $data[0]);
                    $labels .= "TagMapping::create(array('tag' => '" . $label . "', 'upc' => '" . $upc . "'));\n";
                    /*$tag = new TagMapping();
                    $tag -> tag = $label;
                    $tag -> upc = $upc;
                    $tag -> save();*/
                }
            }
            fclose($handle);
        }
    }
    echo "<pre>" . print_r($labels, true) . "</pre>";
});


Route::get('/gen_readings/{num}', function($num){
    if ($num && $num > 0) {
        $activeEvent = JourneyEvent::where('active', '=', true) -> first();
        $epcs = TagMapping::all() -> toArray();
        $startDate = new DateTime();
        $minutes = 0;
        for ($i = 0; $i < $num; $i ++) {
            $read = new EventLog();
            $read -> event_id = $activeEvent -> id;
            $randTagId = rand(0, count($epcs) - 1);
            $read -> tag = $epcs[$randTagId]['tag'];
            $read -> antenna_in = 1;
            $read -> antenna_out = 2;
            $currentDate = strtotime('+' . $minutes . ' minutes', strtotime($startDate -> format('Y/m/d H:i:s')));
            $read -> created_at = $currentDate;
            $read -> updated_at = $currentDate;
            $read -> save();
            $minutes += rand(1, 10);
            echo "<pre>" . print_r($read -> toArray(), true) . "</pre>";
        }
    }
});
