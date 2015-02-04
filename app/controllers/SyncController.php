<?php

class SyncController extends \BaseController {

    /**
     * Returns the catalog objects to sync
     *
     * @return Response
     */
    public function index() {
        $result = array();
        $result['products'] = Product::select('id', 'upc', 'product_name')->whereType(1)->orderBy("sort_by")->get()->toArray();
        $result['products_bar'] = Product::select('id', 'upc', 'product_name')->whereType(2)->orderBy("sort_by")->get()->toArray();
        $result['active_event'] = JourneyEvent::active()->toArray();
        $result['bars'] = Bar::select('id', 'name')->get()->toArray();
        return Response::json($result);
    }

    /**
     * Captures the inventory dump from the mobile reader
     *
     * @return Response
     */
    public function postInventory() {
        /*
          $file = Input::all();
          return Response::json($file);
         */

        @set_time_limit(0);
        // Process regular products
        if (Input::has('inventories')) {
            DB::transaction(function() {
                $invs = Input::get('inventories');
                if (!is_array($invs)) {
                    $invs = array($invs);
                }

                foreach ($invs as $inv) {
                    $event_id = isset($inv['event_id']) ? (int) $inv['event_id'] : 0;
                    $name = @$inv['name'];
                    $type = isset($inv['type']) ? (int) $inv['type'] : 1;
                    $date = @$inv['datetime'];
                    $event_array = DB::select("select id from (select id,min(sec) from (
                        select id,0 sec from journey_events where ? between started_at and finished_at UNION ALL
                        select id,abs(TO_SECONDS(started_at)-TO_SECONDS(?)) sec from journey_events UNION ALL
                        select id,ifnull(abs(TO_SECONDS(finished_at)-TO_SECONDS(?)),TO_SECONDS(CURRENT_TIMESTAMP)) sec from journey_events
                        ) a group by id order by sec) a limit 0,1", array($date, $date, $date));
                    $event_id = $event_array[0]->id;
                    $epcs = array();
                    $upcs = array();

                    // open csv
                    if (Input::hasFile('csv')) {
                        $file = Input::file('csv');
                        if (!$file->isValid()) {
                            return App::abort(403, 'Invalid Input File');
                        }
                        $path = $file->getRealPath();
                        if (($handle = fopen($path, "r")) !== FALSE) {
                            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                                if (!in_array($data[0], $epcs)) {
                                    array_push($epcs, $data[0]);
                                }
                            }
                            fclose($handle);
                        }
                        @unlink($path);
                    } else if (isset($inv['epcs'])) { //if(Input::has('epcs')) {
                    $file = $inv['epcs'];
                    if (!is_array($file)) {
                        $file = array($file);
                    }
                    foreach ($file as $epc) {
                        if (!in_array($epc, array_keys($epcs))) {
                            $e = new Epc($epc);
                            $epcs[$epc] = $e->getUpc();
                        }
                    }
                }

                foreach ($epcs as $e => $u) {
                    //$epc = new Epc($e);
                    $upc = $u; //$epc -> getUpc();
                    if (!in_array($upc, array_keys($upcs))) {
                        $upcs[$upc] = array('epcs' => array());
                    }
                    array_push($upcs[$upc]['epcs'], $e);
                }

                $i = new Inventory();
                $i->event_id = $event_id;
                $i->name = $name;
                $i->inventory_type = $type;
                if (null !== $date) {
                    $i->created_at = $i->updated_at = new \Carbon\Carbon($date);
                }
                $i->total_tags = count($epcs);
                $i->save();

                foreach (array_chunk($epcs, 200, true) as $chunk) {
                    $dbchunk = array();
                    foreach ($chunk as $epc => $upc) {
                        array_push($dbchunk, array('inventory_id' => $i->id, 'epc' => $epc, 'upc' => $upc, 'created_at' => DB::Raw('current_timestamp'), 'updated_at' => DB::Raw('current_timestamp'), 'deleted_at' => null));
                    }
                    InventoryEpc::insert($dbchunk);
                    unset($dbchunk);
                }
                foreach (array_chunk($upcs, 200, true) as $chunk) {
                    $dbchunk = array();
                    foreach ($chunk as $upc => $epcs) {
                        array_push($dbchunk, array('inventory_id' => $i->id, 'upc' => $upc, 'total' => count($epcs['epcs']), 'created_at' => DB::Raw('current_timestamp'), 'updated_at' => DB::Raw('current_timestamp'), 'deleted_at' => null));
                    }
                    InventorySummary::insert($dbchunk);
                    unset($dbchunk);
                }
                        }
            }); # end transaction
        }

        if (Input::has('bar_orders')) {
            DB::transaction(function() {
                        $invs = Input::get('bar_orders');
                        if (!is_array($invs)) {
                            $invs = array($invs);
                        }
                        foreach ($invs as $inv) {
                            $event_id = isset($inv['event_id']) ? (int) $inv['event_id'] : 0;
                            $bar_id = isset($inv['bar_id']) ? (int) $inv['bar_id'] : 0;
                            $name = @$inv['name'];
                            //$type = isset($inv['type'])?(int)$inv['type']:1;
                            $date = @$inv['datetime'];

                            if (!$bar_id) {
                                return App::abort(403, 'Undefined bar_id in bar order');
                            }

                            // Get active event if no event was specified
                            /* if ($event_id == 0) {
                              $ev = JourneyEvent::active();
                              $mustgen = false;
                              if (!$ev->count()) {
                              //return App::abort(403, 'No Active Event');
                              $mustgen = true;
                              }
                              else {
                              $now = \Carbon\Carbon::now();
                              $start = $now -> subHours(8);
                              $end = $now -> addHours(8);
                              if ($ev -> started_at < $start || $ev -> started_at > $end) {
                              $mustgen = true;
                              }
                              }

                              if ($mustgen) {
                              $now = \Carbon\Carbon::now();
                              // deactivate others
                              JourneyEvent::where('active', 1) -> update(array('active' => 0));
                              $ev = new JourneyEvent();
                              $ev -> event_name = 'Evento en fecha '. $now -> format('d/m/Y g:i a');
                              $ev -> description = 'Evento autogenerado '. $now -> format('d/m/Y g:i a');
                              $ev -> started_at = $now;
                              $ev -> active = 1;
                              $ev -> save();
                              }
                              $event_id = $ev -> id;
                              } */
                            $event_array = DB::select("select id from (select id,min(sec) from (
select id,0 sec from journey_events where ? between started_at and finished_at UNION ALL
select id,abs(TO_SECONDS(started_at)-TO_SECONDS(?)) sec from journey_events UNION ALL
select id,ifnull(abs(TO_SECONDS(finished_at)-TO_SECONDS(?)),TO_SECONDS(CURRENT_TIMESTAMP)) sec from journey_events
) a group by id order by sec) a limit 0,1", array($date, $date, $date));
                            $event_id = $event_array[0]->id;

                            $epcs = array();
                            $upcs = array();

                            if (isset($inv['epcs'])) { //if(Input::has('epcs')) {
                                $file = $inv['epcs'];
                                if (!is_array($file)) {
                                    $file = array($file);
                                }
                                foreach ($file as $epc) {
                                    if (!in_array($epc, array_keys($epcs))) {
                                        $e = new Epc($epc);
                                        $epcs[$epc] = $e->getUpc();
                                    }
                                }
                            }

                            foreach ($epcs as $e => $u) {
                                $epc = new Epc($e);
                                $upc = $u;
                                if (!in_array($upc, array_keys($upcs))) {
                                    $upcs[$upc] = array('epcs' => array());
                                }
                                array_push($upcs[$upc]['epcs'], $e);
                            }

                            $i = new BarOrder();
                            $i->event_id = $event_id;
                            $i->bar_id = $bar_id;
                            $i->name = $name;
                            //$i -> inventory_type = $type;
                            if (null !== $date) {
                                $i->created_at = $i->updated_at = new \Carbon\Carbon($date);
                            }
                            $i->qty = count($epcs);
                            $i->save();

                            foreach (array_chunk($epcs, 200, true) as $chunk) {
                                $dbchunk = array();
                                foreach ($chunk as $epc => $upc) {
                                    array_push($dbchunk, array('bar_order_id' => $i->id, 'epc' => $epc, 'upc' => $upc, 'created_at' => DB::Raw('current_timestamp'), 'updated_at' => DB::Raw('current_timestamp'), 'deleted_at' => null));
                                }
                                BarOrderEpc::insert($dbchunk);
                                unset($dbchunk);
                            }

                            foreach (array_chunk($upcs, 200, true) as $chunk) {
                                $dbchunk = array();
                                foreach ($chunk as $upc => $epcs) {
                                    array_push($dbchunk, array('bar_order_id' => $i->id, 'upc' => $upc, 'total' => count($epcs['epcs']), 'created_at' => DB::Raw('current_timestamp'), 'updated_at' => DB::Raw('current_timestamp'), 'deleted_at' => null));
                                }
                                BarOrderSummary::insert($dbchunk);
                                unset($dbchunk);
                            }
                        }
                    }); # end transaction
        }

        // return in json format
        return Response::json(array('ok' => 'ok'));
    }

    /**
     * Captures the inventory dump from the mobile reader, related to a specific order
     *
     * @return Response
     */
    public function postOrderInventory() {
        if (!Input::has('bar_order_id')) {
            return App::abort(403, 'Invalid Request');
        }

        $order_id = (int) Input::get('bar_order_id');
        $epcs = array();
        if (Input::has('epc')) {
            $epcs = Input::get('epc');
            if (!is_array($epcs)) {
                $epcs = array($epcs);
            }
        }
        $i = new Inventory();
        $i->event_id = $event_id;
        $i->name = '';
        $i->inventory_type = 0;
        $i->total_tags = count($epcs);
        $i->save();

        foreach ($epcs as $epc) {
            $e = new InventoryEpc();
            $e->epc = $epc;
            $i->epcs()->save($e);
        }
        return Response::json(array('inventory_id' => $i->id));
    }

    /**
     * Captures the event log from desktop antennas
     *
     * @return Response
     */
    function postDesktop() {
        if (!Input::has('TagList')) {
            return App::abort(403, 'Invalid Request');
        } else {
            $taglist = Input::get('TagList');
            if (!is_array($taglist)) {
                $taglist = array($taglist);
            }
        }

        DB::transaction(function() use ($taglist) {
            //Check current event and create a new one if not one available
            $current_event = JourneyEvent::whereNull("finished_at")->where(DB::raw("timestampdiff(hour,started_at,CURRENT_TIMESTAMP)"), "<", "12")->first();

        if (is_null($current_event)) {
            JourneyEvent::whereNull("finished_at")->update(array(
                "active" => 0,
                "finished_at" => EventLog::max("created_at"),
            ));

            $event_id = JourneyEvent::insertGetId(array(
                        "event_name" => DB::raw("CONCAT(\"Evento \",date_format(CURRENT_TIMESTAMP,\"%d-%m-%Y %h:%i %p\"))"),
                        "active" => 1,
                        "started_at" => DB::raw("CURRENT_TIMESTAMP"),
                        "created_at" => DB::raw("CURRENT_TIMESTAMP"),
                        "updated_at" => DB::raw("CURRENT_TIMESTAMP"),
            ));
        } else {
            $event_id = $current_event->id;
        }
        //Get traffic rules
        $traffic_rules=DB::table("traffic_rules")->where("action","!=","--")->get();
        $rules=array();
        foreach($traffic_rules as $rule){
            $rules[$rule->antenna_in."-".$rule->antenna_out]=$rule->action;
        }
            
                    $taglist = Input::get('TagList');
                    foreach ($taglist as $tag) {
                        $e = new Epc($tag["Epc"]);
                            $upc=$e->getUPC();
                        if(array_key_exists($tag["InAntenna"]."-".$tag["OutAntenna"],$rules)) {
                            EventLog::insert(array(
                                "event_id" => $event_id,
                                "tag" => $tag["Epc"],
                                "upc" => $upc,
                                "antenna_in" => $tag["InAntenna"],
                                "antenna_out" => $tag["OutAntenna"],
                                "event_name" => $rules[$tag["InAntenna"]."-".$tag["OutAntenna"]],
                                "created_at" => $tag["Date"],
                                "updated_at" => $tag["Date"],
                            ));
                            
                        }
                        
                        $query=sprintf("insert into tag_last_seen (event_id,tag,upc,last_seen,is_out,created_at,updated_at) values (%u,'%s','%s','%s',%u,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP) ON DUPLICATE KEY UPDATE last_seen='%s',is_out=if(is_out=0,%u,is_out),updated_at=CURRENT_TIMESTAMP",
                                    $event_id,
                                    $tag["Epc"],
                                    $upc,
                                    $tag["Date"],
                                    ((array_key_exists($tag["InAntenna"]."-".$tag["OutAntenna"],$rules)?$rules[$tag["InAntenna"]."-".$tag["OutAntenna"]]:"")=="Salida"),
                                    $tag["Date"],
                                    ((array_key_exists($tag["InAntenna"]."-".$tag["OutAntenna"],$rules)?$rules[$tag["InAntenna"]."-".$tag["OutAntenna"]]:"")=="Salida")
                                    );
                            DB::statement($query);
                        
                        DB::table("event_history")->insert(array(
                                "event_id" => $event_id,
                                "tag" => $tag["Epc"],
                                "upc" => $upc,
                                "antenna_in" => $tag["InAntenna"],
                                "antenna_out" => $tag["OutAntenna"],
                                "event_name" => (array_key_exists($tag["InAntenna"]."-".$tag["OutAntenna"],$rules)?$rules[$tag["InAntenna"]."-".$tag["OutAntenna"]]:""),
                                "created_at" => $tag["Date"],
                                "updated_at" => $tag["Date"],
                            ));
                    }
                });
    }

}