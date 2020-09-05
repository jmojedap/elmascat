<?php
    $app_cf_index = $this->uri->segment(2) . '_' . $this->uri->segment(4);
    
    $cl_nav_3['test_confirmation'] = '';
    $cl_nav_3['test_result'] = '';;
    //$cl_nav_3['orders_import'] = '';
    
    $cl_nav_3[$app_cf_index] = 'active';
    //if ( $app_cf_index == 'orders_cropping' ) { $cl_nav_3['orders_test'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_3 = [];
    var sections_rol = [];
    var element_id = '<?= $row->id ?>';
    
    sections.confirmation = {
        icon: '',
        text: 'Confirmación',
        class: '<?= $cl_nav_3['test_confirmation'] ?>',
        cf: 'pedidos/test/' + element_id + '/confirmation',
        anchor: true

    };

    sections.result = {
        icon: '',
        text: 'Resultado',
        class: '<?= $cl_nav_3['test_result'] ?>',
        cf: 'pedidos/test/' + element_id + '/result',
        anchor: true
    };
    
    //Secciones para cada rol
    sections_rol.dvlp = ['confirmation', 'result'];
    //sections_rol.admn = ['explore', 'info', 'edit', 'test'];
    
    //Recorrer el sections del rol actual y cargarlos en el menú
    for ( key_section in sections_rol[app_r]) 
    {
        //console.log(sections_rol[rol][key_section]);
        var key = sections_rol[app_r][key_section];   //Identificar elemento
        nav_3.push(sections[key]);    //Agregar el elemento correspondiente
    }
    
</script>

<?php
$this->load->view('common/nav_3_v');