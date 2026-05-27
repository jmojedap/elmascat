<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pedidos extends CI_Controller{

// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'pedidos/';
    public $url_controller = URL_ADMIN . 'pedidos/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct() {
        parent::__construct();

        $this->load->model('Pedido_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }

//EXPLORACIÓN
//---------------------------------------------------------------------------------------------------

    /** Exploración de Pedidos */
    function explore($num_page = 1)
    {
        //Identificar filtros de búsqueda
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        //Datos básicos de la exploración
            $data = $this->Pedido_model->explore_data($filters, $num_page);
        
        //Opciones de filtros de búsqueda
            $data['options_status'] = $this->Item_model->options('categoria_id = 7', 'Todos');
            $data['options_crpol'] = $this->Item_model->options('categoria_id = 10', 'Todos');   //Codigo Respuesta POL
            $data['options_payment_channel'] = $this->Item_model->options('categoria_id = 106', 'Todos');   //Canales de pago
            
        //Arrays con valores para contenido en lista
            $data['arr_status'] = $this->Item_model->arr_cod('categoria_id = 7');
            $data['arr_payment_channel'] = $this->Item_model->arr_cod('categoria_id = 106');
            
        //Cargar vista
            $this->App_model->view(TPL_ADMIN, $data);
    }
    
    /**
     * AJAX
     * Eliminar un grupo de pedidos seleccionados
     */
    function eliminar_seleccionados()
    {
        $str_seleccionados = $this->input->post('seleccionados');
        
        $seleccionados = explode('-', $str_seleccionados);
        
        foreach ( $seleccionados as $elemento_id ) 
        {
            $this->Pedido_model->eliminar($elemento_id);
        }
        
        echo count($seleccionados);
    }
    
    /**
     * Exporta el resultado de la búsqueda a un archivo CSV
     * 2023-02-22: Separar ciudad, departamento, país
     */
    function exportar()
    {
        //Cargando
            $this->load->model('Search_model');

            $filters = $this->Search_model->filters();
            $query = $this->Pedido_model->query_export($filters); //Para calcular el total de resultados

        //Contenido del archivo
        $data['file_name'] = date('Ymd_His'). '_pedidos.csv';
        $data['content'] = $this->pml->content_query_to_csv($query);

        $this->load->view('app/export_csv_v', $data);
    }
    
    /**
     * Formulario de edición de los datos de gestión administrativa de un pedido
     * Se envía a pedidos/guardar_admin
     * 2021-11-22
     */
    function editar($pedido_id)
    {
        $data = $this->Pedido_model->basico($pedido_id);
            
        //Variables
            $data['options_estado_pedido'] = $this->Item_model->opciones('categoria_id = 7');
            $data['options_payment_channel'] = $this->Item_model->opciones('categoria_id = 106');
            $data['options_shipping_method'] = $this->Item_model->opciones('categoria_id = 183');

        //Variables generales
            $data['view_a'] = 'pedidos/editar_v';
            $data['back_link'] = $this->url_controller . 'explore/';

        $this->load->view(TPL_ADMIN, $data);
    }

    function ver($pedido_id)
    {
        redirect("pedidos/info/{$pedido_id}");
    }
    
    /**
     * Información general de un pedido
     * 2020-08-12
     */
    function info($pedido_id)
    {
        $data = $this->Pedido_model->basico($pedido_id);    
        $data['detalle'] = $this->Pedido_model->detalle($pedido_id);
        $data['extras'] = $this->Pedido_model->extras($pedido_id);
        $data['row_ciudad'] = $this->Pcrn->registro_id('lugar', $data['row']->ciudad_id);
        
        //Estados
            $data['estados'] = $this->db->get_where('item', 'categoria_id = 7');
        
        //Variables
            $data['pedido_id'] = $pedido_id;
            $data['comision_pol'] = $this->Pedido_model->comision_pol($pedido_id);
            $data['arr_meta'] = $this->Pedido_model->arr_meta($data['row']);
            $data['options_payment_channels'] = $this->Item_model->options('categoria_id = 106 AND filtro LIKE "%-manual-%"', 'Canal de pago');
            $data['missing_data'] = $this->Pedido_model->missing_data($data['row']);
            
        //Tipos de precio, promociones
            $this->load->model('Producto_model');
            $data['arr_tipos_precio'] = $this->Producto_model->arr_tipos_precio();
        
        //Solicitar vista
            $data['nav_2'] = 'pedidos/menu_v';
            $data['view_a'] = 'pedidos/info/info_v';
            $data['back_link'] = $this->url_controller . 'explore/';
            $this->load->view(TPL_ADMIN, $data);
    }
    
    /**
     * Información general de un pedido, para imprimir
     */
    function reporte($pedido_id, $tipo_reporte = 'general')
    {
        $data = $this->Pedido_model->basico($pedido_id);    
        $data['detalle'] = $this->Pedido_model->detalle($pedido_id);
        $data['extras'] = $this->Pedido_model->extras($pedido_id);
        $data['row_ciudad'] = $this->Pcrn->registro_id('lugar', $data['row']->ciudad_id);
        
        //Estados
            $data['estados'] = $this->db->get_where('item', 'categoria_id = 7');
            
        //Tipos de precio, promociones
            $this->load->model('Producto_model');
            $data['arr_tipos_precio'] = $this->Producto_model->arr_tipos_precio();
            
        //Metadatos de POL
            $condicion = "tabla_id = 3000 AND elemento_id = {$pedido_id} AND dato_id = 3005";
            $row_meta = $this->Pcrn->registro('meta', $condicion);
            
        //Array respuesta POL
            $arr_respuesta_pol = array();
            if ( ! is_null($row_meta) ) { $arr_respuesta_pol = json_decode($row_meta->valor, TRUE); }
        
        //Variables

            $data['pedido_id'] = $pedido_id;
            $data['comision_pol'] = $this->Pedido_model->comision_pol($pedido_id);
            $data['row_meta'] = $row_meta;
            $data['arr_respuesta_pol'] = $arr_respuesta_pol;
            $data['arr_meta'] = $this->Pedido_model->arr_meta($data['row']);
        
        //Solicitar vista
            $data['head_subtitle'] = $this->Pcrn->moneda($data['row']->valor_total);
            $data['view_a'] = "pedidos/reporte/{$tipo_reporte}_v";
            $this->load->view('templates/print_bs4/blank_v', $data);
    }

    /**
     * Función compatibilidad
     * 2020-08-12
     */
    function pol($pedido_id)
    {
        redirect("pedidos/payu/{$pedido_id}");
    }
    
    /**
     * Información del pedido asociada a PayU
     * Se muestran datos si se recibieron desde la página de confirmación
     */
    function payu($pedido_id)
    {
        $data = $this->Pedido_model->basico($pedido_id);    
        
        //Listado de confirmaciones realizadas por payu
            $confirmations = $this->Pedido_model->confirmations($pedido_id);

        //Datos de confirmación (3005) en tabla meta
            if ( $confirmations->num_rows() > 0 )
            {
                $row_meta = $confirmations->row();
                $json_rta_pol = $row_meta->valor;

                //Array respuesta POL
                    $arr_respuesta_pol = array();
                    $firma_pol_confirmacion = NULL;
                    
                    if ( strlen($json_rta_pol) )
                    {
                        $arr_respuesta_pol = json_decode($row_meta->valor, TRUE);
                        $firma_pol_confirmacion = $this->Pedido_model->firma_pol_confirmacion($pedido_id, $arr_respuesta_pol['estado_pol']);
                    }

                //Variables
                $data['row_meta'] = $row_meta;
                $data['arr_respuesta_pol'] = $arr_respuesta_pol;
                $data['firma_pol_confirmacion'] = $firma_pol_confirmacion;
            }
            
        //Variables
            $data['pedido_id'] = $pedido_id;
            $data['confirmations'] = $confirmations;
        
        //Solicitar vista
            $data['nav_2'] = 'pedidos/menu_v';
            $data['view_a'] = 'pedidos/payu_v';
            $data['back_link'] = $this->url_controller . 'explore/';
            $this->load->view(TPL_ADMIN, $data);
    }

// PAGO MANUAL
//-----------------------------------------------------------------------------

    /**
     * Formulario de datos de pago del pedido
     * 2021-11-22
     */
    function payment($pedido_id)
    {
        $data = $this->Pedido_model->basico($pedido_id);

        $data['confirmations'] = $this->Pedido_model->confirmations($pedido_id);

        $data['options_payment_channel'] = $this->Item_model->options('categoria_id = 106 AND filtro LIKE "%-manual-%"');
        $data['missing_data'] = $this->Pedido_model->missing_data($data['row']);

        $data['active_form'] = TRUE;
        if ( $data['row']->payment_channel > 0 AND $data['row']->payment_channel < 20 ) $data['active_form'] = FALSE;

        $data['view_a'] = 'pedidos/payment/payment_v';
        $data['back_link'] = $this->url_controller . 'explore/';
        $this->App_model->view(TPL_ADMIN, $data);
    }

// GESTIÓN DE EXTRAS PEDIDO
//-----------------------------------------------------------------------------

    /**
     * Cobros o descuentos extra de un pedido
     * 2020-03-13
     */
    function extras($pedido_id)
    {
        $data = $this->Pedido_model->basico($pedido_id);

        $data['extras'] = $this->Pedido_model->extras($pedido_id);
        $data['options_extra'] = $this->Item_model->opciones('categoria_id = 6', 'Extra Tipo');
        $data['arr_extra_types'] = $this->Item_model->arr_item(6, 'id_interno_num');
        $data['editable'] = ( $data['row']->estado_pedido == 1) ? 1 : 0 ; //Se pueden editar extras sí estado iniciado (1)
        if ( $this->session->userdata('role') <= 1 ) { $data['editable'] = 1; }

        $data['head_subtitle'] = 'Extras';
        $data['view_a'] = 'pedidos/extras/extras_v';
        $data['nav_2'] = 'pedidos/menu_v';
        $data['back_link'] = $this->url_controller . 'explore/';
        $this->load->view(TPL_ADMIN, $data);
    }

// PROCESO DE PAGO
//---------------------------------------------------------------------------------------------------
    
    /**
     * Vista del listado de productos que están en el carrito de compras, listos
     * para empezar el proceso de pago.
     */
    function carrito()
    {
        $this->load->model('Producto_model');
        
        
        if ( ! is_null($this->session->userdata('order_code')) ) 
        {
            $pedido = $this->Pedido_model->row_by_code($this->session->userdata('order_code'));
            $this->Pedido_model->act_totales($pedido->id);
            
            $data = $this->Pedido_model->basico($pedido->id);
            
            $data['order'] = $pedido;
            $data['products'] = $this->Pedido_model->detalle($pedido->id);
            $data['rol_comprador'] = $this->Pedido_model->rol_comprador($pedido->id);
            $data['extras'] = $this->Pedido_model->extras($pedido->id);
            $data['pedido_id'] = $pedido->id;
            $data['arr_tipos_precio'] = $this->Producto_model->arr_tipos_precio();
            $data['descuentos'] = $this->Pedido_model->descuentos($pedido->id);
            $data['arr_extras_pedidos'] = $this->Item_model->arr_item(6, 'id_interno_num');
            
            $data['destino_form'] = "pedidos/compra_a/{$data['row']->cod_pedido}";
            $data['view_a'] = 'pedidos/compra/compra_v';
            $data['view_b'] = 'pedidos/compra/carrito/carrito_v';
        } else {
            $data['view_a'] = 'pedidos/carrito_vacio_v';
        }
        
        //Solicitar vista
            $data['head_title'] = 'Carrito de compras';
            $this->load->view(TPL_FRONT, $data);
    }

    /**
     * Identificar y crear usuario si no existe.
     * 2021-09-27
     */
    function usuario()
    {
        $order_code = $this->session->userdata('order_code');
        $order = $this->Pedido_model->row_by_code($order_code);
        $data = $this->Pedido_model->basico($order->id);

        if ( $data['row']->usuario_id > 0 )
        {
            redirect('pedidos/compra_a');
        } else {
            $this->load->model('Evento_model');

            //Variables
                $data['recaptcha_sitekey'] = K_RCSK;    //config/constants.php
                $data['qty_digital_products'] = $this->Pedido_model->qty_digital_products($order->id);
                $data['options_year'] = $this->Evento_model->options_year(1920,2005);
                $data['options_month'] = $this->Evento_model->options_month();
                $data['options_day'] = $this->Evento_model->options_day();

            //Solicitar vista
                $data['head_title'] = 'Tus datos';
                $data['view_a'] = 'pedidos/compra/compra_v';
                $data['view_b'] = 'pedidos/compra/usuario/usuario_v';
                $this->load->view(TPL_FRONT, $data);
        }
    }
    
    /**
     * Etapa A del proceso de compra y pago. Se solicitan datos de localización
     * para entrega, ciudad. Y los datos personales de contacto.
     */
    function compra_a()
    {
        $this->load->model('Producto_model');
        $this->load->model('Flete_model');
        $this->load->model('Usuario_model');
        $this->load->model('Evento_model');
        
        $order_code = $this->session->userdata('order_code');
        $order = $this->Pedido_model->row_by_code($order_code);
        
        $data = $this->Pedido_model->basico($order->id);
        
        $data['detalle'] = $this->Pedido_model->detalle($order->id);
        $data['extras'] = $this->Pedido_model->extras($order->id);
        $data['arr_extras_pedidos'] = $this->Item_model->arr_item(6, 'id_interno_num');

        //Identificar region
            $region_id = 267; //Bogotá
            if ( $data['row']->region_id > 0 ) $region_id = $data['row']->region_id;

        //Opciones formulario
            $data['options_pais'] = $this->App_model->opciones_lugar("tipo_id = 2 AND activo = 1", 'nombre_lugar', 'País');
            $data['options_region'] = $this->App_model->opciones_lugar("tipo_id = 3 AND pais_id = 51", 'nombre_lugar');
            $data['options_ciudad'] = $this->App_model->opciones_lugar("(tipo_id = 4 AND activo = 1 AND region_id = {$region_id})", 'nombre_lugar');
            $data['options_tipo_documento'] = $this->Item_model->opciones('categoria_id = 53 AND filtro LIKE "%-cliente-%"');
            $data['options_year'] = $this->Evento_model->options_year(1920,2005);
            $data['options_month'] = $this->Evento_model->options_month();
            $data['options_day'] = $this->Evento_model->options_day();
            $data['row_usuario'] = $this->Db_model->row_id('usuario', $data['row']->usuario_id);

        //Si tiene peso o no, cambian los datos solicitados
            $data['view_b'] = 'pedidos/compra/compra_a/compra_a_v';
            /*if ( $data['row']->peso_total == 0 ) {
                $data['view_b'] = 'pedidos/compra/compra_a_sin_peso_v';
            }*/
        
        //Solicitar vista
            $data['head_title'] = 'Datos de entrega';
            $data['view_a'] = 'pedidos/compra/compra_v';
            $data['section_id'] = 'cart_items';
            $this->load->view(TPL_FRONT, $data);
    }
    
    /**
     * Resumen del pedido, productos y datos de entrega, para revisión antes
     * de ir a plataforma de pagos
     * 2020-02-27
     */
    function verificar()
    {
        $this->load->model('Producto_model');

        $order_code = $this->session->userdata('order_code');
        $order = $this->Pedido_model->row_by_code($order_code);
        
        $data = $this->Pedido_model->basico($order->id);
        $data['products'] = $this->Pedido_model->detalle($order->id);
        $data['extras'] = $this->Pedido_model->extras($order->id);
        $data['row_ciudad'] = $this->Db_model->row_id('lugar', $data['row']->ciudad_id);
        $data['arr_meta'] = $this->Pedido_model->arr_meta($data['row']);
        $data['validacion'] = $this->Pedido_model->validar($data['row']);
        $data['arr_extras_pedidos'] = $this->Item_model->arr_item(6, 'id_interno_num');
        
        //Datos para el formulario que se envía a PagosOnLine
            $data['form_data'] = $this->Pedido_model->form_data_pol($order->id);
            //Donde se envían los datos para el pago
            $data['destino_form'] = 'https://checkout.payulatam.com/ppp-web-gateway-payu';
            
            if ( $this->input->get('testing') == 1 )
            {
                $data['form_data'] = $this->Pedido_model->form_data_pol($order->id, 1);
            }
        
        //Solicitar vista
            $data['head_title'] = 'Verificar datos';
            $data['view_a'] = 'pedidos/compra/compra_v';
            $data['view_b'] = 'pedidos/compra/verificar_v';
            $data['section_id'] = 'cart_items';
            $this->load->view(TPL_FRONT, $data);
    }

    
    
    /**
     * Igual a la parte B de la compra (verificar), pero convierte los valores de
     * pago en dólares de Estados Unidos (USD)
     */
    function verificar_usd()
    {
        $this->load->model('Producto_model');
        $pedido_id = $this->session->userdata('pedido_id');
        
        $data = $this->Pedido_model->basico($pedido_id);
        $data['detalle'] = $this->Pedido_model->detalle($pedido_id);
        $data['row_ciudad'] = $this->Pcrn->registro_id('lugar', $data['row']->ciudad_id);
        $data['precio_dolar'] = $this->App_model->valor_opcion(105);    //Ver Ajustes > Parámetros > General > Id 105
        
        //Extras
            $arr_extras['gastos_envio'] = $this->Pedido_model->valor_extras($pedido_id, 'producto_id IN (1,4)');
            $data['arr_extras'] = $arr_extras;
        
        //Datos para el formulario que se envía a PagosOnLine
            $prueba = FALSE;
            if ( $this->input->get('prueba') == 1 ) { $prueba = TRUE; }
            $data['form_data'] = $this->Pedido_model->form_data_pol_usd($pedido_id, $prueba);
            $data['destino_form'] = 'https://checkout.payulatam.com/ppp-web-gateway-payu';  //Donde se envían los datos para el pago
        
        //Solicitar vista
            $data['head_title'] = 'Districatólicas';
            $data['view_a'] = 'pedidos/compra/compra_v';
            $data['view_b'] = 'pedidos/compra/verificar_usd_v';
            $data['section_id'] = 'cart_items';
            $this->load->view(TPL_FRONT, $data);
    }

    /**
     * Link de pago
     * 2020-03-31
     */
    function link_pago($cod_pedido)
    {
        $order = $this->Pedido_model->row_by_code($cod_pedido);
        $this->load->model('Producto_model');
        
        $data = $this->Pedido_model->basico($order->id);
        $data['detalle'] = $this->Pedido_model->detalle($order->id);
        $data['extras'] = $this->Pedido_model->extras($order->id);
        $data['arr_extras_pedidos'] = $this->Item_model->arr_item(6, 'id_interno_num');
        $data['row_ciudad'] = $this->Db_model->row_id('lugar', $data['row']->ciudad_id);
        $data['missing_data'] = $this->Pedido_model->missing_data($order);
        
        //Datos para el formulario que se envía a PagosOnLine
            $data['form_data'] = $this->Pedido_model->form_data_pol($order->id);
            $data['destino_form'] = 'https://checkout.payulatam.com/ppp-web-gateway-payu';  //Donde se envían los datos para el pago
        
        //Solicitar vista
            $data['head_title'] = 'Pagar pedido: ' . $order->cod_pedido;
            $data['view_a'] = 'pedidos/compra/compra_v';
            $data['view_b'] = 'pedidos/compra/link_pago_v';
            $this->load->view(TPL_FRONT, $data);
    
    }
    
    /**
     * Retoma un pedido, cargándolo a las variables de sesión
     * Como medida de seguridad se toma el código de pedido.
     */
    function retomar($cod_pedido)
    {
        $data['status'] = $this->Pedido_model->retomar($cod_pedido);
        redirect('pedidos/carrito');
    }
    
    function aplicar_desc_distribuidor($pedido_id)
    {
        $row = $this->Pcrn->registro_id('pedido', $pedido_id);
        $this->Pedido_model->act_desc_distribuidor($pedido_id);
        $this->Pedido_model->act_totales($pedido_id);
        redirect("pedidos/compra_a/{$row->cod_pedido}");
    }

//GESTIÓN DE DETALLE DE PEDIDO
//---------------------------------------------------------------------------------------------------
    
    /**
     * AJAX
     * Eliminar un grupo de productos de un pedido
     */
    function eliminar_seleccionados_detalle($pedido_id)
    {
        $str_seleccionados = $this->input->post('seleccionados');
        
        $seleccionados = explode('-', $str_seleccionados);
        $this->session->set_userdata('seleccionados', $str_seleccionados);
        
        foreach ( $seleccionados as $elemento_id ) 
        {
            $this->session->set_userdata('elemento_id', $elemento_id);
            $this->Pedido_model->eliminar_detalle($elemento_id);
        }
        
        $this->Pedido_model->act_totales($pedido_id);
        
        echo count($seleccionados);
    }
    
   

// EMPACAR COMO REGALO
//-----------------------------------------------------------------------------

    /**
     * Formulario donde se requieren al comprador los datos de empaque y tarjeta
     * del regalo
     * 2022-06-02
     */
    function datos_regalo()
    {
        $order_code = $this->session->userdata('order_code');
        $order = $this->Pedido_model->row_by_code($order_code);
        
        $data = $this->Pedido_model->basico($order->id);
        $data['products'] = $this->Pedido_model->detalle($order->id);
        $data['arr_meta'] = $this->Pedido_model->arr_meta($data['row']);
        $data['extras'] = $this->Pedido_model->extras($order->id);
        $data['arr_extras_pedidos'] = $this->Item_model->arr_item(6, 'id_interno_num');
        
        //Solicitar vista
            $data['head_title'] = 'Datos para regalo';
            $data['view_a'] = 'pedidos/compra/compra_v';
            $data['view_b'] = 'pedidos/compra/datos_regalo/datos_regalo_v';
            $data['section_id'] = 'cart_items';
            $this->load->view(TPL_FRONT, $data);
    }

    
    
//PAGOS ON LINE
//---------------------------------------------------------------------------------------------------
    
    /**
     * Formulario para probar el resultado de ejecución de la página de confirmación
     * ejecutada por PayU remotamente
     */
    function test($order_id, $type = 'confirmation')
    {
        $data = $this->Pedido_model->basico($order_id);
        
        $data['view_a'] = "pedidos/test/{$type}_v";        
        $data['nav_3'] = "pedidos/test/menu_v";
        $data['back_link'] = $this->url_controller . 'explore/';
        $this->App_model->view(TPL_ADMIN, $data);
    }
    
    /**
     * Vista previa del mensaje de correo electrónico, informando los cambios de los datos
     * de un pedido, cuando se modifican durante la administración, preparación y envío.
     */
    function email_admon($pedido_id)
    {
        $row_pedido = $this->Pcrn->registro_id('pedido', $pedido_id);
        
        $data['row_pedido'] = $row_pedido ;
        $data['detalle'] = $this->Pedido_model->detalle($row_pedido->id);
        
        $this->load->view('pedidos/mensaje_admon_v', $data);
    }
    
    /**
     * Página respuesta para mostrar datos de PagosOnLine al usuario
     */
    function respuesta()
    {
        $this->Pedido_model->cancel(); //Se abandona el pedido de las variables de sesión
        
        $arr_respuesta_pol = $this->Pedido_model->arr_respuesta_pol();
        $row_pedido = $this->Pedido_model->row_by_code($arr_respuesta_pol['ref_venta']);
        
        $this->load->model('Producto_model');
        $pedido_id = $row_pedido->id;
        
        $data = $this->Pedido_model->basico($pedido_id);
        $data['detalle'] = $this->Pedido_model->detalle($pedido_id);
        $data['extras'] = $this->Pedido_model->extras($pedido_id);
        $data['row_ciudad'] = $this->Pcrn->registro_id('lugar', $data['row']->ciudad_id);
        $data['arr_respuesta_pol'] = $arr_respuesta_pol;
        $data['row_pedido'] = $row_pedido;
        
        //Solicitar vista
            $data['head_title'] = 'Resultado';
            $data['view_a'] = 'pedidos/compra/compra_v';
            $data['view_b'] = 'pedidos/compra/respuesta_v';
            $this->load->view(TPL_FRONT, $data);
    }
    
    function respuesta_print()
    {
        //$this->output->enable_profiler(TRUE);
        $this->Pedido_model->cancel(); //Se abandona el pedido de las variables de sesión
        
        $arr_respuesta_pol = $this->Pedido_model->arr_respuesta_pol();
        $row_pedido = $this->Pedido_model->row_by_code($arr_respuesta_pol['ref_venta']);
        
        $this->load->model('Producto_model');
        $pedido_id = $row_pedido->id;
        
        $data = $this->Pedido_model->basico($pedido_id);
        $data['detalle'] = $this->Pedido_model->detalle($pedido_id);
        $data['extras'] = $this->Pedido_model->extras($pedido_id);
        $data['row_ciudad'] = $this->Pcrn->registro_id('lugar', $data['row']->ciudad_id);
        $data['arr_respuesta_pol'] = $arr_respuesta_pol;
        $data['row_pedido'] = $row_pedido;
        
        //Solicitar vista
            $data['titulo_pagina'] = 'Districatólicas Unidas S.A.S.';
            $data['vista_a'] = 'pedidos/compra/respuesta_v';
            $this->load->view('pedidos/compra/respuesta_print_v', $data);
    }
    
//GESTIÓN ADMINISTRATIVA DE LOS PEDIDOS
//---------------------------------------------------------------------------------------------------
    
    /**
     * Formulario y vista para consultar el estado de un pedido. Vista comercial
     * El usuario ingresa el código del pedido y se devuelve sus datos.
     */
    function estado()
    {
        $pedido_id = 0;
        $cod_pedido = $this->Pedido_model->cod_pedido();
        $row_pedido = NULL;
        $sin_resultado = FALSE;
        
        if ( strlen($cod_pedido) > 0 ) 
        {
            $row_pedido = $this->Pedido_model->row_by_code($cod_pedido);
            $pedido_id = NULL;
        }
        
        if ( ! is_null($row_pedido) )
        {
            $this->load->model('Producto_model');
            
            $pedido_id = $row_pedido->id;

            $data = $this->Pedido_model->basico($pedido_id);
            
            //Extras
            $arr_extras['gastos_envio'] = $this->Pedido_model->valor_extras($pedido_id, 'producto_id IN (1,4)');
            $arr_extras['dto_distribuidor'] = $this->Pedido_model->valor_extras($pedido_id, 'producto_id = 3');
            $data['arr_extras'] = $arr_extras;
            $data['arr_tipos_precio'] = $this->Producto_model->arr_tipos_precio();
            
            $data['detalle'] = $this->Pedido_model->detalle($pedido_id);
            $data['row_ciudad'] = $this->Pcrn->registro_id('lugar', $data['row']->ciudad_id);
        }
        
        //Variables
            $data['pedido_id'] = $pedido_id;
            $data['cod_pedido'] = $cod_pedido;
            $data['sin_resultado'] = $sin_resultado;
        
        //Solicitar vista
            $data['head_title'] = 'Estado de mi compra';
            $data['view_a'] = 'pedidos/estado_v';
            $this->load->view(TPL_FRONT, $data);
    }
    
    function mis_pedidos()
    {
        
        $this->load->model('Busqueda_model');
        $busqueda = $this->Busqueda_model->busqueda_array();
        $busqueda['condicion'] = "usuario_id = {$this->session->userdata('usuario_id')}";
        
        $pedidos = $this->Pedido_model->buscar($busqueda);
        
        //Variables
            $data['resultados'] = $pedidos;
        
        //Solicitar vista
            $data['titulo_pagina'] = 'Mis Pedidos';
            $data['subtitulo_pagina'] = $this->session->userdata('nombre_completo');
            $data['vista_a'] = 'pedidos/mis_pedidos_v';
            $this->load->view(PTL_ADMIN, $data);
    }

// COTIZADOR
//-----------------------------------------------------------------------------

    /**
     * Vista del listado de productos para hacer una cotización a un cliente,
     * simulando descuentos y valores de envío
     * 2025-02-10
     */
    function cotizador()
    {
        $this->load->model('Producto_model');
        
        
        if ( ! is_null($this->session->userdata('order_code')) ) 
        {
            $pedido = $this->Pedido_model->row_by_code($this->session->userdata('order_code'));
            $this->Pedido_model->act_totales($pedido->id);
            
            $data = $this->Pedido_model->basico($pedido->id);
            
            $data['order'] = $pedido;
            $data['products'] = $this->Pedido_model->detalle($pedido->id);
            $data['rol_comprador'] = $this->Pedido_model->rol_comprador($pedido->id);
            $data['extras'] = $this->Pedido_model->extras($pedido->id);
            $data['pedido_id'] = $pedido->id;
            $data['arr_tipos_precio'] = $this->Producto_model->arr_tipos_precio();
            $data['descuentos'] = $this->Pedido_model->descuentos($pedido->id);
            $data['arr_extras_pedidos'] = $this->Item_model->arr_item(6, 'id_interno_num');

            //Identificar region
            $region_id = 267; //Bogotá
            if ( $pedido->region_id > 0 ) $region_id = $pedido->region_id;

            $data['options_region'] = $this->App_model->opciones_lugar("tipo_id = 3 AND pais_id = 51", 'nombre_lugar');
            $data['options_ciudad'] = $this->App_model->opciones_lugar("(tipo_id = 4 AND activo = 1 AND region_id = {$region_id})", 'nombre_lugar');
            
            $data['destino_form'] = "pedidos/compra_a/{$data['row']->cod_pedido}";
            $data['view_a'] = 'pedidos/cotizador/cotizador_v';
        } else {
            $data['view_a'] = 'pedidos/carrito_vacio_v';
        }
        
        //Solicitar vista
            $data['head_title'] = 'Cotizador';
            $this->load->view(TPL_FRONT, $data);
    }

}