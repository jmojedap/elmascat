<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pedidos extends CI_Controller{

// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'pedidos/';
    public $url_controller = URL_API . 'pedidos/';

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
     * Listado de Pedidos, filtrados por búsqueda, JSON
     */
    function get($num_page = 1)
    {
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $data = $this->Pedido_model->get($filters, $num_page);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
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
     * Recibie datos post de pedidos/editar. Guarda los datos de gestión administrativa
     * de un pedido. Si se generan cambios se envía notificación por email al cliente
     */
    function guardar_admin()
    {
        $order_id = $this->input->post('id');
        $arr_row = $this->input->post();
        $data = $this->Pedido_model->guardar_admin($order_id, $arr_row);
        
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function ver($pedido_id)
    {
        redirect("pedidos/info/{$pedido_id}");
    }
    
    
    
    

    /**
     * Función compatibilidad
     * 2020-08-12
     */
    function pol($pedido_id)
    {
        redirect("pedidos/payu/{$pedido_id}");
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

    /**
     * AJAX JSON
     * Actualizar datos de pago de un pedido
     * 2021-11-18
     */
    function update_payment()
    {
        $pedido_id = $this->input->post('id');
        $payed = $this->input->post('payed');
        if ( $payed == '01' ) {
            $data = $this->Pedido_model->update_payment($pedido_id);
        } else {
            $data = $this->Pedido_model->remove_payment($pedido_id);
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// GESTIÓN DE EXTRAS PEDIDO
//-----------------------------------------------------------------------------

    

    function extras_get($pedido_id)
    {
        $extras = $this->Pedido_model->extras($pedido_id);
        $data['list'] = $extras->result();

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function extras_save()
    {
        $data = $this->Pedido_model->extras_save();
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function extras_delete($pedido_id, $pd_id)
    {
        $data = $this->Pedido_model->extras_delete($pedido_id, $pd_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// PROCESO DE PAGO
//---------------------------------------------------------------------------------------------------

    /**
     * Información general del pedido
     * 2021-09-22
     */
    function get_info($order_code)
    {
        $pedido = $this->Pedido_model->row_by_code($order_code);
        $data['order'] = $pedido;
        $data['products'] = $this->Pedido_model->detalle($pedido->id)->result();
        $data['extras'] = $this->Pedido_model->extras($pedido->id)->result();

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }    

    /**
     * Al presionar el botón [IR A PAGAR] de la sección pedidos/verificar, se marca
     * el pedido en estado 2, para evitar que posteriormente edite.
     * 2023-01-25
     */
    function iniciar_pago()
    {
        $order_code = $this->session->userdata('order_code');
        $order = $this->Pedido_model->row_by_code($order_code);

        $data['status'] = 0;
        if ( ! is_null($order) ) {
            $data['status'] = $this->Pedido_model->iniciar_pago($order);
        }

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Revisa que haya disponibilidad de los productos incluidos en un pedido
     * 2020-12-26
     */
    function validar_existencias()
    {
        $pedido_id = $this->session->userdata('pedido_id');
        $data = $this->Pedido_model->validar_existencias($pedido_id);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Cambia el campo pedido.codigo_pedido, para poder realizar un intento de pago nuevamente
     * 2020-03-31
     */
    function reiniciar($cod_pedido)
    {
        $row = $this->Pedido_model->row_by_code($cod_pedido);
        $data['cod_pedido'] = $this->Pedido_model->act_cod_pedido($row->id);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * AJAX JSON
     * Valida y actualiza los datos de contacto y entrega de un pedido, proviene de pedidos/compra_a
     * 2023-05-28
     */
    function guardar_pedido($update_totals = 0)
    {
        $order_code = $this->session->userdata('order_code');
        $order = $this->Pedido_model->row_by_code($order_code);

        $data['status'] = 1;
        $data['qty_affected'] = 0;
        
        if ( ! is_null($order) ) 
        {
            //Construir registro y guardar
            $arr_row = $this->input->post();
            $data['qty_affected'] =  $this->Pedido_model->act_pedido($order->id, $arr_row);

            if ( $data['qty_affected'] > 0 && $update_totals == 1) {
                $this->Pedido_model->act_totales($order->id);
            }

            //Validar usuario comprador
            $this->Pedido_model->set_user_data($order->id);
        }

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));   
    }
    
    /**
     * Abandona un pedido actual quitándolo de las variables de sesión
     * No elimina el pedido ni su detalle
     */
    function cancel()
    {
        $data = $this->Pedido_model->cancel();
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
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

    /**
     * AJAX
     * Asigna un usuario a un pedido
     * 2025-02-12
     */
    function set_user($user_id)
    {
        $data = array('status' => 0, 'message' => 'Usuario no asignado');
        $order_code = $this->session->userdata('order_code');
        $row_order = $this->Pedido_model->row_by_code($order_code);

        if ( ! is_null($row_order) ) {
            $data = $this->Pedido_model->set_user($row_order, $user_id);
        }

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * AJAX
     * Cargar un pedido en variables de sesión
     * 2025-02-10
     */
    function load_in_session($orderCode)
    {
        $data['status'] = 0;
        $data['order'] = $this->Pedido_model->load_in_session($orderCode);
        if ( ! is_null($data['order']) ) {
            $data['status'] = 1;
        };
        
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

//GESTIÓN DE DETALLE DE PEDIDO
//---------------------------------------------------------------------------------------------------

    /**
     * Agrega un producto a una compra, si la compra no está definida crea una y la agrega 
     * a variables de sesión
     * 2021-05-06
     */
    function add_product($product_id, $quantity = 1, $order_code = null)
    { 
        //Resultado por defecto
        $data = array('status' => 0, 'message' => 'Compra no identificada');

        //No hay order_code definida
        if ( is_null($order_code) ) 
        {
            //Crear nueva order, y ponerla en variables de sesión
            $data_order = $this->Pedido_model->crear();
            $this->session->set_userdata('order_code', $data_order['order_code']);
            $order_code = $data_order['order_code'];
        }

        //Registro de compra
        $row_order = $this->Pedido_model->row_by_code($order_code);
        $editable = $this->Pedido_model->editable($row_order);

        //Código de compra existe
        if ( $editable )
        {
            $data = $this->Pedido_model->add_product($product_id, $quantity, $row_order->id);
        } else {
            $this->Pedido_model->unset_session();
            $data = array('status' => 2, 'message' => 'La compra no puede modificarse, ya fue procesada');
        }

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
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
    
    /**
     * Elimina un producto del pedido
     * 2021-09-24
     */
    function remove_product($product_id, $order_code = '')
    {
        //Resultado por defecto
        $data = array('status' => 0, 'message' => 'Compra no identificada');

        $row_order = $this->Pedido_model->row_by_code($order_code);
        $editable = $this->Pedido_model->editable($row_order);

        if ( $editable )
        {
            $data = $this->Pedido_model->remove_product($product_id, $row_order);
        } else {
            $this->Pedido_model->unset_session();
            $data = array('status' => 2, 'message' => 'La compra no puede modificarse, ya fue procesada');
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * AJAX
     * Guardar datos de lugar de entrega de un pedido
     */
    function guardar_lugar()
    {
        $order_code = $this->session->userdata('order_code');
        $order = $this->Pedido_model->row_by_code($order_code);
        
        $data = $this->Pedido_model->guardar_lugar();
        if ( $data['status'] ) {
            $this->Pedido_model->act_totales($order->id);
        }
        
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// EMPACAR COMO REGALO
//-----------------------------------------------------------------------------

    function guardar_datos_regalo()
    {
        $order = $this->Pedido_model->row_by_code($this->session->userdata('order_code'));
        $data = $this->Pedido_model->guardar_datos_regalo($order->id);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
//PAGOS ON LINE
//---------------------------------------------------------------------------------------------------
    
    /**
     * Página de confirmación que ejecuta remotamente PagosOnLine (pol) al 
     * terminar una transacción. Recibe datos de POL vía post, actualiza 
     * datos del pago del pedido
     */
    function confirmacion_pol()
    {
        $confirmacion_pol = $this->Pedido_model->confirmacion_pol();
        $this->output->set_content_type('application/json')->set_output($confirmacion_pol);
    }

    function assign_posts($pedido_id)
    {
        $data = $this->Pedido_model->assign_posts($pedido_id);
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }    
    
//ADMINISTRACIÓN DEL PEDIDO
//---------------------------------------------------------------------------------------------------
    
    /**
     * Actualiza masivamente el estado de los pedidos con respuesta de PayU,
     * que tienen respuesta POL en tabla post, pero cod_estado_pol está vacío
     * 2021-10-23
     */
    function act_estado_pendientes()
    {
        $cant_pedidos = $this->Pedido_model->act_estado_pendientes();
        
        //Resultado
            $data['message'] = "Se actualizaron {$cant_pedidos} pedidos.";
            $data['status'] = 1;
        
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Ejecuta procesos masivos sobre pedidos
     * 2022-08-12
     */
    function run_process($cod_process)
    {
        //Ampliar tiempo de ejecución
        set_time_limit(360);   //  6 minutos
        
        $data['status'] = 0;
        $data['message'] = 'Proceso no ejecutado';
        
        if ( $cod_process == 1 ) {
            $data = $this->Pedido_model->act_estado_pendientes();
        } elseif ( $cod_process == 2 ) {
            $data = $this->Pedido_model->update_payed();
        } elseif ( $cod_process == 3 ) {
            $data = $this->Pedido_model->update_payment_channel_payu();
        } elseif ( $cod_process == 4 ) {
            $data = $this->Pedido_model->update_gender_age();
        }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}