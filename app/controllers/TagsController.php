<?php

class TagsController extends BaseController {

    public function index() {
        return View::make('tags.index');
    }

    public function tagsDatatables() {
        $tags = DB::table('tags_mappings')
            -> join('products', 'tags_mappings.upc', '=', 'products.upc')
            -> select(array('tags_mappings.tag', 'products.product_name', 'tags_mappings.upc', 'tags_mappings.created_at'));
        return  Datatables::of($tags) -> make();
    }
}
