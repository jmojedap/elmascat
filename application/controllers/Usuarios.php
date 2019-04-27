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
    
//---------------------------------------------------------------------------------------------------
//GESTIÓN DE USUARIOS
    
    /**
     * Exploración y búsqueda de productos, administración
     */
    function explorar()
    {
        //Datos básicos de la exploración
            $data = $this->Usuario_model->data_explorar();
        
        //Opciones de filtros de búsqueda
            $data['arr_filtros'] = array('rol', 'sexo');
            $data['opciones_rol'] = $this->Item_model->opciones('categoria_id = 58', 'Todos');
            $data['opciones_sexo'] = $this->Item_model->opciones('categoria_id = 59 and item_grupo = 1', 'Todos');
            
        //Arrays con valores para contenido en lista
            $data['arr_roles'] = $this->Item_model->arr_interno('categoria_id = 58');
            $data['arr_sexos'] = $this->Item_model->arr_interno('categoria_id = 59');
        
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
    function eliminar_seleccionados()
    {
        $str_seleccionados = $this->input->post('seleccionados');
        
        $seleccionados = explode('-', $str_seleccionados);
        
        foreach ( $seleccionados as $elemento_id ) {
            $this->Usuario_model->eliminar($elemento_id);
        }
        
        echo count($seleccionados);
    }
    
    function nuevo()
    {
        $this->load->helper('string');
        
        //Array data espefícicas
            $data['titulo_pagina'] = 'Usuarios';
            $data['subtitulo_pagina'] = 'Nuevo';
            $data['vista_menu'] = 'usuarios/explorar/menu_v';
            $data['vista_a'] = 'usuarios/nuevo/nuevo_v';
        
        $this->load->view(PTL_ADMIN, $data);
    }
    
    function insertar()
    {
        $registro = $this->input->post();
        $registro['estado'] = 1; //Activo
        
        $resultado = $this->Usuario_model->insertar($registro);
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($resultado));
    }
    
    /**
     * Formulario GroceryCRUD para la edición de los datos de un usuario.
     */
    function editar()
    {
        $usuario_id = $this->uri->segment(4);
        
        //Datos básicos
        $data = $this->Usuario_model->basico($usuario_id);
        
        $gc_output = $this->Usuario_model->crud_admin($data['row']);
        
        //Array data espefícicas
            $data['vista_b'] = 'usuarios/editar_v';
            $data['vista_menu'] = 'usuarios/menu_admin_v';
        
        $output = array_merge($data,(array)$gc_output);
        $this->load->view(PTL_ADMIN, $output);
    }
    
    function info($usuario_id)
    {
        //Datos básicos
        if ( $this->session->userdata('rol_id') > 2 ) { $usuario_id = $this->session->userdata('usuario_id'); }
        $data = $this->Usuario_model->basico($usuario_id);
        
        //Array data espefícicas
        $data['vista_b'] = 'usuarios/info_v';
        $data['vista_menu'] = 'usuarios/menu_admin_v';
        
        $this->load->view(PTL_ADMIN, $data);
    }
    
    function procesos($usuario_id)
    {
        //Datos básicos
        if ( $this->session->userdata('rol_id') > 2 ) { $usuario_id = $this->session->userdata('usuario_id'); }
        $data = $this->Usuario_model->basico($usuario_id);
        
        //Array data espefícicas
        $data['vista_b'] = 'usuarios/procesos_v';
        $data['menu'] = 'usuarios/menu_admin_v';
        
        $this->load->view(PTL_ADMIN, $data);
    }
    
    function solicitudes_rol()
    {
        //Variables específicas
            $data['solicitudes'] = $this->Usuario_model->solicitudes();

        //Variables generales
            $data['titulo_pagina'] = 'Usuarios';
            $data['subtitulo_pagina'] = 'Solicitudes de cambio de rol';
            $data['vista_a'] = 'usuarios/solicitudes_rol_v';
            $data['vista_menu'] = 'usuarios/explorar/menu_v';

        $this->load->view(PTL_ADMIN, $data);
    }
    
//GESTIÓN DE CUENTAS
//---------------------------------------------------------------------------------------------------
    
    function registrado()
    {
        $usuario_id = $this->session->userdata('usuario_id');
        $row_usuario = $this->Pcrn->registro_id('usuario', $usuario_id);
        
        //Solicitar vista
            $data['titulo_pagina'] = 'Usuario registrado';
            $data['subtitulo_pagina'] = $row_usuario->nombre . ' ' . $row_usuario->apellidos;
            $data['vista_a'] = 'usuarios/registrado_v';
            $this->load->view(PTL_FRONT, $data);
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
            $data['vista_a'] = 'usuarios/activar_v';
            
        //Evaluar condiciones
            $condiciones = 0;
            if ( ! is_null($row_usuario) ) { $condiciones++; }
            if ( $this->session->userdata('logged') != TRUE ) { $condiciones++; }
        
        if ( $condiciones == 2 ) 
        {
            $data['titulo_pagina'] = "Cuenta de {$row_usuario->nombre}";
            $this->load->view(PTL_FRONT, $data);
        } else {
            redirect('app/no_permitido');
        }
    }
    
    /**
     * Activar usuario
     * @param type $cod_activacion
     */
    function activar_ajax($cod_activacion)
    {
        
        $usuario_id = 0;
        $row_usuario = $this->Usuario_model->row_activacion($cod_activacion);
        
        if ( ! is_null($row_usuario) ) 
        {
            $this->Usuario_model->activar($row_usuario->id);

            $this->load->model('Esp');
            $this->Esp->crear_sesion($row_usuario->username, 1);
            $usuario_id = $row_usuario->id;
        }
        
        echo $usuario_id;
    }
    
    function activar_e($cod_activacion)
    {
        //$this->output->enable_profiler(TRUE);
        $validar_contrasenas = $this->Usuario_model->validar_contrasenas();
        
        if ( $validar_contrasenas ) 
        {
            $row_usuario = $this->Usuario_model->activar($cod_activacion);

            $this->load->model('Login_model');
            $this->Login_model->crear_sesion($row_usuario->username, 1);
            redirect("usuarios/mi_perfil");
        } else {
            $this->activar($cod_activacion);
        }
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
            $data['titulo_pagina'] = 'Recuperación de cuentas';
            $data['vista_a'] = 'usuarios/recuperar_v';
            $data['resultado'] = $resultado;
            $this->load->view(PTL_FRONT, $data);
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
            $this->email->from('info@districatolicas.com', 'Districatólicas Unidas S.A.S.');
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
        $data = $this->Usuario_model->basico($this->session->userdata('usuario_id'));
        
        //Array data espefícicas
        $data['vista_b'] = 'usuarios/info_v';
        $data['vista_menu'] = 'usuarios/menu_personal_v';
        
        $this->load->view(PTL_ADMIN, $data);
    }
    
    function editarme()
    {
        $usuario_id = $this->uri->segment(4);
        
        //Datos básicos
        $data = $this->Usuario_model->basico($this->session->userdata('usuario_id'));
        
        $gc_output = $this->Usuario_model->crud_basico($data['row']);
        
        //Evita que un usuario edite los datos de otro
        if ( $usuario_id != $this->session->userdata('usuario_id') )
        {
            $data['vista_a'] = 'app/no_permitido_v';
        }
        
        //Array data espefícicas
        $data['vista_b'] = 'usuarios/editar_v';
        $data['vista_menu'] = 'usuarios/menu_personal_v';
        
        
        $output = array_merge($data,(array)$gc_output);
        $this->load->view(PTL_ADMIN, $output);
    }
    
    /**
     * Cambio de contraseña de cada usuario, el que ha iniciado sesión  
     * @param type $resultado
     */
    function contrasena($resultado = FALSE)
    {
        
        $data = $this->Usuario_model->basico($this->session->userdata('usuario_id'));
        
        $data['resultado'] = $resultado;
        
        //Solicitar vista
            $data['vista_menu'] = 'usuarios/menu_personal_v';
            $data['vista_b'] = 'usuarios/contrasena_v';
            $this->load->view(PTL_ADMIN, $data);
        
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
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($username));
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
        if( $this->session->userdata('rol_id') > 2 ) { $usuario_id = $this->session->userdata('usuario_id'); }
        $data = $this->Usuario_model->basico($usuario_id);
        
        //Variables específicas
            $data['pedidos'] = $this->Usuario_model->pedidos($usuario_id);
        

        //Variables generales
        $data['subtitulo_pagina'] = 'Pedidos';
        $data['vista_b'] = 'usuarios/pedidos_v';

        $this->load->view(PTL_ADMIN, $data);
    }
    
    function mis_pedidos()
    {
        $usuario_id = $this->session->userdata('usuario_id');
        $this->pedidos($usuario_id);
    }

//DIRECCIONES DE ENTREGA
//---------------------------------------------------------------------------------------------------

    /**
     * Administración de direcciones de entrega de un usuario
     * @param type $usuario_id
     */
    function direcciones($usuario_id)
    {   
        if( $this->session->userdata('rol_id') > 2 ) { $usuario_id = $this->session->userdata('usuario_id'); }
        $data = $this->Usuario_model->basico($usuario_id);
        
        //Render grocery crud
            $gc_output = $this->Usuario_model->crud_direccion($usuario_id);
        
        //Direcciones actuales
            $data['direcciones'] = $this->Usuario_model->direcciones($usuario_id);
            $data['direccion_editable'] = $this->Usuario_model->direccion_editable($this->uri->segment(5));
        
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Direcciones';
            $data['vista_b'] = 'usuarios/direcciones_v';
            $data['vista_menu'] = 'usuarios/menu_personal_v';
        
            $output = array_merge($data,(array)$gc_output);
            $this->load->view(PTL_ADMIN, $output);
    }
    
    /**
     * Establece una dirección de un usuario como la principal
     * 
     * @param type $usuario_id
     * @param type $direccion_id
     */
    function act_dir_principal($usuario_id, $direccion_id)
    {
        $this->Usuario_model->act_dir_principal($usuario_id, $direccion_id);
        redirect("usuarios/direcciones/{$usuario_id}");
    }
    
    /**
     * REDIRECT
     * Elimina una dirección de entrega de un usuario.
     * 
     * @param type $usuario_id
     * @param type $direccion_id
     */
    function eliminar_direccion($usuario_id, $direccion_id) 
    {
        $resultado = $this->Usuario_model->eliminar_direccion($usuario_id, $direccion_id);
        
        $this->session->set_flashdata('resultado', $resultado);
        redirect("usuarios/direcciones/{$usuario_id}");
    }
    
}