<?php
class Evento_Model extends CI_Model{
    
    function basico($evento_id)
    {
        $row_evento = $this->Pcrn->registro_id('evento', $evento_id);
        
        $basico['evento_id'] = $evento_id;
        $basico['row'] = $row_evento;
        $basico['titulo_pagina'] = $row_evento->nombre_evento;
        $basico['vista_a'] = 'eventos/evento_v';
        
        return $basico;
    }
    
    function buscar($busqueda, $per_page = NULL, $offset = NULL)
    {
        
        //$filtro_rol = $this->filtro_usuarios($this->session->userdata('usuario_id'));

        //Construir búsqueda
        //Crear array con términos de búsqueda
            if ( strlen($busqueda['q']) > 2 )
            {
                $palabras = $this->Busqueda_model->palabras($busqueda['q']);

                foreach ($palabras as $palabra_busqueda)
                {
                    $concat_campos = $this->Busqueda_model->concat_campos(array('nombre_evento', 'descripcion', 'url'));
                    $this->db->like("CONCAT({$concat_campos})", $palabra_busqueda);
                }
            }
        
        //Especificaciones de consulta
            //$this->db->where($filtro_rol); //Filtro según el rol de usuario que se tenga
            $this->db->order_by('creado', 'DESC');
            
        //Otros filtros
            //if ( $busqueda['u'] != '' ) { $this->db->where('creador_id', $busqueda['u']); }        //Creado
            if ( $busqueda['tp'] != '' ) { $this->db->where('tipo_id', $busqueda['tp']); }          //Tipo
            
        //Obtener resultados
        if ( is_null($per_page) ){
            $query = $this->db->get('evento'); //Resultados totales
        } else {
            $query = $this->db->get('evento', $per_page, $offset); //Resultados por página
        }
        
        return $query;
        
    }
    
    /**
     * Devuelve la cantidad de registros encontrados en la tabla con los filtros
     * establecidos en la búsqueda
     * 
     * @param type $busqueda
     * @return type
     */
    function cant_resultados($busqueda)
    {
        $resultados = $this->buscar($busqueda); //Para calcular el total de resultados
        return $resultados->num_rows();
    }
    
    /**
     * Determina si un usuario tiene el permiso para eliminar un registro de evento
     * 
     * @param type $evento_id
     * @return boolean
     */
    function eliminable($evento_id)
    {   
        $eliminable = FALSE;
        $row_evento = $this->Pcrn->registro_id('evento', $evento_id);
        
        //El usuario creó el evento
        if ( $row_evento->creador_id == $this->session->userdata('usuario_id') ) {
            $eliminable = TRUE;
        }
        
        //El usuario es aministrador
        if ( $this->session->userdata('rol_id') <= 1 ) { $eliminable = TRUE; }
            
        return $eliminable;
    }
    
    /**
     * Array con los datos para la vista de exploración
     * 
     * @return string
     */
    function data_explorar()
    {
        //Elemento de exploración
            $data['elemento_p'] = 'eventos';    //Nombre del elemento el plural
            $data['elemento_s'] = 'evento';      //Nombre del elemento en singular
            $data['controlador'] = 'eventos';   //Nombre del controlador
            $data['funcion'] = 'explorar';      //Función de exploración
            $data['cf'] = $data['controlador'] . '/' . $data['funcion'] . '/';      //Controlador función
            $data['carpeta_vistas'] = 'eventos/';   //Carpeta donde están las vistas de exploración
            $data['titulo_pagina'] = 'Eventos';
        
        //Paginación
            $data['per_page'] = 20; //Cantidad de registros por página
            $data['offset'] = $this->input->get('per_page');
            
        //Búsqueda y Resultados
            $this->load->model('Busqueda_model');
            $data['busqueda'] = $this->Busqueda_model->busqueda_array();
            $data['busqueda_str'] = $this->Busqueda_model->busqueda_str();
            $data['resultados'] = $this->Evento_model->buscar($data['busqueda'], $data['per_page'], $data['offset']);    //Resultados para página
            $data['cant_resultados'] = $this->Evento_model->cant_resultados($data['busqueda']);
            
        //Otros
            $data['url_paginacion'] = base_url("{$data['cf']}?{$data['busqueda_str']}");
            $data['seleccionados_todos'] = '-'. $this->Pcrn->query_to_str($data['resultados'], 'id');   //Para selección masiva de todos los elementos de la página

        //Visitas
            $data['subtitulo_pagina'] = $data['cant_resultados'];
            $data['vista_a'] = $data['carpeta_vistas'] . 'explorar/explorar_v';
            $data['vista_menu'] = $data['carpeta_vistas'] . 'explorar/menu_v';
        
        return $data;
    }
    
    /**
     * Elimina un registro de evento y sus registros relacionados en otras tablas
     * 
     * @param type $evento_id
     * @return type
     */
    function eliminar($evento_id)
    {
        $cant_eliminados = 0;
        $eliminable = $this->eliminable($evento_id);
        
        if ( $eliminable ) 
        {
            //Tablas relacionadas
        
            //Tabla
                $this->db->where('id', $evento_id);
                $this->db->delete('evento');
                
            $cant_eliminados = $this->db->affected_rows();
        }
            
        return $cant_eliminados;
    }
    
    /**
     * Modifica el campo evento.estado para un registro específico
     * 
     * @param type $tipo_id
     * @param type $elemento_id
     * @param type $estado
     */
    function act_estado($tipo_id, $elemento_id, $estado)
    {
        $registro['estado'] = $estado;
        
        $this->db->where('tipo_id', $tipo_id);
        $this->db->where('elemento_id', $elemento_id);
        $this->db->update('evento', $registro);
    }
    
    /**
     * Guarda un registro en la tabla evento
     * 
     * @param type $registro
     * @return type
     */
    function guardar_evento($registro, $condicion_add = NULL)
    {
        //Condición para identificar el registro del evento
            $condicion = "tipo_id = {$registro['tipo_id']} AND elemento_id = {$registro['elemento_id']}";
            if ( ! is_null($condicion_add) )
            {
                $condicion .= " AND " . $condicion_add;
            }
        
            $evento_id = $this->Pcrn->existe('evento', $condicion);
        
        //Datos adicionales del registro
            $registro['editor_id'] = $this->session->userdata('usuario_id');
            $registro['editado'] = date('Y-m-d H:i:s');
        
        //Guardar el evento
        if ( $evento_id == 0 )
        {
            //No existe, se inserta
            $registro['direccion_ip'] = $this->input->ip_address();
            $registro['editado'] = date('Y-m-d H:i:s');
            $registro['editor_id'] = $this->session->userdata('usuario_id');
            $registro['creado'] = date('Y-m-d H:i:s');
            $registro['creador_id'] = $this->session->userdata('usuario_id');
            
            $this->db->insert('evento', $registro);
            $evento_id = $this->db->insert_id();
        } else {
            //Ya existe, editar
            $this->db->where('id', $evento_id);
            $this->db->update('evento', $registro);
        }
        
        return $evento_id;
    }
    
    /**
     * Devuelve array con datos registro base, para crear o editar un registro
     * de evento, datos primordiales, comunes.
     * 
     */
    function reg_base()
    {
        $usuario_id = 0;
        if ($this->session->userdata('logged') ) { $usuario_id = $this->session->userdata('usuario_id'); }
        
        $momento = date('Y-m-d H:i:s');
        
        $registro['usuario_id'] = $usuario_id;
        $registro['direccion_ip'] = $this->input->ip_address();
        $registro['inicio'] = $momento;
        $registro['editado'] = $momento;
        $registro['editor_id'] = $usuario_id;
        $registro['creado'] = $momento;
        $registro['creador_id'] = $usuario_id;
        
        return $registro;
        
    }
    
// DATOS
//---------------------------------------------------------------------------------------------------------
    
    function cant_eventos($filtros)
    {
        if ( $filtros['u'] != '' ) { $this->db->where('usuario_id', $filtros['u']); }   //Usuario
        if ( $filtros['t'] != '' ) { $this->db->where('tipo_id', $filtros['t']); }      //Tipo
        
        $query = $this->db->get('evento');
        
        return $query->num_rows();
    }
    
    function row_evento($claves)
    {
        //Valor por defecto
        $row = NULL;
        
        $this->db->where($claves);
        $query = $this->db->get('evento');
        if ( $query->num_rows() > 0 ){
            $row = $query->row();
        }
        
        return $row;
    }
    
    /**
     * Cantidad de segundos entre la fecha y hora de inicio, y una fecha y hora 
     * determinados
     */
    function segundos($row_evento, $fh_fin)
    {
        $fh_inicio = $row_evento->inicio . ' ' . $row_evento->hora_inicio;
        $segundos = $this->Pcrn->segundos_lapso($fh_inicio, $fh_fin);
        
        return $segundos;
    }
    
// GESTIÓN DE EVENTO LOGIN
//-----------------------------------------------------------------------------
    
    /**
     * Crea un registro en la tabla evento, asociado al inicio de sesión
     * @return type
     */
    function guardar_ev_login()
    {
        $row_usuario = $this->Pcrn->registro_id('usuario', $this->session->userdata('usuario_id'));
        //$nivel = $this->Pcrn->campo_id('grupo', $row_usuario->grupo_id, 'nivel');
        
        //Registro, valores generales
            $registro['tipo_id'] = 101;   //Programación de quiz, ver item cantegoria_id = 13
            $registro['inicio'] = date('Y-m-d H:i:s');
            $registro['elemento_id'] = $row_usuario->id;
            $registro['usuario_id'] = $row_usuario->id;
            $registro['estado'] = 1;    //Login iniciado

            $condicion_add = 'id = 0';  //Se pone una condición adicional incumplible, para que siempre agregue el registro
            $evento_id = $this->guardar_evento($registro, $condicion_add);
        
        //Agregar evento_id a los datos de sesión
            $this->session->set_userdata('login_id', $evento_id);
        
        return $evento_id;
    }
    
// NOTICIAS
//---------------------------------------------------------------------------------------------------------
    
    /**
     * Query con los eventos para mostrarse en el muro de noticias del usuario
     * 
     * @return type
     */
    function noticias($busqueda, $limit, $offset = 0)
    {
        
        $condicion = $this->condicion_noticias();
        $fecha_limite = $this->Pcrn->suma_fecha(date('Y-m-d'), '+1 day') . ' 23:59:59';
        
        //Filtros
        if ( $busqueda['tp'] != '' ) { $this->db->where('tipo_id', $busqueda['tp']); }      //Tipo evento
        
        $this->db->select('evento.*, usuario.nombre, usuario.apellidos');
        $this->db->limit($limit);
        $this->db->offset($offset);
        $this->db->where('inicio <= "' . $fecha_limite . '"');
        $this->db->where("($condicion)");
        $this->db->join('usuario', 'usuario.id = evento.usuario_id', 'LEFT');
        $this->db->order_by('inicio', 'DESC');
        $this->db->order_by('hora_inicio', 'DESC');
        
        $query = $this->db->get('evento');
        
        return $query;
    }
    
    /**
     * String con condición SQL para filtrar los eventos que aparecerán
     * en el muro de noticias del usuario. El filtro depende del rol de usuario
     * y del tipo de evento.
     * 
     * @return type
     */
    function condicion_noticias()
    {
        $srol = $this->session->userdata('srol');   //Superrol
        $condicion = 'id = 0';
        
        $str_grupos = $this->Pcrn->si_strlen(implode(',', $this->session->userdata('arr_grupos')), 'NULL');
        
        
        switch ($srol)
        {
            case 'interno';
                $condicion = "tipo_id IN (16, 17, 101, 50, 30, 31, 35, 36, 37)";
                break;
            case 'institucional';
                $condicion = "(tipo_id = 50 AND entero_1 IN (1, 3) AND evento.institucion_id = {$this->session->userdata('institucion_id')})";
                $condicion .= ' OR ';
                $condicion .= "(tipo_id = 16 AND evento.grupo_id IN ({$str_grupos}))";
                $condicion .= ' OR ';
                $condicion .= "(tipo_id = 50 AND entero_1 = 2 AND evento.grupo_id IN ({$str_grupos}))";
                $condicion .= ' OR ';
                $condicion .= "(tipo_id = 30 AND evento.grupo_id IN ({$str_grupos}))";
                $condicion .= ' OR ';
                $condicion .= "(tipo_id = 35 AND evento.grupo_id IN ({$str_grupos}))";
                $condicion .= ' OR ';
                $condicion .= "(tipo_id = 37 AND evento.grupo_id IN ({$str_grupos}))";
                $condicion .= ' OR ';
                $condicion .= "(tipo_id = 17 AND usuario_id = {$this->session->userdata('usuario_id')})";
                break;
            case 'externo';
                //Estudiantes
                $condicion = "(tipo_id = 1 AND estado = 0 AND usuario_id = {$this->session->userdata('usuario_id')})";
                $condicion .= ' OR ';
                $condicion .= "(tipo_id = 16 AND usuario_id = {$this->session->userdata('usuario_id')})";
                $condicion .= ' OR ';
                $condicion .= "(tipo_id = 50 AND evento.grupo_id IN ({$str_grupos}))";
                $condicion .= ' OR ';
                $condicion .= "(tipo_id = 30 AND evento.grupo_id IN ({$str_grupos}))";
                $condicion .= ' OR ';
                $condicion .= "(tipo_id = 36 AND usuario_id = {$this->session->userdata('usuario_id')})";
                $condicion .= ' OR ';
                $condicion .= "(tipo_id = 37 AND evento.grupo_id IN ({$str_grupos}))";
                $condicion .= ' OR ';
                $condicion .= "(tipo_id = 31 AND evento.grupo_id IN ({$str_grupos}))";
                break;
        }
        
        return $condicion;
    }
    
    /**
     * Array con variables para la configuración del formulario
     * de publicaciones del muro de noticias, la configuración depende del rol de usuario
     * 
     * @return string
     */
    function config_form_publicacion()
    {
        //Variables de referencia
            $srol = $this->session->userdata('srol');
            $grupo_id = $this->session->userdata('grupo_id');
        
        //Valores por defecto, srol estudiante
            $config_form['entero_1'] = 2;
            $config_form['grupo_id'] = $grupo_id;
            //$config_form['texto_alcance'] = '<i class="fa fa-users"></i> Grupo ' . $this->App_model->nombre_grupo($grupo_id);
            $config_form['texto_alcance'] = '¿Quién verá esto?';

        //Config, según súper rol
        /*switch ($srol) {
            case 'interno':
                $config_form['entero_1'] = 1;
                $config_form['grupo_id'] = 0;
                $config_form['texto_alcance'] = '<i class="fa fa-building"></i> Institución';
                break;
            case 'institucional':
                $config_form['entero_1'] = 1;
                $config_form['grupo_id'] = 0;
                $config_form['texto_alcance'] = '<i class="fa fa-building"></i> Institución';
                break;
        }*/
        
        return $config_form;
    }
    
// ACTIVIDAD Y NOTICIAS DE USUARIO
//---------------------------------------------------------------------------------------------------------
    
    /**
     * Query con los eventos de la actividad de un usuario específico ($usuario_id)
     * 
     * @return type
     */
    function noticias_usuario($usuario_id, $busqueda, $limit, $offset = 0)
    {
        
        $condicion = $this->condicion_noticias();
        $fecha_limite = $this->Pcrn->suma_fecha(date('Y-m-d'), '+1 day') . ' 23:59:59';
        
        //Filtros
        if ( $busqueda['tp'] != '' ) { $this->db->where('tipo_id', $busqueda['tp']); }      //Tipo evento
        
        //Usuario
        $this->db->where('usuario_id', $usuario_id);
        
        $this->db->limit($limit);
        $this->db->offset($offset);
        $this->db->where('inicio <= "' . $fecha_limite . '"');
        $this->db->where("({$condicion})");
        $this->db->join('usuario', 'usuario.id = evento.usuario_id', 'LEFT');
        $this->db->order_by('inicio', 'DESC');
        $this->db->order_by('hora_inicio', 'DESC');
        
        $query = $this->db->get('evento');
        
        return $query;
    }
    
    
// EVENTOS BÚSQUEDAS
//-----------------------------------------------------------------------------
    
    /**
     * Define si un texto de búsqueda de productos se guarda o no
     * 
     * @param type $busqueda
     * @return boolean
     */
    function busqueda_guardable($busqueda)
    {
        $guardable = FALSE;
        
        //Según el estado de login
            if ( $this->session->userdata('logged') ) 
            {
                //Usuarios logueados
                $guardable = TRUE;
            } else {
                //Usuarios no logueados, identificado por session id
                    $mktime = strtotime(date('Y-m-d H:i:s') . ' -1 day');
                    $fi = date('Y-m-d H:i:s', $mktime);
                    $direccion_ip = $this->input->ip_address();
                    $condicion = "inicio >= '{$fi}' AND tipo_id = 310020 AND direccion_ip = '{$direccion_ip}'";
                    $cant_registros = $this->Pcrn->num_registros('evento', $condicion);
                    
                    if ( $cant_registros < 3 )  //Hasta tres registros de búsqueda para no logueados
                    {
                        $guardable = TRUE;
                    }
            }
            
        //Según la longitud del texto de búsqueda
            if ( strlen($busqueda['q']) == 0 ) { $guardable = FALSE; }
        
        
        return $guardable;
    }
    
    /**
     * Inserta un registro en la tabla evento, sobre un texto buscado en el
     * catálogo de productos.
     * 
     * @param type $busqueda
     */
    function guardar_ev_busqueda($busqueda)
    {
        
        //Verificar si la búsqueda se guarda o no
        if ( $this->busqueda_guardable($busqueda) )
        {
            //Registro base
                $registro = $this->reg_base();

            //Construir Registro
                $registro['nombre_evento'] = $busqueda['q'];
                $registro['tipo_id'] = 310020;
                $registro['estado'] = $this->Pcrn->si_nulo($this->session->userdata('logged'), 0);

            //Guardar
                $this->db->insert('evento', $registro);
        }
    }
    
// EVENTOS PUBLICACIONES
//---------------------------------------------------------------------------------------------------------
    
    function guardar_ev_publicacion($post_id)
    {
        $row_post = $this->Pcrn->registro_id('post', $post_id);
        
        $registro['tipo_id'] = 50;    //Publicación, ver item categoria_id = 13, tipos de evento
        $registro['elemento_id'] = $post_id;
        $registro['entero_1'] = $this->input->post('entero_1');    //Tipo publicación, ver item categoria_id = 12, tipo de publicación muro
        $registro['inicio'] = $this->Pcrn->fecha_formato($row_post->creado, 'Y-m-d');
        $registro['hora_inicio'] = $this->Pcrn->fecha_formato($row_post->creado, 'H:i:s');
        $registro['institucion_id'] = $this->session->userdata('institucion_id');
        $registro['grupo_id'] = $this->input->post('grupo_id');
        $registro['usuario_id'] = $this->session->userdata('usuario_id');

        $evento_id = $this->guardar_evento($registro);
        
        return $evento_id;
    }

// OPIONES DROPDOWN
//-----------------------------------------------------------------------------

    function options_year($start, $end)
    {
        $options_year = array();
        for ($year=$start; $year < $end; $year++) { 
            $options_year['0' . $year] = $year;
        }

        return $options_year;
    }

    function options_month()
    {
        $options_month['001'] = 'Enero';
        $options_month['002'] = 'Febrero';
        $options_month['003'] = 'Marzo';
        $options_month['004'] = 'Abril';
        $options_month['005'] = 'Mayo';
        $options_month['006'] = 'Junio';
        $options_month['007'] = 'Julio';
        $options_month['008'] = 'Agosto';
        $options_month['009'] = 'Septiembre';
        $options_month['010'] = 'Octubre';
        $options_month['011'] = 'Noviembre';
        $options_month['012'] = 'Diciembre';

        return $options_month;
    }

    function options_day()
    {
        $options_day = array();
        for ($day=1; $day < 32; $day++) { 
            $options_day['0' . $day] = $day;
        }

        return $options_day;
    }
    
}