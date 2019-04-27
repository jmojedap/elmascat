<?php 
    $clase_estado = 'label label-danger';
    if ( $row->estado == 1 ) { $clase_estado = 'label label-success'; }
    
    $clase_imagenes = 'label label-danger';
    if ( $imagenes->num_rows() > 0 ) { $clase_imagenes = 'label label-success'; }
?>

<p>
    <span class="<?= $clase_estado ?>">
        <?= $this->Item_model->nombre(8, $row->estado); ?>
        <?php //$row->estado ?>
    </span>
    
    &nbsp;
   
    <span class="suave">Precio</span>
    <span class="resaltar">
        <?= $this->Pcrn->moneda($row->precio); ?>
    </span>
    |
    
    <span class="suave">Disponibles</span>
    <span class="resaltar">
        <?= $row->cant_disponibles; ?>
    </span>
    |
    
    <span class="suave">Imágenes</span>
    <span class="<?= $clase_imagenes ?>">
        <?= $imagenes->num_rows() ?>
    </span>
</p>

<?php
    //Clases menú
        $seccion = $this->uri->segment(2);
        if ( $this->uri->segment(2) == 'nuevo_grupo' ) { $seccion = 'grupos'; }

        $clases[$seccion] = 'active';

    
    //Atributos de los elementos del menú
        $arr_menus['explorar'] = array(
            'icono' => '<i class="fa fa-list-alt"></i>',
            'texto' => '',
            'link' => "productos/explorar/",
            'atributos' => ''
        );
        
        $arr_menus['ver'] = array(
            'icono' => '',
            'texto' => 'Información ',
            'link' => "productos/ver/{$row->id}",
            'atributos' => ''
        );
        
        $arr_menus['detalle'] = array(
            'icono' => '<i class="fa fa-laptop"></i>',
            'texto' => 'Vista previa',
            'link' => "productos/detalle/{$row->id}/{$row->slug}",
            'atributos' => 'target="_blank" title="Ver el producto en la tienda"'
        );
        
        $arr_menus['tags'] = array(
            'icono' => '<i class="fa fa-tags"></i>',
            'texto' => 'Etiquetas',
            'link' => "productos/tags/{$row->id}",
            'atributos' => ''
        );
        
        $arr_menus['variaciones'] = array(
            'icono' => '<i class="fa fa-code-fork"></i>',
            'texto' => 'Variaciones',
            'link' => "productos/variaciones/{$row->id}/",
            'atributos' => ''
        );
        
        $arr_menus['editar'] = array(
            'icono' => '<i class="fa fa-pencil"></i>',
            'texto' => 'Editar',
            'link' => "productos/editar/{$row->id}",
            'atributos' => ''
        );
        
        $arr_menus['nuevo'] = array(
            'icono' => '<i class="fa fa-plus"></i>',
            'texto' => 'Nuevo',
            'link' => "productos/nuevo/add",
            'atributos' => ''
        );
        
    //Elementos de menú para cada rol
        $elementos_rol[0] = array('explorar', 'ver', 'editar', 'tags', 'variaciones', 'detalle', 'nuevo');
        $elementos_rol[1] = array('explorar', 'ver', 'editar', 'tags', 'variaciones', 'detalle', 'nuevo');
        $elementos_rol[2] = array('explorar', 'ver', 'editar', 'tags', 'variaciones', 'detalle', 'nuevo');
        $elementos_rol[6] = array('explorar', 'ver', 'editar', 'tags', 'variaciones', 'detalle', 'nuevo');
        $elementos_rol[7] = array('explorar', 'ver', 'editar', 'tags', 'variaciones', 'detalle', 'nuevo');
        
        
    //Definiendo menú mostrar
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: app/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;

    //Cargar vista
        $this->load->view('comunes/menu_v', $data_menu);
        $this->load->view($vista_b);