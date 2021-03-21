<?php
    $app_cf_index = $this->uri->segment(1) . '_' . $this->uri->segment(2);
    
    $cl_nav_2['accounts_profile'] = '';
    $cl_nav_2['accounts_edit'] = '';
    //$cl_nav_2['accounts_import'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    //if ( $app_cf == 'accounts/explore' ) { $cl_nav_2['accounts_explore'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_rol = [];
    var element_id = '<?= $row->id ?>';
    
    sections.profile = {
        icon: 'fa fa-user',
        text: 'Perfil',
        class: '<?= $cl_nav_2['accounts_profile'] ?>',
        cf: 'accounts/profile/'
    };

    sections.edit = {
        icon: 'fa fa-pencil-alt',
        text: 'Editar',
        class: '<?= $cl_nav_2['accounts_edit'] ?>',
        cf: 'accounts/edit/basic'
    };
    
    //Secciones para cada rol
    sections_rol[0] = ['profile', 'edit'];
    sections_rol[1] = ['profile', 'edit'];
    sections_rol[2] = ['profile', 'edit'];
    sections_rol[6] = ['profile', 'edit'];
    sections_rol[15] = ['profile', 'edit'];
    sections_rol[21] = ['profile', 'edit'];
    sections_rol[22] = ['profile', 'edit'];
    sections_rol[23] = ['profile', 'edit'];
    
    //Recorrer el sections del rol actual y cargarlos en el menú
    for ( key_section in sections_rol[app_rid]) 
    {
        //console.log(sections_rol[rol][key_section]);
        var key = sections_rol[app_rid][key_section];   //Identificar elemento
        nav_2.push(sections[key]);    //Agregar el elemento correspondiente
    }
    
</script>

<?php
$this->load->view('common/nav_2_v');