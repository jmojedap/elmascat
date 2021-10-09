<?php
class Archivo_model extends CI_Model{
    
    function basico($archivo_id)
    {
        
        $this->db->where('id', $archivo_id);
        $query = $this->db->get('archivo');
        
        $row = $query->row();
        
        $basico['primer_archivo_id'] = $this->archivo_id();
        $basico['row'] = $row;
        $basico['titulo_pagina'] = $row->titulo_archivo;
        $basico['vista_a'] = 'archivos/archivo_v';
        
        return $basico;
    }
    
    function buscar($busqueda, $per_page = NULL, $offset = NULL)
    {

        //Construir búsqueda
        //Crear array con términos de búsqueda
            if ( strlen($busqueda['q']) > 2 ){
                $concat_campos = $this->Busqueda_model->concat_campos(array('nombre_archivo', 'titulo_archivo', 'subtitulo', 'descripcion', 'palabras_clave', 'link', 'meta'));
                $palabras = $this->Busqueda_model->palabras($busqueda['q']);

                foreach ($palabras as $palabra) {
                    $this->db->like("CONCAT({$concat_campos})", $palabra);
                }
            }
        
        //Especificaciones de consulta
            $this->db->select('*, CONCAT((id), " :: ", (titulo_archivo)) AS name');
            $this->db->order_by('id', 'DESC');
            
        //Otros filtros
            if ( $busqueda['rol'] != '' ) { $this->db->where('rol_id', $busqueda['rol']); }    //Rol de archivo
            
        //Obtener resultados
        if ( is_null($per_page) ){
            $query = $this->db->get('archivo'); //Resultados totales
        } else {
            $query = $this->db->get('archivo', $per_page, $offset); //Resultados por página
        }
        
        return $query;
        
    }
    
    /**
     * Consulta para la función ajax de autocompletar, en el SELECT tiene el campo
     * "name", para mostrar el resultado.
     * 
     * @param type $busqueda
     * @param type $limit
     * @return type
     */
    function autocompletar($busqueda, $limit = 20)
    {
        //Variables previas
            //$filtro_rol = $this->filtro_buscar();
            $concat_campos = $this->Busqueda_model->concat_campos(array('id', 'nombre_archivo', 'titulo_archivo'));
        
        //Construcción de la consulta
            $this->db->select('id, CONCAT((id), " | ", (titulo_archivo), " | ",(nombre_archivo)) AS name');
            $this->db->like("CONCAT({$concat_campos})", $busqueda['q']);
            //$this->db->where($filtro_rol); //Filtro según el rol de usuario que se tenga
            $this->db->order_by('titulo_archivo', 'ASC');
            
        
            $query = $this->db->get('archivo', $limit); //Resultados totales
        
        return $query;
        
    }
    
    /**
     * Verifica si el archivo se puede editar o no, permiso.
     * @param type $archivo_id
     * @return int
     */
    function editable($archivo_id)
    {
        $editable = 1;
        return $editable;
    }
    
    /**
     * Devuelve el id del primer archivo registrado
     * @return type
     */
    function archivo_id()
    {
        $this->db->order_by('id', 'ASC');
        $archivos = $this->db->get('archivo', 1);
        
        $archivo_id = $archivos->row()->id;
        
        return $archivo_id;
    }
    
    function actualizar($archivo_id, $arr_row)
    {
        //Datos adicionales
            $arr_row['editor_id'] = $this->session->userdata('user_id');
        
        //Actualizar
            $this->db->where('id', $archivo_id);
            $this->db->update('archivo', $arr_row);
            
        //Cargar resultado
            $data['saved_id'] = $archivo_id;
            $data['message'] = 'El archivo fue modificado.';

        return $data;
    }
    
    /**
     * Render de Grocery Crud para archivo
     * 
     * @return type
     */
    function crud_editar()
    {
        //Grocery crud
        $this->load->library('grocery_CRUD');
        
        $crud = new grocery_CRUD();
        $crud->set_table('archivo');
        $crud->set_subject('archivo');
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_back_to_list();
        $crud->unset_delete();
        
        //Títulos
            $crud->display_as('titulo_archivo', 'Título');
            $crud->display_as('subtitulo', 'Subtítulo');
            $crud->display_as('descripcion', 'Descripción');
            $crud->display_as('link', 'Link destino');
            
        //Campos
            $crud->edit_fields(
                    'titulo_archivo',
                    'subtitulo',
                    'descripcion',
                    'palabras_clave',
                    'link'
                );
            
            $crud->add_fields(
                    'titulo_archivo',
                    'subtitulo',
                    'descripcion',
                    'palabras_clave',
                    'link'
                );
            
        //Reglas
            $crud->required_fields('titulo_archivo', 'palabras_clave');
                        
        //Formato
            $crud->unset_texteditor('descripcion');
        
        $output = $crud->render();
        
        return $output;
    }
    
    /**
     * Crea el registro del archivo en la tabla archivo
     * 2021-06-18
     */
    function crear_registro($upload_data)
    {
        //Construir registro
            $arr_row['nombre_archivo'] = $upload_data['file_name'];
            $arr_row['ext'] = $upload_data['file_ext'];
            $arr_row['palabras_clave'] = $this->input->post('palabras_clave');
            $arr_row['titulo_archivo'] = str_replace($upload_data['file_ext'], '', $upload_data['client_name']);  //Para quitar la extensión y punto
            $arr_row['carpeta'] = date('Y/m/');
            $arr_row['url'] = URL_UPLOADS . $arr_row['carpeta'] . $upload_data['file_name'];
            $arr_row['url_thumbnail'] = URL_UPLOADS . $arr_row['carpeta'] . '500px_' . $upload_data['file_name'];
            $arr_row['type'] = $upload_data['file_type'];
            $arr_row['es_imagen'] = $upload_data['is_image'];    //Definir si es imagen o no
            $arr_row['meta'] = json_encode($upload_data);
            $arr_row['table_id'] = ( ! is_null($this->input->post('table_id')) ) ? $this->input->post('table_id') : 0;
            $arr_row['related_1'] = ( ! is_null($this->input->post('related_1')) ) ? $this->input->post('related_1') : 0;
            $arr_row['editado'] = date('Y-m-d H:i:s');
            $arr_row['editor_id'] = $this->session->userdata('usuario_id');
            $arr_row['creado'] = date('Y-m-d H:i:s');
            $arr_row['creador_id'] = $this->session->userdata('usuario_id');
            
        //Insertar
            $this->db->insert('archivo', $arr_row);
            
        //Registro
            $row_registro = $this->Pcrn->registro_id('archivo', $this->db->insert_id());

        return $row_registro;
    }
    
    /**
     * Edita el registro del archivo, tabla archivo
     * @param type $upload_data
     */
    function cambiar($archivo_id, $upload_data)
    {
        //Construir registro
            $registro['nombre_archivo'] = $upload_data['file_name'];
            $registro['carpeta'] = date('Y/m/');
            $registro['ext'] = $upload_data['file_ext'];
            $registro['type'] = $upload_data['file_type'];
            $registro['es_imagen'] = $upload_data['is_image'];    //Definir si es imagen o no
            $registro['meta'] = json_encode($upload_data);
            $registro['editor_id'] = $this->session->userdata('usuario_id');
            $registro['editado'] = date('Y-m-d H:i:s');
            
        //Actualizar
            $this->db->where('id', $archivo_id);
            $this->db->update('archivo', $registro);
            
        return $this->db->affected_rows();
    }
    
// ELIMINACIÓN
//-----------------------------------------------------------------------------

    /**
     * Determina si un archivo puede ser o no eliminado por el usuario en sesión
     * 2021-01-27
     */
    function deleteable($file_id)
    {
        $deleteable = false;

        //Administradores pueden eliminar
        if ( in_array($this->session->userdata('role'), array(1,2,3)) ){
            $deleteable = true; 
        } else {
            $row = $this->Db_model->row_id('archivo', $file_id);

            //Si es el usuario creador
            if ( $row->creador_id == $this->session->userdata('user_id') ) { $deleteable = true; }

            //Si es el usuario asociado
            if ( $row->table_id == 1000 && $row->related_1 == $this->session->userdata('user_id') ) { $deleteable = true; }
        }

        return $deleteable;
    }
    
    /**
     * Elimina file del servidor y sus miniaturas y el el registro en la tabla files
     * 2021-02-20
     */
    function delete($file_id)
    {   
        $qty_deleted = 0;

        if ( $this->deleteable($file_id) )
        {
            //Eliminar files del servidor
                $row_file = $this->Db_model->row_id('archivo', $file_id);
                if ( ! is_null($row_file) ) 
                {
                    $this->unlink($row_file->carpeta, $row_file->nombre_archivo);
                }
            
            //Eliminar registros de la base de datos
                $qty_deleted = $this->delete_rows($file_id);
        }

        return $qty_deleted;
    }
    
    /**
     * Elimina de la BD los registros asociados al file
     */
    function delete_rows($file_id)
    {
        $qty_deleted = 0;

        //Desvincular registro de files con otros elementos
            $this->delete_related_rows($file_id);
        
        //Tabla file
            if ( $file_id > 0 )
            {
                $this->db->where('id', $file_id);
                $this->db->delete('archivo');

                $qty_deleted = $this->db->affected_rows();
            }

        return $qty_deleted;
    }
    
    /**
     * Elimina los registros que relacionan al file con otros elementos de la
     * base de datos. Tambien edita los fields de registros referentes al 
     * file_id
     * 2021-06-10
     */
    function delete_related_rows($file_id)
    {
        //Prepara registro
            $arr_row['image_id'] = 0;
            $arr_row['url_image'] = '';
            $arr_row['url_thumbnail'] = '';

        //Prepara registro producto
            $arr_row_producto['imagen_id'] = 0;
            $arr_row_producto['url_image'] = '';
            $arr_row_producto['url_thumbnail'] = '';

        //Actualizar registros en tablas tablas
            $this->db->where('image_id', $file_id)->update('usuario', $arr_row);
            //$this->db->where('image_id', $file_id)->update('posts', $arr_row);
            $this->db->where('imagen_id', $file_id)->update('producto', $arr_row_producto);
    }

    /**
     * Elimina un archivo y sus miniaturas del servidor
     */
    function unlink($folder, $file_name)
    {
        $qty_unlinked = 0;

        $files[] = PATH_UPLOADS . "{$folder}{$file_name}";
        $files[] = PATH_UPLOADS . "{$folder}500px_{$file_name}";

        foreach ( $files as $file_path )
        {
            if ( file_exists($file_path) ) 
            {
                unlink($file_path);
                $qty_unlinked++;
            }
        }
        
        return $qty_unlinked;
    }

//DATOS
//---------------------------------------------------------------------------------------------------
    
    /**
     * Devuelve un array con los atributos de una imagen, para ser usado con la funcion img();
     * 
     * @param type $archivo_id
     * @param type $prefijo
     * @return string
     */
    function att_img($archivo_id, $prefijo= '')
    {
        $row_archivo = $this->Pcrn->registro_id('archivo', $archivo_id);
        
        $att_img = array(
            'src' => URL_UPLOADS . $row_archivo->carpeta . $prefijo . $row_archivo->nombre_archivo,
            'alt' => $row_archivo->nombre_archivo,
            'style' => 'width: 100%'
        );
        
        return $att_img;
    }
    
    function att_thumbnail($archivo_id)
    {
        $row_archivo = $this->Pcrn->registro_id('archivo', $archivo_id);
        
        $att_img = array(
            'src' => URL_UPLOADS . $row_archivo->carpeta . '500px_' . $row_archivo->nombre_archivo,
            'alt' => $row_archivo->nombre_archivo,
            'style' => 'width: 100%',
        );
        
        return $att_img;
    }
    
    function row_img($archivo_id, $prefijo = '')
    {
        $row_img = NULL;
        
        $select = '*, CONCAT("' . URL_UPLOADS . '", (carpeta), "' . $prefijo . '", (nombre_archivo)) AS src';
        
        $this->db->select($select);
        $this->db->where('id', $archivo_id);
        $query = $this->db->get('archivo');
        
        if ( $query->num_rows() > 0 ) { $row_img = $query->row(); }
        
        return $row_img;        
    }

// UPLOAD
//-----------------------------------------------------------------------------

// CARGUE
//---------------------------------------------------------------------------------------------------
    
    /**
     * Realiza el upload de un archivo al servidor, crea el registro asociado en
     * la tabla "archivo".
     * 2021-06-18
     */
    function upload($archivo_id = NULL)
    {
        $config_upload = $this->config_upload();
        $this->load->library('upload', $config_upload);
        
        $row_archivo = $this->Pcrn->registro_id('archivo', $archivo_id);

        if ( $this->upload->do_upload('file_field') )  //El archivo se cargó
        {
            //Identificar registro en la tabla "archivo"
                if ( is_null($archivo_id) ) { $row_archivo = $this->crear_registro($this->upload->data()); }
                
            //Si es imagen, se generan miniaturas y edita archivo original
                if ( $row_archivo->es_imagen )
                {
                    $this->Archivo_model->crear_miniaturas($row_archivo->id);   //Crear miniaturas de la imagen
                    $this->Archivo_model->mod_original_id($row_archivo->id);    //Modificar imagen original después de crear miniaturas
                }
            
            //Array resultado
                $data['status'] = 1;
                $data['upload_data'] = $this->upload->data();
                $data['row'] = $row_archivo;
        }
        else    //No se cargó
        {
            $data = array('status' => 0, 'message' => 'El archivo no fue cargado');
            $data['html'] = $this->upload->display_errors('<div role="alert" class="alert alert-danger"><i class="fa fa-warning"></i> ', '</div>');
        }
        
        return $data;
    }
    
    /**
     * Realiza el upload de una imagen al servidor
     * @return type
     */
    function cargar_img()
    {
        $resultado['mensaje'] = '';
        
        $config = $this->config_upload();
        $this->load->library('upload', $config);

        if ( $this->upload->do_upload('archivo') ) 
        {
            $upload_data = $this->upload->data();
            
            $archivo_id = $this->crear_registro($upload_data);
            $this->crear_miniaturas($archivo_id);
            
            //Cargado
            $resultado['ejecutado'] = 1;
            $resultado['upload_data'] = $upload_data;
            $resultado['archivo_id'] = $archivo_id;
        } else {
            //No cargado
            $resultado['ejecutado'] = 0;
            $resultado['mensaje'] = 'El archivo no fue cargado';
            $resultado['clase'] = 'alert alert-danger';
            $resultado['html'] = $this->upload->display_errors('<div role="alert" class="alert alert-danger">', '<div>');
        }
        
        return $resultado;
    }
    
    /**
     * Configuración para cargue de imágenes
     * @return boolean
     */
    function config_upload()
    {
        $config['upload_path'] = RUTA_UPLOADS . date('Y/m');
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size']	= '5000';   //2MB
        $config['max_width']  = '5000';     //2000px
        $config['max_height']  = '5000';    //2000px
        $config['encrypt_name']  = TRUE;
        
        return $config;
    }
    
    /**
     * Crea los archivos miniaturas de una imagen
     * 
     * @param type $archivo_id
     */
    function crear_miniaturas($archivo_id)
    {
        $anchos = $this->anchos();
        $row_archivo = $this->Pcrn->registro_id('archivo', $archivo_id);
        
        foreach( $anchos as $ancho) {
            $this->crear_miniatura($row_archivo, $ancho);
        }
    }
    
    function mod_original_id($archivo_id)
    {
        $row_archivo = $this->Pcrn->registro_id('archivo', $archivo_id);
        $modificado = $this->mod_original($row_archivo->carpeta, $row_archivo->nombre_archivo);
        
        return $modificado;
    }
    
    /**
     * Modifica la imagen original con un tamaño específico máximo
     * 2021-05-07
     * 
     */
    function mod_original($carpeta, $nombre_archivo)
    {
        $modificado = 0;
        $image_size = getimagesize(RUTA_UPLOADS . $carpeta . $nombre_archivo);
        
        $ancho = 920;   //(1500px hasta 20210507)
        
        $cant_condiciones = 0;
        
        if ( $image_size[0] > $ancho ) { $cant_condiciones++; }
        if ( $image_size[1] > $ancho ) { $cant_condiciones++; }
        
        if ( $cant_condiciones > 0 ) {
            
            $modificado = 1;
            
            $this->load->library('image_lib');

            //Config
                $config['image_library'] = 'gd2';
                $config['source_image'] = RUTA_UPLOADS . $carpeta . $nombre_archivo;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = $ancho;
                $config['height'] = $ancho;
                $config['quality'] = 90;

                $this->image_lib->initialize($config);
                $this->image_lib->resize();
                $this->image_lib->clear();
        }
        
        return $modificado;
        
    }
    
    /**
     * Crea la miniatura de una imagen
     * 
     * @param type $row_archivo
     * @param type $ancho - valor en pixeles de la miniatura
     */
    function crear_miniatura($row_archivo, $ancho)
    {
        $this->load->library('image_lib');
        
        //Config
            $config['image_library'] = 'gd2';
            $config['source_image'] = RUTA_UPLOADS . $row_archivo->carpeta . $row_archivo->nombre_archivo;
            $config['new_image'] = RUTA_UPLOADS . $row_archivo->carpeta . $ancho . 'px_' . $row_archivo->nombre_archivo;;
            $config['maintain_ratio'] = TRUE;
            $config['width'] = $ancho;
            $config['height'] = $ancho;

            $this->image_lib->initialize($config);
            $this->image_lib->resize();
            $this->image_lib->clear();
    }
    
    /**
     * Array con los anchos en pixeles de miniaturas
     * 
     * @return int
     */
    function anchos()
    {
        $anchos = array(500);
        return $anchos;
    }
    
    /**
     * Array con los prefijos de las imágenes
     * @return string
     */
    function prefijos()
    {
        $prefijos = array('');  //Primero, sin prefijo
        $anchos = $this->anchos();
        
        foreach( $anchos as $ancho ){
            $prefijos[] = $ancho . 'px_';
        }
        
        return $prefijos;
    }
    
//GESTIÓN DE ARCHIVOS EN CARPETAS
//---------------------------------------------------------------------------------------------------
    
    /**
     * Listado de archivos en una carpeta
     * 
     * @param type $anio
     * @param type $mes
     * @return type
     */
    function archivos($anio, $mes)
    {
        $this->load->helper('file');
        $archivos = get_filenames(RUTA_UPLOADS . $anio . '/' . $mes);
        
        return $archivos;
    }
    
    /**
     * Elimina los archivos que no están siendo utilizados en la herramienta
     * Se considera no usado si no tiene registro asociado en la tabla "archivo"
     * 2020-05-25
     */
    function unlink_no_usados($year, $month)
    {
        $arr_eliminados = array();
        $this->load->helper('file');
        $archivos = get_filenames(RUTA_UPLOADS . $year . '/' . $month);
        
        $carpeta = "{$year}/{$month}/";
        
        foreach( $archivos as $nombre_archivo )
        {
            
            $sin_prefijo = $this->quitar_prefijo($nombre_archivo);
            $tiene_registro = $this->tiene_registro($carpeta, $sin_prefijo);
            
            if ( ! $tiene_registro ) { 
                $this->unlink($carpeta, $sin_prefijo);
                $arr_eliminados[] = $sin_prefijo;
            }
        }
        
        return $arr_eliminados;
    }
    
    /**
     * Devuelve 1/0, verifica si un archivo tiene registro relacionado
     * en la tabla "archivo"
     */
    function tiene_registro($carpeta, $nombre_archivo)
    {
        $tiene_registro = 0;
        
        $this->db->where('carpeta', $carpeta);
        $this->db->where('nombre_archivo', $nombre_archivo);
        $query = $this->db->get('archivo');
        
        if ( $query->num_rows() > 0 ) { $tiene_registro = 1; }
        
        return $tiene_registro;
    }
    
    /**
     * Le quita el prefijo a un nombre de archivo
     * 
     * @param type $nombre_archivo
     * @return type
     */
    function quitar_prefijo($nombre_archivo)
    {
        $anchos = $this->anchos();
        $sin_prefijo = $nombre_archivo;
        
        foreach ( $anchos as $ancho ) {
            $prefijo = $ancho . 'px_';
            $sin_prefijo = str_replace($prefijo, '', $sin_prefijo);
        }
        
        return $sin_prefijo;
    }

    function unlink_thumbnails($year, $month)
    {
        $cant_eliminados = 0;
        $carpeta = "{$year}/{$month}/";

        $this->db->where('carpeta', $carpeta);
        $archivos = $this->db->get('archivo');

        foreach ($archivos->result() as $row_archivo)
        {
            $file_path_1 = PATH_UPLOADS . $carpeta . '125px_' . $row_archivo->nombre_archivo;
            if ( file_exists($file_path_1) )
            {
                unlink($file_path_1);
                $cant_eliminados++;
            }

            $file_path_2 = PATH_UPLOADS . $carpeta . '250px_' . $row_archivo->nombre_archivo;
            if ( file_exists($file_path_2) )
            {
                unlink($file_path_2);
                $cant_eliminados++;
            }
        }

        $data = array('status' => 0, 'message' => 'No se eliminaron archivos');
        if ( $cant_eliminados > 0 ) {
            $data = array('status' => 1, 'message' => "Archivos eliminados: {$cant_eliminados}");
        }
        
        return $data;
    }
}