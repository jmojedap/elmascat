<?php
    $app_cf_index = $this->uri->segment(2) . '_' . $section;

    $cl_nav_3['edit_description'] = '';
    $cl_nav_3['edit_valores'] = '';
    $cl_nav_3['edit_metadata'] = '';
    //$cl_nav_3['edit_import'] = '';
    
    $cl_nav_3[$app_cf_index] = 'active';
    if ( $app_cf_index == 'edit_cropping' ) { $cl_nav_3['edit_image'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_3 = [];
    var sections_rol = [];
    var element_id = '<?= $row->id ?>';

    sections.description = {
        icon: '',
        text: 'General',
        class: '<?= $cl_nav_3['edit_description'] ?>',
        cf: 'productos/edit/' + element_id + '/description'
    };
    
    sections.valores = {
        icon: '',
        text: 'Valores',
        class: '<?= $cl_nav_3['edit_valores'] ?>',
        cf: 'productos/edit/' + element_id + '/valores'
    };

    sections.metadata = {
        icon: '',
        text: 'Especificaciones',
        class: '<?= $cl_nav_3['edit_metadata'] ?>',
        cf: 'productos/edit/' + element_id + '/metadata'
    };
    
    //Secciones para cada rol
    sections_rol[0] = ['description', 'valores', 'metadata'];
    sections_rol[1] = ['description', 'valores', 'metadata'];
    sections_rol[2] = ['description', 'valores', 'metadata'];
    sections_rol[6] = ['description', 'valores', 'metadata'];
    sections_rol[7] = ['description', 'valores', 'metadata'];
    
    //Recorrer el sections del rol actual y cargarlos en el menú
    for ( key_section in sections_rol[app_rid]) 
    {
        var key = sections_rol[app_rid][key_section];   //Identificar elemento
        nav_3.push(sections[key]);    //Agregar el elemento correspondiente
    }
    
</script>

<?php
$this->load->view('common/nav_3_v');