<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lugares extends CI_Controller{
    
    function __construct() {
        parent::__construct();

        $this->load->model('Lugar_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
//LUGARES - TABLE PLACE
//---------------------------------------------------------------------------------------------------
    
    /**
     * Exploración y búsqueda de lugares
     */
    function explorar($num_pagina = 0)
    {
        //Datos básicos de la exploración
            $data = $this->Lugar_model->data_explorar($num_pagina);
        
        //Opciones de filtros de búsqueda
            $data['arr_filtros'] = array('tp');
            $data['opciones_tipo'] = $this->Item_model->opciones('categoria_id = 70', 'Todos');
            
        //Arrays con valores para contenido en lista
            $data['arr_tipos'] = $this->Item_model->arr_interno('categoria_id = 70');
        
        //Cargar vista
            $this->load->view(PTL_ADMIN, $data);
    }    
    
    /**
     * AJAX
     * 
     * Devuelve JSON, que incluye string HTML de la tabla de exploración para la
     * página $num_pagina, y los filtros enviados por post
     * 
     * @param type $num_pagina
     */
    function tabla_explorar($num_pagina = 0)
    {
        //Datos básicos de la exploración
            $data = $this->Lugar_model->data_tabla_explorar($num_pagina);
        
        //Arrays con valores para contenido en lista
            $data['arr_roles'] = $this->Item_model->arr_interno('categoria_id = 58');
            
        //Arrays con valores para contenido en lista
            $data['arr_tipos'] = $this->Item_model->arr_interno('categoria_id = 70');
        
        //Preparar respuesta
            $respuesta['html'] = $this->load->view('sistema/lugares/explorar/tabla_v', $data, TRUE);
            $respuesta['seleccionados_todos'] = $data['seleccionados_todos'];
            $respuesta['num_pagina'] = $num_pagina;
        
        //Salida
            $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($respuesta));
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
            $resultados_total = $this->Lugar_model->buscar($busqueda); //Para calcular el total de resultados
        
        //Preparar datos
            $datos['nombre_hoja'] = 'Lugares';
            $datos['query'] = $resultados_total;
            
        //Preparar archivo
            $objWriter = $this->Pcrn_excel->archivo_query($datos);
        
        $data['objWriter'] = $objWriter;
        $data['nombre_archivo'] = date('Ymd_His'). '_lugares'; //save our workbook as this file name
        
        $this->load->view('comunes/descargar_phpexcel_v', $data);
    }
    
    function editar()
    {
        $lugar_id = $this->uri->segment(4);
        $data = $this->Lugar_model->basico($lugar_id);
        
        $gc_output = $this->Lugar_model->crud_basico();

        //Array data espefícicas
            $data['subtitulo_pagina'] = 'Editar';
            $data['vista_b'] = 'comunes/gc_v';    
        
        $output = array_merge($data,(array)$gc_output);
        $this->load->view(PTL_ADMIN, $output);
    }
    
    /**
     * Eliminar un grupo de registros seleccionados
     */
    function eliminar_seleccionados()
    {
        $str_seleccionados = $this->input->post('seleccionados');
        
        $seleccionados = explode('-', $str_seleccionados);
        
        foreach ( $seleccionados as $elemento_id ) 
        {
            $this->Lugar_model->eliminar($elemento_id);
        }
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($seleccionados));
    }
    
    function sublugares($lugar_id)
    {
        
        //Cargando
            $this->load->model('Busqueda_model');
            $this->load->helper('text');
            
        //Data básico
            $data = $this->Lugar_model->basico($lugar_id);
            $titulo_sublugares = $this->Lugar_model->titulo_sublugares($data['row']->tipo_id);
        
        //Variables para vista
            $data['sublugares'] = $this->Lugar_model->sublugares($lugar_id);;
            $data['titulo_sublugares'] = $titulo_sublugares;
        
        //Solicitar vista
            $data['subtitulo_pagina'] = $titulo_sublugares;
            $data['vista_b'] = 'sistema/lugares/sublugares_v';
            $this->load->view(PTL_ADMIN, $data);
        
    }
    
    function pedidos($lugar_id)
    {
        $data = $this->Lugar_model->basico($lugar_id);
        
        //Solicitar vista
            $data['subtitulo_pagina'] = 'En construcción';
            $data['vista_b'] = 'app/en_construccion_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    function fletes($lugar_id)
    {
            
        //Data básico
            $data = $this->Lugar_model->basico($lugar_id);
        
        //Variables para vista
            $data['fletes'] = $this->Lugar_model->fletes($lugar_id);
        
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Fletes';
            $data['vista_b'] = 'sistema/lugares/fletes_v';
            $this->load->view(PTL_ADMIN, $data);
        
    }
    
    function guardar($lugar_id)
    {
        $this->Lugar_model->guardar($lugar_id);
        redirect("lugares/sublugares/{$lugar_id}");
    }
    
}