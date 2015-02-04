<?php
class CSVGenerate {
    public static function sendCSV ($columns,$headers,$table){
        $input = Input::all();
        $query=DB::table($table)->select($columns);
            $query->where(function($query) use ($input,$columns) {
                    foreach ($columns as $column) {
                        $query->orWhere($column, "LIKE", '%' . $input['sSearch'] . '%');
                    }
                });
        $query->orderBy($columns[$input["iSortCol_0"]], $input["sSortDir_0"]);
        $query->select($columns);

        ini_set("memory_limit", "2048M");
        set_time_limit(900);

        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        header('Content-type: application/csv; encoding: cp1250;');
        header('Content-Disposition:  inline; filename=report.csv');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        flush();

        $elements = $query->get();
        array_unshift($elements, $headers);
        $csvstring = "";
        foreach ($elements as $fields) {
            $retVal = '';
            foreach ($fields as $field) {
                $retVal .= sprintf('"%s",', str_replace('"', '""', utf8_decode($field)));
            }
            $csvstring.=rtrim($retVal, ',') . "\r\n";
        }
        echo $csvstring;
        die();
    }
    public static function sendRawCSV ($grid){

        ini_set("memory_limit", "2048M");
        set_time_limit(900);

        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        header('Content-type: application/csv; encoding: cp1250;');
        header('Content-Disposition:  inline; filename=report.csv');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        flush();
        
        $csvstring = "";
        foreach ($grid as $fields) {
            $retVal = '';
            foreach ($fields as $field) {
                $retVal .= sprintf('"%s",', str_replace('"', '""', utf8_decode($field)));
            }
            $csvstring.=rtrim($retVal, ',') . "\r\n";
        }
        echo $csvstring;
        die();
    }
}
