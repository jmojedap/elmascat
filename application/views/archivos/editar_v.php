<?php $this->load->view('assets/chosen_jquery'); ?>

<?php
    $att_form = array(
        'class' => 'form-horizontal'
    );

    $att_titulo_archivo = array(
        'id'     => 'campo-titulo_archivo',
        'name'   => 'titulo_archivo',
        'required'   => TRUE,
        'type'   => 'text',
        'class'  => 'form-control',
        'value'  => $row->titulo_archivo,
        'placeholder'   => 'Escriba el título para el archivo',
        'title'   => 'Escriba un título para el archivo'
    );
    
    $att_subtitulo = array(
        'id'     => 'campo-subtitulo',
        'name'   => 'subtitulo',
        'type'   => 'text',
        'class'  => 'form-control',
        'value'  => $row->subtitulo,
        'placeholder'   => 'Escriba un subtítulo',
        'title'   => 'Escriba un subtítulo para el archivo'
    );
    
    $att_palabras_clave = array(
        'id'     => 'campo-palabras_clave',
        'name'   => 'palabras_clave',
        'required'   => TRUE,
        'type'   => 'text',
        'class'  => 'form-control',
        'value'  => $row->palabras_clave,
        'placeholder'   => 'Palabras claves que describen al archivo, útil para las búsquedas',
        'title'   => 'Palabras claves que describen al archivo, útil para las búsquedas'
    );
    
    $att_descripcion = array(
        'id'     => 'campo-descripcion',
        'name'   => 'descripcion',
        'type'   => 'text',
        'class'  => 'form-control',
        'value'  => $row->descripcion,
        'placeholder'   => 'Descripción del archivo',
        'title'   => 'Descripción del archivo',
        'rows'   => 3
    );
    
    $att_link = array(
        'id'     => 'campo-link',
        'name'   => 'link',
        'type'   => 'url',
        'class'  => 'form-control',
        'value'  => $row->link,
        'placeholder'   => 'Link destino al hacer clic en la imagen',
        'title'   => 'Link de destino al hacer clic en la imagen',
        'rows'   => 3
    );
    
    $att_submit = array(
        'class' => 'btn btn-primary',
        'value' => 'Guardar'
    );
?>



<div class="row">
    <div class="col col-sm-8">
        <div class="panel panel-default">
            <div class="panel-body">
                <?= form_open($destino_form, $att_form) ?>
                    <div class="form-group">
                        <label for="titulo_archivo" class="col-sm-3 control-label">Título archivo</label>
                        <div class="col-sm-9">
                            <?= form_input($att_titulo_archivo); ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="subtitulo" class="col-sm-3 control-label">Subtítulo</label>
                        <div class="col-sm-9">
                            <?= form_input($att_subtitulo); ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="palabras_clave" class="col-sm-3 control-label">Palabras clave *</label>
                        <div class="col-sm-9">
                            <?= form_input($att_palabras_clave); ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="descripcion" class="col-sm-3 control-label">Descripción</label>
                        <div class="col-sm-9">
                            <?= form_textarea($att_descripcion); ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="link" class="col-sm-3 control-label">Link</label>
                        <div class="col-sm-9">
                            <?= form_input($att_link); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                            <?= form_submit($att_submit) ?>
                        </div>
                    </div>
                <?= form_close('') ?>
                
            </div>
        </div>    
        <?php $this->load->view('comunes/resultado_proceso_v'); ?>
    </div>
    
    <div class="col col-sm-4">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="sep1">
                    <?= img($att_img) ?>
                </div>
                <?= anchor("archivos/cambiar/{$row->id}", 'Cambiar', 'class="btn btn-default" title="Cambiar esta imagen"') ?>
            </div>
            
        </div>
    </div>
</div>



