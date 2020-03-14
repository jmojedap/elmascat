<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pedidos extends CI_Controller{
    
    function __construct() {
        parent::__construct();

        $this->load->model('Pedido_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }

// Exploración de pedidos
//---------------------------------------------------------------------------------------------------
    
    function explorar()
    {
        //Cargando
            $this->load->model('Busqueda_model');
            $this->load->helper('text');
        
        //Grupos de consulta, construyendo array de búsqueda
            $busqueda = $this->Busqueda_model->busqueda_array();
            $busqueda_str = $this->Busqueda_model->busqueda_str();
            $resultados_total = $this->Pedido_model->buscar($busqueda); //Para calcular el total de resultados
            
        //Generar resultados para mostrar
            $data['per_page'] = 20; //Cantidad de registros por página
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
     * Exporta el resultado de la búsqueda a un archivo de Excel
     */
    function exportar()
    {
        
        //Cargando
            $this->load->model('Busqueda_model');
            $this->load->model('Pcrn_excel');
        
        //Datos de consulta, construyendo array de búsqueda
            $busqueda = $this->Busqueda_model->busqueda_array();
            $resultados_total = $this->Pedido_model->buscar($busqueda); //Para calcular el total de resultados
        
        //Preparar datos
            $datos['nombre_hoja'] = 'Pedidos';
            $datos['query'] = $resultados_total;
            
        //Preparar archivo
            $objWriter = $this->Pcrn_excel->archivo_query($datos);
        
        $data['objWriter'] = $objWriter;
        $data['nombre_archivo'] = date('Ymd_His'). '_pedidos.xls'; //save our workbook as this file name
        
        $this->load->view('app/descargar_phpexcel_v', $data);
    }
    
    /**
     * Formulario de edición de los datos de gestión administrativa de un pedido
     * Se envía a pedidos/guardar_admin
     */
    function editar()
    {
        //Variables específicas
            $pedido_id = $this->uri->segment(4);
            $data = $this->Pedido_model->basico($pedido_id);
            
        //Variables
            $data['destino_form'] = "pedidos/guardar_admin/{$pedido_id}";

        //Variables generales
            $data['subtitulo_pagina'] = 'Editar';
            $data['vista_b'] = 'pedidos/editar_v';
            $data['vista_menu'] = 'instituciones/explorar_menu_v';

        $this->load->view(PTL_ADMIN, $data);
    }
    
    /**
     * Recibie datos post de pedidos/editar. Guarda los datos de gestión administrativa
     * de un pedido. Si se generan cambios se envía notificación por email al cliente
     * 
     * @param type $pedido_id
     */
    function guardar_admin($pedido_id)
    {
        $this->output->enable_profiler(TRUE);
        $resultado = $this->Pedido_model->guardar_admin($pedido_id);
        
        //Si los datos del pedido se modificaron se envía email al cliente, 
        if ( $resultado['modificado'] )
        {
            $this->Pedido_model->email_actualizacion($pedido_id);    
        }
        
        $this->session->set_flashdata('resultado', $resultado);
        redirect("pedidos/editar/edit/{$pedido_id}");
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
            $data['subtitulo_pagina'] = 'Detalles';
            $data['vista_a'] = 'pedidos/pedido_v';
            $data['vista_b'] = 'pedidos/ver_v';
            $this->load->view(PTL_ADMIN, $data);
    }
    
    function reporte($pedido_id)
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
            $data['subtitulo_pagina'] = $this->Pcrn->moneda($data['row']->valor_total);
            $data['vista_a'] = 'pedidos/reporte_v';
            $this->load->view('p_print/plantilla_v', $data);
    }
    
    
    /**
     * Información del pedido asociada a Pagos On Line (pol)
     * Se muestran datos si se recibieron desde la página de confirmación
     * 
     * @param type $pedido_id
     */
    function pol($pedido_id)
    {
        $data = $this->Pedido_model->basico($pedido_id);    
        
        //Datos de confirmación (3005) en tabla meta
            $condicion = "tabla_id = 3000 AND elemento_id = {$pedido_id} AND dato_id = 3005 ORDER BY id DESC";
            $row_meta = $this->Pcrn->registro('meta', $condicion);
            $json_rta_pol = $this->Pcrn->campo('meta', $condicion, 'valor');
            
        //Array respuesta POL
            $arr_respuesta_pol = array();
            $firma_pol_confirmacion = NULL;
            
            if ( strlen($json_rta_pol) )
            {
                $arr_respuesta_pol = json_decode($row_meta->valor, TRUE);
                $firma_pol_confirmacion = $this->Pedido_model->firma_pol_confirmacion($pedido_id, $arr_respuesta_pol['estado_pol']);
            }
            
        //Variables
            $data['pedido_id'] = $pedido_id;
            $data['row_meta'] = $row_meta;
            $data['arr_respuesta_pol'] = $arr_respuesta_pol;
            $data['firma_pol_confirmacion'] = $firma_pol_confirmacion;
        
        //Solicitar vista
            $data['subtitulo_pagina'] = 'Pedido';
            $data['vista_a'] = 'pedidos/pedido_v';
            $data['vista_b'] = 'pedidos/pol_v';
            $this->load->view(PTL_ADMIN, $data);
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

        $data['subtitulo_pagina'] = 'Extras';
        $data['vista_a'] = 'pedidos/pedido_v';
        $data['vista_b'] = 'pedidos/extras/extras_v';
        $this->load->view(PTL_ADMIN, $data);
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
        $resumen_mes = $this->Estadistica_model->pedidos_resumen_mes(1);
        
        //Cargando variables
            $data['resumen_mes'] = $resumen_mes;
        
        //Solicitar vista
            $data['titulo_pagina'] = 'Pedidos';
            $data['subtitulo_pagina'] = 'Resumen por mes';
            $data['vista_a'] = 'estadisticas/pedidos/resumen_mes_v';
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
            $data['vista_a'] = 'pedidos/compra/compra_v';
            $data['vista_b'] = 'pedidos/compra/carrito_v';
        } else {
            $data['vista_a'] = 'pedidos/carrito_vacio_v';
        }
        
        //Solicitar vista
            $data['titulo_pagina'] = 'Carrito de compras';
            $this->load->view(PTL_FRONT, $data);
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
        
        $pedido_id = $this->session->userdata('pedido_id');
        
        $data = $this->Pedido_model->basico($pedido_id);
        
        $data['detalle'] = $this->Pedido_model->detalle($pedido_id);
        $data['destino_form'] = "pedidos/guardar_pedido";
        
        //Extras
            $arr_extras['gastos_envio'] = $this->Pedido_model->valor_extras($pedido_id, 'producto_id IN (1,4)');
            $arr_extras['dto_distribuidor'] = $this->Pedido_model->valor_extras($pedido_id, 'producto_id = 3');
            $data['arr_extras'] = $arr_extras;
        
        //Solicitar vista
            $data['titulo_pagina'] = 'Districatólicas';
            $data['vista_a'] = 'pedidos/compra/compra_v';
            $data['vista_b'] = 'pedidos/compra/compra_a_v';
            $data['section_id'] = 'cart_items';
            $this->load->view(PTL_FRONT, $data);
    }
    
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
            $data['titulo_pagina'] = 'Districatólicas';
            $data['vista_a'] = 'pedidos/compra/compra_v';
            $data['vista_b'] = 'pedidos/compra/compra_b_v';
            $data['section_id'] = 'cart_items';
            $this->load->view(PTL_FRONT, $data);
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
            $data['titulo_pagina'] = 'Districatólicas';
            $data['vista_a'] = 'pedidos/compra/compra_v';
            $data['vista_b'] = 'pedidos/compra/compra_b_usd_v';
            $data['section_id'] = 'cart_items';
            $this->load->view(PTL_FRONT, $data);
    }
    
    /**
     * POST REDIRECT
     * Valida y actualiza los datos de contacto y entrega de un pedido, proviene
     * de pedidos/compra_a
     * 
     */
    function guardar_pedido()
    {
        $valido = $this->Pedido_model->validar();
        
        $pedido_id = $this->session->userdata('pedido_id');
        $row_pedido = $this->Pcrn->registro_id('pedido', $pedido_id);
        
        if ( $valido ) 
        {
            //Construir registro
                $registro['nombre'] = $this->input->post('nombre');
                $registro['apellidos'] = $this->input->post('apellidos');
                $registro['no_documento'] = $this->input->post('no_documento');
                $registro['email'] = $this->input->post('email');
                $registro['direccion'] = $this->input->post('direccion');
                $registro['direccion_detalle'] = $this->input->post('direccion_detalle');
                $registro['telefono'] = $this->input->post('telefono');
                $registro['celular'] = $this->input->post('celular');
                $registro['notas'] = $this->input->post('notas');    
            
            $this->Pedido_model->act_pedido($pedido_id, $registro);
            
            redirect("pedidos/compra_b/{$row_pedido->cod_pedido}");
        } else {
            $this->output->enable_profiler(TRUE);
            //$this->compra_a($row_pedido->cod_pedido);
        }
        
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
    
    function aplicar_desc_distribuidor($pedido_id)
    {
        $row = $this->Pcrn->registro_id('pedido', $pedido_id);
        $this->Pedido_model->act_desc_distribuidor($pedido_id);
        $this->Pedido_model->act_totales($pedido_id);
        redirect("pedidos/compra_a/{$row->cod_pedido}");
    }
    
//PAGOS ON LINE
//---------------------------------------------------------------------------------------------------
    
    /**
     * Para pruebas, Pagos on line
     */
    function test_pol()
    {
        //Solicitar vista
            $data['titulo_pagina'] = 'Test Respuesta';
            $data['subtitulo_pagina'] = 'Test Respuesta';
            $data['vista_a'] = 'pedidos/respuesta_test_v';
            $this->load->view(PTL_FRONT, $data);
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
            $data['titulo_pagina'] = 'Districatólicas';
            $data['vista_a'] = 'pedidos/compra/compra_v';
            $data['vista_b'] = 'pedidos/compra/respuesta_v';
            $this->load->view(PTL_FRONT, $data);
    }
    
    /**
     * Página respuesta para mostrar datos de PagosOnLine al usuario
     */
    function respuesta_test()
    {
        //$this->output->enable_profiler(TRUE);
        //$this->Pedido_model->abandonar(); //Se abandona el pedido de las variables de sesión
        
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
            $data['titulo_pagina'] = 'Districatólicas';
            $data['vista_a'] = 'pedidos/compra/compra_v';
            $data['vista_b'] = 'pedidos/compra/respuesta_v';
            $this->load->view(PTL_FRONT, $data);
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
        
        $this->output
            ->set_content_type('application/json')
            ->set_output($confirmacion_pol);
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
            $data['titulo_pagina'] = 'Estado Pedido';
            $data['vista_a'] = 'pedidos/estado_v';
            $this->load->view(PTL_FRONT, $data);
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
     * Crea o edita un registro en la tabla pedido_detalle, corresponde listado 
     * de productos de un pedido
     * 
     * @param type $elemento_id
     */
    function guardar_detalle()
    {
        //Si no existe pedido se crea
            if ( is_null($this->session->userdata('pedido_id')) ) { $this->Pedido_model->crear(); }
        
        //Construir registro
            $registro['producto_id'] = $this->input->post('producto_id');
            $registro['cantidad'] = $this->input->post('cantidad');
            $registro['tipo_id'] = 1;   //Tipo de detalle, producto
            
        //Guardar registro
            $pd_id = $this->Pedido_model->guardar_detalle($registro);

        //Salida
            $this->output->set_content_type('application/json')->set_output($pd_id);
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
    
    function eliminar_detalle($elemento_id)
    {
        $pedido_id = $this->session->userdata('pedido_id');
        
        $this->Pedido_model->eliminar_detalle($elemento_id);
        $this->Pedido_model->act_totales($pedido_id);
        
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
        echo 'OK';
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
    
// INFO
//-----------------------------------------------------------------------------
    
    function soy_distribuidor()
    {
        //Variables específicas
            $this->db->order_by('entero_1', 'desc');
            $this->db->where('categoria_id', 5);
            $data['fabricantes'] = $this->db->get('item');

        //Variables generales
            $data['titulo_pagina'] = 'Distribuidores';
            $data['subtitulo_pagina'] = '';
            $data['vista_a'] = 'comunes/gc_v';
            $data['vista_menu'] = 'pedidos/info/soy_distribuidor_v';

        $this->load->view(PTL_FRONT, $data);
    }
    
}