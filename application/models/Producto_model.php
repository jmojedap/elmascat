<?php
class Producto_Model extends CI_Model{
    
    function basico($producto_id)
    {
        
        $row = $this->row_principal($producto_id);
        
        //Imagen principal
        $basico['row'] = $row;
        $basico['row_variacion'] = $this->Pcrn->registro_id('producto', $producto_id);
        $basico['elemento_s'] = 'producto';
        $basico['elemento_p'] = 'productos';
        $basico['tabla_id'] = 3100;
        $basico['cant_visitas'] = $this->cant_visitas($producto_id);
        $basico['cant_pedidos'] = $this->Pcrn->num_registros('pedido_detalle', "producto_id = {$producto_id}");
        $basico['imagenes'] = $this->imagenes($producto_id);
        $basico['row_archivo'] = $this->Pcrn->registro_id('archivo', $row->imagen_id);
        $basico['titulo_pagina'] = $row->nombre_producto;
        $basico['vista_a'] = 'productos/producto_v';
        $basico['head_title'] = $row->nombre_producto;
        $basico['view_a'] = 'productos/producto_v';
        
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
    
    function producto_id()
    {
        $producto_id = 0;
        
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('producto');
        
        if ( $query->num_rows() > 0 ) {
            $producto_id = $query->row()->id;
        }
        
        return $producto_id;
    }
    
    /**
     * Igual a la función busqueda, pero agrega una restricción, producto debe
     * estar activo
     * 
     * @param array $busqueda
     * @param type $per_page
     * @param type $offset
     * @return type
     */
    function catalogo($busqueda, $per_page = NULL, $offset = NULL)
    {
        $busqueda['etd'] = 1;   //Solo productos activos
        $query = $this->buscar($busqueda, $per_page, $offset);
        return $query;
    }
    
    /**
     * Array con los datos para la vista de exploración
     * 
     * @return string
     */
    function data_explorar()
    {
        //Elemento de exploración
            $data['elemento_p'] = 'productos';    //Nombre del elemento el plural
            $data['elemento_s'] = 'producto';      //Nombre del elemento en singular
            $data['controlador'] = 'productos';   //Nombre del controlador
            $data['funcion'] = 'explorar';      //Función de exploración
            $data['cf'] = $data['controlador'] . '/' . $data['funcion'] . '/';      //Controlador función
            $data['carpeta_vistas'] = 'productos/';   //Carpeta donde están las vistas de exploración
            $data['titulo_pagina'] = 'Productos';
        
        //Paginación
            $data['per_page'] = 20; //Cantidad de registros por página
            $data['offset'] = $this->input->get('per_page');
            
        //Búsqueda y Resultados
            $this->load->model('Busqueda_model');
            $data['busqueda'] = $this->Busqueda_model->busqueda_array();
            $data['busqueda_str'] = $this->Busqueda_model->busqueda_str();
            $data['resultados'] = $this->Producto_model->buscar($data['busqueda'], $data['per_page'], $data['offset']);    //Resultados para página
            $data['cant_resultados'] = $this->Producto_model->cant_resultados($data['busqueda']);
            
        //Otros
            $data['url_paginacion'] = base_url("{$data['cf']}?{$data['busqueda_str']}");
            $data['seleccionados_todos'] = '-'. $this->Pcrn->query_to_str($data['resultados'], 'id');   //Para selección masiva de todos los elementos de la página

        //Visitas
            $data['subtitulo_pagina'] = $data['cant_resultados'];
            $data['vista_a'] = $data['carpeta_vistas'] . 'explorar/explorar_v';
            $data['vista_menu'] = $data['carpeta_vistas'] . 'explorar/menu_v';
        
        return $data;
    }
    
    function buscar($busqueda, $per_page = NULL, $offset = NULL)
    {
        
        //Consultas previas
            $descendencia = '0';
            if ( $busqueda['tag'] != '' ) 
            {
                $this->load->model('Datos_model');
                $tag_id = (int) $busqueda['tag'];
                $descendencia = $this->Datos_model->descendencia($tag_id, 'string');
            }

        //Construir búsqueda
        //Crear array con términos de búsqueda
            if ( strlen($busqueda['q']) > 2 )
            {
                $palabras = $this->Busqueda_model->palabras($busqueda['q']);
                
                $campos = array('producto.id', 'nombre_producto', 'producto.palabras_clave', 'producto.referencia', 'producto.meta', 'producto.descripcion');
                $concat_campos = $this->Busqueda_model->concat_campos($campos);

                foreach ($palabras as $palabra) 
                {
                    $this->db->like("CONCAT({$concat_campos})", $palabra);
                }
            }
            
        //Especificaciones de consulta
            $this->db->select('producto.*, archivo.nombre_archivo, archivo.carpeta, cant_disponibles');
            $this->db->where('producto.tipo_id', 1); //Producto padre, que no sea una variación
            $this->db->join('archivo', 'producto.imagen_id = archivo.id', 'LEFT');
            
        //Filtros por rol y estado login
            if ( $this->session->userdata('logged') ) 
            {
                //Logueado
                if ( ! in_array($this->session->userdata('rol_id'), array(0,1,2)) )
                {
                    $this->db->where('precio > 0');
                }
            } else {
                //Para clientes sin loguear
                $this->db->where('precio > 0');
            }
            
        //Orden
            $order_type = 'DESC';
            if ( $busqueda['ot'] != '' ) 
            {
                $order_type = $busqueda['ot'];
            }
            
            if ( $busqueda['o'] != '' ) 
            {
                $this->db->order_by($busqueda['o'], $order_type);
            }
            
            $this->db->order_by('puntaje', 'DESC');
            $this->db->order_by('puntaje_auto', 'DESC');
            
        //Otros filtros
            if ( $busqueda['e'] != '' ) { $this->db->where('editado', $busqueda['e']); }        //Editado
            if ( $busqueda['tag'] != '' ) 
            {
                $this->db->where("producto.id IN (SELECT elemento_id FROM meta WHERE tabla_id = 3100 AND dato_id = 21 AND relacionado_id IN({$descendencia}) )");
            }
            
            if ( $busqueda['cat'] != '' ) { $this->db->where('categoria_id', $busqueda['cat']); }       //Categoría del producto
            if ( $busqueda['fab'] != '' ) { $this->db->where('fabricante_id', $busqueda['fab']); }      //Fabricante del producto
            if ( $busqueda['prc_min'] != '' ) { $this->db->where("precio >= {$busqueda['prc_min']}"); }    //Precio mínimo
            if ( $busqueda['prc_max'] != '' ) { $this->db->where("precio <= {$busqueda['prc_max']}"); }    //Precio máximo
            if ( $busqueda['etd'] != '' ) { $this->db->where('estado', $busqueda['etd']); }     //Estado del producto
            if ( $busqueda['ofrt'] == 1 ) { $this->db->where('promocion_id <> 0'); }         //Producto en oferta
            if ( $busqueda['est'] != '' ) { $this->db->where('estado', $busqueda['est']); }     //Estado del producto
            if ( $busqueda['fi'] != '' ) { $this->db->where('producto.creado >=', $busqueda['fi']); }     //Fecha inicial mínima en la que fue creado
            if ( $busqueda['dcto'] != '' ) { $this->db->where('promocion_id', $busqueda['dcto']); }     //Descuento aplicado
            
        //Obtener resultados
        if ( is_null($per_page) ){
            $query = $this->db->get('producto'); //Resultados totales
        } else {
            $query = $this->db->get('producto', $per_page, $offset); //Resultados por página
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
        $data = $this->buscar($busqueda); //Para calcular el total de resultados
        return $data->num_rows();
    }
    
    function crud_editar()
    {
        //Grocery crud
        $this->load->library('grocery_CRUD');
        
        $crud = new grocery_CRUD();
        $crud->set_table('producto');
        $crud->set_subject('producto');
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_back_to_list();
        $crud->unset_delete();
        
        //Títulos
            $crud->display_as('descripcion', 'Descripción');
            $crud->display_as('peso', 'Peso (gramos)');
            $crud->display_as('ancho', 'Ancho (cm)');
            $crud->display_as('alto', 'Alto (cm)');
            $crud->display_as('fabricante_id', 'Fabricante');
            $crud->display_as('iva', 'Valor IVA');
            $crud->display_as('iva_porcentaje', '% IVA');
            
        //Campos
            $crud->edit_fields(
                    'referencia',
                    'nombre_producto',
                    'descripcion',
                    'precio_base',
                    'iva_porcentaje',
                    'iva',
                    'precio',
                    'cant_disponibles',
                    'peso',
                    'ancho',
                    'alto',
                    //'grosor',
                    'fabricante_id',
                    'usuario_id',
                    'editado'
                );
            
            $crud->add_fields(
                    'referencia',
                    'nombre_producto',
                    'descripcion',
                    'precio_base',
                    'iva_porcentaje',
                    'iva',
                    'precio',
                    'cant_disponibles',
                    'peso',
                    'ancho',
                    'alto',
                    //'grosor',
                    'fabricante_id',
                    'usuario_id',
                    'editado',
                    'creado'
                );
        //Relaciones
            $crud->set_relation('fabricante_id', 'item', 'item', 'tag_id = 5');
            
        //Reglas
            $crud->required_fields('nombre_producto', 'desrcipcion', 'precio_base', 'iva', 'precio', 'peso', 'cant_disponibles', 'referencia');
            $crud->set_rules('precio', 'Precio', 'is_natural');
            $crud->set_rules('cant_disponibles', 'Cant Disponibles', 'is_natural');
            //$crud->set_rules('peso', 'Peso', 'greater_than[0]');
            $crud->set_rules('alto', 'Alto', 'greater_than[0]');
            $crud->set_rules('ancho', 'Ancho', 'greater_than[0]');
            //$crud->set_rules('grosor', 'Grosor', 'is_natural');
        
        //Valores por defecto
            $crud->field_type('creado', 'hidden', date('Y-m-d H:i:s'));
            $crud->field_type('editado', 'hidden', date('Y-m-d H:i:s'));
            $crud->field_type('usuario_id', 'hidden', $this->session->userdata('usuario_id'));
            
                        
        //Formato
            $crud->unset_texteditor('descripcion');
        
        $output = $crud->render();
        
        return $output;
    }
    
    /** 
     * Devuelve render de formulario grocery crud para la edición
     * de datos de producto
     */
    function crud_nuevo()
    {
        //Grocery crud
        $this->load->library('grocery_CRUD');
        
        $crud = new grocery_CRUD();
        $crud->set_table('producto');
        $crud->set_subject('producto');
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_back_to_list();
        $crud->unset_delete();
        $crud->unset_edit();
        
        //Títulos
            $crud->display_as('descripcion', 'Descripción');
            $crud->display_as('peso', 'Peso (gramos)');
            $crud->display_as('ancho', 'Ancho (cm)');
            $crud->display_as('alto', 'Alto (cm)');
            $crud->display_as('fabricante_id', 'Fabricante');
            $crud->display_as('iva', 'Valor IVA');
            $crud->display_as('iva_porcentaje', '% IVA');
            $crud->display_as('precio', 'Precio venta');
            $crud->display_as('costo', 'Costo compra o fabricación');
            
        //Campos
            $crud->add_fields(
                    'referencia',
                    'nombre_producto',
                    'descripcion',
                    'precio',
                    'iva_porcentaje',
                    'costo',
                    'cant_disponibles',
                    'peso',
                    'iva',
                    'precio_base',
                    'estado',
                    'usuario_id',
                    'editado',
                    'creado'
                );
        //Relaciones
            $crud->set_relation('fabricante_id', 'item', 'item', 'tag_id = 5');
            
        //Redirigir después de enviar el mensaje
            $controller = 'productos';
            $function = 'editar_reciente';
            $crud->set_lang_string('insert_success_message',
                                    'El producto ha sido creado<br/>Por favor espere mientras se abre la página de edición de productos.
                                    <script type="text/javascript">
                                    window.location = "'.site_url($controller.'/'.$function).'";
                                    </script>
                                    <div style="display:none">
                                    '
            );
            
        //Reglas
            $crud->required_fields('nombre_producto', 'descripcion', 'precio_base', 'iva_porcentaje', 'costo', 'iva', 'precio', 'peso', 'cant_disponibles', 'referencia');
            $crud->set_rules('precio', 'Precio', 'is_natural|required');
            $crud->set_rules('cant_disponibles', 'Cant Disponibles', 'is_natural|required');
            $crud->set_rules('peso', 'Peso', 'greater_than[-1]|required');
            $crud->unique_fields(array('referencia'));
        
        //Valores por defecto
            $crud->field_type('estado', 'hidden', 1);
            $crud->field_type('creado', 'hidden', date('Y-m-d H:i:s'));
            $crud->field_type('editado', 'hidden', date('Y-m-d H:i:s'));
            $crud->field_type('usuario_id', 'hidden', $this->session->userdata('usuario_id'));
                        
        //Formato
            $crud->unset_texteditor('descripcion');
            
        //Procesos
            $crud->callback_after_insert(array($this, 'gc_after_insert'));
        
        $output = $crud->render();
        
        return $output;
    }
    
    function gc_after_insert($post_array,$primary_key)
    {
        $registro['slug'] = $this->Pcrn->slug_unico($post_array['nombre_producto'], 'producto');
        
        $this->db->where('id', $primary_key);
        $this->db->update('producto', $registro);
    }
    
    function crud_meta($producto_id)
    {
        //Grocery crud
        $this->load->library('grocery_CRUD');
        
        $crud = new grocery_CRUD();
        $crud->set_table('meta');
        $crud->set_subject('meta');
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_back_to_list();
        //$crud->unset_delete();
        $crud->unset_read();
        
        //Filtros
            $crud->where('tabla_id', 3100); //Tabla producto
            $crud->where('elemento_id', $producto_id);
            $crud->where('dato_id >= 31000');
            $crud->where('dato_id < 31010');
        
        //Títulos
            $crud->display_as('dato_id', 'Dato');
            $crud->display_as('fecha', 'Editado');
            $crud->display_as('usuario_id', 'Por');
            
        //Campos
            $crud->columns(
                    'dato_id',
                    'valor',
                    'fecha'
                );
            
            $crud->edit_fields(
                    'tabla_id',
                    'elemento_id',
                    'dato_id',
                    'valor',
                    'fecha',
                    'usuario_id'
                );
            
            $crud->add_fields(
                    'tabla_id',
                    'elemento_id',
                    'dato_id',
                    'valor',
                    'fecha',
                    'usuario_id'
                );
            
        //Relaciones
            //$crud->set_relation('usuario_id', 'usuario', 'username');
            
        //Reglas
            $crud->required_fields('dato_id', 'valor');
        
        //Array opciones de datos
            $arr_config = $this->App_model->arr_config_item();
            $arr_config['condicion'] = "tag_id = 2 AND filtro LIKE '%3100%'";
            $opciones_item = $this->Item_model->opciones('tag_id = 2', 'Elija el campo');
            
        //Valores por defecto
            $crud->field_type('tabla_id', 'hidden', 3100);    //Tabla producto
            $crud->field_type('elemento_id', 'hidden', $producto_id);
            $crud->field_type('dato_id', 'dropdown', $opciones_item);
            $crud->field_type('fecha', 'hidden', date('Y-m-d H:i:s'));
            $crud->field_type('usuario_id', 'hidden', $this->session->userdata('usuario_id'));
            
                        
        //Formato
            $crud->callback_column('dato_id', array($this,'gc_nombre_dato'));
            $crud->unset_texteditor('valor');
        
        $output = $crud->render();
        
        return $output;
    }
    
    function gc_nombre_dato($value)
    {
        $nombre_dato = $this->Pcrn->campo('item', "tag_id = 2 AND id_interno = {$value}", 'item');
        return $nombre_dato;
    }
    
    /**
     * Insertar un registro en la tabla.
     * 
     * @param type $registro
     * @return type
     */
    function insertar($registro)
    {
        //Completar registro
            $registro['usuario_id'] = $this->session->userdata('usuario_id');
            $registro['creado'] = date('Y-m-d H:i:s');
            $registro['editado'] = date('Y-m-d H:i:s');
        
        //Insertar
            $this->db->insert('producto', $registro);
            $producto_id = $this->db->insert_id();

        //Preparar resultado
            $resultado['ejecutado'] = 1;
            $resultado['mensaje'] = 'El producto fue creado correctamente';
            $resultado['type'] = 'success';
            $resultado['nuevo_id'] = $producto_id;
        
        return $resultado;
    }
    
    /**
     * Actualizar los datos de un registro en la tabla producto
     * 
     * @param type $producto_id
     * @return type
     */
    function guardar($producto_id)
    {
        //Resultado por defecto, valor inicial.
            $this->load->model('Esp');
            $resultado = $this->Esp->res_inicial('El producto no fue actualizado.');
        
        //Construir registro            
            $registro = $this->registro_actualizar();
            
        //Verificar referencia única
            $cant_referencias = 0;
            if ( isset($registro['referencia']) ) 
            {
                $cant_referencias = $this->Pcrn->num_registros('producto', "id <> {$producto_id} AND referencia = '{$registro['referencia']}'");
                if ( $cant_referencias > 0 ) 
                {
                    $resultado['mensaje'] .= " Ya existe un producto la referencia '{$registro['referencia']}'.";
                }
            }
            
        //Guardar si cumple la condición
            if ( $cant_referencias == 0 ) 
            {
                //Guardar
                    $this->Pcrn->guardar('producto', "id = {$producto_id}", $registro);
                    
                //Modificar resultado
                    $resultado['ejecutado'] = 1;
                    $resultado['mensaje'] = 'Los datos del producto fueron actualizados.';
                    $resultado['type'] = 'success';
            }
        
        return $resultado;
    }
    
    /**
     * Devuelve array con los valores post del formulario para actualizar el
     * registro en la tabla producto
     * 
     * @return type
     */
    function registro_actualizar() 
    {
        //Construir registro            
            $registro = $this->input->post();
            
        //Quitar del input datos que no son campos de la tabla producto
            unset($registro['tags']);
            
        //Valores predeterminados
            $registro['editado'] = date('Y-m-d H:i:s');
            $registro['usuario_id'] = $this->session->userdata('usuario_id');
            
        return $registro;
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
    
    function eliminable($producto_id)
    {
        $eliminable = FALSE;
        if ( $this->session->userdata('rol_id') <= 1  ) { $eliminable = TRUE; }
        
        return $eliminable;
    }
    
    function eliminar($producto_id)
    {
        $eliminado = 0;
        
        if ( $this->eliminable($producto_id) ) 
        {
            //Tabla meta
                $this->db->where('tabla_id', 2);    //Tabla producto
                $this->db->where('elemento_id', $producto_id);
                $this->db->delete('meta');

            //Tabla principal
                $this->db->where('id', $producto_id);
                $this->db->delete('producto');
                
            $eliminado = $this->db->affected_rows();
        }
        
        return $eliminado;
    }
    
    /**
     * Array con atributos para describir el estado de publicación producto
     * @return array
     */
    function arr_estados()
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
            $cant_visitas = $this->Pcrn->campo_id('meta', $meta_id, 'valor');
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
    
    
//GESTIÓN DE IMÁGENES
//---------------------------------------------------------------------------------------------------

    function imagenes($producto_id)
    {
        //Select
            $select = 'meta.id AS meta_id, CONCAT( ("'. URL_UPLOADS . '"), (archivo.carpeta) ) AS url_carpeta, archivo.id AS archivo_id, archivo.*';
        
        //Consulta
        $this->db->select($select);
        $this->db->where('dato_id', 1);
        $this->db->where('elemento_id', $producto_id);
        $this->db->join('meta', 'archivo.id = meta.relacionado_id');
        $imagenes = $this->db->get('archivo');
        
        return $imagenes;
    }
    
    function image_src($row_producto)
    {
        $ancho = '250';
        $row_archivo = $this->Pcrn->registro_id('archivo', $row_producto->imagen_id);
        $image_src = URL_UPLOADS . $row_archivo->carpeta . $ancho . 'px_' . $row_archivo->nombre_archivo;
        
        return $image_src;
    }
    
    function att_img($producto_id, $ancho)
    {
        $row_producto = $this->Pcrn->registro_id('producto', $producto_id);
        $row_archivo = $this->Pcrn->registro_id('archivo', $row_producto->imagen_id);
        $src_alt = URL_IMG . 'app/' . $ancho . 'px_producto.png';   //Imagen alternativa
        
        $att_img['src'] = URL_UPLOADS . $row_archivo->carpeta . $ancho . 'px_' . $row_archivo->nombre_archivo;
        $att_img['alt'] = $row_producto->nombre_producto;
        $att_img['title'] = $row_producto->nombre_producto;
        $att_img['onError'] = "this.src='" . $src_alt . "'"; //Imagen alternativa
        
        return $att_img;
        
    }
    
    /**
     * Relaciona un producto con un registro de archivo
     * Tabla meta
     * 
     * @param type $producto_id
     * @param type $archivo_id
     * @return type
     */
    function asociar_img($producto_id, $archivo_id)
    {
        //Construir registro
            $registro['tabla_id'] = 3100;  //Tabla producto
            $registro['elemento_id'] = $producto_id; //
            $registro['dato_id'] = 1;   //Es una imagen, ver item.id_interno, donde tag_id = 2
            $registro['editado'] = date('Y-m-d H:i:s');
            $registro['relacionado_id'] = $archivo_id;
            
        //Insertar
            $this->db->insert('meta', $registro);
            
        //Si es la primera imagen, se establece como principal
            $this->establecer_img_si($producto_id, $archivo_id);
        
        return $this->db->insert_id();
    }
    
    /**
     * Establecer imagen principal de un producto
     * 
     * @param type $producto_id
     * @param type $archivo_id
     */
    function establecer_img($producto_id, $archivo_id)
    {
        $registro['imagen_id'] = $archivo_id;
        $this->db->where('id', $producto_id);
        $this->db->update('producto', $registro);
    }
    
    /**
     * Establecer imagen principal de un producto si no tiene una asignada
     * 
     * @param type $producto_id
     * @param type $archivo_id
     */
    function establecer_img_si($producto_id, $archivo_id)
    {
        $resultado = 0;
        
        $row_producto = $this->Pcrn->registro_id('producto', $producto_id);
        
        $cant_condiciones = 0;
        
        if ( strlen($row_producto->imagen_id) ) { $cant_condiciones++; }
        
        //Si cumple alguna de las condiciones
        if ( $cant_condiciones == 1 ) 
        {
            $this->establecer_img($producto_id, $archivo_id); 
            $resultado = 1;
        }
        
        return $resultado;
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
        $select_label = $this->Datos_model->select_tags();   
        $condicion = "id IN (SELECT relacionado_id FROM meta WHERE tabla_id = 3100 AND elemento_id = {$producto_id} AND dato_id = 21)";
        
        $this->db->select($select_label);
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
     * Actualiza el listado de categorías asiganadas a un producto en la tabla meta
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
     * 
     * @param type $producto_id
     * @return type
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
            $arr_precio['promocion_id'] = key($arr_precios);
            $arr_precio['precio'] = current($arr_precios);
        
        return $arr_precio;
    }
    
    /**
     * Array con los diferentes precios que se le pueden aplicar a un producto
     * 
     * @param type $producto_id
     * @return type
     */
    function arr_precios($producto_id)
    {
        $row_producto = $this->row_principal($producto_id);
        
        //Precios
            $arr_precios[1] = $row_producto->precio;                        //Precio normal
            $arr_precios[2] = $this->precio_distribuidor($row_producto);    //Precio para distribuidor
            
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
        $pct_fabricante = $this->Pcrn->campo_id('item', $fabricante_id, 'entero_1');
        
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
     * Asignar un contenido de la tabla post a un usuario, lo agrega como metadato
     * en la tabla meta, con el tipo 100012
     * 2020-04-15
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
     * Contenidos digitales asignados a un producto
     */
    function assigned_posts($producto_id)
    {
        $this->db->select('post.id, nombre_post AS title, code, slug, resumen, post.estado, publicado, meta.id AS meta_id');
        $this->db->join('meta', 'post.id = meta.relacionado_id');
        $this->db->order_by('post.id', 'ASC');
        $this->db->where('meta.dato_id', 310012);   //Asignación de contenido
        $this->db->where('meta.elemento_id', $producto_id);

        $posts = $this->db->get('post');
        
        return $posts;
    }
    
}