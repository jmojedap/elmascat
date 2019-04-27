<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bootstrap {

    
    /**
     * HTML con elemento progress-bar de BootStrap
     * @param type $pct
     * @param type $valor
     * @param type $clase
     * @return string
     */
    public function progress_bar($pct, $valor, $clase = '')
    {
        //Definir atributos
        $clase_plus = '';
        if ( strlen($clase) > 0 ) { $clase_plus = "progress-bar-{$clase}"; }
        $valor_plus = '';
        if ( ! is_null($valor) ) { $valor_plus = $valor; }

    //Construir elmemento
        $bs_progress_bar = '<div class="progress">';
        $bs_progress_bar .= '<div class="progress-bar ' . $clase_plus . '" role="progressbar" aria-valuenow="' . $pct . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $pct . '%; min-width: 1em;">';
        $bs_progress_bar .= $valor_plus;
        $bs_progress_bar .= '</div>';
        $bs_progress_bar .= '</div>';

        return $bs_progress_bar;
    }
    
    /**
     * Devuelve una texto con la clase bootstrap según el porcentaje (entero de
     * 0 a 100).
     * 
     * @param type $pct
     * @return string
     */
    function clase_pct($pct)
    {
        $bs_clase = 'danger';
        if ( $pct > 5 && $pct <= 20 )
        {
            $bs_clase = 'warning';
        } elseif ( $pct > 20 && $pct <= 50) {
            $bs_clase = 'primary';
        } elseif ( $pct > 50 && $pct <= 90) {
            $bs_clase = 'info';
        } elseif ( $pct > 90) {
            $bs_clase = 'success';
        }
        
        return $bs_clase;
    }
}