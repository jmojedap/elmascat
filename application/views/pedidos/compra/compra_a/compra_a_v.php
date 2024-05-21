<a href="<?= base_url("pedidos/carrito") ?>" class="btn btn-polo w120p">
    <i class="fa fa-arrow-left"></i>
    Atrás
</a>

<hr>

<div id="compra_a_app">
    <div class="row">
        <div class="col-md-8 col-sm-12">
            <div class="form-horizontal">
                <div class="form-group">
                    <label for="region_id" class="col-md-4 control-label">Departamento</label>
                    <div class="col-md-8">
                        <select name="region_id" v-model="region_id" class="form-control input-lg" required
                            v-on:change="get_cities">
                            <option v-for="(option_region, region_key) in options_region" v-bind:value="region_key">
                                {{ option_region }}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="ciudad_id" class="col-md-4 control-label">Ciudad</label>
                    <div class="col-md-8">
                        <select name="ciudad_id" v-model="ciudad_id" class="form-control input-lg" required
                            v-on:change="guardar_lugar">
                            <option v-for="(option_ciudad, ciudad_key) in options_ciudad" v-bind:value="ciudad_key">
                                {{ option_ciudad }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>

    <form method="post" accept-charset="utf-8" class="form-horizontal" id="compra_a_form" @submit.prevent="send_form"
        v-show="ciudad_id.length > 1">
        <fieldset v-bind:disabled="loading">


            <input type="hidden" v-model="ciudad_id" name="ciudad_id">
            <input type="hidden" v-model="screen_width" name="screen_width">
            <input type="hidden" v-model="screen_height" name="screen_height">

            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="nombre" class="col-sm-4 control-label">Nombres | Apellidos</label>
                        <div class="col-sm-4">
                            <input type="text" name="nombre" class="form-control input-lg" required
                                placeholder="Nombres" title="Nombres" v-model="form_values.nombre">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="apellidos" required class="form-control input-lg"
                                placeholder="Apellidos" title="Apellidos" v-model="form_values.apellidos">
                        </div>
                        <div class="col-sm-8 col-md-offset-4">
                            <span id="" class="help-block">Por favor escriba sus nombres y apellidos completos</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="col-md-4 control-label">Correo electrónico</label>
                        <div class="col-md-8">
                            <input type="email" name="email" required class="form-control input-lg"
                                v-model="form_values.email">
                            <span id="helpBlock" class="help-block">Si usa un correo de <b
                                    class="text-danger">hotmail.com</b>, los mensajes de confirmación podrían llegar a
                                la carpeta de spam o correo no deseado.</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="no_documento" class="col-md-4 control-label">No. Documento </label>
                        <div class="col-md-4">
                            <input type="number" name="no_documento" required min="100" class="form-control input-lg"
                                v-model="form_values.no_documento">
                        </div>
                        <div class="col-md-4">
                            <?= form_dropdown('tipo_documento_id', $options_tipo_documento, '', 'class="form-control input-lg" required v-model="`0` + form_values.tipo_documento_id"') ?>
                        </div>
                    </div>

                    <!-- MOSTRAR OPCIÓN SOLO SI LA CIUDAD ES 0909:BOGOTA -->
                    <div class="form-group" v-show="ciudad_id == `0909`">
                        <label for="tipo_envio" class="col-md-4 control-label">Tipo de entrega</label>
                        <div class="col-md-8">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="shipping_method_id" id="optionsRadios1" value="0" v-model="form_values.shipping_method_id"
                                    v-on:change="setShippingMethodId">
                                    Enviar pedido a dirección
                                </label>
                            </div>
                            <div class="radio hidden">
                                <label>
                                    <input type="radio" name="shipping_method_id" value="97" v-model="form_values.shipping_method_id"
                                    v-on:change="setShippingMethodId">
                                    Pago contra entrega &middot;
                                    <a href="https://envia.co/" target="_blank">
                                        Transportadora Envía
                                    </a>
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="shipping_method_id" value="98" v-model="form_values.shipping_method_id"
                                    v-on:change="setShippingMethodId">
                                    Recoger en tienda &middot;
                                    <a href="https://www.google.com/maps/place/Cl.+72+%2383-96,+Bogot%C3%A1/@4.6949995,-74.107477,17z/" target="_blank">
                                        Av Calle 72 # 83-96
                                    </a>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="direccion" class="col-md-4 control-label">
                            <span v-show="form_values.shipping_method_id != `98`">Dirección entrega</span>
                            <span v-show="form_values.shipping_method_id == `98`">Dirección facturación</span>
                        </label>
                        <div class="col-md-8">
                            <input type="text" name="direccion" class="form-control input-lg" required
                                title="Dirección de entrega del pedido" v-model="form_values.direccion">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="celular" class="col-md-4 control-label">Celular</label>
                        <div class="col-md-8">
                            <input type="text" name="celular" required class="form-control input-lg" minlength="10"
                                placeholder="Número celular" title="Número teléfono celular sin espacios, solo números"
                                pattern="[0-9]{5,}" v-model="form_values.celular">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="celular" class="col-md-4 control-label">Empacar como regalo</label>
                        <div class="col-md-8">
                            <select name="is_gift" v-model="form_values.is_gift" class="form-control input-lg"
                                v-bind:disabled="order.valor_total < 20000">
                                <option v-for="(option_gift, gift_key) in options_gift" v-bind:value="gift_key">
                                    {{ option_gift }}</option>
                            </select>
                            <span id="is_gift_help" class="help-block" v-show="order.valor_total < 20000">Aplica para
                                compras mayores a $20.000 COP</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="notas" class="col-md-4 control-label">Notas sobre el pedido</label>
                        <div class="col-md-8">
                            <textarea rows="3" id="field-notas" name="notas" class="form-control input-lg"
                                placeholder="Notas sobre su pedido e instrucciones de envío"
                                title="Notas sobre su pedido e instrucciones de envío"
                                v-model="form_values.notas"></textarea>
                        </div>
                    </div>


                    <hr>
                    <?php if ( is_null($row_usuario->fecha_nacimiento) )  { ?>
                    <div class="form-group row">
                        <label for="fecha_nacimiento" class="col-xs-12 col-md-4 control-label">Fecha de
                            nacimiento</label>
                        <div class="col-xs-4 col-md-2">
                            <?= form_dropdown('day', $options_day, '', 'class="form-control input-lg" required v-model="day"') ?>
                        </div>
                        <div class="col-xs-4 col-md-4">
                            <?= form_dropdown('month', $options_month, '', 'class="form-control input-lg" required v-model="month"') ?>
                        </div>
                        <div class="col-xs-4 col-md-2">
                            <?= form_dropdown('year', $options_year, '', 'class="form-control input-lg" required v-model="year"') ?>
                        </div>
                    </div>
                    <?php } ?>
                    <?php if ( is_null($row_usuario->sexo) )  { ?>
                    <div class="form-group">
                        <div class="col-md-8 col-md-offset-4">
                            <input type="radio" name="sexo" value="1" required> Mujer
                            <input type="radio" name="sexo" value="2"> Hombre
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-polo-lg btn-block" type="submit">
                        <i class="fa fa-check"></i> Continuar
                    </button>
                    <?php $this->load->view('pedidos/compra/totales_v'); ?>
                </div>
            </div>
            <fieldset>
    </form>

</div>

<?php $this->load->view('pedidos/compra/compra_a/vue_v') ?>