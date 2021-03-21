<?php
    $app_cf_index = $this->uri->segment(1) . '_' . $this->uri->segment(2);
    
    $cl_nav_2['usuarios_explore'] = '';
    $cl_nav_2['usuarios_profile'] = '';
    $cl_nav_2['usuarios_pedidos'] = '';
    $cl_nav_2['usuarios_books'] = '';
    $cl_nav_2['usuarios_edit'] = '';
    //$cl_nav_2['usuarios_import'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    if ( $app_cf_index == 'usuarios_cropping' ) { $cl_nav_2['usuarios_image'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_rol = [];
    var element_id = '<?= $row->id ?>';
    
    sections.explore = {
        icon: 'fa fa-arrow-left',
        text: 'Explorar',
        class: '<?= $cl_nav_2['usuarios_explore'] ?>',
        cf: 'usuarios/explore/',
        anchor: true
    };

    sections.profile = {
        icon: 'fa fa-profile-circle',
        text: 'Información',
        class: '<?= $cl_nav_2['usuarios_profile'] ?>',
        cf: 'usuarios/profile/' + element_id,
        anchor: true
    };

    sections.pedidos = {
        icon: 'fa fa-shopping-cart',
        text: 'Pedidos',
        class: '<?= $cl_nav_2['usuarios_pedidos'] ?>',
        cf: 'usuarios/pedidos/' + element_id,
        anchor: true
    };

    sections.books = {
        icon: 'fa fa-book',
        text: 'Contenidos',
        class: '<?= $cl_nav_2['usuarios_books'] ?>',
        cf: 'usuarios/books/' + element_id,
        anchor: true
    };

    sections.edit = {
        icon: 'fa fa-pencil-alt',
        text: 'Editar',
        class: '<?= $cl_nav_2['usuarios_edit'] ?>',
        cf: 'usuarios/edit/' + element_id,
        anchor: true
    };

    sections.editarme = {
        icon: 'fa fa-pencil-alt',
        text: 'Editar',
        class: '<?= $cl_nav_2['usuarios_edit'] ?>',
        cf: 'usuarios/editarme/edit/' + element_id,
        anchor: true
    };
    
    //Secciones para cada rol
    sections_rol[0] = ['explore', 'profile', 'pedidos', 'books', 'edit'];
    sections_rol[1] = ['explore', 'profile', 'pedidos', 'books', 'edit'];
    sections_rol[2] = ['explore', 'profile', 'pedidos', 'books', 'edit'];
    sections_rol[6] = ['explore', 'profile', 'pedidos', 'books'];
    sections_rol.clbd = ['explore', 'profile', 'pedidos'];
    sections_rol[21] = ['profile', 'pedidos', 'editarme'];
    sections_rol[22] = ['profile', 'pedidos', 'editarme'];
    sections_rol[23] = ['profile', 'pedidos', 'editarme'];
    
    //Recorrer el sections del rol actual y cargarlos en el menú
    for ( key_section in sections_rol[app_rid]) 
    {
        var key = sections_rol[app_rid][key_section];   //Identificar elemento
        nav_2.push(sections[key]);    //Agregar el elemento correspondiente
    }
    
</script>

<?php
$this->load->view('common/nav_2_v');