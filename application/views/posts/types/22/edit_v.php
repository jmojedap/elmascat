<?php $this->load->view('assets/summernote') ?>

<?php
    $arr_fields = array(
        'code',
        'estado',
        'slug',
        /*'keywords',*/
        'padre_id',
        /*'place_id',*/
        'referente_1_id',
        'texto_1',
        'texto_2',
        'imagen_id'
    );
?>

<script>
    var post_id = <?= $row->id ?>;

    $(document).ready(function(){
        $('#field-contenido').summernote({
            lang: 'es-ES',
            height: 300
        });

        $('#post_form').submit(function(){
            update_post();
            return false;
        });

// Funciones
//-----------------------------------------------------------------------------
    function update_post(){
        $.ajax({        
            type: 'POST',
            url: url_app + 'posts/save/',
            data: $('#post_form').serialize(),
            success: function(response){
                if ( response.status == 1 )
                {
                    toastr['success']('Guardado');
                }
            }
        });
    }
    });
</script>

<div id="edit_post">
    <form accept-charset="utf-8" method="POST" id="post_form">
        <input type="hidden" name="id" value="<?= $row->id ?>">
        <div class="row">
            <div class="col-md-7">
                <div class="form-group">
                    <label for="resumen">resumen</label>
                    <textarea name="resumen" id="field-resumen" rows="3" class="form-control"><?= $row->resumen ?></textarea>
                </div>


                <div class="form-group">
                    <label for="contenido" class="form-control-label">contenido</label>
                    <textarea name="contenido" id="field-contenido" class="form-control"><?= $row->contenido ?></textarea>
                </div>

                <div class="form-group">
                    <label for="content_json">contenido json</label>
                    <textarea name="content_json" id="field-content_json" rows="3" class="form-control"><?= $row->content_json ?></textarea>
                </div>


            </div>
            <div class="col-md-5">
                <div class="form-group row">
                    <div class="col-md-8 offset-md-4">
                        <button class="btn btn-success btn-block" type="submit">
                            <i class="fa fa-save"></i>
                            Guardar
                        </button>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="nombre_post" class="col-md-4 col-form-label text-right">Nombre post</label>
                    <div class="col-md-8">
                        <input
                            type="text"
                            name="nombre_post"
                            required
                            class="form-control"
                            placeholder="post name"
                            title="post name"
                            value="<?= $row->nombre_post ?>"
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="tipo_id" class="col-md-4 col-form-label text-right">ID Tipo</label>
                    <div class="col-md-8">
                        <?= form_dropdown('tipo_id', $options_type, $row->tipo_id, 'class="form-control"') ?>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="publicado" class="col-md-4 col-form-label text-right">Fecha publicación</label>
                    <div class="col-md-8">
                        <input
                            type="text"
                            id="field-publicado"
                            name="publicado"
                            required
                            class="form-control"
                            placeholder="Fecha publicación"
                            title="Fecha publicación"
                            value="<?= $row->publicado ?>"
                            >
                    </div>
                </div>

                <?php foreach ( $arr_fields as $field ) { ?>
                    <div class="form-group row">
                        <label for="<?= $field ?>" class="col-md-4 col-form-label text-right"><?= str_replace('_',' ',$field) ?></label>
                        <div class="col-md-8">
                            <input
                                type="text"
                                name="<?= $field ?>"
                                class="form-control"
                                title="<?= $field ?>"
                                value="<?= $row->$field ?>"
                                >
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </form>
</div>