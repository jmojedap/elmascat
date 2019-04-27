<?php    
    //Clases y estilo
        foreach ( $arr_filtros as $filtro )
        {
            $style_filtros[$filtro] = 'style="display: none;"';
            $clases_filtros[$filtro] = 'filtro';
            if ( strlen($busqueda[$filtro]) > 0 )
            {
                $style_filtros[$filtro] = '';
                $clases_filtros[$filtro] = '';
            }
        }
?>

<form action="<?php echo base_url("app/buscar/{$controlador}/explorar") ?>" accept-charset="utf-8">
    <div class="form-group row">
        <div class="col-sm-4">
            <div class="btn btn-default btn-block" id="alternar_avanzada" data-toggle="tooltip" title="Búsqueda avanzada">
                Filtros
                <i class="fa fa-caret-down float-right"></i>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="input-group">
                <input name="q" autofocus value="<?= $busqueda['q'] ?>" type="text" class="form-control" placeholder="Buscar transacción...">
                <span class="input-group-btn">
                    <button class="btn btn-info btn-flat" type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
        </div>
    </div>
    <div class="form-group row <?= $clases_filtros['tp'] ?>" <?php echo $style_filtros['tp'] ?>>
        <label for="tp" class="col-sm-4 col-form-label text-right">Tipo</label>
        <div class="col-sm-8">
            <?= form_dropdown('tp', $opciones_tipo, $busqueda['tp'], 'class="form-control" title="Filtrar tipo de lugar"'); ?>
        </div>
    </div>
        
        

</form>