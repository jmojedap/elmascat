<?php
class Usuario_model extends CI_Model{
    
    //Devuelve un arra con los datos del usuario
    function basico($usuario_id)
    {
        $basico = array();
        
        $this->db->where('id', $usuario_id);
        $query = $this->db->get('usuario');
        
        if( $query->num_rows() > 0 )
        {
            $row = $query->row();
            
            $basico['nombre_completo'] = $row->nombre . " " . $row->apellidos;
            $basico['titulo_pagina'] = $row->nombre . " " . $row->apellidos;
            $basico['vista_a'] = 'usuarios/roles/cliente_v';
            $basico['row'] = $row;            
        }
        
        return $basico;
    }
    
    function buscar($busqueda, $per_page = NULL, $offset = NULL)
    {
        
        $filtro_rol = $this->filtro_rol($this->session->userdata('usuario_id'));

        //Construir búsqueda
        //Crear array con términos de búsqueda
            if ( strlen($busqueda['q']) > 2 )
            {
                $concat_campos = $this->Busqueda_model->concat_campos(array('nombre', 'apellidos', 'username', 'no_documento', 'email'));
                $palabras = $this->Busqueda_model->palabras($busqueda['q']);

                foreach ($palabras as $palabra) {
                    $this->db->like("CONCAT({$concat_campos})", $palabra);
                }
            }
        
        //Especificaciones de consulta
            $this->db->where($filtro_rol); //Filtro según el rol de usuario que se tenga
            $this->db->order_by('editado', 'DESC');
            
        //Otros filtros
            if ( $busqueda['rol'] != '' ) { $this->db->where('rol_id', $busqueda['rol']); }    //Rol de usuario
            if ( $busqueda['sexo'] != '' ) { $this->db->where('sexo', $busqueda['sexo']); }    //Sexo
            
        //Obtener resultados
        if ( is_null($per_page) ){
            $query = $this->db->get('usuario'); //Resultados totales
        } else {
            $query = $this->db->get('usuario', $per_page, $offset); //Resultados por página
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
     * Devuelve segmento SQL
     * 
     * @param type $usuario_id
     * @return type 
     */
    function filtro_rol()
    {
        
        $rol_id = $this->session->userdata('rol_id');
        $condicion = 'id = 0';  //Valor por defecto, ningún usuario, se obtendrían cero usuarios.
        
        if ( $rol_id == 0 ) {
            //Desarrollador, todos los usuarios
            $condicion = 'id > 0';
        } else  {
            $condicion = 'id > 0';
        }
        
        
        return $condicion;
        
    }
    
    /**
     * Array con los datos para la vista de exploración
     * 
     * @return string
     */
    function data_explorar()
    {
        //Elemento de exploración
            $data['elemento_p'] = 'usuarios';    //Nombre del elemento el plural
            $data['elemento_s'] = 'usuario';      //Nombre del elemento en singular
            $data['controlador'] = 'usuarios';   //Nombre del controlador
            $data['funcion'] = 'explorar';      //Función de exploración
            $data['cf'] = $data['controlador'] . '/' . $data['funcion'] . '/';      //Controlador función
            $data['carpeta_vistas'] = 'usuarios/';   //Carpeta donde están las vistas de exploración
            $data['titulo_pagina'] = 'Usuarios';
        
        //Paginación
            $data['per_page'] = 20; //Cantidad de registros por página
            $data['offset'] = $this->input->get('per_page');
            
        //Búsqueda y Resultados
            $this->load->model('Busqueda_model');
            $data['busqueda'] = $this->Busqueda_model->busqueda_array();
            $data['busqueda_str'] = $this->Busqueda_model->busqueda_str();
            $data['resultados'] = $this->Usuario_model->buscar($data['busqueda'], $data['per_page'], $data['offset']);    //Resultados para página
            $data['cant_resultados'] = $this->Usuario_model->cant_resultados($data['busqueda']);
            
        //Otros
            $data['url_paginacion'] = base_url("{$data['cf']}?{$data['busqueda_str']}");
            $data['seleccionados_todos'] = '-'. $this->Pcrn->query_to_str($data['resultados'], 'id');   //Para selección masiva de todos los elementos de la página

        //Visitas
            $data['subtitulo_pagina'] = $data['cant_resultados'];
            $data['vista_a'] = $data['carpeta_vistas'] . 'explorar/explorar_v';
            $data['vista_menu'] = $data['carpeta_vistas'] . 'explorar/menu_v';
        
        return $data;
    }
    
    function editable()
    {
        return TRUE;
    }
    
    /** 
     * Devuelve render de formulario grocery crud para la edición
     * de datos de usuario
     */
    function crud_basico()
    {
        //Grocery crud
        $this->load->library('grocery_CRUD');
        
        $crud = new grocery_CRUD();
        $crud->set_table('usuario');
        $crud->set_subject('usuario');
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_back_to_list();
        $crud->unset_delete();
        $crud->unset_add();
        $crud->columns('nombre');

        //Títulos de los campos
            $crud->display_as('nombre', 'Nombre');
            $crud->display_as('email', 'Correo electrónico');
            //$crud->display_as('rol_id', 'Perfil');
            $crud->display_as('no_documento', 'CC o NIT');
            $crud->display_as('tipo_documento_id', 'Tipo documento');
            $crud->display_as('dv', 'DV (Dígito verificación)');
            $crud->display_as('direccion', 'Dirección');
            $crud->display_as('url', 'Página Web');
            $crud->display_as('ciudad_id', 'Ciudad Residencia');
        
        //Relaciones
            $crud->set_relation('ciudad_id', 'lugar', '{nombre_lugar} - {region}', 'tipo_id = 4');

        //Formulario Edit
            $crud->edit_fields(
                    'nombre',
                    'apellidos',
                    'tipo_documento_id',
                    'no_documento',
                    'dv',
                    'email',
                    'sexo',
                    'fecha_nacimiento',
                    'ciudad_id',
                    'telefono',
                    'celular',
                    'url',
                    'creado'
                );

        //Formulario Add
            $crud->add_fields(
                    'nombre',
                    'apellidos',
                    'tipo_documento_id',
                    'no_documento',
                    'dv',
                    'username',
                    'email',
                    'password',
                    'sexo',
                    'fecha_nacimiento',
                    'ciudad_id',
                    'telefono',
                    'celular',
                    'url',
                    'creado'
                );
            
        //Opciones rol
            $opciones_rol = $this->Item_model->arr_interno("categoria_id = 58 AND id_interno >= {$this->session->userdata('rol_id')}");
            $crud->field_type('rol_id', 'dropdown', $opciones_rol);
            
        //Sexo
            $opciones_sexo = $this->Item_model->opciones("categoria_id = 59 AND item_grupo = 1");
            $crud->field_type('sexo', 'dropdown', $opciones_sexo);
            
        //Tipo documento
            $opciones_tp_documento = $this->Item_model->opciones("categoria_id = 53");
            $crud->field_type('tipo_documento_id', 'dropdown', $opciones_tp_documento);
            
        //Valores por defecto
            $crud->field_type('creado', 'hidden', date('Y-m-d H:i:s'));
            
        //Procesos
            $crud->callback_after_insert(array($this, 'gc_after_add'));

        //Reglas de validación
            $crud->required_fields('nombre', 'apellidos', 'username', 'password');
            $crud->unique_fields('email', 'no_documento');
            $crud->set_rules('celular', 'Celular', 'integer|greater_than[3000000000]|less_than[3300000000]');
            $crud->set_rules('email', 'Correo electrónico', 'valid_email');
        
        $output = $crud->render();
        
        return $output;
    }
    
    /** 
     * Devuelve render de formulario grocery crud para la edición
     * de datos de usuario
     */
    function crud_admin()
    {
        //Grocery crud
        $this->load->library('grocery_CRUD');
        
        $crud = new grocery_CRUD();
        $crud->set_table('usuario');
        $crud->set_subject('usuario');
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_back_to_list();
        $crud->unset_delete();
        $crud->unset_add();
        $crud->columns('nombre');

        //Títulos de los campos
            $crud->display_as('nombre', 'Nombre');
            $crud->display_as('email', 'Correo electrónico');
            $crud->display_as('rol_id', 'Rol');
            $crud->display_as('no_documento', 'CC o NIT');
            $crud->display_as('tipo_documento_id', 'Tipo documento');
            $crud->display_as('dv', 'DV (Dígito verificación)');
            $crud->display_as('direccion', 'Dirección');
            $crud->display_as('url', 'Página Web');
            $crud->display_as('ciudad_id', 'Ciudad Residencia');
        
        //Relaciones
            $crud->set_relation('ciudad_id', 'lugar', '{nombre_lugar} - {region}', 'tipo_id = 4');

        //Formulario Edit
            $crud->edit_fields(
                    'nombre',
                    'apellidos',
                    'rol_id',
                    'tipo_documento_id',
                    'no_documento',
                    'dv',
                    'email',
                    'sexo',
                    'fecha_nacimiento',
                    'ciudad_id',
                    'telefono',
                    'celular',
                    'url'
                );

        //Formulario Add
            $crud->add_fields(
                    'nombre',
                    'apellidos',
                    'rol_id',
                    'tipo_documento_id',
                    'no_documento',
                    'dv',
                    'username',
                    'email',
                    'password',
                    'sexo',
                    'fecha_nacimiento',
                    'ciudad_id',
                    'telefono',
                    'celular',
                    'url'
                );
            
        //Array opciones
            $opciones_rol = $this->Item_model->arr_interno("categoria_id = 58 AND id_interno >= {$this->session->userdata('rol_id')}");
            $crud->field_type('rol_id', 'dropdown', $opciones_rol);
            
        //Sexo
            $opciones_sexo = $this->Item_model->opciones("categoria_id = 59 AND item_grupo = 1");
            $crud->field_type('sexo', 'dropdown', $opciones_sexo);
            
        //Tipo documento
            $opciones_tp_documento = $this->Item_model->opciones("categoria_id = 53");
            $crud->field_type('tipo_documento_id', 'dropdown', $opciones_tp_documento);
            
        //Valores por defecto
            $crud->field_type('creado', 'hidden', date('Y-m-d H:i:s'));
            
        //Procesos
            $crud->callback_after_insert(array($this, 'gc_after_add'));

        //Reglas de validación
            $crud->required_fields('nombre', 'apellidos', 'username', 'password');
            $crud->unique_fields('email');
            $crud->set_rules('celular', 'Celular', 'integer|greater_than[3000000000]|less_than[3300000000]');
            $crud->set_rules('email', 'Correo electrónico', 'valid_email');
        
        $output = $crud->render();
        
        return $output;
    }
    
    function gc_after_add($post_array,$primary_key)
    {
        $registro['password'] = $this->encriptar_pw($post_array['password']);

        $this->db->where('id', $primary_key);
        $this->db->update('usuario', $registro);

        return true;
    }
    
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
    
    function guardar($registro)
    {
        $registro['nombre'] = $this->input->post('nombre');
        $registro['apellidos'] = $this->input->post('apellidos');
        $registro['email'] = $this->input->post('email');
        $registro['celular'] = $this->input->post('celular');
        $registro['username'] = $this->input->post('email');
        $registro['creado'] = date('Y-m-d H:i:s');
        $registro['editado'] = date('Y-m-d H:i:s');
        
        $usuario_id = $this->Pcrn->insertar_si('usuario', "email = '{$registro['email']}'", $registro);
        
        return $usuario_id;
    }
    
    /**
     * Después de validar el formulario de registro de usuario, se lo guarda
     * en la tabla.
     * 
     * @param type $registro
     * @return type
     */
    function insertar($registro)
    {
        //Encriptar pw
            $registro['password'] = $this->encriptar_pw($registro['password']);
        
        //Datos complementarios
            $registro['editado'] = date('Y-m-d h:i:s');
            $registro['creado'] = date('Y-m-d h:i:s');
        
        $this->db->insert('usuario', $registro);
        
        $resultado['nuevo_id'] = $this->db->insert_id();
        $resultado['ejecutado'] = 1;
        
        return $resultado;
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
            $subject = 'Activar cuenta en Districatólicas Unidas S.A.S.';
            if ( $tipo_activacion == 'restaurar' ) {
                $subject = 'Restaurar contraseña en Districatólicas Unidas S.A.S.';
            }
        
        //Enviar Email
            $this->load->library('email');
            $config['mailtype'] = 'html';

            $this->email->initialize($config);
            $this->email->from('info@districatolicas.com', 'Districatólicas Unidas S.A.S.');
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
    
    function eliminable()
    {
        $eliminable = 0;
        if ( $this->session->userdata('usuario_id') <= 1 ) {
            $eliminable = 1;
        }

        return $eliminable;
    }
    
    function eliminar($usuario_id)
    {
        if ( $this->eliminable($usuario_id) ) {
            //Tablas relacionadas
                
                //meta
                $this->db->where('tabla_id', 1000); //Tabla usuario
                $this->db->where('elemento_id', $usuario_id);
                $this->db->delete('meta');
            
            //Tabla principal
                $this->db->where('id', $usuario_id);
                $this->db->delete('usuario');
        }
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
     * 
     * @param type $post_id
     * @return type
     */
    function arr_direccion($post_id = NULL)
    {
        //Variables iniciales
            $arr_direccion['ciudad_id'] = '';
        
        //Calcular valores si se identifica usuario y dirección (post)
            $usuario_id = $this->session->userdata('usuario_id');
            $direcciones = $this->direcciones($usuario_id, $post_id);

            if ( $direcciones->num_rows() > 0 ) 
            {
                $row_direccion = $direcciones->row();
                $row_lugar = $this->Pcrn->registro_id('lugar', $row_direccion->lugar_id);

                $arr_direccion['ciudad_id'] = $row_direccion->lugar_id;
                $arr_direccion['direccion'] = $row_direccion->direccion;
                $arr_direccion['direccion_detalle'] = $row_direccion->direccion_detalle;
                $arr_direccion['telefono'] = $row_direccion->telefono;
                
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
        $this->load->model('Busqueda_model');
        $this->load->model('Pedido_model');
        $busqueda = $this->Busqueda_model->busqueda_array();
        $busqueda['condicion'] = "usuario_id = {$usuario_id}";
        
        $pedidos = $this->Pedido_model->buscar($busqueda);
        
        return $pedidos;
    }
    
}
