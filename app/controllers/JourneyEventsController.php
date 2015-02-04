<?php

class JourneyEventsController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getIndex() {
        return View::make('journeyevents.index');
    }

    public function eventsCSV() {
        $columns = array('id', 'event_name', 'description', 'started_at', 'finished_at', 'active', 'created_at', 'updated_at');
        $headers = array('id', 'Evento', 'Descripción', 'Inicio', 'Fin', 'Activo?', 'creado', 'modificado');
        CSVGenerate::sendCSV($columns, $headers, "journey_events");
    }

    /**
     * Gets the objects to display in a Datatable component.
     *
     * @return Response
     */
    public function getDatatable() {
        /*
          ALTER TABLE `axceso-feng_db`.`journey_events`
          ADD COLUMN `initial_inventory` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `finished_at`,
          ADD COLUMN `final_inventory` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `initial_inventory`;
         * ALTER TABLE `axceso-feng_db`.`journey_events` 
          ADD COLUMN `deleted_at` TIMESTAMP NULL AFTER `updated_at`;
         * CREATE TABLE `axceso-feng_db`.`family` (
          `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
          `family_name` VARCHAR(255) NOT NULL,
          `description` TEXT NOT NULL,
          `created_at` DATETIME NOT NULL,
          `updated_at` DATETIME NOT NULL,
          `deleted_at` DATETIME NULL,
          PRIMARY KEY (`id`),
          UNIQUE INDEX `family_name_UNIQUE` (`family_name` ASC));
         * 
         * ALTER TABLE `axceso-feng_db`.`products` 
          ADD COLUMN `family_id` INT(10) UNSIGNED NOT NULL DEFAULT 1 AFTER `description`;
         * ALTER TABLE `axceso-feng_db`.`products` 
          ADD INDEX `fk_product_family_idx` (`family_id` ASC);
          ALTER TABLE `axceso-feng_db`.`products`
          ADD CONSTRAINT `fk_product_family`
          FOREIGN KEY (`family_id`)
          REFERENCES `axceso-feng_db`.`family` (`id`)
          ON DELETE NO ACTION
          ON UPDATE NO ACTION;
         * 
         * 
         * CREATE TABLE `axceso-feng_db`.`report_sold` (
          `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
          `product_id` INT(10) UNSIGNED NOT NULL,
          `journey_event_id` INT(10) UNSIGNED NOT NULL,
          `count` INT(10) UNSIGNED NOT NULL,
          `created_at` DATETIME NOT NULL,
          `updated_at` DATETIME NOT NULL,
          PRIMARY KEY (`id`),
          INDEX `fk_rs_product_idx` (`product_id` ASC),
          INDEX `fk_rs_event_idx` (`journey_event_id` ASC),
          UNIQUE INDEX `un_report_sold` (`product_id` ASC, `journey_event_id` ASC),
          CONSTRAINT `fk_rs_product`
          FOREIGN KEY (`product_id`)
          REFERENCES `axceso-feng_db`.`products` (`id`)
          ON DELETE NO ACTION
          ON UPDATE NO ACTION,
          CONSTRAINT `fk_rs_event`
          FOREIGN KEY (`journey_event_id`)
          REFERENCES `axceso-feng_db`.`journey_events` (`id`)
          ON DELETE NO ACTION
          ON UPDATE NO ACTION);

          CREATE TABLE `report_traffic` (
          `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
          `event_id` int(10) unsigned NOT NULL,
          `product_id` int(10) unsigned NOT NULL,
          `epc` varchar(48) NOT NULL,
          `out_date` datetime NOT NULL,
          `created_at` datetime NOT NULL,
          `updated_at` datetime NOT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `uq_rt` (`event_id`,`epc`),
          KEY `fk_ rt_event_idx` (`event_id`),
          KEY `fk_rt_product_idx` (`product_id`),
          CONSTRAINT `fk_ rt_event` FOREIGN KEY (`event_id`) REFERENCES `journey_events` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
          CONSTRAINT `fk_rt_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8
         * 
         * ALTER TABLE `axceso-feng_db`.`products` 
          ADD COLUMN `public_price` DECIMAL(13,4) UNSIGNED NOT NULL DEFAULT 0 AFTER `family_id`,
          ADD COLUMN `real_price` DECIMAL(13,4) UNSIGNED NOT NULL DEFAULT 0 AFTER `public_price`;
         * 
         * CREATE TABLE `axceso-feng_db`.`report_not_in_traffic` (
          `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
          `event_id` INT(10) UNSIGNED NOT NULL,
          `product_id` INT(10) UNSIGNED NOT NULL,
          `epc` VARCHAR(48) NOT NULL,
          `created_at` DATETIME NOT NULL,
          `updated_at` DATETIME NOT NULL,
          PRIMARY KEY (`id`),
          INDEX `fk_rnit_event_idx` (`event_id` ASC),
          INDEX `fk_rnit_product_idx` (`product_id` ASC),
          UNIQUE INDEX `un_rnit` (`event_id` ASC, `epc` ASC),
          CONSTRAINT `fk_rnit_event`
          FOREIGN KEY (`event_id`)
          REFERENCES `axceso-feng_db`.`journey_events` (`id`)
          ON DELETE NO ACTION
          ON UPDATE NO ACTION,
          CONSTRAINT `fk_rnit_product`
          FOREIGN KEY (`product_id`)
          REFERENCES `axceso-feng_db`.`products` (`id`)
          ON DELETE NO ACTION
          ON UPDATE NO ACTION);
         * 
         * CREATE TABLE `axceso-feng_db`.`tag_last_seen` (
          `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
          `event_id` INT(10) UNSIGNED NOT NULL,
          `tag` VARCHAR(48) NOT NULL,
          `upc` VARCHAR(48) NOT NULL,
          `last_seen` DATETIME NOT NULL,
          `created_at` DATETIME NOT NULL,
          `updated_at` DATETIME NOT NULL,
          PRIMARY KEY (`id`),
          INDEX `fk_tls_event_idx` (`event_id` ASC),
          UNIQUE INDEX `uq_tls` (`event_id` ASC, `tag` ASC),
          CONSTRAINT `fk_tls_event`
          FOREIGN KEY (`event_id`)
          REFERENCES `axceso-feng_db`.`journey_events` (`id`)
          ON DELETE NO ACTION
          ON UPDATE NO ACTION);


          insert into tag_last_seen (event_id,tag,upc,last_seen,created_at,updated_at) select event_id,tag,upc,created_at,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP from event_log inner join (select max(id) id from event_log group by tag,event_id) last_seen on event_log.id=last_seen.id;


          ALTER TABLE `axceso-feng_db`.`tag_last_seen`
          ADD COLUMN `with_direction` TINYINT(4) NOT NULL DEFAULT 1 AFTER `last_seen`;
         * 
         * ALTER TABLE `axceso-feng_db`.`event_log` 
          ADD COLUMN `event_name` VARCHAR(45) NOT NULL AFTER `antenna_out`;
         * 
         * ALTER TABLE `axceso-feng_db`.`tag_last_seen` 
          ADD COLUMN `is_out` TINYINT(1) NOT NULL DEFAULT 1 AFTER `last_seen`;
         * 
         * CREATE TABLE `event_history` (
          `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
          `event_id` int(10) unsigned NOT NULL,
          `tag` varchar(48) COLLATE utf8_unicode_ci NOT NULL,
          `upc` varchar(48) COLLATE utf8_unicode_ci DEFAULT NULL,
          `antenna_in` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
          `antenna_out` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
          `event_name` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
          `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
          `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
          PRIMARY KEY (`id`),
          KEY `event_history_event_id_foreign` (`event_id`),
          KEY `eh_upc_index` (`upc`),
          CONSTRAINT `event_history_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `journey_events` (`id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
         * 
         * ALTER TABLE `axceso-feng_db`.`report_sold` 
          ADD COLUMN `inv_ini` INT(10) UNSIGNED NOT NULL AFTER `journey_event_id`,
          ADD COLUMN `inv_final` INT(10) UNSIGNED NOT NULL AFTER `inv_ini`;
         * 
         * CREATE TABLE `report_traffic_no_direction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned NOT NULL,
  `epc` varchar(48) NOT NULL,
  `out_date` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_rtnd` (`event_id`,`epc`),
  KEY `fk_ rtnd_event_idx` (`event_id`),
  KEY `fk_rtnd_product_idx` (`product_id`),
  CONSTRAINT `fk_ rtnd_event` FOREIGN KEY (`event_id`) REFERENCES `journey_events` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_rtnd_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

         */

        $es = JourneyEvent::select('id', 'event_name', 'description', 'started_at', 'finished_at', 'initial_inventory', 'final_inventory', 'active', 'created_at', 'updated_at')->orderBy('active', 'desc')->orderBy('created_at', 'desc');
        return Datatables::of($es)->make();
    }

    /**
     * Return specified item.
     *
     * @return Response
     */
    public function getEvent($id) {
        $p = JourneyEvent::find($id);
        if ($p !== null) {
            return Response::json($p);
        }
        return App::abort(403, 'Item not found');
    }

    /**
     * Generates report.
     *
     * @return Response
     */
    public function postGenerate($id) {
        try {
            DB::transaction(function() use ($id) {
                        //Change me
                /*
                         DB::statement("delete from tag_last_seen where event_id=" . $id);
                          DB::statement("insert into tag_last_seen (event_id,tag,upc,last_seen,created_at,updated_at) select event_id,tag,upc,max(created_at),CURRENT_TIMESTAMP,CURRENT_TIMESTAMP from event_log where event_id=" . $id . " group by tag"); 
                          
//*/
                        $invini = Inventory::with("epcs")->where("event_id", "=", $id)->where("inventory_type", "=", 1)->first();
                        $invfinal = Inventory::with("epcs")->where("event_id", "=", $id)->where("inventory_type", "=", 2)->first();
                        $invdesc = Inventory::with("epcs")->where("event_id", "=", $id)->where("inventory_type", "=", 3)->get();

                        //Move Initial Inventory tags to hash table
                        $epcs_ini = array();
                        foreach ($invini->epcs as $inv_epc)
                            $epcs_ini[$inv_epc->epc] = $inv_epc;

                        //Move Final Inventory tags to hash table
                        $epcs_final = array();
                        foreach ($invfinal->epcs as $inv_epc)
                            $epcs_final[$inv_epc->epc] = $inv_epc;

                        //Remove Desc inventories to final inventory
                        foreach ($invdesc as $desc_epcs) {
                            foreach ($desc_epcs->epcs as $epc_desc) {
                                if (array_key_exists($epc_desc->epc, $epcs_final)) {
                                    unset($epcs_final[$epc_desc->epc]);
                                }
                            }
                        }

                        // Remove final inventory to Initial inventory so we can get the sold inventory
                        $epcs_sold = $epcs_ini;
                        foreach ($epcs_final as $desc_epcs) {
                            if (array_key_exists($desc_epcs->epc, $epcs_sold)) {
                                unset($epcs_sold[$desc_epcs->epc]);
                            }
                        }

                        //Get products to hash table
                        $products = Product::get();
                        $hproducts = array();
                        foreach ($products as $product) {
                            $hproducts[$product->upc] = $product;
                        }

                        //Creating Insert Array to Sold Report
                        $sold_summary = array();
                        foreach ($epcs_ini as $inv_epc) {
                            if (array_key_exists($inv_epc->upc, $sold_summary))
                                $sold_summary[$inv_epc->upc]["inv_ini"]++;
                            else
                                $sold_summary[$inv_epc->upc] = array(
                                    "product_id" => $hproducts[$inv_epc->upc]->id,
                                    "journey_event_id" => $id,
                                    "inv_ini" => 1,
                                    "inv_final" => 0,
                                    "count" => 0,
                                    "created_at" => DB::raw("CURRENT_TIMESTAMP"),
                                    "updated_at" => DB::raw("CURRENT_TIMESTAMP"),
                                );
                        }

                        foreach ($epcs_final as $inv_epc) {
                            if (array_key_exists($inv_epc->upc, $sold_summary))
                                $sold_summary[$inv_epc->upc]["inv_final"]++;
                        }

                        foreach ($epcs_sold as $inv_epc) {
                            if (array_key_exists($inv_epc->upc, $sold_summary))
                                $sold_summary[$inv_epc->upc]["count"]++;
                        }


                        DB::table("report_sold")->where("journey_event_id", $id)->delete();
                        if (count($sold_summary) != 0)
                            DB::table("report_sold")->insert($sold_summary);

                        $traffic_summary = array();
                        $traffic_no_direction_summary = array();
                        $not_traffic_summary = array();
                        $last_seen = array();

                        $temp = DB::table("tag_last_seen")->where("event_id", $id)->get();

                        foreach ($temp as $row) {
                            $last_seen[$row->tag] = $row;
                        }

                        foreach ($epcs_sold as $inv_epc) {
                            if (!array_key_exists($inv_epc->epc, $last_seen)) {
                                $not_traffic_summary[] = array(
                                    "event_id" => $id,
                                    "product_id" => $hproducts[$inv_epc->upc]->id,
                                    "epc" => $inv_epc->epc,
                                    "created_at" => DB::raw("CURRENT_TIMESTAMP"),
                                    "updated_at" => DB::raw("CURRENT_TIMESTAMP"),
                                );
                                continue;
                            }
                            
                            if ($last_seen[$inv_epc->epc]->is_out == 1)
                                $traffic_summary[] = array(
                                    "event_id" => $id,
                                    "product_id" => $hproducts[$inv_epc->upc]->id,
                                    "epc" => $inv_epc->epc,
                                    "out_date" => $last_seen[$inv_epc->epc]->last_seen,
                                    "created_at" => DB::raw("CURRENT_TIMESTAMP"),
                                    "updated_at" => DB::raw("CURRENT_TIMESTAMP"),
                                );
                            else
                                $traffic_no_direction_summary[] = array(
                                    "event_id" => $id,
                                    "product_id" => $hproducts[$inv_epc->upc]->id,
                                    "epc" => $inv_epc->epc,
                                    "out_date" => $last_seen[$inv_epc->epc]->last_seen,
                                    "created_at" => DB::raw("CURRENT_TIMESTAMP"),
                                    "updated_at" => DB::raw("CURRENT_TIMESTAMP"),
                                );
                        }

                        DB::table("report_traffic")->where("event_id", "=", $id)->delete();
                        DB::table("report_traffic_no_direction")->where("event_id", "=", $id)->delete();
                        DB::table("report_not_in_traffic")->where("event_id", "=", $id)->delete();
                        if (count($traffic_summary) != 0)
                            DB::table("report_traffic")->insert($traffic_summary);
                        if (count($not_traffic_summary) != 0)
                            DB::table("report_not_in_traffic")->insert($not_traffic_summary);
                        if (count($traffic_no_direction_summary) != 0)
                            DB::table("report_traffic_no_direction")->insert($traffic_no_direction_summary);
                    });
        } catch (Exception $e) {
            return json_encode(array(
                "message" => $e->getMessage(),
                "status" => false,
                "response" => null,
            ));
        }

        return json_encode(array(
            "message" => null,
            "status" => true,
            "response" => null,
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postIndex($id = 0) {
        if (Request::has('event')) {
            $ev = Request::get('event');
            if ($id == 0) {
                $ev['started_at'] = Carbon\Carbon::now();
                $event = new JourneyEvent($ev);
            }
            if ((int) @$ev['active'] == 1) {
                // deactivate others
                JourneyEvent::where('active', 1)->update(array('active' => 0));
            }
            if ($id != 0) {
                $event = JourneyEvent::find($id);
                if (null === $event) {
                    return App::abort(403, 'Item not found');
                }
                $event->fill($ev);
            }
            $event->save();
            return Response::json($event);
        }
        return App::abort(403, 'Invalid Request');
    }

    /**
     * Perform a logical delete on an object.
     *
     * @return Response
     */
    public function postDelete($id) {
        $p = JourneyEvent::find($id);
        if ($p) {
            $p->delete();
        }
        return Response::json(array('ok' => 'ok'));
    }

    /**
     * Display the report form and, if the needed input values are available, returns the report data as well.
     *
     * @return Response
     */
    public function getReport($event_id = 0) {
        //echo "<pre>".print_r(Input::All(),true)."</pre>";die();
        if ($event_id == 0) {
            $ev = JourneyEvent::active();
            if ($ev->count()) {
                $event_id = $ev->id;
            }
        } else {
            $ev = JourneyEvent::find($event_id);
        }

        if (Input::get('move_to_event') != "0" && Input::has('move_to_event')) {
            Inventory::where("id", "=", Input::get('move_to_event'))
                    ->update(array(
                        "event_id" => Input::get('new_event'),
                        "inventory_type" => 0,
            ));
        }

        if (Input::get('change_inventory_type') != "0" && Input::has('change_inventory_type')) {
            if (Input::get('new_inventory_type') == "1") {
                Inventory::where("event_id", "=", $event_id)
                        ->where("inventory_type", "=", 1)
                        ->update(array(
                            "inventory_type" => 0,
                ));

                $ev->initial_inventory = Inventory::find(Input::get('change_inventory_type'))->epcs->count();
                $ev->save();
            }

            if (Input::get('new_inventory_type') == "2")
                Inventory::where("event_id", "=", $event_id)
                        ->where("inventory_type", "=", 2)
                        ->update(array(
                            "inventory_type" => 0,
                ));

            Inventory::where("id", "=", Input::get('change_inventory_type'))
                    ->update(array(
                        "inventory_type" => Input::get('new_inventory_type'),
            ));
        }

        if ((Input::get('move_to_event') != "0" && Input::has('move_to_event')) || (Input::get('change_inventory_type') != "0" && Input::has('change_inventory_type'))) {
            $invini = Inventory::where("event_id", "=", $event_id)->where("inventory_type", "=", 1)->first();
            $invfinal = Inventory::where("event_id", "=", $event_id)->where("inventory_type", "=", 2)->first();
            $invdesc = Inventory::where("event_id", "=", $event_id)->where("inventory_type", "=", 3)->get();



            if ($invini)
                $ev->initial_inventory = $invini->epcs->count();
            else
                $ev->initial_inventory = null;

            if ($invfinal) {
                $epcs_final_raw = $invfinal->epcs;
                $epcs_final = array();
                foreach ($epcs_final_raw as $inv_epc)
                    $epcs_final[$inv_epc->epc] = $inv_epc->epc;

                foreach ($invdesc as $desc_epcs) {
                    foreach ($desc_epcs->epcs as $epc_desc) {
                        if (array_key_exists($epc_desc->epc, $epcs_final)) {
                            unset($epcs_final[$epc_desc->epc]);
                        }
                    }
                }
                $ev->final_inventory = count($epcs_final);
            }
            else
                $ev->final_inventory = null;


            $ev->save();
        }


        $inventories = null;
        $invfull = null;
        $products = null;
        $iids = array();
        if ($event_id != 0) {
            $eventinv = JourneyEvent::with(array('inventories'))->find($event_id);
            if (Input::has('inventory_id')) {
                $iids = Input::get('inventory_id', array());
                if (!is_array($iids)) {
                    $iids = array($iids);
                }
                if (count($iids)) {
                    $event = JourneyEvent::with(array('inventories' => function($query) use ($iids) {
                                    return $query->whereIn('id', $iids);
                                }, 'inventories.summaries.product'))->find($event_id);
                    $inventories = $event->inventories;
                    foreach ($inventories as &$i) {
                        $prodarr = array();
                        foreach ($i->summaries as $s) {
                            if (!isset($products[$s->product['upc']])) {
                                $products[$s->product['upc']] = $s->product;
                            }
                            $prodarr[$s->product['upc']] = $s;
                        }
                        $i['productarray'] = $prodarr;
                        unset($prodarr);
                        unset($i);
                    }
                }
            }
            $queries = DB::getQueryLog();
            //var_dump($queries); die();
            $invfull = $eventinv->inventories;
        }

        $events = JourneyEvent::orderBy('active', 'desc')->orderBy('started_at', 'desc')->get();

        return View::make('journeyevents.report', array('events' => $events, 'event_id' => $event_id, 'inventories' => $inventories, 'products' => $products, 'invfull' => $invfull, 'iids' => $iids,));
    }

    public function reportCSV($event_id = 0) {
        if ($event_id == 0) {
            $ev = JourneyEvent::active();
            if ($ev->count()) {
                $event_id = $ev->id;
            }
        }

        $inventories = null;
        $invfull = null;
        $products = null;
        $iids = array();
        if ($event_id != 0) {
            $eventinv = JourneyEvent::with(array('inventories'))->find($event_id);
            if (Input::has('inventory_id')) {
                $iids = Input::get('inventory_id', array());
                if (!is_array($iids)) {
                    $iids = array($iids);
                }
                if (count($iids)) {
                    $event = JourneyEvent::with(array('inventories' => function($query) use ($iids) {
                                    return $query->whereIn('id', $iids);
                                }, 'inventories.summaries.product'))->find($event_id);
                    $inventories = $event->inventories;
                    foreach ($inventories as &$i) {
                        $prodarr = array();
                        foreach ($i->summaries as $s) {
                            if (!isset($products[$s->product['upc']])) {
                                $products[$s->product['upc']] = $s->product;
                            }
                            $prodarr[$s->product['upc']] = $s;
                        }
                        $i['productarray'] = $prodarr;
                        unset($prodarr);
                        unset($i);
                    }
                }
            }
            $queries = DB::getQueryLog();
            //var_dump($queries); die();
            $invfull = $eventinv->inventories;
        }
        //---------------
        $grid = array();
        $tempa = array(
            "Producto",
            "UPC",
        );
        foreach ($inventories as $i) {
            $tempa[] = $i->name;
        }
        $tempa[] = "Diferencias";
        $grid[] = $tempa;

        foreach ($products as $p) {
            unset($tempa);
            $tempa = array(
                $p['upc'],
                $p['product_name'],
            );
            $first = null;
            $diff = 0;
            foreach ($inventories as $i) {
                if (null === $first) {
                    $first = isset($i->productarray[$p['upc']]['total']) ? $i->productarray[$p['upc']]['total'] : 0;
                    $diff = 0;
                }
                if (isset($i->productarray[$p['upc']]['total'])) {
                    $diff = $first - $i->productarray[$p['upc']]['total'];
                } else {
                    $diff = $first;
                }
                $tempa[] = $i->productarray[$p['upc']]['total'] or '-';
            }
            $tempa[] = $diff;
            $grid[] = $tempa;
        }

        CSVGenerate::sendRawCSV($grid);
    }

}