<?php

    class BarsController extends \BaseController {

        public function barsList(){
            return View::make('bars.index');
        }

        public function barsDatatables() {
            $bars = Bar::select(array('id', 'name', 'created_at', 'updated_at'));
            return  Datatables::of($bars) -> make();
        }
		
        /**
         * Return specified item.
         *
         * @return Response
         */
        public function getBar($id) {
                $p = Bar::find($id);
                if ($p !== null) {
                        return Response::json($p);
                }
                return App::abort(403, 'Item not found');
        }

        /**
         * Store a resource in storage.
         *
         * @return Response
         */
        public function postIndex($id = 0) {
                if (Request::has('bar')) {
                        $bd = Request::get('bar');
                        if ($id == 0) {
                                $bar = new Bar($bd);
                        }
                        else {
                                $bar = Bar::find($id);
                                if (null === $bar) {
                                        return App::abort(403, 'Item not found');
                                }
                                $bar -> fill($bd);
                        }
                        $bar -> save();
                        return Response::json($bar);
                }
                return App::abort(403, 'Invalid request');
        }

        /**
         * Perform a logical delete on an object.
         *
         * @return Response
         */
        public function postDelete($id) {
                $p = Bar::find($id);
                if ($p) {
                        $p -> delete();
                }
                return Response::json(array('ok' => 'ok'));
        }
		
                
        public function barsCSV() {
            $columns=array('id', 'name', 'created_at', 'updated_at');
            $headers=array('id', 'Barra', 'creado', 'modificado');
            CSVGenerate::sendCSV($columns, $headers, "bars");
        }
    }