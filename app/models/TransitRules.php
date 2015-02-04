<?php

    class TransitRules {

        public static function getAction($antenna_in, $antenna_out){
            switch ($antenna_in) {
                case 1:
                    if ($antenna_out == 1) {
                        $io = 'No salió';
                    } else if ($antenna_out == 2 || $antenna_out == 4) {
                        $io = 'Salida';
                    }
                    break;
                case 2:
                    if ($antenna_out == 1) {
                        $io = 'Entrada';
                    } elseif ($antenna_out == 2) {
                        $io = 'No salió :: Acción Indebida';
                    } elseif ($antenna_out == 4) {
                        $io = 'Salida :: Acción Indebida';
                    }
                    break;
                case 4:
                    if ($antenna_out == 1) {
                        $io = 'Entrada';
                    } elseif ($antenna_out == 2) {
                        $io = 'Entrada :: Accion Indebida';
                    } elseif ($antenna_out == 4) {
                        $io = 'No Regresó';
                    }
                    break;
            }

            return $io;
        }
    }
