<?php

    class ProductsTableSeeder extends Seeder {

        public function run()
        {
            DB::table('products') -> delete();

            Product::create(array('upc' => '660010106019', 'product_name' => 'JAGERMEISTER', 'sort_by' => 64));
            Product::create(array('upc' => '660010106026', 'product_name' => 'HIPNOTIQ', 'sort_by' => 63));
            Product::create(array('upc' => '660010106033', 'product_name' => 'BAILEYS', 'sort_by' => 58));
            Product::create(array('upc' => '660010106040', 'product_name' => 'ABSTHIN VERDE', 'sort_by' => 57));
            Product::create(array('upc' => '660010106057', 'product_name' => 'ABSTHIN ROJO', 'sort_by' => 56));
            Product::create(array('upc' => '660010106095', 'product_name' => 'LICOR 43', 'sort_by' => 66));
            Product::create(array('upc' => '660010106156', 'product_name' => 'CONTROY', 'sort_by' => 60));
            Product::create(array('upc' => '660010106163', 'product_name' => 'LICOR DE ALMENDRA', 'sort_by' => 67));
            Product::create(array('upc' => '660010106170', 'product_name' => 'LICOR DE CACAO OBSCURA', 'sort_by' => 68));
            Product::create(array('upc' => '660010106187', 'product_name' => 'LICOR DE COCO', 'sort_by' => 70));
            Product::create(array('upc' => '660010106200', 'product_name' => 'LICOR DE CAFÉ', 'sort_by' => 69));
            Product::create(array('upc' => '660010106217', 'product_name' => 'LICOR DE DURAZNO', 'sort_by' => 72));
            Product::create(array('upc' => '660010106224', 'product_name' => 'LICOR DE CURAZAO', 'sort_by' => 71));
            Product::create(array('upc' => '660010106231', 'product_name' => 'LICOR DE MELON', 'sort_by' => 75));
            Product::create(array('upc' => '660010106248', 'product_name' => 'LICOR DE MENTA VERDE', 'sort_by' => 76));
            Product::create(array('upc' => '660010106255', 'product_name' => 'LICOR DE NARANJA', 'sort_by' => 77));
            Product::create(array('upc' => '660010106262', 'product_name' => 'LICOR DE PLATANO', 'sort_by' => 78));
            Product::create(array('upc' => '660010106279', 'product_name' => 'LICOR DE FRESA', 'sort_by' => 73));
            Product::create(array('upc' => '660010106286', 'product_name' => 'ROMPOPE', 'sort_by' => 80));
            Product::create(array('upc' => '660010107016', 'product_name' => 'AZTECA DE ORO', 'sort_by' => 1));
            Product::create(array('upc' => '660010107023', 'product_name' => 'TORRES X', 'sort_by' => 4));
            Product::create(array('upc' => '660010107030', 'product_name' => 'TORRES 20', 'sort_by' => 2));
            Product::create(array('upc' => '660010107047', 'product_name' => 'TORRES JAIME', 'sort_by' => 3));
            Product::create(array('upc' => '660010108013', 'product_name' => 'MARTELL VS', 'sort_by' => 11));
            Product::create(array('upc' => '660010108020', 'product_name' => 'HENNESY V.S.O.P.', 'sort_by' => 61));
            Product::create(array('upc' => '660010108037', 'product_name' => 'HENNESY X.O.', 'sort_by' => 62));
            Product::create(array('upc' => '660010108044', 'product_name' => 'BABY MOET', 'sort_by' => 5));
            Product::create(array('upc' => '660010108051', 'product_name' => 'JACK DANIELS SINATRA', 'sort_by' => 51));
            Product::create(array('upc' => '660010109010', 'product_name' => 'FINLANDIA CRANBERRY', 'sort_by' => 38));
            Product::create(array('upc' => '660010109027', 'product_name' => 'FINLANDIA LIME', 'sort_by' => 39));
            Product::create(array('upc' => '660010109034', 'product_name' => 'FINLANDIA MANGO', 'sort_by' => 40));
            Product::create(array('upc' => '660010109041', 'product_name' => 'SMIRNOFF', 'sort_by' => 41));
            Product::create(array('upc' => '660010109058', 'product_name' => 'STOLICHNAYA', 'sort_by' => 42));
            Product::create(array('upc' => '660010109065', 'product_name' => 'STOLICHNAYA ELITE', 'sort_by' => 43));
            Product::create(array('upc' => '660010109072', 'product_name' => 'STOLICHNAYA GOLD', 'sort_by' => 44));
            Product::create(array('upc' => '660010109119', 'product_name' => 'FINLANDIA', 'sort_by' => 37));
            Product::create(array('upc' => '660010110016', 'product_name' => 'APLETON SPECIAL', 'sort_by' => 13));
            Product::create(array('upc' => '660010110023', 'product_name' => 'APLETON STATE', 'sort_by' => 14));
            Product::create(array('upc' => '660010110030', 'product_name' => 'APLETON WHITE', 'sort_by' => 15));
            Product::create(array('upc' => '660010110047', 'product_name' => 'APPLETON 21', 'sort_by' => 16));
            Product::create(array('upc' => '660010110054', 'product_name' => 'APLETON 12', 'sort_by' => 12));
            Product::create(array('upc' => '660010110061', 'product_name' => 'MATUSALEN PLATINO', 'sort_by' => 21));
            Product::create(array('upc' => '660010110078', 'product_name' => 'MATUSALEN CLASICO', 'sort_by' => 19));
            Product::create(array('upc' => '660010110085', 'product_name' => 'MATUSALEN GRAN RESERVA', 'sort_by' => 20));
            Product::create(array('upc' => '660010110092', 'product_name' => 'BACARDI', 'sort_by' => 17));
            Product::create(array('upc' => '660010110108', 'product_name' => 'MALIBU', 'sort_by' => 79));
            Product::create(array('upc' => '660010110221', 'product_name' => 'FLOR DE CANA', 'sort_by' => 18));
            Product::create(array('upc' => '660010111013', 'product_name' => 'MOET CHANDON', 'sort_by' => 7));
            Product::create(array('upc' => '660010111020', 'product_name' => 'CRISTAL', 'sort_by' => 6));
            Product::create(array('upc' => '660010111037', 'product_name' => 'MOET CHANDON IMPERIAL 3000', 'sort_by' => 8));
            Product::create(array('upc' => '660010111044', 'product_name' => 'MOET ICE', 'sort_by' => 9));
            Product::create(array('upc' => '660010111051', 'product_name' => 'LICOR DE MANZANA', 'sort_by' => 74));
            Product::create(array('upc' => '660010111150', 'product_name' => 'MOET ROSE', 'sort_by' => 10));
            Product::create(array('upc' => '660010112027', 'product_name' => 'JACK DANIEL´S', 'sort_by' => 47));
            Product::create(array('upc' => '660010112034', 'product_name' => 'JACK DANIEL´S GENTLEMAN', 'sort_by' => 48));
            Product::create(array('upc' => '660010112041', 'product_name' => 'JACK DANIEL´S SINGLER BARREL', 'sort_by' => 52));
            Product::create(array('upc' => '660010112058', 'product_name' => 'JACK DANIEL´S 3000', 'sort_by' => 46));
            Product::create(array('upc' => '660010112065', 'product_name' => 'REB LABEL', 'sort_by' => 55));
            Product::create(array('upc' => '660010112072', 'product_name' => 'BLACK LABEL', 'sort_by' => 53));
            Product::create(array('upc' => '660010112096', 'product_name' => 'BLUE LABEL', 'sort_by' => 54));
            Product::create(array('upc' => '660010112102', 'product_name' => 'BUCHANNAS', 'sort_by' => 45));
            Product::create(array('upc' => '660010112126', 'product_name' => 'JACK DANIELS MASTER', 'sort_by' => 50));
            Product::create(array('upc' => '660010112171', 'product_name' => 'JACK DANIEL´S HONEY', 'sort_by' => 49));
            Product::create(array('upc' => '660010113017', 'product_name' => 'HERRADURA SELECCIÓN SUPREMA', 'sort_by' => 30));
            Product::create(array('upc' => '660010113024', 'product_name' => 'HERRADURA GRAN IMPERIO', 'sort_by' => 27));
            Product::create(array('upc' => '660010113031', 'product_name' => 'HERRADURA ANEJO', 'sort_by' => 23));
            Product::create(array('upc' => '660010113048', 'product_name' => 'HERRADURA REPOSADO', 'sort_by' => 29));
            Product::create(array('upc' => '660010113055', 'product_name' => 'HERRADURA PLATA', 'sort_by' => 28));
            Product::create(array('upc' => '660010113062', 'product_name' => 'HERRADURA ANTIGUO ANEJO', 'sort_by' => 24));
            Product::create(array('upc' => '660010113079', 'product_name' => 'HERRADURA ANTIGUO BLANCO', 'sort_by' => 25));
            Product::create(array('upc' => '660010113086', 'product_name' => 'HERRADURA ANTIGUO REPOSADO', 'sort_by' => 26));
            Product::create(array('upc' => '660010113093', 'product_name' => 'JIMADOR REPOSADO', 'sort_by' => 34));
            Product::create(array('upc' => '660010113109', 'product_name' => 'JIMADOR BLANCO', 'sort_by' => 32));
            Product::create(array('upc' => '660010113116', 'product_name' => 'JIMADOR ANEJO', 'sort_by' => 31));
            Product::create(array('upc' => '660010113123', 'product_name' => 'AZUL CENTENARIO', 'sort_by' => 22));
            Product::create(array('upc' => '660010113130', 'product_name' => 'TRADICIONAL REPOSADO', 'sort_by' => 36));
            Product::create(array('upc' => '660010113147', 'product_name' => 'TRADICIONAL PLATA', 'sort_by' => 35));
            Product::create(array('upc' => '660010113154', 'product_name' => 'JIMADOR BLANCO 700ML', 'sort_by' => 33));
            Product::create(array('upc' => '660010115011', 'product_name' => 'CONTI', 'sort_by' => 59));
            Product::create(array('upc' => '660010115028', 'product_name' => 'KALHUA', 'sort_by' => 65));
        }

    }
