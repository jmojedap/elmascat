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

    function panel() 
    {
        $this->load->model('Estadistica_model');
        
        
        //Variables específicas
            $data['nuevas_compras'] = $this->Pcrn->num_registros('pedido', 'estado_pedido = 3');
            $data['cant_usuarios'] = $this->Pcrn->num_registros('usuario', 'rol_id >= 20');
            
        //Variables generales
            $data['head_title'] = NOMBRE_APP;
            $data['head_subtitle'] = 'Resumen';
            $data['view_a'] = 'pedidos/panel/panel_v';

        $this->load->view(TPL_ADMIN, $data);
    }

    function resumen_mes()
    {
        $this->load->model('Estadistica_model');
        $start_year = date('Y') - 2;
        for ($year=$start_year; $year <= date('Y'); $year++)
        { 
            $resumen_mes[$year] = $this->Estadistica_model->ventas_mes($year);
        }
        
        //Cargando variables
            $data['resumen_mes'] = $resumen_mes;
        
        //Solicitar vista
            $data['head_title'] = 'Resumen por mes';
            $data['view_a'] = 'estadisticas/pedidos/resumen_mes_v';
            $data['nav_2'] = 'estadisticas/pedidos/menu_v';
            $this->load->view(TPL_ADMIN, $data);
    }

    function resumen_dia($qty_days = 7)
    {
        //Cargando variables
            $this->load->model('Estadistica_model');
            $data['resumen_dia'] = $this->Estadistica_model->ventas_dia($qty_days);
            $data['qty_days'] = $qty_days;
        
        //Solicitar vista
            $data['head_title'] = 'Pedidos';
            $data['head_subtitle'] = 'Resumen por día';
            $data['view_a'] = 'estadisticas/pedidos/resumen_dia_v';
            $data['nav_2'] = 'estadisticas/pedidos/menu_v';
            $this->load->view(TPL_ADMIN, $data);
    }

    function ventas_ciudad()
    {
        $this->load->model('Estadistica_model');
        $ventas_ciudad = $this->Estadistica_model->ventas_ciudad(1);
        //$ventas_ciudad = array();
        
        //Cargando variables
            $data['ventas_ciudad'] = $ventas_ciudad;
        
        //Solicitar vista
            $data['head_title'] = 'Pedidos';
            $data['head_subtitle'] = 'Ventas por ciudad';
            $data['view_a'] = 'estadisticas/pedidos/ventas_ciudad_v';
            $data['nav_2'] = 'estadisticas/pedidos/menu_v';
            $this->load->view(TPL_ADMIN, $data);
    }

    function ventas_departamento()
    {
        $this->load->model('Estadistica_model');
        $ventas_departamento = $this->Estadistica_model->ventas_departamento(1);
        //$ventas_ciudad = array();
        
        //Cargando variables
            $data['ventas_departamento'] = $ventas_departamento;
        
        //Solicitar vista
            $data['head_title'] = 'Pedidos';
            $data['head_subtitle'] = 'Ventas por Departamento';
            $data['view_a'] = 'estadisticas/pedidos/ventas_departamento_v';
            $data['nav_2'] = 'estadisticas/pedidos/menu_v';
            $this->load->view(TPL_ADMIN, $data);
    }

    function meta_anual()
    {
        $this->load->model('Estadistica_model');
        $resumen_anio = $this->Estadistica_model->pedidos_resumen_anio(1);
        $this->load->library('pacarina');
        
        //Metas
            $json_metas = $this->App_model->valor_opcion(300001);
        
        //Cargando variables
            $data['resumen_anio'] = $resumen_anio;
            $data['arr_metas'] = json_decode($json_metas, TRUE);
        
        //Solicitar vista
            $data['head_title'] = 'Pedidos';
            $data['head_subtitle'] = 'Meta anual';
            $data['view_a'] = 'estadisticas/pedidos/meta_anual_v';
            $data['nav_2'] = 'estadisticas/pedidos/menu_v';
            $this->load->view(TPL_ADMIN, $data);
    }

    function efectividad()
    {
        $this->load->model('Estadistica_model');
        
        //Cargando variables
            $data['resumen_mes'] = $this->Estadistica_model->pedidos_resumen_mes(1);
            $data['resumen_mes_total'] = $this->Estadistica_model->pedidos_resumen_mes();
        
        //Solicitar vista
            $data['head_title'] = 'Pedidos';
            $data['head_subtitle'] = 'Efectividad';
            $data['view_a'] = 'estadisticas/pedidos/efectividad_v';
            $data['nav_2'] = 'estadisticas/pedidos/menu_v';
            $this->load->view(TPL_ADMIN, $data);
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

// Productos
//-----------------------------------------------------------------------------

    function productos_top()
    {
        //Cargue
            $this->load->model('Estadistica_model');
        
        //Cargando variables
            $data['productos'] = $this->Estadistica_model->productos_top();
        
        //Solicitar vista
            $data['head_title'] = 'Pedidos';
            $data['head_subtitle'] = 'Productos más vendidos';
            $data['view_a'] = 'estadisticas/productos/productos_top_v';
            $data['nav_2'] = 'estadisticas/productos/menu_v';
            $this->load->view(TPL_ADMIN, $data);
    }

    /**
     * Cantidad de suscriptores por edición en línea de la revista Minutos
     * de Amor
     * 2023-01-25
     */
    function minutos_en_linea($minYear = 2021)
    {
        $this->load->model('Estadistica_model');

        $ediciones_usuarios = $this->Estadistica_model->ediciones_usuarios($minYear);

        //Cargando variables
        $data['ediciones_usuarios'] = $ediciones_usuarios;

        //Solicitar vista
        $data['head_title'] = 'Suscriptores en Línea';
        $data['head_subtitle'] = 'por edición';
        $data['view_a'] = 'estadisticas/productos/minutos_en_linea_v';
        $data['nav_2'] = 'estadisticas/productos/menu_v';
        $this->load->view(TPL_ADMIN, $data);

        //Salida JSON
        //$this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// Procesos masivos
//-----------------------------------------------------------------------------

    function ventas_lugar_poblacion()
    {
        $data['lugares'] = $this->Estadistica_model->ventas_lugar_poblacion();
        $data['head_title'] = 'Ventas por población';
        $data['view_a'] = 'estadisticas/pedidos/ventas_lugar_poblacion_v';
        $data['nav_2'] = 'estadisticas/pedidos/menu_v';

        $this->load->view(TPL_ADMIN, $data);
    }

    /**
     * Actualiza el campo lugar.score_1, ventas en el último año por cada 100
     * mil habitantes
     * 2021-11-24
     */
    function update_lugar_score_1($tipo_id = 4)
    {
        if ( $tipo_id == 3 ) {
            $data = $this->Estadistica_model->update_region_score_1();
        } else if ( $tipo_id == 4 ) {
            $data = $this->Estadistica_model->update_ciudad_score_1();
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}