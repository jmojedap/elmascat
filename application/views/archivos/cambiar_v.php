<?php
    $att_form = array(
        'class' => 'form-horizontal'
    );

    $att_archivo = array(
        'name' => 'file_field',
        'required' => TRUE
    );

    $att_submit = array(
        'class' =>  'btn btn-info w3',
        'value' =>  'Cargar'
    );
?>

<div class="alert alert-warning">
    <i class="fa fa-info-circle"></i> 
    Al cargar un nuevo archivo, el archivo actual se eliminará. Los datos de descripción del archivo se conservarán sin cambios.
</div>

<div class="panel panel-default">
    <div class="panel-body">
        <?= form_open_multipart($destino_form, $att_form) ?>
            <div class="form-group">
                <label for="archivo" class="col-sm-2 control-label">Archivo</label>
                <div class="col-sm-10">
                    <?= form_upload($att_archivo) ?>
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