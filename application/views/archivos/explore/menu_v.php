<?php
    $app_cf_index = $this->uri->segment(1) . '_' . $this->uri->segment(2);
    
    $cl_nav_2['archivos_imagenes'] = '';
    $cl_nav_2['archivos_carpetas'] = '';
    $cl_nav_2['archivos_import'] = '';
    $cl_nav_2['archivos_add'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    if ( $app_cf_index == 'archivos_import_e' ) { $cl_nav_2['archivos_import'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_role = [];

    sections.imagenes = {
        icon: '',
        text: 'Imágenes',
        class: '<?= $cl_nav_2['archivos_imagenes'] ?>',
        cf: 'archivos/imagenes',
        anchor: true
    };

    sections.carpetas = {
        icon: '',
        text: 'Carpetas',
        class: '<?= $cl_nav_2['archivos_carpetas'] ?>',
        cf: 'archivos/carpetas/2020/06'
    };

    sections.add = {
        icon: '',
        text: 'Cargar',
        class: '<?= $cl_nav_2['archivos_add'] ?>',
        cf: 'archivos/add'
    };
    
    //Secciones para cada rol
    sections_role[0] = ['imagenes', 'add', 'carpetas'];
    sections_role[1] = ['imagenes', 'add'];
    sections_role[2] = ['imagenes', 'add'];
    
    //Recorrer el sections del rol actual y cargarlos en el menú
    for ( key_section in sections_role[app_rid]) 
    {
        //console.log(sections_role[rol][key_section]);
        var key = sections_role[app_rid][key_section];   //Identificar elemento
        nav_2.push(sections[key]);    //Agregar el elemento correspondiente
    }
</script>

<?php
$this->load->view('common/nav_2_v');