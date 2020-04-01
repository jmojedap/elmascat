<div class="row">
    <div class="col col-md-12">
        <div class="page-title">
            <h2 class="title">
                Distribuidores
            </h2>
        </div>
        
    </div>
    
</div>
<div class="row">
    <div class="col col-md-5">
        <h3>¿Quiere ser distribuidor?</h3>
            
        <p>
            Si usted hace sus compras en <span class="resaltar">Districatolicas.com</span> para su librería o negocio puede
            solicitar que su cuenta se asigne con perfil de <span class="resaltar">Distribudor</span>.
            Un distribuidor de Districatólicas tiene acceso a descuentos especiales en algunas editoriales.
        </p>

        <p>
            Para hacer la solicitud debe haber registrado sus datos personales (<?php echo anchor("usuarios/editarme/edit/{$this->session->userdata('usuario_id')}", 'Editar mis datos') ?>), incluyendo número de documento, dirección, teléfono y 
            celular. Uno de nuestros asesores se contactará con usted para continuar con el proceso.
        </p>
        
        <?php if ( $this->session->userdata('logged') ) { ?>
            <?= anchor("usuarios/solicitar_rol/{$this->session->userdata('usuario_id')}/22", 'Quiero ser distribuidor', 'class="btn btn-polo" title=""') ?>
        <?php } else { ?>
            <?= anchor("app/login/distribuidor", 'Quiero ser distribuidor', 'class="btn btn-polo" title=""') ?>
        <?php } ?>
    </div>
    <div class="col col-md-7">
        <h3>Descuentos para distribuidores</h3>
        
        <table class="table table-hover">
            <thead>
                <th class="<?= $clases_col['nombre_elemento'] ?>">Marca/Editorial</th>
                <th class="<?= $clases_col['percent_descuento'] ?>">% descuento</th>
            </thead>

            <tbody>
                <?php foreach($fabricantes->result() as $row_fabricante) : ?>
                <tr>
                    <td class="<?= $clases_col['nombre_fabricante'] ?>">
                        <?= anchor("productos/catalogo/?fab={$row_fabricante->id}", $row_fabricante->item) ?>
                    </td>
                    <td class="<?= $clases_col['percent_descuento'] ?>">
                        <?= $row_fabricante->entero_1 ?>%
                    </td>
                </tr>
                <?php endforeach; ?>

            </tbody>
        </table>
        
    </div>
</div>