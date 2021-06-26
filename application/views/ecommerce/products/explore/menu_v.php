<?php
    $app_cf_index = $this->uri->segment(1) . '_' . $this->uri->segment(2);
    
    $cl_nav_2['productos_explore'] = '';
    $cl_nav_2['productos_actualizar_datos'] = '';
    $cl_nav_2['productos_promociones'] = '';
    $cl_nav_2['productos_add'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    if ( $app_cf_index == 'productos_actualizar_datos_e' ) { $cl_nav_2['productos_actualizar_datos'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_role = [];
    
    sections.explore = {
        icon: 'fa fa-search',
        text: 'Explorar',
        class: '<?= $cl_nav_2['productos_explore'] ?>',
        cf: 'productos/explore'
    };

    sections.actualizar_datos = {
        icon: 'fa fa-upload',
        text: 'Importar',
        class: '<?= $cl_nav_2['productos_actualizar_datos'] ?>',
        cf: 'productos/actualizar_datos'
    };

    sections.promociones = {
        icon: '',
        text: 'Promociones',
        class: '<?= $cl_nav_2['productos_promociones'] ?>',
        cf: 'productos/promociones'
    };

    sections.add = {
        icon: 'fa fa-plus',
        text: 'Nuevo',
        class: '<?= $cl_nav_2['productos_add'] ?>',
        cf: 'productos/add'
    };
    
    //Secciones para cada rol
    sections_role[0] = ['explore', 'add', 'promociones', 'actualizar_datos'];
    sections_role[1] = ['explore', 'add', 'promociones', 'actualizar_datos'];
    sections_role[2] = ['explore', 'add', 'actualizar_datos'];
    sections_role[6] = ['explore', 'add'];
    sections_role[7] = ['explore', 'add'];
    
    //Recorrer el sections del rol actual y cargarlos en el menú
    for ( key_section in sections_role[app_rid]) 
    {
        var key = sections_role[app_rid][key_section];   //Identificar elemento
        nav_2.push(sections[key]);    //Agregar el elemento correspondiente
    }
</script>

<?php
$this->load->view('common/nav_2_v');