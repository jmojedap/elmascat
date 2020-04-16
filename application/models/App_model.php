<?php
class App_model extends CI_Model{
    
    /* App, hace referencia a Application,
     * Colección de funciones creadas para utilizarse específicamente
     * con CodeIgniter en la apliación del sitio
     * 
     * Districatólicas.com
     * 
     */
    
    function __construct(){
        parent::__construct();
        
    }
    
//---------------------------------------------------------------------------------------------------------
//SISTEMA

    /**
     * Carga la view solicitada, si por get se solicita una view específica
     * se devuelve por secciones el html de la view, por JSON.
     * 
     * @param type $view
     * @param type $data
     */
    function view($view, $data)
    {
        if ( $this->input->get('json') )
        {
            //Sende sections JSON
            $result['head_title'] = $data['head_title'];
            $result['head_subtitle'] = '';
            $result['nav_2'] = '';
            $result['nav_3'] = '';
            $result['view_a'] = '';
            
            if ( isset($data['head_subtitle']) ) { $result['head_subtitle'] = $data['head_subtitle']; }
            if ( isset($data['view_a']) ) { $result['view_a'] = $this->load->view($data['view_a'], $data, TRUE); }
            if ( isset($data['nav_2']) ) { $result['nav_2'] = $this->load->view($data['nav_2'], $data, TRUE); }
            if ( isset($data['nav_3']) ) { $result['nav_3'] = $this->load->view($data['nav_3'], $data, TRUE); }
            
            $this->output->set_content_type('application/json')->set_output(json_encode($result));
            //echo trim(json_encode($result));
        } else {
            //Cargar view completa de forma normal
            $this->load->view($view, $data);
        }
    }
    
    function menu_actual()
    {
        if( $this->session->userdata('rol_id') == 0 ){
            $menu_current = $this->menu_general();
        } elseif ( $this->session->userdata('rol_id') == 1 ) {
            $menu_current = $this->menu_general();
        } elseif ( $this->session->userdata('rol_id') == 2 ) {
            $menu_current = $this->menu_general();
        } elseif ( $this->session->userdata('rol_id') == 5 ) {
            $menu_current = $this->menu_suscriptor();
        } elseif ( $this->session->userdata('rol_id') == 7 ) {
            $menu_current = $this->menu_general();
        }
        
        return $menu_current;
    }
    
    function menu_general()
    {
        
        //Usuarios
            $menus['usuarios/explorar'] = array('usuarios', 'usuarios-explorar');
            $menus['usuarios/mi_perfil'] = array('usuarios', 'usuarios-mi_perfil');
            $menus['usuarios/info'] = array('usuarios', 'usuarios');
            $menus['usuarios/contrasena'] = array('usuarios', 'usuarios-mi_perfil');
            $menus['usuarios/editar_mi_perfil'] = array('usuarios', 'usuarios-mi_perfil');
            $menus['usuarios/nuevo'] = array('usuarios');
            $menus['usuarios/editar'] = array('usuarios');
            $menus['usuarios/solicitudes_rol'] = array('usuarios');
        
        //Productos
            $menus['productos/explorar'] = array('productos');
            $menus['productos/nuevo'] = array('productos');
            $menus['productos/editar'] = array('productos');
            $menus['productos/ver'] = array('productos');
            $menus['productos/editar_img'] = array('productos');
            $menus['productos/cargar'] = array('productos', '');
            $menus['productos/procesos'] = array('productos', '');
            $menus['productos/importar_existencias'] = array('productos', '');
            $menus['productos/importar_existencias_e'] = array('productos', '');

        //Fletes
            $menus['fletes/explorar'] = array('fletes');
            $menus['fletes/nuevo'] = array('fletes');
            $menus['fletes/editar'] = array('fletes');
            
        //Pedidos
            $menus['pedidos/explorar'] = array('pedidos');
            $menus['pedidos/ver'] = array('pedidos');
            $menus['pedidos/nuevo'] = array('pedidos');
            $menus['pedidos/editar'] = array('pedidos');
            
        //Archivos
            $menus['archivos/imagenes'] = array('archivos', 'archivos-imagenes');
            $menus['archivos/cargar'] = array('archivos', 'archivos-cargar');
            $menus['archivos/editar'] = array('archivos', 'archivos-editar');
            
        //Datos
            $menus['datos/sis_opcion'] = array('ajustes', 'ajustes-parametros');
            $menus['datos/categorias'] = array('ajustes', 'ajustes-parametros');
            $menus['datos/palabras_clave'] = array('ajustes', 'ajustes-parametros');
            $menus['datos/listas'] = array('ajustes', 'ajustes-parametros');
            $menus['datos/metadatos'] = array('ajustes', 'ajustes-parametros');
            $menus['datos/fabricantes'] = array('ajustes', 'ajustes-parametros');
            $menus['datos/extras'] = array('ajustes', 'ajustes-parametros');
            $menus['datos/estado_pedido'] = array('ajustes', 'ajustes-parametros');
            
            $menus['develop/tablas'] = array('ajustes', 'ajustes-database');
            $menus['develop/acl_recursos'] = array('ajustes', 'ajustes-procesos');
            $menus['develop/procesos'] = array('ajustes', 'ajustes-procesos');
            $menus['sincro/panel'] = array('ajustes', 'ajustes-database');
            
            $menus['items/explorar'] = array('ajustes', 'ajustes-items');
            $menus['items/nuevo'] = array('ajustes', 'ajustes-items');
            $menus['items/editar'] = array('ajustes', 'ajustes-items');
            
            $menus['lugares/explorar'] = array('ajustes', 'ajustes-lugares');
            $menus['lugares/nuevo'] = array('ajustes', 'ajustes-lugares');
            $menus['lugares/editar'] = array('ajustes', 'ajustes-lugares');
            $menus['lugares/pedidos'] = array('ajustes', 'ajustes-lugares');
        
        $ubicacion = $this->uri->segment(1) . '/' . $this->uri->segment(2);
        $menu_actual = $menus[$ubicacion];
        
        return $menu_actual;
    }
    
    function menu_suscriptor()
    {
        $ubicacion = $this->uri->segment(1) . '/' . $this->uri->segment(2);
        
        //Usuarios
            $menus['usuarios/mi_perfil'] = array('usuarios', 'usuarios-mi_perfil');
            $menus['usuarios/contrasena'] = array('usuarios', 'usuarios-mi_perfil');
            $menus['usuarios/editar_mi_perfil'] = array('usuarios', 'usuarios-mi_perfil');
            
        //Pedidos
            $menus['pedidos/mis_pedidos'] = array('pedidos');
        
        $menu_actual = $menus[$ubicacion];
        
        return $menu_actual;
    }
    
    /**
     * Devuelve el valor del campo sis_opcion.valor
     * @param type $opcion_id
     * @return type
     */
    function valor_opcion($opcion_id)
    {
        $valor_opcion = $this->Pcrn->campo_id('sis_opcion', $opcion_id, 'valor');
        return $valor_opcion;
    }
    
// AUTOCOMPLETAR (ac)
//-----------------------------------------------------------------------------
    
    /**
     * Select AutoCompletar
     * Devuelve el segmento sql SELECT, para construir las las consultas de 
     * resultados en las busquedas autocompletar (ac).
     */
    function select_ac($tabla)
    {
        $select = "id, nombre_{$tabla} AS nombre_ac";
        
        $arr_select = array(
            'lugar' => 'nombre_lugar',
            'usuario' => 'username',
            'archivo' => 'CONCAT ((id), " | ", (titulo_archivo), " | ", (nombre_archivo))',
        );

        if ( array_key_exists($tabla, $arr_select) ) {
            $select = 'id, ' . $arr_select[$tabla] . ' AS nombre_ac';
        }
        
        return $select;
    }
    
    /**
     * Nombre AutoCompletar
     * String con nombre de un elemento con el formato de las consultas de auto
     * completar.
     * 
     * @param type $tabla
     * @param type $elemento_id
     * @return type
     */
    function nombre_ac($tabla, $elemento_id)
    {
        $nombre_elemento = 'ND';
        $select = $this->select_ac($tabla);
        
        //Consulta
            $this->db->select($select);
            $this->db->where('id', $elemento_id);
            $query = $this->db->get($tabla);
        
        if ( $query->num_rows() > 0 ) { $nombre_elemento = $query->row()->nombre_ac; }
        
        return $nombre_elemento;
    }
    
//---------------------------------------------------------------------------------------------------------
//GESTIÓN DE NOMBRES
    
    function nombre($tabla, $elemento_id, $formato = 1)
    {
        $nombre_elemento = 'ND';
        switch ($tabla) 
        {
            case 'lugar':
                $nombre_elemento = $this->nombre_lugar($elemento_id, $formato);
                break;
            case 'usuario';
                $nombre_elemento = $this->nombre_usuario($elemento_id, $formato);
            case 'archivo';
                $nombre_elemento = $this->Pcrn->campo_id('archivo', $elemento_id, 'titulo_archivo');
            default:
                break;
        }
        
        return $nombre_elemento;
    }
    
    /**
     * Devuelve el nombre de un usuario ($usuario_id)
     * en un formato específico ($formato)
     * 
     * @param type $usuario_id
     * @param type $formato
     * @return string 
     */
    function nombre_usuario($usuario_id, $formato = 1)
    {
        
        if ( ! empty($usuario_id) ){
            $row = $this->Pcrn->registro('usuario', "id = {$usuario_id}");
        
            if ( $formato == 1 ){
                $nombre_usuario = $row->username;
            } elseif ( $formato == 2 ){
                $nombre_usuario = "{$row->nombre} {$row->apellidos}";
            } elseif ( $formato == 3 ){
                $nombre_usuario = "{$row->apellidos} {$row->nombre}";
            }
        } else {
            //Si el varlor de $usuario_id está vacío
            $nombre_usuario = "(Vacío)";
        }
        
        
        
        return $nombre_usuario;
    }
    
    /**
     * Devuelve el nombre de una registro ($item_id)
     * en un formato específico ($formato).
     * Si se define una categoría ($categoria_id), $item_id hace referencia al campo id_interno
     * @param type $item_id
     * @param type $formato
     * @param type $categoria_id
     * @return type
     */
    function nombre_item($item_id, $formato = 1, $categoria_id = NULL)
    {
        $item_id = $this->Pcrn->si_nulo($item_id, 0);
        
        if ( is_null($categoria_id) ){
            //Se hace referencia al id absoluto > item.id
            $row = $this->Pcrn->registro('item', "id = {$item_id}");
        } else {
            //Se hace referencia al id_interno de la categoría > item.id_interno
            $row = $this->Pcrn->registro('item', "id_interno = {$item_id} AND categoria_id = {$categoria_id}");
        }

        //Se muestra un valor dependiendo del formato ($formato) seleccionado
        if ( $formato == 1 ){
            $nombre_item = $row->item;
        } elseif ( $formato == 2 ) {
            $nombre_item = $row->item_largo;
        }
        
        return $nombre_item;
    }
    
    /* Devuelve el nombre de una registro ($lugar_id)
    * en un formato específico ($formato).
    * Si se define una categoría ($categoria_id), $lugar_id hace referencia al campo id_interno
    */
    function nombre_lugar($lugar_id, $formato = 1)
    {
        
        $nombre_lugar = '-';
        
        if ( strlen($lugar_id) > 0 )
        {
            
            $this->db->select("lugar.id, lugar.nombre_lugar, region, pais"); 
            $this->db->where('lugar.id', $lugar_id);
            $row = $this->db->get('lugar')->row();

            if ( $formato == 1 ){
                $nombre_lugar = $row->nombre_lugar;
            } elseif ( $formato == 'CR' ) {
                $nombre_lugar = $row->nombre_lugar . ', ' . $row->region;
            } elseif ( $formato == 'CRP' ) {
                $nombre_lugar = $row->nombre_lugar . ' - ' . $row->region . ' - ' . $row->pais;
            }
            
        } else {
            //El valor está vacío
            $nombre_lugar = 'ND/NA';
        }
        
        
        return $nombre_lugar;
    }
    
    
    /**
     * Devuelve el nombre de un post ($post_id)
     * en un formato específico ($formato).
     * 
     * @param type $post_id
     * @param type $formato
     * @return string
     */
    function nombre_post($post_id, $formato = 'n')
    {
        $nombre_post = '-';
        
        if ( ! is_null($post_id) )
        {
            $row = $this->Pcrn->registro_id('post', $post_id);

            if ( $formato == 'n' )
            {
                $nombre_post = $row->nombre_post;
            }
        }
        
        return $nombre_post;
    }
    
    /**
     * 
     * 
     * @param type $producto_id
     * @param type $formato
     * @return string
     */
    function nombre_producto($producto_id, $formato = 1)
    {
        
        if ( ! is_null($producto_id) ){
            $row = $this->Pcrn->registro('producto', "id = {$producto_id}");
            if ( $formato == 1 ){
                $nombre_producto = $row->nombre_producto;
            }
        } else {
            $nombre_producto = "ND";
        }
        
        return $nombre_producto;
    }

    
//---------------------------------------------------------------------------------------------------------
//OTRAS FUNCIONES
    
    
    /** 
     * Devuelve un array con la configuración para generar
     * los links de paginación, Class Pagination
     * 
     * @param type $formato
     * @return string
     */
    function config_paginacion($formato = 1)
    {
        
            $config['per_page']   = 25;
            $config['num_links']   = 4;
            $config['uri_segment']  = 4;
            $config['prev_link'] = '<i class="fa fa-caret-left"></i>';
            $config['next_link'] = '<i class="fa fa-caret-right"></i>';;
            $config['last_link'] = '<i class="fa fa-step-forward"></i>';
            $config['first_link'] = '<i class="fa fa-step-backward"></i>';
            $config['first_tag_open'] = '<li class="pagination">';
            $config['first_tag_close'] = '</li>';
            $config['last_tag_open'] = '<li class="pagination">';
            $config['last_tag_close'] = '</li>';
            $config['next_tag_open'] = '<li class="pagination">';
            $config['next_tag_close'] = '</li>';
            $config['prev_tag_open'] = '<li class="pagination">';
            $config['prev_tag_close'] = '</li>';
            $config['num_tag_open'] = '<li class="pagination">';
            $config['num_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li class="pagination active"><span>';
            $config['cur_tag_close'] = '</span></li>';
            $config['full_tag_open'] = '<nav><ul class="pagination">';
            $config['full_tag_close'] = '</ul></nav>';
        
        if ( $formato == 1 ){
            $config['page_query_string'] = TRUE;    //Variables por GET
        } elseif  ( $formato == 2 ) {
            $config['full_tag_open'] = '<ul class="pagination pagination-sm no-margin">';
            $config['full_tag_close'] = '</ul>';
            $config['prev_link'] = '<i class="fa fa-caret-left"></i>';
            $config['next_link'] = '<i class="fa fa-caret-right"></i>';;
            $config['last_link'] = '<i class="fa fa-step-forward"></i>';
            $config['first_link'] = '<i class="fa fa-step-backward"></i>';
            $config['first_tag_open'] = '<li class="pagination">';
            $config['first_tag_close'] = '</li>';
            $config['last_tag_open'] = '<li class="pagination">';
            $config['last_tag_close'] = '</li>';
            $config['next_tag_open'] = '<li class="pagination">';
            $config['next_tag_close'] = '</li>';
            $config['prev_tag_open'] = '<li class="pagination">';
            $config['prev_tag_close'] = '</li>';
            $config['num_tag_open'] = '<li class="pagination">';
            $config['num_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li class="pagination active"><span>';
            $config['cur_tag_close'] = '</span></li>';
            $config['page_query_string'] = TRUE;    //Variables por GET
        } elseif  ( $formato == 3 ) {   //Polo Template
            $config['full_tag_open'] = '<ul class="pagination">';
            $config['full_tag_close'] = '</ul>';
            $config['prev_link'] = '<i class="fa fa-caret-left"></i>';
            $config['next_link'] = '<i class="fa fa-caret-right"></i>';
            $config['last_link'] = '<i class="fa fa-step-forward"></i>';
            $config['first_link'] = '<i class="fa fa-step-backward"></i>';
            $config['first_tag_open'] = '<li>';
            $config['first_tag_close'] = '</li>';
            $config['last_tag_open'] = '<li class="">';
            $config['last_tag_close'] = '</li>';
            $config['next_tag_open'] = '<li class="">';
            $config['next_tag_close'] = '</li>';
            $config['prev_tag_open'] = '<li class="">';
            $config['prev_tag_close'] = '</li>';
            $config['num_tag_open'] = '<li class="">';
            $config['num_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li class="active"><a href="#">';
            $config['cur_tag_close'] = '</a></li>';
            $config['page_query_string'] = TRUE;    //Variables por GET
        }
        return $config;
        
    }
    
    /**
     * Eliminar meta dato de producto de la tabla meta
     * @param type $meta_id
     */
    function eliminar_meta($meta_id)
    {
        $row_meta = $this->Pcrn->registro_id('meta', $meta_id);
        
        $this->db->where('id', $meta_id);
        $this->db->delete('meta');
        
        return $row_meta;
    }
    
//IMÁGENES
//---------------------------------------------------------------------------------------------------------

    function row_img($archivo_id, $prefijo)
    {
        $this->load->model('Archivo_model');
        $row_img = $this->Archivo_model->row_img($archivo_id, $prefijo);
        return $row_img;
    }
    
    
//---------------------------------------------------------------------------------------------------------
//OPCIONES PARA DROPDOWN, EN FORMULARIOS
    
    /**
     * Devuelve un array con índice y valor para una tabla
     * 
     * @param type $tabla :: Nombre de la tabla
     * @param type $condicion :: Condición SQL para filtrar resultados
     * @param type $campo_valor :: nombre del campo del valor de los elementos del array
     * @param type $campo_indice :: nombre del campo del índice del array
     * @param type $str :: Definie si el índice del array es uns string o numérico
     * @return type
     */
    function arr_tabla($tabla, $condicion, $campo_valor, $campo_indice, $str = TRUE)
    {
        
        $indice = 'campo_indice_str';
        if ( ! $str ) { $indice = 'campo_indice'; }
        
        $select = $campo_indice . ' AS campo_indice, CONCAT("0", (' . $campo_indice . ')) AS campo_indice_str, ' . $campo_valor .' AS campo_valor';
        
        $this->db->select($select);
        if ( ! is_null($condicion) ) { $this->db->where($condicion); }
        $this->db->order_by($campo_valor, 'ASC');
        $query = $this->db->get($tabla);
        
        $arr_item = $this->Pcrn->query_to_array($query, 'campo_valor', $indice);
        
        return $arr_item;
    }
    
    /* Devuelve un array con las opciones de la tabla lugar, limitadas por una condición definida
    * en un formato ($formato) definido
    */
    function opciones_lugar($condicion, $formato = 1, $texto_vacio = ' Lugar ')
    {
        
        $this->db->select("CONCAT('0', lugar.id) AS lugar_id, nombre_lugar, CONCAT((nombre_lugar), ', ', (region)) AS cr, CONCAT((nombre_lugar), ' (', (region), ' - ', (pais),')') AS crp", FALSE); 
        $this->db->where($condicion);
        $this->db->order_by('lugar.nombre_lugar', 'ASC');
        $query = $this->db->get('lugar');
        
        $campo_indice = 'lugar_id';
        
        if ( $formato == 1 )
        {
            $campo_valor = 'nombre_lugar';
        } elseif ( $formato == 'CR' ) {
            $campo_valor = 'cr';
        } elseif ( $formato == 'CRP' ) {
            $campo_valor = 'crp';
        }
        
        $opciones_lugar = array_merge(array('' => '[ ' . $texto_vacio . ' ]'), $this->Pcrn->query_to_array($query, $campo_valor, $campo_indice));
        
        return $opciones_lugar;
    }
    
    /* Devuelve un array con las opciones de la tabla post, limitadas por una condición definida
    * en un formato ($formato) definido
    */
    function opciones_post($condicion, $formato = 'n', $texto_vacio = 'Post')
    {
        
        $this->db->select("CONCAT('0', post.id) AS post_id, nombre_post", FALSE); 
        $this->db->where($condicion);
        $this->db->order_by('post.nombre_post', 'ASC');
        $query = $this->db->get('post');
        
        $campo_indice = 'post_id';
        
        if ( $formato == 'n' )
        {
            $campo_valor = 'nombre_post';
        }
        
        $opciones_post = array_merge(array('' => '[ ' . $texto_vacio . ' ]'), $this->Pcrn->query_to_array($query, $campo_valor, $campo_indice));
        
        return $opciones_post;
    }
    
    /* Devuelve un array con las opciones de la tabla usuario, limitadas por una condición definida
     * en un formato ($formato) definido
     */
    function opciones_usuario($condicion, $formato = 1)
    {
        
        
        $this->db->select("CONCAT('0', id) as usuario_id, CONCAT(apellidos, ' ', nombre, ' (', username, ')') AS na_username", FALSE); 
        $this->db->where($condicion);
        $this->db->order_by('apellidos', 'ASC');
        $query = $this->db->get('usuario');
        
        $campo_indice = "usuario_id";
        
        if ( $formato == 1 ){
            $campo_valor = "na_username";
        }
        
        $opciones_usuario = array(
            "" => "(Vacío)"
        );
        
        $opciones_usuario = array_merge($opciones_usuario, $this->Pcrn->query_to_array($query, $campo_valor, $campo_indice));
        
        return $opciones_usuario;
    }
    
    /**
     * Devuelve un array con las opciones de la tabla producto, limitadas por una condición definida
     * en un formato ($formato) definido
     * @param type $condicion
     * @param type $formato
     * @return type
     */
    function opciones_producto($condicion, $formato = 1)
    {

        
        $this->db->select("CONCAT('0', id) as producto_id, nombre_producto", FALSE); 
        $this->db->where($condicion);
        $this->db->order_by('nombre_producto', 'ASC');
        $query = $this->db->get('producto');
        
        $campo_indice = "producto_id";
        
        if ( $formato == 1 ){
            $campo_valor = 'nombre_producto';
        }
        
        $opciones_producto = array(
            '' => '(Seleccione el producto)'
        );
        
        $opciones_producto = array_merge($opciones_producto, $this->Pcrn->query_to_array($query, $campo_valor, $campo_indice));
        
        return $opciones_producto;
    }

    /**
     * Validación de Google Recaptcha V3, la validación se realiza considerando el valor de
     * $recaptcha->score, que va de 0 a 1.
     * 2019-10-31
     */
    function recaptcha()
    {
        $secret = K_RCSC;   //Ver config/constants.php
        $response = $this->input->post('g-recaptcha-response');
        $json_recaptcha = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$response}");
        $recaptcha = json_decode($json_recaptcha);
        
        return $recaptcha;
    }
    
    
    /**
     * Query con las etiquetas de producto
     * 
     * @param type $padre_id
     * @return type
     */
    function tags($padre_id = 0)
    {
        $this->db->where('padre_id', $padre_id);
        $this->db->where('categoria_id', 21);   //Categorías
        $this->db->order_by('item', 'ASC');
        $query = $this->db->get('item');
        
        return $query;
    }
    
    /**
     * Query con las categorías de producto
     * 
     * @param type $padre_id
     * @return type
     */
    function fabricantes()
    {
        
        $this->db->select('item.id, item.item AS nombre_fabricante, item.slug, COUNT(producto.id) AS cant_productos');
        $this->db->where('item.categoria_id', 5);   //Fabricantes
        $this->db->join('producto', 'item.id = producto.fabricante_id');
        $this->db->group_by('item.id, item.item, slug');
        $this->db->order_by('COUNT(producto.id)', 'DESC');
        $query = $this->db->get('item');
        
        return $query;
    }
    
    function imagenes_carrusel()
    {
        $lista_id = $this->Pcrn->campo_id('sis_opcion', 104, 'valor');
        $select = 'titulo_archivo AS titulo, subtitulo, descripcion, link, CONCAT("' . URL_UPLOADS . '", (carpeta), (nombre_archivo)) AS src';
        
        $this->db->select($select);
        $this->db->where("id IN (SELECT elemento_id FROM meta WHERE tabla_id = 1020 AND dato_id = 22 AND relacionado_id = {$lista_id})");
        $this->db->order_by('id', 'RANDOM');
        $query = $this->db->get('archivo');
        
        return $query;
    }
    
    function email_style()
    {
        $style['body'] = "font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;";
        $style['h1'] = 'color: #00b0f0;';
        $style['a'] = 'color: #00b0f0';

        $style['table'] = 'width: 100%; border-collapse: collapse;';
        $style['thead'] = '';
        $style['td'] = 'border: 1px solid #DDD; padding: 5px;';

        $style['btn'] = 'background: #fdd922; padding: 10px; color: #333; text-decoration: none; border-radius: 5px';
        $style['resaltar'] = 'font-weight: bold; color: #da4631;';
        $style['suave'] = 'color: #aaa;';
        
        return $style;
    }
    
    /**
     * Devuelve el detalle de los productos de un pedido, asociado en la tabla pedido_detalle
     * Igual a 
     * 
     * @param type $pedido_id
     * @return type
     */
    function detalle_pedido($pedido_id)
    {
        $this->load->model('Pedido_model');
        $query = $this->Pedido_model->detalle($pedido_id);
        
        return $query;
    }
    
    /*function row_img($archivo_id)
    {
        return $this->Pcrn->registro_id('archivo', $archivo_id);
    }*/
    
    function att_img($producto_id, $ancho = 125)
    {
        $this->load->model('Producto_model');
        $att_img = $this->Producto_model->att_img($producto_id, $ancho);
        
        return $att_img;
    }
    
// GENERAL
//-----------------------------------------------------------------------------
    
    /**
     * Redondea hacia arriba un valor numérico a un múltiplo definido.
     * 
     * @param type $valor
     * @param type $multiplo
     * @return type
     */
    function redondear($valor, $multiplo = 50)
    {
        $redondeado = $redondeado = ceil($valor / $multiplo) * $multiplo;
        
        return $redondeado;
    }
    
// IMÁGENES
//-----------------------------------------------------------------------------
    
    function src_img_usuario($row_usuario, $prefijo = '')
    {
        $src = URL_IMG . 'usuarios/'. $prefijo . 'usuario.png';
            
        if ( $row_usuario->imagen_id > 0 )
        {
            $src = URL_UPLOADS . $row_usuario->carpeta_imagen . $prefijo . $row_usuario->archivo_imagen;
        }
        
        return $src;
    }
    
    function att_img_usuario($row_usuario, $prefijo = '')
    {
        $att_img = array(
            'src' => $this->src_img_usuario($row_usuario, $prefijo),
            'alt' => 'Imagen del usuario ' . $row_usuario->username,
            'width' => '100%'
        );
        
        return $att_img;
    }
    
}