<?php
    //$arr_respuesta_pol = json_decode($row_meta->valor, TRUE);
    
    $destacadas = array(
        'valor',
        'mensaje_respuesta_pol',
        'firma',
        'codigo_respuesta_pol',
    );
    
    $clase_alert = 'alert alert-info';
    $texto_mensaje = 'No se ha registrado confirmación de PagosOnLine';
    
    if ( count($arr_respuesta_pol) > 0 )
    {
        $texto_mensaje = $arr_respuesta_pol['mensaje_respuesta_pol'];
        
        switch ( $arr_respuesta_pol['codigo_respuesta_pol'] ) {
            case '1':
                $clase_alert = 'alert alert-success';
                break;
            default:
                $clase_alert = 'alert alert-warning';
                break;
        }
    }
    
    //Validaciones
        //Firma de Confirmación
    
        $clase_firma = 'success';
        $texto_firma = '<i class="fa fa-check"></i> Ok';
        if ( $arr_respuesta_pol['firma'] != $firma_pol_confirmacion )
        {
            $clase_firma = 'danger';
            $texto_firma = '<i class="fa fa-times"></i> Error';
        }
        
        //Valor de la transacción
        
?>

<div class="<?= $clase_alert ?>" role="alert">
    <p>
        <i class="fa fa-info-circle"></i>
        <?= $texto_mensaje ?>
    </p>
</div>

<div class="row">
    <div class="col col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                Resumen Respuesta POL
            </div>
            <div class="panel-body">
                <?php if ( count($arr_respuesta_pol) > 0 ) { ?>
                    <table class="table table-condensed">
                        <thead>
                            <th>Variable</th>
                            <th>Valor</th>
                        </thead>

                        <tbody>
                            <tr>
                                <td>Respuesta</td>
                                <td><?= $arr_respuesta_pol['mensaje_respuesta_pol'] ?></td>
                            </tr>
                            <tr class="<?= $clase_firma ?>">
                                <td>Firma POL Respuesta</td>
                                <td>
                                    Firma Recibida: <?= $arr_respuesta_pol['firma'] ?><br/>
                                    Firma Generada: <?= $firma_pol_confirmacion ?><br/>
                                    <?= $texto_firma ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Valor</td>
                                <td>
                                    <div class="row">
                                        <div class="col col-sm-6">
                                            Valor POL:
                                        </div>
                                        <div class="col col-sm-6">
                                            <?= $arr_respuesta_pol['valor'] ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col col-sm-6">
                                            Valor Pedido:
                                        </div>
                                        <div class="col col-sm-6">
                                            <?= number_format($row->valor_total,2,'.','') ?>
                                        </div>
                                    </div>

                                    
                                </td>
                            </tr>
                            <tr class="">
                                <td>Estado POL</td>
                                <td>
                                    <?= $this->App_model->nombre_item($arr_respuesta_pol['estado_pol'], 1, 9) ?>
                                </td>
                            </tr>
                            <tr class="">
                                <td>Tipo medio de pago</td>
                                <td>
                                    <?= $this->App_model->nombre_item($arr_respuesta_pol['tipo_medio_pago'], 1, 11) ?>
                                </td>
                            </tr>
                            <tr class="">
                                <td>Medio de pago</td>
                                <td>
                                    <?= $this->App_model->nombre_item($arr_respuesta_pol['medio_pago'], 1, 12) ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="col col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                Detalle Respuesta POL
            </div>
            <div class="panel-body">
                

                <?php if ( count($arr_respuesta_pol) > 0 ) { ?>
                    <table class="table table-condensed">
                        <thead>
                            <th class="">Variable</th>
                            <th class="">Valor</th>
                        </thead>

                        <tbody>
                            <?php foreach ($arr_respuesta_pol as $key => $valor_campo) : ?>
                                <?php
                                    $clase_fila = '';
                                    if (in_array($key, $destacadas) ) { $clase_fila = 'info'; }
                                ?>
                                <tr class="<?= $clase_fila ?>">
                                    <td><?= $key ?></td>
                                    <td><?= $valor_campo ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

