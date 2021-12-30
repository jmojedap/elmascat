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
        
        $arr_menus['lista'] = array(
            'icono' => '<i class="fa fa-th-list"></i>',
            'texto' => 'Elementos',
            'link' => "posts/lista/{$row->id}",
            'atributos' => ''
        );
        
        $arr_menus['ver'] = array(
            'icono' => '<i class="fa fa-laptop"></i>',
            'texto' => '',
            'link' => "posts/leer/{$row->id}",
            'atributos' => ''
        );
        
    //Elementos de menú para cada rol
        $elementos_rol[0] = array('explorar', 'lista', 'editar');
        $elementos_rol[1] = array('explorar', 'lista', 'editar');
        $elementos_rol[2] = array('explorar', 'lista', 'editar');
        
        
        
    //Definiendo menú mostrar
        $elementos = $elementos_rol[$this->session->userdata('role')];
        
    //Array data para la vista: app/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
    
?>
<p>
    <span class="text-muted">Post tipo </span>
    <span class="text-success">
        Lista
    </span>
    <span class="text-muted"> | </span>
    
    <span class="text-muted">Creado </span>
    <span class="text-success">
        <?= $this->Pcrn->fecha_formato($row->creado, 'Y-M-d') ?>
    </span>
    <span class="text-muted"> | </span>
    
    <span class="text-muted">Por </span>
    <span class="text-success">
        <?= $this->App_model->nombre_usuario($row->usuario_id, 2) ?>
    </span>
    <span class="text-muted"> | </span>
    
    <span class="text-muted">Editado </span>
    <span class="text-success">
        <?= $this->Pcrn->fecha_formato($row->editado, 'Y-M-d') ?>
    </span>
    <span class="text-muted"> | </span>
    
    <span class="text-muted">Por </span>
    <span class="text-success">
        <?= $this->App_model->nombre_usuario($row->editor_id, 2) ?>
    </span>
    <span class="text-muted"> | </span>
</p>

<?php $this->load->view('common/nav_2_v', $data_menu)?>

