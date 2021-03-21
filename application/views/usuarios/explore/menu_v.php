<?php
    $app_cf_index = $this->uri->segment(1) . '_' . $this->uri->segment(2);
    
    $cl_nav_2['usuarios_explore'] = '';
    $cl_nav_2['usuarios_add'] = '';
    $cl_nav_2['usuarios_solicitudes_rol'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    if ( $app_cf_index == 'usuarios_import_e' ) { $cl_nav_2['usuarios_import'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_rol = [];
    
    sections.explore = {
        icon: 'fa fa-search',
        text: 'Explorar',
        class: '<?= $cl_nav_2['usuarios_explore'] ?>',
        cf: 'usuarios/explore'
    };

    sections.add = {
        icon: 'fa fa-plus',
        text: 'Nuevo',
        class: '<?= $cl_nav_2['usuarios_add'] ?>',
        cf: 'usuarios/add',
        anchor: true
    };

    sections.solicitudes_rol = {
        icon: 'fa fa-user-plus',
        text: 'Solicitudes',
        class: '<?= $cl_nav_2['usuarios_solicitudes_rol'] ?>',
        cf: 'usuarios/solicitudes_rol',
        anchor: true
    };
    
    //Secciones para cada rol
    sections_rol[0] = ['explore', 'add', 'solicitudes_rol'];
    sections_rol[1] = ['explore', 'add', 'solicitudes_rol'];
    sections_rol[2] = ['explore', 'add'];
    sections_rol[6] = ['explore'];
    
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