<?php
    $app_cf_index = $this->uri->segment(1) . '_' . $this->uri->segment(2);
    
    $cl_nav_2['estadisticas_minutos_en_linea'] = '';
    $cl_nav_2['estadisticas_productos_top'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    //if ( $app_cf_index == 'usuarios_import_e' ) { $cl_nav_2['usuarios_import'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_rol = [];

    sections.productos_top = {
        icon: '',
        text: 'Productos top',
        class: '<?= $cl_nav_2['estadisticas_productos_top'] ?>',
        cf: 'estadisticas/productos_top',
        anchor: true
    };
    
    sections.minutos_en_linea = {
        icon: '',
        text: 'MDA En Línea',
        class: '<?= $cl_nav_2['estadisticas_minutos_en_linea'] ?>',
        cf: 'estadisticas/minutos_en_linea',
        anchor: true
    };
    
    //Secciones para cada rol
    sections_rol[0] = ['productos_top', 'minutos_en_linea'];
    sections_rol[1] = ['productos_top', 'minutos_en_linea'];
    sections_rol[2] = ['productos_top', 'minutos_en_linea'];
    
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