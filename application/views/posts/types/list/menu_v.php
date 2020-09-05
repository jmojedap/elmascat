<?php
    $app_cf_index = $this->uri->segment(1) . '_' . $this->uri->segment(2);
    
    $cl_nav_2['posts_explore'] = '';
    $cl_nav_2['posts_info'] = '';
    $cl_nav_2['posts_edit'] = '';
    $cl_nav_2['posts_list_elements'] = '';
    //$cl_nav_2['posts_import'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    /*if ( $app_cf_index == 'posts_cropping' ) { $cl_nav_2['posts_list_elements'] = 'active'; }*/
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_rol = [];
    var element_id = '<?= $row->id ?>';
    
    sections.explore = {
        'icon': 'fa fa-arrow-left',
        'text': 'Explorar',
        'class': '<?= $cl_nav_2['posts_explore'] ?>',
        'cf': 'posts/explore/',
        'anchor': true
    };

    sections.info = {
        'icon': 'fa fa-info-circle',
        'text': 'Información',
        'class': '<?= $cl_nav_2['posts_info'] ?>',
        'cf': 'posts/info/' + element_id
    };

    sections.edit = {
        'icon': 'fa fa-pencil-alt',
        'text': 'Editar',
        'class': '<?= $cl_nav_2['posts_edit'] ?>',
        'cf': 'posts/edit/' + element_id
    };
    
    sections.list_elements = {
        'icon': 'fa fa-list',
        'text': 'Elementos',
        'class': '<?= $cl_nav_2['posts_list_elements'] ?>',
        'cf': 'posts/list_elements/' + element_id
    };
    
    //Secciones para cada rol
    sections_rol.dvlp = ['explore', 'info', 'list_elements', 'edit'];
    sections_rol.admn = ['info', 'list_elements', 'edit'];
    
    //Recorrer el sections del rol actual y cargarlos en el menú
    for ( key_section in sections_rol[app_r]) 
    {
        var key = sections_rol[app_r][key_section];   //Identificar elemento
        nav_2.push(sections[key]);    //Agregar el elemento correspondiente
    }
    
</script>

<?php
$this->load->view('common/nav_2_v');