<!-- TABLA DE PRODUCTOS PARA MOBILE -->
<div class="table-responsive only-sm">
    <table class="table ">
        <thead>
            <th width="50px"></th>
            <th>Producto</th>
        </thead>
        <tbody>
            <tr v-for="(product, pk) in products">
                <td>
                    <a v-bind:href="`<?= URL_APP . "productos/detalle/" ?>` + product.producto_id + `/` + product.slug"
                        class="clase">
                        <img class="rounded w50p" alt="Imagen producto"
                            onerror="this.src='<?= URL_IMG ?>app/262px_producto.png'"
                            v-bind:src="product.url_thumbnail">
                    </a>
                </td>
                <td>
                    <div class="mb-2">
                        
                        <a v-bind:href="`<?= URL_APP . "productos/detalle/" ?>` + product.producto_id + `/` + product.slug"
                            class="clase">
                            {{ product.nombre_producto }}
                        </a>
                    </div>
                    <p>
                        <span v-if="product.peso > 0">
                            <input
                                name="cantidad" type="number" class="" min="1" v-bind:max="product.stock" v-bind:disabled="loading" style="width: 50px; text-align: center;"
                                required
                                v-on:change="add_product(pk)"
                                v-model="product.cantidad"
                            >
                        </span>
                        <span v-else>1</span>
                        x {{ product.precio | currency }} = 
                        <strong>{{ product.precio * product.cantidad | currency }}</strong>
                    </p>
                    <div v-show="is_special_price(pk)" class="mb-2">
                        <span class="correcto">
                            <i class="fa fa-check"></i>
                            {{ product.promocion_id | nombre_tipo_precio }}
                        </span>
                        <br>
                        <div style="font-size: 90%; margin-top: 0.5em;">
                            <span class="suave">
                                Precio normal 
                            </span>
                            <span class="suave">
                                {{ product.precio_nominal | currency }}
                            </span>

                            <span class="label label-success">- {{ discount_percent(product) }} %</span>
                        </div>
                    </div>
                    <p>
                        <a class="btn btn-link btn-xs" href="#" data-toggle="modal" data-target="#delete_modal"
                            v-on:click="set_product(pk)">
                            Quitar
                        </a>
                    </p>
                </td>
            </tr>
        </tbody>
    </table>
</div>