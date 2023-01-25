<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Info extends CI_Controller{
    
    function __construct() {
        parent::__construct();

        //$this->load->model('Flete_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }

    /**
     * Listado de distribuidores MDA disponibles por ciudad y departamento
     * 2022-09-23
     */
    function distribuidores_minutos_de_amor()
    {
        $data['list'] = file_get_contents(PATH_CONTENT . "especial/distribuidores_mda.json");

        $data['head_title'] = 'Distribuidores Minutos de Amor';
        $data['view_a'] = 'info/general/distribuidores_mda_v';

        $this->load->view(TPL_FRONT, $data);
    }

    /**
     * Listado de distribuidores MDA disponibles por ciudad y sector durante
     * la cuarentena 2020
     * 2020-04-01
     */
    function distribuidores_disponibles()
    {
        $data['list'] = file_get_contents(PATH_CONTENT . "especial/distribuidores_cuarentena.json");

        $data['head_title'] = 'Distribuidores Disponibles';
        $data['view_a'] = 'info/general/distribuidores_disponibles_v';

        $this->load->view(TPL_FRONT, $data);
    }
    

}