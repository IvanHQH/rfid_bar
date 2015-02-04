<?php

class FamilyController extends \BaseController {

    public function getIndex() {
        return View::make('family.index');
    }
    
    /**
     * Gets the objects to display in a Datatable component.
     *
     * @return Response
     */
    public function getDatatable() {
        $es = Family::select('id', 'family_name', 'description', 'created_at', 'updated_at');
        return Datatables::of($es)->make();
    }
    
    public function familyCSV() {
            $columns=array('id', 'family_name','description', 'created_at', 'updated_at');
            $headers=array('id', 'Familia','descripción', 'creado', 'modificado');
            CSVGenerate::sendCSV($columns, $headers, "family");
        }


        public function store($id = 0) {
            $input = Input::All();
            
			if ($id == 0) {
				$family = new Family();
			}
			else {
				$family = Family::find($id);
				if (!$family) {
					return App::abort(403, 'Item not found');
				}
			}
			$family -> family_name = $input["family"]['family_name'];
			$family -> description = $input["family"]['description'];
			$family -> save();

			return Response::json($family);
        }
		
		public function getFamily($id) {
			$p = Family::find($id);
			if ($p !== null) {
				return Response::json($p);
			}
			return App::abort(403, 'Item not found');
		}
		
		public function delete($id) {
			$p = Family::find($id);
			if ($p) {
				$p -> delete();
			}
			return Response::json(array('ok' => 'ok'));
		}

}