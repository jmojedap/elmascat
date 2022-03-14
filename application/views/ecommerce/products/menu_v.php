<?php
    $app_cf_index = $this->uri->segment(1) . '_' . $this->uri->segment(2);

    $cl_nav_2['productos_info'] = '';
    $cl_nav_2['productos_details'] = '';
    $cl_nav_2['productos_edit'] = '';
    $cl_nav_2['productos_tags'] = '';
    $cl_nav_2['productos_orders'] = '';
    $cl_nav_2['productos_books'] = '';
    $cl_nav_2['productos_images'] = '';
    //$cl_nav_2['productos_import'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    if ( $app_cf_index == 'productos_cropping' ) { $cl_nav_2['productos_image'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_rol = [];
    var element_id = '<?= $row->id ?>';

    sections.info = {
        icon: '',
        text: 'Información',
        class: '<?= $cl_nav_2['productos_info'] ?>',
        cf: 'productos/info/' + element_id
    };

    sections.details = {
        icon: '',
        text: 'Detalles',
        class: '<?= $cl_nav_2['productos_details'] ?>',
        cf: 'productos/details/' + element_id
    };

    sections.images = {
        icon: 'fa fa-image',
        text: 'Imágenes',
        class: '<?= $cl_nav_2['productos_images'] ?>',
        cf: 'productos/images/' + element_id
    };

    sections.tags = {
        icon: '',
        text: 'Etiquetas',
        class: '<?= $cl_nav_2['productos_tags'] ?>',
        cf: 'productos/tags/' + element_id
    };

    sections.orders = {
        icon: '',
        text: 'Pedidos',
        class: '<?= $cl_nav_2['productos_orders'] ?>',
        cf: 'productos/orders/' + element_id
    };

    sections.variaciones = {
        icon: '',
        text: 'Variaciones',
        class: '<?= $cl_nav_2['productos_variaciones'] ?>',
        cf: 'productos/variaciones/' + element_id
    };

    sections.books = {
        icon: '',
        text: 'Contenidos',
        class: '<?= $cl_nav_2['productos_books'] ?>',
        cf: 'productos/books/' + element_id
    };

    sections.edit = {
        icon: 'fa fa-pencil-alt',
        text: 'Editar',
        class: '<?= $cl_nav_2['productos_edit'] ?>',
        cf: 'productos/edit/' + element_id,
        anchor: true
    };

    sections.detalle = {
        icon: '',
        text: 'Vista previa',
        class: '<?= $cl_nav_2['productos_detalle'] ?>',
        cf: 'productos/detalle/' + element_id,
        anchor: true
    };
    
    //Secciones para cada rol
    sections_rol[0] = ['info', 'details', 'images', 'edit', 'tags', 'orders', 'variaciones', 'books', 'detalle'];
    sections_rol[1] = ['info', 'details', 'images', 'edit', 'tags', 'orders', 'variaciones', 'books', 'detalle'];
    sections_rol[2] = ['info', 'details', 'images', 'edit', 'tags', 'orders', 'variaciones', 'books', 'detalle'];
    sections_rol[6] = ['info', 'details', 'images', 'edit', 'tags', 'variaciones', 'detalle'];
    sections_rol[7] = ['info', 'details', 'images', 'edit', 'tags', 'variaciones', 'detalle'];
    
    //Recorrer el sections del rol actual y cargarlos en el menú
    for ( key_section in sections_rol[app_rid]) 
    {
        var key = sections_rol[app_rid][key_section];   //Identificar elemento
        nav_2.push(sections[key]);    //Agregar el elemento correspondiente
    }
    
</script>

<?php
$this->load->view('common/nav_2_v');