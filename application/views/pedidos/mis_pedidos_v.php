<?php
    //Clases columnas
        $clases_col['cod'] = '';
        $clases_col['cliente'] = 'hidden-xs';
        $clases_col['estado'] = 'hidden-xs';
        $clases_col['creado'] = 'hidden-xs hidden-sm';
        $clases_col['creado_hace'] = 'hidden-xs';
        $clases_col['valor'] = 'hidden-xs';
?>


<div class="row">
    <div class="col-md-12">
        <div class="nav-tabs-custom">
            
            <ul class="nav nav-tabs">
                <li class="active">
                    <?= anchor("pedidos/mis_pedidos/", 'Mis pedidos') ?>
                </li>
            </ul>
            
            <div class="tab-content">
                <div class="tab-pane active">
                    <table class="table table-responsive" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="100px;" class="">CÓD</th>
                                <th class="">Datos pedido</th>
                                <th class="<?= $clases_col['cliente'] ?>">Nombre Cliente</th>
                                <th class="<?= $clases_col['estado'] ?>">Estado</th>
                                <th class="<?= $clases_col['creado'] ?>">Creado</th>
                                <th class="<?= $clases_col['creado_hace'] ?>">Hace</th>
                                <th class="<?= $clases_col['valor'] ?> info">Valor total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($resultados->result() as $row_resultado) { ?>
                                <?php
                                    //Variables
                                    $nombre_pedido = $row_resultado->cod_pedido;
                                    $link_pedido = anchor("pedidos/estado/?cod_pedido={$row_resultado->cod_pedido}", $nombre_pedido, 'class="btn btn-info"');
                                    $editable = $this->Pedido_model->editable($row_resultado->id);
                                    $estado_pedido = $this->App_model->nombre_item($row_resultado->estado_pedido, 1, 7);
                                    $creado_hace = $this->Pcrn->tiempo_hace($row_resultado->creado);

                                    //Checkbox
                                    $att_check = array(
                                        'class' => 'check_registro',
                                        'data-id' => $row_resultado->id,
                                        'checked' => FALSE
                                    );
                                ?>
                                <tr>
                                    <td class=""><span class="etiqueta primario w1"><?= $link_pedido ?></span></td>
                                    <td class="">
                                        <?php if ( $row_resultado->estado_pedido == 1 ){ ?>
                                            <?= anchor("pedidos/retomar/{$row_resultado->cod_pedido}", 'Continuar', 'class="btn btn-success" title="Retomar el pedido"') ?>
                                            <br/>
                                        <?php } ?>
                                        
                                        <span><?= $estado_pedido ?></span>
                                        <br/>
                                        <span>$<?= number_format($row_resultado->valor_total, 0, ',', '.') ?></span>
                                        <br/>
                                        <span> Hace 
                                            <?= $creado_hace ?>
                                        </span>
                                        
                                        
                                    </td>
                                    <td class="<?= $clases_col['cliente'] ?>">
                                        <?= $row_resultado->nombre . ' ' . $row_resultado->apellidos ?>
                                    </td>
                                    <td class="<?= $clases_col['estado'] ?>">
                                        <?= $estado_pedido ?>
                                        <?php if ( $row_resultado->estado_pedido == 1 ){ ?>
                                            <?php //anchor("pedidos/retomar/{$row_resultado->cod_pedido}", 'Continuar', 'class="btn btn-success" title="Retomar el pedido"') ?>
                                        <?php } ?>
                                    </td>
                                    <td class="<?= $clases_col['creado'] ?>"><?= $this->Pcrn->fecha_formato($row_resultado->creado, 'Y-M-d') ?></td>
                                    <td class="<?= $clases_col['creado_hace'] ?>"><?= $creado_hace ?></td>
                                    <td class="<?= $clases_col['valor'] ?> info">$<?= number_format($row_resultado->valor_total, 0, ',', '.') ?></td>
                                </tr>

                            <?php } //foreach  ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
</div>