<?php 
    $att_form = array(
        'class' => 'form-horizontal'
    );
    
    $att_submit = array(
        'class'  => 'btn btn-success btn-block',
        'value'  => 'Actualizar'
    );
?>

<?php $this->load->view('productos/editar/jquery_scripts_v') ?>

<div class="panel panel-default">
    <div class="panel-body">
        <form accept-charset="utf-8" method="POST" id="producto_form" class="form-horizontal">
            <div class="form-group">
                <label class="col-md-4 control-label" for=""></label>
                <div class="col-md-8">
                    <?= form_submit($att_submit) ?>
                </div>
            </div>
            <?php foreach ($metacampos->result() as $row_meta) : ?>
                <?php
                    $value = $this->Meta_model->valor(3100, $row_meta->meta_id, $producto_id);

                    //Configurar campo
                        $att_meta = array(
                            'id'     => 'field-meta_' . $row_meta->meta_id,
                            'name'   => 'meta_' . $row_meta->meta_id,
                            'class'  => 'form-control',
                            'value'  => $value,
                            'placeholder'   => $row_meta->descripcion,
                            'title'   => $row_meta->descripcion
                        );
                ?>
                <div class="form-group">
                    <label class="col-sm-4 control-label" for="<?= $att_meta['name'] ?>">
                        <?= $row_meta->nombre_meta ?>
                    </label>
                    <div class="col-sm-8">
                        <?= form_input($att_meta) ?>
                    </div>
                </div>
            <?php endforeach ?>
        </form>
    </div>
</div>
