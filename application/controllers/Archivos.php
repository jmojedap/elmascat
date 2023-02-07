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
    
    function edit($archivo_id)
    {
        $data = $this->Archivo_model->basico($archivo_id);
        
        //Variables
            $data['destino_form'] = "archivos/editar_e/{$archivo_id}";
            $data['att_img'] = $this->Archivo_model->att_img($archivo_id, '500px_');
        
        //Variables generales
            $data['archivo_id'] = $archivo_id;
            $data['titulo_pagina'] = $data['row']->nombre_archivo;
            $data['vista_a'] = 'archivos/archivo_v';
            $data['vista_b'] = 'archivos/editar_v';
            
        //Variables generales
        
        $this->load->view(PTL_ADMIN, $data);
    }
    
    function update()
    {
        $arr_row = $this->input->post();
        $data = $this->Archivo_model->actualizar($arr_row['id'], $arr_row);
        
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
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
        $resultado = $this->Archivo_model->upload($archivo_id);
        $this->session->set_flashdata('resultado', $resultado);
        
        if ( $resultado['status'] == 1 )
        {
            $row = $this->Db_model->row_id('archivo', $archivo_id);
            
            //Eliminar archivo anterior
                $this->Archivo_model->unlink($row->carpeta, $row->nombre_archivo);
            
            //Actualizar archivo, datos del nuevo archivo
                $this->Archivo_model->cambiar($archivo_id, $resultado['upload_data']);
                $this->Archivo_model->crear_miniaturas($archivo_id);   //Crear miniaturas de la imagen
                $this->Archivo_model->mod_original_id($archivo_id);    //Mofificar imagen original después de crear miniaturas
                
            redirect("archivos/edit/{$archivo_id}");
        } else {
            //No se cargó
            redirect("archivos/cambiar/{$archivo_id}");
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
            $this->Archivo_model->delete($elemento_id);
        }
        
        echo count($seleccionados);
    }

    /**
     * Elimina un registro de la tabla file, y los archivos asociados en el servidor
     * 2020-07-24
     */
    function delete($file_id)
    {
        $data = $this->Archivo_model->delete($file_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    function carpetas($year, $month, $offset = 0)
    {
        //Cargue
            $this->load->model('Esp');
        
        $archivos = $this->Archivo_model->archivos($year, $month);
        $cant_archivos = count($archivos);
        
        //Opciones meses
            $arr_meses = $this->Esp->arr_meses();
        
        //Variables
            $data['archivos'] = $archivos;
            $data['cant_archivos'] = $cant_archivos;
            $data['year'] = $year;
            $data['month'] = $month;
            $data['nombre_mes'] = $arr_meses[$month];
            $data['months'] = $this->Esp->arr_meses();
            $data['years'] = range(2015, 2022);
        
        //Solicitar vista
            $data['head_title'] = "Archivos :: {$year}/{$data['nombre_mes']}";
            $data['nav_2'] = 'archivos/explore/menu_v';
            $data['view_a'] = 'archivos/carpetas_v';
            $this->App_model->view(TPL_ADMIN, $data);
    }
    
    /**
     * Eliminar archivos
     */
    function unlink_no_usados($year, $month)
    {
        $data = array('status' => 0, 'message' => 'No se eliminaron archivos');

        $arr_eliminados = $this->Archivo_model->unlink_no_usados($year, $month);
        if ( count($arr_eliminados) > 0 )
        {
            $data['status'] = 1;
            $data['message'] = "Eliminados: " . count($arr_eliminados);
            $data['eliminados'] = $arr_eliminados;
        }
        
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * Modifica calidad y tamaño de archivo original
     * 2020-04-29
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

        $data['message'] = "Archivos modificados: {$cant_modificados}";
        
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function unlink_thumbnails($year, $month)
    {
        $data = $this->Archivo_model->unlink_thumbnails($year, $month);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
//CARGUE - UPLOAD
//---------------------------------------------------------------------------------------------------
    /**
     * Formulario para hacer el upload de un archivo
     * 
     * @param type $archivo_id
     */
    function add($archivo_id = NULL)
    {
        //Array data espefícicas
            $data['archivo_id'] = $archivo_id;
            $data['head_title'] = 'Cargar archivo';
            $data['nav_2'] = 'archivos/explore/menu_v';
            $data['view_a'] = 'archivos/add_v';
        
        //Cargar vista
            $this->App_model->view(TPL_ADMIN, $data);
    }
    
    /**
     * AJAX JSON
     * Carga un archivo en la ruta "content/uploads/{year}/}{month}/"
     * Crea registro de ese arhivo en la tabla file
     */
    function upload()
    {
        $data = $this->Archivo_model->upload();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// Procesos masivos
//-----------------------------------------------------------------------------

    /**
     * Actualizar tabla archivo, campos url y url_thumbnail
     * 2021-05-19
     */
    function actualizar_url()
    {
        $this->db->select('id, carpeta, nombre_archivo');
        //$this->db->where('url_image = ""');
        $archivos = $this->db->get('archivo');

        $qty_updated = 0;

        foreach ( $archivos->result() as $archivo )
        {
            $arr_row['url'] = URL_UPLOADS . $archivo->carpeta . $archivo->nombre_archivo;
            $arr_row['url_thumbnail'] = URL_UPLOADS . $archivo->carpeta . '500px_' . $archivo->nombre_archivo;

            $this->db->where('id', $archivo->id)->update('archivo', $arr_row);
            
            $qty_updated += $this->db->affected_rows();
        }

        $data['status'] = 1;
        $data['message'] = "Archivos actualizados: {$qty_updated}";
    
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Actualiza los campos archivo: table_id, related_1, para los archivos relacionados con productos
     * 2021-05-28
     */
    function actualizar_campos_related()
    {
        $data = array('status' => 1, 'message' => 'No se actualizaron registros de archivos');
        $qty_updated = 0;
        
        $this->db->where('dato_id', 1); //Imagen asociada
        $this->db->where('tabla_id', 3100); //Tabla producto
        $relacionados = $this->db->get('meta');

        $arr_row['table_id'] = 3100;    //Tabla producto

        foreach( $relacionados->result() as $meta)
        {
            $arr_row['related_1'] = $meta->elemento_id;

            $this->db->where('id', $meta->relacionado_id);
            $this->db->update('archivo', $arr_row);
            
            $qty_updated += $this->db->affected_rows();
        }

        //Verificar resultado
        if ( $qty_updated > 0 ) {
            $data = array('status' => 1, 'message' => "Archivos actualizados: {$qty_updated}");
        }

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
}
