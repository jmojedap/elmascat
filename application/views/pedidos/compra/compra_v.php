<?php 

    $clases_col['carrito'] = '';
    $clases_col['datos'] = '';
    $clases_col['verificacion'] = '';
    $clases_col['resultado'] = '';
    
    if ( $this->uri->segment(2) == 'carrito' ) { $clases_col['carrito'] = 'info'; }
    if ( $this->uri->segment(2) == 'compra_a' ) { $clases_col['datos'] = 'info'; }
    if ( $this->uri->segment(2) == 'compra_b' ) { $clases_col['verificacion'] = 'info'; }
    if ( $this->uri->segment(2) == 'respuesta' ) { $clases_col['resultado'] = 'info'; }
?>

<table class="table table-bordered">
    <tbody>
        <tr class="text-center">
            <td width="25%" class="<?= $clases_col['carrito'] ?>">
                Carrito
            </td>
            <td width="25%" class="<?= $clases_col['datos'] ?>">
                Datos de entrega
            </td>
            <td width="25%" class="<?= $clases_col['verificacion'] ?>">
                Verificación
            </td>
            <td width="25%" class="<?= $clases_col['resultado'] ?>">
                Resultado pago
            </td>
        </tr>
    </tbody>
</table>

<?php $this->load->view($vista_b); ?>
<br/>

