<?php
    $controladores = $this->db->get_where('item', 'categoria_id = 32');
    
    //Titulo filtro
        $titulo_filtro = 'Todos';
        if ( ! is_null( $controlador ) ) { $titulo_filtro = $controlador; }
        
?>

<?php $this->load->view('sistema/develop/procesos_menu_v'); ?>

<script>
    $(document).ready(function(){
        $('#field-funcion').change(function(){
            completar_campos();
        });
        
        $('#field-controlador').change(function(){
            completar_campos();
        });
    });
</script>

<script>
    function completar_campos()
    {
        var field_recurso = $('#field-controlador').val() + '/' + $('#field-funcion').val();
        var field_titulo = $('#field-controlador').val() + ' ' + $('#field-funcion').val();
        $('#field-recurso').val(field_recurso);
        $('#field-titulo_recurso').val(field_titulo);
    }
</script>

<div class="div2">
    
    <div class="btn-group" role="group" aria-label="...">
        <div class="w4 btn btn-info">
            <?= $titulo_filtro ?>
        </div>
        
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                Controlador
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                <li>
                    <?= anchor("develop/acl_recursos", 'Todos', 'role="menuitem"') ?>
                </li>
                <?php foreach ($controladores->result() as $row_controlador) : ?>
                    <li role="presentation">
                        <?= anchor("develop/acl_recursos/{$row_controlador->slug}", $row_controlador->item, 'role="menuitem"') ?>
                    </li>
                <?php endforeach ?>
            </ul>
        </div>
    </div>
</div>

<?= $output; ?>