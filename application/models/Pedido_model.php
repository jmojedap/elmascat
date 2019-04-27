<?php
class Pedido_Model extends CI_Model{
    
    function basico($pedido_id)
    {
        
        $this->db->where('id', $pedido_id);
        $query = $this->db->get('pedido');
        
        $row = $query->row();
        
        $basico['row'] = $row;
        $basico['titulo_pagina'] = $row->cod_pedido;
        $basico['vista_a'] = 'pedidos/pedido_v';
        
        return $basico;
    }
    
    function buscar($busqueda, $per_page = NULL, $offset = NULL)
    {

        //Construir búsqueda
        //Crear array con términos de búsqueda
            if ( strlen($busqueda['q']) > 2 ){
                
                $campos_pedidos = array('nombre', 'apellidos', 'cod_pedido', 'factura', 'no_guia');
                
                $concat_campos = $this->Busqueda_model->concat_campos($campos_pedidos);
                $palabras = $this->Busqueda_model->palabras($busqueda['q']);

                foreach ($palabras as $palabra) {
                    $this->db->like("CONCAT({$concat_campos})", $palabra);
                }
            }
        
        //Especificaciones de consulta
            $this->db->select('pedido.*');
            $this->db->order_by('id', 'DESC');
            
        //Otros filtros
            if ( $busqueda['est'] != '' ) { $this->db->where('estado_pedido', $busqueda['est']); }    //Editado
            if ( $busqueda['condicion'] != '' ) { $this->db->where($busqueda['condicion']); }    //Condición especial
            
        //Obtener resultados
        if ( is_null($per_page) ){
            $query = $this->db->get('pedido'); //Resultados totales
        } else {
            $query = $this->db->get('pedido', $per_page, $offset); //Resultados por página
        }
        
        return $query;
        
    }
    
    function editable($pedido_id)
    {
        $editable = 1;
        return $editable;
    }
    
    /**
     * Determina si un pedido es eliminable o no
     * @return int
     */
    function eliminable()
    {
        $eliminable = 0;
        if ( $this->session->userdata('rol_id') <= 1 ) 
        {
            $eliminable = 1;
        }

        return $eliminable;
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
    
    /**
     * Actualizar registro de pedido, datos de administración.
     * 
     * @param type $pedido_id
     * @return string
     */
    function guardar_admin($pedido_id)
    {   
        //Resultado del proceso, valor por defecto
            $resultado['clase'] = 'alert-success';
            $resultado['mensaje'] = 'El pedido se guardó correctamente.';
            $resultado['modificado'] = 0;   //El registró no se modificó
        
        //Actualizar registro
            $registro = $this->input->post();
            $this->db->where('id', $pedido_id);
            $this->db->update('pedido', $registro);
            
            
        if ( $this->db->affected_rows() > 0 ) 
        {
            $resultado['modificado'] = 1;
            $resultado['mensaje'] .= ' Se enviará un e-mail al cliente notificando los cambios.';
        }
        
        return $resultado;
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
     * 
     * @param type $pedido_id
     * @return type
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
     * 
     * @param type $pedido_id
     * @return type
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
     * 
     * @param type $pedido_id
     * @return type
     */
    function cant_productos($pedido_id)
    {
        $condicion = "pedido_id = {$pedido_id} AND tipo_id = 1";
        $cant_productos = $this->Pcrn->num_registros('pedido_detalle', $condicion);
        
        return $cant_productos;
    }
    
    /**
     * Guarda un registro en la tabla pedido_detalle (pd)
     * 
     * @param type $registro
     * @return type
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

//DATOS
//---------------------------------------------------------------------------------------------------
    
    /**
     * Devuelve un elemento row, de un pedido dado el código del pedido
     * @param type $cod_pedido
     * @return type
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
     * 
     * @return type
     */
    function crear()
    {    
        //Construir registro
            $registro['pais_id'] = 51; //Colombia, por defecto, ver lugar.id
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
     * 
     * @param type $pedido_id
     */
    function act_cod_pedido($pedido_id)
    {
        $this->load->helper('string');
        
        $registro['cod_pedido'] = 'DC-' . strtoupper(random_string('alpha', 4)) . '-' . $pedido_id;
        
        $this->db->where('id', $pedido_id);
        $this->db->update('pedido', $registro);
    }
    
    /**
     * Establece la dirección de entrega de un pedido, conociendo el usuario y 
     * la dirección (post_id) registrada en la tabla post
     * 
     * @param type $pedido_id
     * @param type $post_id
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
     * Validar los datos de contacto del formulario del pedido
     * @return type
     */
    function validar()
    {
        $this->load->library('form_validation');   
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert">', '</div>');
        
        //Reglas
            $this->form_validation->set_rules('email', 'Correo electrónico', 'valid_email');
            $this->form_validation->set_rules('telefono', 'Teléfono', 'min_length[6]');
            
        //Mensajes
            $this->form_validation->set_message('valid_email', 'El correo electrónico no es válido');
            $this->form_validation->set_message('min_length', 'La casilla %s debe tener al menos 6 caracteres');
        
        $valido =  $this->form_validation->run();
        
        return $valido;
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
        $this->act_flete($pedido_id);
        $this->act_comision_pol($pedido_id);        //Actualizar comisión POL
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
                $registro['producto_id'] = 3;   //COD 2, corresponde a descuento para distribuidores, ver Ajustes > Parámetros > Extras pedidos
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
     * a Pagos On Line, para pruebas
     * 
     * @param type $pedido_id
     * @return type
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
     * 
     * @param type $pedido_id
     * @return type
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
     * 
     * @param type $estado_pol
     * @return type
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
    
    /**
     * Tomar y procesar los datos POST que envía PagosOnLine a la página 
     * de confirmación.
     * url_confirmacion >> 'pedidos/confirmacion_pol'
     * 
     * @return type
     */
    function confirmacion_pol()
    {
        
        //Identificar Pedido
        $row = $this->row_cod_pedido($this->input->post('ref_venta'));    

        if ( ! is_null($row) )
        {
            //Guardar array completo de confirmación en la tabla "meta"
                $this->json_confirmacion($row->id);

            //Actualizar registro de pedido
                $estado_pedido = $this->act_estado($row->id, 1);    //Usuario id=1, administrador

            //Descontar cantidades de producto.cant_disponibles, si está en estado 3: pago confirmado
                if ( $estado_pedido == 3 )
                {
                    //Pago confirmado
                    $this->descontar_disponibles($row->id);
                }

            //Enviar mensaje a administradores de tienda y al cliente
                $this->email_cliente($row->id);
                $this->email_admon($row->id);
                //if ( $estado_pedido == 3 ) { $this->email_admon($row->id); }
                

            return $row->id;
        }
        
    }
    
// Comisión para Pagos On Line
//-----------------------------------------------------------------------------
    
    /**
     * Valor total del pedido, calculado desde pedido_detalle
     * 
     * @param type $pedido_id
     * @return type
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
     * 
     * @param type $pedido_id
     * @return type
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
     * Calcula el valor de la comisión cobrada por POL en una transacción de
     * ventas
     * 
     * @param type $pedido_id
     * @return type
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

            $comision_iva = $comision_pol * 0.16;   //Iva de la comisión POL
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
     * 
     * @param type $pedido_id
     */
    function act_comision_pol($pedido_id)
    {
        //Se actualiza si la ciudad ya está definida
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
    
// Título Separador
//-----------------------------------------------------------------------------
    
    /**
     * Actualiza el campo pedido.estado_pedido dependiendo de los datos
     * que se tengan de la transacción en Pagos On Line
     * 
     * @param type $pedido_id
     * @param type $usuario_id
     * @return type}
     */
    function act_estado($pedido_id, $usuario_id)
    {
        //Valores
            $codigo_respuesta_pol = $this->codigo_respuesta_pol($pedido_id);
            $estado_pedido = $this->estado_pedido_pol($codigo_respuesta_pol);
            
        //Registro
            $registro['codigo_respuesta_pol'] = $codigo_respuesta_pol;
            $registro['estado_pedido'] = $estado_pedido;
            $registro['editado_usuario_id'] = $usuario_id;
            
        //Actualizar
            $this->act_pedido($pedido_id, $registro);
            
        return $estado_pedido;
    }
    
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
    
    function mensaje_admon($row_pedido)
    {
        $data['row_pedido'] = $row_pedido ;
        $data['detalle'] = $this->detalle($row_pedido->id);
        
        $mensaje = $this->load->view('pedidos/mensaje_admon_v', $data, TRUE);
        
        return $mensaje;
    }
    
    /**
     * HTML del mensaje que se envía tras la actualización del estado de un pedido
     * 
     * @param type $row_pedido
     * @return type
     */
    function mensaje_actualizacion($row_pedido)
    {
        $data['row_pedido'] = $row_pedido ;
        $data['style'] = $this->App_model->email_style();
        
        $mensaje = $this->load->view('usuarios/emails/act_pedido_v', $data, TRUE);
        
        return $mensaje;
    }
    
    /**
     * Tras la confirmación POL del pedido, se envía un mensaje de estado del pedido
     * al cliente
     * 
     * @param type $pedido_id
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
     * Tras edición de los datos administrativos de un pedido, se envía un
     * e-mail de estado del pedido al cliente
     * 
     * @param type $pedido_id
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
     * Crea un registro en la tabla meta, con los datos recibidos tras en la 
     * ejecución de la página de confirmación por parte de Pagos On Line (POL).
     * 
     * @param type $pedido_id
     * @return type
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
     * Devuelve el código de respuesta de Pagos On Line
     * tomado de los datos de POL guardados en la tabla meta tras la 
     * confirmación del resultado de la transacción de pago.
     * 
     * @param type $pedido_id
     * @return int
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
     * Devuelve el código del estado de un pedido dependidendo 
     * de los datos de POL guardados en la tabla meta tras la confirmación del resultado
     * de la transacción de pago.
     * 
     * @param type $codigo_respuesta_pol
     * @return int
     */
    function estado_pedido_pol($codigo_respuesta_pol)
    {
        $estado_pedido = 2; //Valor inicial
        if ( $codigo_respuesta_pol == 1 ) { $estado_pedido = 3; }     //Pago confirmado
        
        return $estado_pedido;
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
    
}

