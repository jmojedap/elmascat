<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Datos extends CI_Controller{
    
    function __construct() {
        parent::__construct();

        $this->load->model('Datos_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
//METADATOS
//---------------------------------------------------------------------------------------------------

    function metadatos()
    {
        
        $gc_output = $this->Datos_model->crud_item_meta(); //ver categoria.id
        
        //Array data espefícicas
        $data['titulo_pagina'] = 'Parámetros';
        $data['subtitulo_pagina'] = 'Metadatos del sistema';
        $data['vista_menu'] = 'sistema/admin/parametros_menu_v';
        $data['vista_a'] = 'comunes/gc_v';
        
        $output = array_merge($data,(array)$gc_output);
        $this->load->view(PTL_ADMIN, $output);
    }
    
//ETIQUETAS
//---------------------------------------------------------------------------------------------------
    
    function tags()
    {
        //Array data vista
        $data['titulo_pagina'] = 'Parámetros';
        $data['subtitulo_pagina'] = 'Etiquetas de productos';
        $data['vista_a'] = 'datos/tags/tags_v';
        
    //Cargar vista
        $this->load->view(PTL_ADMIN, $data);
    }

    function get_tags()
    {
        $etiquetas =  $this->Datos_model->tags();
        $data['list'] = $etiquetas->result();

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }
    
    function save_tag($tag_id = 0)
    {
        $data = array('status' => 0, 'message' => 'No se guardó la etiqueta');

        $tag_id = $this->Datos_model->save_tag($tag_id);

        if ( $tag_id > 0 )
        {
            $data = array('status' => 1, 'message' => 'Etiqueta guardada ID: ' . $tag_id, 'tag_id' => $tag_id);
        }

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }

    /**
     * JSON AJAX
     * Elimina etiqueta
     */
    function delete_tag($tag_id)
    {
        $data = array('status' => 0, 'message' => 'Etiqueta no eliminada');

        $quan_deleted = $this->Datos_model->delete_tag($tag_id);

        if ( $quan_deleted > 0 )
        {
            $data = array('status' => 1, 'message' => 'Etiqueta eliminada');
        }

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }
    
    /**
     * Exporta el resultado de la búsqueda a un archivo de Excel
     */
    function export_tags()
    {
        //Cargando
            $this->load->model('Pcrn_excel');
        
        //Preparar datos
            $datos['nombre_hoja'] = 'Etiquetas';
            $datos['query'] = $this->Datos_model->tags();
            
        //Preparar archivo
            $objWriter = $this->Pcrn_excel->archivo_query($datos);
        
        $data['objWriter'] = $objWriter;
        $data['nombre_archivo'] = date('Ymd_His'). '_etiquetas'; //save our workbook as this file name
        
        $this->load->view('app/descargar_phpexcel_v', $data);
    }
    
//OTRAS
//---------------------------------------------------------------------------------------------------
    
    function fabricantes()
    {
        $gc_output = $this->Datos_model->crud_fabricantes(); //ver categoria.id
        
        //Array data espefícicas
        $data['titulo_pagina'] = 'Parámetros';
        $data['subtitulo_pagina'] = 'Fabricantes';
        $data['vista_menu'] = 'sistema/admin/parametros_menu_v';
        $data['vista_a'] = 'comunes/gc_v';
        
        
        $output = array_merge($data,(array)$gc_output);
        $this->load->view(PTL_ADMIN, $output);
    }
    
    function listas()
    {
        
        $gc_output = $this->Datos_model->crud_listas(); //ver categoria.id
        
        //Array data espefícicas
        $data['titulo_pagina'] = 'Parámetros';
        $data['subtitulo_pagina'] = 'Listas';
        $data['vista_menu'] = 'datos/parametros_menu_v';
        $data['vista_a'] = 'comunes/gc_v';
        
        $output = array_merge($data,(array)$gc_output);
        $this->load->view(PTL_ADMIN, $output);
    }
    
    function extras()
    {
        
        $gc_output = $this->Datos_model->crud_extras(); //ver categoria.id
        
        //Array data espefícicas
        $data['titulo_pagina'] = 'Parámetros';
        $data['subtitulo_pagina'] = 'Extras pedidos';
        $data['vista_menu'] = 'sistema/admin/parametros_menu_v';
        $data['vista_a'] = 'comunes/gc_v';
        
        $output = array_merge($data,(array)$gc_output);
        $this->load->view(PTL_ADMIN, $output);
    }
    
    function estado_pedido()
    {
        
        $gc_output = $this->Datos_model->crud_estado_pedido(); //ver categoria.id
        
        //Head includes específicos para la página
        
        //Array data espefícicas
        $data['titulo_pagina'] = 'Parámetros';
        $data['subtitulo_pagina'] = 'Estados pedido';
        $data['vista_menu'] = 'sistema/admin/parametros_menu_v';
        $data['vista_a'] = 'comunes/gc_v';
        
        
        $output = array_merge($data,(array)$gc_output);
        $this->load->view(PTL_ADMIN, $output);
    }

    
}