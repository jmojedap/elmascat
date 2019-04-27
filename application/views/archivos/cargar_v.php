<?php
    $att_form = array(
        'class' => 'form-horizontal'
    );

    $att_archivo = array(
        'name' => 'archivo'
    );
    
    $att_palabras_clave = array(
        'id'     => 'campo-palabras_clave',
        'name'   => 'palabras_clave',
        'required'   => TRUE,
        'class'  => 'form-control',
        'placeholder'   => 'Escriba las palabras clave del archivo',
        'title'   => 'Escriba las palabras clave del archivo'
    );

    $att_submit = array(
        'class' =>  'btn btn-info w3',
        'value' =>  'Cargar'
    );
?>

<script>
    //Variables
    var base_url = '<?= base_url() ?>';
    var meta_id = 0;
</script>

<script>
    $(document).ready(function(){
        $('.eliminar_img').click(function(){
            meta_id = $(this).data('meta_id');
            eliminar_img(meta_id);
        });
    });
</script>

<script>
    //Ajax, función para eliminar una imagen
    function eliminar_img(){
        $.ajax({        
            type: 'POST',
            url: base_url + 'productos/eliminar_img/' + meta_id,
            success: function(respuesta){
                if ( respuesta ) {
                    $('#archivo_' + meta_id).hide(); 
                }
            }
        });
    }
</script>

<?php $this->load->view($vista_menu); ?>

<div class="panel panel-default">
    <div class="panel-body">
        <?= form_open_multipart("archivos/cargar_e/{$row->id}", $att_form) ?>
            <div class="form-group">
                <label for="archivo" class="col-sm-2 control-label">Archivo *</label>
                <div class="col-sm-10">
                    <?= form_upload($att_archivo) ?>
                </div>
            </div>
            <div class="form-group">
                <label for="palabras_clave" class="col-sm-2 control-label">Palabras clave *</label>
                <div class="col-sm-10">
                    <?= form_input($att_palabras_clave) ?>
                </div>
            </div>
            
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <?= form_submit($att_submit) ?>
                </div>
            </div>
        <?= form_close('') ?>
    </div>
</div>


<?php $this->load->view('comunes/resultado_proceso_v'); ?>