<?php
    $app_cf_index = $this->uri->segment(1) . '_' . $this->uri->segment(2);
    
    $cl_nav_2['posts_info'] = '';
    $cl_nav_2['posts_edit'] = '';
    $cl_nav_2['posts_details'] = '';
    
    
    $cl_nav_2[$app_cf_index] = 'active';
    /*if ( $app_cf_index == 'posts_cropping' ) { $cl_nav_2['posts_list_elements'] = 'active'; }*/
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_rol = [];
    var element_id = '<?= $row->id ?>';

    sections.info = {
        icon: 'fa fa-info-circle',
        text: 'Información',
        class: '<?= $cl_nav_2['posts_info'] ?>',
        cf: 'posts/info/' + element_id
    };

    sections.details = {
        icon: '',
        text: 'Detalles',
        class: '<?= $cl_nav_2['posts_details'] ?>',
        cf: 'posts/details/' + element_id
    };

    sections.edit = {
        icon: 'fa fa-pencil-alt',
        text: 'Editar',
        class: '<?= $cl_nav_2['posts_edit'] ?>',
        cf: 'posts/edit/' + element_id,
        anchor: true
    };
    
    //Secciones para cada rol
    sections_rol[0] = ['info', 'details', 'edit'];
    sections_rol[1] = ['info', 'details', 'edit'];
    sections_rol[2] = ['info', 'details', 'edit'];
    
    //Recorrer el sections del rol actual y cargarlos en el menú
    for ( key_section in sections_rol[app_rid]) 
    {
        var key = sections_rol[app_rid][key_section];   //Identificar elemento
        nav_2.push(sections[key]);    //Agregar el elemento correspondiente
    }
    
</script>

<?php
$this->load->view('common/nav_2_v');