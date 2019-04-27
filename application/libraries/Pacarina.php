<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pacarina {
    
    /**
     * Número en un formato determinado
     * 
     * @param type $numero
     * @param type $formato
     * @return string
     */
    function moneda($numero, $formato = NULL)
    {
        $moneda = "$" .  number_format($numero, 0, ',', '.');
        
        if ( $formato == 'M' ) //Millones
        {
            $moneda = "$" .  number_format($numero/1000000, 1, ',', '.') . ' M';
        }
        
        return $moneda;
    }
}