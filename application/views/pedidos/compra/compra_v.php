<?php
    $step = $this->uri->segment(2);

    $cl_steps['carrito'] = '';
    $cl_steps['datos'] = '';
    $cl_steps['verificacion'] = '';
    $cl_steps['resultado'] = '';
    
    if ( $step == 'carrito' ) { $cl_steps['carrito'] = 'active'; }
    if ( $step == 'usuario' ) { $cl_steps['datos'] = 'active'; }
    if ( $step == 'compra_a' ) { $cl_steps['datos'] = 'active'; }
    if ( $step == 'datos_regalo' ) { $cl_steps['datos'] = 'active'; }
    if ( $step == 'verificar' ) { $cl_steps['verificacion'] = 'active'; }
    if ( $step == 'link_pago' ) { $cl_steps['verificacion'] = 'active'; }
    if ( $step == 'respuesta' ) { $cl_steps['resultado'] = 'active'; }

    $arr_pct = array(
        'carrito' => '25',
        'compra_a' => '50',
        'verificar' => '75',
        'respuesta' => '100'
    );
?>

<table class="table table-bordered checkout_steps">
    <tbody>
        <tr class="text-center">
            <td width="25%" class="step <?= $cl_steps['carrito'] ?>">
                PRODUCTOS
            </td>
            <td width="25%" class="step <?= $cl_steps['datos'] ?>">
                TUS DATOS
            </td>
            <td width="25%" class="step <?= $cl_steps['verificacion'] ?>">
                VERIFICA
            </td>
            <td width="25%" class="step <?= $cl_steps['resultado'] ?>">
                RESULTADO
            </td>
        </tr>
    </tbody>
</table>

<?php if ( ! is_null($vista_b) ) { ?>
    <?php $this->load->view($vista_b); ?>
<?php } ?>