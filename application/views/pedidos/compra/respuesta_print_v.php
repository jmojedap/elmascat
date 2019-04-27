



<?php
    //Evitar errores de definición de variables e índices de arrays, 2013-12-07
        ini_set('display_errors', 1);
        ini_set('error_reporting', E_ERROR);
        
    //Imagen logo
        $att_img['src'] = RUTA_IMG . 'app/logo_impresion.png';
        $att_img['width'] = '50%';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <![endif]-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <title><?= $titulo_pagina ?></title>
        <link rel="shortcut icon" href="<?= URL_IMG ?>app/icono.png" type="image/ico" />
        
        <?php $this->load->view('assets/jquery'); ?>
        <?php $this->load->view('assets/bootstrap'); ?>
        
        <?php $this->load->view('plantillas/polo/partes/head'); ?>

        <link rel="stylesheet" href="<?= base_url() ?>css/polo_add.css" type="text/css">
        
        <script>
            $(document).ready(function(){
                window.print();
            });
        </script>
    </head>

    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <?= img($att_img) ?>

                            <h4>Districatólicas Unidas S.A.S.</h4>
                            <hr/>

                            <div class="page-title">
                                <h2 class="title">Resultado transacción</h2>
                            </div>



                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>Respuesta Pagos On Line</td>
                                        <td><?= $this->App_model->nombre_item($arr_respuesta_pol['codigo_respuesta_pol'], 2, 10) ?></td>
                                    </tr>
                                    <tr>
                                        <td>Cód. Transacción en Pagos On Line</td>
                                        <td><?= $arr_respuesta_pol['ref_pol'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>Valor</td>
                                        <td>
                                            <?= $arr_respuesta_pol['moneda'] ?>
                                            <?= number_format($arr_respuesta_pol['valor'],2, ',', '.') ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Fecha transacción</td>
                                        <td><?= $arr_respuesta_pol['fecha_procesamiento'] ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="page-title">
                <h2 class="title">Datos de su compra</h2>
            </div>
            
            <div class="row">
                <div class="col-md-4 col-xs-4">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            
                            <dl>
                                <dt>Cód. Pedido</dt>
                                <dd><?= $row->cod_pedido ?></dd>

                                <dt>Nombre</dt>
                                <dd><?= $row->nombre . ' ' . $row->apellidos ?></dd>

                                <dt>No. documento</dt>
                                <dd><?= $row->no_documento ?></dd>

                                <dt>E-mail</dt>
                                <dd><?= $row->email ?></dd>

                                <dt>Dirección</dt>
                                <dd>
                                    <?= $row->direccion ?>
                                    <?php if ( strlen($row->direccion_detalle) ){ ?>
                                        <br/>
                                        <?= $row->direccion_detalle ?>
                                    <?php } ?>
                                    <br/>
                                    <?= $row->ciudad ?>
                                </dd>


                                <dt>Teléfonos</dt>
                                <dd>
                                    <?= $row->telefono ?> - <?= $row->celular ?>
                                </dd>


                                <dt>Notas</dt>
                                <dd>
                                    <?= $row->notas ?>
                                </dd>


                                <dt>Subtotal Productos</dt>
                                <dd>
                                    <?= $this->Pcrn->moneda($row->total_productos) ?>
                                </dd>


                                <dt>Gastos de envío</dt>
                                <dd>
                                    <?= $this->Pcrn->moneda($row->total_extras) ?>
                                </dd>

                                <dt>Valor total</dt>
                                <dd>
                                    <b>
                                        <?= $this->Pcrn->moneda($row->valor_total) ?>
                                    </b>
                                </dd>
                              </dl>

                            
                        </div>
                    </div>
                </div>

                <div class="col-md-8 col-xs-8">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="text-center">
                                <td>Producto</td>
                                <td>$/Und</td>
                                <td>Cant</td>
                                <td>Total</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($detalle->result() as $row_detalle) : ?>
                                <?php
                                $precio_detalle = $row_detalle->cantidad * $row_detalle->precio;
                                $peso_detalle = $row_detalle->cantidad * $row_detalle->peso;
                                ?>
                                <tr>
                                    <td class="">
                                        <?=$row_detalle->nombre_producto  ?>
                                    </td>
                                    <td class="text-right">
                                        <?= $this->Pcrn->moneda($row_detalle->precio) ?>
                                    </td>
                                    <td class="text-center">
                                        <?= $row_detalle->cantidad ?>
                                    </td>
                                    <td class="text-right">
                                        <?= $this->Pcrn->moneda($precio_detalle) ?>
                                    </td>

                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
                
            </div>
        </div>
    </body>
</html>

