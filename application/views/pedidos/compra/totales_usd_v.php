<div style="height: 35px;"></div>
<table class="data-table cart-table">
    <thead>
        <th colspan="2">Total (USD)</th>
    </thead>
    <tfoot>
        <tr>
            <td class="a-left" colspan="1"><strong>Total</strong></td>
            <td class="text-right">
                <strong>
                    <span class="money money_total" style="font-size: 150%;">
                        USD $<?php echo $form_data['valor'] ?>
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
                    $ <?php echo number_format($row->total_productos/$precio_dolar, 2, '.', ',') ?>
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
                        $ <?php echo number_format($arr_extras['gastos_envio']/$precio_dolar, 2, '.', ',') ?>
                    </span>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<div style="height: 35px;"></div>