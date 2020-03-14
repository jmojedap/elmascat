<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Productos extends CI_Controller{
    
    function __construct() {
        parent::__construct();

        $this->load->model('Producto_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }

//CRUD
//---------------------------------------------------------------------------------------------------
    
    function index()
    {
        $this->catalogo();
    }
    
    /**
     * Exploración y búsqueda de productos, administración
     */
    function explorar()
    {
        $this->load->helper('text_helper');
     
        //Datos básicos de la exploración
            $data = $this->Producto_model->data_explorar();
        
        //Opciones de filtros de búsqueda
            $data['arr_filtros'] = array('est', 'cat', 'fab', 'dcto', 'tag');
            $data['opciones_estado'] = $this->Item_model->opciones('categoria_id = 8', 'Todos');
            $data['opciones_tag'] = $this->Item_model->opciones_id('categoria_id = 21', 'Filtrar por etiqueta');
            $data['opciones_fabricante'] = $this->Item_model->opciones_id('categoria_id = 5', 'Fabricante/Editorial');
            $data['opciones_promocion'] = $this->App_model->opciones_post('tipo_id = 31001 AND estado = 1', 'n', 'Todas');
            $data['opciones_categoria'] = $this->Item_model->opciones('categoria_id = 25', 'Todas las categorías');
            
        //Arrays con valores para contenido en lista
            $data['arr_estados'] = $this->Item_model->arr_interno('categoria_id = 8');
            $data['arr_tags'] = $this->Item_model->arr_interno('categoria_id = 25');
        
        //Cargar vista
            $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * AJAX
     * Eliminar un conjunto de productos seleccionados
     */
    function eliminar_seleccionados()
    {
        $str_seleccionados = $this->input->post('seleccionados');
        
        $seleccionados = explode('-', $str_seleccionados);
        $cant_eliminados = 0;
        
        foreach ( $seleccionados as $elemento_id ) 
        {
            $cant_eliminados += $this->Producto_model->eliminar($elemento_id);
        }
        
        $this->output
        ->set_content_type('application/json')
        ->set_output($cant_eliminados);
    }
    
    /**
     * Exporta el resultado de la búsqueda a un archivo de Excel
     */
    function exportar()
    {
        
        //Cargando
            $this->load->model('Busqueda_model');
            $this->load->model('Pcrn_excel');
        
        //Datos de consulta, construyendo array de búsqueda
            $busqueda = $this->Busqueda_model->busqueda_array();
            $resultados_total = $this->Producto_model->buscar($busqueda); //Para calcular el total de resultados
        
        //Preparar datos
            $datos['nombre_hoja'] = 'Productos';
            $datos['query'] = $resultados_total;
            
        //Preparar archivo
            $objWriter = $this->Pcrn_excel->archivo_query($datos);
        
        $data['objWriter'] = $objWriter;
        $data['nombre_archivo'] = date('Ymd_His'). '_productos.xls'; //save our workbook as this file name
        
        $this->load->view('app/descargar_phpexcel_v', $data);
            
    }
    
    /**
     * Formulario para la creación de un nuevo producto
     */
    function nuevo()
    {
        //Variables generales
            $data['titulo_pagina'] = 'Productos';
            $data['subtitulo_pagina'] = 'Nuevo';
            $data['vista_a'] = 'productos/nuevo_v';
            $data['vista_menu'] = 'productos/explorar/menu_v';

        $this->load->view(PTL_ADMIN, $data);
    }
    
    function editar_reciente()
    {
        $producto_id = $this->Producto_model->producto_id();
        redirect("productos/editar/{$producto_id}");
    }
    
    /**
     * Formulario para la edición de productos. Según la sección se editan datos 
     * del registro en la tabla producto (descripcion y valores), 
     * metadatos (tabla meta), e imágenes (tabla meta).
     * 
     * @param type $producto_id
     */
    function editar($producto_id, $seccion = 'descripcion')
    {
        //Cargue
            $this->load->model('Datos_model');
            $this->load->model('Meta_model');
            $this->load->library('Bootstrap');
        
            $data = $this->Producto_model->basico($producto_id);

        //Array data espefícicas
            $data['producto_id'] = $producto_id;
            $data['tags_activas'] = $this->Producto_model->tags_activas($producto_id);
            $data['imagenes'] = $this->Producto_model->imagenes($producto_id);
            $data['metacampos'] = $this->Meta_model->metacampos(3100, 'editable');  //Tabla ID, ver sis_tabla;
            $data['subtitulo_pagina'] = 'Editar';
            $data['vista_b'] = 'productos/editar/editar_v';
            
            $data['arr_precios'] = $this->Producto_model->arr_precios($producto_id);
            $data['arr_tipos_precio'] = $this->Producto_model->arr_tipos_precio($producto_id);
            $data['arr_precio'] = $this->Producto_model->arr_precio($producto_id);
            
        //Destino para formulario
            $data['destino_form'] = "productos/guardar/{$producto_id}";
            if ( $seccion == 'especificaciones' ) { $data['destino_form'] = "productos/guardar_metadatos/{$producto_id}"; }

        $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * AJAX JSON
     * Recibe datos post de productos/nuevo, crea un producto nuevo, y le agrega
     * las categorías definidas.
     */
    function insertar()
    {
        $registro = $this->input->post();
        unset($registro['tags']);
        
        $resultado = $this->Producto_model->insertar($registro);
        
        //Si se insertó, se le agregan las categorías
        if ( $resultado['nuevo_id'] > 0 ) 
        {
            $tags = $this->input->post('tags');
            $this->Producto_model->act_tags($resultado['nuevo_id'], $tags);
        }
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($resultado));
    }
    
    /**
     * AJAX JSON
     * Actualizar datos de un producto y redirigir a la vista de formulario de
     * edición mostrando resultado de la actualización.
     */
    function guardar($producto_id)
    {
        
        //Guardar datos en tabla producto
            $resultado = $this->Producto_model->guardar($producto_id);
            
        //Si se guardó, ejecutar los demás procesos
            if ( $resultado['ejecutado'] ) 
            {
                //Actualizar los datos de las variaciones del producto, que hereden sus cambios
                    $this->Producto_model->act_variaciones($producto_id);    

                //Actualizar tags
                    if ( ! is_null($this->input->post('tags')) )
                    {
                        $tags = $this->input->post('tags');
                        $this->Producto_model->act_tags($producto_id, $tags);
                    }

                //Crear registro de edición en la tabla meta
                    $this->Producto_model->meta_editado($producto_id);
            }

        //Resultado
           $this->output
           ->set_content_type('application/json')
           ->set_output(json_encode($resultado));
    }
    
    /**
     * JSON AJAX
     * Actualizar datos de un producto y redirigir a la vista de formulario de
     * edición mostrando resultado de la actualización.
     */
    function guardar_metadatos($producto_id)
    {
        //Guardar
            $this->Producto_model->guardar_metadatos($producto_id);
        
        //Resultado
            $resultado['ejecutado'] = 1;
            $resultado['mensaje'] = 'Los datos de especificaciones del producto se guardaron correctamente';
            $resultado['type'] = 'success';
        
        //Crear registro de edición en la tabla meta
            $this->Producto_model->meta_editado($producto_id);
            $this->Producto_model->act_meta($producto_id);
        
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($resultado));
        
    }
    
    function ver($producto_id)
    {
        $this->load->model('Datos_model');
        
        $data = $this->Producto_model->basico($producto_id);
        $data['metadatos'] = $this->Producto_model->metadatos_valor($producto_id);
        $data['vista_b'] = 'productos/ver_v';
        
        //Datos
            $data['palabras_clave'] = $this->Producto_model->palabras_clave($producto_id);
            $data['tags'] = $this->Producto_model->tags($producto_id);
            $data['ediciones'] =  $this->Producto_model->meta($producto_id, 'dato_id = 30');
        
        $this->load->view(PTL_ADMIN, $data);
    }
    
    function guardar_comentario($producto_id)
    {
        $this->load->model('Meta_model');
        $this->Meta_model->agregar_comentario(3100);   // 3100 => producto
        redirect("productos/detalle/{$producto_id}");
    }
    
//FUNCIONES COMERCIALES
//---------------------------------------------------------------------------------------------------
    
    /**
     * Controla y redirecciona las búsquedas de exploración del catálogo de productos
     * así se evita el problema de reenvío del formulario al presionar el botón 
     * "atrás" del browser
     * 
     * @param type $elemento
     */
    function catalogo_redirect()
    {
        $this->load->model('Busqueda_model');
        $busqueda_str = $this->Busqueda_model->busqueda_str();
        redirect("productos/catalogo/?{$busqueda_str}");
    }
    
    /**
     * Catálogo de productos, responde a búsquedas y filtros
     */
    function catalogo()
    {
        //Cargue inicial
            $this->load->helper('text');
            $this->load->model('Archivo_model');
            $this->load->model('Busqueda_model');
        
        //Cargando datos básicos (basico)
            
        
        //Datos de consulta, construyendo array de búsqueda
            $busqueda = $this->Busqueda_model->busqueda_array();
            $busqueda_str = $this->Busqueda_model->busqueda_str();
            $resultados_total = $this->Producto_model->catalogo($busqueda); //Para calcular el total de resultados
            
        //Guardar búsqueda
            $this->load->model('Evento_model');
            $this->Evento_model->guardar_ev_busqueda($busqueda);    
        
        //Paginación
            $this->load->library('pagination');
            $config = $this->App_model->config_paginacion(3);
            $config['per_page'] = 12;
            $config['base_url'] = base_url("productos/catalogo/?{$busqueda_str}");
            $config['total_rows'] = $resultados_total->num_rows();
            $this->pagination->initialize($config);
            
        //Generar resultados para mostrar
            $offset = $this->input->get('per_page');
            $resultados = $this->Producto_model->catalogo($busqueda, $config['per_page'], $offset);
        
        //Variables
            $data['titulo_pagina'] = NOMBRE_APP;
            $data['cant_resultados'] = $config['total_rows'];
            $data['busqueda'] = $busqueda;
            $data['busqueda_str'] = $busqueda_str;
            $data['resultados'] = $resultados;
            $data['tags'] = $this->App_model->tags();
            $data['fabricantes'] = $this->Producto_model->fabricantes();
        
        //Solicitar vista
            $data['vista_a'] = 'productos/catalogo/catalogo_v';
            $this->load->view(PTL_FRONT, $data);
    }
    
    /**
     * REDIRECT
     * Registra la visita de un usuario a un producto, y lo redirige a la vista
     * principal del producto: productos/detalle
     * 
     * @param type $producto_id
     * @param type $slug
     */
    function visitar($producto_id, $slug = '')
    {   
        
        //Registra visita al producto
        $this->Producto_model->registrar_visita($producto_id);
        
        $variaciones = $this->Producto_model->variaciones($producto_id);
        if ( $variaciones->num_rows() )
        {
            //Para redirigir a la primera variación del producto
            $producto_id = $variaciones->row()->id;
        }
        
        redirect("productos/detalle/{$producto_id}/{$slug}");
    }
    
    function detalle($producto_id)
    {
        $this->load->model('Archivo_model');
        
        $data = $this->Producto_model->basico($producto_id);
        
        $data['metadatos'] = $this->Producto_model->metadatos_valor($producto_id, 'visible');
        $data['variaciones'] = $this->Producto_model->variaciones($producto_id);
        $data['comentarios'] = $this->Producto_model->comentarios($producto_id);
        $data['vista_a'] = 'productos/detalle/producto_v';
        $data['vista_b'] = 'productos/detalle/info_v';
        $data['arr_precio'] = $this->Producto_model->arr_precio($producto_id);
        $data['arr_tipos_precio'] = $this->Producto_model->arr_tipos_precio();
        
        //Datos
            $data['imagenes'] = $this->Producto_model->imagenes($producto_id);
            $data['palabras_clave'] = $this->Producto_model->palabras_clave($producto_id);
        
        $this->load->view(PTL_FRONT, $data);
    }
    
    
//PROCESOS EN EXPLORAR
//---------------------------------------------------------------------------------------------------
    
    function z_f_controlador($producto_id, $estado)
    {
        $this->Producto_model->act_estado($producto_id, $estado);
        
        $this->load->model('Busqueda_model');
        $busqueda_str = $this->Busqueda_model->busqueda_str();
        
        $destino = "productos/explorar/?{$busqueda_str}";
        
        redirect($destino);
    }
    
    /**
     * AJAX
     * Agrega a una lista un grupo de productos seleccionados
     */
    function agregar_lista_seleccionados()
    {
        $str_seleccionados = $this->input->post('seleccionados');
        $lista_id = $this->input->post('lista_id');
        $row_lista = $this->Pcrn->registro_id('item', $lista_id);
        
        $seleccionados = explode('-', $str_seleccionados);
        
        foreach ( $seleccionados as $elemento_id ) {
            $this->Producto_model->agregar_lista($elemento_id, $lista_id);
        }
        
        $respuesta = 'Se agregaron ' . count($seleccionados) . ' productos a la lista ' . $row_lista->item;
        
        echo $respuesta;
    }
    
//DESCARGAS
//---------------------------------------------------------------------------------------------------
    function descargas()
    {
        //Solicitar vista
            $data['titulo_pagina'] = 'Productos';
            $data['subtitulo_pagina'] = 'Descargas';
            $data['vista_a'] = 'productos/explorar/menu_v';
            $data['vista_b'] = 'productos/descargas_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
//GESTIÓN DE IMÁGENES
//---------------------------------------------------------------------------------------------------
    
    function z_editar_img($producto_id)
    {
        $data = $this->Producto_model->basico($producto_id);
        
        $data['imagenes'] = $this->Producto_model->imagenes($producto_id);
        
        $data['vista_b'] = 'productos/editar_img_v';
        $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * POST REDIRECT
     * Recibe datos de productos/editar/$producto_id/imagenes
     * 
     * Carga y asigna una imagen al producto
     * @param type $producto_id
     */
    function agregar_img($producto_id)
    {
        $this->output->enable_profiler(TRUE);
        $this->load->model('Archivo_model');
        $resultado = $this->Archivo_model->cargar();
        
        //Cargue exitoso, se crea registro asociado
            if ( $resultado['ejecutado'] ) 
            {
                $this->Producto_model->asociar_img($producto_id, $resultado['row_archivo']->id);
            }
            
        $this->session->set_flashdata('mensaje', $resultado['mensaje']);
        redirect("productos/editar/{$producto_id}/imagenes");
    }
    
    /**
     * AJAX eliminar imagen
     * @param type $meta_id
     */
    function eliminar_img($meta_id)
    {
        
        $row_meta = $this->App_model->eliminar_meta($meta_id);
        
        //Eliminar archivo y registro de la tabla archivo
            $this->load->model('Archivo_model');
            $this->Archivo_model->eliminar($row_meta->relacionado_id);
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(1);
    }
    
    function establecer_img($producto_id, $archivo_id)
    {
        $this->Producto_model->establecer_img($producto_id, $archivo_id);
        redirect("productos/editar/{$producto_id}/imagenes");
    }
    
//GESTIÓN DE ETIQUETAS DE PRODUCTO
//---------------------------------------------------------------------------------------------------------
    
    function tags($producto_id)
    {
        //Cargue
            $this->load->model('Datos_model');
        
        //Datos básicos
            $data = $this->Producto_model->basico($producto_id);
            
        //Categorías
            $tags = $this->Datos_model->tags();
            $tags_producto = $this->Producto_model->tags($producto_id);
            
        //Variables
            $data['tags'] = $tags;
            $data['tags_producto'] = $tags_producto;
        
        //Solicitar vista
            $data['vista_b'] = 'productos/tags_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
//GESTIÓN DE VARIACIONES
//---------------------------------------------------------------------------------------------------------
    
    function variaciones($producto_id, $variacion_id = NULL)
    {
        
        //Datos básicos
            $data = $this->Producto_model->basico($producto_id);
            
        //Variaciones
            $row_variacion = $this->Pcrn->registro_id('producto', $variacion_id);
            $variaciones = $this->Producto_model->variaciones($producto_id);
            
        //Destino form
            $destino_form = "productos/crear_variacion/{$producto_id}";
            if ( ! is_null($row_variacion) ) {
                $destino_form = "productos/act_variacion/{$producto_id}/{$variacion_id}";
            }
            
        //Variables
            $data['variacion_id'] = $variacion_id;
            $data['row_variacion'] = $row_variacion;
            $data['productos'] = $variaciones;
            $data['destino_form'] = $destino_form;
        
        //Solicitar vista
            $data['vista_b'] = 'productos/variaciones_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    function crear_variacion($producto_id)
    {   
        
        //Comprobar referencia única
        $referencia = $this->input->post('referencia');
        $existe = $this->Pcrn->existe('producto', "referencia = '{$referencia}'");
        
        if ( $existe ) {
            //La referncia ya existe
            $resultado['clase'] = 'alert-danger';
            $resultado['mensaje'] = 'La referencia "' . $referencia . '" ya existe en la base de datos';
            
        } else {
            //La referencia no existe, se crea
            $this->Producto_model->crear_variacion($producto_id);
            $resultado['clase'] = 'alert-success';
            $resultado['mensaje'] = 'La variación del producto se creó exitosamente';
        }
        
        $this->session->set_flashdata('resultado', $resultado);
        
        
        redirect("productos/variaciones/{$producto_id}");
    }
    
    function act_variacion($producto_id, $variacion_id)
    {
        $registro['referencia'] = $this->input->post('referencia');
        $registro['cant_disponibles'] = $this->input->post('cant_disponibles');
        $registro['talla'] = $this->input->post('talla');
        $registro['puntaje'] = $this->input->post('puntaje');
        
        $this->db->where('id', $variacion_id);
        $this->db->update('producto', $registro);
        
        redirect("productos/variaciones/{$producto_id}");
    }
    
    function eliminar_variacion($producto_id, $variacion_id)
    {
        $condicion = "id = {$variacion_id} AND padre_id = {$producto_id} AND tipo_id = 2";
        $elemento_id = $this->Pcrn->existe('producto', $condicion);
        
        if ( $elemento_id > 0 ) 
        {
            $this->Producto_model->eliminar($elemento_id);
            
            $resultado['clase'] = 'alert-info';
            $resultado['mensaje'] = '<i class="fa fa-info-circle"></i> La variación de producto fue eliminada';
            
            $this->session->set_flashdata('resultado', $resultado);
        }
        
        //$this->output->enable_profiler(TRUE);
        redirect("productos/variaciones/{$producto_id}");
    }
    
    
//PROCESOS
//---------------------------------------------------------------------------------------------------
    
    function procesos()
    {
        //Solicitar vista
            $data['titulo_pagina'] = 'Productos';
            $data['subtitulo_pagina'] = 'Procesos masivos';
            $data['vista_menu'] = 'productos/explorar/menu_v';
            $data['vista_a'] = 'productos/procesos_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    function ejecutar_proceso($cod_proceso)
    {
        
        //Ampliar tiempo de ejecución
            set_time_limit(360);   //  6 minutos
        
        $data['status'] = 0;
        $data['message'] = 'Proceso no ejecutado';
        
        if ( $cod_proceso == 1 ) {
            $data = $this->Producto_model->actualizar_slug();
        } elseif ( $cod_proceso == 2 ) {
            $data = $this->Producto_model->asociar_img_inicial_masivo();
        } elseif ( $cod_proceso == 3 ) {
            $data = $this->Producto_model->establecer_img_si_masivo();
        } elseif ( $cod_proceso == 4 ) {
            $data = $this->Producto_model->depurar_img_principal();
        } elseif ( $cod_proceso == 5 ) {
            $data = $this->Producto_model->act_puntaje_auto_masivo();
        } elseif ( $cod_proceso == 6 ) {
            $data = $this->Producto_model->redondear_costos();
        } elseif ( $cod_proceso == 7 ) {
            $data = $this->Producto_model->act_meta_masivo();
        }
        
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }


    
//CARGUE MASIVO DE PRODUCTOS
//---------------------------------------------------------------------------------------------------
    
    function limpiar()
    {
        $consultas[] = 'DELETE FROM producto WHERE 1;';
        $consultas[] = 'DELETE FROM meta WHERE 1;';
        
        foreach ($consultas as $sql) {
            $this->db->query($sql);
        }
        
        echo 'Listo';
    }
    
    /**
     * Formulario para cargue de archivo excel, cargue de productos
     */
    function importar()
    {
        //Solicitar vista
            $data['titulo_pagina'] = 'Productos';
            $data['subtitulo_pagina'] = 'Cargar';
            $data['vista_menu'] = 'productos/explorar/menu_v';
            $data['vista_a'] = 'productos/cargar_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * e => ejecutar
     * Cargar registros desde archivo de excel
     */
    function importar_e()
    {
        
        //Variables
            $filtro = '';
        
        $archivo = $_FILES['file']['tmp_name'];             //Se crea un archivo temporal, no se sube al servidor, se toma el nombre temporal
        $nombre_hoja = $this->input->post('nombre_hoja');   //Nombre de hoja digitada en el formulario
        
        $this->load->model('Pcrn_excel');
        $cargue_excel = $this->Pcrn_excel->array_hoja($archivo, $nombre_hoja, 'T');    //Hasta la columna de la hoja de cálculo
        $mensaje = $cargue_excel['mensaje'];
        $array_excel = $cargue_excel['array_hoja'];
        
        if ( $cargue_excel['cargado'] ) {
            
            $res_proceso = $this->Producto_model->cargar($array_excel);
            
            $filtro = "?e={$res_proceso['e']}";
            $cant_no_cargados = count($array_excel) - $res_proceso['cant_cargados'];
            
            //Preparar mensaje
            $mensaje = '<i class="fa fa-check"></i>' .  " Se cargaron {$res_proceso['cant_cargados']} registros. <br/>";
            if ( $cant_no_cargados > 0 ) { $mensaje .=  '<i class="fa fa-times"></i>' . " No se cargaron {$cant_no_cargados} registros."; }
            
        }
        
        //Cargue de variables para flashdata
            $res_proceso['mensaje'] = $mensaje;
            $res_proceso['cargado'] = $cargue_excel['cargado'];
        
        //Cargar flashdata
          $this->session->set_flashdata('res_proceso', $res_proceso);
        
        $destino = "productos/explorar/{$filtro}";
        redirect($destino);
    }
    
    /**
     * Mostrar formulario de importación de existencais de productos mediante 
     * archivo MS Excel. El resultado del formulario se envía a 
     * 'productos/importar_existencias_e'
     * 
     * @param type $programa_id
     */
    function actualizar_datos()
    {
        
        //Iniciales
            $nombre_archivo = '02_actualizar_productos.xlsx';
            $parrafos_ayuda = array(
                'En la columna <b>A</b> escriba la referencia del producto.',
                'En la columna <b>B</b> escriba la cantidad de existencias del producto.',
                'Sin en la columna <b>B</b> no hay un dato numérico, las existencias se actualizarán con valor cero (0).',
                'Antes de ejecutar este proceso es recomendable exportar una <span class="resaltar">COPIA DE SEGURIDAD</span> de los datos de producto.',
                'Si la referencia de un producto está repetida, sólo se actualizará el producto que fue creado en primer lugar.',
            );
        
        //Instructivo
            $data['titulo_ayuda'] = '¿Cómo actualizar datos de productos?';
            $data['nota_ayuda'] = 'Se importarán y actualizarán datos de productos existentes a la base de datos.';
            $data['parrafos_ayuda'] = $parrafos_ayuda;
        
        //Variables específicas
            $data['destino_form'] = 'productos/actualizar_datos_e';
            $data['nombre_archivo'] = $nombre_archivo;
            $data['nombre_hoja'] = 'productos';
            $data['url_archivo'] = base_url("assets/formatos_cargue/{$nombre_archivo}");
            
        //Variables generales
            $data['titulo_pagina'] = 'Productos';
            $data['subtitulo_pagina'] = 'Actualizar datos';
            $data['vista_a'] = 'comunes/importar_v';
            $data['vista_menu'] = 'productos/explorar/menu_v';
        
        $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * Importar programas, (e) ejecutar.
     */
    function actualizar_datos_e()
    {
        //Proceso
            $this->load->model('Pcrn_excel');
            $no_importados = array();
            $letra_columna = 'D';   //Última columna con datos
            
            $resultado = $this->Pcrn_excel->array_hoja_default($letra_columna);

            if ( $resultado['valido'] )
            {
                $no_importados = $this->Producto_model->actualizar_datos($resultado['array_hoja']);
                $this->guardar_ev_actualizar_datos(1);
            }
        
        //Cargue de variables
            $data['valido'] = $resultado['valido'];
            $data['mensaje'] = $resultado['mensaje'];
            $data['array_hoja'] = $resultado['array_hoja'];
            $data['nombre_hoja'] = $this->input->post('nombre_hoja');
            $data['no_importados'] = $no_importados;
            $data['destino_volver'] = "productos/explorar/";
        
        //Cargar vista
            $data['titulo_pagina'] = 'Productos';
            $data['subtitulo_pagina'] = 'Resultado importación';
            $data['vista_a'] = 'comunes/resultado_importacion_v';
            $data['vista_menu'] = 'productos/explorar/menu_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    function guardar_ev_actualizar_datos($estado)
    {
        $this->load->model('Evento_model');
        
        //Registro base
            $registro = $this->Evento_model->reg_base();

        //Construir Registro
            $registro['tipo_id'] = 310021;  //Actualización de datos de productos
            $registro['estado'] = $estado;

        //Guardar
            $this->db->insert('evento', $registro);
    }
    
    function editar_meta($producto_id)
    {
        $this->output->enable_profiler(TRUE);
        
        //Cargue
            $this->load->model('Datos_model');
        
        $data = $this->Producto_model->basico($producto_id);
        
        $output = $this->Producto_model->crud_meta($producto_id);
        
        //Head includes específicos para la página
            $head_includes[] = 'grocery_crud';
            $data['head_includes'] = $head_includes;

        //Array data espefícicas
            $data['subtitulo_pagina'] = 'Editar detalles';
            $data['vista_b'] = 'comunes/gc_v';

        
        $output = array_merge($data,(array)$output);
        $this->load->view(PTL_ADMIN, $output);
    }
    
// PROMOCIONES
//-----------------------------------------------------------------------------
    
    function promociones()
    {
        $this->load->model('Post_model');
        $gc_output = $this->Post_model->crud_promociones();
        
        //Array data espefícicas
        $data['titulo_pagina'] = 'Productos';
        $data['subtitulo_pagina'] = 'Promociones';
        $data['vista_a'] = 'comunes/gc_v';
        $data['vista_menu'] = 'productos/explorar/menu_v';
        
        $output = array_merge($data,(array)$gc_output);
        $this->load->view(PTL_ADMIN, $output);
    }
    
}
