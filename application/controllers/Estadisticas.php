<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Estadisticas extends CI_Controller{
    
    function __construct() {
        parent::__construct();

        $this->load->model('Pedido_model');
        $this->load->model('Estadistica_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }

    function ventas_categoria()
    {
        $data['ventas_categoria'] = $this->Estadistica_model->ventas_categoria(1);
        $data['inventario_categoria'] = $this->Estadistica_model->inventario_categoria();

        //Solicitar vista
            $data['head_title'] = 'Ventas';
            $data['head_subtitle'] = 'por categoría';
            $data['view_a'] = 'estadisticas/pedidos/ventas_categoria_v';
            $data['nav_2'] = 'estadisticas/pedidos/menu_v';
            $this->load->view(TPL_ADMIN, $data);
    }
}