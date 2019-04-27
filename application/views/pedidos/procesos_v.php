<?php
    $resultados = $this->session->flashdata('resultados');
?>

<?php if ( strlen($resultados['mensaje']) > 0 ){ ?>
    <div class="alert <?= $resultados['clase_alerta'] ?>" role="alert">
        <?= $resultados['mensaje'] ?>
    </div>
<?php } ?>

<table class="table table-bordered">
    <thead>
        <th class="w4">Ejecutar</th>
        <th>Proceso</th>
        <th>Descripción</th>
    </thead>
    <tbody>
        <tr>
            <td>
                <?= anchor("productos/ejecutar_proceso/1", 'Ejecutar', 'class="btn btn-primary" title=""') ?>
            </td>
            <td>Actualizar slug</td>
            <td>Actualizar el campo producto.slug</td>
        </tr>
        
        <tr>
            <td>
                <?= anchor("productos/ejecutar_proceso/2", 'Ejecutar', 'class="btn btn-primary" title=""') ?>
            </td>
            <td>Asignar imagenes</td>
            <td>Asignar imágenes de la carpeta "im_cargue"</td>
        </tr>
    </tbody>
</table>