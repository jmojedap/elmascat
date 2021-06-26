<?php
class Producto_Model extends CI_Model{
    
    function basico($producto_id)
    {
        
        $row = $this->row_principal($producto_id);
        
        //Imagen principal
        $basico['row'] = $row;
        $basico['row_variacion'] = $this->Db_model->row_id('producto', $producto_id);
        $basico['elemento_s'] = 'producto';
        $basico['elemento_p'] = 'productos';
        $basico['tabla_id'] = 3100;
        $basico['cant_visitas'] = $this->cant_visitas($producto_id);
        $basico['cant_pedidos'] = $this->Db_model->num_rows('pedido_detalle', "producto_id = {$producto_id}");
        $basico['images'] = $this->images($producto_id);
        $basico['head_title'] = $row->nombre_producto;
        $basico['view_a'] = 'productos/producto_v';
        $basico['nav_2'] = $this->views_folder . 'menu_v';
        $basico['back_link'] = $this->url_controller . 'explore';
        
        return $basico;
    }
    
    /**
     * Row del producto, si es una variación es el row del producto padre
     * @param type $producto_id
     * @return type
     */
    function row_principal($producto_id)
    {
        $row = $this->Pcrn->registro_id('producto', $producto_id);
        $row_principal = $row;
        
        if ( $row->tipo_id == 2 ) 
        {
            //Es la variacion de un producto
            $row_principal = $this->Pcrn->registro_id('producto', $row->padre_id);
        }
        
        return $row_principal;
    }

// EXPLORE FUNCTIONS - products/explore
//-----------------------------------------------------------------------------
    
    /**
     * Array con los datos para la vista de exploración
     */
    function explore_data($filters, $num_page, $per_page = 10)
    {
        //Data inicial, de la tabla
            $data = $this->get($filters, $num_page, $per_page);
        
        //Elemento de exploración
            $data['controller'] = 'productos';                       //Nombre del controlador
            $data['cf'] = 'productos/explore/';                      //Nombre del controlador
            $data['views_folder'] = 'ecommerce/products/explore/'; //Carpeta donde están las vistas de exploración
            $data['num_page'] = $num_page;                          //Número de la página
            
        //Vistas
            $data['head_title'] = 'Productos';
            $data['view_a'] = $data['views_folder'] . 'explore_v';
            $data['nav_2'] = $data['views_folder'] . 'menu_v';
        
        return $data;
    }

    /**
     * Array con listado de products, filtrados por búsqueda y num página, más datos adicionales sobre
     * la búsqueda, filtros aplicados, total resultados, página máxima.
     * 2020-08-01
     */
    function get($filters, $num_page, $per_page = 8)
    {
        //Load
            $this->load->model('Search_model');

        //Búsqueda y Resultados
            $data['filters'] = $filters;
            $offset = ($num_page - 1) * $per_page;      //Número de la página de datos que se está consultado
            $elements = $this->search($filters, $per_page, $offset);    //Resultados para página
        
        //Cargar datos
            $data['filters'] = $filters;
            $data['list'] = $this->list($filters, $per_page, $offset);      //Resultados para página
            $data['str_filters'] = $this->Search_model->str_filters();      //String con filtros en formato GET de URL
            $data['search_num_rows'] = $this->search_num_rows($data['filters']);
            $data['max_page'] = ceil($this->pml->if_zero($data['search_num_rows'],1) / $per_page);   //Cantidad de páginas

        return $data;
    }

    /**
     * Segmento Select SQL, con diferentes formatos, consulta de usuarios
     * 2020-12-12
     */
    function select($format = 'general')
    {
        $arr_select['general'] = 'producto.id, nombre_producto as name, referencia AS code, slug, descripcion AS description, promocion_id, puntaje AS priority, puntaje_auto AS priority_auto, 
            palabras_clave AS keywords, precio AS price, cant_disponibles AS stock, imagen_id AS image_id, url_image, url_thumbnail, 
            estado AS status, producto.tipo_id AS type_id, creado AS created_at, editado AS updated_at';

        //$arr_select['export'] = 'usuario.id, username, usuario.email, nombre, apellidos, sexo, rol_id, estado, no_documento, tipo_documento_id, institucion_id, grupo_id';

        return $arr_select[$format];
    }
    
    /**
     * Query de products, filtrados según búsqueda, limitados por página
     * 2021-05-28
     */
    function search($filters, $per_page = NULL, $offset = NULL)
    {
        //Construir consulta
            $this->db->select($this->select());
            
        //Orden
            if ( $filters['o'] != '' )
            {
                $order_type = $this->pml->if_strlen($filters['ot'], 'ASC');
                $this->db->order_by($filters['o'], $order_type);
            }

            $this->db->order_by('puntaje', 'DESC');
            $this->db->order_by('puntaje_auto', 'DESC');
            
        //Filtros
            $search_condition = $this->search_condition($filters);
            if ( $search_condition ) { $this->db->where($search_condition);}
            
        //Obtener resultados
            $query = $this->db->get('producto', $per_page, $offset); //Resultados por página
        
        return $query;
    }

    /**
     * String con condición WHERE SQL para filtrar products
     * 2020-08-01
     */
    function search_condition($filters)
    {
        $condition = NULL;

        $condition .= $this->role_filter() . ' AND ';

        //q words condition
        $words_condition = $this->Search_model->words_condition($filters['q'], array('referencia', 'nombre_producto', 'descripcion', 'palabras_clave'));
        if ( $words_condition )
        {
            $condition .= $words_condition . ' AND ';
        }
        
        //Otros filtros
        if ( $filters['status'] != '' ) { $condition .= "estado = {$filters['status']} AND "; }
        if ( $filters['cat'] != '' ) { $condition .= "categoria_id = {$filters['cat']} AND "; }
        if ( $filters['fab'] != '' ) { $condition .= "fabricante_id = {$filters['fab']} AND "; }
        if ( $filters['dcto'] != '' ) { $condition .= "promocion_id = {$filters['dcto']} AND "; }   //Descuento o promoción
        if ( $filters['promo'] != '' ) { $condition .= "promocion_id > 0 AND "; }   //Tiene algúna oferta o descuento
        if ( $filters['fe1'] != '' ) { $condition .= "peso <= {$filters['fe1']} AND "; }   //Peso máximo
        if ( $filters['tag'] != '' ) {
            $condition .= "producto.id IN (SELECT elemento_id FROM meta WHERE tabla_id = 3100 AND dato_id = 21 AND relacionado_id IN ({$filters['tag']}) ) AND ";
        }
        
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
            $sql_ventas = 'SELECT id FROM pedido WHERE codigo_respuesta_pol = 1';
            $row->qty_sold = $this->Db_model->num_rows('pedido_detalle', "producto_id = {$row->id} AND pedido_id IN ({$sql_ventas})");  //Cantidad de estudiantes*/
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
        $query = $this->db->get('producto'); //Para calcular el total de resultados

        return $query->num_rows();
    }
    
    /**
     * Devuelve segmento SQL, para filtrar listado de usuarios según el rol del usuario en sesión
     * 2020-08-01
     */
    function role_filter()
    {
        $role = $this->session->userdata('role');
        $condition = 'producto.id = 0';  //Valor por defecto, ningún user, se obtendrían cero registros.
        
        if ( $role <= 10 ) 
        {   //Desarrollador, todos los registros
            $condition = 'producto.id > 0';
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
            'id' => 'ID Producto',
            'name' => 'Nombre',
            'price' => 'Precio',
        );
        
        return $order_options;
    }

// DATOS
//-----------------------------------------------------------------------------
    
    /**
     * Igual a la función busqueda, pero agrega una restricción, producto debe
     * estar activo
     * 
     */
    function catalogo($busqueda, $per_page = NULL, $offset = NULL)
    {
        $busqueda['status'] = 1;   //Solo productos activos
        $query = $this->search($busqueda, $per_page, $offset);
        return $query;
    }
    
    /**
     * Insertar un registro en la tabla.
     * 2021-06-03
     */
    function insert($arr_row)
    {
        //Completar registro
            $arr_row['usuario_id'] = $this->session->userdata('usuario_id');
            $arr_row['creado'] = date('Y-m-d H:i:s');
            $arr_row['editado'] = date('Y-m-d H:i:s');
        
        //Insertar
            $this->db->insert('producto', $arr_row);
            $data['saved_id'] = $this->db->insert_id();
        
        return $data;
    }
    
    /**
     * Actualizar los datos de un registro en la tabla producto
     * 2021-06-03
     */
    function update($producto_id)
    {
        //Construir registro            
            $arr_row = $this->input->post();
            
        //Quitar del input datos que no son campos de la tabla producto
            unset($arr_row['tags']);
        
            $arr_row['editado'] = date('Y-m-d H:i:s');
            $arr_row['editor_id'] = $this->session->userdata('user_id');
        
        //Guardar
            $this->Db_model->save('producto', "id = {$producto_id}", $arr_row);
            $data['saved_id'] = $producto_id;
        
        return $data;
    }
    
    /**
     * Devuelve array con los valores post del formulario para actualizar el
     * registro en la tabla producto
     * 2021-06-03
     */
    function arr_row_update() 
    {
        //Construir registro            
            $arr_row = $this->input->post();
            
        //Quitar del input datos que no son campos de la tabla producto
            unset($arr_row['tags']);
            
        //Valores predeterminados
            $arr_row['editado'] = date('Y-m-d H:i:s');
            $arr_row['usuario_id'] = $this->session->userdata('usuario_id');
            
        return $arr_row;
    }
    
    /**
     * Registrar metadato de edición de producto,
     * Se crea un registro en la tabla meta cada vez que se edita un producto.
     * 
     */
    function meta_editado($producto_id)
    {
        $registro['tabla_id'] = 3100;
        $registro['elemento_id'] = $producto_id;
        $registro['dato_id'] = 30;  //Edición de elemento
        $registro['editado'] = date('Y-m-d H:i:s');
        $registro['fecha'] = date('Y-m-d H:i:s');
        $registro['usuario_id'] = $this->session->userdata('usuario_id');
        
        $this->db->insert('meta', $registro);
        $meta_id = $this->db->insert_id();
        
        return $meta_id;
    }
    
    /**
     * Array con el nombre de los campos que pueden ser editables por el usuario
     * desde el formulario en productos/editar
     * 
     * @return string
     */
    function campos_editables()
    {
        $campos = array(
            'nombre_producto',
            'descripcion',
            'palabras_clave',
            'precio_base',
            'iva_porcentaje',
            'iva',
            'precio',
            'precio_anterior',
            'promocion_id',
            'peso',
            'ancho',
            'alto',
            'grosor',
            'cant_disponibles',
            'referencia',
            'fabricante_id',
            'puntaje',
            'estado'
        );
        
        return $campos;
    }
    
    function editable($producto_id)
    {
        $editable = 0;
        if ( $this->session->userdata('usuario_id') <= 2 )
        {
            $editable = 1;
        }
        
        return $editable;
    }
    
// Eliminación de productos
//-----------------------------------------------------------------------------

    /**
     * Verificar si un producto se puede eliminar o no
     * 2021-06-10
     */
    function deleteable($producto_id)
    {
        $deleteable = FALSE;
        if ( in_array($this->session->userdata('role'), array(0,1))  ) $deleteable = TRUE;

        //En cuántos pedidos está incluido el producto
        $qty_orders = $this->Db_model->num_rows('pedido_detalle', "producto_id = {$producto_id}");

        //Si está en algún pedido no se puede eliminar
        if ( $qty_orders > 0 ) $deleteable = FALSE;
        
        return $deleteable;
    }
    
    /**
     * Eliminar producto
     * 2021-06-10
     */
    function delete($producto_id)
    {
        $qty_deleted = 0;
        
        if ( $this->deleteable($producto_id) ) 
        {
            //Tabla meta
                $this->db->where('tabla_id', 2);    //Tabla producto
                $this->db->where('elemento_id', $producto_id);
                $this->db->delete('meta');

            //Tabla producto
                $this->db->where('id', $producto_id)->delete('producto');
                
            $qty_deleted = $this->db->affected_rows();
        }
        
        return $qty_deleted;
    }

// Otros
//-----------------------------------------------------------------------------
    
    /**
     * Array con atributos para describir el estado de publicación producto
     * @return array
     */
    function z_arr_estados()
    {
        $arr_estados = array(
            1 => array(
                'texto' => 'Activo',
                'clase' => ''
            ),
            2 => array(
                'texto' => 'Inactivo',
                'clase' => 'warning'
            ),
            3 => array(
                'texto' => 'Borrador',
                'clase' => 'info'
            )
        );
        
        return $arr_estados;
    }
    
//DATOS SOBRE UN PRODUCTO
//---------------------------------------------------------------------------------------------------
    
    /**
     * Devuelve query con los valores de los metadatos de un producto
     * De la tabla meta, donde meta.valor no esté vacío
     * 
     * @param type $producto_id
     * @return type
     */
    function metadatos_valor($producto_id, $filtro = NULL)
    {
        $this->db->select('item AS nombre_metadato, meta.valor');
        $this->db->where('tabla_id', 3100); //producto
        $this->db->where('elemento_id', $producto_id);
        $this->db->where('item.categoria_id = 2');  //Metadatos
        $this->db->where('valor IS NOT NULL');  //
        $this->db->order_by('dato_id', 'ASC');
        $this->db->join('item', 'item.id_interno = meta.dato_id');
        
        if ( ! is_null($filtro) ) { $this->db->like('item.filtro', "-{$filtro}-"); }
        
        $query = $this->db->get('meta');
        
        return $query;
    }
    
    function meta($producto_id, $condicion)
    {
        
        $this->db->where('tabla_id', 3100);
        $this->db->where('elemento_id', $producto_id);
        if ( ! is_null($condicion) ) { $this->db->where($condicion); }
        $this->db->order_by('fecha', 'DESC');
        $query = $this->db->get('meta');

        return $query;
    }
    
    function comentarios($producto_id)
    {
        $this->db->select('*, contenido AS texto_comentario, texto_1 AS nombre_usuario, texto_2 AS email_usuario');
        $this->db->where('tipo_id', 23);
        $this->db->where('referente_1_id', 3100);  //cod_tabla = 3100, producto
        $this->db->where('referente_2_id', $producto_id);  //elemento_id, ID del producto
        $this->db->order_by('editado', 'DESC');
        $query = $this->db->get('post');
        
        return $query;
    }

//METADATOS
//---------------------------------------------------------------------------------------------------
    
    function guardar_metadatos($producto_id)
    {
        $this->load->model('Meta_model');
        $metacampos = $this->Meta_model->metacampos(3100, 'editable');
        
        foreach ( $metacampos->result() as $row_campo )
        {
            $name = "meta_{$row_campo->meta_id}";
            
            $registro['valor'] = $this->input->post($name);
            $registro['dato_id'] = $row_campo->meta_id;
            
            $this->guardar_meta_valor($producto_id, $registro);
        }
    }
    
    function guardar_meta_valor($producto_id, $registro)
    {
        //Construir registro
            $registro['tabla_id'] = 3100;  //Tabla producto
            $registro['elemento_id'] = $producto_id;
            $registro['editado'] = date('Y-m-d H:i:s');
            $registro['usuario_id'] = $this->session->userdata('usuario_id');
            
        //Condicion
            $condicion = "tabla_id = {$registro['tabla_id']} AND ";
            $condicion .= "elemento_id = {$registro['elemento_id']} AND ";
            $condicion .= "dato_id = {$registro['dato_id']}";
            
        //Guardando
            $meta_id = $this->Pcrn->guardar('meta', $condicion, $registro);
            
        return $meta_id;
    }
    
    /**
     * Aumentar el número de visitas cuando un usuario ingresa al detalle del producto
     * Se guarda en el metadato con dato_id = 10
     * 
     * @param type $producto_id
     * @return type
     */
    function registrar_visita($producto_id)
    {
        $condicion = "tabla_id = 3100 AND elemento_id = {$producto_id} AND dato_id = 10";
        $meta_id = $this->Pcrn->existe('meta', $condicion);
        if ( $meta_id > 0 ) 
        {
            //Existe
            $sql = "UPDATE meta SET valor = valor + 1, editado = '" . date('Y-m-d H:i:s') . "'  WHERE id = {$meta_id}";
            $this->db->query($sql);
        } else {
            //No existe, insertar
            $registro['valor'] = 1;
            $registro['dato_id'] = 10;  //Código del metadato, cantidad de visitas
            $meta_id = $this->guardar_meta_valor($producto_id, $registro);
        }
        
        return $meta_id;
    }
    
    /**
     * Devuelve el valor con la cantidad de visitas que ha tenido el producto en la sección
     * producto/detalle
     * 
     * @param type $producto_id
     * @return type
     */
    function cant_visitas($producto_id)
    {
        $cant_visitas = 0;
        
        $condicion = "tabla_id = 3100 AND elemento_id = {$producto_id} AND dato_id = 10";
        $meta_id = $this->Pcrn->existe('meta', $condicion);
        if ( $meta_id > 0 ) {
            $cant_visitas = $this->Db_model->field_id('meta', $meta_id, 'valor');
        }
        
        return $cant_visitas;
        
    }
    
//GESTIÓN DE VARIACIONES
//---------------------------------------------------------------------------------------------------------
    
    function variaciones($producto_id)
    {
        //Identificar al padre de los productos, por defecto
        $padre_id = $producto_id;
        
        $row = $this->Pcrn->registro_id('producto', $producto_id);
        if ( $row->tipo_id == 2 ) {
            //El producto es una variación, no un producto original, se identifica al padre
            $padre_id = $row->padre_id;
        }
        
        $this->db->select('*');
        $this->db->where('padre_id', $padre_id);
        $this->db->where('tipo_id', 2); //Tipo 2, variación de un producto
        $this->db->order_by('puntaje', 'ASC');
        $query = $this->db->get('producto');
        
        return $query;
    }
    
    function crear_variacion($producto_id)
    {
        $row = $this->Pcrn->registro_id('producto', $producto_id);
                
        //Datos previos
            $nombre_producto = "{$row->nombre_producto} - Talla {$this->input->post('talla')}";
            $slug = $this->Pcrn->slug_unico($nombre_producto, 'producto', 'nombre_producto');
        
        //Creando registro
            $registro['tipo_id'] = 2;
            $registro['padre_id'] = $producto_id;
            
            $registro['nombre_producto'] = $nombre_producto;
            $registro['slug'] = $slug;
            $registro['imagen_id'] = $row->imagen_id;
            $registro['precio_base'] = $row->precio_base;
            $registro['iva_porcentaje'] = $row->iva_porcentaje;
            $registro['iva'] = $row->iva;
            $registro['precio'] = $row->precio;
            $registro['peso'] = $row->peso;
            $registro['ancho'] = $row->ancho;
            $registro['alto'] = $row->alto;
            $registro['grosor'] = $row->grosor;
            
            $registro['cant_disponibles'] = $this->input->post('cant_disponibles');
            $registro['referencia'] = $this->input->post('referencia');
            $registro['talla'] = $this->input->post('talla');
            $registro['puntaje'] = $this->input->post('puntaje');
            
            $registro['usuario_id'] = $this->session->userdata('usuario_id');
            $registro['creado'] = date('Y-m-d H:i:s');
            $registro['editado'] = date('Y-m-d H:i:s');
            
        $this->db->insert('producto', $registro);
        
        return $this->db->insert_id();
        
    }
    
    /**
     * Cuando se edita un producto padre, también se editan los datos de sus 
     * variaciones
     * 
     * @param type $producto_id
     */
    function act_variaciones($producto_id)
    {
        $row = $this->Pcrn->registro_id('producto', $producto_id);
        
        //Construyendo registro
            $registro['imagen_id'] = $row->imagen_id;
            $registro['precio_base'] = $row->precio_base;
            $registro['iva_porcentaje'] = $row->iva_porcentaje;
            $registro['iva'] = $row->iva;
            $registro['precio'] = $row->precio;
            $registro['costo'] = $row->costo;
            $registro['peso'] = $row->peso;
            $registro['ancho'] = $row->ancho;
            $registro['alto'] = $row->alto;
            $registro['grosor'] = $row->grosor;
            $registro['promocion_id'] = $row->promocion_id;

            $registro['usuario_id'] = $this->session->userdata('usuario_id');
            $registro['editado'] = date('Y-m-d H:i:s');
        
        //Actualizando los registros
            $this->db->where('padre_id', $producto_id); //Que tengan como padre al producto
            $this->db->where('tipo_id', 2);             //Que productos tipo variación
            $this->db->update('producto', $registro);
            
        return $this->db->affected_rows();
        
    }
    
    
//PUNTAJE AUTO
//---------------------------------------------------------------------------------------------------
    
    /**
     * Actualiza el campo producto.puntaje_auto
     */
    function act_puntaje_auto($producto_id)
    {   
        $cant_visitas = $this->cant_visitas($producto_id);
        $cant_pedidos = $this->Pcrn->num_registros('pedido_detalle', "producto_id = {$producto_id}");
        
        $puntaje_auto = $cant_pedidos * 10 + $cant_visitas;
                
        $arr_row['puntaje_auto'] = $puntaje_auto;
        
        $this->db->where('id', $producto_id);
        $this->db->update('producto', $arr_row);

        return $puntaje_auto;
    }
    
    function act_puntaje_auto_masivo()
    {
        $this->db->limit(750);
        $this->db->order_by('puntaje', 'RANDOM');
        $productos = $this->db->get('producto');

        $sum_puntaje = 0;
        
        foreach( $productos->result() as $row_producto )
        {
            $sum_puntaje += $this->act_puntaje_auto($row_producto->id);
        }

        $avg_puntaje = number_format($sum_puntaje / 750, 0);
        
        $data['status'] = 1;
        $data['message'] = 'Actualización ejecutada, promedio: ' . $avg_puntaje;
        
        return $data;
    }
    
//PROCESOS
//---------------------------------------------------------------------------------------------------

    /**
     * Redondea el campo "costo", de las tablas producto y detalle_pedido
     * @return string
     */
    function redondear_costos()
    {
        //Tabla producto
            $productos = $this->db->get('producto');

            foreach ($productos->result() as $row_producto) 
            {
                $registro['costo'] = $this->App_model->redondear($row_producto->costo, 5);

                $this->db->where('id', $row_producto->id);
                $this->db->update('producto', $registro);
            }
        
        //Tabla pedido_detalle
            $pedido_detalle = $this->db->get('pedido_detalle');

            foreach ($pedido_detalle->result() as $row_detalle) 
            {
                $registro['costo'] = $this->App_model->redondear($row_detalle->costo, 5);

                $this->db->where('id', $row_detalle->id);
                $this->db->update('pedido_detalle', $registro);
            }
        
        //Resultado
            $resultado['ejecutado'] = 1;
            $resultado['mensaje'] = 'Productos actualizados: ' . $productos->num_rows();
            $resultado['mensaje'] .= '. Detalles actualizados: ' . $pedido_detalle->num_rows();
            $resultado['clase'] = 'alert-success';
            $resultado['icono'] = 'fa-check';
        
        return $resultado;
    }
    
    /**
     * Actualiza el campo producto.meta para todos los productos
     */
    function act_meta_masivo()
    {
        $this->db->select('id');
        $productos = $this->db->get('producto');
        
        foreach ( $productos->result() as $row_producto ) 
        {
            $this->act_meta($row_producto->id);
        }
        
        $data['status'] = 1;
        $data['message'] = 'Productos actualizados: ' . $productos->num_rows();
        
        return $data;
    }
    
    function act_meta($producto_id)
    {
        $registro['meta'] = $this->str_meta($producto_id);
        
        $this->db->where('id', $producto_id);
        $this->db->update('producto', $registro);
    }
    
    function str_meta($producto_id)
    {
        $this->db->select('meta.valor, item.item');
        $this->db->where('elemento_id', $producto_id);
        $this->db->where('item.categoria_id', 2);
        $this->db->where('item.item_grupo', 1);
        $this->db->join('item', 'item.id_interno = meta.dato_id');
        $meta = $this->db->get('meta');
        
        $str_meta = '';
        
        foreach ( $meta->result() as $row_meta ) 
        {
            $str_meta .= $row_meta->item . ':' . $row_meta->valor . ', ';
        }
        
        return $str_meta;
    }
    
// IMAGES
//-----------------------------------------------------------------------------

    /**
     * Imágenes asociadas al producto
     * 2021-02-24
     */
    function images($product_id)
    {
        $this->db->select('archivo.id, archivo.titulo_archivo AS title, url, url_thumbnail, archivo.integer_1 AS main');
        $this->db->where('es_imagen', 1);
        $this->db->where('table_id', 3100);           //Tabla products
        $this->db->where('related_1', $product_id);   //Relacionado con el product
        $images = $this->db->get('archivo');

        return $images;
    }

    /**
     * Establecer una imagen asociada a un product como la imagen principal (tabla file)
     * 2021-02-24
     */
    function set_main_image($product_id, $file_id)
    {
        $data = array('status' => 0);

        $row_file = $this->Db_model->row_id('archivo', $file_id);
        if ( ! is_null($row_file) )
        {
            //Quitar otro principal
            $this->db->query("UPDATE archivo SET integer_1 = 0 WHERE table_id = 3100 AND related_1 = {$product_id} AND integer_1 = 1");

            //Poner nuevo principal
            $this->db->query("UPDATE archivo SET integer_1 = 1 WHERE id = {$file_id} AND related_1 = {$product_id}");

            //Actualizar registro en tabla products
            $arr_row['imagen_id'] = $row_file->id;
            $arr_row['url_image'] = $row_file->url;
            $arr_row['url_thumbnail'] = $row_file->url_thumbnail;

            $this->db->where('id', $product_id);
            $this->db->update('producto', $arr_row);

            $data['status'] = 1;
        }

        return $data;
    }
    
//GESTIÓN DE PALABRAS CLAVE
//---------------------------------------------------------------------------------------------------
    
    function palabras_clave($producto_id)
    {
        $this->db->select('meta.id AS meta_id, item.item AS palabra');
        $this->db->where('tabla_id', 3100);    //Tabla producto
        $this->db->where('elemento_id', $producto_id);
        $this->db->where('dato_id', 20);    //15, palabras clave, ver item.id_interno, tag_id = 2
        $this->db->join('item', 'meta.relacionado_id = item.id');
        $palabras_clave = $this->db->get('meta');
        
        return $palabras_clave;
    }
    
    function agregar_palabra($producto_id)
    {
        //Preparar registro
            $registro['tabla_id'] = 3100;
            $registro['elemento_id'] = $producto_id;
            $registro['relacionado_id'] = $this->input->post('relacionado_id');
            $registro['dato_id'] = 20;  //Palabra clave
            
        //Condicion
            $condicion = "tabla_id = {$registro['tabla_id']} AND ";
            $condicion .= "elemento_id = {$registro['elemento_id']} AND ";
            $condicion .= "relacionado_id = {$registro['relacionado_id']} AND ";
            $condicion .= "dato_id = {$registro['dato_id']}";
            
        //Guardando
            $meta_id = $this->Pcrn->insertar_si('meta', $condicion, $registro);
            
        return $meta_id;
    }
    
//GESTIÓN DE TAGS
//---------------------------------------------------------------------------------------------------
    
    function tags($producto_id)
    {
        //$select_label = $this->Datos_model->select_tags();   
        $condicion = "id IN (SELECT relacionado_id FROM meta WHERE tabla_id = 3100 AND elemento_id = {$producto_id} AND dato_id = 21)";
        
        $this->db->select('id, item AS nombre_tag, descripcion, filtro, padre_id, slug, ascendencia, orden AS nivel');
        $this->db->where($condicion);
        $tags = $this->db->get('item');
        
        return $tags;
    }
    
    function in_tag($producto_id, $tag_id)
    {
        $meta_id = 0;
        
        $condicion = "tabla_id = 3100 AND elemento_id = {$producto_id} AND relacionado_id = {$tag_id} AND dato_id = 21";
        $row_meta = $this->Pcrn->registro('meta', $condicion);
        
        if ( ! is_null($row_meta) ) { $meta_id = $row_meta->id; } 
        
        return $meta_id;
    }
    
    function agregar_etiqueta($producto_id, $tag_id)
    {
        //Preparar registro
            $registro['tabla_id'] = 3100;
            $registro['elemento_id'] = $producto_id;
            $registro['relacionado_id'] = $tag_id;
            $registro['dato_id'] = 21;  //Categoría
            $registro['fecha'] = date('Y-m-d H:i:s');
            $registro['usuario_id'] = $this->session->userdata('usuario_id');
            $registro['editado'] = date('Y-m-d H:i:s');
            
        //Condicion
            $condicion = "tabla_id = {$registro['tabla_id']} AND ";
            $condicion .= "elemento_id = {$registro['elemento_id']} AND ";
            $condicion .= "relacionado_id = {$registro['relacionado_id']} AND ";
            $condicion .= "dato_id = {$registro['dato_id']}";
            
        //Guardando
            $meta_id = $this->Pcrn->insertar_si('meta', $condicion, $registro);
            
        return $meta_id;
    }
    
    /**
     * Actualiza el listado de categorías asignadas a un producto en la tabla meta
     * 
     * @param type $producto_id
     * @param type $tags
     */
    function act_tags($producto_id, $tags)
    {
        //Eliminar tags actuales
            $this->db->where('tabla_id', 3100);
            $this->db->where('elemento_id', $producto_id);
            $this->db->where('dato_id', 21);    //Categoría
            $this->db->delete('meta');
        
        //Agregar tags
            if ( count($tags) > 0 ) 
            {
                foreach ( $tags as $tag_id ) 
                {
                    $this->agregar_etiqueta($producto_id, $tag_id);
                }
            }
    }
    
    /**
     * Query con todas categorías de producto,
     * Incluye la columna activo con el meta.id si la categoría está asignada al producto
     * 
     * @param type $producto_id
     * @return type
     */
    function tags_activas($producto_id)
    {
        $this->db->select('item.id, item AS nombre_tag, IFNULL(meta.id, (0)) AS activo, item.orden AS nivel');
        $this->db->where('categoria_id', 21);
        $condicion_join = "item.id = meta.relacionado_id AND tabla_id = 3100 AND dato_id = 21 AND elemento_id = {$producto_id}";
        $this->db->join('meta', $condicion_join, 'left');
        $this->db->order_by('ascendencia', 'ASC');
        $this->db->order_by('item', 'ASC');
        $query = $this->db->get('item');
        
        return $query;
    }
    
//GESTIÓN DE LISTAS
//---------------------------------------------------------------------------------------------------
    
    function listas($producto_id)
    {
        $this->db->select('meta.id AS meta_id, item.item AS nombre_lista');
        $this->db->where('tabla_id', 3100);    //Tabla producto
        $this->db->where('elemento_id', $producto_id);
        $this->db->where('dato_id', 22);    //22, listas, ver item.id_interno
        $this->db->join('item', 'meta.relacionado_id = item.id');
        $this->db->order_by('ascendencia', 'ASC');
        $this->db->order_by('item', 'ASC');
        $palabras_clave = $this->db->get('meta');
        
        return $palabras_clave;
    }
    
    function agregar_lista($producto_id, $lista_id)
    {
        //Preparar registro
            $registro['tabla_id'] = 3100;
            $registro['elemento_id'] = $producto_id;
            $registro['relacionado_id'] = $lista_id;
            $registro['dato_id'] = 22;  //Lista
            
        //Condicion
            $condicion = "tabla_id = {$registro['tabla_id']} AND ";
            $condicion .= "elemento_id = {$registro['elemento_id']} AND ";
            $condicion .= "relacionado_id = {$registro['relacionado_id']} AND ";
            $condicion .= "dato_id = {$registro['dato_id']}";
            
        //Guardando
            $meta_id = $this->Pcrn->insertar_si('meta', $condicion, $registro);
            
        return $meta_id;
    }
    
    
//DATOS PRODUCTOS
//---------------------------------------------------------------------------------------------------
    
    function recomendados($cantidad)
    {
        $this->load->model('Busqueda_model');
        $lista_id = $this->App_model->valor_opcion(10); //Recomendados
        $busqueda['list'] = $lista_id;
        
        $recomendados = $this->Busqueda_model->productos($busqueda, $cantidad);
        
        return $recomendados;
        
    }
    
    function fabricantes()
    {
        $this->db->select('id, item AS nombre_fabricante');
        $this->db->where('categoria_id', 5);    //Fabricantes
        $this->db->order_by('item', 'ASC');
        $query = $this->db->get('item');
        
        return $query;
    }
    
//PROCESOS
//---------------------------------------------------------------------------------------------------
    
    function act_estado($producto_id, $estado)
    {
        $registro['estado'] = $estado;
        $registro['editado'] = date('Y-m-d H:i:s');
        $registro['usuario_id'] = $this->session->userdata('usuario_id');
        
        $this->db->where('id', $producto_id);
        $this->db->update('producto', $registro);
        
        return $this->db->affected_rows();
    }
    
    function actualizar_slug()
    {
        
        //Seleccionar registros
        $this->db->where('slug IS NULL');
        $productos = $this->db->get('producto');
        
        foreach ($productos->result() as $row_producto) {
            $registro['slug'] = $this->Pcrn->slug_unico($row_producto->nombre_producto, 'producto', 'slug');
            
            $this->db->where('id', $row_producto->id);
            $this->db->update('producto', $registro);
        }
        
        $data['resultado'] = 1;
        $data['mensaje'] = '<i class="fa fa-check"></i> Se actualizaron ' . $productos->num_rows() . ' registros';
        $data['clase_alerta'] = 'alert-success';
        
        return $data;
    }
    
    /**
     * Asocia archivos de imagen con productos de manera masiva
     * Revisa los archivos de la carpeta uploads/img_inicial y los compara
     * con el campo producto.img_inicial, si conincide el valor del campo
     * con el nombre del archivo, se asocia el producto con el archivo
     * 
     * @return string
     */
    function asociar_img_inicial_masivo()
    {   
        $this->load->helper('string');
        $this->load->model('Archivo_model');
        $carpeta_origen = RUTA_UPLOADS . 'img_inicial/';
        $coincidencias = 0;
        $campo = 'img_inicial';
        
        $this->load->helper('file');
        
        $archivos = get_filenames( $carpeta_origen );
        
        foreach ($archivos as $archivo) {
            
            //Buscar producto
                $titulo = substr($archivo, 0, -4);      //Sin extensión 4 caracteres '.jpg'
                
                $condicion = "{$campo} = '{$titulo}'";  //Condición para asociar, que coincida el nombre del archivo con el campo img_inicial
                //echo "{$condicion}<br/>";
                $row_producto = $this->Pcrn->registro('producto', $condicion);
            
            if ( ! is_null($row_producto) ) {
                $this->asociar_img_inicial($row_producto, $archivo);
                $coincidencias++;
            }
        }
        
        $data['resultado'] = 1;
        $data['mensaje'] = '<i class="fa fa-check"></i> Se asignaron imágenes a ' . $coincidencias . ' productos';
        $data['clase_alerta'] = 'alert-success';
        
        return $data;
    }
    
    /**
     * Establecer masivamente la imagen principal los productos.
     * Se actualiza el campo producto.imagen_id
     * 
     * @return string
     */
    function establecer_img_si_masivo()
    {   
        $cantidad = 0;
        $productos = $this->db->get_where('meta', 'tabla_id = 3100 AND dato_id = 1');
        
        foreach ( $productos->result() as $row_producto) {
            $cantidad += $this->establecer_img_si($row_producto->elemento_id, $row_producto->relacionado_id);
        }
        
        $data['resultado'] = 1;
        $data['mensaje'] = '<i class="fa fa-check"></i> Se establecieron imágenes a ' . $cantidad . ' productos';
        $data['clase_alerta'] = 'alert-success';
        
        return $data;
    }
    
    function depurar_img_principal()
    {
        $cantidad = 0;
        
        //Seleccionar de productos
            $this->db->where('imagen_id IS NOT NULL');
            $productos = $this->db->get('producto');
        
        //Registro para actualizar
            $registro['imagen_id'] = NULL;
        
        //Recorrer productos
            foreach ( $productos->result() as $row_producto ) {
                $condicion = "id = {$row_producto->imagen_id}";
                $cant_registros = $this->Pcrn->num_registros('archivo', $condicion);

                //Si el archivo no existe, se actualiza imagen_id = NULL
                if ( $cant_registros == 0 ) {
                    $this->db->where('id', $row_producto->id);
                    $this->db->update('producto', $registro);
                    
                    $cantidad++;
                }
            }
            
        //Resultados
            $data['resultado'] = 1;
            $data['mensaje'] = '<i class="fa fa-check"></i> Se actualizaron ' . $cantidad . ' productos';
            $data['clase_alerta'] = 'alert-success';
        
        return $data;
    }
    
    /**
     * Relaciona una imagen en la carpeta uploads/img_inicial
     * con un registro de producto, se crea el registro de archivo en la tabla archivo
     * y se crea un registro en la tabla meta que asocia producto y archivo
     * 
     * @param type $row_producto
     * @param type $nombre_archivo
     */
    function asociar_img_inicial($row_producto, $nombre_archivo)
    {
        $carpeta_origen = RUTA_UPLOADS . 'img_inicial/';
        $carpeta_destino = RUTA_UPLOADS . date('Y/m/');
        
        $nuevo_nombre = $row_producto->id . '-' . random_string('alnum', 8) . '-' . $nombre_archivo;
        
        //Mover archivo
            rename($carpeta_origen . $nombre_archivo, $carpeta_destino . $nuevo_nombre);

        //Crear registro de archivo
            $file_data['file_name'] = $nuevo_nombre;
            $file_data['orig_name'] = $nombre_archivo;
            $archivo_id = $this->Archivo_model->crear_registro($file_data);

        //Procesar archivo y asociar        
            $this->Archivo_model->crear_miniaturas($archivo_id);
            $this->Archivo_model->mod_original_id($archivo_id);    //Mofificar imagen original después de crear miniaturas
            $this->asociar_img($row_producto->id, $archivo_id);
            $this->establecer_img_si($row_producto->id, $archivo_id);
        
    }
    
// PRECIOS
//-----------------------------------------------------------------------------
    
    /**
     * Array con el precio que se le aplica a un producto en el momento de cargarlo 
     * a un pedido, se escoge entre los diferentes precios que puede tener un 
     * producto según sus ofertas o promociones aplicables.
     */
    function arr_precio($producto_id)
    {
        $arr_precios = $this->arr_precios($producto_id);
        
        //Si no es distribuidor, se elimina la opción de precio para distribuidor
            if ( $this->session->userdata('rol_id') != 22 ) 
            {
                unset($arr_precios[2]);
            }
        
        //Se aplica el primer precio en el array, al estar ordenado ASC por precio
            $key_first = array_key_first($arr_precios);
            $arr_precio['promocion_id'] = $key_first;
            $arr_precio['precio'] = $arr_precios[$key_first];
        
        return $arr_precio;
    }
    
    /**
     * Array con los diferentes precios que se le pueden aplicar a un producto
     * 
     */
    function arr_precios($producto_id)
    {
        $row_producto = $this->row_principal($producto_id);
        
        //Precios normal
            $arr_precios[1] = $row_producto->precio;                        //Precio normal
            $arr_precios[2] = $this->precio_distribuidor($row_producto);    //Precio para distribuidor o mayorista
            
        //Precio promoción del producto, el índice es el ID de la promoción
            if ( $row_producto->promocion_id > 0 ) //Si tiene alguna promoción aplicada
            {
                $arr_precios[$row_producto->promocion_id] = $this->precio_promocion($row_producto);
            }
            
        //Organizar de menor a mayor, el precio más bajo queda en el primer lugar
            asort($arr_precios);
        
        return $arr_precios;
    }
    
    /**
     * Devuelve el precio de un producto que se le aplica a un distribuidor,
     * se hace un descuento dependiendo del fabricante del producto.
     * 
     * @param type $row_producto
     * @return type
     */
    function precio_distribuidor($row_producto)
    {
        $pct_fabricante = $this->pct_fabricante($row_producto->fabricante_id);
        $precio_distribuidor_pre = $row_producto->precio * ( 100 - $pct_fabricante)/100;
        $precio_distribuidor = $this->App_model->redondear($precio_distribuidor_pre);
        
        return $precio_distribuidor;
    }
    
    /**
     * Porcentaje de descuento que se aplica a un producto dependiendo del fabri
     * cante del producto. En valores enteros, no decimal.
     * 
     * @param type $fabricante_id
     */
    function pct_fabricante($fabricante_id)
    {
        $pct_fabricante = $this->Db_model->field_id('item', $fabricante_id, 'entero_1');
        
        if ( is_null($pct_fabricante) ) { $pct_fabricante = 0; }
        
        return $pct_fabricante;
    }
    
    function precio_promocion($row_producto)
    {
        $row_promocion = $this->row_promocion($row_producto->promocion_id);
        
        $pct_descuento = $row_promocion->pct_descuento;
        $precio_promocion_pre = $row_producto->precio * ( 100 - $pct_descuento)/100;
        $precio_promocion = $this->App_model->redondear($precio_promocion_pre);
        
        return $precio_promocion;
    }
    
    
    /**
     * Array con Id y nombre de tipos de precio, corresponde a precio normal,
     * descuentos del sistema y promociones creadas.
     * 
     * y promociones creadas.
     * @return type}
     */
    function arr_tipos_precio() 
    {
        //Tipos base, del sistema
            $arr_descuentos = array(
                1 =>    'Precio normal',
                2 =>    'Precio para distribuidor',
            );
        
        //Agregar promociones creadas en la tabla post, tipo 31001
            $promociones = $this->promociones();
            foreach ($promociones->result() as $row_promocion)
            {
                $arr_descuentos[$row_promocion->id] = $row_promocion->nombre_promocion;
            }
        
        return $arr_descuentos;
    }
    
// PROMOCIONES
//-----------------------------------------------------------------------------
    
    /**
     * Segmento SELECT SQL de la tabla post, para los post.tipo_id = 31001
     * correspondiente a  promociones.
     * 
     * @return string
     */
    function select_promocion()
    {
        $select_promocion = 'id, nombre_post AS nombre_promocion, resumen, estado, referente_1_id AS pct_descuento';
        $select_promocion .= ', usuario_id, editor_id, creado, editado';
        
        return $select_promocion;
    }
    
    /**
     * Row, de una promoción (tabla post)
     * 
     * @param type $promocion_id
     * @return type
     */
    function row_promocion($promocion_id)
    {
        $row_promocion = NULL;  //Valor por defecto
        
        $promociones = $this->promociones("id = {$promocion_id}");
        if ( $promociones->num_rows() > 0 ) { $row_promocion = $promociones->row(); }
        
        return $row_promocion;
    }
    
    /**
     * Query con promociones creadas en la tabla post,
     * post.tipo_id = 31001 corresponde a promociones creadas
     * 
     * @param type $condicion
     * @return type
     */
    function promociones($condicion = 'id > 0')
    {
        $this->db->select($this->select_promocion());
        $this->db->where('tipo_id', 31001); //Post tipo promoción
        $this->db->where($condicion);
        $this->db->order_by('nombre_post', 'ASC');
        $query = $this->db->get('post');
        
        return $query;
    }
    
    
    
//CARGUE MASIVO DE DATOS
//---------------------------------------------------------------------------------------------------
    
    /**
     * Recorrer array de excel para cargue de productos
     * 
     * @param type $array_excel
     * @return type
     */
    function cargar($array_excel)
    {       
        //Variables
            $cant_cargados = 0;
            $editado = date('Y-m-d H:i:s');
        
        //Predeterminados registro nuevo
            $registro['usuario_id'] = $this->session->userdata('usuario_id');
            $registro['editado'] = $editado;
            $registro['creado'] = $editado;
        
        foreach ( $array_excel as $row_elemento ) {
            $cant_cargados += $this->cargar_producto($row_elemento, $registro);
        }
        
        $res_cargue['cant_cargados'] = $cant_cargados;
        $res_cargue['e'] = str_replace(array(' ', '-', ':'), '', $editado); //Para mostrar resultados, $busqueda['e']
        
        return $res_cargue;
    }
    
    /**
     * Leer fila de array excel para guardar registro de producto
     * 
     * @param type $row_elemento
     * @param type $registro
     * @return int
     */
    function cargar_producto($row_elemento, $registro)
    {
        $cargado = 0;
        $producto_id = $this->Pcrn->si_nulo($row_elemento[0], 0);
        
        if ( strlen($row_elemento[1]) > 1 ) {
                
            //Registro
                $registro['nombre_producto'] = $row_elemento[1];
                $registro['slug'] = $this->Pcrn->slug_unico($row_elemento[1], 'producto', 'slug');
                $registro['descripcion'] = $row_elemento[2];
                $registro['precio_base'] = $row_elemento[3];
                $registro['iva'] = $row_elemento[4];
                $registro['precio'] = $row_elemento[5];
                $registro['peso'] = $row_elemento[6];
                $registro['ancho'] = $row_elemento[7];
                $registro['alto'] = $row_elemento[8];
                $registro['grosor'] = $row_elemento[9];
                $registro['cant_disponibles'] = $row_elemento[10];
                $registro['referencia'] = $row_elemento[11];
                $registro['fabricante_id'] = $row_elemento[12];
                $registro['img_inicial'] = $row_elemento[13];

            //Guardar
                $condicion = "id = {$producto_id}";
                $producto_id = $this->Pcrn->guardar('producto', $condicion, $registro);
                
            //Agregar a categoría
                $this->agregar_etiqueta($producto_id, $row_elemento[14]);  //Columna M
                
            //Agregar metadatos
                $this->cargar_meta($producto_id, $row_elemento);

            $cargado = 1;
        }
        
        return $cargado;
    }
    
    /**
     * Le asigna los metadatos al producto a partir 
     * de la fila de datos de excel ($row_elemento)
     * 
     * @param type $producto_id
     * @param type $row_elemento
     */
    function cargar_meta($producto_id, $row_elemento)
    {
        //Relación de columnas con metadato meta.dato_id
            $columnas['15'] = 31001;   //N: Autor
            $columnas['16'] = 31002;   //O: Colección
            $columnas['17'] = 31003;   //P: Páginas
            $columnas['18'] = 31004;   //Q: Letra
            $columnas['19'] = 31005;   //R: ISBN
            //$columnas['18'] = 31006;   //S: IVA
        
        foreach ($columnas as $num_col => $dato_id) 
        {
            if ( strlen($row_elemento[$num_col]) > 0 ) //Si no está vacío
            {
                $reg_meta['dato_id'] = $dato_id;
                $reg_meta['valor'] = $row_elemento[$num_col];
                $this->guardar_meta_valor($producto_id, $reg_meta);
            }
        }
    }
    
    /**
     * Actualiza masivamente datos de productos.
     * 
     * @param type $array_hoja    Array con los datos de los productos
     */
    function actualizar_datos($array_hoja)
    {   
        $this->load->model('Esp');
        
        $no_importados = array();
        $fila = 2;  //Inicia en la fila 2 de la hoja de cálculo

        //Predeterminados registro editado
            $registro['usuario_id'] = $this->session->userdata('usuario_id');
            $registro['editado'] = date('Y-m-d H:i:s');
        
        foreach ( $array_hoja as $array_fila )
        {
            //Identificar valores
                $referencia = trim($array_fila[0]); //Sin espacios finales e iniciales
                $producto_id = $this->Pcrn->campo('producto', "referencia = '{$referencia}'", 'id');
            
            //Complementar registro
                $registro['cant_disponibles'] = $array_fila[1];
                $registro['precio'] = $array_fila[2];
                $registro['iva_porcentaje'] = $this->Pcrn->si_strlen($array_fila[3], 0);
                $registro['precio_base'] = $array_fila[2] / ( 1 + ($registro['iva_porcentaje']/100) );
                $registro['iva'] = $registro['precio'] - $registro['precio_base'];
                
            //Campos activos
                //Revisar campos de precios
                $act_precios = TRUE;
                if ( strlen($registro['precio']) == 0 ) { $act_precios = FALSE; }
                if ( strlen($registro['iva_porcentaje']) == 0 ) { $act_precios = FALSE; }
                
                //
                if ( ! $act_precios ) {
                    unset($registro['precio']);
                    unset($registro['iva_porcentaje']);
                    unset($registro['precio_base']);
                    unset($registro['precio_base']);
                }
                
            //Validar
                $condiciones = 0;
                if ( ! is_null($producto_id) ) { $condiciones++; }   //Tiene producto identificado
                if ( $array_fila[1] >= 0 ) { $condiciones++; }       //La cantidad disponible es un cero o mayor
                
            //Si cumple las condiciones
            if ( $condiciones == 2 )
            {   
                $this->Pcrn->guardar('producto', "id = '{$producto_id}'", $registro);
            } else {
                $no_importados[] = $fila;
            }
            
            $fila++;    //Para siguiente fila
        }
        
        return $no_importados;
    }

// GESTIÓN DE POSTS ASOCIADOS
//-----------------------------------------------------------------------------

    /**
     * Asignar un contenido de la tabla post a un producto, lo agrega como metadato
     * en la tabla meta, con el tipo 310012
     * 2021-01-27
     */
    function add_post($producto_id, $post_id)
    {
        //Construir registro
        $arr_row['tabla_id'] = 3100;    //producto
        $arr_row['dato_id'] = 310012;   //Asignación de post a un producto
        $arr_row['elemento_id'] = $producto_id; //Producto ID, al que se asigna
        $arr_row['relacionado_id'] = $post_id;  //ID contenido
        $arr_row['usuario_id'] = 0;  //Usuario que asigna

        //Establecer usuario que ejecuta
        if ( $this->session->userdata('logged') ) {
            $arr_row['usuario_id'] = $this->session->userdata('user_id');
        }

        $condition = "dato_id = {$arr_row['dato_id']} AND elemento_id = {$arr_row['elemento_id']} AND relacionado_id = {$arr_row['relacionado_id']}";
        $meta_id = $this->Db_model->save('meta', $condition, $arr_row);

        //Establecer resultado
        $data = array('status' => 0, 'saved_id' => '0');
        if ( $meta_id > 0) { $data = array('status' => 1, 'saved_id' => $meta_id); }

        return $data;
    }

    /**
     * Quita la asignación de un post a un producto
     * 2021-01-27
     */
    function remove_post($producto_id, $meta_id)
    {
        $data = array('status' => 0, 'qty_deleted' => 0);

        $this->db->where('id', $meta_id);
        $this->db->where('elemento_id', $producto_id);
        $this->db->delete('meta');

        $data['qty_deleted'] = $this->db->affected_rows();

        if ( $data['qty_deleted'] > 0) { $data['status'] = 1; }

        return $data;
    }

    /**
     * Contenidos digitales asignados a un producto
     */
    function assigned_posts($producto_id)
    {
        $this->db->select('post.id, nombre_post AS title, code, slug, resumen, post.estado, publicado, meta.id AS meta_id, post.imagen_id');
        $this->db->join('meta', 'post.id = meta.relacionado_id');
        $this->db->order_by('post.id', 'ASC');
        $this->db->where('meta.dato_id', 310012);   //Asignación de contenido
        $this->db->where('meta.elemento_id', $producto_id);

        $posts = $this->db->get('post');
        
        return $posts;
    }
}