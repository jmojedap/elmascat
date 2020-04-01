<?php

class Pcc {
    
    //Pcc, hace referencia al punto del hook, Post Controller Constructor
    
    function index()
    {
        //Crea instancia para obtener acceso a las librerías de codeigniter, basado en el id
            $this->CI = &get_instance();
        
        //Identificar controlador/función, y permiso
            $cf = $this->CI->uri->segment(1) . '/' . $this->CI->uri->segment(2);
            $permiso_cf = $this->permiso_cf($cf);    //Permisos de acceso al recurso controlador/función
        
        //Verificar permiso
            if ( $permiso_cf )
            {
                //$this->no_leidos();     //Actualizar variable de sesión, cant mensajes no leídos
            } else {
                redirect("app/no_permitido/{$cf}");
            }
    }
    
    /**
     * Control de acceso de usuarios basado en el id de los recursos (sis_acl.id)
     * 
     */
    function permiso_cf($cf)
    {
        //Valor inicial
        $permiso_cf = TRUE;
        
        //Si no es administrador, verificar permiso
        if ( $this->CI->session->userdata('rol_id') > 1 ) 
        {
            $acl = $this->CI->session->userdata('acl');    

            $funciones = $this->CI->db->get_where('sis_acl', "recurso = '{$cf}'");

            $cf_id = 0;
            if ( $funciones->num_rows() > 0 ) { $cf_id = $funciones->row()->id; }

            //Si el controlador/funcion requerido no está entre las funciones permitidas
            if ( ! in_array($cf_id, $acl) ) { $permiso_cf = FALSE; }      
        }
        
        //Si no está logueado
        if ( ! $this->CI->session->userdata('logged') ) { $permiso_cf = FALSE; }
        
        //Si está ingresando a una función pública, se da permiso
        $funciones_publicas = $this->funciones_publicas();
        if ( in_array($cf, $funciones_publicas) ) { $permiso_cf = TRUE; }
        
        return $permiso_cf;
        
    }
    
    /**
     * Array con las funciones (controlador/funcion) a las que se pueden acceder
     * libremente, sin iniciar sesión de usuario.
     * 
     * @return string
     */
    function funciones_publicas()
    {
        $funciones_publicas[] = '/';
        $funciones_publicas[] = 'app/index';
        $funciones_publicas[] = 'app/no_permitido';
        $funciones_publicas[] = 'app/login';
        $funciones_publicas[] = 'app/polo';
        $funciones_publicas[] = 'app/buscar';
        
        $funciones_publicas[] = 'app/registro';
        $funciones_publicas[] = 'app/registrar';
        $funciones_publicas[] = 'app/validar_login';
        $funciones_publicas[] = 'app/logged';
        $funciones_publicas[] = 'app/logout';
        $funciones_publicas[] = 'app/basica';
        $funciones_publicas[] = 'app/pronto';
        $funciones_publicas[] = 'app/crear_usuario';
        
        $funciones_publicas[] = 'busquedas/productos';
        $funciones_publicas[] = 'busquedas/buscar_redirect';
        
        $funciones_publicas[] = 'productos/detalle';
        $funciones_publicas[] = 'productos/visitar';
        $funciones_publicas[] = 'productos/catalogo';
        $funciones_publicas[] = 'productos/catalogo_redirect';
        
        $funciones_publicas[] = 'pedidos/carrito';
        $funciones_publicas[] = 'pedidos/compra_a';
        $funciones_publicas[] = 'pedidos/compra_b';
        $funciones_publicas[] = 'pedidos/compra_b_usd';
        $funciones_publicas[] = 'pedidos/link_pago';
        $funciones_publicas[] = 'pedidos/abandonar';
        $funciones_publicas[] = 'pedidos/respuesta';          //Pagos on line, para usuario
        $funciones_publicas[] = 'pedidos/respuesta_print';    //Pagos on line, para usuario
        $funciones_publicas[] = 'pedidos/confirmacion_pol';   //Pagos on line
        $funciones_publicas[] = 'pedidos/estado';
        $funciones_publicas[] = 'pedidos/soy_distribuidor';
        $funciones_publicas[] = 'pedidos/eliminar_detalle';
        $funciones_publicas[] = 'pedidos/guardar_detalle';  //Ajax
        $funciones_publicas[] = 'pedidos/guardar_pedido';  //Ajax
        $funciones_publicas[] = 'pedidos/guardar_lugar';  //Ajax
        
        $funciones_publicas[] = 'usuarios/registrado';
        $funciones_publicas[] = 'usuarios/activar';
        $funciones_publicas[] = 'usuarios/activar_e';
        $funciones_publicas[] = 'usuarios/recuperar';
        $funciones_publicas[] = 'usuarios/recuperar_e';
        
        $funciones_publicas[] = 'posts/leer';
        
        $funciones_publicas[] = 'sincro/registros_json';
        $funciones_publicas[] = 'sincro/registros_json_id';
        $funciones_publicas[] = 'sincro/json_estado_tablas';
        $funciones_publicas[] = 'sincro/test_ajax';
        $funciones_publicas[] = 'sincro/cant_registros';

        $funciones_publicas[] = 'accounts/signup';
        $funciones_publicas[] = 'accounts/validate_signup';
        $funciones_publicas[] = 'accounts/register';
        $funciones_publicas[] = 'accounts/registered';
        $funciones_publicas[] = 'accounts/fb_login';

        $funciones_publicas[] = 'books/read';

        $funciones_publicas[] = 'info/distribuidores_disponibles';
        
        
        return $funciones_publicas;
    }
    
    /**
     * Antes de cada acceso, actualiza la variable de sesión de cantidad de mensajes 
     * sin leer
     */
    function no_leidos()
    {
        $this->CI = &get_instance();
        
        //Consulta
            $this->CI->db->where('estado', 0);  //No leído
            $this->CI->db->where('usuario_id', $this->CI->session->userdata('usuario_id'));  //No leído
            $mensajes = $this->CI->db->get('mensaje_usuario');
            
        //Establecer valor
            $no_leidos = 0;
            if ( $mensajes->num_rows() > 0 ) { $no_leidos = $mensajes->num_rows(); }
        
        //Actualizar variable de sesión
            $this->CI->session->set_userdata('no_leidos', $no_leidos);
    }
    
}