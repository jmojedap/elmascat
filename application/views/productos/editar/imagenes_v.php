<?php

    $att_archivo = array(
        'name' => 'archivo',
        'required' => TRUE
    );

    $att_submit = array(
        'class' =>  'btn btn-success',
        'value' =>  'Subir'
    );
?>

<script>
    //Variables
    var base_url = '<?= base_url() ?>';
    var meta_id = 0;

    $(document).ready(function(){
        $('.eliminar_img').click(function(){
            meta_id = $(this).data('meta_id');
            eliminar_img(meta_id);
        });
    });

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

<table class="table table-responsive bg-blanco">
    <thead>
        <th width="120px">Imágen</th>
        <td></td>
        <th width="70px">
            
        </th>
    </thead>
    <tbody>
        
        <?= form_open_multipart("productos/agregar_img/{$row->id}", $att_form) ?>
            <tr class="info">
                <td>Nueva <i class="fa fa-caret-right"></i></td>
                <td><?= form_upload($att_archivo) ?></td>
                <td>
                    <?= form_submit($att_submit) ?>
                </td>
            </tr>
        <?= form_close('') ?>
        
        <?php foreach ($imagenes->result() as $row_imagen) : ?>
            <?php
                $att_img['src'] = URL_UPLOADS . $row_imagen->carpeta . '500px_' . $row_imagen->nombre_archivo;
                $att_img['width'] = '100px';
                $att_img['height'] = '100px';
                $att_img['class'] = 'img-thumbnail';
            ?>
            <tr id="archivo_<?= $row_imagen->meta_id ?>">
                <td>
                    <?= img($att_img) ?>
                </td>
                <td>
                    <?php if ( ($row_imagen->archivo_id - $row->imagen_id) != 0 ){ ?>
                        <?= anchor("productos/establecer_img/{$row->id}/{$row_imagen->archivo_id}", 'Establecer principal', 'class="btn btn-default" title="Establecer esta imagen como la principal"') ?>
                    <?php } else { ?>
                        <span class="label label-info">Imagen principal</span>
                    <?php } ?>
                </td>
                <td>
                    <button class="btn btn-sm btn-warning eliminar_img pull-right" data-meta_id="<?= $row_imagen->meta_id ?>">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>