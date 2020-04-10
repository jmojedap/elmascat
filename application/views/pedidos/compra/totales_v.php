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
                        <?php echo $this->Pcrn->moneda($row->valor_total) ?>
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
                    <?= $this->Pcrn->moneda($row->total_productos) ?>
                </span>
            </td>
        </tr>

        <?php if ( $arr_extras['gastos_envio'] ) { ?>
            <tr>
                <td class="a-left">
                    Gastos transacción y Envío (<?= $row->peso_total ?> kg)
                </td>
                <td class="text-right">
                    <span class="money">
                        <?= $this->Pcrn->moneda($arr_extras['gastos_envio']);  ?>
                    </span>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>