<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Eventos extends CI_Controller{
    
    function __construct() 
    {
        parent::__construct();

        $this->load->model('Evento_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
//---------------------------------------------------------------------------------------------------
//GESTIÓN DE USUARIOS
    
    /**
     * Exploración y búsqueda de productos, administración
     */
    function explorar()
    {
        //Datos básicos de la exploración
            $data = $this->Evento_model->data_explorar();
        
        //Opciones de filtros de búsqueda
            $data['arr_filtros'] = array('tipo');
            $data['opciones_tipo'] = $this->Item_model->opciones('categoria_id = 13', 'Todos');
            
        //Arrays con valores para contenido en lista
            $data['arr_tipos'] = $this->Item_model->arr_interno('categoria_id = 13');
        
        //Cargar vista
            $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * Exporta el resultado de la búsqueda a un archivo de Excel
     */
    function exportar()
    {
        
        //Cargando
            $this->load->model('Busqueda_model');
            $this->load->model('Pcrn_excel');
        
        //Datos de consulta, construyendo array de búsqueda
            $busqueda = $this->Busqueda_model->busqueda_array();
            $resultados_total = $this->Evento_model->buscar($busqueda); //Para calcular el total de resultados
        
        //Preparar datos
            $datos['nombre_hoja'] = 'Eventos';
            $datos['query'] = $resultados_total;
            
        //Preparar archivo
            $objeto_archivo = $this->Pcrn_excel->archivo_query($datos);
        
        $data['objeto_archivo'] = $objeto_archivo;
        $data['nombre_archivo'] = date('Ymd_His'). '_eventos.xls'; //save our workbook as this file name
        
        $this->load->view('comunes/descargar_phpexcel_v', $data);
            
    }
    
    /**
     * Eliminar un grupo de eventos seleccionados
     */
    function eliminar_seleccionados()
    {
        $str_seleccionados = $this->input->post('seleccionados');
        
        $seleccionados = explode('-', $str_seleccionados);
        
        foreach ( $seleccionados as $elemento_id ) {
            $this->Evento_model->eliminar($elemento_id);
        }
        
        echo count($seleccionados);
    }
    
    function info($evento_id)
    {
        //Datos básicos
        if ( $this->session->userdata('role') > 2 ) { $evento_id = $this->session->userdata('evento_id'); }
        $data = $this->Evento_model->basico($evento_id);
        
        //Array data espefícicas
        $data['vista_b'] = 'eventos/info_v';
        $data['vista_menu'] = 'eventos/menu_admin_v';
        
        $this->load->view(PTL_ADMIN, $data);
    }
}