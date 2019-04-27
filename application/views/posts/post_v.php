<?php
        
    //Clases menú
        $seccion = $this->uri->segment(2);
        if ( $this->uri->segment(2) == 'nuevo_grupo' ) { $seccion = 'grupos'; }

        $clases[$seccion] = 'active';

    
    //Atributos de los elementos del menú
        $arr_menus['explorar'] = array(
            'icono' => '<i class="fa fa-list-alt"></i>',
            'texto' => '',
            'link' => "posts/explorar/",
            'atributos' => ''
        );
        
        $arr_menus['editar'] = array(
            'icono' => '<i class="fa fa-pencil"></i>',
            'texto' => 'Editar',
            'link' => "posts/editar/{$row->id}",
            'atributos' => ''
        );
        
        $arr_menus['ver'] = array(
            'icono' => '<i class="fa fa-laptop"></i>',
            'texto' => '',
            'link' => "posts/leer/{$row->id}",
            'atributos' => 'target="_blank"'
        );
        
    //Elementos de menú para cada rol
        $elementos_rol[0] = array('explorar', 'editar', 'ver');
        $elementos_rol[1] = array('explorar', 'editar', 'ver');
        
        
        
    //Definiendo menú mostrar
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: app/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
    
?>
<p>
    <span class="suave">Creado </span>
    <span class="resaltar">
        <?= $row->nombre ?> <?= $row->creado ?>
    </span>
    <span class="suave"> | </span>
    
    <span class="suave">Tipo </span>
    <span class="resaltar">
        <?= $row->nombre ?> <?= $row->tipo_id ?>
    </span>
    <span class="suave"> | </span>
</p>

<?php $this->load->view('comunes/menu_v', $data_menu)?>
<?php $this->load->view($vista_b); ?>

