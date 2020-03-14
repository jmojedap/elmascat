<p>
    <span class="suave">Cliente </span>
    <span class="resaltar">
        <?= $row->nombre ?> <?= $row->apellidos ?>
    </span>
    <span class="suave"> | </span>
    
    <span class="suave">Valor </span>
    <span class="resaltar">
        <?= $this->Pcrn->moneda($row->valor_total) ?>
    </span>
    <span class="suave"> | </span>
    
    <span class="suave">Creado </span>
    <span class="resaltar">
        <?= $this->Pcrn->fecha_formato($row->creado, 'Y-M-d') ?>
    </span>
    <span class="suave">
        Hace <?= $this->Pcrn->tiempo_hace($row->creado); ?>
    </span>
    <span class="suave"> | </span>
    
    <span class="suave">
        Estado Pago 
    </span>
    <span class="resaltar">
        <?= $this->App_model->nombre_item($row->codigo_respuesta_pol, 1, 10); ?>
    </span>
    <span class="suave"> | </span>
</p>

<?php
        $seccion = $this->uri->segment(2);
        //if ( $this->uri->segment(2) == 'productos_top' ) { $seccion = ''; }

        $clases[$seccion] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['explorar'] = array(
            'icono' => '<i class="fa fa-caret-left"></i>',
            'texto' => 'Pedidos',
            'link' => "pedidos/explorar/",
            'atributos' => 'title="Explorar pedidos"'
        );
            
        $arr_menus['ver'] = array(
            'icono' => '<i class="fa fa-info-circle"></i>',
            'texto' => 'Ver pedido',
            'link' => "pedidos/ver/{$row->id}",
            'atributos' => 'title="Información del pedido"'
        );

        $arr_menus['extras'] = array(
            'icono' => '<i class="fas fa-dollar-sign"></i>',
            'texto' => 'Extras',
            'link' => "pedidos/extras/{$row->id}",
            'atributos' => 'title="Cobros extras y descuentos"'
        );
            
        $arr_menus['pol'] = array(
            'icono' => '<i class="fa fa-credit-card"></i>',
            'texto' => 'PagosOnLine',
            'link' => "pedidos/pol/{$row->id}",
            'atributos' => 'title="Datos de Pagos On Line sobre el pedido"'
        );
            
        $arr_menus['editar'] = array(
            'icono' => '<i class="fa fa-edit"></i>',
            'texto' => 'Editar',
            'link' => "pedidos/editar/edit/{$row->id}",
            'atributos' => 'title="Editar pedido"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('explorar', 'ver', 'pol', 'extras', 'editar');
        $elementos_rol[1] = array('explorar', 'ver', 'pol', 'editar');
        $elementos_rol[2] = array('explorar', 'ver', 'pol', 'editar');
        $elementos_rol[6] = array('explorar', 'ver', 'pol', 'editar');
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: comunes/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
    
    //Cargue vista
        $this->load->view('comunes/menu_v', $data_menu);
        $this->load->view($vista_b);