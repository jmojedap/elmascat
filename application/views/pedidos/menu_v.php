<?php $this->load->view('pedidos/pedido_v') ?>

<?php
    $app_cf_index = $this->uri->segment(1) . '_' . $this->uri->segment(2);
    
    $cl_nav_2['pedidos_explore'] = '';
    $cl_nav_2['pedidos_info'] = '';
    $cl_nav_2['pedidos_payu'] = '';
    $cl_nav_2['pedidos_test'] = '';
    $cl_nav_2['pedidos_edit'] = '';
    $cl_nav_2['pedidos_extras'] = '';
    //$cl_nav_2['pedidos_import'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    //if ( $app_cf_index == 'pedidos_cropping' ) { $cl_nav_2['pedidos_extras'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_rol = [];
    var element_id = '<?php echo $row->id ?>';
    
    sections.explore = {
        icon: 'fa fa-arrow-left',
        text: 'Explorar',
        class: '<?php echo $cl_nav_2['pedidos_explore'] ?>',
        cf: 'pedidos/explorar/',
        anchor: true
    };

    sections.info = {
        icon: 'fa fa-info-circle',
        text: 'Información',
        class: '<?php echo $cl_nav_2['pedidos_info'] ?>',
        cf: 'pedidos/info/' + element_id,
        anchor: true
    };

    sections.payu = {
        icon: '',
        text: 'PayU',
        class: '<?php echo $cl_nav_2['pedidos_payu'] ?>',
        cf: 'pedidos/payu/' + element_id,
        anchor: true
    };

    sections.test = {
        icon: '',
        text: 'Test',
        class: '<?php echo $cl_nav_2['pedidos_test'] ?>',
        cf: 'pedidos/test/' + element_id + '/confirmation',
        anchor: true
    };

    sections.edit = {
        icon: 'fa fa-pencil-alt',
        text: 'Editar',
        class: '<?php echo $cl_nav_2['pedidos_editar'] ?>',
        cf: 'pedidos/editar/' + element_id,
        anchor: true
    };
    
    sections.extras = {
        icon: 'fas fa-dollar-sign',
        text: 'Extras',
        class: '<?php echo $cl_nav_2['pedidos_extras'] ?>',
        cf: 'pedidos/extras/' + element_id,
        anchor: true
    };
    
    //Secciones para cada rol
    sections_rol.dvlp = ['explore', 'info', 'payu', 'extras', 'test', 'edit'];
    sections_rol.admn = ['explore', 'info', 'payu', 'extras', 'test', 'edit'];
    sections_rol.edtr = ['explore', 'info', 'payu', 'edit'];
    sections_rol.vndd = ['explore', 'info', 'payu', 'edit'];
    sections_rol.clbd = ['explore', 'info', 'payu', 'edit'];
    
    //Recorrer el sections del rol actual y cargarlos en el menú
    for ( key_section in sections_rol[app_r]) 
    {
        var key = sections_rol[app_r][key_section];   //Identificar elemento
        nav_2.push(sections[key]);    //Agregar el elemento correspondiente
    }
    
</script>

<?php
$this->load->view('common/nav_2_v');