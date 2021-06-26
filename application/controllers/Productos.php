<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Productos extends CI_Controller{

// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'ecommerce/products/';
    public $url_controller = URL_ADMIN . 'productos/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct() {
        parent::__construct();

        $this->load->model('Producto_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }

    function index()
    {
        $this->catalogo();
        //$this->inicio();
    }

//EXPLORE
//---------------------------------------------------------------------------------------------------

    /**
     * Exploración y búsqueda de productos
     * 2021-02-24
     */
    function explore($num_page = 1)
    {        
        //Identificar filtros de búsqueda
            $this->load->model('Search_model');
            $filters = $this->Search_model->filters();

        //Datos básicos de la exploración
            $data = $this->Producto_model->explore_data($filters, $num_page);
        
        //Opciones de filtros de búsqueda
            $data['options_estado'] = $this->Item_model->options('categoria_id = 8', 'Todos');
            $data['options_tag'] = $this->Item_model->opciones_id('categoria_id = 21', 'Todas');
            $data['options_fabricante'] = $this->Item_model->opciones_id('categoria_id = 5', 'Fabricante/Editorial');
            $data['options_promocion'] = $this->App_model->opciones_post('tipo_id = 31001 AND estado = 1', 'n', 'Todas');
            $data['options_categoria'] = $this->Item_model->options('categoria_id = 25', 'Todas las categorías');
            
        //Arrays con valores para contenido en lista
            $data['arr_estados'] = $this->Item_model->arr_cod('categoria_id = 8');
            $data['arr_tags'] = $this->Item_model->arr_cod('categoria_id = 25');
            //$data['arr_id_number_types'] = $this->Item_model->arr_item('category_id = 53', 'cod_abr');

        //Promociones
            $promociones = $this->db->get_where('post', 'tipo_id = 31001');
            $data['arr_promociones'] = $this->pml->query_to_array($promociones, 'nombre_post', 'id');
            
        //Cargar vista
            $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * JSON
     * Listado de users, según filtros de búsqueda
     */
    function get($num_page = 1, $per_page = 10)
    {
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        //Consultas previas, tags
        if ( $filters['tag'] != '' ) {
            $this->load->model('Datos_model');
            $tag_id = (int) $filters['tag'];
            $filters['fe2'] = $this->Datos_model->descendencia($tag_id, 'string');
        }

        $data = $this->Producto_model->get($filters, $num_page, $per_page);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * AJAX JSON
     * Eliminar un conjunto de users seleccionados
     * 2021-06-10
     */
    function delete_selected()
    {
        $selected = explode(',', $this->input->post('selected'));
        $data['qty_deleted'] = 0;
        
        foreach ( $selected as $row_id ) $data['qty_deleted'] += $this->Producto_model->delete($row_id);
        
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

//CRUD
//---------------------------------------------------------------------------------------------------
    
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
        
        $this->App_model->view('app/descargar_phpexcel_v', $data);
            
    }

    /**
     * Vista información general del producto
     * 2021-06-23
     */
    function info($producto_id)
    {
        $data = $this->Producto_model->basico($producto_id);
        $data['metadatos'] = $this->Producto_model->metadatos_valor($producto_id);
        $data['ediciones'] =  $this->Producto_model->meta($producto_id, 'dato_id = 30');

        $data['view_a'] = $this->views_folder. 'info_v';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    function details($producto_id)
    {
        $data = $this->Producto_model->basico($producto_id);
        $data['view_a'] = 'common/row_details_v';
        $data['nav_2'] = $this->views_folder . 'menu_v';
        $this->App_model->view(TPL_ADMIN, $data);
    }
    
    /**
     * Formulario para la creación de un nuevo producto
     * 2021-06-02
     */
    function add()
    {
        //Opciones formulario
            $data['options_fabricante'] = $this->Item_model->opciones_id('categoria_id = 5', 'Todos las marcas');
            $data['options_categoria'] = $this->Item_model->options('categoria_id = 25');

        //Variables generales
            $data['head_title'] = 'Productos';
            $data['view_a'] = $this->views_folder .  'add_v';
            $data['nav_2'] = $this->views_folder . 'explore/menu_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    function validate($producto_id = NULL)
    {
        //Reglas
        $validation['referencia_unique'] = $this->Db_model->is_unique('producto', 'referencia', $this->input->post('referencia'), $producto_id);

        //Resultado
        $data = array('status' => 1, 'message' => 'Los datos de producto son válidos');
        foreach ( $validation as $value )
        {
            if ( $value == FALSE )  //Si alguno de los valores no es válido
            {
                $data['status'] = 0;
                $data['message'] = 'Los datos del producto NO son válidos';
            }
        }

        $data['validation'] = $validation;

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
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
     */
    function edit($producto_id, $section = 'description')
    {
        //Cargue
            $this->load->model('Datos_model');
            $this->load->model('Meta_model');
            $this->load->library('Bootstrap');
        
            $data = $this->Producto_model->basico($producto_id);

        //Array data espefícicas
            $data['producto_id'] = $producto_id;
            $data['tags_activas'] = $this->Producto_model->tags_activas($producto_id);
            $data['metacampos'] = $this->Meta_model->metacampos(3100, 'editable');  //Tabla ID, ver sis_tabla;
            $data['view_a'] = "{$this->views_folder}edit/{$section}_v";
            $data['nav_3'] = $this->views_folder . 'edit/menu_v';
            $data['section'] = $section;

        //Opciones
            $data['options_estado'] = $this->Item_model->opciones('categoria_id = 8');
            $data['options_categoria'] = $this->Item_model->options('categoria_id = 25');
            $data['options_fabricante'] = $this->Item_model->opciones_id('categoria_id = 5', 'Todos las marcas');
            
        //Arrays
            $data['arr_precios'] = $this->Producto_model->arr_precios($producto_id);
            $data['arr_tipos_precio'] = $this->Producto_model->arr_tipos_precio($producto_id);
            $data['arr_precio'] = $this->Producto_model->arr_precio($producto_id);
            
        //Destino para formulario
            $data['form_destination'] = "productos/guardar/{$producto_id}";
            if ( $section == 'especificaciones' ) { $data['form_destination'] = "productos/guardar_metadatos/{$producto_id}"; }

        $this->App_model->view(TPL_ADMIN, $data);
    }
    
    /**
     * AJAX JSON
     * Recibe datos post de productos/nuevo, crea un producto nuevo
     * 2021-06-03
     */
    function insert()
    {
        $arr_row = $this->input->post();
        $data = $this->Producto_model->insert($arr_row);
        
        $this->output->set_content_type('application/json')->set_output(json_encode($resultado));
    }
    
    /**
     * AJAX JSON
     * Actualizar datos de un producto y redirigir a la vista de formulario de
     * edición mostrando resultado de la actualización.
     */
    function update($producto_id)
    {
        
        //Guardar datos en tabla producto
            $data = $this->Producto_model->update($producto_id);
            
        //Si se guardó, ejecutar los demás procesos
            if ( $data['saved_id'] > 0 ) 
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
           $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * JSON AJAX
     * Actualizar datos de un producto y redirigir a la vista de formulario de
     * edición mostrando resultado de la actualización.
     * 2021-06-18
     */
    function guardar_metadatos($producto_id)
    {
        //Guardar
            $this->Producto_model->guardar_metadatos($producto_id);
        
        //Resultado
            $data['saved_id'] = $producto_id;
            $data['message'] = 'Los datos de especificaciones del producto se guardaron correctamente';
        
        //Crear registro de edición en la tabla meta
            $this->Producto_model->meta_editado($producto_id);
            $this->Producto_model->act_meta($producto_id);
        
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
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
        
        $this->App_model->view(TPL_ADMIN, $data);
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
     * Inicio a catálogo, archivo caché
     * 2020-05-08
     */
    function inicio()
    {
        $data['head_title'] = 'DistriCatólicas';
        $data['view_a'] = 'cache/productos_catalogo';
        $this->App_model->view(TPL_FRONT, $data);
    }
    
    /**
     * Catálogo de productos, responde a búsquedas y filtros
     */
    function catalogo()
    {
        
        //Cargue inicial
            $this->load->helper('text');
            $this->load->model('Archivo_model');
            $this->load->model('Search_model');
        
        //Datos de consulta, construyendo array de búsqueda
            $filters = $this->Search_model->filters();
            $str_filters = $this->Search_model->str_filters();
            $resultados_total = $this->Producto_model->catalogo($filters); //Para calcular el total de resultados
            
        //Guardar búsqueda
            $this->load->model('Evento_model');
            $this->Evento_model->guardar_ev_busqueda($filters);    
        
        //Paginación
            $this->load->library('pagination');
            $config = $this->App_model->config_paginacion(3);
            $config['per_page'] = 6;
            $config['base_url'] = base_url("productos/catalogo/?{$str_filters}");
            $config['total_rows'] = $resultados_total->num_rows();
            $this->pagination->initialize($config);
            
        //Generar resultados para mostrar
            $offset = $this->input->get('per_page');
            $resultados = $this->Producto_model->catalogo($filters, $config['per_page'], $offset);
        
        //Variables
            $data['head_title'] = NOMBRE_APP;
            $data['cant_resultados'] = $config['total_rows'];
            $data['filters'] = $filters;
            $data['str_filters'] = $str_filters;
            $data['resultados'] = $resultados;
            $data['tags'] = $this->App_model->tags();
            $data['fabricantes'] = $this->Producto_model->fabricantes();
        
        //Solicitar vista
            $data['view_a'] = 'productos/catalogo/catalogo_v';
            //$data['view_a'] = 'cache/productos_catalogo';
            $this->App_model->view(TPL_FRONT, $data);
    }
    
    /**
     * REDIRECT
     * Registra la visita de un usuario a un producto, y lo redirige a la vista
     * principal del producto: productos/detalle
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

        $destination = "productos/detalle/{$producto_id}/{$slug}";
        if ( in_array($producto_id, array(17206, 17205, 17204)) )
        {
            $destination = 'catalogo/productos_digitales';
        }
        
        redirect($destination);
    }
    
    /**
     * Vista detalles de producto, pública para compradores
     * 2021-01-27
     */
    function detalle($producto_id)
    {
        $this->load->model('Archivo_model');
        
        $data = $this->Producto_model->basico($producto_id);
        
        $data['metadatos'] = $this->Producto_model->metadatos_valor($producto_id, 'visible');
        $data['variaciones'] = $this->Producto_model->variaciones($producto_id);
        $data['comentarios'] = $this->Producto_model->comentarios($producto_id);
        $data['view_a'] = 'productos/detalle/producto_v';
        $data['arr_precio'] = $this->Producto_model->arr_precio($producto_id);
        $data['arr_tipos_precio'] = $this->Producto_model->arr_tipos_precio();
        $data['tags'] = $this->Producto_model->tags($producto_id);
        
        //Datos
            $data['palabras_clave'] = $this->Producto_model->palabras_clave($producto_id);
        
        $this->App_model->view(TPL_FRONT, $data);
        //Salida JSON
        //$this->output->set_content_type('application/json')->set_output(json_encode($data));
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
            $data['head_title'] = 'Productos';
            $data['view_a'] = $this->views_folder .  'explorar/menu_v';
            $data['vista_b'] = 'productos/descargas_v';
            $this->App_model->view(TPL_ADMIN, $data);
    }
    
// IMÁGENES DEL PRODUCTO
//-----------------------------------------------------------------------------

    function images($product_id)
    {
        $data = $this->Producto_model->basico($product_id);

        $data['images'] = $this->Producto_model->images($product_id);

        //$data['file_form_action'] = 'send_file_form';

        $data['view_a'] = $this->views_folder . 'images/images_v';
        $data['nav_2'] = $this->views_folder . 'menu_v';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * AJAX JSON
     * Imágenes de un producto
     * 2020-07-07
     */
    function get_images($product_id)
    {
        $images = $this->Producto_model->images($product_id);
        $data['images'] = $images->result();

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Asocia una imagen a un producto, lo carga en la tabla file, y lo asocia en la tabla
     * products_meta
     * 2020-07-06
     */
    function add_image($product_id)
    {
        //Cargue
        $this->load->model('File_model');
        $data_upload = $this->File_model->upload();

        $data = $data_upload;
        if ( $data_upload['status'] )
        {
            $data['meta_id'] = $this->Producto_model->add_image($product_id, $data_upload['row']->id);   //Asociar en la tabla products_meta
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Establecer imagen principal de un producto
     * 2020-07-07
     */
    function set_main_image($product_id, $meta_id)
    {
        $data = $this->Producto_model->set_main_image($product_id, $meta_id);
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Elimina una imagen de un producto, elimina el registro de la tabla file
     * y sus archivos relacionados
     * 2020-07-08
     */
    function delete_image($product_id, $meta_id)
    {
        $data['qty_deleted'] = $this->Producto_model->delete_image($product_id, $meta_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
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
            $data['view_a'] = $this->views_folder . 'tags_v';
            $this->App_model->view(TPL_ADMIN, $data);
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
            $data['view_a'] = $this->views_folder . 'variaciones_v';
            $this->App_model->view(TPL_ADMIN, $data);
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
            $data['head_title'] = 'Productos';
            $data['nav_2'] = $this->views_folder . 'explorar/menu_v';
            $data['view_a'] = $this->views_folder .  'procesos_v';
            $this->App_model->view(TPL_ADMIN, $data);
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
        
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
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
            $data['head_title'] = 'Productos';
            $data['nav_2'] = $this->views_folder . 'explorar/menu_v';
            $data['view_a'] = $this->views_folder .  'cargar_v';
            $this->App_model->view(TPL_ADMIN, $data);
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

// Actualizar datos de productos mediante archivo de excel
//-----------------------------------------------------------------------------
    
    /**
     * Mostrar formulario de importación de existencais de productos mediante 
     * archivo MS Excel. El resultado del formulario se envía a 
     * 'productos/importar_existencias_e'
     * 
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
            $data['head_title'] = 'Productos';
            $data['view_a'] = 'comunes/importar_v';
            //$data['view_a'] = 'common/import_v';
            $data['nav_2'] = $this->views_folder . 'explore/menu_v';
        
        $this->App_model->view(TPL_ADMIN, $data);
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
            $data['head_title'] = 'Productos';
            $data['view_a'] = 'comunes/resultado_importacion_v';
            $data['nav_2'] = $this->views_folder . 'explore/menu_v';
        
        $this->App_model->view(TPL_ADMIN, $data);
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
            $data['subhead_title'] = 'Editar detalles';
            $data['vista_b'] = 'comunes/gc_v';

        
        $output = array_merge($data,(array)$output);
        $this->App_model->view(TPL_ADMIN, $output);
    }
    
// PROMOCIONES
//-----------------------------------------------------------------------------
    
    /**
     * Vista Grocery Crud, promociones de productos
     */
    function promociones()
    {
        $this->load->model('Post_model');
        $gc_output = $this->Post_model->crud_promociones();
        
        //Array data espefícicas
        $data['head_title'] = 'Productos';
        $data['view_a'] = 'common/gc_v';
        $data['nav_2'] = $this->views_folder . 'explore/menu_v';
        
        $output = array_merge($data,(array)$gc_output);
        $this->App_model->view(TPL_ADMIN, $output);
    }

// CONTENIDOS ASIGNADOS
//-----------------------------------------------------------------------------

    /**
     * Contenidos digitales asignados a un producto
     * 2020-04-15
     */
    function books($producto_id)
    {        
        $this->load->model('Archivo_model');
        

        $data = $this->Producto_model->basico($producto_id);

        $data['books'] = $this->Producto_model->assigned_posts($producto_id);
        $data['options_book'] = $this->App_model->opciones_post('tipo_id = 8', 'n', 'Libro');

        $data['view_a'] = 'productos/books_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Agregar un post a un producto
     * 2021-01-27
     */
    function add_post($producto_id, $post_id)
    {
        $data = $this->Producto_model->add_post($producto_id, $post_id);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Quitar un post de un producto, segun el meta_id
     * 2021-01-27
     */
    function remove_post($producto_id, $meta_id)
    {
        $data = $this->Producto_model->remove_post($producto_id, $meta_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// Procesos masivos
//-----------------------------------------------------------------------------

    /**
     * Actualizar tabla producto, campos url_image y url_thumbnail
     * 2021-05-19
     */
    function actualizar_campos_imagenes()
    {
        $this->db->select('id, imagen_id');
        //$this->db->where('url_image = ""');
        $productos = $this->db->get('producto');

        $qty_updated = 0;

        foreach ( $productos->result() as $producto )
        {
            $archivo = $this->Db_model->row_id('archivo', $producto->imagen_id);
            if ( ! is_null($archivo) )
            {
                $arr_row['url_image'] = $archivo->url;
                $arr_row['url_thumbnail'] = $archivo->url_thumbnail;
    
                $this->db->where('id', $producto->id)->update('producto', $arr_row);
                
                $qty_updated += $this->db->affected_rows();

                //Actualizar archivo como principal
                $this->db->query("UPDATE archivo SET integer_1 = 1 WHERE id = {$archivo->id}");
            }

        }

        $data['status'] = 1;
        $data['message'] = "Productos actualizados: {$qty_updated}";
    
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

}