<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends CI_Controller{
    
    function __construct() 
    {
        parent::__construct();

        $this->load->model('Usuario_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }

//EXPLORACIÓN
//---------------------------------------------------------------------------------------------------

    function explorar()
    {
        redirect('usuarios/explore');
    }

    /** Exploración de Usuarios */
    function explore($num_page = 1)
    {
        //Identificar filtros de búsqueda
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        //Datos básicos de la exploración
            $data = $this->Usuario_model->explore_data($filters, $num_page);
        
        //Opciones de filtros de búsqueda
            $data['options_role'] = $this->Item_model->options('categoria_id = 58', 'Todos');
            $data['options_gender'] = $this->Item_model->options('categoria_id = 59 AND id_interno IN (1,2,90)', 'Todos');
            
        //Arrays con valores para contenido en lista
            $data['arr_roles'] = $this->Item_model->arr_cod('categoria_id = 58');
            $data['arr_genders'] = $this->Item_model->arr_cod('categoria_id = 59');
            $data['arr_id_number_types'] = $this->Item_model->arr_item('53', 'id_interno_num_abreviatura');
            
        //Cargar vista
            $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Listado de Usuarios, filtrados por búsqueda, JSON
     */
    function get($num_page = 1)
    {
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $data = $this->Usuario_model->get($filters, $num_page);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
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
            $resultados_total = $this->Usuario_model->buscar($busqueda); //Para calcular el total de resultados
        
        //Preparar datos
            $datos['nombre_hoja'] = 'Usuarios';
            $datos['query'] = $resultados_total;
            
        //Preparar archivo
            $objeto_archivo = $this->Pcrn_excel->archivo_query($datos);
        
        $data['objeto_archivo'] = $objeto_archivo;
        $data['nombre_archivo'] = date('Ymd_His'). '_usuarios.xls'; //save our workbook as this file name
        
        $this->load->view('comunes/descargar_phpexcel_v', $data);
            
    }
    
    /**
     * Eliminar un grupo de usuarios seleccionados
     */
    function delete_selected()
    {
        $selected = explode(',', $this->input->post('selected'));
        $data['qty_deleted'] = 0;
        
        foreach ( $selected as $row_id ) 
        {
            $data['qty_deleted'] += $this->Usuario_model->delete($row_id);
        }

        //Establecer resultado
        if ( $data['qty_deleted'] > 0 ) { $data['status'] = 1; }
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function procesos($usuario_id)
    {
        //Datos básicos
        if ( $this->session->userdata('role') > 2 ) { $usuario_id = $this->session->userdata('usuario_id'); }
        $data = $this->Usuario_model->basic($usuario_id);
        
        //Array data espefícicas
        $data['vista_b'] = 'usuarios/procesos_v';
        $data['menu'] = 'usuarios/menu_admin_v';
        
        $this->load->view(TPL_ADMIN, $data);
    }
    
    function solicitudes_rol()
    {
        //Variables específicas
            $data['solicitudes'] = $this->Usuario_model->solicitudes();

        //Variables generales
            $data['head_title'] = 'Usuarios';
            $data['head_subtitle'] = 'Solicitudes de cambio de rol';
            $data['view_a'] = 'usuarios/solicitudes_rol_v';
            $data['nav_2'] = 'usuarios/explore/menu_v';

        $this->load->view(TPL_ADMIN, $data);
    }

    /**
     * Vista datos generales del usuario
     * 2020-11-11
     */
    function profile($usuario_id)
    {
        //Datos básicos
        if ( $this->session->userdata('role') > 6 ) { $usuario_id = $this->session->userdata('user_id'); }
        $data = $this->Usuario_model->basic($usuario_id);

        $data['qty_login'] = $this->Db_model->num_rows('evento', "tipo_id = 101 AND usuario_id = {$usuario_id}");
        $data['qty_open_posts'] = $this->Db_model->num_rows('evento', "tipo_id = 51 AND usuario_id = {$usuario_id}");
        
        //Array data espefícicas
        $data['view_a'] = 'usuarios/profile_v';
        
        $this->load->view(TPL_ADMIN, $data);
    }

// CREACIÓN Y EDICIÓN
//-----------------------------------------------------------------------------
    
    /**
     * Formulario creación de nuevo usuario
     * 2020-09-26
     */
    function add($type = 'person')
    {
        $this->load->helper('string');
        
        //Array data espefícicas
            $data['head_title'] = 'Crear usuario';
            $data['nav_2'] = 'usuarios/explore/menu_v';
            $data['view_a'] = 'usuarios/add/add_v';
        
        $this->load->view(TPL_ADMIN, $data);
    }

    /**
     * AJAX JSON
     * Valida valores correctos para formulario de creación o edición de usuarios
     * 2020-09-20
     */
    function validate($user_id = NULL)
    {
        $this->load->model('Validation_model');

        $data = array('status' => 1);   //Valor inicial
        
        $validation_email = $this->Validation_model->email($user_id);
        $validation_id_number = $this->Validation_model->id_number($user_id);
        
        $validation = array_merge($validation_email, $validation_id_number);

        //Comprobar que cada elemento sea válido
        foreach ( $validation as $value )
        {
            if ( $value == FALSE ) { $data = array('status' => 0); }
        }

        $data['validation'] = $validation;

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * Crear un registro en la tabla usuario
     * 2020-09-26
     */
    function insert()
    {
        $arr_row = $this->input->post();
        $arr_row['estado'] = 2; //Registrado
        
        $data = $this->Usuario_model->insert($arr_row);
        
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Formulario edición de un usuario
     * 2020-09-26
     */
    function edit($user_id)
    {
        $data = $this->Usuario_model->basic($user_id);   
        $data['view_a'] = 'usuarios/edit/edit_v';
        $this->load->view(TPL_ADMIN, $data);
    }

    /**
     * Actualizar registro en la tabla usuario
     * 2020-09-28
     */
    function update($user_id)
    {
        $arr_row = $this->input->post();
        $data = $this->Usuario_model->update($user_id, $arr_row);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * Formulario GroceryCRUD para la edición de los datos de un usuario.
     */
    function editar()
    {
        $usuario_id = $this->uri->segment(4);
        
        //Datos básicos
        $data = $this->Usuario_model->basic($usuario_id);
        
        $gc_output = $this->Usuario_model->crud_admin($data['row']);
        
        //Array data espefícicas
            $data['view_a'] = 'usuarios/editar_v';
        
        $output = array_merge($data,(array)$gc_output);
        $this->load->view(TPL_ADMIN, $output);
    }
    
//GESTIÓN DE CUENTAS
//---------------------------------------------------------------------------------------------------
    
    function registrado()
    {
        $usuario_id = $this->session->userdata('usuario_id');
        $row_usuario = $this->Pcrn->registro_id('usuario', $usuario_id);
        
        //Solicitar vista
            $data['head_title'] = 'Usuario registrado';
            $data['head_subtitle'] = $row_usuario->nombre . ' ' . $row_usuario->apellidos;
            $data['view_a'] = 'usuarios/registrado_v';
            $this->load->view(TPL_FRONT, $data);
    }
    
//---------------------------------------------------------------------------------------------------
    
    /**
     * AJAX, devuelve un valor de username sugerido disponible, dados los nombres y apellidos
     */
    function username()
    {
        $nombre = $this->input->post('nombre');
        $apellidos = $this->input->post('apellidos');
        $username = $this->Usuario_model->generar_username($nombre, $apellidos);
        
        $this->output->set_content_type('application/json')->set_output(json_encode($username));
    }

    /**
     * Actualiza el campo user.activation_key, para activar o restaurar la contraseña de un usuario
     * 2019-11-18
     */
    function set_activation_key($user_id)
    {
        $this->load->model('Account_model');
        $activation_key = $this->Account_model->activation_key($user_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($activation_key));
    }
    
// PROCESOS
//-----------------------------------------------------------------------------
    
    function solicitar_rol($usuario_id, $rol_id)
    {
        $resultado = $this->Usuario_model->solicitar_rol($usuario_id, $rol_id);
        $this->session->set_flashdata('resultado', $resultado);
        redirect('accounts/profile');
    }
    
//PEDIDOS POR USUARIO
//--------------------------------------------------------------------------------------------------- 
    
    function pedidos($usuario_id) 
    {
        if ( $this->session->userdata('role') > 6 ) { $usuario_id = $this->session->userdata('user_id'); }
        $data = $this->Usuario_model->basic($usuario_id);
        
        //Variables específicas
            $data['pedidos'] = $this->Usuario_model->pedidos($usuario_id);

        //Variables generales
        $data['view_a'] = 'usuarios/pedidos_v';
        if ( $this->session->userdata('role') >= 20 )
        {
            $data['head_title'] = 'Mis pedidos';
            $data['nav_2'] = NULL; 
        }

        $this->load->view(TPL_ADMIN, $data);
    }
    
    function mis_pedidos()
    {
        $usuario_id = $this->session->userdata('usuario_id');
        $this->pedidos($usuario_id);
    }

// CONTENIDOS ASIGNADOS
//-----------------------------------------------------------------------------

    /**
     * Contenidos digitales asignados a un usuario
     * 2020-04-15
     */
    function books($user_id = 0)
    {
        $this->load->model('Archivo_model');
        //Control de permisos de acceso
        if ( $this->session->userdata('role') >= 10 ) { $user_id = $this->session->userdata('user_id'); }
        if ( $user_id == 0 ) { $user_id = $this->session->userdata('user_id'); }

        $data = $this->Usuario_model->basic($user_id);

        $data['books'] = $this->Usuario_model->assigned_posts($user_id);

        //Opciones de libro
        $fecha_publicado_desde = date("Y-m-d H:i:s", strtotime("-1 months"));
        $fecha_publicado_hasta = date("Y-m-d H:i:s", strtotime("+13 months"));

        $condition = "tipo_id = 8 AND publicado >='{$fecha_publicado_desde}' AND publicado <= '{$fecha_publicado_hasta}'";
        $data['options_book'] = $this->App_model->opciones_post($condition, 'n', 'Libro');

        $data['view_a'] = 'usuarios/books_v';
        if ( $this->session->userdata('role') >= 20 ) { $data['nav_2'] = NULL; }

        $this->App_model->view(TPL_ADMIN, $data);
    }
}