<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tienda extends CI_Controller{

// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'ecommerce/tienda/';
    public $url_controller = URL_ADMIN . 'tienda/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct() 
    {
        parent::__construct();

        $this->load->model('Tienda_model');
        $this->load->model('Producto_model');
        //$this->load->model('Order_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
    function index($product_id)
    {
        redirect("tienda/producto/{$product_id}");
    }
    
//EXPLORE
//---------------------------------------------------------------------------------------------------

    /**
     * Exploración y búsqueda de productos
     * 2021-02-24
     */
    function productos($num_page = 1)
    {        
        //Identificar filtros de búsqueda
            $this->load->model('Search_model');
            $filters = $this->Search_model->filters();

        //Datos básicos de la exploración
            $data = $this->Tienda_model->explore_data($filters, $num_page);
        
        //Opciones de filtros de búsqueda
            $data['options_status'] = $this->Item_model->options('categoria_id = 8', 'Todos');
            $data['options_category'] = $this->Item_model->options('categoria_id = 25');
            
        //Arrays con valores para contenido en lista
            $data['arr_status'] = $this->Item_model->arr_cod('categoria_id = 8');
            $data['arr_categories'] = $this->Item_model->arr_cod('categoria_id = 25');
            //$data['arr_id_number_types'] = $this->Item_model->arr_item('category_id = 53', 'cod_abr');
            $data['view_a'] = 'templates/libreria/example';
            
        //Cargar vista
            $this->App_model->view('templates/libreria/main', $data);
    }

    /**
     * JSON
     * Listado de productos, según filtros de búsqueda
     */
    function get_products($num_page = 1, $per_page = 12)
    {
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();
        $data = $this->Tienda_model->get($filters, $num_page, $per_page);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// INFORMACIÓN
//-----------------------------------------------------------------------------

    /**
     * Información general del producto
     */
    function producto($product_id)
    {        
        //Datos básicos
        $data = $this->Tienda_model->basic($product_id);
        $data['images'] = $this->Producto_model->images($product_id);
        $data['cat_1_name'] = $this->Item_model->name(25, $data['row']->categoria_id);
        //$data['cat_2_name'] = $this->Item_model->name(25, $data['row']->cat_2);
        $data['metadatos'] = $this->Producto_model->metadatos_valor($product_id, 'visible');
        $data['tags'] = $this->Producto_model->tags($product_id);
        $data['best_price'] = $this->Producto_model->arr_precio($product_id);
        
        //Variables específicas
        $data['view_a'] = 'ecommerce/tienda/producto/producto_v';
        
        $this->App_model->view('templates/libreria/main', $data);
    }

// PROCESO DE PAGO
//-----------------------------------------------------------------------------

    /**
     * Pasos en el proceso de pago:
     * Step 1: formulario para completar datos personales
     * Step 2: Verificación de datos y totales
     */
    function carrito()
    {
        if ( ! is_null($this->session->userdata('order_code')) )
        {
            $order = $this->Order_model->row_by_code($this->session->userdata('order_code'));
            $data['order'] = $order;
    
            $data['products'] = $this->Order_model->products($order->id);
            $data['extras'] = $this->Order_model->extras($order->id);
        }
        
        $data['view_a'] = "ecommerce/tienda/carrito/carrito_v";
        $data['head_title'] = 'Proceso de pago';
        $this->App_model->view(TPL_FRONT, $data);
    }

    /**
     * Pasos en el proceso de pago:
     * Step 1: formulario para completar datos personales
     * Step 2: Verificación de datos y totales
     */
    function pago($step = 'tus_datos')
    {
        $order = $this->Order_model->row_by_code($this->session->userdata('order_code'));        

        $data['step'] = $step;
        $data['order'] = $order;
        $data['products'] = $this->Order_model->products($order->id);
        $data['extras'] = $this->Order_model->extras($order->id);

        
        $data['options_document_type'] = $this->Item_model->options('category_id = 53');
        $data['options_region'] = $this->App_model->options_place("country_id = {$order->country_id} AND type_id = 3 AND status = 1", 'full_name', 'Departamento');
        $data['options_city'] = $this->App_model->options_place("region_id = '{$order->region_id}' AND type_id = 4 AND status = 1", 'place_name', 'Ciudad');

        $this->load->model('Wompi_model');
        $data['form_destination'] = $this->Wompi_model->form_destination();
        $data['form_data'] = $this->Wompi_model->form_data($order);

        $data['head_title'] = 'Proceso de pago';
        $data['view_a'] = "ecommerce/tienda/pago/{$step}_v";
        $this->App_model->view(TPL_FRONT, $data);
    }

    /**
     * Vista HTML, Página de respuesta, redireccionada desde Wompi para mostrar el resultado
     * de una transacción de pago. Toma los datos desde la API de Wompi.
     */
    function resultado()
    {
        $this->load->model('Wompi_model');
        $data = $this->Wompi_model->result_data();

        //Si se identifica la compra
        if ( ! is_null($data['order_code']) ) {
            $this->Order_model->unset_session();
        }

        $data['step'] = 'resultado';  //Cuarto y último paso, resultado
        $data['view_a'] = 'ecommerce/tienda/pago/resultado_wompi_v';
        $this->App_model->view(TPL_FRONT, $data);
    }

    /**
     * Estado de una compra, visible para un cliente o comprador
     */
    function estado_compra($order_code = '')
    {
        $data['head_title'] = 'Order';
        $data['view_a'] = 'ecommerce/tienda/estado_compra_v';
        $data['order_code'] = $order_code;

        $data['arr_order_status'] = $this->Item_model->arr_cod('category_id = 7');
        $data['arr_shipping_methods'] = $this->Item_model->arr_cod('category_id = 183');
        $data['arr_shipping_status'] = $this->Item_model->arr_cod('category_id = 187');
        $data['arr_document_types'] = $this->Item_model->arr_cod('category_id = 53');

        $this->App_model->view(TPL_FRONT, $data);
    }

    // otros
    //-----------------------------------------------------------------------------

    function test($destination_id = 909)
    {
        $this->load->model('Shipping_model');
        $data['shipping_costs'][1] = $this->Shipping_model->shipping_cost(909, $destination_id, 1);
        $data['shipping_costs'][2] = $this->Shipping_model->shipping_cost(909, $destination_id, 2);
        $data['shipping_costs'][4] = $this->Shipping_model->shipping_cost(909, $destination_id, 4);
        $data['shipping_costs'][8] = $this->Shipping_model->shipping_cost(909, $destination_id, 8);
        $data['shipping_costs'][16] = $this->Shipping_model->shipping_cost(909, $destination_id, 16);
        $data['shipping_costs'][32] = $this->Shipping_model->shipping_cost(909, $destination_id, 32);
        
        $data['array_intervals'] = $this->Shipping_model->array_intervals(909, $destination_id);
        $data['variable_cost'][1] = $this->Shipping_model->variable_cost(909, $destination_id, 1);
        $data['variable_cost'][2] = $this->Shipping_model->variable_cost(909, $destination_id, 2);
        $data['variable_cost'][5] = $this->Shipping_model->variable_cost(909, $destination_id, 5);
        $data['variable_cost'][6] = $this->Shipping_model->variable_cost(909, $destination_id, 6);
        $data['variable_cost'][7] = $this->Shipping_model->variable_cost(909, $destination_id, 7);
        $data['variable_cost'][8] = $this->Shipping_model->variable_cost(909, $destination_id, 8);
        $data['variable_cost'][16] = $this->Shipping_model->variable_cost(909, $destination_id, 16);
        $data['variable_cost'][20] = $this->Shipping_model->variable_cost(909, $destination_id, 20);
        $data['variable_cost'][32] = $this->Shipping_model->variable_cost(909, $destination_id, 32);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}