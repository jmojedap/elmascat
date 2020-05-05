<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Admin_model');
        
        //Para formato de horas
        date_default_timezone_set("America/Bogota");

    }
        
    function index()
    {
        redirect('sistema/tablas');
    }
        
//---------------------------------------------------------------------------------------------------
//PANEL DE CONTROL
    
    function sis_opcion()
    {
        $gc_output = $this->Admin_model->crud_sis_opcion();
        
        //Array data espefícicas
        $data['titulo_pagina'] = 'Parámetros';
        $data['subtitulo_pagina'] = 'Opciones generales';
        $data['vista_menu'] = 'sistema/admin/parametros_menu_v';
        $data['vista_a'] = 'comunes/gc_v';
        
        $output = array_merge($data,(array)$gc_output);
        $this->load->view(PTL_ADMIN, $output);
    }
    
    function procesos()
    {
        //Procesos
            $this->db->select('id, nombre_post AS nombre_proceso, contenido, texto_1 AS link_proceso');
            $this->db->where('tipo_id', 10);
            $procesos = $this->db->get('post');
            
        //Variables
            $data['procesos'] = $procesos;
        
        $data['titulo_pagina'] = 'Procesos del sistema';
        $data['vista_a'] = "sistema/admin/procesos_v";
        $this->load->view(PTL_ADMIN, $data);
    }
    
    function tablas($nombre_tabla)
    {
        $gc_output = $this->Admin_model->crud_tabla($nombre_tabla);
        
        //Variables
            $this->load->model('Sincro_model');
            $data['tablas'] = $this->Sincro_model->tablas();
        
        //Solicitar vista
            $data['titulo_pagina'] = 'Tablas: ' . $nombre_tabla;
            $data['nombre_tabla'] = $nombre_tabla;
            $data['vista_a'] = 'sistema/admin/tablas_v';

        $output = array_merge($data,(array)$gc_output);
        $this->load->view(PTL_ADMIN, $output);
    }
    
// EXPORTACIÓN DE DATOS A MS-EXCEL
//-----------------------------------------------------------------------------
    
    function msexcel($nombre_tabla = 'usuario')
    {
        //Variables específicas
            $max_registros = MAX_REG_EXPORT;  //Número máximo de registros a exportar por cada archivo Excel.
            $num_registros = $this->Pcrn->num_registros($nombre_tabla, 'id > 0');  //Todos los registros
            
        //Tablas
            $this->db->select('nombre_tabla');
            $this->db->where('id NOT IN (1110, 4420, 9999)');   //Tablas
            $this->db->order_by('nombre_tabla', 'ASC');
            $tablas = $this->db->get('sis_tabla');
            
        //Cargar data
            $data['nombre_tabla'] = $nombre_tabla;
            $data['max_registros'] = $max_registros;
            $data['num_registros'] = $num_registros;
            $data['tablas'] = $tablas;
            $data['destino_form'] = 'admin/msexcel_e';
            $data['destino_pre'] = "admin/msexcel_e/{$nombre_tabla}/{$max_registros}/";
            

        //Variables generales
            $data['titulo_pagina'] = "Tabla: {$nombre_tabla}";
            $data['subtitulo_pagina'] = "{$num_registros} registros";
            $data['vista_a'] = 'sistema/admin/msexcel_v';
            $data['vista_menu'] = 'sistema/admin/database_menu_v';
            $data['ayuda_id'] = 133;

        $this->load->view(PTL_ADMIN, $data);
    }
    
    function msexcel_e($nombre_tabla, $max_registros, $offset)
    {
        set_time_limit(120);    //120 segundos, 2 minutos para el proceso

        $this->db->order_by('id', 'ASC');
        $query = $this->db->get($nombre_tabla, $max_registros, $offset);
        $parte = 1 + $offset / $max_registros;
        $total_registros = $this->Pcrn->num_registros($nombre_tabla, 'id > 0');
        $total_partes = ceil($total_registros / $max_registros);
        
        //Cargando
            
            //Preparar datos
                $datos['nombre_hoja'] = "{$nombre_tabla}_{$parte}_de_{$total_partes}";
                $datos['query'] = $query;

            //Preparar archivo
                $this->load->model('Pcrn_excel');
                $objWriter = $this->Pcrn_excel->archivo_query($datos);

            $data['objWriter'] = $objWriter;
            $data['nombre_archivo'] = date('Ymd_'). "tabla_{$nombre_tabla}_{$parte}_de_{$total_partes}.xlsx"; //save our workbook as this file name

            $this->load->view('comunes/descargar_phpexcel_v', $data);
    }
    
//CRUD DE TABLA item    
//---------------------------------------------------------------------------------------------------
    
    /**
     * ml > master login
     * Función para el login de administradores ingresando con otro usuario
     * 
     * @param type $usuario_id
     */
    function ml($usuario_id)
    {
        $this->load->model('Login_model');
        $username = $this->Pcrn->campo_id('usuario', $usuario_id, 'username');
        if ( $this->session->userdata('rol_id') <= 1 ) { $this->Login_model->crear_sesion($username, FALSE); }
        
        redirect('app/logged');
    }
    
    /**
     * Inserta en la tabla item, los campos de las tablas de la base de datos
     */
    function campos_item()
    {
        $resultado = $this->Admin_model->act_campos_item();
        
        $this->session->set_flashdata('resultado', $resultado);
        redirect('admin/procesos');
    }
    
    
        
//---------------------------------------------------------------------------------------------------
//CRUD DE ACL
        
    function explorar_acl()
    {
        $this->load->model('Busqueda_model');
        
        //Datos de consulta, construyendo array de búsqueda
            $busqueda = $this->Busqueda_model->busqueda_array();
            $busqueda_str = $this->Busqueda_model->busqueda_str();
            $resultados_total = $this->Busqueda_model->acl_recursos($busqueda); //Para calcular el total de resultados
        
        //Paginación
            $this->load->library('pagination');
            $config = $this->App_model->config_paginacion(3);
            $config['base_url'] = base_url() . "admin/explorar_acl/?{$busqueda_str}";
            $config['total_rows'] = $resultados_total->num_rows();
            $this->pagination->initialize($config);
            
        //Generar resultados para mostrar
            $offset = $this->input->get('per_page');
            $resultados = $this->Busqueda_model->acl_recursos($busqueda, $config['per_page'], $offset);
        
        //Variables para vista
            $data['cant_resultados'] = $config['total_rows'];
            $data['busqueda'] = $busqueda;
            $data['busqueda_str'] = $busqueda_str;
            $data['resultados'] = $resultados;
        
        //Solicitar vista
            $data['titulo_pagina'] = 'ACL Recursos';
            $data['subtitulo_pagina'] = 'Explorar';
            $data['vista_a'] = 'admin/acl_explorar_menu_v';
            $data['vista_b'] = 'admin/acl_explorar_v';
            $this->load->view('p_apanel2/plantilla_v', $data);
    }
    
    function acl($controlador = NULL)
    {
        $gc_output = $this->Admin_model->crud_acl($controlador);
        
        //Variables
            $data['controladores'] = $this->db->get_where('item', 'categoria_id = 32');

        //Solicitar vista
        $data['titulo_pagina'] = 'ACL Permisos de acceso';
        $data['vista_a'] = 'sistema/admin/acl_v';

        $output = array_merge($data,(array)$gc_output);
        $this->load->view(PTL_ADMIN, $output);
    }

        
//---------------------------------------------------------------------------------------------------
//PROCESOS MASIVOS DE DEPURACIÓN O ACTUALIZACIÓN
    
    function eliminar_cascada()
    {
        $this->load->model('Esp');
        $this->Admin_model->eliminar_cascada();
        
        $data['mensaje'] = 'Eliminación en cascada ejecutada';
        $data['titulo_pagina'] = 'Eliminación en cascada';
        $data['vista_a'] = "app/mensaje_v";
        
        $this->load->view('plantilla_apanel/plantilla', $data);
        
    }
    
    function set_ascendencia($categoria_id = 21)
    {
        $this->load->model('Admin_model');
        
        $this->db->where('categoria_id', $categoria_id);
        $query = $this->db->get('item');
        
        foreach ( $query->result() as $row_item ) {
            $this->Admin_model->set_ascendencia($row_item->id);
        }
        
        $data['mensaje'] = 'Actualización de campo ejecutada';
        $data['clase_alert'] = 'success';
        $data['titulo_pagina'] = 'Procesos';
        $data['vista_a'] = 'sistema/admin/procesos_v';
        
        $this->load->view(PTL_ADMIN, $data);
    }
    
//PROCESOS MASIVOS DE DEPURACIÓN O ACTUALIZACIÓN
//---------------------------------------------------------------------------------------------------    

    function gestion_suscriptores()
    {
        $this->db->where('rol_id', 23);
        $suscriptores = $this->db->get('usuario');

        $this->load->model('Usuario_model');
        $this->load->model('Post_model');

        foreach ($suscriptores->result() as $row_usuario)
        {
            echo $row_usuario->apellidos . '<br>';
            $this->Usuario_model->cod_activacion($row_usuario->id);
            $this->Usuario_model->establecer_contrasena($row_usuario->id, $row_usuario->no_documento);
            $this->Post_model->add_to_user(311, $row_usuario->id);
        }
        
    }
        
}