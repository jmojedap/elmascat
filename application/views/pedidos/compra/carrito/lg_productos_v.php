<div class="table-responsive only-lg">
    <table class="table ">
        <thead>
            <th width="50px"></th>
            <th>Productos ({{ products.length }})</th>
            <th>Cantidad</th>
            <th>Total</th>
            <th width="10px"></th>
        </thead>
        <tbody>
            <tr v-for="(product, pk) in products">
                <td>
                    <a v-bind:href="`<?= URL_APP . "productos/detalle/" ?>` + product.producto_id + `/` + product.slug"
                        class="clase">
                        <img class="rounded w50p" alt="Imagen producto"
                            onerror="this.src='<?= URL_IMG ?>app/sm_md.png'"
                            v-bind:src="product.url_thumbnail">
                    </a>
                </td>
                <td>
                    <a v-bind:href="`<?= URL_APP . "productos/detalle/" ?>` + product.producto_id + `/` + product.slug"
                        class="clase">
                        {{ product.nombre_producto }}
                    </a>
                    <br>
                    <span class="money" style="font-size: 125%;">{{ product.precio | currency }}</span> 
                    <div v-show="is_special_price(pk)">
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
                </td>
                <td class="text-center">
                    <div v-if="product.peso > 0">
                        <input
                            name="quantity" type="number" class="form-control text-center" min="1" required
                            v-bind:max="product.cant_disponibles" v-bind:disabled="loading" v-on:change="add_product(pk)" v-model="product.cantidad"
                        >
                    </div>
                    <div v-else>1</div>
                </td>
                <td>{{ product.precio * product.cantidad | currency }}</td>
                <td>
                    <button class="a4" data-toggle="modal" data-target="#delete_modal"
                        v-bind:disabled="loading"
                        v-on:click="set_product(pk)">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>