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

    /** Exploración de Pedidos */
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
        if ( $this->session->userdata('rol_id') > 2 ) { $usuario_id = $this->session->userdata('usuario_id'); }
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
    
    function enviar_email($usuario_id = 2)
    {
        $this->Usuario_model->email_activacion($usuario_id);
    }
    
    function activar($cod_activacion, $tipo_activacion = 'activar')
    {
        $this->load->model('Esp');
        $row_usuario = $this->Pcrn->registro('usuario', "cod_activacion = '{$cod_activacion}'");        
        
        //Variables
            $data['cod_activacion'] = $cod_activacion;
            $data['tipo_activacion'] = $tipo_activacion;
            $data['row'] = $row_usuario;
            $data['view_a'] = 'usuarios/activar_v';
            
        //Evaluar condiciones
            $condiciones = 0;
            if ( ! is_null($row_usuario) ) { $condiciones++; }
            if ( $this->session->userdata('logged') != TRUE ) { $condiciones++; }
        
        if ( $condiciones == 2 ) 
        {
            $data['head_title'] = "Cuenta de {$row_usuario->nombre}";
            $this->load->view(TPL_FRONT, $data);
        } else {
            redirect('app/no_permitido');
        }
    }
    
    /**
     * Activar usuario
     */
    function activar_e($cod_activacion)
    {
        $data['user_id'] = 0;
        $row_user = $this->Usuario_model->row_activacion($cod_activacion);
        $validar_contrasenas = $this->Usuario_model->validar_contrasenas();
        
        if ( ! is_null($row_user) && $validar_contrasenas ) 
        {
            $this->Usuario_model->activar($cod_activacion);

            $this->load->model('Login_model');
            $this->Login_model->crear_sesion($row_user->username, 1);
            $data['user_id'] = $row_user->id;
        }

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    function test_email($usuario_id, $tipo_activacion = 'activar')
    {
        $row_usuario = $this->Pcrn->registro_id('usuario', $usuario_id);
        $data['row_usuario'] = $row_usuario ;
        $data['tipo_activacion'] = $tipo_activacion;
        
        $this->load->view('usuarios/email_activacion_v', $data);
    }
    
    function aprobar_rol($meta_id)
    {
        $resultado = $this->Usuario_model->aprobar_rol($meta_id);
        $this->session->set_flashdata('resultado', $resultado);
        redirect('usuarios/solicitudes_rol');
    }
    
    function test_aprobado($meta_id = 27106)
    {
        $row_meta = $this->Pcrn->registro_id('meta', $meta_id);
        $row_usuario = $this->Pcrn->registro_id('usuario', $row_meta->elemento_id);
            
        $data['row_meta'] = $row_meta;
        $data['row_usuario'] = $row_usuario;
        $data['style'] = $this->App_model->email_style();
        
        $this->load->view('usuarios/emails/rta_solicitud_v', $data);
    }
    
//RESTAURACIÓN DE CUENTAS
//---------------------------------------------------------------------------------------------------
    
    /**
     * Formulario para recuperar contraseña o reactivar cuenta
     * se ingresa con nombre de usuario y contraseña
     */
    function recuperar($resultado = NULL)
    {
        
        if ( $this->session->userdata('logged') == TRUE ){
            redirect('busquedas/productos');
        } else {
            $data['head_title'] = 'Recuperación de cuentas';
            $data['view_a'] = 'usuarios/recuperar_v';
            $data['resultado'] = $resultado;
            $this->load->view(TPL_FRONT, $data);
        }
    }
    
    /**
     * Formulario para recuperar contraseña o reactivar cuenta
     * se ingresa con nombre de usuario y contraseña
     */
    function recuperar_e()
    {
        $email = $this->input->post('email');
        $resultado = $this->Usuario_model->recuperar($email);
        
        if ( $resultado == 1 ){
            //El correo se encontró y se envío email de recuperación de cuenta
            redirect('usuarios/recuperar/enviado');
        } else {
            //El correo no existe en la base de datos
            redirect('usuarios/recuperar/no_encontrado');
        }
    }
    
    function test_envio($usuario_id)
    {

        if ( $usuario_id == 3484 ) 
        {
            $this->load->library('email');
            $config['mailtype'] = 'html';

            //$row_usuario = $this->Pcrn->registro_id('usuario', $usuario_id);

            $this->email->initialize($config);
            $this->email->from('info@districatolicas.com', 'DistriCatolicas.com');
            $this->email->to('jmojedap@gmail.com');
            $this->email->message('Este es el contenido del mensaje');
            $this->email->subject('Subject del mensaje');
        
            $this->email->send();   //Enviar
            
            echo 'email enviado';
            echo $this->email->print_debugger(array('headers'));
        } else {
            echo 'email no enviado';
        }
    }
    
//---------------------------------------------------------------------------------------------------
    
    function mi_perfil()
    {
        //Datos básicos
        $data = $this->Usuario_model->basic($this->session->userdata('usuario_id'));
        
        //Array data espefícicas
        $data['view_a'] = 'usuarios/profile_v';
        $data['nav_1'] = 'usuarios/menu_personal_v';
        
        $this->load->view(TPL_ADMIN, $data);
    }
    
    /**
     * Editar datos de la cuenta del usuario en sesión
     * 2020-09-30
     */
    function editarme()
    {
        $usuario_id = $this->session->userdata('user_id');
        
        //Datos básicos
        $data = $this->Usuario_model->basic($this->session->userdata('usuario_id'));
        
        //Evita que un usuario edite los datos de otro
        if ( $usuario_id != $this->session->userdata('usuario_id') )
        {
            $data['view_a'] = 'app/no_permitido_v';
        }
        
        //Array data espefícicas
        $data['view_a'] = 'usuarios/edit/edit_v';
        $data['nav_2'] = 'usuarios/menu_personal_v';
        
        $this->load->view(TPL_ADMIN, $data);
    }
    
    /**
     * Cambio de contraseña de cada usuario, el que ha iniciado sesión  
     * @param type $resultado
     */
    function contrasena($resultado = FALSE)
    {
        
        $data = $this->Usuario_model->basic($this->session->userdata('usuario_id'));
        
        $data['resultado'] = $resultado;
        
        //Solicitar vista
            $data['view_a'] = 'usuarios/contrasena_v';
            $this->load->view(TPL_ADMIN, $data);
        
    }
    
    /* Cambio de contraseña de los demás usuarios */
    function cambiar_contrasena()
    {
        $this->load->library('form_validation');
        
        //Reglas
            $this->form_validation->set_rules('password_actual', 'Contraseña actual', 'trim|required|callback__password_check');
            $this->form_validation->set_rules('password', 'Nueva contraseña', 'trim|required|alpha_numeric|min_length[8]');
            $this->form_validation->set_rules('passconf', 'Confirmación de la nueva contraseña', 'trim|required|matches[password]');
        
        //Mensajes de validación
            $this->form_validation->set_message('required', "El campo %s es requerido");
            $this->form_validation->set_message('alpha_numeric', "%s: sólo permite letras y números");
            $this->form_validation->set_message('matches', "El valor de las contraseñas no coincide");
            $this->form_validation->set_message('min_length', 'El valor escrito en %s es demasiado corto');
            $this->form_validation->set_message('_password_check', "La %s no es correcta");
        
        if ( $this->form_validation->run() ){
            //La validación es exitosa, se cambia la contraseña
            $password = $this->input->post('password');
            $usuario_id = $this->session->userdata('usuario_id');
            $resultado = $this->Usuario_model->establecer_contrasena($usuario_id, $password);
            redirect("usuarios/contrasena/{$resultado}");
        } else {
            //La validación falla, retornar al formulario
            $this->contrasena();
        }
    }
    
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
        redirect('usuarios/mi_perfil');
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
        $data['options_book'] = $this->App_model->opciones_post('tipo_id = 8', 'n', 'Libro');

        $data['view_a'] = 'usuarios/books_v';
        if ( $this->session->userdata('role') >= 20 ) { $data['nav_2'] = NULL; }

        $this->App_model->view(TPL_ADMIN, $data);
    }
}