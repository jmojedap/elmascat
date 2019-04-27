<?php
class Login_model extends CI_Model{
    
    /**
     * Realiza la validación de login, usuario y contraseña. Valida coincidencia
     * de contraseña, y estado del usuario.
     * 
     * @param type $username
     * @param type $password
     * @return int
     */
    function validar_login($username, $password)
    {
        $resultado['ejecutado'] = 0;
        $resultado['mensajes'] = array();
        
        $condiciones = 0;   //Valor inicial
        
        //Validación de password (Condición 1)
            $validar_password = $this->validar_password($username, $password);
            $resultado['mensajes'][] = $validar_password['mensaje'];

            if ( $validar_password['ejecutado'] ) { $condiciones++; }
            
        //Verificar que el usuario esté activo (Condición 2)
            $estado_usuario = $this->estado_usuario($username);
            $resultado['mensajes'][] = $estado_usuario['mensaje'];
            
            if ( $estado_usuario['estado'] == 1 ) { $condiciones++; }   //Usuario activo
            
        //Se valida el login si se cumplen las condiciones
        if ( $condiciones == 2 ) 
        {
            $resultado['ejecutado'] = 1;
        }
            
        return $resultado;
    }
    
    //Verificar si tiene cookie para ser recordado en el equipo
    function login_cookie()
    {
        $this->load->helper('cookie');
        get_cookie('dcsesionrc');
        $recordarme = $this->input->cookie('dcsesionrc');

        $condicion = "cod_activacion = '{$recordarme}'";
        $row_usuario = $this->Pcrn->registro('usuario', $condicion);

        if ( ! is_null($row_usuario) && strlen($recordarme) > 0)
        {
            $this->crear_sesion($row_usuario->username, TRUE);
        }    
    }
    
    /**
     * Array con: valor del campo usuario.estado, y un mensaje explicando 
     * el estado
     * 
     * @param type $username
     * @return string
     */
    function estado_usuario($username)
    {
        $est_usuario['estado'] = 2;     //Valor inicial, 2 => inexistente
        $est_usuario['mensaje'] = 'El usuario "'. $username .'" no existe';
        
        $this->db->where("username = '{$username}' OR email = '{$username}'");
        $query = $this->db->get('usuario');
        
        if ( $query->num_rows() > 0 )
        {
            $est_usuario['estado'] = $query->row()->estado;
            $est_usuario['mensaje'] = NULL;
            
            if ( $est_usuario['estado'] == 0 ) { $est_usuario['mensaje'] = 'El usuario está inactivo, consulte al administrador'; }
        }
        
        return $est_usuario;
        
    }
    
    /**
     * Verificar la contraseña de de un usuario. Verifica que la combinación de
     * usuario y contraseña existan en un mismo registro en la tabla usuario.
     * 
     * @param type $username
     * @param type $password
     * @return boolean
     */
    function validar_password($username, $password)
    {
        //Valor por defecto
            $resultado['ejecutado'] = 0;
            $resultado['mensaje'] = 'El usuario y contraseña no coinciden';
        
        //Buscar usuario con username o correo electrónico
            $condicion = "username = '{$username}' OR email = '{$username}'";
            $row_usuario = $this->Pcrn->registro('usuario', $condicion);
        
        if ( ! is_null($row_usuario) )
        {    
            //Encriptar
                $epw = crypt($password, $row_usuario->password);
                $pw_comparar = $row_usuario->password;
            
            if ( $pw_comparar == $epw )
            {
                $resultado['ejecutado'] = 1;    //Contraseña válida
                $resultado['mensaje'] = 'Contraseña válida';
            }
        }
        
        return $resultado;
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
        
        //Si el usuario solicitó ser recordardo en el equipo
            if ( $this->input->post('recordarme') ) { $this->recordarme(); }
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
                $registro['estado'] = 2;    //Cerrado
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
    
    /**
     * Se ejecuta la función si el usuario activó la casilla "Recordarme" en
     * el formulario de login.
     */
    function recordarme()
    {
        $this->load->helper('string');
        $registro['cod_activacion'] = random_string('alnum', 32);
        
        //Crear cookie por 7 días
            $this->load->helper('cookie');
            set_cookie('dcsesionrc', $registro['cod_activacion'], 7*24*60*60);
        
        //Actualizar en la base de datos
            $this->db->where('id', $this->session->userdata('usuario_id'));
            $this->db->update('usuario', $registro);
    }
    
    function session_data($username)
    {
        $this->load->helper('text');
        $row_usuario = $this->Pcrn->registro('usuario', "username = '{$username}' OR email='{$username}'");

        //$data general
            $data = array(
                'logged' =>   TRUE,
                'nombre_usuario'    =>  $row_usuario->username,
                'nombre_completo'    =>  "{$row_usuario->nombre} {$row_usuario->apellidos}",
                'nombre_corto'    =>  $row_usuario->nombre,
                'usuario_id'    =>  $row_usuario->id,
                'rol_id'    => $row_usuario->rol_id,
                'ultimo_login'    => $row_usuario->ultimo_login,
                'src_img'    => $this->App_model->src_img_usuario($row_usuario, '60px_')
            );
            
        //$data específico
            //Seguridad, utilizada en hooks/acceso
            $data['acl'] = $this->acl($row_usuario);
        
        //Devolver array
            return $data;
    }
    
    function acl($row_usuario)
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