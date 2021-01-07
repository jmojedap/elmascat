<?php
class Pedido_Model extends CI_Model{
    
    function basico($pedido_id)
    {
        
        $this->db->where('id', $pedido_id);
        $query = $this->db->get('pedido');
        
        $row = $query->row();
        
        $basico['row'] = $row;
        $basico['head_title'] = $row->cod_pedido;
        $basico['nav_2'] = 'pedidos/menu_v';
        
        return $basico;
    }

// EXPLORE FUNCTIONS - pedidos/explorar
//-----------------------------------------------------------------------------
    
    /**
     * Array con los datos para la vista de exploración
     */
    function explore_data($filters, $num_page)
    {
        //Data inicial, de la tabla
            $data = $this->get($filters, $num_page);
        
        //Elemento de exploración
            $data['controller'] = 'pedidos';                      //Nombre del controlador
            $data['cf'] = 'pedidos/explore/';                      //Nombre del controlador
            $data['views_folder'] = 'pedidos/explore/';           //Carpeta donde están las vistas de exploración
            
        //Vistas
            $data['head_title'] = 'Pedidos';
            $data['head_subtitle'] = $data['search_num_rows'];
            $data['view_a'] = $data['views_folder'] . 'explore_v';
            //$data['nav_2'] = $data['views_folder'] . 'menu_v';
        
        return $data;
    }

    function get($filters, $num_page)
    {
        //Referencia
            $per_page = 12;                             //Cantidad de registros por página
            $offset = ($num_page - 1) * $per_page;      //Número de la página de datos que se está consultado

        //Búsqueda y Resultados
            $data['filters'] = $filters;
            //$elements = $this->search($data['filters'], $per_page, $offset);    //Resultados para página
            $list = $this->list($filters, $per_page, $offset);
        
        //Cargar datos
            $data['list'] = $list;
            $data['str_filters'] = $this->Search_model->str_filters();
            $data['search_num_rows'] = $this->search_num_rows($data['filters']);
            $data['max_page'] = ceil($this->pml->if_zero($data['search_num_rows'],1) / $per_page);   //Cantidad de páginas

        return $data;
    }

    /**
     * Array Listado elemento resultado de la búsqueda (filtros).
     * 2020-01-21
     */
    function list($filters, $per_page = NULL, $offset = NULL)
    {
        $query = $this->search($filters, $per_page, $offset);
        $list = array();

        foreach ($query->result() as $row)
        {
            $condicion = "dato_id = 3005 AND elemento_id = {$row->id}";
            $confirmacion = $this->confirmacion($row->id);
            if ( ! is_null($confirmacion) )
            {
                $row->payu_mensaje_respuesta_pol = $confirmacion->mensaje_respuesta_pol;
                $row->payu_codigo_respuesta_pol = $confirmacion->codigo_respuesta_pol;
                $row->payu_firma = $confirmacion->firma;
                $row->firma_pol_confirmacion = $this->firma_pol_confirmacion($row->id, $confirmacion->estado_pol);
            } else {
                $row->payu_mensaje_respuesta_pol = '';
                $row->payu_codigo_respuesta_pol = 0;
                $row->payu_firma = 0;
                $row->firma_pol_confirmacion = 0;
            }
            //$row->confirmacion = $this->Pedido_model->confirmacion($row->id);

            $list[] = $row;
        }

        return $list;
    }
    
    /**
     * Query con resultados de posts filtrados, por página y offset
     * 2020-07-15
     */
    function search($filters, $per_page = NULL, $offset = NULL)
    {
        //Construir consulta
            $this->db->select('pedido.id, cod_pedido, pedido.usuario_id, pedido.nombre, pedido.apellidos, pedido.email, estado_pedido, valor_total, pedido.direccion, pedido.ciudad, pedido.celular, pedido.peso_total, pedido.editado, editado_usuario_id, factura, no_guia, codigo_respuesta_pol, usuario.username AS updater_username, is_gift');
            //$this->db->join('lugar', 'pedido.lugar_id = lugar.id', 'left');
            $this->db->join('usuario', 'pedido.editado_usuario_id = usuario.id', 'left');
        
        //Orden
            if ( $filters['o'] != '' )
            {
                $order_type = $this->pml->if_strlen($filters['ot'], 'ASC');
                $this->db->order_by($filters['o'], $order_type);
            } else {
                $this->db->order_by('id', 'DESC');
            }
            
        //Filtros
            $search_condition = $this->search_condition($filters);
            if ( $search_condition ) { $this->db->where($search_condition);}
            
        //Obtener resultados
            $query = $this->db->get('pedido', $per_page, $offset); //Resultados por página
        
        return $query;
        
    }

    /**
     * String con condición WHERE SQL para filtrar post
     * 2020-08-01
     */
    function search_condition($filters)
    {
        $condition = NULL;

        $condition .= $this->role_filter() . ' AND ';

        //q words condition
        $words_condition = $this->Search_model->words_condition($filters['q'], array('pedido.nombre', 'pedido.apellidos', 'pedido.email', 'cod_pedido', 'factura'));
        if ( $words_condition )
        {
            $condition .= $words_condition . ' AND ';
        }
        
        //Otros filtros
        if ( $filters['status'] != '' ) { $condition .= "estado_pedido = {$filters['status']} AND "; }          //Por estado
        //Filtro por peso
        if ( $filters['fe1'] != '' )
        {
            if ( $filters['fe1'] == 0 ) { $condition .= "peso_total = 0 AND "; }                                    //Sin peso
            if ( $filters['fe1'] == 1 ) { $condition .= "peso_total > 0 AND "; }                                    //Con peso
        }
        //Filtro por Código de Respuesta POL
        if ( $filters['fe2'] != '' )
        {
            if ( $filters['fe2'] == 1 ) { $condition .= "codigo_respuesta_pol = 1 AND "; }                                    
            if ( $filters['fe2'] == 2 ) { $condition .= "codigo_respuesta_pol > 1 AND "; }                                    
        }
        if ( $filters['d1'] != '' ) { $condition .= "pedido.creado >= '{$filters['d1']} 00:00:00' AND "; }             //Creado a partir de
        if ( $filters['u'] != '' ) { $condition .= "pedido.usuario_id = {$filters['u']} AND "; }                       //Cliente User ID
        
        
        //Quitar cadena final de ' AND '
        if ( strlen($condition) > 0 ) { $condition = substr($condition, 0, -5);}
        
        return $condition;
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
        $query = $this->db->get('pedido'); //Para calcular el total de resultados

        return $query->num_rows();
    }
    
    /**
     * Devuelve segmento SQL
     */
    function role_filter()
    {
        $row_user = $this->Db_model->row_id('usuario', $this->session->userdata('user_id'));
        $condition = 'id = 0';  //Valor por defecto, ninguna institución, se obtendrían cero pedidos.
        
        if ( $row_user->rol_id <= 10 ) {
            //Usuarios internos
            $condition = 'pedido.id > 0';
        } else {
            $condition = "pedido.usuario_id = {$this->session->userdata('user_id')}";
        }
        
        return $condition;
    }
    
    /**
     * Array con options para ordenar el listado de post en la vista de
     * exploración
     */
    function order_options()
    {
        $order_options = array(
            '' => '[ Ordenar por ]',
            'id' => 'ID Pedido',
            'creado' => 'Fecha de creación'
        );
        
        return $order_options;
    }
    
    function eliminar($pedido_id)
    {
        if ( $this->eliminable($pedido_id) ) 
        {
            //Tablas relacionadas
            
                //pedido_detalle
                $this->db->where('pedido_id', $pedido_id);
                $this->db->delete('pedido_detalle');
                
                //meta
                $this->db->where('tabla_id', 3000); //Tabla pedido
                $this->db->where('elemento_id', $pedido_id);
                $this->db->delete('meta');
            
            //Tabla principal
                $this->db->where('id', $pedido_id);
                $this->db->delete('pedido');
        }
    }

// Administración
//-----------------------------------------------------------------------------
    
    /**
     * Actualizar registro de pedido, datos de administración.
     * 2020-04-28
     */
    function guardar_admin($pedido_id)
    {   
        //Resultado del proceso, valor por defecto
            $data = array('status' => 0, 'message' => 'El pedido NO se modificó');
        
        //Actualizar registro
            $arr_row = $this->input->post();
            $arr_row['editado_usuario_id'] = $this->session->userdata('usuario_id');
            $this->db->where('id', $pedido_id);
            $this->db->update('pedido', $arr_row);
            
        if ( $this->db->affected_rows() > 0 ) 
        {
            $data['status'] = 1;
            $data['message'] = 'Pedido Actualizado';

            //Enviar email, si corresponde a un estado de interés comercial
            if ( in_array($arr_row['estado_pedido'], array(4,6)) )
            {
                $this->Pedido_model->email_actualizacion($pedido_id);    
                $data['message'] .= '. E-mail enviado al cliente.';
            }
        }
        
        return $data;
    }
    
//DATOS SOBRE UN PEDIDO
//---------------------------------------------------------------------------------------------------
    
    /**
     * Devuelve el código de un pedido
     * @return string
     */
    function cod_pedido()
    {
        //Definido por GET
        $cod_pedido = $this->input->get('cod_pedido');
        
        //Si no está en GET, se define por post
        if ( strlen($cod_pedido) == 0 ) 
        { 
            $cod_pedido = $this->input->post('cod_pedido');
        }
        
        //Si no está por ninguno, se devuelve cadena vacía
        if ( strlen($cod_pedido) == 0 ) 
        { 
            $cod_pedido = '';
        }
        
        return $cod_pedido;
    }
    
    /**
     * Devuelve el detalle de los productos de un pedido, asociado en la tabla pedido_detalle
     */
    function detalle($pedido_id)
    {
        $this->db->select('pedido_detalle.*, producto.nombre_producto, producto.peso, producto.fabricante_id');
        $this->db->where('pedido_id', $pedido_id);
        $this->db->where('pedido_detalle.tipo_id', 1); //Productos
        $this->db->join('producto', 'producto.id = pedido_detalle.producto_id');
        $this->db->order_by('producto_id', 'ASC');
        $query = $this->db->get('pedido_detalle');
        
        return $query;
    }
    
    /**
     * Devuelve el detalle de un pedido, que no son productos, elementos extras
     * Descuentos, gastos de envío, etc.
     */
    function extras($pedido_id)
    {
        $this->db->select('pedido_detalle.*');
        $this->db->where('pedido_id', $pedido_id);
        $this->db->where('tipo_id', 2); //Elmentos extras del pedido
        $this->db->order_by('producto_id', 'ASC');
        $query = $this->db->get('pedido_detalle');
        
        return $query;
    }
    
    /**
     * Calcula el valor en dinero, para un pedido específico, de los valores extras
     * cobrados, con alguna condición (WHERE) específica
     */
    function valor_extras($pedido_id, $condicion = NULL)
    {
        $valor_extras = 0;
        
        $this->db->select('SUM(precio * cantidad) as sum_total');
        $this->db->where('pedido_id', $pedido_id);
        $this->db->where('tipo_id', 2);     //Detalle tipo 2, extra
        if ( ! is_null($condicion) ) { $this->db->where($condicion); }
        
        $query = $this->db->get('pedido_detalle');
        
        if ( $query->num_rows() > 0 ) { $valor_extras = $query->row()->sum_total; }
        
        return $valor_extras;
    }
    
    /**
     * Cantidad de productos que tiene un pedido
     */
    function cant_productos($pedido_id)
    {
        $condicion = "pedido_id = {$pedido_id} AND tipo_id = 1";
        $cant_productos = $this->Pcrn->num_registros('pedido_detalle', $condicion);
        
        return $cant_productos;
    }

    /**
     * Array con valores del campo pedido.meta
     * 2020-12-12
     */
    function arr_meta($row)
    {
        $regalo = array('de' => '', 'para' => '', 'mensaje' => '');
        $arr_meta = array('regalo' => $regalo);
        if ( strlen($row->meta) ) { $arr_meta = json_decode($row->meta); }

        return $arr_meta;
    }
    
    /**
     * Guarda un registro en la tabla pedido_detalle (pd)
     */
    function guardar_detalle($registro)
    {
        //ID inicial, por defecto
            $pd_id = 0;
            
        //Identificar producto
            $this->load->model('Producto_model');
            $row_producto = $this->Pcrn->registro_id('producto', $registro['producto_id']);
            
        //Array de precio, tipo de precio aplicable y valor
            $arr_precio = $this->Producto_model->arr_precio($row_producto->id);
        
        //Construir registro
            $registro['pedido_id'] = $this->session->userdata('pedido_id');
            $registro['precio'] = $arr_precio['precio'];
            $registro['promocion_id'] = $arr_precio['promocion_id'];
            $registro['precio_nominal'] = $row_producto->precio;
            $registro['costo'] = $row_producto->costo;
            $registro['iva'] = $arr_precio['precio'] - ( $arr_precio['precio'] / ((100+$row_producto->iva_porcentaje)/100) );
            
        //Limitar cantidad
            $registro['cantidad'] = $this->Pcrn->limitar_entre($registro['cantidad'], 1, $row_producto->cant_disponibles);
        
        //Condición
            $condicion = "pedido_id = {$registro['pedido_id']} AND producto_id = {$registro['producto_id']}";
            
            if ( $registro['cantidad'] > 0 ) 
            {
                $pd_id = $this->Pcrn->guardar('pedido_detalle', $condicion, $registro);
            }
        
        //Actualizar totales
            $this->act_totales($registro['pedido_id']);
            
        //Actualizar variable de sesión, para info carrito de compras
            $cant_productos = $this->cant_productos($registro['pedido_id']);
            $this->session->set_userdata('cant_productos', $cant_productos);
            
        return $pd_id;
    }
    
    /**
     * Elimina un registro de la tabla pedido_detalle
     * @param type $pd_id
     */
    function eliminar_detalle($pd_id)
    {
        //Eliminar
            $this->db->where('pedido_id', $this->session->userdata('pedido_id'));  //Medida de seguridad, debe tenerse los dos datos
            $this->db->where('id', $pd_id);
            $this->db->delete('pedido_detalle');
            
        //
        
        //Actualizar variable de sesión
            $pedido_id = $this->session->userdata('pedido_id');
            $cant_productos = $cant_productos = $this->cant_productos($pedido_id);
            $this->session->set_userdata('cant_productos', $cant_productos);
    }
    
    /**
     * Listado de los descuentos que se aplican en un pedido o conjunto de pedidos
     * también se calcula el valor del descuento.
     * 
     * @param type $pedido_id
     * @param type $condicion
     * @return type
     */
    function descuentos($pedido_id = NULL, $condicion = NULL)
    {
        $this->db->select('promocion_id, SUM((precio_nominal-precio)*cantidad) as sum_descuento');
        if ( ! is_null($pedido_id) ) { $this->db->where('pedido_id', $pedido_id); }
        if ( ! is_null($condicion) ) { $this->db->where($condicion); }
        $this->db->where('precio < precio_nominal');
        $this->db->order_by('promocion_id', 'ASC');
        $this->db->group_by('promocion_id');
        $descuentos = $this->db->get('pedido_detalle');
        
        return $descuentos;
    }

    /**
     * Revisa que haya disponibilidad de los productos incluidos en un pedido
     * Compara producto.cant_disponibles >= pedido_detalle.cantidad
     * 2020-12-26
     */
    function verificar_existencias($pedido_id)
    {
        $productos = $this->detalle($pedido_id);
        $data = array('status' => 1, 'error' => '');

        foreach ($productos->result() as $row_detalle)
        {
            $producto = $this->Db_model->row_id('producto', $row_detalle->producto_id);
            if ( $row_detalle->cantidad > $producto->cant_disponibles )
            {
                $data['error'] .= 'El producto: "' . $producto->nombre_producto . '" ya no está disponible en la cantidad requerida. ';
            }
        }

        if ( strlen($data['error']) > 0 ) $data['status'] = 0;

        return $data;
    }

//FORMATO
//---------------------------------------------------------------------------------------------------
    
    /**
     * Array con clases bootstrap según el estado del pedido
     */
    function clases_estado() 
    {
        $query = $this->db->get_where('item', 'categoria_id = 7');
        $clases_estado = $this->Pcrn->query_to_array($query, 'item_corto', 'id_interno');
        return $clases_estado;
    }

//METADATOS
//---------------------------------------------------------------------------------------------------
    
    function guardar_meta_valor($pedido_id, $registro)
    {
        //Construir registro
            $registro['tabla_id'] = 2;  //Tabla pedido
            $registro['elemento_id'] = $pedido_id;
            
        //Condicion
            $condicion = "tabla_id = {$registro['tabla_id']} AND ";
            $condicion .= "elemento_id = {$registro['elemento_id']} AND ";
            $condicion .= "valor = '{$registro['valor']}' AND ";
            $condicion .= "dato_id = {$registro['dato_id']}";
            
        //Guardando
            $meta_id = $this->Pcrn->insertar_si('meta', $condicion, $registro);
            
        return $meta_id;
    }

    function guardar_datos_regalo($pedido_id)
    {
        $row = $this->Db_model->row_id('pedido', $pedido_id);
        $arr_meta = (array) $this->arr_meta($row);

        $arr_meta['regalo']['de'] = $this->input->post('regalo_de');
        $arr_meta['regalo']['para'] = $this->input->post('regalo_para');
        $arr_meta['regalo']['mensaje'] = $this->input->post('regalo_mensaje');

        $arr_row['meta'] = json_encode($arr_meta);

        $this->db->where('id', $pedido_id);
        $this->db->update('pedido', $arr_row);

        $data = array('status' => 1, 'message' => $arr_meta['regalo']);
        
        return $data;
    }

//DATOS
//---------------------------------------------------------------------------------------------------
    
    /**
     * Row, de un pedido dado el calor pedido.cod_pedido
     * 2020-08-12
     */
    function row_cod_pedido($cod_pedido) 
    {
        $row = $this->Pcrn->registro('pedido', "cod_pedido = '{$cod_pedido}'");
        return $row;
    }
    
    function rol_comprador($pedido_id)
    {
        $row = $this->Pcrn->registro_id('pedido', $pedido_id);
        $rol_id = NULL;    //Valor por defecto, sin rol
        
        if ( $row->usuario_id != 0 ) 
        {
            $row_usuario = $this->Pcrn->registro_id('usuario', $row->usuario_id);
            $rol_id = $row_usuario->rol_id;
        }
        
        return $rol_id;
    }
    
//PROCESOS
//---------------------------------------------------------------------------------------------------
    
    /**
     * Crea el registro de un pedido
     * 2020-12-03
     */
    function crear()
    {    
        //Construir registro
            $registro['pais_id'] = 51; //Colombia, por defecto, ver lugar.id
            $registro['region_id'] = 267; //Bogotá D.C., por defecto, ver lugar.id
            $registro['estado_pedido'] = 1; //Iniciado
            $registro['creado'] = date('Y-m-d H:i:s');
            $registro['editado'] = date('Y-m-d H:i:s');
            $registro['session_id'] = session_id();
        
        //Crear registro
            $this->db->insert('pedido', $registro);
            $pedido_id = $this->db->insert_id();
            
        //Procesos
            $this->act_cod_pedido($pedido_id);      //Establecer cod_pedido, código identificador de pedido
            $this->set_datos_usuario($pedido_id);   //Establecer datos personales del comprador
            $this->set_direccion($pedido_id);       //Establecer dirección, por defecto
            $this->set_ip_address($pedido_id);      //Metadato, dirección IP desde la que se creó el pedido
            
        //Cargar dato de pedido en variable de sesión de usuario    
            $this->session->set_userdata('pedido_id', $pedido_id);
        
        return $pedido_id;
    }
    
    /**
     * Si el usuario comprador está registrado establece sus datos al pedido.
     * 
     * @param type $pedido_id
     */
    function set_datos_usuario($pedido_id)
    {
        if ( $this->session->userdata('logged') ) 
        {
            $usuario_id = $this->session->userdata('usuario_id');
            $row_usuario = $this->Pcrn->registro_id('usuario', $usuario_id);

            $registro['nombre'] = $row_usuario->nombre;
            $registro['apellidos'] = $row_usuario->apellidos;
            $registro['no_documento'] = $row_usuario->no_documento;
            $registro['email'] = $row_usuario->email;
            $registro['telefono'] = $row_usuario->telefono;
            $registro['celular'] = $row_usuario->celular;
            $registro['usuario_id'] = $row_usuario->id;
            
            $this->db->where('id', $pedido_id);
            $this->db->update('pedido', $registro);
        }
    }
    
    /**
     * Actualiza el campo pedido.cod_pedido, con el formato definido
     * 2020-08-12
     */
    function act_cod_pedido($pedido_id)
    {
        $this->load->helper('string');
        
        $registro['cod_pedido'] = 'DC' . strtoupper(random_string('alpha', 2)) . '-' . $pedido_id;
        
        $this->db->where('id', $pedido_id);
        $this->db->update('pedido', $registro);

        return $this->db->affected_rows();
    }
    
    /**
     * Establece la dirección de entrega de un pedido, conociendo el usuario y 
     * la dirección (post_id) registrada en la tabla post
     */
    function set_direccion($pedido_id, $post_id = NULL)
    {
        if ( $this->session->userdata('logged') == TRUE ) 
        {
            $this->load->model('Usuario_model');
            $arr_direccion = $this->Usuario_model->arr_direccion($post_id);

            $this->db->where('id', $pedido_id);
            $this->db->update('pedido', $arr_direccion);
        }
    }
    
    /**
     * Agregar a la tabla meta, el valor
     * @param type $pedido_id
     */
    function set_ip_address($pedido_id)
    {
        $this->load->model('Meta_model');
        
        $registro['tabla_id'] = 3000;
        $registro['elemento_id'] = $pedido_id;
        $registro['dato_id'] = 3011;    //Metadato, dirección IP
        $registro['valor'] = $this->input->ip_address();
        
        $meta_id = $this->Meta_model->guardar($registro, 1);
        
        return $meta_id;
    }
    
    /**
     * Abandona un pedido actual quitándolo de las variables de sesión
     * No elimina el pedido ni su detalle
     */
    function abandonar()
    {
        $this->session->unset_userdata('pedido_id');
        $this->session->unset_userdata('cant_productos');
    }
    
    /**
     * Retoma un pedido iniciado, lo carga en las variables de sesión para continuar
     * editando el pedido hasta finalizarlo.
     * 
     * @param type $cod_pedido
     * @return int
     */
    function retomar($cod_pedido)
    {
        $retomado = 0;
        
        $row = $this->row_cod_pedido($cod_pedido);
        $cant_productos = $this->cant_productos($row->id);
        
        if ( $row->estado_pedido == 1 ) {
            //Solo se puede retomar un pedido si está en estado 1 (Iniciado)
            $this->session->set_userdata('pedido_id', $row->id);
            $this->session->set_userdata('cant_productos', $cant_productos);
            
            $retomado = 1;
        }
        
        return $retomado;
    }
    
    /**
     * Actualiza los datos de un pedido, tabla pedido
     * 
     * @param type $pedido_id
     * @param array $registro
     */
    function act_pedido($pedido_id, $registro)
    {
        //Datos complementarios
            $registro['editado'] = date('Y-m-d H:i:s');
            
        //Actualizar
            $this->db->where('id', $pedido_id);
            $this->db->update('pedido', $registro);
            
        return $this->db->affected_rows();
    }
    
    /**
     * Calcula valores totales de los pedidos
     * @param type $pedido_id
     */
    function act_totales($pedido_id)
    {
        //Productos
        $this->act_totales_1($pedido_id);           //Valor productos y peso
        
        //Extras
        //$this->act_extras_relacionados($pedido_id); //Desactivado, 2017-07-11
        $this->act_flete($pedido_id);               //Costos de envío del pedido
        //$this->act_comision_pol($pedido_id);      //Actualizar comisión POL (DESACTIVADA 2020-04-16)
        $this->act_totales_2($pedido_id);           //Valor total extras
        
        //Productos + Extras
        $this->act_totales_3($pedido_id);           //Valor total
    }
    
    /**
     * Actualiza el detalle extra de descuento para distribuidor
     * @param type $pedido_id
     */
    function act_extras_relacionados($pedido_id)
    {
        $this->act_desc_distribuidor($pedido_id);   //Descuento para distribuidor
    }
    
    /**
     * Actualiza los totales: pedido.total_productos, pedido.total_iva, pedido.peso_total
     * @param type $pedido_id
     */
    function act_totales_1($pedido_id)
    {
     
        $total_productos = 0;
        $peso_total = 0;
        
        $this->db->select('SUM(pedido_detalle.precio * cantidad) AS total_productos, SUM(peso * cantidad) AS peso_total, SUM(pedido_detalle.iva * cantidad) AS total_iva');
        $this->db->where('pedido_id', $pedido_id);
        $this->db->where('pedido_detalle.tipo_id', 1);  //Productos
        $this->db->join('producto', 'producto.id = pedido_detalle.producto_id');
        $query = $this->db->get('pedido_detalle');
        
        if ($query->num_rows() > 0 ) 
        {
            $total_productos = $query->row()->total_productos;
            $total_iva = $query->row()->total_iva;
            $peso_total = $query->row()->peso_total;
        }
        
        
        $registro['total_productos'] = $total_productos;
        $registro['total_iva'] = $total_iva;
        $registro['peso_total'] = ceil($peso_total / 1000); //Convierte de gramos a Kg, y redondea hacia arriba
        
        $this->db->where('id', $pedido_id);
        $this->db->update('pedido', $registro);
    }
    
    /**
     * Actualiza los totales: pedido.total_extras
     * @param type $pedido_id
     */
    function act_totales_2($pedido_id)
    {
     
        $total_extras = 0;
        
        $this->db->select('SUM(pedido_detalle.precio * cantidad) AS total_extras');
        $this->db->where('pedido_id', $pedido_id);
        $this->db->where('pedido_detalle.tipo_id', 2);  //Extras
        $query = $this->db->get('pedido_detalle');
        
        if ($query->num_rows() > 0 ) {
            $total_extras = $query->row()->total_extras;
        }
        
        $registro['total_extras'] = $total_extras;
        
        $this->db->where('id', $pedido_id);
        $this->db->update('pedido', $registro);
    }
    
    /**
     * Actualiza los totales: pedido.valor_total
     * @param type $pedido_id
     */
    function act_totales_3($pedido_id)
    {
        $sql = "UPDATE pedido SET valor_total = total_productos + total_extras WHERE id = {$pedido_id}";
        $this->db->query($sql);
    }
    
    /**
     * Actualiza la tabla pedido, campos pais_id, region_id, ciudad_id y ciudad
     */
    function guardar_lugar()
    {
        $row_lugar = $this->Pcrn->registro_id('lugar', $this->input->post('ciudad_id'));
        
        if ( ! is_null($row_lugar) )
        {
            $registro['pais_id'] = $row_lugar->pais_id;
            $registro['region_id'] = $row_lugar->region_id;
            $registro['ciudad_id'] = $row_lugar->id;
            $registro['ciudad'] = $row_lugar->nombre_lugar . ' - ' . $row_lugar->region . ' - ' . $row_lugar->pais;
        }
        
        $pedido_id = $this->session->userdata('pedido_id');
        $this->db->where('id', $pedido_id);
        $this->db->update('pedido', $registro);
    }
    
    /**
     * Actualiza el valor del flete de un pedido
     * Agrega o edita el registro en la tabla pedido_detalle
     */
    function act_flete()
    {
        //Cargue
            $this->load->model('Flete_model');
            $pedido_id = $this->session->userdata('pedido_id');
            $row_pedido = $this->Pcrn->registro_id('pedido', $pedido_id);
        
        //Se actualiza si la ciudad ya está definida
        if ( ! is_null($row_pedido->ciudad_id) ) 
        {
            //Construir registro
                $registro['pedido_id'] = $this->session->userdata('pedido_id');
                $registro['producto_id'] = 1;   //COD 1, corresponde a flete, ver Ajustes > Parámetros > Extras pedidos
                $registro['tipo_id'] = 2;       //No es un producto (1), es un elemento extra (2)
                $registro['precio'] = $this->Flete_model->flete(909, $row_pedido->ciudad_id, $row_pedido->peso_total);
                $registro['cantidad'] = 1;  //Un envío
                $registro['costo'] = 0;     //No aplica
                $registro['iva'] = 0;       //No aplica

            //Condición
                $condicion = "pedido_id = {$pedido_id} AND producto_id = 1 AND tipo_id = 2";

            //Guardar
                $this->Pcrn->guardar('pedido_detalle', $condicion, $registro);
        }
    }

    /**
     * Verifica y valida usuario asociado a un pedido que no tiene usaurio_id identificado
     * 2020-04-17
     */
    function validate_user($pedido_id)
    {
        $row = $this->Db_model->row_id('pedido', $pedido_id);
        
        //No hay usuario definido
        if ( $row->usuario_id == 0 )
        {
            //Identificar usuario por el email
            $row_usuario = $this->Db_model->row('usuario', "email = '{$row->email}'");

            if ( ! is_null($row_usuario) )
            {
                //Usuario ya existe en la base de datos
                $arr_row['usuario_id'] = $row_usuario->id;
            } else {
                //No existe, crearlo y enviar email de activación
                $user['no_documento'] = $this->input->post('no_documento');
                $user['tipo_documento_id'] = $this->input->post('tipo_documento_id');
                $user['address'] = $this->input->post('direccion');
                $user['ciudad_id'] = $row->ciudad_id;
                $user['sexo'] = $this->input->post('sexo');
                $user['fecha_nacimiento'] = substr($this->input->post('year'),-4) . '-' . substr($this->input->post('month'),-2) . '-' .  substr($this->input->post('day'),-2);
                
                $this->load->model('Usuario_model');
                $arr_row['usuario_id'] = $this->Usuario_model->guardar($user);

                $this->Usuario_model->email_activacion($arr_row['usuario_id']);  //Envía mensaje email para activar cuenta de usuario
            }

            //Cargar usuario en variables de sesión
            $this->session->set_userdata('user_id', $arr_row['usuario_id']);

            //Se actualiza pedido.usuario_id
            $this->db->where('id', $row->id);
            $this->db->update('pedido', $arr_row);
        }
    }

    /**
     * Carga los datos del pedido al usuario, si no los tiene.
     * 2020-04-30
     */
    function set_user_data($pedido_id)
    {
        $data['saved_id'] = 0;  //Valor por defecto
        $row = $this->Db_model->row_id('pedido', $pedido_id);
        
        //Hay usuario definido
        if ( $row->usuario_id > 0 )
        {
            //Identificar usuario por el email
            $row_usuario = $this->Db_model->row_id('usuario', $row->usuario_id);

            $user = array();

            if ( strlen($row_usuario->nombre) == 0 ) { $user['nombre'] = $this->input->post('nombre'); }
            if ( strlen($row_usuario->apellidos) == 0 ) { $user['apellidos'] = $this->input->post('apellidos'); }
            if ( strlen($row_usuario->no_documento) == 0 ) { $user['no_documento'] = $this->input->post('no_documento'); }
            if ( strlen($row_usuario->tipo_documento_id) == 0 ) { $user['tipo_documento_id'] = $this->input->post('tipo_documento_id'); }
            if ( strlen($row_usuario->ciudad_id) == 0 ) { $user['ciudad_id'] = $this->input->post('ciudad_id'); }
            if ( strlen($row_usuario->address) == 0 ) { $user['address'] = $this->input->post('direccion'); }
            if ( strlen($row_usuario->celular) == 0 ) { $user['celular'] = $this->input->post('celular'); }
            if ( is_null($row_usuario->sexo) ) { $user['sexo'] = $this->input->post('sexo'); }
            if ( is_null($row_usuario->fecha_nacimiento) ) { $user['fecha_nacimiento'] = substr($this->input->post('year'),-4) . '-' . substr($this->input->post('month'),-2) . '-' .  substr($this->input->post('day'),-2); }

            if ( count($user) > 0 )
            {
                $data['saved_id'] = $this->Db_model->save('usuario', "id = {$row_usuario->id}", $user);
            }

            //Cargar usuario en variables de sesión
            $this->session->set_userdata('user_id', $data['saved_id']);
        }

        return $data;
    }

    /**
     * Establecer un usuario y sus datos a un pedido en curso
     * 2020-04-30
     */
    function set_user($user_id)
    {
        $row_user = $this->Db_model->row_id('usuario', $user_id);

        $arr_row['usuario_id'] = $user_id;
        $arr_row['email'] = $row_user->email;

        //Se actualiza pedido.usuario_id
        $this->db->where('id', $this->session->userdata('pedido_id'));
        $this->db->update('pedido', $arr_row);

        $data['usuario_id'] = $user_id;
        $data['pedido'] = $this->session->userdata('pedido_id');

        return $data;
    }
    
//DESCUENTO PARA DISTRIBUIDOR
//---------------------------------------------------------------------------------------------------
    
    /**
     * Crea o edita un registro en la tabla pedido_detalle, elemento extra del pedido
     * que corresponde al descuento que se le otorga a un cliente por ser distribuidor
     * 
     * @param type $pedido_id
     */
    function act_desc_distribuidor($pedido_id)
    {
        //Identificar rol de usuario
            $rol_id = $this->rol_comprador($pedido_id);
            $arr_roles = $this->arr_roles_distribuidor();   //Array con ID de roles que permiten este descuento
        
        //Se actualiza si el usuario tiene un rol que permite este decuento
        if ( in_array($rol_id, $arr_roles) )
        {
            //Construir registro
                $registro['pedido_id'] = $pedido_id;
                $registro['producto_id'] = 3;   //COD 3, corresponde a descuento para distribuidores, ver Ajustes > Parámetros > Extras pedidos
                $registro['tipo_id'] = 2;       //No es un producto (1), es un elemento extra (2)
                $registro['precio'] = $this->valor_desc_distribuidor($pedido_id);
                $registro['cantidad'] = 1;      //Un descuento

            //Condición
                $condicion = "pedido_id = {$pedido_id} AND producto_id = 3 AND tipo_id = 2";

            //Guardar
                $this->Pcrn->guardar('pedido_detalle', $condicion, $registro);
        }
    }
    
    /**
     * Devuelve el valor del descuento que se le otorga a un distribuidor
     * 
     * @param type $pedido_id
     * @return type
     */
    function valor_desc_distribuidor($pedido_id)
    {
        $descuento = 0;
        $detalle = $this->detalle($pedido_id);
        
        foreach ( $detalle->result() as $row_detalle )
        {
            $porcentaje = $this->porcentaje_desc_fabricante($row_detalle->fabricante_id);
            $descuento -= $row_detalle->precio * $row_detalle->cantidad * ($porcentaje / 100);
        }
        
        return $descuento;
    }
    
    /**
     * Porcentaje de descuento para distribuidores según el fabricante del producto
     * El descuento está definido en el campo item.entero_1
     * 
     * @param type $fabricante_id
     * @return type
     */
    function porcentaje_desc_fabricante($fabricante_id)
    {
        $row_item = $this->Pcrn->registro_id('item', $fabricante_id);
        $porcentaje = $this->Pcrn->si_strlen($row_item->entero_1, 0);
        
        return $porcentaje;
    }
    
    /**
     * Array con roles de usuario que permiten el descuento para distribuidores
     * 
     * @return int
     */
    function arr_roles_distribuidor()
    {
        $arr_roles = array(22);
        return $arr_roles;
    }

//PAGOS ON LINE
//---------------------------------------------------------------------------------------------------
    
    /**
     * Devuelve array con los campos y valores para el formulario que se envía
     * a PayU, para pruebas
     */    
    function form_data_payu_pruebas($pedido_id)
    {
        $row_pedido = $this->Pcrn->registro_id('pedido', $pedido_id);
        
        $form_data['merchantId'] = 508029;
        $form_data['accountId'] = 512321;
        $form_data['description'] = 'Compra ' . $row_pedido->cod_pedido . ' en Districatolicas Unidas S.A.S.';
        $form_data['referenceCode'] = $row_pedido->cod_pedido;
        $form_data['amount'] = $row_pedido->valor_total . '.00'; //2016-04-19 se agregan, punto y dos ceros según especificación de POL
        $form_data['taxReturnBase'] = $row_pedido->total_iva / 0.19;
        $form_data['tax'] = $row_pedido->total_iva;
        $form_data['currency'] = 'COP';
        $form_data['buyerEmail'] = $row_pedido->email;
        $form_data['signature'] = $this->firma_pol_pruebas($form_data);
        $form_data['shippingAddress'] = $row_pedido->direccion . ' ' . $row_pedido->direccion_detalle;
        $form_data['shippingCity'] = $row_pedido->ciudad;
        $form_data['shippingCountry'] = 'CO';
        $form_data['telephone'] = $row_pedido->celular . ' - ' . $row_pedido->telefono;
        
        return $form_data;
    }
    
    /**
     * Devuelve array con los campos y valores para el formulario que se envía
     * a Pagos On Line
     */    
    function form_data_pol($pedido_id, $prueba = NULL)
    {
        $row_pedido = $this->Pcrn->registro_id('pedido', $pedido_id);
        
        $form_data['usuarioId'] = 67827;    //Correspondiente usuario en POL
        $form_data['descripcion'] = 'Compra ' . $row_pedido->cod_pedido . ' en Districatolicas Unidas S.A.S.';
        $form_data['refVenta'] = $row_pedido->cod_pedido;
        $form_data['valor'] = $row_pedido->valor_total . '.00'; //2016-04-19 se agregan, punto y dos ceros según especificación de POL
        $form_data['baseDevolucionIva'] = $row_pedido->total_iva / 0.19;
        $form_data['iva'] = $row_pedido->total_iva;
        $form_data['moneda'] = 'COP';
        $form_data['emailComprador'] = $row_pedido->email;
        $form_data['firma'] = $this->firma_pol($form_data);
        $form_data['url_respuesta'] = base_url('pedidos/respuesta/');
        $form_data['url_confirmacion'] = base_url('pedidos/confirmacion_pol/');
        
        //Adicionales
        $form_data['nombreComprador'] = $row_pedido->nombre . ' ' . $row_pedido->apellidos;
        $form_data['documentoIdentificacion'] = $row_pedido->no_documento;
        $form_data['telefonoMovil'] = $row_pedido->celular;
        
        if ( $prueba == 1 ) 
        {
            $form_data['prueba'] = 1;
        }   //Establece si es una pago de prueba o real
        
        return $form_data;
    }
    
    function form_data_pol_usd($pedido_id, $prueba = NULL)
    {
        $row_pedido = $this->Pcrn->registro_id('pedido', $pedido_id);
        $precio_dolar = $this->App_model->valor_opcion(105);    //Ver Ajustes > Parámetros > General > Id 105
        
        $form_data['usuarioId'] = 67827;    //Correspondiente usuario en POL
        $form_data['descripcion'] = 'Compra ' . $row_pedido->cod_pedido . ' en Districatolicas Unidas S.A.S.';
        $form_data['refVenta'] = $row_pedido->cod_pedido;
        $form_data['valor'] = number_format($row_pedido->valor_total/$precio_dolar, 2, '.', '');
        $form_data['baseDevolucionIva'] = number_format($row_pedido->total_iva / (0.19 * $precio_dolar), 2, '.', '');
        $form_data['iva'] = number_format($row_pedido->total_iva / $precio_dolar, 2, '.', '');
        $form_data['moneda'] = 'USD';
        if ( $prueba == 1 ) { $form_data['prueba'] = 1; }   //Establece si es una pago de prueba o real
        $form_data['emailComprador'] = $row_pedido->email;
        $form_data['firma'] = $this->firma_pol($form_data);
        $form_data['url_respuesta'] = base_url('pedidos/respuesta/');
        $form_data['url_confirmacion'] = base_url('pedidos/confirmacion_pol/');
        
        //Adicionales
        $form_data['nombreComprador'] = $row_pedido->nombre . ' ' . $row_pedido->apellidos;
        $form_data['documentoIdentificacion'] = $row_pedido->no_documento;
        $form_data['telefonoMovil'] = $row_pedido->celular;
        
        return $form_data;
    }
    
    /**
     * Genera la firma para enviar a POL en el formulario de pago
     * @param type $form_data
     * @return type
     */
    function firma_pol($form_data)
    {
        $llave_encripcion = '12de7e3b732';  //Tomado de https://secure.pagosonline.net > Opciones
        
        $signature_pre = $llave_encripcion;
        $signature_pre .= '~' . $form_data['usuarioId'];
        $signature_pre .= '~' . $form_data['refVenta'];
        $signature_pre .= '~' . $form_data['valor'];
        $signature_pre .= '~' . $form_data['moneda'];
        
        return md5($signature_pre);
    }
    
    /**
     * Genera la firma para enviar a POL en el formulario de pago
     * @param type $form_data
     * @return type
     */
    function firma_payu_pruebas($form_data)
    {
        $api_key = '4Vj8eK4rloUd272L48hsrarnUA';  //Tomado de http://developers.payulatam.com/es/web_checkout/sandbox.html > Opciones
        
        $signature_pre = $api_key;
        $signature_pre .= '~' . $form_data['merchantId'];
        $signature_pre .= '~' . $form_data['referenceCode'];
        $signature_pre .= '~' . $form_data['amount'];
        $signature_pre .= '~' . $form_data['currency'];
        
        return md5($signature_pre);
    }
    
    /**
     * Genera la firma que genera POL tras una transacción,
     * Se genera y se compara con la enviada por POL a la página de confirmación
     * de una trasacción
     */
    function firma_pol_confirmacion($pedido_id, $estado_pol)
    {
        $llave_encripcion = '12de7e3b732';  //Tomado de https://secure.pagosonline.net > Opciones
        
        $form_data = $this->form_data_pol($pedido_id);
        
        $signature_pre = $llave_encripcion;
        $signature_pre .= '~' . $form_data['usuarioId'];
        $signature_pre .= '~' . $form_data['refVenta'];
        $signature_pre .= '~' . $form_data['valor'];
        $signature_pre .= '~' . $form_data['moneda'];
        $signature_pre .= '~' . $estado_pol;
        
        return strtoupper(md5($signature_pre));
    }
    
    function arr_respuesta_pol()
    {
        $arr_confirmacion = $this->input->get();    //Original
        //$arr_confirmacion = $this->input->post();    //Para test
        
        return $arr_confirmacion;
    }

    function delete_respuesta_pol($pedido_id)
    {

    }
    
    /**
     * Tomar y procesar los datos POST que envía PagosOnLine a la página de confirmación
     * url_confirmacion >> 'pedidos/confirmacion_pol'
     */
    function confirmacion_pol()
    {
        //Identificar Pedido
        $meta_id = 0;
        $row = $this->row_cod_pedido($this->input->post('ref_venta'));    

        if ( ! is_null($row) )
        {
            //Guardar array completo de confirmación en la tabla "meta"
                $meta_id = $this->json_confirmacion($row->id);

            //Actualizar registro de pedido
                $codigo_respuesta_pol = $this->act_estado($row->id, 110);    //Usuario id=110, PayU Automático

            //Descontar cantidades de producto.cant_disponibles, si codigo_respuesta_pol = 1: Trasnsacción aprobada
                if ( $codigo_respuesta_pol == 1 )
                {
                    $this->descontar_disponibles($row->id);     //Restar vendidos de cantidades disponibles
                    $this->assign_posts($row->id);              //Asignar contenidos digitales asociados a los productos comprados
                }

            //Enviar e-mails a administradores de tienda y al cliente
                if ( ENV == 'production' )
                {
                    $this->email_cliente($row->id);
                    $this->email_admon($row->id);
                    //if ( $estado_pedido == 3 ) { $this->email_admon($row->id); }
                }
        }

        return $meta_id;
    }

    /**
     * Crea un registro en la tabla meta, con los datos recibidos tras en la 
     * ejecución de la página de confirmación por parte de Pagos On Line (POL).
     */
    function json_confirmacion($pedido_id)
    {
        //Datos POL
            $arr_confirmacion_pol = $this->input->post();
            $arr_confirmacion_pol['ip_address'] = $this->input->ip_address();
            $json_confirmacion_pol = json_encode($arr_confirmacion_pol);
        
        //Construir registro
            $registro['tabla_id'] = 3000;  //Pedido
            $registro['elemento_id'] = $pedido_id;
            $registro['relacionado_id'] = 0;
            $registro['dato_id'] = 3005;  //Ver: parámetros > metadatos
            $registro['editado'] = date('Y-m-d H:i:s');
            $registro['valor'] = $json_confirmacion_pol;
        
        //Guardar
            $this->db->insert('meta', $registro);
        
        return $this->db->insert_id();
    }

    /**
     * Actualiza el campo pedido.estado_pedido dependiendo de los datos
     * que se tengan de la transacción en Pagos On Line
     * 2020-08-12
     */
    function act_estado($pedido_id, $usuario_id)
    {
        //Valores
            $codigo_respuesta_pol = $this->codigo_respuesta_pol($pedido_id);
            $estado_pedido = $this->estado_pedido_pol($codigo_respuesta_pol);
            
        //Registro
            $arr_row['codigo_respuesta_pol'] = $codigo_respuesta_pol;
            $arr_row['estado_pedido'] = $estado_pedido;
            $arr_row['editado_usuario_id'] = $usuario_id;
            $arr_row['confirmado'] = date('Y-m-d H:i:s');
            
        //Actualizar
            $this->act_pedido($pedido_id, $arr_row);
            
        return $codigo_respuesta_pol;
    }

    /**
     * Devuelve el código de respuesta de Pagos On Line tomado de los datos de POL guardados en la 
     * tabla meta tras la confirmación del resultado de la transacción de pago.
     * 2020-08-12
     */
    function codigo_respuesta_pol($pedido_id)
    {
        $codigo_respuesta_pol = 0; //Valor inicial
        
        $condicion = "tabla_id = 3000 AND dato_id = 3005 AND elemento_id = {$pedido_id} ORDER BY id DESC";
        $json = $this->Pcrn->campo('meta', $condicion, 'valor');
        
        if ( strlen($json) > 0 ) 
        {
            $arr_confirmacion = json_decode($json, TRUE);
            $codigo_respuesta_pol = $arr_confirmacion['codigo_respuesta_pol']; 
        }
        
        return $codigo_respuesta_pol;
    }
    
    /**
     * Devuelve el código del estado de un pedido dependidendo de los datos de POL guardados
     * en la tabla meta tras la confirmación del resultado de la transacción de pago.
     * 2020-08-12
     */
    function estado_pedido_pol($codigo_respuesta_pol)
    {
        $estado_pedido = 2; //Valor inicial
        if ( $codigo_respuesta_pol == 1 ) { $estado_pedido = 3; }     //Pago confirmado
        
        return $estado_pedido;
    }

// GESTIÓN DE ASIGNACIÓN DE PRODUCTOS DIGITALES
//-----------------------------------------------------------------------------

    /**
     * Verifica qué productos de los comprados incluyen contenidos digitales y se los asigna
     * al usuario que realizó la compra
     * 2020-04-16
     * 
     */
    function assign_posts($pedido_id)
    {
        //Cargue inicial
        $this->load->model('Producto_model');
        $this->load->model('Post_model');
        $row = $this->Db_model->row_id('pedido', $pedido_id);

        $productos = $this->digital_products($pedido_id);   //Productos con contenidos digitales

        $arr_posts = array();
        foreach( $productos->result() as $row_producto )
        {
            $posts = $this->Producto_model->assigned_posts($row_producto->id);
            foreach ( $posts->result() as $row_post )
            {
                $arr_posts[] = array('id' => $row_post->id, 'title' => $row_post->title);
                $this->Post_model->add_to_user($row_post->id, $row->usuario_id);
            }
        }

        $data['productos'] = $productos->result();
        $data['posts'] = $arr_posts;
        $data['qty_posts'] = count($arr_posts);

        return $data;
    }

    /**
     * Listado de productos con contenidos digitales que están incluidos en un pedido
     * 2020-04-16
     */
    function digital_products($pedido_id)
    {
        $this->db->select('id,nombre_producto');
        $this->db->where("id IN (SELECT producto_id FROM pedido_detalle WHERE pedido_id = {$pedido_id} AND tipo_id = 1)");
        $this->db->where('categoria_id', 162);  //Contenidos digitales
        $productos = $this->db->get('producto');

        return $productos;
    }
    
    /**
     * Valor total del pedido, calculado desde pedido_detalle
     */
    function valor_total($pedido_id)
    {
        $valor_total = 0;
        
        $this->db->select('SUM(precio * cantidad) as sum_total');
        $this->db->where('pedido_id', $pedido_id);
        
        $query = $this->db->get('pedido_detalle');
        
        if ( $query->num_rows() > 0 ) {
            $valor_total = $query->row()->sum_total;
        }
        
        return $valor_total;
    }
    
    /**
     * Valor actual de la comisión a POL guardada en la tabla pedido_detalle
     */
    function comision_pol_actual($pedido_id)
    {
        $comision = 0;
        
        $this->db->select('SUM(precio * cantidad) as sum_total');
        $this->db->where('pedido_id', $pedido_id);
        $this->db->where('tipo_id', 2);     //Extra
        $this->db->where('producto_id', 4); //Comisión pagos on line
        
        $query = $this->db->get('pedido_detalle');
        
        if ( $query->num_rows() > 0 ) {
            $comision = $query->row()->sum_total;
        }
        
        return $comision;
        
    }
    
    /**
     * Calcula el valor de la comisión cobrada por POL en una transacción de ventas
     */
    function comision_pol($pedido_id)
    {
        //Variables iniciales
            $valor_total_bruto = $this->valor_total($pedido_id);    //Valor total bruto del pedido
            $cpol_actual = $this->comision_pol_actual($pedido_id);  //Valor actual de la comisión
            $valor_total = $valor_total_bruto - $cpol_actual;
        
            $probabilidad_tc = 0.1875 + 0;          //Probabilidad que el pago se haga con Tarjeta de Crédito + margen error
            $tasa_retenciones = 0.015 + 0.00414;    //Porcentaje retenciones, Rete Renta + Rete ICA
            
        //Cálculos
            //$comision_pol = 900 + ($valor_total * 0.0449);
            $comision_pol = 900 + ($valor_total * 0.02);    //Medida transitoria 2016-08-29
            if ( $comision_pol < 2900 ) { $comision_pol = 2900; }    //El valor mínimo de la comisión es 2900

            $comision_iva = $comision_pol * 0.19;   //Iva de la comisión POL
            $retencion_tc = $valor_total * $tasa_retenciones * $probabilidad_tc; //Retención por pago tarjeta de crédito

        //Total
            $comision = $comision_pol + $comision_iva + $retencion_tc;
            //$comision = $retencion_tc;
            $comision_int = ceil($comision / 50) * 50;  //Convertido en múltiplo de $50
            //$comision_int = $comision;
        
        return $comision_int;
        
    }
    
    /**
     * Actualiza el valor de la comisión pol en la tabla pedido_detalle
     */
    function act_comision_pol($pedido_id)
    {
        //Construir registro
            $registro['pedido_id'] = $pedido_id;
            $registro['producto_id'] = 4;   //COD 4, corresponde a comisión pol, ver Ajustes > Parámetros > Extras pedidos
            $registro['tipo_id'] = 2;       //No es un producto (1), es un elemento extra (2)
            $registro['precio'] = $this->comision_pol($pedido_id);
            $registro['cantidad'] = 1;      //Una (1) comision
            $registro['costo'] = 0;
            $registro['precio_nominal'] = 0;
            $registro['iva'] = 0;

        //Condición
            $condicion = "pedido_id = {$pedido_id} AND producto_id = 4 AND tipo_id = 2";

        //Guardar
            $this->Pcrn->guardar('pedido_detalle', $condicion, $registro);
    }
    
// 
//-----------------------------------------------------------------------------
    
    function act_estado_pendientes()
    {
        //Seleccionar los pedidos pendientes
            $this->db->where('codigo_respuesta_pol IS NULL');   //Sin valor
            $this->db->where("id IN (SELECT elemento_id FROM meta WHERE tabla_id = 3000 AND dato_id = 3005)");  //Tiene metadados de POL
            $pedidos = $this->db->get('pedido');
        
        //Procesar
            foreach ( $pedidos->result() as $row_pedido )
            {
                //echo $row_pedido->id . '<br/>';
                $this->Pedido_model->act_estado($row_pedido->id, $this->session->userdata('usuario_id'));
            }
            
        return $pedidos->num_rows();
    }

// NOTIFICACIÓN EMAIL
//-----------------------------------------------------------------------------
    
    /**
     * Tras la confirmación POL del pedido, se envía un mensaje de estado del pedido
     * a los e-mails definidos en la tabla sis_opcion, ID = 25
     * 
     * @param type $pedido_id
     */
    function email_admon($pedido_id)
    {
        $row_pedido = $this->Pcrn->registro_id('pedido', $pedido_id);
            
        //Destinatarios
            $str_destinatarios = $this->Pcrn->campo_id('sis_opcion', 25, 'valor');
            
        //Asunto de mensaje
            $subject = "Pedido {$row_pedido->cod_pedido}: " . $this->Item_model->nombre(10, $row_pedido->codigo_respuesta_pol);
        
        //Enviar Email
            $this->load->library('email');
            $config['mailtype'] = 'html';

            $this->email->initialize($config);
            $this->email->from('info@districatolicas.com', 'Districatólicas Unidas S.A.S.');
            $this->email->to($str_destinatarios);
            $this->email->message($this->mensaje_admon($row_pedido));
            $this->email->subject($subject);
            
            $this->email->send();   //Enviar
            
    }
    
    /**
     * String HTML con mensaje para enviar por email administrador
     */
    function mensaje_admon($row_pedido)
    {
        $data['row_pedido'] = $row_pedido ;
        $data['detalle'] = $this->detalle($row_pedido->id);
        
        $mensaje = $this->load->view('pedidos/mensaje_admon_v', $data, TRUE);
        
        return $mensaje;
    }
    
    /**
     * HTML del mensaje que se envía tras la actualización del estado de un pedido
     */
    function mensaje_actualizacion($row_pedido)
    {
        $data['row_pedido'] = $row_pedido ;
        $data['style'] = $this->App_model->email_style();
        
        $mensaje = $this->load->view('usuarios/emails/act_pedido_v', $data, TRUE);
        
        return $mensaje;
    }
    
    /**
     * Envía Email a cliente, estado del pedido Tras la confirmación POL del pedido
     */
    function email_cliente($pedido_id)
    {
        $row_pedido = $this->Pcrn->registro_id('pedido', $pedido_id);
            
        //Asunto de mensaje
            $subject = "{$row_pedido->nombre}, resumen de su pedido {$row_pedido->cod_pedido}";
        
        //Enviar Email
            $this->load->library('email');
            $config['mailtype'] = 'html';

            $this->email->initialize($config);
            $this->email->from('info@districatolicas.com', 'Districatólicas Unidas S.A.S.');
            $this->email->to($row_pedido->email);
            $this->email->subject($subject);
            $this->email->message($this->mensaje_admon($row_pedido));
            
            $this->email->send();   //Enviar       
    }
    
    /**
     * Envía e-mail de estado del pedido al cliente Tras edición de los datos logísticos de un pedido
     */
    function email_actualizacion($pedido_id)
    {
        $row_pedido = $this->Pcrn->registro_id('pedido', $pedido_id);
            
        //Asunto de mensaje
            $subject = "Compra {$row_pedido->cod_pedido}: " . $this->Item_model->nombre(7, $row_pedido->estado_pedido);
        
        //Enviar Email
            $this->load->library('email');
            $config['mailtype'] = 'html';

            $this->email->initialize($config);
            $this->email->from('info@districatolicas.com', 'Districatólicas Unidas S.A.S.');
            $this->email->to($row_pedido->email);
            $this->email->bcc($this->App_model->valor_opcion(25));
            $this->email->subject($subject);
            $this->email->message($this->mensaje_actualizacion($row_pedido));
            
            $this->email->send();   //Enviar
            
    }
    
    /**
     * Actualiza el campo producto.cant_disponibles después de que se confirma el pago de un pedido
     * Se resta la cantidad pedida al campo producto.cant_disponibles.
     * 
     * @param type $pedido_id
     */
    function descontar_disponibles($pedido_id)
    {
        $productos = $this->detalle($pedido_id);
        
        foreach ( $productos->result() as $row_detalle ) {
            $sql = "UPDATE producto SET cant_disponibles = cant_disponibles - {$row_detalle->cantidad} WHERE id = {$row_detalle->producto_id}";
            $this->db->query($sql);
        }
    }

    function confirmacion($pedido_id)
    {
        $confirmacion = null;

        $this->db->where("dato_id = 3005 AND elemento_id = {$pedido_id}");
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('meta');

        if ( $query->num_rows() > 0 ) 
        {
            $confirmacion = json_decode($query->row(0)->valor);
        }

        return $confirmacion;
    }

// GESTIÓN DE EXTRAS
//-----------------------------------------------------------------------------

    function extras_save()
    {
        $data = array('status' => 0, 'saved_id' => '0');    //Valores iniciales

        //Construyendo registro
            $arr_row = $this->input->post();
            $arr_row['tipo_id'] = 2;    //Extra, no producto
            $arr_row['promocion_id'] = 0;
            $arr_row['precio_nominal'] = $arr_row['precio'];
            $arr_row['costo'] = 0;
            $arr_row['iva'] = 0;
            $arr_row['cantidad'] = 1;

        //Guardar
            $condition = "pedido_id = {$arr_row['pedido_id']} AND producto_id = {$arr_row['producto_id']} AND tipo_id = {$arr_row['tipo_id']}";
            $data['saved_id'] = $this->Pcrn->guardar('pedido_detalle', $condition, $arr_row);

        //Actualizar totales pedido
            $this->act_totales_2($arr_row['pedido_id']);           //Valor total extras
            $this->act_totales_3($arr_row['pedido_id']);           //Valor total

        //Preparar resultado
            if ( $data['saved_id'] > 0 ) { $data['status'] = 1;}

        return $data;
    }

    function extras_delete($pedido_id, $pd_id)
    {
        $data = array('status' => 0, 'qty_deleted' => '0');
        
        $this->db->where('id', $pd_id);
        $this->db->where('pedido_id', $pedido_id);
        $this->db->delete('pedido_detalle');

        $data['qty_deleted'] = $this->db->affected_rows();
        if ( $data['qty_deleted'] > 0 )
        {
            $data['status'] = 1;

            //Actualizar totales pedido
            $this->act_totales_2($pedido_id);           //Valor total extras
            $this->act_totales_3($pedido_id);           //Valor total
        }

        return $data;
    }
}