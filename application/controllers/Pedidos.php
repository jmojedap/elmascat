<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pedidos extends CI_Controller{
    
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
            
        //Arrays con valores para contenido en lista
            $data['arr_status'] = $this->Item_model->arr_cod('categoria_id = 7');
            
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
    
    function explorar_ant()
    {
        //Cargando
            $this->load->model('Busqueda_model');
            $this->load->helper('text');
        
        //Grupos de consulta, construyendo array de búsqueda
            $busqueda = $this->Busqueda_model->busqueda_array();
            $busqueda_str = $this->Busqueda_model->busqueda_str();
            $resultados_total = $this->Pedido_model->buscar($busqueda); //Para calcular el total de resultados
            
        //Generar resultados para mostrar
            $data['per_page'] = 15; //Cantidad de registros por página
            $data['offset'] = $this->input->get('per_page');
            $resultados = $this->Pedido_model->buscar($busqueda, $data['per_page'], $data['offset']);
        
        //Variables para vista
            $data['cant_resultados'] = $resultados_total->num_rows();
            $data['busqueda'] = $busqueda;
            $data['busqueda_str'] = $busqueda_str;
            $data['resultados'] = $resultados;
            $data['url_paginacion'] = base_url("pedidos/explorar/?{$busqueda_str}");
            $data['elemento_p'] = 'pedidos';
            $data['elemento_s'] = 'pedido';
            $data['controlador'] = 'pedidos';
        
        //Solicitar vista
            $data['titulo_pagina'] = 'Pedidos';
            $data['subtitulo_pagina'] = $data['cant_resultados'];
            $data['vista_a'] = 'pedidos/explorar/explorar_v';
            $data['vista_menu'] = 'pedidos/explorar/menu_v';
            $this->load->view(PTL_ADMIN, $data);
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
     * 2020-08-28
     */
    function exportar()
    {
        //Cargando
            $this->load->model('Search_model');
        
        
            $filters = $this->Search_model->filters();
            $query = $this->Pedido_model->search($filters); //Para calcular el total de resultados

        //Construyento archivo
        $content = '';

        //Primera fila, columnas
            $fields = $query->list_fields();
            $content .= implode("\t", $fields) . "\n";

        //Registros
        foreach($query->result() as $row)
        {
            foreach($fields as $field) $content .= $row->$field."\t";
            $content .= "\n";
        }

        $file_name = date('Ymd_His'). '_pedidos.csv';

        $content = mb_convert_encoding($content, 'UTF-16LE', 'UTF-8');
        
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="' . $file_name . '"');
        echo $content; exit();
    }
    
    /**
     * Formulario de edición de los datos de gestión administrativa de un pedido
     * Se envía a pedidos/guardar_admin
     */
    function editar($pedido_id)
    {
        $data = $this->Pedido_model->basico($pedido_id);
            
        //Variables
            $data['destino_form'] = "pedidos/guardar_admin/{$pedido_id}";
            $data['opciones_estado'] = $this->Item_model->opciones('categoria_id = 7');

        //Variables generales
            $data['view_a'] = 'pedidos/editar_v';

        $this->load->view(TPL_ADMIN, $data);
    }
    
    /**
     * Recibie datos post de pedidos/editar. Guarda los datos de gestión administrativa
     * de un pedido. Si se generan cambios se envía notificación por email al cliente
     */
    function guardar_admin($pedido_id)
    {
        $data = $this->Pedido_model->guardar_admin($pedido_id);
        
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * Vista previa del mensaje de e-mail que se envía a un cliente cuando los
     * datos de su pedido son modificados.
     * 
     * @param type $pedido_id
     */
    function test_email_act($pedido_id)
    {
        $data['row_pedido'] = $this->Pcrn->registro_id('pedido', $pedido_id);
        $data['style'] = $this->App_model->email_style();
        $this->load->view('usuarios/emails/act_pedido_v', $data);
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
            
        //Tipos de precio, promociones
            $this->load->model('Producto_model');
            $data['arr_tipos_precio'] = $this->Producto_model->arr_tipos_precio();
        
        //Solicitar vista
            $data['nav_2'] = 'pedidos/menu_v';
            $data['view_a'] = 'pedidos/info_v';
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
        
        //Solicitar vista
            $data['head_subtitle'] = $this->Pcrn->moneda($data['row']->valor_total);
            $data['view_a'] = "pedidos/reporte_{$tipo_reporte}_v";
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
            $confirmations = $this->db->query("SELECT * FROM meta WHERE tabla_id = 3000 AND elemento_id = {$pedido_id} AND dato_id = 3005 ORDER BY id DESC");

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
            $this->load->view(TPL_ADMIN, $data);
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
        $this->load->view(TPL_ADMIN, $data);
    }

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

//GESTIÓN DE COMPRAS
//---------------------------------------------------------------------------------------------------
    
    /**
     * Vista del listado de productos que están en el carrito de compras, listos
     * para empezar el proceso de pago.
     */
    function carrito()
    {
        $this->load->model('Producto_model');
        
        
        if ( ! is_null($this->session->userdata('pedido_id')) ) 
        {
            $pedido_id = $this->session->userdata('pedido_id');
            $this->Pedido_model->act_totales($pedido_id);
            
            $data = $this->Pedido_model->basico($pedido_id);
            
            $data['detalle'] = $this->Pedido_model->detalle($pedido_id);
            $data['rol_comprador'] = $this->Pedido_model->rol_comprador($pedido_id);
            $data['extras'] = $this->Pedido_model->extras($pedido_id);
            $data['pedido_id'] = $this->session->userdata('pedido_id');
            $data['arr_tipos_precio'] = $this->Producto_model->arr_tipos_precio();
            $data['descuentos'] = $this->Pedido_model->descuentos($pedido_id);
            
            $data['destino_form'] = "pedidos/compra_a/{$data['row']->cod_pedido}";
            $data['view_a'] = 'pedidos/compra/compra_v';
            $data['view_b'] = 'pedidos/compra/carrito_v';
        } else {
            $data['view_a'] = 'pedidos/carrito_vacio_v';
        }
        
        //Solicitar vista
            $data['head_title'] = 'Carrito de compras';
            $this->load->view(TPL_FRONT, $data);
    }

    function usuario()
    {
        $pedido_id = $this->session->userdata('pedido_id');
        $data = $this->Pedido_model->basico($pedido_id);

        if ( $data['row']->usuario_id > 0 )
        {
            redirect('pedidos/compra_a');
        } else {
            //Variables
                $data['recaptcha_sitekey'] = K_RCSK;    //config/constants.php

            //Solicitar vista
                $data['head_title'] = 'Datos de entrega';
                $data['view_a'] = 'pedidos/compra/compra_v';
                $data['view_b'] = 'pedidos/compra/usuario_v';
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
        
        $pedido_id = $this->session->userdata('pedido_id');
        
        $data = $this->Pedido_model->basico($pedido_id);
        
        $data['detalle'] = $this->Pedido_model->detalle($pedido_id);

        //Opciones formulario
            $data['options_pais'] = $this->App_model->opciones_lugar("tipo_id = 2 AND activo = 1", 'nombre_lugar', 'País');
            $options_ciudad_grande = $this->App_model->opciones_lugar_poblacion("tipo_id = 4 AND activo = 1 and poblacion > 400000", 'cr', 'Ciudad');
            $options_ciudad_pre = $this->App_model->opciones_lugar("(tipo_id = 4 AND activo = 1) OR (id = 1)", 'cr');
            $data['options_ciudad'] = array_merge($options_ciudad_grande, $options_ciudad_pre);
            $data['options_tipo_documento'] = $this->Item_model->opciones('categoria_id = 53 AND filtro LIKE "%-cliente-%"');
            $data['options_year'] = $this->Evento_model->options_year(1920,2005);
            $data['options_month'] = $this->Evento_model->options_month();
            $data['options_day'] = $this->Evento_model->options_day();
            $data['row_usuario'] = $this->Db_model->row_id('usuario', $data['row']->usuario_id);
        
        //Extras
            $arr_extras['gastos_envio'] = $this->Pedido_model->valor_extras($pedido_id, 'producto_id IN (1,4)');
            $data['arr_extras'] = $arr_extras;

        //Si tiene peso o no, cambian los datos solicitados
            $data['view_b'] = 'pedidos/compra/compra_a_v';
            if ( $data['row']->peso_total == 0 ) {
                $data['view_b'] = 'pedidos/compra/compra_a_sin_peso_v';
            }
        
        //Solicitar vista
            $data['head_title'] = 'Datos de entrega';
            $data['view_a'] = 'pedidos/compra/compra_v';
            $data['section_id'] = 'cart_items';
            $this->load->view(TPL_FRONT, $data);
    }
    
    /**
     * Resumen del pedido, productos y datos de entrega, para revisión antes de ir a plataforma de pagos
     * 2020-04-16
     */
    function compra_b()
    {
        $this->load->model('Producto_model');
        $pedido_id = $this->session->userdata('pedido_id');
        
        $data = $this->Pedido_model->basico($pedido_id);
        $data['detalle'] = $this->Pedido_model->detalle($pedido_id);
        $data['row_ciudad'] = $this->Pcrn->registro_id('lugar', $data['row']->ciudad_id);
        
        //Extras
            $arr_extras['gastos_envio'] = $this->Pedido_model->valor_extras($pedido_id, 'producto_id IN (1,4)');
            $data['arr_extras'] = $arr_extras;
        
        //Datos para el formulario que se envía a PagosOnLine
            $data['form_data'] = $this->Pedido_model->form_data_pol($pedido_id);
            $data['destino_form'] = 'https://gateway.pagosonline.net/apps/gateway/index.html';  //Donde se envían los datos para el pago
            
            if ( $this->input->get('prueba') == 1 )
            {
                $data['form_data'] = $this->Pedido_model->form_data_pol($pedido_id, 1);
                $data['destino_form'] = 'https://gateway.pagosonline.net/apps/gateway/index.html';  //Página para transacciones de prueba
            }
        
        //Solicitar vista
            $data['head_title'] = 'Districatólicas';
            $data['view_a'] = 'pedidos/compra/compra_v';
            $data['view_b'] = 'pedidos/compra/compra_b_v';
            $data['section_id'] = 'cart_items';
            $this->load->view(TPL_FRONT, $data);
    }
    
    /**
     * Igual a la parte B de la compra (compra_b), pero convierte los valores de
     * pago en dólares de Estados Unidos (USD)
     */
    function compra_b_usd()
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
            $data['destino_form'] = 'https://gateway.pagosonline.net/apps/gateway/index.html';  //Donde se envían los datos para el pago
        
        //Solicitar vista
            $data['head_title'] = 'Districatólicas';
            $data['view_a'] = 'pedidos/compra/compra_v';
            $data['view_b'] = 'pedidos/compra/compra_b_usd_v';
            $data['section_id'] = 'cart_items';
            $this->load->view(TPL_FRONT, $data);
    }

    /**
     * Link de pago
     * 2020-03-31
     */
    function link_pago($cod_pedido)
    {
        $row = $this->Pedido_model->row_cod_pedido($cod_pedido);
        $this->load->model('Producto_model');
        $pedido_id = $row->id;
        
        $data = $this->Pedido_model->basico($pedido_id);
        $data['detalle'] = $this->Pedido_model->detalle($pedido_id);
        $data['row_ciudad'] = $this->Pcrn->registro_id('lugar', $data['row']->ciudad_id);
        
        //Extras
            $arr_extras['gastos_envio'] = $this->Pedido_model->valor_extras($pedido_id, 'producto_id IN (1,4)');
            $data['arr_extras'] = $arr_extras;
        
        //Datos para el formulario que se envía a PagosOnLine
            $data['form_data'] = $this->Pedido_model->form_data_pol($pedido_id);
            $data['destino_form'] = 'https://gateway.pagosonline.net/apps/gateway/index.html';  //Donde se envían los datos para el pago
            
            if ( $this->input->get('prueba') == 1 )
            {
                $data['form_data'] = $this->Pedido_model->form_data_pol($pedido_id, 1);
                $data['destino_form'] = 'https://gateway.pagosonline.net/apps/gateway/index.html';  //Página para transacciones de prueba
            }
        
        //Solicitar vista
            $data['head_title'] = 'Pagar pedido: ' . $row->cod_pedido;
            $data['view_a'] = 'pedidos/compra/compra_v';
            $data['view_b'] = 'pedidos/compra/link_pago_v';
            $this->load->view(TPL_FRONT, $data);
    
    }

    /**
     * Cambia el campo pedido.codigo_pedido, para poder realizar un intento de pago nuevamente
     * 2020-03-31
     */
    function reiniciar($cod_pedido)
    {
        $row = $this->Pedido_model->row_cod_pedido($cod_pedido);
        $data['qty_affected'] = $this->Pedido_model->act_cod_pedido($row->id);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * AJAX JSON
     * Valida y actualiza los datos de contacto y entrega de un pedido, proviene de pedidos/compra_a
     * 2020-04-07
     */
    function guardar_pedido()
    {
        $pedido_id = $this->session->userdata('pedido_id');
        $row_pedido = $this->Pcrn->registro_id('pedido', $pedido_id);

        $data['qty_affected'] = 0;
        
        if ( ! is_null($row_pedido) ) 
        {
            //Construir registro y guardar
            $arr_row = $this->input->post();
			unset($arr_row['fecha_nacimiento']);      //No cargar datos de registro de usuario
            unset($arr_row['year']);      //No cargar datos de registro de usuario
            unset($arr_row['month']);     //No cargar datos de registro de usuario
            unset($arr_row['day']);       //No cargar datos de registro de usuario
            unset($arr_row['sexo']);                //No cargar datos de registro de usuario
            $data['qty_affected'] =  $this->Pedido_model->act_pedido($pedido_id, $arr_row);

            //Validar usuario comprador
            $this->Pedido_model->set_user_data($pedido_id);
        }

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));   
    }
    
    /**
     * Abandona un pedido actual quitándolo de las variables de sesión
     * No elimina el pedido ni su detalle
     */
    function abandonar()
    {
        $this->Pedido_model->abandonar();
        redirect('productos/catalogo');
    }
    
    /**
     * Retoma un pedido, cargándolo a las variables de sesión
     * Como medida de seguridad se toma el código de pedido.
     */
    function retomar($cod_pedido)
    {
        $this->Pedido_model->retomar($cod_pedido);
        redirect('pedidos/carrito');
    }
    
    function aplicar_desc_distribuidor($pedido_id)
    {
        $row = $this->Pcrn->registro_id('pedido', $pedido_id);
        $this->Pedido_model->act_desc_distribuidor($pedido_id);
        $this->Pedido_model->act_totales($pedido_id);
        redirect("pedidos/compra_a/{$row->cod_pedido}");
    }

    function set_user($user_id)
    {
        $data = $this->Pedido_model->set_user($user_id);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
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
        $this->App_model->view(TPL_ADMIN, $data);
    }
    
    /**
     * Vista previa del mensaje de correo electrónico, informando los cambios de 
     * los datos de un pedido, cuando se modifican durante la administración, 
     * preparación y envío.
     * 
     * @param type $pedido_id
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
        $this->Pedido_model->abandonar(); //Se abandona el pedido de las variables de sesión
        
        $arr_respuesta_pol = $this->Pedido_model->arr_respuesta_pol();
        $row_pedido = $this->Pedido_model->row_cod_pedido($arr_respuesta_pol['ref_venta']);
        
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
        $this->Pedido_model->abandonar(); //Se abandona el pedido de las variables de sesión
        
        $arr_respuesta_pol = $this->Pedido_model->arr_respuesta_pol();
        $row_pedido = $this->Pedido_model->row_cod_pedido($arr_respuesta_pol['ref_venta']);
        
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
    
    /**
     * Página de confirmación que ejecuta remotamente PagosOnLine (pol) al 
     * terminar una transacción. Recibe datos de POL vía post, actualiza 
     * datos del pago del pedido
     */
    function confirmacion_pol()
    {
        //$this->Pedido_model->marca_confirmacion_pol();
        $confirmacion_pol = $this->Pedido_model->confirmacion_pol();
        $this->output->set_content_type('application/json')->set_output($confirmacion_pol);
    }

    function assign_posts($pedido_id)
    {
        $data = $this->Pedido_model->assign_posts($pedido_id);
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
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
            $row_pedido = $this->Pedido_model->row_cod_pedido($cod_pedido);
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
    
    
//GESTIÓN DE DETALLE DE PEDIDO
//---------------------------------------------------------------------------------------------------

    /**
     * AJAX
     * Crea o edita un registro en la tabla pedido_detalle, corresponde listado de productos de un pedido
     * 2020-08-12
     */
    function guardar_detalle()
    {
        //Valores iniciales
            $data = array('status' => 0, 'message' => 'Producto no agregado');
            $pd_id = 0;
            $pedido_id = $this->session->userdata('pedido_id');


        //Si no existe pedido se crea
            if ( is_null($pedido_id) ) { $pedido_id = $this->Pedido_model->crear(); }
            $row = $this->Db_model->row_id('pedido', $pedido_id);

        if ( ! is_null($row) )
        {
            //Construir registro
                $registro['producto_id'] = $this->input->post('producto_id');
                $registro['cantidad'] = $this->input->post('cantidad');
                $registro['tipo_id'] = 1;   //Tipo de detalle, producto
                
            //Se agrega producto a pedido solo si está en estado iniciado (1)
                if ( $row->estado_pedido == 1 )
                {
                    $pd_id = $this->Pedido_model->guardar_detalle($registro);
                    $data = array('status' => 1, 'message' => 'Producto agregado en: ' . $pd_id);
                } else {
                    $data['message'] = 'El pedido está cerrado, no puede modificarse';
                }
        }

        $data['pedido_id'] = $pedido_id;
        $data['pd_id'] = $pd_id;

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
     * 2020-08-12
     */
    function eliminar_detalle($elemento_id)
    {
        $pedido_id = $this->session->userdata('pedido_id');
        
        $row = $this->Db_model->row_id('pedido', $pedido_id);

        //Se modifica solo si está en estado iniciado (1)
        if ( $row->estado_pedido == 1 )
        {
            $this->Pedido_model->eliminar_detalle($elemento_id);
            $this->Pedido_model->act_totales($pedido_id);
        }
        
        redirect("pedidos/carrito");
    }
    
    /**
     * AJAX
     * Guardar datos de lugar de entrega de un pedido
     */
    function guardar_lugar()
    {
        $pedido_id = $this->session->userdata('pedido_id');
        
        $this->Pedido_model->guardar_lugar();
        $this->Pedido_model->act_totales($pedido_id);

        $data['status'] = 1;
        
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
//ADMINISTRACIÓN DEL PEDIDO
//---------------------------------------------------------------------------------------------------
    
    function act_estado_pendientes()
    {
        $cant_pedidos = $this->Pedido_model->act_estado_pendientes();
        
        //Resultado
            $data['mensaje'] = "Se actualizaron {$cant_pedidos} pedidos.";
            $data['clase_alert'] = 'success';
            $data['titulo_pagina'] = 'Procesos';
            $data['vista_a'] = 'sistema/develop/procesos_v';
        
        $this->load->view(PTL_ADMIN, $data);
    }

//ESTADÍSTICAS
//---------------------------------------------------------------------------------------------------
    
    function panel() 
    {
        $this->load->model('Estadistica_model');
        
        
        //Variables específicas
            $data['nuevas_compras'] = $this->Pcrn->num_registros('pedido', 'estado_pedido = 3');
            $data['cant_usuarios'] = $this->Pcrn->num_registros('usuario', 'rol_id >= 20');
            
        //Variables generales
            $data['titulo_pagina'] = NOMBRE_APP;
            $data['subtitulo_pagina'] = 'Resumen';
            $data['vista_a'] = 'pedidos/panel/panel_v';

        $this->load->view(PTL_ADMIN, $data);
    }

    function resumen_mes()
    {
        $this->load->model('Estadistica_model');
        for ($year=2018; $year < 2021; $year++)
        { 
            $resumen_mes[$year] = $this->Estadistica_model->ventas_mes($year);
        }
        
        //Cargando variables
            $data['resumen_mes'] = $resumen_mes;
        
        //Solicitar vista
            $data['titulo_pagina'] = 'Pedidos';
            $data['subtitulo_pagina'] = 'Resumen por mes';
            $data['vista_a'] = 'estadisticas/pedidos/resumen_mes_v';
            $data['vista_menu'] = 'estadisticas/pedidos/menu_v';
            $this->load->view(PTL_ADMIN, $data);
    }

    function resumen_dia($qty_days = 7)
    {
        //Cargando variables
            $this->load->model('Estadistica_model');
            $data['resumen_dia'] = $this->Estadistica_model->ventas_dia($qty_days);
            $data['qty_days'] = $qty_days;
        
        //Solicitar vista
            $data['titulo_pagina'] = 'Pedidos';
            $data['subtitulo_pagina'] = 'Resumen por día';
            $data['vista_a'] = 'estadisticas/pedidos/resumen_dia_v';
            $data['vista_menu'] = 'estadisticas/pedidos/menu_v';
            $this->load->view(PTL_ADMIN, $data);
    }

    function ventas_ciudad()
    {
        $this->load->model('Estadistica_model');
        $ventas_ciudad = $this->Estadistica_model->ventas_ciudad(1);
        //$ventas_ciudad = array();
        
        //Cargando variables
            $data['ventas_ciudad'] = $ventas_ciudad;
        
        //Solicitar vista
            $data['titulo_pagina'] = 'Pedidos';
            $data['subtitulo_pagina'] = 'Ventas por ciudad';
            $data['vista_a'] = 'estadisticas/pedidos/ventas_ciudad_v';
            $data['vista_menu'] = 'estadisticas/pedidos/menu_v';
            $this->load->view(PTL_ADMIN, $data);
    }

    function ventas_departamento()
    {
        $this->load->model('Estadistica_model');
        $ventas_departamento = $this->Estadistica_model->ventas_departamento(1);
        //$ventas_ciudad = array();
        
        //Cargando variables
            $data['ventas_departamento'] = $ventas_departamento;
        
        //Solicitar vista
            $data['titulo_pagina'] = 'Pedidos';
            $data['subtitulo_pagina'] = 'Ventas por Departamento';
            $data['vista_a'] = 'estadisticas/pedidos/ventas_departamento_v';
            $data['vista_menu'] = 'estadisticas/pedidos/menu_v';
            $this->load->view(PTL_ADMIN, $data);
    }

    function meta_anual()
    {
        $this->load->model('Estadistica_model');
        $resumen_anio = $this->Estadistica_model->pedidos_resumen_anio(1);
        $this->load->library('pacarina');
        
        //Metas
            $json_metas = $this->App_model->valor_opcion(300001);
        
        //Cargando variables
            $data['resumen_anio'] = $resumen_anio;
            $data['arr_metas'] = json_decode($json_metas, TRUE);
        
        //Solicitar vista
            $data['titulo_pagina'] = 'Pedidos';
            $data['subtitulo_pagina'] = 'Meta anual';
            $data['vista_a'] = 'estadisticas/pedidos/meta_anual_v';
            $data['vista_menu'] = 'estadisticas/pedidos/menu_v';
            $this->load->view(PTL_ADMIN, $data);
    }

    function productos_top()
    {
        //Cargue
            $this->load->model('Estadistica_model');
        
        //Cargando variables
            $data['productos'] = $this->Estadistica_model->productos_top();
        
        //Solicitar vista
            $data['titulo_pagina'] = 'Pedidos';
            $data['subtitulo_pagina'] = 'Productos más vendidos';
            $data['vista_a'] = 'estadisticas/pedidos/productos_top_v';
            $data['vista_menu'] = 'estadisticas/pedidos/menu_v';
            $this->load->view(PTL_ADMIN, $data);
    }

    function efectividad()
    {
        $this->load->model('Estadistica_model');
        
        //Cargando variables
            $data['resumen_mes'] = $this->Estadistica_model->pedidos_resumen_mes(1);
            $data['resumen_mes_total'] = $this->Estadistica_model->pedidos_resumen_mes();
        
        //Solicitar vista
            $data['titulo_pagina'] = 'Pedidos';
            $data['subtitulo_pagina'] = 'Efectividad';
            $data['vista_a'] = 'estadisticas/pedidos/efectividad_v';
            $data['vista_menu'] = 'estadisticas/pedidos/menu_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
// INFO
//-----------------------------------------------------------------------------
    
    function soy_distribuidor()
    {
        //Variables específicas
            $this->db->order_by('entero_1', 'desc');
            $this->db->where('categoria_id', 5);
            $data['fabricantes'] = $this->db->get('item');

        //Variables generales
            $data['head_title'] = 'Distribuidores';
            $data['view_a'] = 'pedidos/info/soy_distribuidor_v';

        $this->load->view(TPL_FRONT, $data);
    }

    /**
     * Le establece los datos de dirección de entrega a un pedido, correspondiente
     * a los datos de dirección de un usuario en la tabla post.
     * 
     * @param type $cod_pedido
     * @param type $post_id
     */
    function set_direccion($cod_pedido, $post_id)
    {
        $row = $this->Pedido_model->row_cod_pedido($cod_pedido);
        $this->Pedido_model->set_direccion($row->id, $post_id);
        $this->Pedido_model->act_totales($row->id); //Actualiza totales de entrega, por si la ciudad es diferente
        
        redirect("pedidos/compra_a/{$cod_pedido}");
    }
}