<?php
    $app_cf_index = $this->uri->segment(1) . '_' . $this->uri->segment(2);
    
    $cl_nav_2['pedidos_explorar'] = '';
    $cl_nav_2['pedidos_import'] = '';
    $cl_nav_2['pedidos_nuevo'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    if ( $app_cf_index == 'pedidos_import_e' ) { $cl_nav_2['pedidos_import'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_rol = [];
    
    sections.explorar = {
        icon: 'fa fa-search',
        text: 'Explorar',
        class: '<?= $cl_nav_2['pedidos_explorar'] ?>',
        cf: 'pedidos/explore'
    };
    
    //Secciones para cada rol
    sections_rol[0] = ['explorar'];
    sections_rol[1] = ['explorar'];
    sections_rol[2] = ['explorar'];
    
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