<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends CI_Controller{
    
    function __construct() {
        parent::__construct();
        
        $this->load->model('Login_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
    function index()
    {
        if ( $this->session->userdata('logged') )
        {
            $this->logged();
        } else {
            redirect('productos/catalogo');
        }    
    }
    
//LOGIN
//---------------------------------------------------------------------------------------------------
    
    /**
     * Formulario de login de usuarios se ingresa con nombre de usuario y 
     * contraseña. Los datos se envían vía POST a app/validar_login
     */
    function login()
    {
        redirect('accounts/login/');
    }
    
    /**
     * Proveniente de app/login, se valida los datos de usuario
     * verificando nombre de usuario y contraseña
     */
    function validate_login()
    {
        //Validación de login
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            
            $validate_login = $this->Login_model->validate($username, $password);
        
        //Crea sesión si se valida usuario y contraseña
            if ( $validate_login['status'] )
            {
                $this->Login_model->crear_sesion($username, TRUE);

                if ( $this->input->post('rememberme') ) { $this->Login_model->rememberme(); }
            }

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($validate_login));
    }
    
    /**
     * Destinos a los que se redirige después de validar el login de usuario
     * según el rol de usuario (índice del array)
     */
    function logged()
    {
        $arr_destinos = array(
            0 => 'pedidos/resumen_dia/',
            1 => 'pedidos/resumen_dia/',
            2 => 'pedidos/explore',
            6 => 'productos/explorar',
            7 => 'productos/explorar',
            21 => 'usuarios/books',
            22 => 'usuarios/books',
            23 => 'usuarios/books'
        );
            
        $destino = $arr_destinos[$this->session->userdata('rol_id')];
        
        redirect($destino);
    }

    /**
     * Destruir sesión existente y redirigir al login, inicio.
     */
    function logout()
    {
        //Editar, evento de inicio de sesión
            if ( strlen($this->session->userdata('login_id')) > 0 ) 
            {
                $row_evento = $this->Pcrn->registro_id('evento', $this->session->userdata('login_id'));

                $registro['fin'] = date('Y-m-d H:i:s');
                $registro['estado'] = 2;    //Cerrado
                $registro['segundos'] = $this->Pcrn->segundos_lapso($row_evento->creado, date('Y-m-d H:i:s'));

                if ( ! is_null($row_evento) ) 
                {
                    //Si el evento existe
                    $this->Pcrn->guardar('evento', "id = {$row_evento->id}", $registro);
                }
            }
        
        //Destruir sesión existente y redirigir al login, inicio.
            $this->session->sess_destroy();

        //Eliminar cookie rememberme
            $this->load->helper('cookie');
            delete_cookie('rm');

        //Redirigir a login inicio
            redirect('accounts/login');
    }
    
    
    /**
     * Dirección a la que se redirige un usuario cuando intenta ingresar
     * a una función no permitida o denegada para su rol.
     */
    function no_permitido()
    {
        //$this->output->enable_profiler(TRUE);
        
        $data['view_a'] = 'app/no_permitido_v';
        $data['head_title'] = "Acceso no permitido";
        
        $this->load->view(TPL_FRONT, $data);
    }

    /**
     * Dirección a la que se redirige un usuario cuando intenta ingresar
     * a una función no permitida o denegada para su rol.
     */
    function mantenimiento()
    {
        /*//$this->output->enable_profiler(TRUE);
        
        $data['view_a'] = 'app/mantenimiento_v';
        $data['head_title'] = "Districatólicas";
        
        $this->load->view('app/mantenimiento_v');*/
    }
    
// BÚSQUEDAS Y REDIRECCIONAMIENTO
//-----------------------------------------------------------------------------
    
    /**
     * POST REDIRECT
     * 2017-07-07
     * Toma los datos de POST, los establece en formato GET para url y redirecciona
     * a una controlador y función definidos.
     * 
     * @param type $controlador
     * @param type $funcion
     */
    function buscar($controlador, $funcion)
    {
        $this->load->model('Busqueda_model');
        $busqueda_str = $this->Busqueda_model->busqueda_str();
        redirect("{$controlador}/{$funcion}/?{$busqueda_str}");
    }

//REGISTRO DE USUARIOS
//---------------------------------------------------------------------------------------------------
    
    /**
     * Formulario de registro de nuevos usuarios en el sistema
     */
    function registro()
    {
        $data['head_title'] = 'Crear nueva cuenta';
        $data['view_a'] = 'templates/polo/signup_v';
        $data['recaptcha_sitekey'] = K_RCSK;    //config/constants.php
        
        $this->load->view('templates/polo/main_v', $data);
    }
    
    /**
     * Recibe los datos POST del formulario en app/registro. Si se validan los 
     * datos, se registra el usuario. Si no se valida se redirige a app/registro
     * con mensajes del resultado del proceso.
     */
    /*function registrar()
    {
        $this->load->model('Esp');
        $res_validacion = $this->Esp->validar_registro();
            
        if ( $res_validacion['ejecutado'] )
        {
            //Construir registro del nuevo usuario
                $this->load->model('Usuario_model');
                
                $registro['nombre'] = $this->input->post('nombre');
                $registro['apellidos'] = $this->input->post('apellidos');
                $registro['username'] = $this->Usuario_model->generar_username($registro['nombre'], $registro['apellidos']);
                $registro['email'] = $this->input->post('email');
                $registro['fecha_nacimiento'] = $this->input->post('fecha_nacimiento');
                $registro['sexo'] = $this->input->post('sexo');

            //Crear usuario
                $usuario_id = $this->Usuario_model->crear_usuario($registro);
                
            //Enviar email con código de activación
                $this->Usuario_model->email_activacion($usuario_id);
                
            //Redirigir
                $this->session->set_userdata('usuario_id', $usuario_id);
                redirect("usuarios/registrado");
        } else {
            $this->session->set_flashdata('valores_form', $this->input->post());
            $this->session->set_flashdata('mensajes', $res_validacion['mensajes']);
            redirect('app/registro');
        }
    }*/
    
    /**
     * Se llega aquí mediante el formulario de registro (app/registro)
     * Se valida el formulario antes de crear el registro nuevo de usuario
     */
    function crear_usuario()
    {
        $this->load->library('form_validation');
        
        //Reglas
            $this->form_validation->set_rules('nombre', 'Nombre', 'min_length[2]');
            $this->form_validation->set_rules('apellidos', 'Apellidos', 'min_length[2]');
            $this->form_validation->set_rules('email', 'Correo electrónico', 'trim|valid_email|is_unique[usuario.email]|callback__validar_dominio',
                array(
                    'valid_email' => 'La dirección de correo electrónico que escribió no es válida',
                    'is_unique' =>  'Esa dirección de correo electrónico ya está registrada. Por favor, elija otra'
                )
            );
            $this->form_validation->set_rules('captcha', 'Código de seguridad', 'required|callback__validar_captcha');
            $this->form_validation->set_rules('condiciones', 'Condiciones del servicio', 'callback__validar_condiciones');
            
        
        if ( $this->form_validation->run() == FALSE )
        {
            //La validación falla, retornar al formulario
            $this->registro();
        } else {
            //La validación es exitosa, se crea el usuario
                $registro['nombre'] = $this->input->post('nombre');
                $registro['apellidos'] = $this->input->post('apellidos');
                $registro['username'] = $this->input->post('email');
                $registro['email'] = $this->input->post('email');

                $this->load->model('Usuario_model');
                $usuario_id = $this->Usuario_model->crear_usuario($registro);
            
            //Enviar email con código de activación
                $this->Usuario_model->email_activacion($usuario_id);
                
            //Si el usuario se registra con otro rol
                if ( $this->input->post('rol_id') != 21 )
                {
                    $rol_id = $this->input->post('rol_id');
                    $this->Usuario_model->email_autorizacion($usuario_id, $rol_id);
                }
                
            
            //Redirigir
                $this->session->set_userdata('usuario_id', $usuario_id);
                redirect("usuarios/registrado");
        }
    }    
    

//AJAX GENERALES
//---------------------------------------------------------------------------------------------------
    
    /**
     * Ajax
     * Devuelve slug único
     */
    function slug_unico()
    {
        $texto = $this->input->post('texto');
        $tabla = $this->input->post('tabla');
        $campo = $this->input->post('campo');
        
        $slug_unico = $this->Pcrn->slug_unico($texto, $tabla, $campo);
        
        echo $slug_unico;
    }
    
    /**
     * AJAX
     * Elimina un registro de la tabla meta
     */
    function eliminar_meta()
    {
        $meta_id = $this->input->post('meta_id');
        $row_meta = $this->App_model->eliminar_meta($meta_id);
        
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($row_meta->id));
    }
    
    
//VARIOS
//---------------------------------------------------------------------------------------------------
    /**
     * AJAX
     * Devuelve un resultado JSON, determina si un valor para un campo es único
     * o no
     */
    function es_unico()
    {
        $tabla = $this->input->post('tabla');
        $campo = $this->input->post('campo');
        $valor = trim($this->input->post('valor'));
        $elemento_id = $this->input->post('elemento_id');
        
        $resultado['ejecutado'] = $this->Pcrn->es_unico($tabla, $campo, $valor, $elemento_id);
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($resultado));
    }

//---------------------------------------------------------------------------------------------------
//AUTOCOMPLETAR
    function autocomplete($tabla)
    {
        $data['titulo_pagina'] = 'Autocomplete';
        $data['vista_a'] = 'app/autocomplete_v';
        $data['tabla'] = $tabla;
        $this->load->view('app/bootstrap_test_v', $data);
    }
    
    function arr_elementos_ajax($tabla)
    {
        
        $nombre_modelo = ucfirst($tabla) . '_model';
        
        $this->load->model('Busqueda_model');
        $this->load->model($nombre_modelo);
        $busqueda = $this->Busqueda_model->busqueda_array();
        $busqueda['q'] = $this->input->post('query');
        
        $resultados = $this->$nombre_modelo->autocompletar($busqueda);
        
        $arr_elementos = $resultados->result_array();
        
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($arr_elementos));
    }
    
    function ajax_usuarios()
    {
        $this->load->model('Busqueda_model');
        $this->load->model('Usuario_model');
        
        $busqueda = $this->Busqueda_model->busqueda_array();
        $busqueda['q'] = $this->input->post('query');
        $resultados = $this->Usuario_model->buscar($busqueda, 20);
        
        $arr_usuarios = $resultados->result_array();
        
        $this->output->set_content_type('application/json')->set_output(json_encode($arr_usuarios));
    }
    
//---------------------------------------------------------------------------------------------------
//FUNCIONES INTERNAS
    
    function prueba()
    {
        $data['head_title'] = 'Prueba librería';
        $data['view_a'] = 'app/prueba_v';
        //$this->load->view($data['view_a'], $data);
        //$this->load->view(TPL_ADMIN, $data);
    }

    function carousel()
    {
        $data['head_title'] = 'Carousel Slick';
        $data['view_a'] = 'app/testing/carousel_slick_v';
        //$this->load->view($data['view_a'], $data);
        $this->load->view(TPL_ADMIN, $data);
    }

    function update_address()
    {
        $this->db->select('referente_1_id AS city_id, texto_1, texto_2, usuario_id');
        $this->db->where('tipo_id', 1001);
        $this->db->order_by('referente_2_id', 'ASC');
        $direcciones = $this->db->get('post');

        $data = array();

        foreach ($direcciones->result() as $row_direccion)
        {
            $row_user = $this->Db_model->row_id('usuario', $row_direccion->usuario_id);
            
            $arr_row['ciudad_id'] = $row_direccion->city_id;
            $arr_row['address'] = $row_direccion->texto_1 . ' - ' . $row_direccion->texto_2;

            $this->db->where('id', $row_direccion->usuario_id);
            $this->db->update('usuario', $arr_row);

            $data[$row_direccion->usuario_id] = $arr_row['address'];
        }

        //$data['direcciones'] = $direcciones->result();

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
        
    }

    function geoplugin()
    {
        $data['head_title'] = 'GeoPlugin';
        $data['view_a'] = 'app/testing/geoplugin_v';

        $data['location'] = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$_SERVER['REMOTE_ADDR']));

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}