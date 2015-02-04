<?php

    class ProductsController extends BaseController {

        public function productsList(){
            return View::make('products.index',array(
                "families"=>Family::get(),
            ));
        }

        public function productsDatatables() {
            
            $products = Product::join(DB::raw("(select id family_id,family_name from family) family"),"products.family_id","=","family.family_id")->select(array('id', 'upc', 'product_name','family_name','public_price','real_price', 'created_at', 'updated_at'));
            return  Datatables::of($products) -> make();
        }
        
        public function productsCSV() {
            $columns=array('id', 'upc', 'product_name', 'created_at', 'updated_at');
            $headers=array('id', 'upc', 'producto', 'creado', 'modificado');
            CSVGenerate::sendCSV($columns, $headers, "products");
        }

        public function inventory(){
            return View::make('products.inventory');
        }

        public function inventoryDatatables(){
            $products = DB::table(DB::raw('(SELECT COUNT(tags_mappings.upc) AS cnt, tags_mappings.upc, products.product_name FROM tags_mappings INNER JOIN products ON tags_mappings.upc = products.upc WHERE tags_mappings.deleted_at IS NULL GROUP BY upc ORDER BY products.product_name) inventory'))
                -> select(array('product_name', 'upc', 'cnt'));
            return  Datatables::of($products) -> make();
        }

        public function index(){
            return $products = Product::all() -> toJson();
        }

        public function store($id = 0) {
            $input = Input::All();
			if ($id == 0) {
				$product = new Product();
			}
			else {
				$product = Product::find($id);
				if (!$product) {
					return App::abort(403, 'Item not found');
				}
			}
			$product -> product_name = $input['product_name'];
			$product -> upc = $input['product_upc'];
			$product -> description = $input['product_description'];
                        $product -> family_id = $input['product_family'];
                        $product -> public_price = str_replace(",","",$input['product_public_price']);
                        $product -> real_price = str_replace(",","",$input['product_real_price']);
			$product -> color = $input['product_color'];
			$product -> type = $input['product_type'];
			$product -> save();

			return Response::json($product);
        }
		
		public function getProduct($id) {
			$p = Product::find($id);
			if ($p !== null) {
				return Response::json($p);
			}
			return App::abort(403, 'Item not found');
		}
		
		public function delete($id) {
			$p = Product::find($id);
			if ($p) {
				$p -> delete();
			}
			return Response::json(array('ok' => 'ok'));
		}
    }
