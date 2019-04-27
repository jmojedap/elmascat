<?php
    
    $att_form = array(
        'class' =>  'form1'
    );

    $att_nombre_hoja = array(
        'name' => 'nombre_hoja',
        'class' =>  'form-control',
        'required'  =>  'required',
        'value' =>  'listado'
    );
    
    $resultado = array();

?>

<?php $this->load->view($vista_menu); ?>

<?= form_open_multipart("productos/cargar_e/", $att_form) ?>

<div class="row">
    <div class="col-md-7">
        
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="form-group">
                    <label for="file" class="label1">Seleccionar archivo</label><br/>
                    <input type="file" name="file" required>
                </div>

                <div class="div1">
                    <label for="nombre_hoja" class="label1">Nombre Hoja</label><br/>
                    <span class="suave">Digite el nombre de la hoja de cálculo en archivo de excel de donde se tomarán los datos de los elementos</span>
                    <?= form_input($att_nombre_hoja) ?>
                </div>

                <div class="div1">
                    <input type="submit" class="btn btn-primary" value="Cargar">
                </div>
            </div>
        </div>
        
        <div class="info_container_body">
            
            <?php //Mensajes de validación del formulario ?>

            <?php if ( ! $cargado ):?>
                <div class="div1">
                    <?php foreach ($resultado as $mensaje_resultado): ?>
                        <h4 class="alert_error"><?= $mensaje ?></h4>
                    <?php endforeach ?>
                </div>
            <?php endif ?>
            
        </div>
    </div>
    
    <div class="col-md-5">
        <div class="panel panel-default">
            <div class="panel-heading">
                Cargue masivo de productos
            </div>
            <div class="panel-body">
                <ul>
                    <li>El tipo de archivo requerido es: <span class="resaltar">MS Excel 97-2003 (.xls) o 2007 (.xlsx)</span>.</li>
                    <li>Verifique el el primer registro esté ubicado en la <span class="resaltar">fila 2</span> de la hoja de cálculo.</li>
                    <li>Si la casilla '<span class="resaltar">ID</span>' (columna A) se encuentra se editará el producto en lugar de agregarlo, si existe.</li>
                    <li>El nombre de la hoja de cálculo dentro del archivo, de la cual se tomarán los datos, no puede contener caracteres con tildes ni letras ñ.</li>
                    <li>Descargar formato: <?= anchor(base_url() . 'assets/formatos_cargue/01_formato_cargue_productos.xlsx', '01_formato_cargue_productos.xlsx', 'class="" title="Descargar formato"') ?> </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?= form_close() ?>




