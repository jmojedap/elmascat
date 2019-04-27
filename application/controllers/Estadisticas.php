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
            $data['titulo_pagina'] = 'Ventas';
            $data['subtitulo_pagina'] = 'por categoría';
            $data['vista_a'] = 'estadisticas/pedidos/ventas_categoria_v';
            $data['vista_menu'] = 'estadisticas/pedidos/menu_v';
            $this->load->view(PTL_ADMIN, $data);
    }
}