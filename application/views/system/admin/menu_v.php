<?php
    $app_cf_index = $this->uri->segment(1) . '_' . $this->uri->segment(2);
    
    $cl_nav_2['admin_options'] = '';
    $cl_nav_2['admin_processes'] = '';
    $cl_nav_2['admin_colors'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    //if ( $app_cf == 'documents/structure' ) { $cl_nav_2['documents_info'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_role = [];
    
    sections.options = {
        icon: 'fa fa-cog',
        text: 'Opciones',
        class: '<?= $cl_nav_2['admin_options'] ?>',
        cf: 'admin/options/'
    };

    sections.processes = {
        icon: '',
        text: 'Procesos',
        class: '<?= $cl_nav_2['admin_processes'] ?>',
        cf: 'admin/processes/'
    };

    sections.colors = {
        icon: 'fas fa-tint',
        text: 'Colores',
        class: '<?= $cl_nav_2['admin_colors'] ?>',
        cf: 'admin/colors/'
    };
    
    //Secciones para cada rol
    sections_role[0] = ['options', 'processes', 'colors'];
    sections_role[1] = ['options', 'processes', 'colors'];
    sections_role[2] = ['options', 'processes'];
    
    //Recorrer el sections del rol actual y cargarlos en el menú
    for ( key_section in sections_role[app_rid]) 
    {
        var key = sections_role[app_rid][key_section];   //Identificar elemento
        nav_2.push(sections[key]);    //Agregar el elemento correspondiente
    }
</script>

<?php
$this->load->view('common/nav_2_v');