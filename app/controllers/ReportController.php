<?php

class ReportController extends \BaseController {

    public function getIndex() {
//select date_format(started_at,'%Y') year from journey_events where initial_inventory is not null and final_inventory is not null group by date_format(started_at,'%Y');
        $years = DB::table("journey_events")
                ->whereNotNull("initial_inventory")
                ->whereNotNull("final_inventory")
                ->whereExists(function($query) {
                            $query->select(DB::raw(1))
                            ->from('report_sold')
                            ->whereRaw('report_sold.journey_event_id = journey_events.id');
                        })
                ->groupBy(DB::raw("date_format(started_at,'%Y')"))
                ->orderBy(DB::raw("date_format(started_at,'%Y')"), "desc")
                ->get(array(DB::raw("date_format(started_at,'%Y') year")));

        $months = DB::table("journey_events")
                ->whereNotNull("initial_inventory")
                ->whereNotNull("final_inventory")
                ->whereExists(function($query) {
                            $query->select(DB::raw(1))
                            ->from('report_sold')
                            ->whereRaw('report_sold.journey_event_id = journey_events.id');
                        })
                ->groupBy(DB::raw("date_format(started_at,'%Y%m')"))
                ->orderBy(DB::raw("date_format(started_at,'%Y%m')"), "desc")
                ->get(array(DB::raw("date_format(started_at,'%Y') year"), DB::raw("date_format(started_at,'%m') month")));

        $events = DB::table("journey_events")
                ->whereNotNull("initial_inventory")
                ->whereNotNull("final_inventory")
                ->whereExists(function($query) {
                            $query->select(DB::raw(1))
                            ->from('report_sold')
                            ->whereRaw('report_sold.journey_event_id = journey_events.id');
                        })
                ->groupBy("id")
                ->orderBy("id", "desc")
                ->get(array(DB::raw("date_format(started_at,'%Y%m') month"), "id", "event_name"));

        return View::make('report.index', array(
                    "years" => $years,
                    "months" => $months,
                    "events" => $events,
        ));
    }

    /**
     * Gets the objects to display in a Datatable component.
     *
     * @return Response
     */
    public function getDatatable() {
        return Datatables::of($this->generateQuery())->make();
    }

    public function getFooter() {
        $query = $this->generateQuery();

        $acol = $this->getColumns(Input::get("report_type"));
        $columns = $acol["columns"];

        $query->where(function($query) use ($columns) {
                    foreach ($columns as $column) {
                        $query->orWhere($column, "LIKE", '%' . Input::get("sSearch") . '%');
                    }
                });
        $grid = $query->get();
        $footer = array();
        foreach ($grid as $row) {
            foreach ($row as $index => $field) {
                if (!array_key_exists($index, $footer))
                    $footer[$index] = 0;

                $footer[$index]+=$field;
            }
        }

        return Response::json($footer);
    }

    private function getColumns($report_type) {
        switch ($report_type) {
            case "1":
                switch (Input::get("group_type")) {
                    case "Producto":
                        $headers = array('upc', 'Producto', 'Familia', 'Inv. Inicial', 'Inv. Final', 'Cantidad', 'Venta', 'Costo', 'Total Venta', 'Total Costo');
                        $columns = array('upc', 'product_name', 'family_name', 'inv_ini', 'inv_final', 'count', 'public_price', 'real_price', 'sold', 'cost');
                        break;
                    case "Familia":
                        $headers = array('Familia', 'Inv. Inicial', 'Inv. Final', 'Cantidad', 'Venta', 'Costo', 'Total Venta', 'Total Costo');
                        $columns = array('family_name', 'inv_ini', 'inv_final', 'count', 'public_price', 'real_price', 'sold', 'cost');
                        break;
                }
                break;
            case "2":
                switch (Input::get("group_type")) {
                    case "Producto":
                        $headers = array('upc', 'Producto', 'Familia', 'Hora', 'Cantidad', 'Venta', 'Costo');
                        $columns = array('upc', 'product_name', 'family_name', 'hour', 'count', 'sold', 'cost');
                        break;
                    case "Familia":
                        $headers = array('Familia', 'Hora', 'Cantidad', 'Venta', 'Costo');
                        $columns = array('family_name', 'hour', 'count', 'sold', 'cost');
                        break;
                }
                break;
            case "3":
                switch (Input::get("group_type")) {
                    case "Producto":
                        $headers = array('upc', 'Producto', 'Familia', 'Cantidad', 'Venta', 'Costo');
                        $columns = array('upc', 'product_name', 'family_name', 'count', 'sold', 'cost');
                        break;
                    case "Familia":
                        $headers = array('Familia', 'Cantidad', 'Venta', 'Costo');
                        $columns = array('family_name', 'count', 'sold', 'cost');
                        break;
                }
                break;
            /*case "4":
                switch (Input::get("group_type")) {
                    case "Producto":
                        $headers = array('upc', 'Producto', 'Familia', 'Cantidad', 'Venta', 'Costo');
                        $columns = array('upc', 'product_name', 'family_name', 'count', 'sold', 'cost');
                        break;
                    case "Familia":
                        $headers = array('Familia', 'Cantidad', 'Venta', 'Costo');
                        $columns = array('family_name', 'count', 'sold', 'cost');
                        break;
                }
                break;*/
            case "4":
                        $headers = array('epc','upc', 'Producto', 'Familia', 'Venta', 'Costo');
                        $columns = array('tag','products.upc', 'product_name', 'family_name', 'public_price', 'real_price');
                   
                break;
            case "5":
                        $headers = array('epc','upc', 'Producto', 'Familia', 'Fecha', 'Venta', 'Costo');
                        $columns = array('tag','products.upc', 'product_name', 'family_name', 'event_history.created_at', 'public_price', 'real_price');
                   
                break;
        }

        return array("headers" => $headers, "columns" => $columns);
    }

    public function reportCSV() {
        $input = Input::all();
        $query = $this->generateQuery();


        $acol = $this->getColumns(Input::get("report_type"));
        $headers = $acol["headers"];
        $columns = $acol["columns"];

        $query->where(function($query) use ($input, $columns) {
                    foreach ($columns as $column) {
                        $query->orWhere($column, "LIKE", '%' . $input['sSearch'] . '%');
                    }
                });
        $query->orderBy($columns[$input["iSortCol_0"]], $input["sSortDir_0"]);
        $grid = $query->get();

        array_unshift($grid, $headers);

        CSVGenerate::sendRawCSV($grid);
    }

    private function generateQuery() {
        $year = Input::get("year");
        $month = Input::get("month");
        $event = Input::get("event");
        $group_type = Input::get("group_type");
        $report_type = Input::get("report_type");

        switch ($report_type) {
            case "1":
                switch ($group_type) {
                    case "Producto":
                        switch ("y" . ($year != "" ? "1" : "0") . "m" . ($month != "" ? "1" : "0") . "e" . ($event != "" ? "1" : "0")) {
                            case "y0m0e0":
                                $report_sold = "select family_id,upc,product_name,'--' inv_ini,'--' inv_final,sum(count) count,public_price,real_price,sum(public_price*count) sold,sum(real_price*count) cost from report_sold inner join products on report_sold.product_id=products.id group by product_id";
                                break;
                            case "y1m0e0":
                                $report_sold = "select family_id,upc,product_name,'--' inv_ini,'--' inv_final,sum(count) count,public_price,real_price,sum(public_price*count) sold,sum(real_price*count) cost from report_sold inner join products on report_sold.product_id=products.id inner join journey_events on report_sold.journey_event_id=journey_events.id where date_format(started_at,'%Y')='" . $year . "' group by product_id";
                                break;
                            case "y1m1e0":
                                $report_sold = "select family_id,upc,product_name,'--' inv_ini,'--' inv_final,sum(count) count,public_price,real_price,sum(public_price*count) sold,sum(real_price*count) cost from report_sold inner join products on report_sold.product_id=products.id inner join journey_events on report_sold.journey_event_id=journey_events.id where date_format(started_at,'%Y%m')='" . $month . "' group by product_id";
                                break;
                            case "y1m1e1":
                                $report_sold = "select family_id,upc,product_name,sum(inv_ini) inv_ini,sum(inv_final) inv_final,sum(count) count,public_price,real_price,sum(public_price*count) sold,sum(real_price*count) cost from report_sold inner join products on report_sold.product_id=products.id where report_sold.journey_event_id='" . $event . "' group by product_id";
                                break;
                        }

                        $es = DB::table(DB::raw("(" . $report_sold . ") report_sold"))
                                ->join("family", "report_sold.family_id", "=", "family.id")
                                ->select('report_sold.upc', 'report_sold.product_name', 'family_name', 'inv_ini', 'inv_final', 'count', 'public_price', 'real_price', 'sold', 'cost');
                        break;
                    case "Familia":
                        switch ("y" . ($year != "" ? "1" : "0") . "m" . ($month != "" ? "1" : "0") . "e" . ($event != "" ? "1" : "0")) {
                            case "y0m0e0":
                                $report_sold = "select family_id,'--' inv_ini,'--' inv_final,sum(count) count,public_price,real_price,sum(public_price*count) sold,sum(real_price*count) cost from report_sold inner join products on report_sold.product_id=products.id group by family_id";
                                break;
                            case "y1m0e0":
                                $report_sold = "select family_id,'--' inv_ini,'--' inv_final,sum(count) count,public_price,real_price,sum(public_price*count) sold,sum(real_price*count) cost from report_sold inner join products on report_sold.product_id=products.id inner join journey_events on report_sold.journey_event_id=journey_events.id where date_format(started_at,'%Y')='" . $year . "' group by family_id";
                                break;
                            case "y1m1e0":
                                $report_sold = "select family_id,'--' inv_ini,'--' inv_final,sum(count) count,public_price,real_price,sum(public_price*count) sold,sum(real_price*count) cost from report_sold inner join products on report_sold.product_id=products.id inner join journey_events on report_sold.journey_event_id=journey_events.id where date_format(started_at,'%Y%m')='" . $month . "' group by family_id";
                                break;
                            case "y1m1e1":
                                $report_sold = "select family_id,sum(inv_ini) inv_ini,sum(inv_final) inv_final,sum(count) count,public_price,real_price,sum(public_price*count) sold,sum(real_price*count) cost from report_sold inner join products on report_sold.product_id=products.id where report_sold.journey_event_id='" . $event . "' group by family_id";
                                break;
                        }

                        $es = DB::table(DB::raw("(" . $report_sold . ") report_sold"))
                                ->join("family", "report_sold.family_id", "=", "family.id")
                                ->select('family_name', 'inv_ini', 'inv_final', 'count', 'public_price', 'real_price', 'sold', 'cost');
                        break;
                }
                break;
            case "2":
                switch ($group_type) {
                    case "Producto":
                        switch ("y" . ($year != "" ? "1" : "0") . "m" . ($month != "" ? "1" : "0") . "e" . ($event != "" ? "1" : "0")) {
                            case "y0m0e0":
                                $report_sold = "select family_id,products.upc,product_name,date_format(out_date,'%k')+0 hour,count(1) count,public_price,real_price,sum(public_price) sold,sum(real_price) cost from report_traffic inner join products on report_traffic.product_id=products.id group by product_id,date_format(out_date,'%H')";
                                break;
                            case "y1m0e0":
                                $report_sold = "select family_id,products.upc,product_name,date_format(out_date,'%k')+0 hour,count(1) count,public_price,real_price,sum(public_price) sold,sum(real_price) cost from report_traffic inner join products on report_traffic.product_id=products.id inner join journey_events on report_traffic.event_id=journey_events.id where date_format(started_at,'%Y')='" . $year . "' group by product_id,date_format(out_date,'%H')";
                                break;
                            case "y1m1e0":
                                $report_sold = "select family_id,products.upc,product_name,date_format(out_date,'%k')+0 hour,count(1) count,public_price,real_price,sum(public_price) sold,sum(real_price) cost from report_traffic inner join products on report_traffic.product_id=products.id inner join journey_events on report_traffic.event_id=journey_events.id where date_format(started_at,'%Y%m')='" . $month . "' group by product_id,date_format(out_date,'%H')";
                                break;
                            case "y1m1e1":
                                $report_sold = "select family_id,products.upc,product_name,date_format(out_date,'%k')+0 hour,count(1) count,public_price,real_price,sum(public_price) sold,sum(real_price) cost from report_traffic inner join products on report_traffic.product_id=products.id where report_traffic.event_id='" . $event . "' group by product_id,date_format(out_date,'%H')";
                                break;
                        }

                        $es = DB::table(DB::raw("(" . $report_sold . ") report_sold"))
                                ->join("family", "family_id", "=", "family.id")
                                ->select('upc', 'product_name', 'family_name', 'hour', 'count', 'public_price', 'real_price', 'sold', 'cost');
                        break;
                    case "Familia":
                        switch ("y" . ($year != "" ? "1" : "0") . "m" . ($month != "" ? "1" : "0") . "e" . ($event != "" ? "1" : "0")) {
                            case "y0m0e0":
                                $report_sold = "select family_id,date_format(out_date,'%k') hour,count(1) count,public_price,real_price,sum(public_price) sold,sum(real_price) cost from report_traffic inner join products on report_traffic.product_id=products.id group by family_id,date_format(out_date,'%H')";
                                break;
                            case "y1m0e0":
                                $report_sold = "select family_id,date_format(out_date,'%k') hour,count(1) count,public_price,real_price,sum(public_price) sold,sum(real_price) cost from report_traffic inner join products on report_traffic.product_id=products.id inner join journey_events on report_traffic.event_id=journey_events.id where date_format(started_at,'%Y')='" . $year . "' group by family_id,date_format(out_date,'%H')";
                                break;
                            case "y1m1e0":
                                $report_sold = "select family_id,date_format(out_date,'%k') hour,count(1) count,public_price,real_price,sum(public_price) sold,sum(real_price) cost from report_traffic inner join products on report_traffic.product_id=products.id inner join journey_events on report_traffic.event_id=journey_events.id where date_format(started_at,'%Y%m')='" . $month . "' group by family_id,date_format(out_date,'%H')";
                                break;
                            case "y1m1e1":
                                $report_sold = "select family_id,date_format(out_date,'%k') hour,count(1) count,public_price,real_price,sum(public_price) sold,sum(real_price) cost from report_traffic inner join products on report_traffic.product_id=products.id where report_traffic.event_id='" . $event . "' group by family_id,date_format(out_date,'%H')";
                                break;
                        }

                        $es = DB::table(DB::raw("(" . $report_sold . ") report_sold"))
                                ->join("family", "report_sold.family_id", "=", "family.id")
                                ->select('family_name', 'hour', 'count', 'public_price', 'real_price', 'sold', 'cost');
                        break;
                }
                break;
            case "3":
                switch ($group_type) {
                    case "Producto":
                        switch ("y" . ($year != "" ? "1" : "0") . "m" . ($month != "" ? "1" : "0") . "e" . ($event != "" ? "1" : "0")) {
                            case "y0m0e0":
                                $report_sold = "select product_id,sum(count) count,sum(public_price*count) sold,sum(real_price*count) cost from report_sold inner join products on report_sold.product_id=products.id group by product_id";
                                break;
                            case "y1m0e0":
                                $report_sold = "select product_id,sum(count) count,sum(public_price*count) sold,sum(real_price*count) cost from report_sold inner join products on report_sold.product_id=products.id inner join journey_events on report_sold.journey_event_id=journey_events.id where date_format(started_at,'%Y')='" . $year . "' group by product_id";
                                break;
                            case "y1m1e0":
                                $report_sold = "select product_id,sum(count) count,sum(public_price*count) sold,sum(real_price*count) cost from report_sold inner join products on report_sold.product_id=products.id inner join journey_events on report_sold.journey_event_id=journey_events.id where date_format(started_at,'%Y%m')='" . $month . "' group by product_id";
                                break;
                            case "y1m1e1":
                                $report_sold = "select product_id,sum(count) count,sum(public_price*count) sold,sum(real_price*count) cost from report_sold inner join products on report_sold.product_id=products.id where report_sold.journey_event_id='" . $event . "' group by product_id";
                                break;
                        }

                        $es = DB::table(DB::raw("(" . $report_sold . ") report_sold"))
                                ->join("products", "report_sold.product_id", "=", "products.id")
                                ->join("family", "products.family_id", "=", "family.id")
                                ->orderBy("count", "desc")
                                ->take(10)
                                ->select('upc', 'product_name', 'family_name', 'count', 'sold', 'cost');
                        break;
                    case "Familia":
                        switch ("y" . ($year != "" ? "1" : "0") . "m" . ($month != "" ? "1" : "0") . "e" . ($event != "" ? "1" : "0")) {
                            case "y0m0e0":
                                $report_sold = "select family_id,sum(count) count,sum(public_price*count) sold,sum(real_price*count) cost from report_sold inner join products on report_sold.product_id=products.id group by family_id";
                                break;
                            case "y1m0e0":
                                $report_sold = "select family_id,sum(count) count,sum(public_price*count) sold,sum(real_price*count) cost from report_sold inner join products on report_sold.product_id=products.id inner join journey_events on report_sold.journey_event_id=journey_events.id where date_format(started_at,'%Y')='" . $year . "' group by family_id";
                                break;
                            case "y1m1e0":
                                $report_sold = "select family_id,sum(count) count,sum(public_price*count) sold,sum(real_price*count) cost from report_sold inner join products on report_sold.product_id=products.id inner join journey_events on report_sold.journey_event_id=journey_events.id where date_format(started_at,'%Y%m')='" . $month . "' group by family_id";
                                break;
                            case "y1m1e1":
                                $report_sold = "select family_id,sum(count) count,sum(public_price*count) sold,sum(real_price*count) cost from report_sold inner join products on report_sold.product_id=products.id where report_sold.journey_event_id='" . $event . "' group by family_id";
                                break;
                        }

                        $es = DB::table(DB::raw("(" . $report_sold . ") report_sold"))
                                ->join("family", "report_sold.family_id", "=", "family.id")
                                ->orderBy("count", "desc")
                                ->take(10)
                                ->select('family_name', 'count', 'sold', 'cost');
                        break;
                }
                break;
            /*case "4":
                switch ($group_type) {
                    case "Producto":
                        switch ("y" . ($year != "" ? "1" : "0") . "m" . ($month != "" ? "1" : "0") . "e" . ($event != "" ? "1" : "0")) {
                            case "y0m0e0":
                                $report_sold = "select family_id,upc,product_name,count(1) count,sum(public_price) sold,sum(real_price) cost from report_not_in_traffic inner join products on report_not_in_traffic.product_id=products.id group by product_id";
                                break;
                            case "y1m0e0":
                                $report_sold = "select family_id,upc,product_name,count(1) count,sum(public_price) sold,sum(real_price) cost from report_not_in_traffic inner join products on report_not_in_traffic.product_id=products.id inner join journey_events on report_not_in_traffic.event_id=journey_events.id where date_format(started_at,'%Y')='" . $year . "' group by product_id";
                                break;
                            case "y1m1e0":
                                $report_sold = "select family_id,upc,product_name,count(1) count,sum(public_price) sold,sum(real_price) cost from report_not_in_traffic inner join products on report_not_in_traffic.product_id=products.id inner join journey_events on report_not_in_traffic.event_id=journey_events.id where date_format(started_at,'%Y%m')='" . $month . "' group by product_id";
                                break;
                            case "y1m1e1":
                                $report_sold = "select family_id,upc,product_name,count(1) count,sum(public_price) sold,sum(real_price) cost from report_not_in_traffic inner join products on report_not_in_traffic.product_id=products.id where report_not_in_traffic.event_id='" . $event . "' group by product_id";
                                break;
                        }

                        $es = DB::table(DB::raw("(" . $report_sold . ") report_sold"))
                                ->join("family", "family_id", "=", "family.id")
                                ->select('upc', 'product_name', 'family_name', 'count', 'sold', 'cost');
                        break;
                    case "Familia":
                        switch ("y" . ($year != "" ? "1" : "0") . "m" . ($month != "" ? "1" : "0") . "e" . ($event != "" ? "1" : "0")) {
                            case "y0m0e0":
                                $report_sold = "select family_id,count(1) count,sum(public_price) sold,sum(real_price) cost from report_not_in_traffic inner join products on report_not_in_traffic.product_id=products.id group by family_id";
                                break;
                            case "y1m0e0":
                                $report_sold = "select family_id,count(1) count,sum(public_price) sold,sum(real_price) cost from report_not_in_traffic inner join products on report_not_in_traffic.product_id=products.id inner join journey_events on report_not_in_traffic.event_id=journey_events.id where date_format(started_at,'%Y')='" . $year . "' group by family_id";
                                break;
                            case "y1m1e0":
                                $report_sold = "select family_id,count(1) count,sum(public_price) sold,sum(real_price) cost from report_not_in_traffic inner join products on report_not_in_traffic.product_id=products.id inner join journey_events on report_not_in_traffic.event_id=journey_events.id where date_format(started_at,'%Y%m')='" . $month . "' group by family_id";
                                break;
                            case "y1m1e1":
                                $report_sold = "select family_id,count(1) count,sum(public_price) sold,sum(real_price) cost from report_not_in_traffic inner join products on report_not_in_traffic.product_id=products.id where report_not_in_traffic.event_id='" . $event . "' group by family_id";
                                break;
                        }

                        $es = DB::table(DB::raw("(" . $report_sold . ") report_sold"))
                                ->join("family", "report_sold.family_id", "=", "family.id")
                                ->select('family_name', 'count', 'sold', 'cost');
                        break;
                }
                break;*/
            case "4":
                
                //inner join event_history on report_traffic_no_direction.epc=event_history.tag;
                $es = DB::table("report_not_in_traffic")
                        ->join("products","product_id","=","products.id")
                        ->join("family", "family_id", "=", "family.id")
                        ->join("journey_events","journey_events.id","=","report_not_in_traffic.event_id")
                        ->select('epc','products.upc', 'product_name', 'family_name', 'public_price', 'real_price');
                switch ("y" . ($year != "" ? "1" : "0") . "m" . ($month != "" ? "1" : "0") . "e" . ($event != "" ? "1" : "0")) {
                    case "y1m0e0":
                        //where date_format(started_at,'%Y')='" . $year . "'
                        $es->where(DB::raw("date_format(started_at,'%Y')"),$year);
                        break;
                    case "y1m1e0":
                        $es->where(DB::raw("date_format(started_at,'%Y%m')"),$month);
                        break;
                    case "y1m1e1":
                        //$report_sold = "select family_id,upc,product_name,count(1) count,sum(public_price) sold,sum(real_price) cost from report_not_in_traffic inner join products on report_not_in_traffic.product_id=products.id where report_not_in_traffic.event_id='" . $event . "' group by product_id";
                        $es->where("report_not_in_traffic.event_id",$event);
                        break;
                }
                break;
            case "5":
                
                //inner join event_history on report_traffic_no_direction.epc=event_history.tag;
                $es = DB::table("report_traffic_no_direction")
                        ->join("products","product_id","=","products.id")
                        ->join("family", "family_id", "=", "family.id")
                        ->join("event_history",function($query){
                            $query->on("tag","=","epc");
                            $query->on("report_traffic_no_direction.event_id","=","event_history.event_id");
                        })
                        ->join("journey_events","journey_events.id","=","report_traffic_no_direction.event_id")
                        ->select('epc','products.upc', 'product_name', 'family_name', 'event_history.created_at', 'public_price', 'real_price');
                switch ("y" . ($year != "" ? "1" : "0") . "m" . ($month != "" ? "1" : "0") . "e" . ($event != "" ? "1" : "0")) {
                    case "y1m0e0":
                        //where date_format(started_at,'%Y')='" . $year . "'
                        $es->where(DB::raw("date_format(started_at,'%Y')"),$year);
                        break;
                    case "y1m1e0":
                        $es->where(DB::raw("date_format(started_at,'%Y%m')"),$month);
                        break;
                    case "y1m1e1":
                        //$report_sold = "select family_id,upc,product_name,count(1) count,sum(public_price) sold,sum(real_price) cost from report_not_in_traffic inner join products on report_not_in_traffic.product_id=products.id where report_not_in_traffic.event_id='" . $event . "' group by product_id";
                        $es->where("report_traffic_no_direction.event_id",$event);
                        break;
                }
                break;
        }

        return $es;
    }

}