<?php
    $src_alt = $src_alt = URL_IMG . 'app/250px_producto.png';   //Imagen alternativa

    $att_img['src'] = RUTA_UPLOADS . $row_archivo->carpeta . '250px_' . $row_archivo->nombre_archivo;
    $att_img['onError'] = "this.src='" . $src_alt . "'" //Imagen alternativa
?>

<div class="box box-info">
    <div class="box-body">
        <div class="row">
            <div class="col-md-3">
                <div class="thumbnail">
                    <?= img($att_img) ?>
                </div>
            </div>

            <div class="col-md-6">

                <dl class="dl-horizontal">
                    <dt>Nombre</dt>
                    <dd><?= $row->nombre_producto ?></dd>
                </dl>
                <dl class="dl-horizontal">
                    <dt>Descripción</dt>
                    <dd><?= $row->descripcion ?></dd>
                </dl>
                <dl class="dl-horizontal">
                    <dt>Precio</dt>
                    <dd>
                        <span class="resaltar" style="font-size: 2em">
                            $ <?= $arr_precio['precio'] ?>
                        </span>
                    </dd>
                </dl>

                <dl class="dl-horizontal">
                    <dt>Creado por</dt>
                    <dd><?= $this->App_model->nombre_usuario($row->usuario_id, 2) ?></dd>
                </dl>

                <dl class="dl-horizontal">
                    <dt>Creado</dt>
                    <dd>
                        <?= $this->Pcrn->fecha_formato($row->creado, 'Y-M-d') ?>
                        <span class="suave">
                        <?= $this->Pcrn->tiempo_hace($row->creado, TRUE) ?>
                        </span>
                    </dd>
                </dl>

                <dl class="dl-horizontal">
                    <dt>Editado</dt>
                    <dd>
                        <?= $this->Pcrn->fecha_formato($row->editado, 'Y-M-d') ?>
                        <span class="suave">
                        <?= $this->Pcrn->tiempo_hace($row->editado, TRUE) ?>
                        </span>
                    </dd>
                </dl>

            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6"> 
                <?php foreach ($palabras_clave->result() as $row_palabra) : ?>
                    <span class="label label-info" style="margin-right: 2px;">
                        <?= $row_palabra->palabra ?>
                    </span>
                <?php endforeach ?>
            </div>

            <div class="col-md-6">

            </div>
        </div>


    </div>

</div>

