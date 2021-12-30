<?php $this->load->view('pedidos/pedido_v') ?>

<?php
    $app_cf_index = $this->uri->segment(1) . '_' . $this->uri->segment(2);
    
    $cl_nav_2['pedidos_explore'] = '';
    $cl_nav_2['pedidos_info'] = '';
    $cl_nav_2['pedidos_payment'] = '';
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
    var element_id = '<?= $row->id ?>';

    sections.info = {
        icon: 'fa fa-info-circle',
        text: 'Información',
        class: '<?= $cl_nav_2['pedidos_info'] ?>',
        cf: 'pedidos/info/' + element_id,
        anchor: true
    };

    sections.payment = {
        icon: 'fa fa-info-circle',
        text: 'Pago',
        class: '<?= $cl_nav_2['pedidos_payment'] ?>',
        cf: 'pedidos/payment/' + element_id,
        anchor: true
    };

    sections.payu = {
        icon: '',
        text: 'PayU',
        class: '<?= $cl_nav_2['pedidos_payu'] ?>',
        cf: 'pedidos/payu/' + element_id,
        anchor: true
    };

    sections.test = {
        icon: '',
        text: 'Test',
        class: '<?= $cl_nav_2['pedidos_test'] ?>',
        cf: 'pedidos/test/' + element_id + '/confirmation',
        anchor: true
    };

    sections.edit = {
        icon: 'fa fa-pencil-alt',
        text: 'Editar',
        class: '<?= $cl_nav_2['pedidos_editar'] ?>',
        cf: 'pedidos/editar/' + element_id,
        anchor: true
    };
    
    sections.extras = {
        icon: 'fas fa-dollar-sign',
        text: 'Extras',
        class: '<?= $cl_nav_2['pedidos_extras'] ?>',
        cf: 'pedidos/extras/' + element_id,
        anchor: true
    };
    
    //Secciones para cada rol
    sections_rol[0] = ['info', 'payment', 'payu', 'extras', 'test', 'edit'];
    sections_rol[1] = ['info', 'payment', 'payu', 'extras', 'edit'];
    sections_rol[2] = ['info', 'payment', 'payu', 'edit'];
    sections_rol[6] = ['info', 'payu', 'edit'];
    sections_rol.clbd = ['info', 'payu', 'edit'];
    
    //Recorrer el sections del rol actual y cargarlos en el menú
    for ( key_section in sections_rol[app_rid]) 
    {
        var key = sections_rol[app_rid][key_section];   //Identificar elemento
        nav_2.push(sections[key]);    //Agregar el elemento correspondiente
    }
    
</script>

<?php
$this->load->view('common/nav_2_v');