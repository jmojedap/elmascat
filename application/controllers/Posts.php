<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Posts extends CI_Controller{
    
    function __construct() 
    {
        parent::__construct();
        $this->load->model('Post_model');
        date_default_timezone_set("America/Bogota");    //Para definir hora local
    }
    
    function index($post_id = NULL)
    {
        if ( is_null($post_id) ) {
            redirect("posts/explore/");
        } else {
            redirect("posts/info/{$post_id}");
        }
    }
    
//EXPLORE FUNCTIONS
//---------------------------------------------------------------------------------------------------

    /** Exploración de Posts */
    function explore()
    {        
        //Datos básicos de la exploración
            $data = $this->Post_model->explore_data(1);
        
        //Opciones de filtros de búsqueda
            $data['options_type'] = $this->Item_model->options('categoria_id = 33', 'Todos');
            
        //Arrays con valores para contenido en lista
            $data['arr_types'] = $this->Item_model->arr_cod('categoria_id = 33');
            
        //Cargar vista
            $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Listado de Posts, filtrados por búsqueda, JSON
     */
    function get($num_page = 1)
    {
        $data = $this->Post_model->get($num_page);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * AJAX JSON
     * Eliminar un conjunto de posts seleccionados
     */
    function delete_selected()
    {
        $selected = explode(',', $this->input->post('selected'));
        $data['quan_deleted'] = 0;
        
        foreach ( $selected as $row_id ) 
        {
            $data['quan_deleted'] += $this->Post_model->delete($row_id);
        }

        //Establecer resultado
        if ( $data['qty_deleted'] > 0 ) { $data['status'] = 1; }
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
// CRUD
//-----------------------------------------------------------------------------

    /**
     * Vista Formulario para la creación de un nuevo post
     */
    function add()
    {
        //Variables generales
            $data['head_title'] = 'Post';
            $data['head_subtitle'] = 'Nuevo';
            $data['nav_2'] = 'posts/explore/menu_v';
            $data['view_a'] = 'posts/add_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Crea un nuevo registro en la tabla post
     * 2019-11-29
     */
    function insert()
    {
        $data = $this->Post_model->insert();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * Información general del post
     */
    function info($post_id)
    {        
        //Datos básicos
        $data = $this->Post_model->basic($post_id);
        $data['view_a'] = 'posts/info_v';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    function open($post_id)
    {
        $row = $this->Db_model->row_id('post', $post_id);
        $destination = "posts/info/{$post_id}";

        if ( $row->tipo_id == 8 ) { $destination = "books/read/{$row->code}/0"; }

        redirect($destination);
    }

    function leer($post_id)
    {
        
        $data = $this->Post_model->basic($post_id);    
        
        //Variables
            $data['post_id'] = $post_id;
        
        //Solicitar vista
            $data['titulo_pagina'] = 'Post';
            $data['view_a'] = 'posts/leer_v';
            $this->load->view(TPL_FRONT, $data);
    }
    
// EDICIÓN Y ACTUALIZACIÓN
//-----------------------------------------------------------------------------

    /**
     * Formulario para la edición de los datos de un user. Los datos que se
     * editan dependen de la $section elegida.
     */
    function edit($post_id)
    {
        //Datos básicos
        $data = $this->Post_model->basic($post_id);

        $data['options_type'] = $this->Item_model->options('categoria_id = 33', 'Todos');
        
        //Array data espefícicas
            $data['nav_2'] = 'posts/menu_v';
            $data['head_subtitle'] = 'Editar';
            $data['view_a'] = $this->edit_view($data['row']);
        
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Guardar un registro en la tabla post, si post_id = 0, se crea nuevo registro
     * 2019-11-29
     */
    function update($post_id)
    {
        $data = $this->Post_model->update($post_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Nombre de la vista con el formulario para la edición del post. Puede cambiar dependiendo
     * del tipo (type_id).
     * 2020-02-23
     */
    function edit_view($row)
    {
        $edit_view = 'posts/edit_v';

        return $edit_view;
    }
    
// IMAGEN PRINCIPAL DEL POST
//-----------------------------------------------------------------------------

    function image($post_id)
    {
        $data = $this->Post_model->basic($post_id);        

        $data['view_a'] = 'posts/image/image_v';
        $data['nav_2'] = 'posts/menu_v';
        $data['subtitle_head'] = 'Imagen asociada';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    function cropping($post_id)
    {
        $data = $this->Post_model->basic($post_id);        

        $data['image_id'] = $data['row']->image_id;
        $data['src_image'] = $data['att_img']['src'];
        $data['back_destination'] = "posts/image/{$post_id}";

        $data['view_a'] = 'files/cropping_v';
        $data['nav_2'] = 'posts/menu_v';
        $data['subtitle_head'] = 'Imagen asociada al post';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * AJAX JSON
     * Carga file de image y se la asigna a un post.
     * 2020-02-22
     */
    function set_image($post_id)
    {
        //Cargue
        $this->load->model('File_model');
        $data_upload = $this->File_model->upload();
        
        $data = $data_upload;
        if ( $data_upload['status'] )
        {
            $this->Post_model->remove_image($post_id);                                  //Quitar image actual, si tiene una
            $data = $this->Post_model->set_image($post_id, $data_upload['row']->id);    //Asignar imagen nueva
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Desasigna y elimina la image asociada a un post, si la tiene.
     */
    function remove_image($post_id)
    {
        $data = $this->Post_model->remove_image($post_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// IMPORTACIÓN DE POSTS
//-----------------------------------------------------------------------------

    /**
     * Mostrar formulario de importación de posts
     * con archivo Excel. El resultado del formulario se envía a 
     * 'posts/import_e'
     */
    function import($type = 'general')
    {
        $data = $this->Post_model->import_config($type);

        $data['url_file'] = URL_RESOURCES . 'import_templates/' . $data['template_file_name'];

        $data['head_title'] = 'Posts';
        $data['nav_2'] = 'posts/explore/menu_v';
        $data['view_a'] = 'common/import_v';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }

    //Ejecuta la importación de posts con archivo Excel
    function import_e()
    {
        //Proceso
        $this->load->library('excel');            
        $imported_data = $this->excel->arr_sheet_default($this->input->post('sheet_name'));
        
        if ( $imported_data['status'] == 1 )
        {
            $data = $this->Post_model->import($imported_data['arr_sheet']);
        }

        //Cargue de variables
            $data['status'] = $imported_data['status'];
            $data['message'] = $imported_data['message'];
            $data['arr_sheet'] = $imported_data['arr_sheet'];
            $data['sheet_name'] = $this->input->post('sheet_name');
            $data['back_destination'] = "posts/explore/";
        
        //Cargar vista
            $data['head_title'] = 'Posts';
            $data['head_subtitle'] = 'Resultado importación';
            $data['view_a'] = 'common/import_result_v';
            $data['nav_2'] = 'posts/explore/menu_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

// Asignación a usuario
//-----------------------------------------------------------------------------

    /**
     * Asigna un contenido digital a un usuario
     */
    function add_to_user($post_id, $user_id)
    {
        $data = $this->Post_model->add_to_user($post_id, $user_id);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Retira un contenido digital a un usuario
     * 2020-04-30
     */
    function remove_to_user($post_id, $meta_id)
    {
        $data = $this->Post_model->remove_to_user($post_id, $meta_id);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

//LISTAS - TIPO 22
//---------------------------------------------------------------------------------------------------
    
    /**
     * Muestra los elementos de un post tipo lista, CRUD de los elementos de la lista
     * 
     * @param type $post_id
     */
    function lista($post_id)
    {
        //$this->output->enable_profiler(TRUE);
            $this->load->model('Esp');
            
        //Datos básicos
            $data = $this->Post_model->basic($post_id);
        
        //Variables
            $tabla_id = $this->Pcrn->si_vacia($data['row']->referente_1_id, '1000');
            $elementos_lista = $this->Post_model->metadata($post_id, 22);  //22, tipo de dato, elementos de lista
            
        //Cargando variables
            $data['tabla_id'] = $tabla_id;
            $data['tabla'] = $this->Pcrn->campo('sis_tabla', "id = {$tabla_id}", 'nombre_tabla');
            $data['elementos_lista'] = $elementos_lista;
            
        //Array data espefícicas
            //$data['view_a'] = 'posts/listas/lista_v';
            $data['view_a'] = 'posts/listas/elementos_v';
        
        $this->load->view(TPL_ADMIN, $data);
    }
    
    function reordenar_lista($post_id)
    {
        $str_orden = $this->input->post('str_orden');
        
        parse_str($str_orden);
        $arr_elementos = $elemento;
        
        $cant_elementos = $this->Post_model->reordenar_lista($post_id, $arr_elementos);
        
        $this->output->set_content_type('application/json')->set_output(json_encode($cant_elementos));
    }



}