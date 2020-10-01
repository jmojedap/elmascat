<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accounts extends CI_Controller {
    
    function __construct() 
    {
        parent::__construct();
        
        $this->load->model('Account_model');
        
        //Local time set
        date_default_timezone_set("America/Bogota");
    }

    /**
     * Primera función de la aplicación
     */
    function index()
    {
        if ( $this->session->userdata('logged') )
        {
            redirect('app/logged');
        } else {
            redirect('accounts/login');
        }    
    }
    
//LOGIN
//---------------------------------------------------------------------------------------------------
    
    /**
     * Form login de users se ingresa con nombre de user y 
     * contraseña. Los datos se envían vía ajax a accounts/validate_login
     * 2020-05-05
     */
    function login()
    {
        //Verificar si es recordado en el equipo (rm: rememberme)
            $this->load->model('Login_model');
            $data['rm'] = $this->Login_model->login_cookie();
        
        //Verificar si está logueado
            if ( $this->session->userdata('logged') )
            {
                redirect('app/logged');
            } else {
                $data['head_title'] = APP_NAME;
                $data['view_a'] = 'accounts/login_v';
                if ( $this->uri->segment(3) == 'facebook' )
                {
                    $data['view_a'] = 'accounts/login_facebook_v';
                }
                //$data['g_client'] = $this->Account_model->g_client(); //Para botón login con Google
                $this->load->view('templates/polo/main_v', $data);
            }
    }

    function validate_login()
    {
        //Setting variables
            $userlogin = $this->input->post('username');
            $password = $this->input->post('password');
            
            $data = $this->Account_model->validate_login($userlogin, $password);
            
            if ( $data['status'] )
            {
                $this->Account_model->create_session($userlogin, TRUE);
            }
            
        //Salida
            $this->output->set_content_type('application/json')->set_output(json_encode($data));      
    }
    
    /**
     * Destroy session and redirect to login, start.
     */
    function logout()
    {
        $this->Account_model->logout();
        redirect('accounts/login');
    }

    //ML Master Login, 
    function ml($user_id)
    {
        $username = $this->Db_model->field_id('user', $user_id, 'username');
        if ( $this->session->userdata('role') <= 1 ) { $this->Account_model->create_session($username, FALSE); }
        
        redirect('app/logged');
    }
    
//USERS REGISTRATION
//---------------------------------------------------------------------------------------------------
    
    /**
     * Form de registro de nuevos users en el sistema, se envían los
     * datos a accounts/register
     */
    function signup($with_email = FALSE)
    {
        $data['head_title'] = 'Crear tu cuenta de ' . APP_NAME ;
        $data['view_a'] = 'templates/polo/signup_v';
        $data['recaptcha_sitekey'] = K_RCSK;    //config/constants.php
        $this->load->view('templates/polo/main_v', $data);
    }
    
    /**
     * AJAX JSON
     * 
     * Recibe los datos POST del form en accounts/signup. Si se validan los 
     * datos, se registra el user. Se devuelve $data, con resultados de registro
     * o de validación (si falló).
     * 2020-02-12
     */
    function register()
    {
        $data = array('status' => 0);  //Initial result values
        $res_validation = $this->Account_model->validate();
        $recaptcha = $this->App_model->recaptcha();
            
        if ( $res_validation['status'] && $recaptcha->score > 0.5 )
        {
            //Construir registro del nuevo user
                $arr_row['nombre'] = $this->input->post('nombre');
                $arr_row['apellidos'] = $this->input->post('apellidos');
                $arr_row['display_name'] = $this->input->post('nombre') . ' ' . $this->input->post('apellidos');
                $arr_row['email'] = $this->input->post('email');
                $arr_row['fecha_nacimiento'] = $this->input->post('fecha_nacimiento');
                $arr_row['sexo'] = $this->input->post('sexo');
                $arr_row['username'] = $arr_row['email'];
                $arr_row['rol_id'] = 21;  //21: Cliente

            //Crear
                $this->load->model('Usuario_model');
                $data['saved_id'] = $this->Usuario_model->crear_usuario($arr_row);

                if ( $data['saved_id'] > 0 ) { $data['status'] = 1; }
            
            //Enviar email con código de activación
                $this->Usuario_model->email_activacion($data['saved_id']);
        } else {
            $data['validation'] = $res_validation['validation'];
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * AJAX JSON
     * Validation of form signup user data
     */
    function validate_signup()
    {
        $data = $this->Account_model->validate();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * WEB VIEW
     * User signup confirmation message
     */
    function registered($user_id)
    {   
        //Solicitar vista
        $data['head_title'] = 'Usuario registrado';
        $data['row'] = $this->Db_model->row_id('usuario', $user_id);
        $data['view_a'] = 'accounts/registered_v';
        $this->load->view('templates/polo/main_v', $data);
    }

// ACTIVATION
//-----------------------------------------------------------------------------

    function activation($activation_key, $activation_type = 'activation')
    {
        $row_user = $this->Db_model->row('user', "activation_key = '{$activation_key}'");        
        
        //Variables
            $data['activation_key'] = $activation_key;
            $data['activation_type'] = $activation_type;
            $data['row'] = $row_user;
            $data['view_a'] = 'accounts/activation_v';
            
        //Evaluar condiciones
            $conditions = 0;
            if ( ! is_null($row_user) ) { $conditions++; }
            if ( $this->session->userdata('logged') != TRUE ) { $conditions++; }
        
        if ( $conditions == 2 ) 
        {
            $data['head_title'] = "Cuenta de {$row_user->display_name}";
            $this->load->view('templates/bootstrap/start_v', $data);
        } else {
            redirect('app/denied');
        }
    }

    function activate($activation_key)
    {
        $data = array('status' => 0, 'message' => 'Ocurrió un error en la activación');
        $conditions = 0;
        if ( $this->input->post('password') == $this->input->post('passconf') ) { $conditions++; }
        if ( strlen($this->input->post('password')) > 0 ) { $conditions++; }
        
        if ( $conditions == 2 ) 
        {
            $row_user = $this->Account_model->activate($activation_key);

            $this->load->model('Account_model');
            $this->Account_model->create_session($row_user->username, 1);
            
            $data = array('status' => 1, 'message' => 'Usuario activado');
        }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// ACTUALIZACIÓN DE DATOS
//-----------------------------------------------------------------------------

    /**
     * AJAX JSON
     * Se validan los datos del usuario en sesión, los datos deben cumplir varios criterios
     * 
     * @param type $user_id
     */
    function validate($type = 'general')
    {
        $user_id = $this->session->userdata('user_id');

        $this->load->model('Account_model');
        $result = $this->Account_model->validate($user_id, $type);
        
        //Enviar result
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    /**
     * POST JSON
     * Actualiza los datos del usuario en sesión.
     * @param type $user_id
     */
    function update()
    {
        $arr_row = $this->input->post();
        $user_id = $this->session->userdata('user_id');

        $this->load->model('User_model');
        $data = $this->User_model->update($user_id, $arr_row);
        
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }

    /**
     * AJAX JSON
     * Ejecuta el proceso de cambio de contraseña de un usuario en sesión
     */
    function change_password()
    {
        $conditions = 0;
        $row_user = $this->Db_model->row_id('user', $this->session->userdata('user_id'));
        
        //Valores iniciales para el resultado del proceso
            $data = array('status' => 0, 'message' => 'La contraseña no fue modificada. ');
        
        //Verificar contraseña actual
            $validar_pw = $this->Account_model->validate_password($row_user->username, $this->input->post('current_password'));
            if ( $validar_pw['status'] ) {
                $conditions++;
            } else {
                $data['message'] = 'La contraseña actual es incorrecta. ';
            }
        
        //Verificar que contraseña nueva coincida con la confirmación
            if ( $this->input->post('password') == $this->input->post('passconf') ) {
                $conditions++;
            } else {
                $data['message'] .= 'La contraseña de confirmación no coincide.';
            }
        
        //Verificar condiciones necesarias
            if ( $conditions == 2 )
            {
                $this->Account_model->change_password($row_user->id, $this->input->post('password'));
                $data['status'] = 1;
                $data['message'] = 'La contraseña se cambió exitosamente.';
            }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($data));   
    }

//RECUPERACIÓN DE CUENTAS
//---------------------------------------------------------------------------------------------------
    
    /**
     * Formulario para restaurar contraseña o reactivar cuenta
     * se ingresa con nombre de usuario y contraseña
     */
    function recovery()
    {
        if ( $this->session->userdata('logged') )
        {
            redirect('app');
        } else {
            $data['head_title'] = 'Restauración de contraseña';
            $data['view_a'] = 'accounts/recovery_v';
            $this->load->view('templates/remark/start_v', $data);
        }
    }

// ADMINISTRACIÓN DE CUENTA
//-----------------------------------------------------------------------------

    /** Perfil del usuario en sesión */
    function profile()
    {        
        $this->load->model('User_model');
        $data = $this->User_model->basic($this->session->userdata('user_id'));
        
        //Variables específicas
        $data['nav_2'] = 'accounts/menu_v';
        $data['view_a'] = 'accounts/profile_v';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Formulario para la edición de los datos del usuario en sessión. Los datos que se
     * editan dependen de la $section elegida.
     */
    function edit($section = 'basic')
    {
        //Datos básicos
        $user_id = $this->session->userdata('user_id');

        $this->load->model('User_model');
        $data = $this->User_model->basic($user_id);
        
        $view_a = "accounts/edit/{$section}_v";
        if ( $section == 'crop' )
        {
            $view_a = 'files/cropping_v';
            $data['image_id'] = $data['row']->image_id;
            $data['src_image'] = URL_UPLOADS . $data['row']->src_image;
            $data['back_destination'] = "accounts/edit/image";
        }
        
        //Array data espefícicas
            //$data['valores_form'] = $this->Pcrn->valores_form($data['row'], 'user');
            $data['nav_2'] = 'accounts/menu_v';
            $data['nav_3'] = 'accounts/edit/menu_v';
            $data['view_a'] = $view_a;
        
        $this->App_model->view(TPL_ADMIN, $data);
    }

//IMAGEN DE PERFIL
//---------------------------------------------------------------------------------------------------

    /**
     * Carga archivo de imagen, y se la asigna como imagen de perfil al usuario
     * en sesión
     * @param type $user_id
     */
    function set_image()
    {
        $user_id = $this->session->userdata('user_id');

        //Cargue
        $this->load->model('File_model');
        
        $data_upload = $this->File_model->upload();
        
        $data = array('status' => 0, 'message' => 'La imagen no fue asignada');
        if ( $data_upload['status'] )
        {
            $this->load->model('User_model');
            $this->User_model->remove_image($user_id);                              //Quitar image actual, si tiene una
            $data = $this->User_model->set_image($user_id, $data_upload['row']->id);   //Asignar imagen nueva
        }

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }

    /**
     * AJAX
     * Desasigna y elimina la imagen asociada (si la tiene) al usuario en sesión.
     */
    function remove_image()
    {
        $user_id = $this->session->userdata('user_id');

        $this->load->model('User_model');
        $data = $this->User_model->remove_image($user_id);
        
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
// USER LOGIN AND REGISTRATION WITH GOOGLE ACCOUNT
//-----------------------------------------------------------------------------
    
    /**
     * Google Callback, recibe los datos después de solicitar autorización de
     * acceso a cuenta de Google de user.
     */
    function g_callback()
    {
        $g_client = $this->Account_model->g_client();
        
        $cf_redirect = 'accounts/login';
        
        if ( ! is_null($this->session->userdata('access_token')) )
        {
            //access_token existe, set in g_client
            $g_client->setAccessToken($this->session->userdata('access_token'));
        } else if ( $this->input->get('code') ) {
            //Google redirect to URL app/g_callback with GET variable (in URL) called 'code'
            $g_client->authenticate($this->input->get('code')); //Autenticate with this 'code'
            $access_token = $g_client->getAccessToken();        //
            $this->session->set_userdata('access_token', $access_token);
        }
        
        //Get data from the account
            $g_userinfo = $this->Account_model->g_userinfo($g_client);
        
        //Check if email already exists in the BD
            $row_user = $this->Db_model->row('user', "email = '{$g_userinfo['email']}'");

        //Create session or insert new user
            if ( ! is_null($row_user) )
            {
                $this->Account_model->create_session($row_user->username);
                $this->session->set_userdata('src_img', $g_userinfo['picture']);
                $cf_redirect = 'app/logged';
            } else {
                //Do not exists, insert new user
                $this->Account_model->g_register($g_userinfo);
            }
        
        redirect($cf_redirect);
    }
    
    function g_signup()
    {
        redirect('accounts/login');
    }

// FACEBOOK LOGIN
//-----------------------------------------------------------------------------

    function fb_login()
    {
        $this->load->view('accounts/fb_login_v');
    }

// CHECKEO DE USUARIOS PARA REGISTRO
//-----------------------------------------------------------------------------

    /**
     * Registro rápido de usuarios que están realizando una compra
     * 2020-04-30
     */
    function fast_register()
    {
        $data = array('status' => 0);  //Initial result values
        $res_validation = $this->Account_model->validate();
        $recaptcha = $this->App_model->recaptcha();
            
        if ( $res_validation['status'] && $recaptcha->score > 0.5 )
        {
            //Construir registro del nuevo user
                $arr_row['email'] = $this->input->post('email');
                $arr_row['password'] = $this->Account_model->crypt_pw($this->input->post('password'));
                $arr_row['username'] = $arr_row['email'];
                $arr_row['estado'] = 1;

            //Crear
                $this->load->model('Usuario_model');
                $data['saved_id'] = $this->Usuario_model->crear_usuario($arr_row);

                if ( $data['saved_id'] > 0 ) { $data['status'] = 1; }
        } else {
            $data['validation'] = $res_validation['validation'];
        }

        //Si hay un pedido en curso asignar el usuario creado
        if ( ! is_null($this->session->userdata('pedido_id'))  )
        {
            $this->load->model('Pedido_model');
            $this->Pedido_model->set_user($data['saved_id']);
        }

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function check_email()
    {
        $data = $this->Account_model->check_email();

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// LOGIN CON CUENTA DE FACEBOOK
//-----------------------------------------------------------------------------

    function facebook_login()
    {
        if ( $this->session->userdata('user_id') ) {
            redirect('app/logged');
        } else {
            $this->load->view('accounts/page_facebook_login_v');
        }
    }

    /**
     * Recibe por POST Access Token de usuario de facebook, se valida.
     * También recibe por POST datos del usuario de facebook para crear usuario en la base de datos
     * o iniciar sesión si ya existe.
     * 2020-08-14
     */
    function validate_facebook_login()
    {
        $data = array('status' => 0, 'message' => 'Authentication failed');
        $token_validation = $this->Account_model->facebook_validate_token($this->input->post('input_token'));

        if ( $token_validation->is_valid )
        {
            //$data = array('status' => 1, 'message' => 'Authentication was successful');
            $data = $this->Account_model->facebook_set_login();
        }

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}