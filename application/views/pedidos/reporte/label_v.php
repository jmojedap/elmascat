<style>
    table{
        font-size: 1.8em;
        margin-top: 1em;
    }

    .big{
        font-size: 1.2em;
        font-weight: bold;
    }
</style>

<h1 class="text-center">DISTRICATÓLICAS - MINUTOS DE AMOR</h1>

<table class="table table-bordered table-sm">
    <tbody>
        <tr>
            <td width="25%">Destinatario</td>
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
            <td><?= $this->App_model->nombre_lugar($row->ciudad_id) ?> | </td>
        </tr>
        <tr>
            <td>Departamento</td>
            <td><?= $this->App_model->nombre_lugar($row->region_id) ?></td>
        </tr>
        <tr>
            <td>Cantidad</td>
            <td></td>
        </tr>
    </tbody>
</table>

<div class="text-center">
    Remite <span class="big">DISTRICATÓLICAS</span> Bogotá D.C. Colombia
    <br>
    Cel. 301 305 4053 - 031 7456922 - Av. Calle 72 # 83-96 
    <br>
    districatolicas.com - infodistricatolicas@gmail.com
    <br>
    <strong>Nuestros productos son perecederos, por favor entregarlos a la mayor brevedad posible</strong>
</div>