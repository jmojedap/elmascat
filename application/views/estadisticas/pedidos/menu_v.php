<?php
    $app_cf_index = $this->uri->segment(1) . '_' . $this->uri->segment(2);
    
    $cl_nav_2['pedidos_resumen_dia'] = '';
    $cl_nav_2['pedidos_resumen_mes'] = '';
    $cl_nav_2['pedidos_ventas_departamento'] = '';
    $cl_nav_2['pedidos_meta_anual'] = '';
    $cl_nav_2['pedidos_productos_top'] = '';
    $cl_nav_2['pedidos_efectividad'] = '';
    $cl_nav_2['estadisticas_ventas_categoria'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    //if ( $app_cf_index == 'usuarios_import_e' ) { $cl_nav_2['usuarios_import'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_rol = [];
    
    sections.resumen_dia = {
        icon: '',
        text: 'Día',
        class: '<?= $cl_nav_2['pedidos_resumen_dia'] ?>',
        cf: 'pedidos/resumen_dia',
        anchor: true
    };

    sections.resumen_mes = {
        icon: '',
        text: 'Mes',
        class: '<?= $cl_nav_2['pedidos_resumen_mes'] ?>',
        cf: 'pedidos/resumen_mes',
        anchor: true
    };

    sections.ventas_departamento = {
        icon: '',
        text: 'Departamento',
        class: '<?= $cl_nav_2['pedidos_ventas_departamento'] ?>',
        cf: 'pedidos/ventas_departamento',
        anchor: true
    };

    sections.meta_anual = {
        icon: '',
        text: 'Meta anual',
        class: '<?= $cl_nav_2['pedidos_meta_anual'] ?>',
        cf: 'pedidos/meta_anual',
        anchor: true
    };

    sections.productos_top = {
        icon: '',
        text: 'Productos top',
        class: '<?= $cl_nav_2['pedidos_productos_top'] ?>',
        cf: 'pedidos/productos_top',
        anchor: true
    };

    sections.efectividad = {
        icon: '',
        text: 'Efectividad',
        class: '<?= $cl_nav_2['pedidos_efectividad'] ?>',
        cf: 'pedidos/efectividad',
        anchor: true
    };

    sections.ventas_categoria = {
        icon: '',
        text: 'Categorías',
        class: '<?= $cl_nav_2['estadisticas_ventas_categoria'] ?>',
        cf: 'estadisticas/ventas_categoria',
        anchor: true
    };
    
    //Secciones para cada rol
    sections_rol.dvlp = ['resumen_dia', 'resumen_mes', 'ventas_departamento', 'meta_anual', 'productos_top', 'efectividad', 'ventas_categoria'];
    sections_rol.admn = ['resumen_dia', 'resumen_mes', 'ventas_departamento', 'meta_anual', 'productos_top', 'efectividad', 'ventas_categoria'];
    sections_rol.edtr = ['resumen_dia', 'resumen_mes', 'ventas_departamento', 'meta_anual', 'productos_top', 'efectividad', 'ventas_categoria'];
    
    //Recorrer el sections del rol actual y cargarlos en el menú
    for ( key_section in sections_rol[app_r]) 
    {
        //console.log(sections_rol[rol][key_section]);
        var key = sections_rol[app_r][key_section];   //Identificar elemento
        nav_2.push(sections[key]);    //Agregar el elemento correspondiente
    }
</script>

<?php
$this->load->view('common/nav_2_v');