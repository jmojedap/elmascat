<?php

class Pcc {
    
    //Pcc, hace referencia al punto del hook, Post Controller Constructor
    
    function index()
    {
        //Crea instancia para obtener acceso a las librerías de codeigniter, basado en el id
            $this->CI = &get_instance();


            if ( $this->CI->uri->segment(1) == 'api' ) {
                $mcf = 'api/' . $this->CI->uri->segment(2) . '/' . $this->CI->uri->segment(3);
                $allowedFunction = $this->allowedFunction($mcf);    //Permisos de acceso al recurso modulo/controlador/función
            } else {
                //Identificar controlador/función, y allow
                $cf = $this->CI->uri->segment(1) . '/' . $this->CI->uri->segment(2);
                $allowedFunction = $this->allow_cf($cf);    //Permisos de acceso al recurso controlador/función   
            }

        //Verificar allow
            if ( $allowedFunction )
            {
                //$this->qty_unread_messages();     //Actualizar variable de sesión, cant mensajes no leídos
            } else {
                //No tiene allow
                //header('HTTP/1.0 403 Forbidden');
                redirect("app/no_permitido/{$cf}");
                //exit;
            }
        
    }


    
    /**
     * Control de acceso de usuarios basado en el archivo config/acl.php
     * CF > Ruta Controller/Function
     * 2020-12-26
     */
    function allow_cf($cf)
    {
        //Cargando lista de control de acceso, application/config/acl.php
        $this->CI->config->load('acl', TRUE);
        $acl = $this->CI->config->item('acl');

        //Variables
        $role = $this->CI->session->userdata('role');
        $allow_cf = FALSE;
        
        //Verificar en funciones públicas
        if ( in_array($cf, $acl['public_functions']) ) $allow_cf = TRUE;
        
        //Si inició sesión
        if ( $this->CI->session->userdata('logged') == TRUE )
        {
            //Es administrador, todos los permisos
            if ( $role <= 1 ) $allow_cf = TRUE;
            //Funciones para todos los usuarios con sesión iniciada
            if ( in_array($cf, $acl['logged_functions']) ) $allow_cf = TRUE;
        }

        //Funciones para el rol actual
        if ( array_key_exists($cf, $acl['function_roles']) )
        {
            $roles = $acl['function_roles'][$cf];
            if ( in_array($role, $roles) ) $allow_cf = TRUE;
        }

        return $allow_cf;
    }    
    
    /**
     * Control de acceso de usuarios basado en el archivo config/acl.php
     * mcf > Ruta Module/Controller/Function
     * 2025-02-10
     */
    function allowedFunction($mcf)
    {
        //Cargando lista de control de acceso, application/config/acl.php
        $this->CI->config->load('acl', TRUE);
        $acl = $this->CI->config->item('acl');

        //Variables
        $role = $this->CI->session->userdata('role');
        $allowedFunction = FALSE;
        
        //Verificar en funciones públicas
        if ( in_array($mcf, $acl['public_functions']) ) $allowedFunction = TRUE;
        
        //Si inició sesión
        if ( $this->CI->session->userdata('logged') == TRUE )
        {
            //Es administrador, todos los permisos
            if ( $role <= 1 ) $allowedFunction = TRUE;
            //Funciones para todos los usuarios con sesión iniciada
            if ( in_array($mcf, $acl['logged_functions']) ) $allowedFunction = TRUE;
        }

        //Funciones para el rol actual
        if ( array_key_exists($mcf, $acl['function_roles']) )
        {
            $roles = $acl['function_roles'][$mcf];
            if ( in_array($role, $roles) ) $allowedFunction = TRUE;
        }

        return $allowedFunction;
    }
    
    /**
     * Antes de cada acceso, actualiza la variable de sesión de cantidad de mensajes 
     * sin leer
     */
    function qty_unread_messages()
    {
        $this->CI = &get_instance();
            
        //Identificar usuario
            $user_id = $this->CI->session->userdata('user_id');

        //Identificar mensajes sin leer
            $this->CI->db->select('message_user.id');
            $this->CI->db->join('messages', 'messages.id = message_user.message_id');
            $this->CI->db->where('message_user.user_id', $user_id);
            $this->CI->db->where('message_user.status', 0); //Enviado, sin leer
            $messages = $this->CI->db->get('message_user');
    
            $qty_unread_messages =  $messages->num_rows();
        
        //Actualizar variable de sesión
            $this->CI->session->set_userdata('qty_unread_messages', $qty_unread_messages);
    }
    
}