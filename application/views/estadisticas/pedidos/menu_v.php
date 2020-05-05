<?php
        $seccion = $this->uri->segment(2);
        //if ( $this->uri->segment(2) == 'otra_seccion' ) { $seccion = 'seccion'; }

        $clases[$seccion] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['resumen_dia'] = array(
            'icono' => '',
            'texto' => 'Día',
            'link' => "pedidos/resumen_dia/",
            'atributos' => 'title="Resumen de ventas por día"'
        );

        $arr_menus['resumen_mes'] = array(
            'icono' => '<i class="fa fa-calendar-o"></i>',
            'texto' => 'Mes',
            'link' => "pedidos/resumen_mes/",
            'atributos' => 'title="Resumen de ventas por mes"'
        );
        
        $arr_menus['ventas_departamento'] = array(
            'icono' => '<i class="fa fa-map-marker"></i>',
            'texto' => 'Departamento',
            'link' => "pedidos/ventas_departamento/",
            'atributos' => 'title="Resumen de ventas por departamento"'
        );
        
        $arr_menus['meta_anual'] = array(
            'icono' => '<i class="fa fa-arrow-right"></i>',
            'texto' => 'Meta anual',
            'link' => 'pedidos/meta_anual/' . date('Y'),
            'atributos' => 'title="Seguimiento a la meta anual de ventas"'
        );
        
        $arr_menus['productos_top'] = array(
            'icono' => '<i class="fa fa-star-o"></i>',
            'texto' => 'Productos top',
            'link' => "pedidos/productos_top/",
            'atributos' => 'title="Los productos más vendidos"'
        );
        
        $arr_menus['efectividad'] = array(
            'icono' => '<i class="fa fa-check"></i>',
            'texto' => 'Efectividad',
            'link' => "pedidos/efectividad/",
            'atributos' => 'title="Porcentaje de pedidos con pago"'
        );

        $arr_menus['ventas_categoria'] = array(
            'icono' => '<i class="fa fa-bars"></i>',
            'texto' => 'Categorías',
            'link' => "estadisticas/ventas_categoria/",
            'atributos' => 'title="Ventas por categoría de producto"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('resumen_dia', 'resumen_mes', 'ventas_departamento', 'meta_anual', 'productos_top', 'efectividad', 'ventas_categoria');
        $elementos_rol[1] = array('resumen_dia', 'resumen_mes', 'ventas_departamento', 'meta_anual', 'productos_top', 'efectividad', 'ventas_categoria');
        $elementos_rol[2] = array('resumen_dia', 'resumen_mes', 'meta_anual', 'productos_top', 'efectividad');
        
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: comunes/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
    
    //Cargue vista
        $this->load->view('comunes/menu_v', $data_menu);