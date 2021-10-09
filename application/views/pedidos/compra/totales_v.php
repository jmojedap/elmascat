<table class="data-table cart-table mb-2">
    <thead>
        <th colspan="2">Valores</th>
    </thead>
    <tfoot>
        <tr>
            <td class="a-left" colspan="1"><strong>Total</strong></td>
            <td class="text-right">
                <strong>
                    <span class="money money_total">
                        {{ order.valor_total | currency }}
                    </span>
                </strong>
            </td>
        </tr>
    </tfoot>
    <tbody>
        <tr>
            <td>
                Subtotal productos
            </td>
            <td class="text-right">
                <span class="money">
                    {{ order.total_productos | currency }}
                </span>
            </td>
        </tr>

        <tr v-for="(extra, ek) in extras">
            <td class="a-left">
                {{ extra.producto_id | name_extra_pedido }}
                <span v-if="extra.producto_id == 1">({{ order.peso_total }} kg)</span>
            </td>
            <td class="text-right">
                <span class="money">
                    {{ extra.precio | currency }}
                </span>
            </td>
        </tr>
    </tbody>
</table>