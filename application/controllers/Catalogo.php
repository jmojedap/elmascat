<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Catalogo extends CI_Controller{
    
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
            $data['head_title'] = NOMBRE_APP;
            $data['cant_resultados'] = $config['total_rows'];
            $data['busqueda'] = $busqueda;
            $data['busqueda_str'] = $busqueda_str;
            $data['resultados'] = $resultados;
            $data['tags'] = $this->App_model->tags();
            $data['fabricantes'] = $this->Producto_model->fabricantes();
        
        //Solicitar vista
            $data['view_a'] = 'productos/catalogo/catalogo_v';
            $this->load->view(TPL_FRONT, $data);
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
        
        redirect("catalogo/producto/{$producto_id}/{$slug}");
    }
    
    function producto($producto_id)
    {
        $this->load->model('Archivo_model');
        
        $data = $this->Producto_model->basico($producto_id);
        
        $data['metadatos'] = $this->Producto_model->metadatos_valor($producto_id, 'visible');
        $data['variaciones'] = $this->Producto_model->variaciones($producto_id);
        $data['comentarios'] = $this->Producto_model->comentarios($producto_id);
        $data['view_a'] = 'productos/detalle/producto_v';
        $data['arr_precio'] = $this->Producto_model->arr_precio($producto_id);
        $data['arr_tipos_precio'] = $this->Producto_model->arr_tipos_precio();
        
        //Datos
            $data['imagenes'] = $this->Producto_model->imagenes($producto_id);
            $data['palabras_clave'] = $this->Producto_model->palabras_clave($producto_id);
        
        $this->load->view(TPL_FRONT, $data);
    }

// PRODUCTOS CON CONTENIDOS DIGITALES
//-----------------------------------------------------------------------------

    function productos_digitales()
    {
        $this->load->model('Archivo_model');

        $data['head_title'] = 'Minutos de Amor :: En Línea';
        $data['view_a'] = 'catalogo/mda_en_linea/mda_en_linea_v';

        $this->db->where('categoria_id', 162);
        $this->db->where('estado', 1);
        $this->db->order_by('precio', 'ASC');
        $data['productos'] = $this->db->get('producto',4);

        $this->load->view(TPL_FRONT, $data);
    }

}