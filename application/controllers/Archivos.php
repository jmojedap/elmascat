<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Archivos extends CI_Controller{
    
    function __construct() {
        parent::__construct();
        
        $this->load->model('Archivo_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }

//CRUD
//---------------------------------------------------------------------------------------------------
    
    function explorar()
    {
        //Cargando
            $this->load->model('Busqueda_model');
            $this->load->helper('text');
        
        //Datos de consulta, construyendo array de búsqueda
            $busqueda = $this->Busqueda_model->busqueda_array();
            $busqueda_str = $this->Busqueda_model->busqueda_str();
            $resultados_total = $this->Busqueda_model->archivos($busqueda); //Para calcular el total de resultados
        
        //Paginación
            $this->load->library('pagination');
            $config = $this->App_model->config_paginacion(2);
            $config['base_url'] = base_url("archivos/explorar/?{$busqueda_str}");
            $config['total_rows'] = $resultados_total->num_rows();
            $this->pagination->initialize($config);
            
        //Generar resultados para mostrar
            $offset = $this->input->get('per_page');
            $resultados = $this->Busqueda_model->archivos($busqueda, $config['per_page'], $offset);
        
        //Variables para vista
            $data['cant_resultados'] = $config['total_rows'];
            $data['busqueda'] = $busqueda;
            $data['busqueda_str'] = $busqueda_str;
            $data['resultados'] = $resultados;
        
        //Solicitar vista
            $data['titulo_pagina'] = 'Archivos';
            $data['subtitulo_pagina'] = 'Explorar';
            $data['vista_a'] = 'archivos/explorar_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
// EDITAR
//-----------------------------------------------------------------------------
    
    function editar($archivo_id)
    {
        $data = $this->Archivo_model->basico($archivo_id);
        
        //Variables
            $data['destino_form'] = "archivos/editar_e/{$archivo_id}";
            $data['att_img'] = $this->Archivo_model->att_img($archivo_id, '500px_');
        
        //Variables generales
            $data['archivo_id'] = $archivo_id;
            $data['subtitulo_pagina'] = $data['row']->nombre_archivo;
            $data['vista_a'] = 'archivos/archivo_v';
            $data['vista_b'] = 'archivos/editar_v';
            
        //Variables generales
        
        $this->load->view(PTL_ADMIN, $data);
    }
    
    function editar_e($archivo_id)
    {
        $registro = $this->input->post();
        $resultado = $this->Archivo_model->actualizar($archivo_id, $registro);
        $this->session->set_flashdata('resultado', $resultado);
        
        redirect("archivos/editar/{$archivo_id}");
    }
    
// CAMBIAR ARCHIVO
//-----------------------------------------------------------------------------
    
    function cambiar($archivo_id)
    {
        $data = $this->Archivo_model->basico($archivo_id);
        
        //Variables
            $data['destino_form'] = "archivos/cambiar_e/{$archivo_id}";
            $data['att_img'] = $this->Archivo_model->att_img($archivo_id, '500px_');
        
        //Variables generales
            $data['archivo_id'] = $archivo_id;
            $data['subtitulo_pagina'] = 'Cambiar archivo';
            $data['vista_a'] = 'archivos/archivo_v';
            $data['vista_b'] = 'archivos/cambiar_v';       
            
        //Variables generales
        
        $this->load->view(PTL_ADMIN, $data);
    }
    
    function cambiar_e($archivo_id)
    {
        $resultado = $this->Archivo_model->cargar($archivo_id);
        $this->session->set_flashdata('resultado', $resultado);
        
        if ( $resultado['ejecutado'] )
        {
            $row = $this->Pcrn->registro_id('archivo', $archivo_id);
            
            //Eliminar archivo anterior
                $this->Archivo_model->unlink($row->carpeta, $row->nombre_archivo);
            
            //Actualizar archivo, datos del nuevo archivo
                $this->Archivo_model->cambiar($archivo_id, $resultado['upload_data']);
                $this->Archivo_model->crear_miniaturas($archivo_id);   //Crear miniaturas de la imagen
                $this->Archivo_model->mod_original_id($archivo_id);    //Mofificar imagen original después de crear miniaturas
                
            redirect("archivos/editar/{$archivo_id}");
        } else {
            //No se cargó
            redirect("archivos/cambiar_e/{$archivo_id}");
        }
    }
    
    /**
     * Explorar imágenes
     */
    function imagenes()
    {
        //Cargando
            $this->load->model('Busqueda_model');
            $this->load->helper('text');
        
        //Datos de consulta, construyendo array de búsqueda
            $busqueda = $this->Busqueda_model->busqueda_array();
            $busqueda_str = $this->Busqueda_model->busqueda_str();
            $resultados_total = $this->Archivo_model->buscar($busqueda); //Para calcular el total de resultados
        
        //Paginación
            $this->load->library('pagination');
            $config = $this->App_model->config_paginacion(2);
            $config['base_url'] = base_url() . "archivos/imagenes/?{$busqueda_str}";
            $config['total_rows'] = $resultados_total->num_rows();
            $this->pagination->initialize($config);
            
        //Generar resultados para mostrar
            $offset = $this->input->get('per_page');
            $resultados = $this->Archivo_model->buscar($busqueda, $config['per_page'], $offset);
        
        //Variables para vista
            $data['cant_resultados'] = $config['total_rows'];
            $data['busqueda'] = $busqueda;
            $data['busqueda_str'] = $busqueda_str;
            $data['resultados'] = $resultados;
            $data['destino_busquedas'] = 'archivos/imagenes';
        
        //Solicitar vista
            $data['titulo_pagina'] = 'Archivos';
            $data['subtitulo_pagina'] = $data['cant_resultados'];
            $data['vista_menu'] = 'archivos/explorar_menu_v';
            $data['vista_a'] = 'archivos/imagenes_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * AJAX Eliminar un grupo de archivos seleccionados
     */
    function eliminar_seleccionados()
    {
        $str_seleccionados = $this->input->post('seleccionados');
        
        $seleccionados = explode('-', $str_seleccionados);
        
        foreach ( $seleccionados as $elemento_id )
        {
            $this->Archivo_model->eliminar($elemento_id);
        }
        
        echo count($seleccionados);
    }
    
    function carpetas($anio, $mes, $offset = 0)
    {
        //Cargue
            $this->load->model('Esp');
        
        $archivos = $this->Archivo_model->archivos($anio, $mes);
        $cant_archivos = count($archivos);
        
        //Opciones meses
            $arr_meses = $this->Esp->arr_meses();
        
        //Variables
            $data['archivos'] = $archivos;
            $data['cant_archivos'] = $cant_archivos;
            $data['anio'] = $anio;
            $data['mes'] = $mes;
            $data['nombre_mes'] = $arr_meses[$mes];
            $data['meses'] = $this->Esp->arr_meses();
            $data['anios'] = range(2015, 2020);
        
        //Solicitar vista
            $data['titulo_pagina'] = "Archivos :: {$anio}/{$data['nombre_mes']}";
            $data['subtitulo_pagina'] = $cant_archivos;
            $data['vista_menu'] = 'archivos/explorar_menu_v';
            $data['vista_a'] = 'archivos/carpetas_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * Eliminar archivos
     * 
     * @param type $anio
     * @param type $mes
     */
    function unlink_no_usados($anio, $mes)
    {
        $cant_eliminados = $this->Archivo_model->unlink_no_usados($anio, $mes);
        $resultado['mensaje'] = "Se eliminaron {$cant_eliminados} sin utilizar";
        $this->session->set_flashdata('resultado', $resultado);
        redirect("archivos/carpetas/{$anio}/{$mes}");
    }
    
    /**
     * Eliminar archivos
     * 
     * @param type $anio
     * @param type $mes
     */
    function mod_original($anio, $mes)
    {
        set_time_limit(180);    //Tres minutos
        
        $cant_modificados = 0;
        $carpeta = "{$anio}/{$mes}/";
        
        $this->db->where('carpeta', $carpeta);
        $archivos = $this->db->get('archivo');
        
        foreach( $archivos->result() as $row_archivo ){
            $cant_modificados += $this->Archivo_model->mod_original($row_archivo->carpeta, $row_archivo->nombre_archivo);
        }
        
        //$cant_modificados = $archivos->num_rows();
        $mensaje = "Se modificaron {$cant_modificados} archivos";
        $this->session->set_flashdata('mensaje', $mensaje);
        redirect("archivos/carpetas/{$anio}/{$mes}");
    }
    
//CARGUE - UPLOAD
//---------------------------------------------------------------------------------------------------
    /**
     * Formulario para hacer el upload de un archivo
     * 
     * @param type $archivo_id
     */
    function cargar($archivo_id = NULL)
    {
        //Array data espefícicas
            $data['archivo_id'] = $archivo_id;
            $data['titulo_pagina'] = 'Archivos';
            $data['subtitulo_pagina'] = 'Cargar archivo';
            $data['vista_menu'] = 'archivos/explorar_menu_v';
            $data['vista_a'] = 'archivos/cargar_v';
        
        //Cargar vista
            $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * Ejecutar cargue, realiza el cargue del archivo
     *
     * @param type $archivo_id
     */
    function cargar_e()
    {
        $resultado = $this->Archivo_model->cargar();
        $this->session->set_flashdata('resultado', $resultado);
        
        if ( $resultado['ejecutado'] )
        {
            //Se cargó
            $archivo_id = $resultado['row_archivo']->id;
            //$this->Archivo_model->crear_miniaturas($archivo_id);   //Crear miniaturas de la imagen
            //$this->Archivo_model->mod_original_id($archivo_id);    //Mofificar imagen original después de crear miniaturas
            redirect("archivos/editar/{$archivo_id}");
        } else {
            //No se cargó
            redirect('archivos/cargar');
        }
        
    }
    
}
