<?php $this->load->view('assets/chosen_jquery'); ?>
<?php $this->load->view('assets/ionslider'); ?>
<?php $this->load->view('assets/toastr'); ?>

<?php $this->load->view('productos/editar/editar_js_v'); ?>

<?php $this->load->view('productos/editar/menu_v'); ?>

<?php
    //Vista formulario según sección
    $vistas_seccion = array(
        '' => 'productos/editar/campos_descripcion_v',
        'descripcion' => 'productos/editar/campos_descripcion_v',
        'valores' => 'productos/editar/campos_valores_v',
        'especificaciones' => 'productos/editar/metadatos_v',
        'imagenes' => 'productos/editar/imagenes_v',
    );
    
    $vista_seccion = $vistas_seccion[$this->uri->segment(4)];
    
    $this->load->view($vista_seccion);





    


