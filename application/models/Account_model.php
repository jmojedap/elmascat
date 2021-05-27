<?php
class Account_model extends CI_Model{
    
    
    /**
     * Realiza la validación de login, user y password. Valida coincidencia
     * de password, y status del user.
     * 
     * @param type $userlogin
     * @param type $password
     * @return int
     */
    function validate_login($userlogin, $password)
    {
        $data = array('status' => 0, 'messages' => array());
        $conditions = 0;   //Initial value
        
        //Validation de password (Condición 1)
            $password_validation = $this->validate_password($userlogin, $password);
            $data['messages'][] = $password_validation['message'];

            if ( $password_validation['status'] ) { $conditions++; }
            
        //Verificar que el user esté activo (Condición 2)
            $user_status = $this->user_status($userlogin);
            if ( $user_status['status'] != 1 ) { $data['messages'][] = $user_status['message']; }
            
            if ( $user_status['status'] == 1 ) { $conditions++; }   //Usuario activo
            
        //Se valida el login si se cumplen las conditions
        if ( $conditions == 2 ) 
        {
            $data['status'] = 1;    //General login validation
        }
            
        return $data;
    }    

    //Check if the user has cookie to be remembered in his browser
    function login_cookie()
    {
        $rememberme = $this->input->cookie('discatak');

        $condition = "activation_key = '{$rememberme}'";
        $row_user = $this->Db_model->row('usuario', $condition);

        if ( ! is_null($row_user) && strlen($rememberme) > 0)
        {
            $this->create_sesion($row_user->username, TRUE);
        }    
    }

    /**
     * Crear cookie para recordar usuario en el equipo
     * discatak: districatolicas activation key
     */
    function rememberme($username)
    {
        $row_user = $this->Db_model->row('usuario', "username = '{$username}' OR email='{$username}' OR no_documento='{$username}'");

        $data = array('status' => 0, 'dcak' => '');

        if ( is_null($row_user) )
        {
            $this->load->helper('cookie');
            set_cookie('discatak', $row_user->cod_activacion);
            $data = array('status' => 1, 'dcak' => $row_user->cod_activacion);
        }

        return $row_user->cod_activacion;
    }

    /**
     * Guardar evento final de sesión, eliminar cookie y destruir sesión
     */
    function logout()
    {
        //Editar, evento de inicio de sesión
        if ( strlen($this->session->userdata('login_id')) > 0 ) 
        {
            $row_event = $this->Db_model->row_id('evento', $this->session->userdata('login_id'));

            $arr_row['fin'] = date('Y-m-d H:i:s');
            $arr_row['estado'] = 2;    //Cerrado
            $arr_row['segundos'] = $this->pml->seconds($row_event->start, date('Y-m-d H:i:s'));

            if ( ! is_null($row_event) ) 
            {
                //Si el evento existe
                $this->Db_model->save('evento', "id = {$row_event->id}", $arr_row);
            }
        }
    
    //Eliminar cookie
        $this->load->helper('cookie');
        delete_cookie('districatolicas_session');
        
    //Destruir sesión existente
        $this->session->sess_destroy();
    }
    
// SESSION CONSTRUCT
//-----------------------------------------------------------------------------
    
    function create_session($username, $register_login = TRUE)
    {
        $data = $this->session_data($username);
        $this->session->set_userdata($data);

        //Registrar evento de login en la tabla [evento]
        if ( $register_login )
        {
            $this->load->model('Event_model');
            $this->Event_model->save_ev_login();
        }
        
        //Actualizar user.ultimo_login
            $this->update_last_login($username);
        
        //Si el user solicitó ser recordardo en el equipo
            if ( $this->input->post('rememberme') ) { $this->rememberme($username); }
    }
    
    /**
     * Actualiza el campo user.last_login, con la última fecha en la que el usuario
     * hizo login
     */
    function update_last_login($username)
    {
        $arr_row['last_login'] = date('Y-m-d H:i:s');

        $this->db->where('username', $username);
        $this->db->update('usuario', $arr_row);
    }

    /**
     * Array con datos de sesión.
     * 2019-06-23
     */
    function session_data($username)
    {
        $this->load->helper('text');
        $row_user = $this->Db_model->row('usuario', "username = '{$username}' OR email='{$username}' OR no_documento='{$username}'");

        //$data general
            $data = array(
                'logged' =>   TRUE,
                'username'    =>  $row_user->username,
                'display_name'    =>  $row_user->display_name,
                'first_name'    =>  $row_user->nombre,
                'user_id'    =>  $row_user->id,
                'role'    => $row_user->rol_id,
                'role_abbr'    => $this->Db_model->field('item', "categoria_id = 58 AND id_interno = {$row_user->rol_id}", 'abreviatura'),
                /*'last_login'    => $row_user->last_login,*/
                /*'src_img'    => $this->App_model->src_img_user($row_user, 'sm_'),*/
                /*'acl' => $this->acl($row_user)   //Listado de permisos*/
            );
                
        //Datos específicos para la aplicación
            $app_session_data = $this->App_model->app_session_data($row_user);
            $data = array_merge($data, $app_session_data);
        
        //Devolver array
            return $data;
    }
    
    //Array con los ID de las funciones permitidas para el usuario
    function acl($row_user)
    {
        $this->db->where("roles LIKE  '%-{$row_user->role}-%'");
        $query = $this->db->get('sis_acl');
        
        $allowed_functions = $this->pml->query_to_array($query, 'id', NULL);
        
        return $allowed_functions;
    }

// REGISTER VALIDATION
//-----------------------------------------------------------------------------

    /**
     * Valida datos de un user nuevo o existente, verificando validez respecto
     * a users ya existentes en la base de datos.
     */
    function validate($user_id = NULL)
    {
        $data = array('status' => 1);   //Valor inicial

        $this->load->model('Validation_model');
        $val_email = $this->Validation_model->email($user_id);
        
        $validation = array_merge($val_email);

        //Comprobar cada elemento
        foreach ( $validation as $value )
        {
            if ( $value == FALSE ) { $data = array('status' => 0); }
        }

        $data['validation'] = $validation;

        return $data;
    }

    /* Esta función genera un string con el username para un registro en la tabla user
    * Se forma: la primera letra del primer nombre + la primera letra del segundo nombre +
    * el primer apellido + la primera letra del segundo apellido.
    * Se verifica que el username construido no exista
    */
    function generate_username()
    {
        
        $this->load->model('User_model');
        
        //Sin espacios iniciales o finales
        $first_name = trim($this->input->post('first_name'));
        $last_name = trim($this->input->post('last_name'));
        
        //Without accents
        $this->load->helper('text');
        $first_name = convert_accented_characters($first_name);
        $last_name = convert_accented_characters($last_name);
        
        $arr_last_name = explode(" ", $last_name);
        $arr_first_name =  explode(" ", $first_name);
        
        //Construyendo por partes
            $username = $arr_first_name[0];
            if ( isset($arr_first_name[1]) ){ $username .= substr($arr_first_name[1], 0, 2); }
            
            $username .= '.' . $arr_last_name[0];
            
            if ( isset($arr_last_name[1]) ){
                $username .= substr($arr_last_name[1], 0, 2);
            }    
        
        //Reemplazando caracteres
            $username = str_replace (' ', '', $username); //Quitando espacios en blanco
            $username = strtolower($username); //Se convierte a minúsculas    
        
        //Verificar, si el username requiere un sufix numérico para hacerlo único
            $sufix = $this->username_sufix($username);
            $username .= $sufix;
        
        return $username;
    }

    /**
     * Set an activation key for user account recovery
     * 
     * @param type $user_id
     */
    function activation_key($user_id)
    {
        $this->load->helper('string');
        $arr_row['cod_activacion'] = strtolower(random_string('alpha', 12));
        
        $this->db->where('id', $user_id);
        $this->db->update('usuario', $arr_row);

        return $arr_row['cod_activacion'];
    }

    function activate($activation_key)
    {
        $row_user = $this->Db_model->row('usuario', "activation_key = '{$activation_key}'");
        
        //Row user
            $arr_row['status'] = 1;
            $arr_row['password'] = $this->crypt_pw($this->input->post('password'));

        //Update
            $this->db->where('id', $row_user->id);
            $this->db->update('usuario', $arr_row);
            
        return $row_user;
    }

// PASSWORDS
//---------------------------------------------------------------------------------------------------

    //Encripta y cambia la contraseña de un usuario
    function change_password($user_id, $password)
    {
        $arr_row = array(
            'password'  => $this->crypt_pw($password)
        );
        
        $this->db->where('id', $user_id);
        $this->db->update('usuario', $arr_row);
    }

    /**
     * Verificar la contraseña de de un user. Verifica que la combinación de
     * user y contraseña existan en un mismo registro en la tabla user.
     * 
     * @param type $userlogin
     * @param type $password
     * @return boolean
     */
    function validate_password($userlogin, $password)
    {
        //Valor por defecto
            $data['status'] = 0;
            $data['message'] = 'Contraseña no válida para el user "'. $userlogin .'"' ;
         
        //Buscar user con username o correo electrónico
            $condition = "username = '{$userlogin}' OR email = '{$userlogin}'";
            $row_user = $this->Db_model->row('usuario', $condition);
        
        if ( ! is_null($row_user) )
        {    
            //Crypting
                $cpw = crypt($password, $row_user->password);
                $pw_compare = $row_user->password;
            
            if ( $pw_compare == $cpw )
            {
                $data['status'] = 1;    //Contraseña válida
                $data['message'] = 'Contraseña válida para el user';
            }
        }
        
        return $data;
    }

    /**
     * Devuelve password encriptado
     * 
     * @param type $input
     * @param type $rounds
     * @return type
     */
    function crypt_pw($input, $rounds = 7)
    {
        $salt = '';
        $salt_chars = array_merge(range('A','Z'), range('a','z'), range(0,9));
        for($i=0; $i < 22; $i++) {
          $salt .= $salt_chars[array_rand($salt_chars)];
        }
        
        return crypt($input, sprintf('$2a$%02d$', $rounds) . $salt);
    }

    /**
     * Array con: valor del campo user.estado, y un mensaje explicando 
     * el estado
     * 
     * @param type $userlogin
     * @return string
     */
    function user_status($userlogin)
    {
        $data['status'] = 2;     //Valor inicial, 2 => inexistente
        $data['message'] = 'No existe un user identificado con "'. $userlogin .'"';
        
        $this->db->where("username = '{$userlogin}' OR email = '{$userlogin}'");
        $query = $this->db->get('usuario');
        
        if ( $query->num_rows() > 0 )
        {
            $data['status'] = $query->row()->estado;
            $data['message'] = 'Usuario activo';
            
            if ( $data['status'] == 0 ) { $data['message'] = "El usuario '{$userlogin}' está inactivo, comuníquese con servicio al cliente"; }
        }
        
        return $data;
        
    }

// ACTIVATION AND ACCOUNT RECOVERY
//---------------------------------------------------------------------------------------------------

    /**
     * Envía un email de para restauración de la contraseña de user
     * 
     * @param type $email
     * @return int
     */
    function recover($email)
    {
        $data = array('status' => 0, 'message' => 'El proceso no fue ejecutado');
        
        //Identificar user
        $row_user = $this->Db_model->row('usuario', "email = '{$email}'");

        if ( ! is_null($row_user) ) 
        {
            //$this->email_activation($row_user->id, 'recovery');
            $data = array('status' => 1, 'message' => 'El mensaje de correo electrónico fue enviado');
        } else {
            $data['status'] = 2;    //Usuario inexistente
            $data['message'] = "No existe ningún user con el correo electrónico: '{$email}'";
        }
        
        return $data;
    }

    /**
     * Envía e-mail de activación o restauración de cuenta
     * 
     * @param type $user_id
     * @param type $activation_type
     */
    function email_activation($user_id, $activation_type = 'activation')
    {
        $row_user = $this->Db_model->row_id('usuario', $user_id);
        
        //Establecer código de activación
            $this->activation_key($user_id);
            
        //Asunto de mensaje
            $subject = APP_NAME . ': Activar cuenta';
            if ( $activation_type == 'recovery' ) {
                $subject = APP_NAME . ' Restaurar contraseña';
            }
        
        //Enviar Email
            $this->load->library('email');
            $config['mailtype'] = 'html';

            $this->email->initialize($config);
            $this->email->from('accounts@' . APP_DOMAIN, APP_NAME);
            $this->email->to($row_user->email);
            $this->email->bcc('jmojedap@gmail.com');
            $this->email->message($this->activation_message($user_id, $activation_type));
            $this->email->subject($subject);
            
            $this->email->send();   //Enviar
    }

    /**
     * Devuelve texto de la vista que se envía por email a un usuario para activación o restauración de su cuenta
     * 
     * @param type $user_id
     * @param type $activation_type
     * @return type
     */
    function activation_message($user_id, $activation_type)
    {
        $row_user = $this->Db_model->row_id('usuario', $user_id);
        $data['row_user'] = $row_user ;
        $data['activation_type'] = $activation_type;
        
        $message = $this->load->view('accounts/email_activation_v', $data, TRUE);
        
        return $message;
    }
    
// LOGIN AND REGISTRATION WITH GOOGLE ACCOUNT
//-----------------------------------------------------------------------------
    
    /**
     * Prepara un objeto Google_Client, para solicitar la autorización  de una
     * autenticación de un user de google y obtener información de su cuenta
     * 
     * Credenciales de Cliente para la aplicación Legalink, creadas con 
     * la cuenta google pacarinamedialab@gmail.com
     * 
     * @return \Google_Client
     */
    public function g_client()
    {
        //require 'vendor/autoload.php';

        $g_client = new Google_Client();
        $g_client->setClientId(K_GCI);
        $g_client->setClientSecret(K_GCS);
        $g_client->setApplicationName(APP_NAME);
        $g_client->setRedirectUri(base_url() . 'accounts/g_callback');
        $g_client->setScopes('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email');
        
        return $g_client;
    }
    
    /**
     * Teniendo como entrada el objeto Google_Cliente autorizado, se solicita
     * y se obtiene la información de la cuenta de un user mediante 
     * Google_Service_Oauth2 y se carga en el array g_userinfo
     * 
     * @param type $g_client
     * @return type
     */
    public function g_userinfo($g_client)
    {
        $oAuth = new Google_Service_Oauth2($g_client);
        $g_userinfo = $oAuth->userinfo_v2_me->get();
        
        return $g_userinfo;
    }

    function g_register($g_userinfo)
    {
        //Construir registro del nuevo user
            $arr_row['first_name'] = $g_userinfo['given_name'];
            $arr_row['last_name'] = $g_userinfo['family_name'];
            $arr_row['display_name'] = $g_userinfo['given_name'] . ' ' . $g_userinfo['family_name'];
            $arr_row['username'] = str_replace('@gmail.com', '', $g_userinfo['email']);
            $arr_row['email'] = $g_userinfo['email'];
            $arr_row['role'] = 31;         //31 Registrado
            $arr_row['status'] = 1;        //Activo

        //Insert in table "user"
            $this->load->model('User_model');
            $data = $this->User_model->insert($arr_row);

        //Create user session
            if ( $data['user_id'] > 0 )
            {
                $this->create_session($arr_row['username']);
                //$this->Account_model->g_save_account($result['user_id']);
            }

        return $data;
    }

// LOGIN Y REGISTRO CON CUENTA DE USUARIO DE FACEBOOK
//-----------------------------------------------------------------------------

    /**
     * Valida si un user access token recibido es válido, se verifica teniendo en cuenta
     * un app_access_token generado inicialmente.
     * 2020-08-14
     */
    function facebook_validate_token($input_token)
    {
        $app_access_token = $this->facebook_get_app_access_token();
        $url = "https://graph.facebook.com/debug_token?input_token={$input_token}&access_token={$app_access_token}";    

        $content = $this->pml->get_url_content($url);
        $token_validation = json_decode($content);

        return $token_validation->data;
    }

    /**
     * String, Genera un app_access_token de la Aplicación de Facebook asociada esta WebApp
     * 2020-08-14
     */
    function facebook_get_app_access_token()
    {
        $client_id = K_FBAI;        //Ver config/constants.php
        $client_secret = K_FBAK;    //Ver config/constants.php
        $url = "https://graph.facebook.com/oauth/access_token?client_id={$client_id}&client_secret={$client_secret}&grant_type=client_credentials";

        $str_content = $this->pml->get_url_content($url);   //Recibe JSON
        $content = json_decode($str_content);               //Se convierte en un objeto 

        //devuelve solo el string de access_token
        return $content->access_token;
    }

    /**
     * Tras validar el token de usuario de facebook, se realiza el login de usuario con esa cuenta
     * Se verifica si el email, ya está registrado, y se inicia sesión.
     * Si no existe, se crea usuario con los datos recibidos y se inicia sesión.
     * 2020-08-14
     */
    function facebook_set_login()
    {
        $data['status'] = 0;    //Resultado inicial por defecto        

        //Verificar si existe usuario con ese e-mail
        $row_user = $this->Db_model->row('usuario', "email = '{$this->input->post('email')}'");

        //Create session or insert new user
            if ( ! is_null($row_user) )
            {
                $data['status'] = 1;
                $data['message'] = 'Ya estás registrado: ' . $row_user->nombre;
                $this->load->model('Login_model');
                $this->Login_model->crear_sesion($row_user->username, TRUE);
                //$this->create_session($row_user->username);
            } else {
                //Do not exists, insert new user
                $data['status'] = 1;
                $data['message'] = 'No estás registrado, crearemos tu cuenta';
                //$this->Account_model->facebook_register();
            }
        
        return $data;
    }

    function facebook_register()
    {
        //Construir registro del nuevo user
            $arr_row['first_name'] = $this->input->post('first_name');
            $arr_row['last_name'] = $this->input->post('last_name');
            $arr_row['display_name'] = $arr_row['first_name'] . ' ' . $arr_row['last_name'];
            //$arr_row['username'] = str_replace('@gmail.com', '', $g_userinfo['email']);
            $arr_row['email'] = $this->input->post('email');
            $arr_row['role'] = 31;         //31 Registrado
            $arr_row['status'] = 1;        //Activo

        //Insert in table "user"
            $this->load->model('User_model');
            $data = $this->User_model->insert($arr_row);

        //Create user session
            if ( $data['user_id'] > 0 )
            {
                $this->create_session($arr_row['username']);
                //$this->Account_model->g_save_account($result['user_id']);
            }

        return $data;
    }

// Checkeo de usuarios
//-----------------------------------------------------------------------------

    function check_email()
    {
        $data = array('status' => 0, 'usuario' => array());

        $row = $this->Db_model->row('usuario', "email = '{$this->input->post('email')}'");

        if ( ! is_null($row)  )
        {
            $data['status'] = 1;
            $user['id'] = $row->id;
            $user['display_name'] = $row->display_name;

            $data['user'] = $user;
        }

        return $data;
    }
}