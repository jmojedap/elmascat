<?php
    $src_alt = $src_alt = URL_IMG . 'app/250px_producto.png';   //Imagen alternativa

    $att_img['src'] = RUTA_UPLOADS . $row_archivo->carpeta . '500px_' . $row_archivo->nombre_archivo;
    $att_img['onError'] = "this.src='" . $src_alt . "'" //Imagen alternativa
?>

<div class="row">
    <div class="col col-sm-3">
        <div class="thumbnail">
            <?= img($att_img) ?>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading">
                Registro
            </div>
            <div class="panel-body">
                <dl class="dl-horizontal">
                    <dt>Creado</dt>
                    <dd>
                        <?= $this->Pcrn->fecha_formato($row->creado, 'Y-M-d') ?>
                        <span class="suave">
                            
                        </span>
                    </dd>
                    
                    <dt>Hace</dt>
                    <dd><?= $this->Pcrn->tiempo_hace($row->creado) ?></dd>
                    
                    <dt>Por</dt>
                    <dd><?= $this->App_model->nombre_usuario($row->usuario_id, 2) ?></dd>
                    
                    <dt>Puntaje</dt>
                    <dd><?= $row->puntaje ?>/100</dd>
                    
                </dl>
            </div>
        </div>
        
        
        
        
    </div>
    <div class="col col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                Información
            </div>
            <div class="panel-body">
                <h3>General</h3>
                
                <dl class="dl-horizontal">
                    <dt>Nombre</dt>
                    <dd><?= $row->nombre_producto ?></dd>

                    <dt>Referencia</dt>
                    <dd><?= $row->referencia ?></dd>

                    <dt>Descripción</dt>
                    <dd><?= $row->descripcion ?></dd>

                    <dt>Precio</dt>
                    <dd>
                        <span class="resaltar" style="font-size: 1.5em">
                            <?= $this->Pcrn->moneda($row->precio) ?>
                        </span>
                    </dd>

                    <dt>Disponibles</dt>
                    <dd><?= $row->cant_disponibles ?></dd>

                    <dt>Fabricante</dt>
                    <dd><?= $this->App_model->nombre_item($row->fabricante_id) ?></dd>
                </dl>
                
                <h3>Detalles</h3>
                
                <dl class="dl-horizontal">
                    <?php foreach ($metadatos->result() as $row_metadato) : ?>
                        <dt><?= $row_metadato->nombre_metadato ?></dt>
                        <dd>
                            <?= $row_metadato->valor ?>
                        </dd>    
                    <?php endforeach ?>
                </dl>
            </div>
            
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading">
                Historial Edición de Información
            </div>
            <div class="panel-body">
                <table class="table table-default">
                    <thead>
                        <th>Fecha</th>
                        <th>Hace</th>
                        <th>Usuario</th>
                    </thead>

                    <tbody>
                        <?php foreach($ediciones->result() as $row_edicion) : ?>
                        <tr>
                            <td>
                                <?= $row_edicion->fecha ?>
                            </td>
                            <td>
                                <?= $this->Pcrn->tiempo_hace($row_edicion->fecha, TRUE) ?>
                            </td>
                            <td>
                                <?= anchor("usuarios/profile{$row_edicion->usuario_id}", $this->App_model->nombre_usuario($row_edicion->usuario_id, 2)) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>

                    </tbody>
                </table>
            </div>
        </div>
        
        
    </div>
    <div class="col col-sm-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                Etiquetas
            </div>
            <div class="panel-body">
                <ul>
                    <?php foreach ($tags->result() as $row_tag) : ?>
                        <?php
                            $link = "productos/explorar/?tag={$row_tag->id}";
                        ?>
                        <li>
                            <?= anchor($link, $row_tag->nombre_tag) ?>
                        </li>
                    <?php endforeach ?>
                </ul>
            </div>
        </div>
    </div>
</div>

