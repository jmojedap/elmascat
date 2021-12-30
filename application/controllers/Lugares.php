<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lugares extends CI_Controller{

// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'admin/lugares/';
    public $url_controller = URL_ADMIN . 'lugares/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct() 
    {
        parent::__construct();

        $this->load->model('Lugar_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
    function index($place_id = null)
    {
        if ( is_null($place_id) ) {
            redirect('lugares/explore');
        } else {
            redirect("lugares/details/{$place_id}");
        }
    }
    
//EXPLORE
//---------------------------------------------------------------------------------------------------
            
    /**
     * Exploración y búsqueda de usuarios
     * 2020-08-01
     */
    function explore($num_page = 1)
    {        
        //Identificar filtros de búsqueda
            $this->load->model('Search_model');
            $filters = $this->Search_model->filters();

        //Datos básicos de la exploración
            $data = $this->Lugar_model->explore_data($filters, $num_page);
        
        //Opciones de filtros de búsqueda
            $data['options_type'] = $this->Item_model->options('categoria_id = 70', 'Todos');
            $data['options_status'] = array('' => '[ Todos los status ]', '00' => 'Inactivo', '01' => 'Activo');
            
        //Arrays con valores para contenido en lista
            $data['arr_types'] = $this->Item_model->arr_cod('categoria_id = 70');
            
        //Cargar vista
            $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * JSON
     * Listado de lugares, según filtros de búsqueda
     */
    function get($num_page = 1, $per_page = 15)
    {
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();
        $data = $this->Lugar_model->get($filters, $num_page, $per_page);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * AJAX JSON
     * Eliminar un conjunto de lugares seleccionados
     * 2021-02-20
     */
    function delete_selected()
    {
        $selected = explode(',', $this->input->post('selected'));
        $data['qty_deleted'] = 0;
        
        foreach ( $selected as $row_id ) $data['qty_deleted'] += $this->Lugar_model->delete($row_id);
        
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// INFORMACIÓN
//-----------------------------------------------------------------------------

    function info($place_id)
    {
        $data = $this->Lugar_model->basic($place_id);
        $data['view_a'] = $this->views_folder . 'info_v';
        $data['nav_2'] = $this->views_folder . 'menu_v';
        $data['back_link'] = $this->url_controller . 'explore';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    function details($place_id)
    {
        $data = $this->Lugar_model->basic($place_id);
        $data['view_a'] = 'common/row_details_v';
        $data['nav_2'] = $this->views_folder . 'menu_v';
        $data['back_link'] = $this->url_controller . 'explore';
        $this->App_model->view(TPL_ADMIN, $data);
    }

// CREACIÓN Y EDICIÓN
//-----------------------------------------------------------------------------

    function add()
    {
        //Formulario
        $data['options_type'] = $this->Item_model->options('categoria_id = 70');
        $data['options_country'] = $this->App_model->opciones_lugar('tipo_id = 2');
        $data['options_region'] = $this->App_model->opciones_lugar('tipo_id = 3 AND pais_id = 51', 'nombre_lugar');
        $data['options_status'] = array('00' => 'Inactivo', '01' => 'Activo');

        //Vista
        $data['view_a'] = $this->views_folder . 'add_v';
        $data['nav_2'] = $this->views_folder . 'explore/menu_v';
        $data['head_title'] = 'Nuevo lugar';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    function edit($place_id)
    {
        //Formulario
        $data = $this->Lugar_model->basic($place_id);

        $data['options_type'] = $this->Item_model->options('categoria_id = 70');
        $data['options_country'] = $this->App_model->opciones_lugar('tipo_id = 2');
        $data['options_region'] = $this->App_model->opciones_lugar('tipo_id = 3 AND pais_id = 51', 'nombre_lugar');
        $data['options_status'] = array('00' => 'Inactivo', '01' => 'Activo');

        //Vista
        $data['view_a'] = $this->views_folder . 'edit_v';
        $data['nav_2'] = $this->views_folder . 'menu_v';
        $data['back_link'] = $this->url_controller . 'explore';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Crear o actualizar registro de lugar, tabla lugares
     * 2021-03-17
     */
    function save($place_id = 0)
    {
        $arr_row = $this->input->post();
        $data['saved_id'] = $this->Lugar_model->save($arr_row, $place_id);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Cambiar el estado de un lugar, campo lugares.status
     * 2021-05-18
     */
    function set_status()
    {
        $arr_row = $this->input->post();
        $data['saved_id'] = $this->Db_model->save_id('lugar', $arr_row);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// Servicios
//-----------------------------------------------------------------------------

    /**
     * Array con opciones de lugar, formato para elemento Select de un form HTML
     * Utiliza los mismos filtros de la sección de exploración
     * 2021-03-16
     */
    function get_options($field_name = 'place_name')
    {
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();
        $data = $this->Lugar_model->get($filters, 1, 500);

        $options = array('' => '[ Seleccione ]');
        foreach ($data['list'] as $place)
        {
            $options['0' . $place->id] = $place->$field_name;
        }

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($options));
    }
}