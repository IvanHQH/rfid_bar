<?php

class HomeController extends BaseController {

	public function index($event_id = 0) {                
		if ($event_id == 0) {
			$ev = JourneyEvent::active();
			if ($ev -> count()) {
				$event_id = $ev -> id;
			}
		}
		
        $activeEvent = JourneyEvent::whereId($event_id) -> first();
        /*$reads = DB::table('event_log')
            -> where('event_log.event_id', '=', $activeEvent -> id)
            -> join('products', 'event_log.upc', '=', 'products.upc')
            //-> join('tags_mappings', 'event_log.tag', '=', 'tags_mappings.tag')
            -> select('event_log.tag', 'event_log.antenna_in', 'event_log.antenna_out', DB::raw('DATE_FORMAT(event_log.created_at, "%Y,%m,%d,%H,%i") AS created_at_formated'), 'event_log.created_at', 'products.upc', 'products.product_name', DB::raw("IF ((event_log.antenna_in = 1 AND (event_log.antenna_out = 2 OR event_log.antenna_out = 3)), 'Salida', 'Entrada') AS io"))
            -> orderBy('event_log.created_at', 'desc')
            -> get();*/
        
        $ini_inv=Inventory::where("event_id",$activeEvent -> id)->where("inventory_type",1)->first();
        //echo "<pre>".print_r($ini_inv->toArray(),true)."</pre>";die();

        $query = DB::table('event_log')
            -> where('event_log.event_id', '=', $activeEvent -> id)
            -> join('products', 'event_log.upc', '=', 'products.upc')
            -> join('traffic_rules', function($query) {
                $query -> on('event_log.antenna_in', '=', 'traffic_rules.antenna_in');
                $query -> on('event_log.antenna_out', '=', 'traffic_rules.antenna_out');
            })
            -> select('event_log.tag', 'event_log.antenna_in', 'event_log.antenna_out', DB::raw('DATE_FORMAT(event_log.created_at, "%Y,%m,%d,%H,%i") AS created_at_formated'),
                'event_log.created_at', 'products.upc', 'products.product_name',
                DB::raw("traffic_rules.action AS io"),
                    DB::raw('DATE_FORMAT(event_log.created_at, "%Y-%m-%d") AS date'),
                    DB::raw('DATE_FORMAT(event_log.created_at, "%H:%i:%s Hrs.") AS time')
            )
            -> orderBy('event_log.created_at', 'desc');
            if(!is_null($ini_inv)){
            $query->join('inventory_epcs','event_log.tag','=','inventory_epcs.epc');
            $query->where('inventory_epcs.inventory_id','=',$ini_inv->id);
                }
            $reads=$query-> get();
            
            $reads=$this->killVirtualTags($reads);
            //echo "<pre>".print_r($reads,true)."</pre>";die();

		$events = JourneyEvent::orderBy('active', 'desc') -> orderBy('started_at', 'desc') -> get();
	
        return View::make('home', array('reads' => $reads, 'events' => $events, 'event_id' => $event_id));
	}

    public function ioReport($event_id = 0) {
		if ($event_id == 0) {
			$ev = JourneyEvent::active();
			if ($ev -> count()) {
				$event_id = $ev -> id;
			}
		}
		
        $activeEvent = JourneyEvent::whereId($event_id) -> first();
        $ini_inv=Inventory::where("event_id",$activeEvent -> id)->where("inventory_type",1)->first();
        $query = DB::table('event_log')
            -> where('event_log.event_id', '=', $activeEvent -> id)
            -> join('products', 'event_log.upc', '=', 'products.upc')
            -> join('traffic_rules', function($query) {
                $query -> on('event_log.antenna_in', '=', 'traffic_rules.antenna_in');
                $query -> on('event_log.antenna_out', '=', 'traffic_rules.antenna_out');
            })
            -> select('event_log.tag', 'event_log.antenna_in', 'event_log.antenna_out', DB::raw('DATE_FORMAT(event_log.created_at, "%Y,%m,%d,%H,%i") AS created_at_formated'),
                'event_log.created_at', 'products.upc', 'products.product_name',
                DB::raw("traffic_rules.message AS io")
                )
            -> orderBy('event_log.created_at', 'desc');
            if(!is_null($ini_inv)){
            $query->join('inventory_epcs','event_log.tag','=','inventory_epcs.epc');
            $query->where('inventory_epcs.inventory_id','=',$ini_inv->id);
                }
            $reads=$query-> get();
		$reads=$this->killVirtualTags($reads);
		$events = JourneyEvent::orderBy('active', 'desc') -> orderBy('started_at', 'desc') -> get();
	
        return View::make('io_report', array('reads' => $reads, 'events' => $events, 'event_id' => $event_id));
    }
    
    public function ioReportCSV($event_id = 0) {
        $input = Input::all();
        $columns=array('io', 'product_name', 'products.upc', 'event_log.tag', 'event_log.created_at');
            $headers=array('Evento', 'Producto', 'UPC', 'Etiqueta', 'Fecha y Hora');
        if ($event_id == 0) {
			$ev = JourneyEvent::active();
			if ($ev -> count()) {
				$event_id = $ev -> id;
			}
		}
		
        $activeEvent = JourneyEvent::whereId($event_id) -> first();
        $ini_inv=Inventory::where("event_id",$activeEvent -> id)->where("inventory_type",1)->first();
        $query = DB::table(DB::raw("(select IF ((event_log.antenna_in = 1 AND (event_log.antenna_out = 2 OR event_log.antenna_out = 4)), 'Salida', 'Entrada') AS io,event_log.* from event_log) event_log"))
            -> where('event_log.event_id', '=', $activeEvent -> id)
            -> join('products', 'event_log.upc', '=', 'products.upc')
            -> select( /*'event_log.antenna_in', 'event_log.antenna_out', DB::raw('DATE_FORMAT(event_log.created_at, "%Y,%m,%d,%H,%i") AS created_at_formated'),
                'event_log.created_at', */
                'event_log.io',
                    'products.product_name',
                    'products.upc',
                    'event_log.tag',
                    'event_log.created_at',
                    DB::raw("traffic_rules.action AS io")
                    )
            ->where(function($query) use ($input,$columns) {
                    foreach ($columns as $column) {
                        $query->orWhere($column, "LIKE", '%' . $input['sSearch'] . '%');
                    }
                })
                        ->orderBy($columns[$input["iSortCol_0"]], $input["sSortDir_0"]);
            if(!is_null($ini_inv)){
            $query->join('inventory_epcs','event_log.tag','=','inventory_epcs.epc');
            $query->where('inventory_epcs.inventory_id','=',$ini_inv->id);
                }
            $reads=$query-> get();
            $reads=$this->killVirtualTags($reads);
        CSVGenerate::sendRawCSV($reads);
    }

    public function doLogout() {
        Auth::logout(); // log the user out of our application
        return Redirect::to('login'); // redirect the user to the login screen
    }

    public function doLogin() {
        return View::make('login');
    }

    public function loginPost() {
        Auth::attempt( array('username' => Input::get('username'), 'password' => Input::get('password')) );
        return Redirect::to('/');
    }

    public function workingDay($event_id) {
        $activeEvent = JourneyEvent::where('id', '=', $event_id) -> first();
        $timeLine = new stdClass();
        $timeline = new stdClass();
        $timeline -> headline = 'Histórico de Entradas y salidas de botellas';
        $timeline -> type = 'default';
        $timeline -> text = '<b>Evento: </b>' . $activeEvent -> event_name . '<br/><b>Descripción: </b>' . $activeEvent -> description . '<br/><b>Fecha y Hora de Inicio: </b>' . $activeEvent -> started_at;
        $timeLine -> timeline = $timeline;


        $activeEvent = JourneyEvent::where('id', '=', $event_id) -> first();
        $ini_inv=Inventory::where("event_id",$activeEvent -> id)->where("inventory_type",1)->first();
        /*$query = DB::table('event_log')
            -> where('event_log.event_id', '=', $activeEvent -> id)
            -> join('products', 'event_log.upc', '=', 'products.upc')
            //-> select('event_log.tag', 'event_log.antenna_in', 'event_log.antenna_out', DB::raw('DATE_FORMAT(event_log.created_at, "%Y,%m,%d,%H,%i") AS created_at_formated'), 'event_log.created_at', 'products.upc', 'products.product_name',DB::raw("traffic_rules.action AS io"));
                -> select('event_log.tag', 'event_log.antenna_in', 'event_log.antenna_out', DB::raw('DATE_FORMAT(event_log.created_at, "%Y,%m,%d,%H,%i") AS created_at_formated'),
                'event_log.created_at', 'products.upc', 'products.product_name',
                DB::raw("traffic_rules.action AS io"),
                        DB::raw("traffic_rules.action"),
                    DB::raw('DATE_FORMAT(event_log.created_at, "%Y-%m-%d") AS date'),
                    DB::raw('DATE_FORMAT(event_log.created_at, "%H:%i:%s Hrs.") AS time')
            );
            if(!is_null($ini_inv)){
            $query->join('inventory_epcs','event_log.tag','=','inventory_epcs.epc');
            $query->where('inventory_epcs.inventory_id','=',$ini_inv->id);
                }
            $reads=$query-> get();*/
        
        $query = DB::table('event_log')
            -> where('event_log.event_id', '=', $activeEvent -> id)
            -> join('products', 'event_log.upc', '=', 'products.upc')
            -> join('traffic_rules', function($query) {
                $query -> on('event_log.antenna_in', '=', 'traffic_rules.antenna_in');
                $query -> on('event_log.antenna_out', '=', 'traffic_rules.antenna_out');
            })
            -> select('event_log.tag', 'event_log.antenna_in', 'event_log.antenna_out', DB::raw('DATE_FORMAT(event_log.created_at, "%Y,%m,%d,%H,%i") AS created_at_formated'),
                'event_log.created_at', 'products.upc', 'products.product_name',
                DB::raw("traffic_rules.action AS io"),
                    DB::raw('DATE_FORMAT(event_log.created_at, "%Y-%m-%d") AS date'),
                    DB::raw('DATE_FORMAT(event_log.created_at, "%H:%i:%s Hrs.") AS time')
            )
            -> orderBy('event_log.created_at', 'desc');
            if(!is_null($ini_inv)){
            $query->join('inventory_epcs','event_log.tag','=','inventory_epcs.epc');
            $query->where('inventory_epcs.inventory_id','=',$ini_inv->id);
                }
            $reads=$query-> get();
            $reads=$this->killVirtualTags($reads);

        if (count($reads) > 0) {
            $dates = array();
            foreach ($reads as $read) {
                $io = '--';
                $io = TransitRules::getAction($read -> antenna_in, $read -> antenna_out);

                //$io = ($read -> antenna_in == 1 && ($read -> antenna_out == 2 || $read -> antenna_out == 4))? 'Salida' : 'Entrada';
                $date = new stdClass();
                $date -> startDate = $read -> created_at_formated;
                $date -> endDate = $read -> created_at_formated;
                $date -> headline = $read -> product_name;
                //<b>UPC: </b>' . $read -> upc . '<br/><b>Etiqueta: </b>' . $read -> tag . '<br/>
                $date -> text = '<b>Fecha y Hora: </b>' . $read -> created_at . '<br/><b>Entrada / Salida: </b>' . $io;
                $dates[] = $date;
            }
            $timeLine -> timeline -> date = $dates;
        }

        #echo "<pre>" . print_r($timeLine , true) . "</pre>";die();
        return Response::json($timeLine);
    }
    
    private function addMinutes($date,$minutes_to_add){
        //$minutes_to_add = 10;

$time = new DateTime($date);
$time->add(new DateInterval('PT' . $minutes_to_add . 'M'));

return $time->format('Y-m-d H:i:s');        
    }
    
    private function killVirtualTags($reads){
        //echo "<pre>".print_r($reads,true)."</pre>";die();
        $out=array();
        $toKill=array();
        $i=count($reads);
        while($i){
            $i--;
            $read=$reads[$i];
            if(array_key_exists($read->tag, $toKill)){
                unset($reads[$i]);
                continue;
            }
            if(array_key_exists($read->tag, $out)){
                if($this->addMinutes($out[$read->tag]->created_at, 10)<$read->created_at){
                    $toKil[$read->tag]=$read;
                    unset($out[$read->tag]);
                    unset($reads[$i]);
                    continue;
                }
                
                if($read->io=="Entrada"){
                    unset($out[$read->tag]);
                }else{
                    $toKil[$read->tag]=$read;
                    unset($out[$read->tag]);
                    unset($reads[$i]);
                }
                continue;
            }
            if($read->io=="Salida"){
                $out[$read->tag]=$read;
            }
        }
        //echo "<pre>".print_r($toKill,true)."</pre>";die();
        return $reads;
    }
}
