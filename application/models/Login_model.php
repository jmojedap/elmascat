<?php
class Login_model extends CI_Model{

    /**
     * Realiza la validación de login, usuario y contraseña. Valida coincidencia
     * de contraseña, y estado del usuario.
     */
    function validate($username, $password)
    {
        $data['status'] = 0;
        $data['messages'] = array();
        
        $conditions = 0;   //Valor inicial
        
        //Validación de password (Condición 1)
            $validate_password = $this->validate_password($username, $password);
            $data['messages'][] = $validate_password['message'];

            if ( $validate_password['status'] ) { $conditions++; }
            
        //Verificar que el usuario esté activo (Condición 2)
            $user_status = $this->user_status($username);
            $data['messages'][] = $user_status['message'];
            
            if ( $user_status['status'] == 1 ) { $conditions++; }   //Usuario activo
            
        //Se valida el login si se cumplen las condiciones
            if ( $conditions == 2 )  { $data['status'] = 1; }
            
        return $data;
    }
    
    /**
     * Verificar si tiene cookie para ser recordado en el equipo
     * e iniciar sesión automáticamente
     * 2020-05-05
     */
    function login_cookie()
    {
        $this->load->helper('cookie');
        $activation_key = get_cookie('rm');

        $condition = "cod_activacion = '{$activation_key}'";
        $row_user = $this->Db_model->row('usuario', $condition);

        if ( ! is_null($row_user) && strlen($activation_key) > 0)
        {
            $this->crear_sesion($row_user->username, TRUE);
        }

        return $activation_key;
    }

    /**
     * Crear cookie para recordar usuario en el equipo
     * rm: rememberme
     * 2020-05-05
     */
    function rememberme()
    {
        $this->load->helper('string');
        $arr_row['cod_activacion'] = 'rm' . strtolower(random_string('alpha', 30));
        
        //Crear cookie por 7 días
            $this->load->helper('cookie');
            set_cookie('rm', $arr_row['cod_activacion'], 7*24*60*60);
        
        //Actualizar en la base de datos
            $this->db->where('id', $this->session->userdata('user_id'));
            $this->db->update('usuario', $arr_row);
    }
    
    /**
     * Array con: valor del campo usuario.estado, y un mensaje explicando 
     * el estado
     * 
     */
    function user_status($username)
    {
        $user_status['status'] = 2;     //Valor inicial, 2 => inexistente
        $user_status['message'] = 'El usuario "'. $username .'" no existe';
        
        $this->db->where("username = '{$username}' OR email = '{$username}'");
        $query = $this->db->get('usuario');
        
        if ( $query->num_rows() > 0 )
        {
            $user_status['status'] = $query->row()->estado;
            $user_status['message'] = 'Usuario activo';
            
            if ( $user_status['status'] == 0 ) { $user_status['message'] = 'El usuario está inactivo, consulte al administrador'; }
        }
        
        return $user_status;
        
    }
    
    /**
     * Verificar la contraseña de de un usuario. Verifica que la combinación de
     * usuario y contraseña existan en un mismo registro en la tabla usuario.
     * 
     * @param type $username
     * @param type $password
     * @return boolean
     */
    function validate_password($username, $password)
    {
        //Valor por defecto
            $data['status'] = 0;
            $data['message'] = 'El usuario y contraseña no coinciden';
        
        //Buscar usuario con username o correo electrónico
            $condition = "username = '{$username}' OR email = '{$username}'";
            $row_user = $this->Pcrn->registro('usuario', $condition);
        
        if ( ! is_null($row_user) )
        {    
            //Encriptar
                $epw = crypt($password, $row_user->password);
                $pw_compare = $row_user->password;
            
            if ( $pw_compare == $epw )
            {
                $data['status'] = 1;    //Contraseña válida
                $data['message'] = 'Contraseña válida';
            }
        }
        
        return $data;
    }
    
    function crear_sesion($username, $registrar_login)
    {
        $data = $this->session_data($username);
        $this->session->set_userdata($data);

        //Registrar evento de login en la tabla [evento]
        if ( $registrar_login )
        {
            $this->load->model('Evento_model');
            $this->Evento_model->guardar_ev_login();
        }
        
        //Actualizar usuario.ultimo_login
            $this->act_ultimo_login($username);
    }
    
    /**
     * Después de iniciar sesión, se edita el campo usuario.ultimo_login para
     * registrar fecha y hora del login más reciente que realizó el usuario.
     * 
     * @param type $username
     */
    function act_ultimo_login($username)
    {
        $registro['ultimo_login'] = date('Y-m-d H:i:s');
        $this->db->where('username', $username);
        $this->db->update('usuario', $registro);
    }
    
    /**
     * Guardar evento final de sesión, eliminar cookie y destruir sesión
     */
    function logout()
    {
        //Editar, evento de inicio de sesión
            if ( strlen($this->session->userdata('login_id')) > 0 ) 
            {
                $row_evento = $this->Pcrn->registro_id('evento', $this->session->userdata('login_id'));

                $registro['fin'] = date('Y-m-d H:i:s');
                $registro['status'] = 2;    //Cerrado
                $registro['segundos'] = $this->Pcrn->segundos_lapso($row_evento->creado, date('Y-m-d H:i:s'));

                if ( ! is_null($row_evento) ) 
                {
                    //Si el evento existe
                    $this->Pcrn->guardar('evento', "id = {$row_evento->id}", $registro);
                }
            }
        
        //Eliminar cookie
            $this->load->helper('cookie');
            delete_cookie('dksesionrc');
            
        //Destruir sesión existente
            $this->session->sess_destroy();
    }
    
    function session_data($username)
    {
        $this->load->helper('text');
        $row_user = $this->Db_model->row('usuario', "username = '{$username}' OR email='{$username}'");

        //$data general
            $data = array(
                'logged' =>   TRUE,
                'nombre_usuario'    =>  $row_user->username,
                'nombre_completo'    =>  "{$row_user->nombre} {$row_user->apellidos}",
                'nombre_corto'    =>  $row_user->nombre,
                'usuario_id'    =>  $row_user->id,
                'rol_id'    => $row_user->rol_id,
                'ultimo_login'    => $row_user->ultimo_login,
                'src_img'    => $this->App_model->src_img_usuario($row_user, '60px_'),
                'username'    =>  $row_user->username,
                'display_name'    =>  $row_user->display_name,
                'first_name'    =>  $row_user->nombre,
                'user_id'    =>  $row_user->id,
                'role'    => $row_user->rol_id,
                'role_abbr'    => $this->Db_model->field('item', "categoria_id = 58 AND id_interno = {$row_user->rol_id}", 'abreviatura')
            );
            
        //$data específico
            //Seguridad, utilizada en hooks/acceso
            //$data['acl'] = $this->acl($row_user);
        
        //Devolver array
            return $data;
    }
    
    /**
     * Función desactivada
     */
    function z_acl($row_usuario)
    {
        $this->db->where("roles LIKE  '%-{$row_usuario->rol_id}-%'");
        $query = $this->db->get('sis_acl');
        
        $array = $this->Pcrn->query_to_array($query, 'id', NULL);
        
        $funciones_permitidas = $array;
        return $funciones_permitidas;
    }
    
    /**
     * Determina mediante el username si un usuario es administrador o no
     * @param type $username
     * @return boolean
     */
    function es_admin($username)
    {
        $es_admin = FALSE;
        $rol_usuario = $this->Pcrn->campo('usuario', "username = '{$username}'", 'rol_id');
        
        if ( $rol_usuario <= 1 ) {
            $es_admin = TRUE;
        }
        
        return $es_admin;
    }
    
    /**
     * Devuelve array $recaptcha, con el resultado de la validación formularios
     * utilizando la herramienta reCaptcha
     * 
     * @return type
     */
    function recaptcha()
    {
        $recaptcha = array(
            'success' => FALSE,
            'challenge_ts' => NULL,
            'hostname' => '',
        );
        
        if ( ! is_null($this->input->post('g-recaptcha-response')) )
        {
            $secret = '6LfC3TQUAAAAAK4qRUzs_AAVwiyIc09eNgFcDvdL';
            $response = $this->input->post('g-recaptcha-response');
            $remoteip = $this->input->ip_address();
            
            $get = "response={$response}&secret={$secret}&remoteip={$remoteip}";
            ini_set("allow_url_fopen", 1);  //2017-07-10, prueba
            $json_recaptcha = file_get_contents("https://www.google.com/recaptcha/api/siteverify?{$get}");
            $recaptcha = json_decode($json_recaptcha, TRUE);
        }
        
        return $recaptcha;
    }
    
    /**
     * Validación del formulario de registro de un usuario en el sitio
     * 
     * @return type
     */
    function validar_registro()
    {
        //Valores iniciales, resultado por defecto
            $resultado['ejecutado'] = 0;
            $resultado['mensajes'] = array(
                'recaptcha' => 'Para registrarse debe activar la casilla de verificación "No soy un robot"',
                'cant_emails' => 'La dirección de correo electrónico ya está registrada. Si ya se registró, recupere su contraseña >> <a href="' . base_url('usuarios/recuperar') . '">aquí</a> <<'
            );
            $cant_condiciones = 0;
        
        //Condición 1. Aprobar el reCaptcha
            $recaptcha = $this->recaptcha();   //Validar formulario con la herramienta reCaptcha
            if ( $recaptcha['success'] ) 
            {
                $cant_condiciones++;
                unset($resultado['mensajes']['recaptcha']);
            }
        
        //Condición 2. E-mail único
            $cant_emails = $this->Pcrn->num_registros('usuario', "email = '{$this->input->post('email')}'");
            if ( $cant_emails == 0 ) 
            {
                $cant_condiciones++;
                unset($resultado['mensajes']['cant_emails']);
            }
            
        //Verificación de condiciones cumplidas
            if ( $cant_condiciones == 2 ) { $resultado['ejecutado'] = 1; }
            
        return $resultado;
    }
}