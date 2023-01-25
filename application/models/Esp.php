<?php

class Esp extends CI_Model{
    
    /* Esp hace referencia a Especial,
     * Colección de funciones especiales para utilizarse específicamente
     * con CodeIgniter en la aplicación del sitio en casos especiales
     * 
     * Districatolicas.com
     */
    
    function __construct(){
        parent::__construct();
        
    }
    
//SESIONES DE USUARIO
//---------------------------------------------------------------------------------------------------------
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
            $validar_password = $this->Esp->validar_password($username, $password);
            $resultado['mensajes'][] = $validar_password['mensaje'];

            if ( $validar_password['ejecutado'] ) { $condiciones++; }
            
        //Verificar que el usuario esté activo (Condición 2)
            $estado_usuario = $this->Esp->estado_usuario($username);
            $resultado['mensajes'][] = $estado_usuario['mensaje'];
            
            if ( $estado_usuario['estado'] == 1 ) { $condiciones++; }   //Usuario activo
            
        //Se valida el login si se cumplen las condiciones
        if ( $condiciones == 2 ) 
        {
            $resultado['ejecutado'] = 1;
        }
            
        return $resultado;
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
        
        //Aplicación Tienda en Línea
            //Si existe un pedido en la sesión, asignarle datos de usuario
            if ( ! is_null($this->session->userdata('pedido_id')) ) 
            {
                $this->load->model('Pedido_model');
                $pedido_id = $this->session->userdata('pedido_id');
                $this->Pedido_model->set_datos_usuario($pedido_id);
                $this->Pedido_model->set_direccion($pedido_id);
            }
        
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
                'row'    => $row_usuario
            );
            
        //$data específico
            //Seguridad, utilizada en hooks/acceso
            $data['funciones_bloqueadas'] = $this->funciones_bloqueadas($row_usuario->rol_id);
            $data['controladores_bloqueados'] = $this->controladores_bloqueados($row_usuario->rol_id);
        
        //Devolver array
            return $data;
        
    }
    
    function controladores_bloqueados($rol_id)
    {
        $this->db->where("roles LIKE  '%-{$rol_id}-%'");
        $this->db->where('tipo_id', 1);     //Controladores
        $query = $this->db->get('sis_acl_recurso');
        
        $controladores_bloqueados = $this->Pcrn->query_to_array($query, 'id', NULL);

        return $controladores_bloqueados;
    }
    
    function funciones_bloqueadas($rol_id)
    {
        $this->db->where("roles LIKE  '%-{$rol_id}-%'");
        $this->db->where('tipo_id', 2);     //Funciones
        $query = $this->db->get('sis_acl_recurso');
        
        $array = $this->Pcrn->query_to_array($query, 'id', NULL);
        
        $funciones_bloqueadas = $array;
        return $funciones_bloqueadas;
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
    
    //Contraseña por defecto
    function pw_default()
    {
        $contrasena = $this->Db_model->field_id('sis_option', 10, 'option_value');
        return md5($contrasena);
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
                'cant_emails' => 'La dirección de correo electrónico ya está registrada. Si ya se registró, recupere su contraseña >> <a href="' . base_url('accounts/recovery') . '">aquí</a> <<'
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
    
    
//---------------------------------------------------------------------------------------------------------
//ARRAYS
    
    function arr_meses()
    {
        $arr_meses = array(
            '01' => 'Enero',
            '02' => 'Febrero',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre',
        );
        
        return $arr_meses;
    }
    
    function arr_si_no($tipo = 'si_no')
    {
        $arrays['si_no'] = array(
            0 => 'No',
            1 => 'Sí'
        );
        
        $arr_si_no = $arrays[$tipo];
        
        return $arr_si_no;
    }
    
    function blacklist()
    {
        $at_emails_no = array(
            '0-mail.com',
            '10minutemail.com',
            '30minutemail.com',
            '4warding.net',
            'amilegit.com',
            'antispam.de',
            'bio-muesli.net',
            'bootybay.de',
            'bugmenot.com',
            'courrieltemporaire.com',
            'cust.in',
            'dayrep.com',
            'devnullmail.com',
            'disposemail.com',
            'dodgit.com',
            'donemail.ru',
            'drdrb.net',
            'e4ward.com',
            'emailinfive.com',
            'emailtemporario.com.br',
            'evopo.com',
            'fastacura.com',
            'fr33mail.info',
            'getonemail.com',
            'great-host.in',
            'guerrillamail.com',
            'haltospam.com',
            'hulapla.de',
            'ieh-mail.de',
            'incognitomail.net',
            'ipoo.org',
            'jetable.org',
            'keepmymail.com',
            'kulturbetrieb.info',
            'lol.ovpn.to',
            'm4ilweb.info',
            'mail4trash.com',
            'mailexpire.com',
            'mailinator.com',
            'mailismagic.com',
            'mailme.lv',
            'mailnesia.com',
            'mailslite.com',
            'mbx.cc',
            'messagebeamer.de',
            'moburl.com',
            'mt2009.com',
            'mytrashmail.com',
            'no-spam.ws',
            'noclickemail.com',
            'nomorespamemails.com',
            'nospamthanks.info',
            'nus.edu.sg',
            'online.ms',
            'owlpic.com',
            'politikerclub.de',
            'qq.com',
            'regbypass.com',
            'rtrtr.com',
            'safetymail.info',
            'saynotospams.com',
            'sharklasers.com',
            'slopsbox.com',
            'sofimail.com',
            'soisz.com',
            'spamavert.com',
            'spambog.com',
            'spambog.ru',
            'spambox.us',
            'spamfree24.com',
            'spamfree24.info',
            'spamgourmet.com',
            'spamify.com',
            'spaml.com',
            'spamobox.com',
            'spamthisplease.com',
            'suremail.info',
            'teleworm.us',
            'tempemail.biz',
            'tempinbox.co.uk',
            'tempmail2.com',
            'temporaryemail.net',
            'thankyou2010.com',
            'tmailinator.com',
            'trash-amil.com',
            'trash2009.com',
            'trashmail.com',
            'trashmailer.com',
            'trbvm.com',
            'uggsrock.com',
            'webm4il.info',
            'whyspam.me',
            'yopmail.com',
            'zippymail.info',
            '0815.ru',
            '20minutemail.com',
            '3d-painting.com',
            '4warding.org',
            'anonbox.net',
            'beefmilk.com',
            'bobmail.info',
            'brefmail.com',
            'bumpymail.com',
            'cubiclink.com',
            'dacoolest.com',
            'deadaddress.com',
            'discardmail.com',
            'dispostable.com',
            'dodgit.org',
            'dontreg.com',
            'dump-email.info',
            'email60.com',
            'emailmiser.com',
            'emailwarden.com',
            'fakeinbox.com',
            'filzmail.com',
            'get1mail.com',
            'getonemail.net',
            'grr.la',
            'guerrillamailblock.com',
            'hochsitze.com',
            'ieatspam.eu',
            'imails.info',
            'incognitomail.org',
            'jetable.com',
            'jnxjn.com',
            'kir.ch.tc',
            'lhsdv.com',
            'lookugly.com',
            'mail-temporaire.fr',
            'mailcatch.com',
            'mailimate.com',
            'mailinator.net',
            'mailmate.com',
            'mailmetrash.com',
            'mailnull.com',
            'mailtemp.info',
            'mciek.com',
            'mierdamail.com',
            'monemail.fr.nf',
            'mypartyclip.de',
            'nepwk.com',
            'no-spammers.com',
            'nogmailspam.info',
            'nospam4.us',
            'notmailinator.com',
            'nwldx.com',
            'opayq.com',
            'pjjkp.com',
            'pookmail.com',
            'quickinbox.com',
            'rmqkr.net',
            'ruffrey.com',
            'safetypost.de',
            'selfdestructingmail.com',
            'shitmail.me',
            'smellfear.com',
            'sofort-mail.de',
            'spam.la',
            'spambob.net',
            'spambog.de',
            'spambox.info',
            'spamcero.com',
            'spamfree24.de',
            'spamfree24.net',
            'spamherelots.com',
            'spaminator.de',
            'spaml.de',
            'spamspot.com',
            'supergreatmail.com',
            'teewars.org',
            'tempalias.com',
            'tempemail.com',
            'tempinbox.com',
            'tempomail.fr',
            'temporaryinbox.com',
            'thisisnotmyrealemail.com',
            'toiea.com',
            'trash-mail.com',
            'trashemail.de',
            'trashmail.net',
            'trashymail.com',
            'trillianpro.com',
            'unmail.ru',
            'wegwerfemail.de',
            'willselfdestruct.com',
            'yuurok.com',
            'zoaxe.com',
            '0clickemail.com',
            '2prong.com',
            '4warding.com',
            '60minutemail.com',
            'anonymbox.com',
            'binkmail.com',
            'bofthew.com',
            'bsnow.net',
            'cosmorph.com',
            'curryworld.de',
            'dandikmail.com',
            'despam.it',
            'discardmail.de',
            'dodgeit.com',
            'doiea.com',
            'dontsendmespam.de',
            'dumpyemail.com',
            'emailigo.de',
            'emailsensei.com',
            'emailx.at.hm',
            'fakeinformation.com',
            'fizmail.com',
            'get2mail.fr',
            'gishpuppy.com',
            'guerillamail.com',
            'h.mintemail.com',
            'hotpop.com',
            'ieatspam.info',
            'incognitomail.com',
            'insorg-mail.info',
            'jetable.net',
            'junk1e.com',
            'klzlk.com',
            'litedrop.com',
            'lopl.co.cc',
            'mail.by',
            'maileater.com',
            'mailin8r.com',
            'mailinator2.com',
            'mailme.ir',
            'mailnator.com',
            'mailsac.com',
            'mailzilla.org',
            'meltmail.com',
            'mintemail.com',
            'msa.minsmail.com',
            'myphantomemail.com',
            'nincsmail.hu',
            'nobulk.com',
            'nomail2me.com',
            'nospamfor.us',
            'nowmymail.com',
            'onewaymail.com',
            'ovpn.to',
            'plexolan.de',
            'prtnx.com',
            'recode.me',
            'rppkn.com',
            's0ny.net',
            'sandelf.de',
            'sendspamhere.com',
            'skeefmail.com',
            'snakemail.com',
            'sogetthis.com',
            'spam.su',
            'spambob.org',
            'spambog.net',
            'spambox.irishspringrealty.com',
            'spamday.com',
            'spamfree24.eu',
            'spamfree24.org',
            'spamhole.com',
            'spamkill.info',
            'spammotel.com',
            'spamthis.co.uk',
            'supermailer.jp',
            'teleworm.com',
            'tempe-mail.com',
            'tempemail.net',
            'tempmail.it',
            'temporarioemail.com.br',
            'thanksnospam.info',
            'throwawayemailaddress.com',
            'tradermail.info',
            'trash-mail.de',
            'trashmail.at',
            'trashmail.ws',
            'trashymail.net',
            'tyldd.com',
            'veryrealemail.com',
            'wh4f.org',
            'wuzupmail.net',
            'zehnminutenmail.de',
            'dodsi.com',
            'guerrillamail.biz',
            'guerrillamail',
            'spam4.me',
            'tafmail.com',
            'boximail.com',
            'imgof.com',
            'abyssmail.com',
            'emailtemporalgratis.com',
            'armyspy.com',
            'cuvox.de',
            'einrot.com'
        );
        
        return $at_emails_no;
    }
    
//---------------------------------------------------------------------------------------------------------
//GENERAL
    
    /**
     * Array con el resultado por defecto de un proceso, valor inicial
     * 
     * @return string
     */
    function res_inicial($mensaje = 'El proceso no fue ejecutado')
    {
        $resultado['ejecutado'] = 0;
        $resultado['mensaje'] = $mensaje;
        $resultado['clase'] = 'alert-danger';
        $resultado['icono'] = 'fa-times';
        
        return $resultado;
    }
    
    function max_query($query, $campo)
    {
        $max = 0;
        foreach ( $query->result() as $row )
        {
            $pruebas = 0;
            if ( $row->$campo > $max   ) { $pruebas += 1; }
            if ( ! is_null($row->$campo) ) { $pruebas += 1; }
            
            if ( $pruebas == 2 ) { $max = $row->$campo; }
        }
        
        return $max;
    }
}