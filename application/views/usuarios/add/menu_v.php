<?php
    $cl_nav_3['institutional'] = '';
    $cl_nav_3['person'] = '';

    $app_cf_index = $this->uri->segment(3);
    if ( strlen($app_cf_index) == 0 ) { $app_cf_index = 'person'; }
    $cl_nav_3[$app_cf_index] = 'active';
?>

<script>
    var sections = [];
    var nav_3 = [];
    var sections_rol = [];
    //var element_id = '<?php //echo $this->uri->segment(3) ?>';
    
    sections.institutional = {
        icon: '',
        text: 'Institución',
        class: '<?= $cl_nav_3['institutional'] ?>',
        cf: 'usuarios/add/institutional',
        anchor: true
    };

    sections.person = {
        icon: '',
        text: 'Persona',
        class: '<?= $cl_nav_3['person'] ?>',
        cf: 'usuarios/add/person',
        anchor: true
    };
    
    //Secciones para cada rol
    sections_rol[0] = ['person', 'institutional'];
    sections_rol[1] = ['person', 'institutional'];
    sections_rol[2] = ['person', 'institutional'];
    sections_rol.prpt = ['person', 'institutional'];
    
    //Recorrer el sections del rol actual y cargarlos en el menú
    for ( key_section in sections_rol[app_rid]) 
    {
        var key = sections_rol[app_rid][key_section];   //Identificar elemento
        nav_3.push(sections[key]);    //Agregar el elemento correspondiente
    }
</script>

<?php
$this->load->view('common/nav_3_v');