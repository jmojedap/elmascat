<style>
    table{
        font-size: 2.2em;
        margin-top: 1em;
    }

    .big{
        font-size: 1.2em;
        font-weight: bold;
    }
</style>

<h1 class="text-center">DISTRICATÓLICAS</h1>

<table class="table table-bordered table-sm">
    <tbody>
        <tr>
            <td width="20%">Destinatario</td>
            <td><?= $row->nombre . ' ' . $row->apellidos ?></td>
        </tr>
        <tr>
            <td>Ref. Venta</td>
            <td><?= $row->cod_pedido ?></td>
        </tr>
        <tr>
            <td>Dirección</td>
            <td><?= $row->direccion ?></td>
        </tr>
        <tr>
            <td>Teléfono</td>
            <td><?= $row->celular ?> - <?= $row->telefono ?></td>
        </tr>
        <tr>
            <td>Ciudad</td>
            <td><?= $this->App_model->nombre_lugar($row->ciudad_id) ?></td>
        </tr>
    </tbody>
</table>

<div class="text-center">
    Cel. 301 305 4053 &middot; infodistricatolicas@gmail.com
</div>