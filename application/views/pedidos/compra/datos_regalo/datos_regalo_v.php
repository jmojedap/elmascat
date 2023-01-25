<div class="mb-2">
    <a href="<?= base_url("pedidos/compra_a") ?>" class="btn btn-polo w120p">
        <i class="fa fa-arrow-left"></i>
        ATRÁS
    </a>
</div>

<div id="datos_regalo_app">
    <div v-show="step == 'empaque'">
        <h3 class="text-center">
            Agrega el empaque del regalo
        </h3>
        <p class="text-center">Empaques agregados: <strong v-bind:class="{'text-danger': qty_empaques_in_cart == 0, 'text-primary': qty_empaques_in_cart > 0 }">{{ qty_empaques_in_cart }}</strong></p>
        <div class="my-2 text-center">
            <button class="btn btn-default w120p"
                v-on:click="no_es_regalo">
                NO ES REGALO
            </button>
            <button class="btn btn-primary w120p"
                v-show="qty_empaques_in_cart > 0"
                v-on:click="set_step('datos_tarjeta')">
                <i v-show="loading" class="fa fa-spin fa-spinner"></i>
                SIGUIENTE
            </button>
            <button class="btn btn-default w120p"
                v-show="qty_empaques_in_cart == 0" v-on:click="warning_no_empaques"
                >
                <i v-show="loading" class="fa fa-spin fa-spinner"></i>
                SIGUIENTE
            </button>
        </div>
        <table class="table bg-white">
            <tbody>
                <tr v-for="(empaque, key) in empaques" v-show="empaque.stock > 0"
                    v-bind:class="{'info': in_cart(empaque.id) }">
                    <td width="40px">
                        <a href="" data-toggle="modal" data-target="#modal_empaque" v-on:click="set_product(key)">
                            <img v-bind:src="empaque.url_thumbnail" class="rounded w40p" alt="Imagen empaque"
                                onError="this.src='<?= URL_IMG . 'app/262px_producto.png' ?>'">
                        </a>
                    </td>
                    <td>
                        <a href="" data-toggle="modal" data-target="#modal_empaque" v-on:click="set_product(key)">{{ empaque.name }}</a><br>
                        <p class="special-price">
                            <span class="price">
                                {{ empaque.price | currency }}
                            </span>
                        </p>
                    </td>
                    <td width="50px" class="text-center">
                        <button class="btn btn-primary" v-on:click="add_product(key)" v-if="!in_cart(empaque.id)">
                            <i class="fa fa-plus"></i>
                        </button>
                        <button class="btn btn-warning" v-on:click="delete_element(empaque.id)" v-else>
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="my-2 text-center">
            <button class="btn btn-default w120p"
                v-on:click="no_es_regalo">
                NO ES REGALO
            </button>
            <button class="btn btn-primary w120p"
                v-show="qty_empaques_in_cart > 0"
                v-on:click="set_step('datos_tarjeta')">
                <i v-show="loading" class="fa fa-spin fa-spinner"></i>
                SIGUIENTE
            </button>
            <button class="btn btn-default w120p"
                v-show="qty_empaques_in_cart == 0" v-on:click="warning_no_empaques"
                >
                <i v-show="loading" class="fa fa-spin fa-spinner"></i>
                SIGUIENTE
            </button>
        </div>
    </div>
    <div v-show="step == 'datos_tarjeta'">
        <h4 class="text-center">Dedicatoria del regalo</h4>
        <form method="post" accept-charset="utf-8" class="form-horizontal" id="datos_regalo_form"
            @submit.prevent="validate_send">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="nombre" class="col-sm-4 control-label">De</label>
                        <div class="col-sm-8">
                            <input type="text" id="field-regalo_de" name="regalo_de" required
                                class="form-control input-lg" placeholder="¿Quién hace el regalo?" title=""
                                maxlength="30" v-model="meta.regalo.de">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="nombre" class="col-sm-4 control-label">Para</label>
                        <div class="col-sm-8">
                            <input type="text" id="field-regalo_para" name="regalo_para" required
                                class="form-control input-lg" placeholder="¿Quién recibe el regalo?" title=""
                                maxlength="30" v-model="meta.regalo.para">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="notas" class="col-md-4 control-label">Mensaje 
                            ({{ 100 - meta.regalo.mensaje.length }})</label>
                        <div class="col-md-8">
                            <textarea rows="3" name="regalo_mensaje" class="form-control input-lg"
                                placeholder="Mensaje o dedicatoria" title="Mensaje o dedicatoria" maxlength="100"
                                v-model="meta.regalo.mensaje"></textarea>
                        </div>
                    </div>

                </div>
                <div class="col-md-4">
                    <button class="btn btn-polo-lg btn-block" type="submit">
                        <i class="fa fa-check"></i>
                        Continuar
                    </button>
                    <?php $this->load->view('pedidos/compra/totales_v'); ?>
                </div>
            </div>
        </form>
        <hr>
        <div class="mb-2 text-center">
            <button class="btn btn-info" v-on:click="set_step('empaque')">Cambiar empaques elegidos ({{ qty_empaques_in_cart }})</button>
        </div>
    </div>
    <!-- Modal detalle empaque -->
    <div class="modal fade" id="modal_empaque" tabindex="-1" role="dialog" aria-labelledby="modalempaqueLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="modalempaqueLabel">{{ empaque.name }}</h4>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <img
                            v-bind:src="empaque.url_image"
                            class="mw360p only-lg"
                            alt="Imagen empaque empaque"
                            onerror="this.src='<?= URL_IMG ?>app/262px_producto.png'"
                        >
                        <img
                            v-bind:src="empaque.url_image"
                            class="w100pc only-sm"
                            alt="Imagen empaque empaque"
                            onerror="this.src='<?= URL_IMG ?>app/262px_producto.png'"
                        >
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-primary w120p" v-bind:href="`<?= URL_APP ?>productos/detalle/` + empaque.id">Ir a producto</a>
                    <button type="button" class="btn btn-default w120p" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('pedidos/compra/datos_regalo/vue_v') ?>

<div class="pull-right">
    <img src="<?= URL_IMG ?>app/positivessl_trust_seal_md_167x42.png" alt="payment">
</div>