<?php $this->load->view('assets/lightbox2') ?>

<div id="pedido_info">
    <div class="mb-2">
        <a href="<?= URL_ADMIN . "pedidos/reporte/{$row->id}" ?>" class="btn btn-light w120p" target="_blank" title="Imprimir reporte resumen">
            <i class="fa fa-print"></i> Resumen
        </a>
        <a href="<?= URL_ADMIN . "pedidos/reporte/{$row->id}/label" ?>" class="btn btn-light w120p" target="_blank" title="Imprimir label envío">
            <i class="fa fa-print"></i> Label
        </a>
        <?php if ( $row->codigo_respuesta_pol != 1 ) { ?>
            <a href="<?= URL_ADMIN . "pedidos/link_pago/{$row->cod_pedido}" ?>" class="btn btn-success" target="_blank" title="Link para iniciar proceso de pago">
                <i class="fa fa-link"></i>
                Link de pago
            </a>
        <?php } ?>
        <?php if ( $row->payed != 1 && $this->session->userdata('role') <= 1 ) { ?>
            <button class="btn btn-light" title="Reiniciar el pedido para intentar nuevamente el pago" id="btn_reiniciar_pedido" v-on:click="reiniciar_pedido">
                <i class="fa fa-sync-alt"></i> Reiniciar
            </button>
        <?php } ?>
        <?php if ( $this->session->userdata('role') == 0 && $this->session->userdata('logged') ) { ?>
            <a href="<?= URL_ADMIN . "admin/tablas/pedido/edit/{$row->id}" ?>" class="btn btn-light w120p" title="Edición del registro en la base de datos" target="_blank">
                <i class="fa fa-edit"></i> Editar Row
            </a>
        <?php } ?>
    </div>

    <div class="row mb-2">
        <div class="col col-md-4">
            <div class="card card-default">
                <div class="card-header">
                    <i class="fa fa-user"></i>
                    Cliente
                </div>

                <table class="table table-condensed">
                    <tbody>
                        <tr>
                            <td>Ref. venta</td>
                            <td>{{ order.cod_pedido }}</td>
                        </tr>
                        
                        <tr>
                            <td>Usuario</td>
                            <td>
                                <?php if ( $row->usuario_id > 0 ) { ?>
                                    <a v-bind:href="`<?= URL_ADMIN . 'usuarios/profile/' ?>` + order.usuario_id">
                                        <?= $this->App_model->nombre_usuario($row->usuario_id, 'na') ?>
                                    </a>
                                <?php } ?>
                            </td>
                        </tr>

                        <tr>
                            <td>A nombre de</td>
                            <td>{{ order.nombre }} {{ order.apellidos }}</td>
                        </tr>
                        
                        <tr>
                            <td>Documento</td>
                            <td>
                                <span class="text-muted"><?= $this->Item_model->nombre(53, $row->tipo_documento_id) ?></span>
                                {{ order.no_documento }}
                            </td>
                        </tr>
                        
                        <tr>
                            <td>E-mail</td>
                            <td>{{ order.email }}</td>
                        </tr>
                        
                        <tr>
                            <td>Creado</td>
                            <td title="<?= $row->creado ?>">
                                {{ order.creado | date_format }}
                                &middot; {{ order.creado | ago }}
                            </td>
                        </tr>
                        
                        <tr>
                            <td>Total $</td>
                            <td> 
                                <strong>
                                    {{ order.valor_total | currency }}
                                </strong>
                                <span class="text-muted">
                                    =
                                    (
                                    {{ order.total_productos | currency }} + 
                                    {{ order.total_extras | currency }}
                                    )
                                </span>
                            </td>
                        </tr>
                        
                        
                    </tbody>
                        
                </table>
            </div>
        </div>
        <div class="col col-md-4">
            <div class="card card-default mb-2">
                <div class="card-header">
                    <i class="fas fa-truck"></i>
                    Entrega
                </div>

                <table class="table table-condensed">
                    <tbody>
                        <tr>
                            <td>Ciudad</td>
                            <td>{{ order.ciudad }}</td>
                        </tr>
                        <tr v-show="order.shipping_method_id != 98">
                            <td>Dirección</td>
                            <td>{{ order.direccion }}</td>
                        </tr>
                        <tr v-show="order.shipping_method_id == 98">
                            <td>Método de entrega</td>
                            <td>
                                <strong class="text-danger">No se envía &middot; Se recoge en tienda</strong>
                            </td>
                        </tr>
                        <tr v-show="order.shipping_method_id == 98">
                            <td>Dirección facturación</td>
                            <td>
                                {{ order.direccion }}
                            </td>
                        </tr>
                        <tr>
                            <td>Celular</td>
                            <td>{{ order.celular }}</td>
                        </tr>
                        <tr>
                            <td>Notas</td>
                            <td>{{ order.notas }}</td>
                        </tr>
                        <tr>
                            <td>Peso</td>
                            <td>{{ order.peso_total }} kg</td>
                        </tr>
                    </tbody>
                        
                </table>
            </div>

            <?php if ( $row->is_gift ) : ?>
                <div class="card card-default">
                    <div class="card-header">
                        <i class="fa fa-gift text-danger"></i>
                        Este pedido es un regalo
                        <span class="badge badge-success">Atención</span>
                    </div>

                    <table class="table table-condensed">
                        <tbody>
                            <tr>
                                <td>De</td>
                                <td><?= $arr_meta['regalo']['de'] ?></td>
                            </tr>
                            <tr>
                                <td>Para</td>
                                <td><?= $arr_meta['regalo']['para'] ?></td>
                            </tr>
                            <tr>
                                <td>Mensaje</td>
                                <td><?= $arr_meta['regalo']['mensaje'] ?></td>
                            </tr>
                        </tbody>
                            
                    </table>
                </div>
            <?php endif; ?>

        </div>
        <div class="col col-md-4">
            <div class="card card-default">
                <div class="card-header">
                    Gestión
                </div>

                <table class="table table-condensed">
                    <tbody>
                        <tr class="info">
                            <td>Pagado</td>
                            <td>
                                <div class="d-flex">
                                    <div class="mr-2">
                                        <span v-if="order.payed == 1">
                                            <i class="fa fa-check-circle text-success"></i>
                                            <strong class="text-success">Sí</strong>
                                        </span>
                                        <span class="text-muted" v-else="order.payed">
                                            <i class="fa fa-info-circle text-warning"></i>
                                            No
                                        </span>
                                    </div>
                                    <div>
                                        <?php if ( $row->payment_channel ) : ?>
                                            <i class="fa fa-circle channel_<?= $row->payment_channel ?>"></i>
                                            <?= $this->Item_model->nombre(106, $row->payment_channel) ?>    
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="info">
                            <td>Estado pedido</td>
                            <td>
                                <b>
                                    <?= $this->Item_model->nombre(7, $row->estado_pedido) ?>
                                </b>
                            </td>
                        </tr>
                        <tr>
                            <td>Editado</td>
                            <td>
                                <?= $this->Pcrn->fecha_formato($row->editado, 'd-M h:i') ?> &middot;
                                Hace <?= $this->Pcrn->tiempo_hace($row->editado) ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Por</td>
                            <td><?= $this->App_model->nombre_usuario($row->editado_usuario_id, 2) ?></td>
                        </tr>
                        <tr>
                            <td>Factura</td>
                            <td>
                                <?= $row->factura ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Método de entrega</td>
                            <td>
                                <?= $this->Item_model->name(183, $row->shipping_method_id); ?>
                                &middot;
                                <small class="text-muted">Guía:</small>
                                <?= $row->no_guia ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Anotación</td>
                            <td>
                                <?= $row->notas_admin ?>
                            </td>
                        </tr>
                    </tbody>
                        
                </table>
            </div>
        </div>
    </div>

    <?php $this->load->view('pedidos/info/productos_v') ?>
    <?php $this->load->view('pedidos/info/extras_v') ?>
</div>

<?php $this->load->view('pedidos/info/vue_v') ?>