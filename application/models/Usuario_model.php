<?php
class Usuario_model extends CI_Model{
    
    //Devuelve un arra con los datos del usuario
    function basic($user_id)
    {
        $row = $this->Db_model->row_id('usuario', $user_id);
            
        $data['head_title'] = $row->display_name;
        $data['nav_2'] = 'usuarios/menus/cliente_v';
        $data['row'] = $row;            
        
        return $data;
    }
    
// EXPLORE FUNCTIONS - usuarios/explore
//-----------------------------------------------------------------------------
    
    /**
     * Array con los datos para la vista de exploración
     */
    function explore_data($filters, $num_page)
    {
        //Data inicial, de la tabla
            $data = $this->get($filters, $num_page);
        
        //Elemento de exploración
            $data['controller'] = 'usuarios';                      //Nombre del controlador
            $data['cf'] = 'usuarios/explore/';                      //Nombre del controlador
            $data['views_folder'] = 'usuarios/explore/';           //Carpeta donde están las vistas de exploración
            
        //Vistas
            $data['head_title'] = 'Usuarios';
            $data['head_subtitle'] = $data['search_num_rows'];
            $data['view_a'] = $data['views_folder'] . 'explore_v';
            $data['nav_2'] = $data['views_folder'] . 'menu_v';
        
        return $data;
    }

    /**
     * Array con listado de usuarios, filtrados por búsqueda y num página, más datos adicionales sobre
     * la búsqueda, filtros aplicados, total resultados, página máxima.
     * 2020-08-01
     */
    function get($filters, $num_page, $per_page = 10)
    {
        //Referencia
            $offset = ($num_page - 1) * $per_page;      //Número de la página de datos que se está consultado

        //Búsqueda y Resultados
            $elements = $this->search($filters, $per_page, $offset);    //Resultados para página
        
        //Cargar datos
            $data['filters'] = $filters;
            $data['list'] = $this->list($filters, $per_page, $offset);    //Resultados para página
            $data['str_filters'] = $this->Search_model->str_filters();      //String con filtros en formato GET de URL
            $data['search_num_rows'] = $this->search_num_rows($data['filters']);
            $data['max_page'] = ceil($this->pml->if_zero($data['search_num_rows'],1) / $per_page);   //Cantidad de páginas

        return $data;
    }
    
    /**
     * Query de usuarios, filtrados según búsqueda, limitados por página
     * 2020-08-01
     */
    function search($filters, $per_page = NULL, $offset = NULL)
    {
        //Construir consulta
            $this->db->select('usuario.id, username, display_name, address, institution_name, short_note, email, rol_id AS role, image_id, url_image, url_thumbnail, estado AS status, usuario.ciudad_id AS city_id, celular AS phone_number, telefono, sexo AS gender, fecha_nacimiento AS birth_date, no_documento AS id_number, tipo_documento_id AS id_number_type');
            
        //Orden
            if ( $filters['o'] != '' )
            {
                $order_type = $this->pml->if_strlen($filters['ot'], 'ASC');
                $this->db->order_by($filters['o'], $order_type);
            } else {
                $this->db->order_by('ultimo_login', 'DESC');
            }
            
        //Filtros
            $search_condition = $this->search_condition($filters);
            if ( $search_condition ) { $this->db->where($search_condition);}
            
        //Obtener resultados
            $query = $this->db->get('usuario', $per_page, $offset); //Resultados por página
        
        return $query;
    }

    /**
     * String con condición WHERE SQL para filtrar users
     * 2020-08-01
     */
    function search_condition($filters)
    {
        $condition = NULL;

        $condition .= $this->role_filter() . ' AND ';

        //q words condition
        $words_condition = $this->Search_model->words_condition($filters['q'], array('display_name', 'email', 'no_documento', 'short_note'));
        if ( $words_condition )
        {
            $condition .= $words_condition . ' AND ';
        }
        
        //Otros filtros
        if ( $filters['role'] != '' ) { $condition .= "rol_id = {$filters['role']} AND "; }
        if ( $filters['gender'] != '' ) { $condition .= "sexo = {$filters['gender']} AND "; }
        
        //Quitar cadena final de ' AND '
        if ( strlen($condition) > 0 ) { $condition = substr($condition, 0, -5);}
        
        return $condition;
    }

    /**
     * Array Listado elemento resultado de la búsqueda (filtros).
     * 2020-06-19
     */
    function list($filters, $per_page = NULL, $offset = NULL)
    {
        $query = $this->search($filters, $per_page, $offset);
        $list = array();

        foreach ($query->result() as $row)
        {
            $row->city_name = $this->App_model->nombre_lugar($row->city_id);  //Cantidad de estudiantes*/
            /*if ( $row->image_id == 0 )
            {
                $first_image = $this->first_image($row->id);
                $row->url_image = $first_image['url'];
                $row->url_thumbnail = $first_image['url_thumbnail'];
            }*/
            $list[] = $row;
        }

        return $list;
    }
    
    /**
     * Devuelve la cantidad de registros encontrados en la tabla con los filtros
     * establecidos en la búsqueda
     */
    function search_num_rows($filters)
    {
        $this->db->select('id');
        $search_condition = $this->search_condition($filters);
        if ( $search_condition ) { $this->db->where($search_condition);}
        $query = $this->db->get('usuario'); //Para calcular el total de resultados

        return $query->num_rows();
    }
    
    /**
     * Devuelve segmento SQL, para filtrar listado de usuarios según el rol del usuario en sesión
     * 2020-08-01
     */
    function role_filter()
    {
        $role = $this->session->userdata('role');
        $condition = 'id = 0';  //Valor por defecto, ningún user, se obtendrían cero user.
        
        if ( $role <= 6 ) 
        {   //Desarrollador, todos los user
            $condition = 'usuario.id > 0';
        }
        
        return $condition;
    }
    
    /**
     * Array con options para ordenar el listado de user en la vista de
     * exploración
     * 
     */
    function order_options()
    {
        $order_options = array(
            '' => '[ Ordenar por ]',
            'id' => 'ID Usuario',
            'last_name' => 'Apellidos',
            'id_number' => 'No. documento',
        );
        
        return $order_options;
    }

    /**
     * Opciones de usuario en campos de autollenado, como agregar usuarios a una conversación
     * 2019-11-13
     */
    function autocomplete($filters, $limit = 50)
    {
        //Construir condición de búsqueda
            $search_condition = $this->search_condition($filters);
        
        //Especificaciones de consulta
            $this->db->select('id, CONCAT((display_name), " (",(username), ")") AS value');
            if ( $search_condition ) { $this->db->where($search_condition);}
            $this->db->order_by('display_name', 'ASC');
            $query = $this->db->get('user', $limit); //Resultados por página
        
        return $query;
    }
    
    
// FIN FUNCIONES EXPLORACIÓN
//-----------------------------------------------------------------------------

    function verificar_username($username)
    {
        
        $this->db->where("username LIKE '{$username}%'");
        $query = $this->db->get('usuario');
        
        return $query->num_rows();   
    }
    
//REGISTRO Y ACTIVACIÓN
//---------------------------------------------------------------------------------------------------
    
    /**
     * Después de validar el formulario de registro de usuario, se lo guarda
     * en la tabla.
     * 
     * @param type $registro
     * @return type
     */
    function crear_usuario($registro)
    {
        //Datos complementarios
            $registro['creado'] = date('Y-m-d h:i:s');
            $registro['rol_id'] = 21;    //Valor por defecto
        
        $this->db->insert('usuario', $registro);
        return $this->db->insert_id();
    }
    
    /**
     * Actualizar registro tabla usuario
     * 2020-09-28
     */
    function update($user_id, $arr_row)
    {
        $arr_row['display_name'] = $arr_row['nombre'] . ' ' . $arr_row['apellidos'];
        if ( $arr_row['sexo'] == 90 ) {
            $arr_row['display_name'] = $arr_row['institution_name'];
        }

        $arr_row['updater_id'] = $this->session->userdata('user_id');
        $arr_row['editado'] = date('Y-m-d H:i:s');

        $data['saved_id'] = $this->Db_model->save('usuario', "id = {$user_id}", $arr_row);

        $data['status'] = 0;
        if ( $data['saved_id'] > 0 ) $data['status'] = 1;
        
        return $data;
    }
    
    /**
     * Después de validar el formulario de registro de usuario se guarda en la tabla usuario
     * 2020-09-26
     * 
     */
    function insert($arr_row)
    {
        //Encriptar pw
            $arr_row['password'] = $this->encriptar_pw($arr_row['password']);
        
        //Datos complementarios
            if ( is_null($this->input->post('username')) ) $arr_row['username'] = $arr_row['email'];
            $arr_row['display_name'] = $arr_row['nombre'] . ' ' . $arr_row['apellidos'];
            $arr_row['editado'] = date('Y-m-d h:i:s');
            $arr_row['creado'] = date('Y-m-d h:i:s');
            $arr_row['updater_id'] = $this->session->userdata('user_id');
            $arr_row['creator_id'] = $this->session->userdata('user_id');
        
        $this->db->insert('usuario', $arr_row);
        
        //Rssultado
        $data['status'] = 0;
        $data['saved_id'] = $this->db->insert_id();
        if ( $data['saved_id'] > 0 ) $data['status'] = 1;
        
        return $data;
    }
    
    /**
     * Envía e-mail de activación o restauración de cuenta
     * 
     * @param type $usuario_id
     * @param type $tipo_activacion
     */
    function email_activacion($usuario_id, $tipo_activacion = 'activar')
    {
        $row_usuario = $this->Pcrn->registro_id('usuario', $usuario_id);
        
        //Establecer código de activación
            $this->cod_activacion($usuario_id);
            
        //Asunto de mensaje
            $subject = 'Actívate en DistriCatolicas.com';
            if ( $tipo_activacion == 'restaurar' ) {
                $subject = 'Restaurar contraseña en DistriCatolicas.com';
            }
        
        //Enviar Email
            $this->load->library('email');
            $config['mailtype'] = 'html';

            $this->email->initialize($config);
            $this->email->from('info@districatolicas.com', 'DistriCatolicas.com');
            $this->email->to($row_usuario->email);
            $this->email->message($this->Usuario_model->mensaje_activacion($usuario_id, $tipo_activacion));
            $this->email->subject($subject);
            
            $this->email->send();   //Enviar
    }
    
    /**
     * Devuelve texto de la vista que se envía por email a un usuario para activación o restauración de su cuenta
     * 
     * @param type $usuario_id
     * @param type $tipo_activacion
     * @return type
     */
    function mensaje_activacion($usuario_id, $tipo_activacion)
    {
        $row_usuario = $this->Pcrn->registro_id('usuario', $usuario_id);
        $data['row_usuario'] = $row_usuario ;
        $data['tipo_activacion'] = $tipo_activacion;
        
        $mensaje = $this->load->view('usuarios/email_activacion_v', $data, TRUE);
        
        return $mensaje;
    }
    
    /**
     * Devuelve texto de la vista que se envía por email a un usuario para autorización de rol para de su cuenta de usuario
     * 
     * @param type $row_meta
     * @return type
     */
    function mensaje_autorizacion($row_meta)
    {
        $row_usuario = $this->Pcrn->registro_id('usuario', $row_meta->elemento_id);
        $data['row_usuario'] = $row_usuario;
        $data['rol_id'] = $row_meta->relacionado_id;
        $data['meta_id'] = $row_meta->id;
        $data['style'] = $this->App_model->email_style();
        
        $mensaje = $this->load->view('usuarios/emails/autorizacion_v', $data, TRUE);
        
        return $mensaje;
    }
    
    /**
     * Establece un código de activación o recuperación de cuenta de usuario
     * 
     * @param type $usuario_id
     */
    function cod_activacion($usuario_id)
    {
        $this->load->helper('string');
        $registro['cod_activacion'] = random_string('alpha', 32);
        
        $this->db->where('id', $usuario_id);
        $this->db->update('usuario', $registro);
    }
    
    function row_activacion($cod_activacion)
    {
        $condicion = "cod_activacion = '{$cod_activacion}'";
        $row_usuario = $this->Pcrn->registro('usuario', $condicion);
        return $row_usuario;
    }
    
    function activar($cod_activacion)
    {
        $row_activacion = $this->row_activacion($cod_activacion);
        
        //Registro
            $registro['estado'] = 1;
            $registro['password'] = $this->encriptar_pw($this->input->post('password'));

        //Actualizar
            $this->db->where('id', $row_activacion->id);
            $this->db->update('usuario', $registro);
            
        return $row_activacion;
    }
    
    /**
     * Envía un email de para la activación o restauración de cuenta
     * 
     * @param type $email
     * @return int
     */
    function recuperar($email)
    {
        $enviado = 0;
        
        //Identificar usuario
        $row_usuario = $this->Pcrn->registro('usuario', "email = '{$email}'");
        if ( ! is_null($row_usuario) ) {
            $this->email_activacion($row_usuario->id, 'restaurar');
            $enviado = 1;
        }
        
        return $enviado;
        
    }
    
    /**
     * Envía e-mail solicitando autorización de registro de cliente
     * 
     * @param type $meta_id
     */
    function email_autorizacion($meta_id)
    {
        $row_meta = $this->Pcrn->registro_id('meta', $meta_id);
        $row_usuario = $this->Pcrn->registro_id('usuario', $row_meta->elemento_id);
            
        //Asunto de mensaje
            $subject = "{$row_usuario->nombre} {$row_usuario->apellidos} solicita rol de Distribuidor";
            $mensaje = $this->Usuario_model->mensaje_autorizacion($row_meta);
        
        //Enviar Email
            $this->load->library('email');
            $config['mailtype'] = 'html';

            $this->email->initialize($config);
            $this->email->from('info@districatolicas.com', 'Districatólicas Unidas S.A.S.');
            $this->email->to($this->App_model->valor_opcion(26));   //Ver Ajustes > Parámetros > General
            $this->email->subject($subject);
            $this->email->message($mensaje);
            
            $this->email->send();   //Enviar
    }
    
    function aprobar_rol($meta_id)
    {
        $row_solicitud = $this->Pcrn->registro_id('meta', $meta_id);
        $row_usuario = $this->Pcrn->registro_id('usuario', $row_solicitud->elemento_id);
        
        //Resultado por defecto
            $resultado['clase'] = 'alert-danger';
            $resultado['mensaje'] = 'No se realizó la actualización, ejecute el proceso desde su cuenta de administración';
        
        if ( ! is_null($row_usuario) )
        {
            //Editar usuario
                $registro['rol_id'] = $row_solicitud->relacionado_id;

                $this->db->where('id', $row_usuario->id);
                $this->db->update('usuario', $registro);
            
            //Editar solicitud, tabla meta
                $reg_meta['estado'] = 1;    //Aprobada
                $reg_meta['usuario_id'] = $this->session->userdata('usuario_id');    //Usuario que aprueba
                $reg_meta['editado'] = date('Y-m-d H:i:s');    //Fecha de aprobación
                
                $this->db->where('id', $meta_id);
                $this->db->update('meta', $reg_meta);
                
            //E-mail informando al usuario
                $this->email_rta_solicitud($meta_id);
                
            //Resultado
                $resultado['clase'] = 'alert-success';
                $resultado['mensaje'] = 'El rol del usuario fue actualizado exitosamente.';
        }
        
        return $resultado;
    }
    
    function email_rta_solicitud($meta_id)
    {
        $row_meta = $this->Pcrn->registro_id('meta', $meta_id);
        $row_usuario = $this->Pcrn->registro_id('usuario', $row_meta->elemento_id);
        
        $texto_estado = $this->Item_model->nombre(43, $row_meta->estado);
            
        //Asunto de mensaje
            $subject = "Resultado solicitud: {$texto_estado}";
            $mensaje = $this->Usuario_model->mensaje_rta_solicitud($row_meta, $row_usuario);
        
        //Enviar Email
            $this->load->library('email');
            $config['mailtype'] = 'html';

            $this->email->initialize($config);
            $this->email->from('info@districatolicas.com', 'Districatólicas Unidas S.A.S.');
            $this->email->to($row_usuario->email);   //Ver Ajustes > Parámetros > General
            $this->email->subject($subject);
            $this->email->message($mensaje);
            
            $this->email->send();   //Enviar
    }
    
    function mensaje_rta_solicitud($row_meta, $row_usuario)
    {
        $data['row_meta'] = $row_meta;
        $data['row_usuario'] = $row_usuario;
        $data['style'] = $this->App_model->email_style();
        
        $mensaje = $this->load->view('usuarios/emails/rta_solicitud_v', $data, TRUE);
        
        return $mensaje;
    }
    
    function solicitudes()
    {
        $this->db->select('id AS meta_id, elemento_id AS usuario_id, relacionado_id AS rol_id, estado');
        $this->db->where('tabla_id', 1000); //Tabla usuario
        $this->db->where('dato_id', 100011); //Solicitud de cambio de rol
        $this->db->order_by('editado', 'DESC');
        $query = $this->db->get('meta');

        return $query;
    }

//GESTIÓN DE CONTRASEÑAS
//---------------------------------------------------------------------------------------------------
    
    /**
     * Devuelve password encriptado
     * 
     * @param type $input
     * @param type $rounds
     * @return type
     */
    function encriptar_pw($input, $rounds = 7)
    {
        $salt = '';
        $salt_chars = array_merge(range('A','Z'), range('a','z'), range(0,9));
        for($i=0; $i < 22; $i++) {
          $salt .= $salt_chars[array_rand($salt_chars)];
        }
        
        return crypt($input, sprintf('$2a$%02d$', $rounds) . $salt);
    }
    
    function validar_contrasenas()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('passconf', 'Password Confirmation', 'required|matches[password]',
                array('matches' => 'Las contraseñas no coinciden')
            );
        
        return $this->form_validation->run();
    }
    
    function establecer_contrasena($usuario_id, $password)
    {
        $registro['password'] = $this->encriptar_pw($password);
        $this->db->where('id', $usuario_id);
        $action = $this->db->update('usuario', $registro);
        return $action;
    }
    
//OTRAS
//---------------------------------------------------------------------------------------------------
    
    function deleteable($usuario_id)
    {
        $user = $this->Db_model->row_id('usuario', $usuario_id);

        $deleteable = 0;
        if ( $this->session->userdata('usuario_id') <= 1 ) $deleteable = 1;
        if ( $user->rol_id <= 1 ) $deleteable = 0;  //No se pueden eliminar los administradores

        return $deleteable;
    }
    
    /**
     * Eliminar usuario
     * 2020-09-26
     */
    function delete($usuario_id)
    {
        $qty_deleted = 0;

        if ( $this->deleteable($usuario_id) )
        {
            //Tablas relacionadas
                //meta
                $this->db->where('tabla_id', 1000); //Tabla usuario
                $this->db->where('elemento_id', $usuario_id);
                $this->db->delete('meta');
            
            //Tabla principal
                $this->db->where('id', $usuario_id);
                $this->db->delete('usuario');

            $qty_deleted = $this->db->affected_rows();
        }

        return $qty_deleted;
    }
    
    /* Esta función genera un string con el username para un registro en la tabla usuario
    * Se forma: la primera letra del primer nombre + la primera letra del segundo nombre +
    * el primer apellido + la primera letra del segundo apellido.
    * Se verifica que el username construido no exista
    */
    function generar_username($nombre, $apellidos){
        
        $this->load->model('Usuario_model');
        
        //Sin espacios iniciales o finales
        $nombre = trim($nombre);
        $apellidos = trim($apellidos);
        
        //Sin tildes ni ñ
        $nombre = $this->Pcrn->sin_acentos($nombre);
        $apellidos = $this->Pcrn->sin_acentos($apellidos);
        
        $apellidos_array = explode(" ", $apellidos);
        $nombre_array = explode(" ", $nombre);
        
        //Construyendo por partes
            //$username = substr($nombre_array[0], 0, 2);
            $username = $nombre_array[0];
            if ( isset($nombre_array[1]) ){
                $username .= substr($nombre_array[1], 0, 2);
            }
            
            $username .= '.' . $apellidos_array[0];
            
            if ( isset($apellidos_array[1]) ){
                $username .= substr($apellidos_array[1], 0, 2);
            }    
        
        //Reemplazando caracteres
            $username = str_replace (' ', '', $username); //Quitando espacios en blanco
            $username = strtolower($username); //Se convierte a minúsculas    
        
        //Verificar, si el username requiere un sufijo numérico para hacerlo único
            $sufijo = $this->sufijo_username($username);
            $username .= $sufijo;
        
        return $username;
        
    }
    
    /**
     * Devuelve un número entero para complementar el username construido
     * Sirve para garantizar que el username sea único, si el username no requiere
     * devuelve una cadena vacía
     * 
     * @param type $username
     * @return int
     */
    function sufijo_username($username)
    {
        $sufijo = '';
        
        $cant_username = $this->cant_username($username);
        if ( $cant_username > 0 )   //Ya existe usuario con ese username, necesita sufijo
        {
            $i = 2;
            while ( $cant_username > 0 ) 
            {
                $username_sufijo = $username . $i;
                $cant_username = $this->cant_username($username_sufijo);
                $sufijo = $i;
                $i++;  //Para siguiente ciclo
            }
        }
        
        return $sufijo;
    }
    
    /**
     * Devuelve cantidad de registros de usuerio que tienen un determinado username
     * @param type $username
     * @return type
     */
    function cant_username($username)
    {
        $condicion = "username = '{$username}'";
        $cant_username = $this->Pcrn->num_registros('usuario', $condicion);
        
        return $cant_username;
    }
    
//PROCESOS
//---------------------------------------------------------------------------------------------------
    
    function solicitar_rol($usuario_id, $rol_id)
    {
        
        $resultado['clase'] = 'alert-info';
        $resultado['mensaje'] = 'No se ejecutó el proceso';
        
        $this->load->model('Meta_model');
        
        //Crear solicitud en tabla meta
            $registro['tabla_id'] = 1000;   //Usuario
            $registro['elemento_id'] = $usuario_id;
            $registro['relacionado_id'] = $rol_id;
            $registro['dato_id'] = 100011;  //Solicitud de cambio de rol
            $registro['estado'] = 5;        //Enviada
            
            $meta_id = $this->Meta_model->guardar($registro);
            $resultado['meta_id'] = $meta_id;
            
        if ( $meta_id > 0 )
        {
            //Enviar Email a Administrador
                $this->email_autorizacion($meta_id);
                
            //Resultado
                $resultado['clase'] = 'alert-success';
                $resultado['mensaje'] = 'La solicitud fue creada. La respuesta se enviará a su correo electrónico.';
        }
        
        return $resultado;
            
            
    }
    
//DIRECCIONES DE USUARIOS
//---------------------------------------------------------------------------------------------------
    
    /**
     * Segmento SELECT sql para un registro de dirección, tabla post
     * @return string
     */
    function select_direccion() 
    {
        $select = 'id';
        $select .= ', nombre_post AS nombre_direccion';
        $select .= ', referente_1_id AS lugar_id';
        $select .= ', texto_1 AS direccion';
        $select .= ', texto_2 AS direccion_detalle';
        $select .= ', texto_3 AS telefono';
        $select .= ', referente_2_id AS es_principal';
        
        return $select;
    }
    
    /**
     * Row de una dirección con el ID $direccion_id, tabla post
     * 
     * @param type $direccion_id
     * @return type
     */
    function row_direccion($direccion_id)
    {
        $row_direccion = NULL;
        
        $this->db->select($this->select_direccion());
        $this->db->where('id', $direccion_id);
        $query = $this->db->get('post');
        
        if ( $query->num_rows() > 0 ) { $row_direccion = $query->row(); }
        
        return $row_direccion;
    }
    
    /**
     * Query con las direcciones que tiene un usuario, se ordenta la 
     * dirección principal de primera, para asignarla por defecto en un pedido.
     * 
     * @param type $meta_id
     * @return type
     */
    function direcciones($usuario_id, $meta_id = NULL)
    {
        if ( ! is_null($meta_id) ) 
        {
            $this->db->where('id', $meta_id);
        }
        
        $this->db->select($this->select_direccion());
        $this->db->where('tipo_id', 1001);  //Ver metadados, dirección
        $this->db->where('usuario_id', $usuario_id);
        $this->db->order_by('referente_2_id', 'DESC');  //El campo marca con 1 la dirección por defecto
        $query = $this->db->get('post');
        
        return $query;
    }
    
    function eliminar_direccion($usuario_id, $direccion_id)
    {
        $resultado['ejecutado'] = 0;
        $resultado['mensaje'] = 'El registro no se eliminó';
        $resultado['clase'] = 'alert-danger';
        $resultado['icono'] = 'fa-times';
        
        $this->db->where('id', $direccion_id);
        $this->db->where('usuario_id', $usuario_id);
        $this->db->delete('post');
        
        if ( $this->db->affected_rows() > 0 )
        {
            $resultado['ejecutado'] = 1;
            $resultado['mensaje'] = 'El registro fue eliminado';
            $resultado['clase'] = 'alert-success';
            $resultado['icono'] = 'fa-check';
        }
        
        return $resultado;
    }
    
    /**
     * Establece una dirección de un usuario como su dirección principal o dirección por defecto
     * El campo que identifica a una dirección como la principal es post.referente_2_id
     * 
     * @param type $usuario_id
     * @param type $post_id
     */
    function act_dir_principal($usuario_id, $post_id)
    {
        //Quitar defecto a todas
            $registro['referente_2_id'] = 0;
        
            $this->db->where('usuario_id', $usuario_id);
            $this->db->where('tipo_id', 1001);  //Ver metadatos
            $this->db->update('post', $registro);
            
        //Actualizar
            $registro['referente_2_id'] = 1;    //Es el que se marca
            
            $this->db->where('id', $post_id);
            $this->db->update('post', $registro);
    }
    
    /**
     * Devuelve un array con los datos de una dirección de un usuario
     * Los índices del array corresponden a los nombres de los campos de la tabla pedido
     * para ser utilizados y guardados en esa tabla
     * 2020-02-12
     */
    function arr_direccion()
    {
        //Variables iniciales
            $arr_direccion['ciudad_id'] = '';
        
        //Calcular valores si se identifica usuario y dirección (post)
            $usuario_id = $this->session->userdata('usuario_id');
            $row_user = $this->Db_model->row_id('usuario', $usuario_id);

            if ( strlen($row_user->address) > 0 ) 
            {
                $row_lugar = $this->Pcrn->registro_id('lugar', $row_user->ciudad_id);

                $arr_direccion['ciudad_id'] = $row_user->ciudad_id;
                $arr_direccion['direccion'] = $row_user->address;
                $arr_direccion['telefono'] = $row_user->telefono;
                
                $arr_direccion['pais_id'] = $row_lugar->pais_id;
                $arr_direccion['region_id'] = $row_lugar->region_id;
                $arr_direccion['ciudad'] = $this->App_model->nombre_lugar($row_lugar->id, 'CRP');
            }
        
        return $arr_direccion;
    }
    
    /**
     * Devuelve falso o verdadero, determina si una dirección puede ser editada
     * o no por un usuario.
     */
    function direccion_editable($direccion_id)
    {
        $row_direccion = $this->Pcrn->registro_id('post', $direccion_id);
        
        $editable = FALSE;  //Valor inicial
        if ( ! is_null($row_direccion) )
        {
            if ( $this->session->userdata('rol_id') <= 2 ) { $editable = TRUE; }    //Usuarios internos
            if ( $row_direccion->usuario_id == $this->session->userdata('usuario_id') ) { $editable = TRUE; }    
        }
        
        return $editable;
    }
    
    /** 
     * Devuelve render de formulario grocery crud para la edición
     * de datos de la tabla post, para el tipo de dato DIRECCIÓN
     */
    function crud_direccion($usuario_id)
    {
        //Grocery crud
        $this->load->library('grocery_CRUD');
        
        $crud = new grocery_CRUD();
        $crud->set_table('post');
        $crud->set_subject('dirección');
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_read();
        $crud->unset_back_to_list();
        
        $crud->add_action('Establecer como principal', URL_IMG . 'app/check.png', "usuarios/set_direccion_default/{$usuario_id}");
        
        $crud->where('usuario_id', $usuario_id);
        $crud->where('post.tipo_id', 1001);  //Ver Metadatos

        //Títulos de los campos
            $crud->display_as('nombre_post', 'Nombre dirección (Ej. Mi oficina, etc.)');
            $crud->display_as('texto_1', 'Dirección');
            $crud->display_as('texto_2', 'Detalle dirección (Ej. Barrio, Conjunto, etc.)');
            $crud->display_as('texto_3', 'Teléfono');
            $crud->display_as('referente_1_id', 'Cuidad');
            $crud->display_as('referente_2_id', 'Principal');
        
        //Relaciones
            $crud->set_relation('referente_1_id', 'lugar', '{nombre_lugar} - {region}', 'tipo_id = 4 AND activo = 1');
            
        //Columna lista
            $crud->columns(
                    'nombre_post',
                    'referente_1_id',
                    'texto_1',
                    'texto_2',
                    'texto_3',
                    'referente_2_id'
                );

        //Formulario Edit
            $crud->edit_fields(
                    'nombre_post',
                    'referente_1_id',
                    'texto_1',
                    'texto_2',
                    'texto_3',
                    'usuario_id',
                    'editado'
                );

        //Formulario Add
            $crud->add_fields(
                    'nombre_post',
                    'referente_1_id',
                    'texto_1',
                    'texto_2',
                    'texto_3',
                    'usuario_id',
                    'editado',
                    'creado',
                    'tipo_id'
                );
            
        //Valores por defecto
            $crud->field_type('creado', 'hidden', date('Y-m-d H:i:s'));
            $crud->field_type('editado', 'hidden', date('Y-m-d H:i:s'));
            $crud->field_type('usuario_id', 'hidden', $usuario_id);
            $crud->field_type('tipo_id', 'hidden', 1001);   //Metadato dirección
            $crud->field_type('referente_2_id', 'dropdown', array(0 => '', 1 => '>> Principal <<'));

        //Reglas de validación
            $crud->required_fields('nombre_post', 'referente_1_id', 'texto_1');
        
        $output = $crud->render();
        
        return $output;
    }

// PEDIDOS DEL USUARIO
//-----------------------------------------------------------------------------
    
    function pedidos($usuario_id)
    {
        $this->load->model('Search_model');
        $this->load->model('Pedido_model');
        $filters = $this->Search_model->filters();
        $filters['u'] = $usuario_id;
        
        $pedidos = $this->Pedido_model->search($filters);
        
        return $pedidos;
    }

// CONTENIDOS VIRUTALES ASIGNADOS
//-----------------------------------------------------------------------------

    /**
     * Contenidos digitales asignados a un usuario
     */
    function assigned_posts($user_id)
    {
        $this->db->select('post.id, nombre_post AS title, code, slug, resumen, post.estado, publicado, meta.id AS meta_id, imagen_id, meta.texto_1 AS format');
        $this->db->join('meta', 'post.id = meta.relacionado_id');
        $this->db->where('meta.dato_id', 100012);   //Asignación de contenido
        $this->db->where('meta.elemento_id', $user_id);
        $this->db->order_by('post.id', 'DESC');
        //$this->db->order_by('post.', 'DESC');

        $posts = $this->db->get('post');
        
        return $posts;
    }
    
}
