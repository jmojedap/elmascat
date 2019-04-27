<?php $this->load->view('assets/chosen_jquery'); ?>
<?php $this->load->view('assets/toastr'); ?>

<?php
    //Opciones
        $opciones_tags = $this->Item_model->opciones_id('categoria_id = 21');
        $opciones_fabricante = $this->Item_model->opciones_id('categoria_id = 5', 'Marca o fabricante');
        $opciones_categoria = $this->Item_model->opciones('categoria_id = 25', 'Categoría');
?>

<script>
// Variables
//-----------------------------------------------------------------------------

    var base_url = '<?php echo base_url() ?>';
    var condiciones_form = 0;
    var referencia_valido = 0;
    var tags_valido = 0;
    var fabricante_valido = 0;
    
// Document Ready
//-----------------------------------------------------------------------------

    $(document).ready(function()
    {
        //autollenado();
        
        $("#formulario").submit(function() 
        {
            validar_tags();   //Validar que haya al menos una categoría
            validar_fabricante();   //Validar que haya elegido fabricante
            
            condiciones_form = referencia_valido + tags_valido + fabricante_valido;
            
            if ( condiciones_form == 3 )
            {
                enviar_formulario();
            } else {
                toastr["error"]('Revise las casillas en rojo');
            }
            return false;
        });
        
        $('#field-referencia').change(function(){
            var referencia = $(this).val();
            es_unico(referencia);
        });
        
        $('.auto_precio').change(function(){
            act_precio_base();
        });
        
        $('#field-costo').change(function(){
            act_costo();
        });
    });
    
//Funciones
//---------------------------------------------------------------------------------------------------
    //Ajax, envía los datos del formulario
    function enviar_formulario()
    {
        $.ajax({
            type: "POST",
            url: base_url + 'productos/insertar/',
            data: $("#formulario").serialize(),
            success: function (resultado) {
                //toastr['success']('El producto fue creado');
                window.location = base_url + 'productos/editar/' + resultado.nuevo_id + '/descripcion';
            },
            error: function(){
                toastr["error"]('Ocurrió un error al guardar');
            }
        });
    }
    
    //Ajax, verificar que la referencia no exista ya en la base de datos
    function es_unico(referencia)
    {
        $.ajax({
            type: "POST",
            url: base_url + 'app/es_unico/',
            data: {
                tabla: 'producto',
                campo: 'referencia',
                valor: referencia
            },
            success: function (resultado) {
                if ( resultado.ejecutado )
                {
                    referencia_valido = 1;
                    $('#form-group_referencia').removeClass('has-error');
                    $('#form-group_referencia').addClass('has-success');
                } else {
                    referencia_valido = 0;
                    toastr["error"]('La referencia escrita ya está asignada a otro producto');
                    $('#form-group_referencia').addClass('has-error');
                }
            }
        });
    }
    
    //Validar que haya elegido al menos una categoría
    function validar_tags()
    {
        if ( $('#field-tags').val() )
        {
            tags_valido = 1;
            $('#form-group_tags').removeClass('has-error');
        } else {
            tags_valido = 0;
            $('#form-group_tags').addClass('has-error');
            toastr["error"]('Debe seleccionar al menos una etiqueta para el producto');
        }
    }
    
    //Validar que haya elegido fabricante
    function validar_fabricante()
    {
        if ( $('#field-fabricante').val() )
        {
            fabricante_valido = 1;
            $('#form-group_fabricante').removeClass('has-error');
        } else {
            fabricante_valido = 0;
            $('#form-group_fabricante').addClass('has-error');
            toastr["error"]('Debe seleccionar el fabricante o marca del producto');
        }
    }
    
    //Actualiza campo precio base según variables relacionadas
    function act_precio_base()
    {
        var precio_base = $('#field-precio').val() / ( 1 + $('#field-iva_porcentaje').val()/100) ;
        var iva = parseFloat($('#field-precio').val()) - precio_base;
        $('#field-iva').val(iva.toFixed(2));
        $('#field-precio_base').val(precio_base.toFixed(2));
    }
    
    //Si el costo es mayor al precio base, alerta
    function act_costo()
    {
        var diferencia = $('#field-costo').val() - $('#field-precio_base').val();
        if ( diferencia > 0 ) 
        {
            var nuevo_costo = $('#field-precio_base').val();
            $('#field-costo').val(nuevo_costo);
            toastr["error"]('El costo del producto es superior al precio base, confirme los valores.');
        }
    }
    
    //Autollenado de valores, test
    function autollenado()
    {
        $('#field-referencia').val('<?php echo date(ymdhis) ?>');
        $('#field-nombre_producto').val('El producto');
        $('#field-descripcion').val('La descripción bla');
        $('#field-cant_disponibles').val('112');
        $('#field-peso').val('213');
        $('#field-precio').val('45000');
        $('#field-iva_porcentaje').val('19');
        $('#field-costo').val('37000');
        
        es_unico($('#field-referencia').val());
        act_precio_base();
    }
</script>

<?php $this->load->view($vista_menu); ?>

<div class="panel panel-default" id="app">
    <div class="panel-body">
        <form id="formulario" method="post" class="form-horizontal">
            <div class="form-group row">
                <div class="col-md-9 col-md-offset-3">
                    <button class="btn btn-success btn-block" type="submit">
                        CREAR
                    </button>
                </div>
            </div>
            
            <div class="form-group row" id="form-group_referencia">
                <label for="referencia" class="col-sm-3 control-label">Referencia *</label>
                <div class="col-sm-9">
                    <input
                        id="field-referencia"
                        name="referencia"
                        class="form-control"
                        placeholder="Referencia única del producto"
                        required
                        autofocus
                        >
                </div>
            </div>
            
            <div class="form-group row">
                <label for="nombre_producto" class="col-sm-3 control-label">Título *</label>
                <div class="col-sm-9">
                    <input
                        id="field-nombre_producto"
                        name="nombre_producto"
                        class="form-control"
                        placeholder="Nombre o título del producto"
                        required
                        >
                </div>
            </div>
            <div class="form-group row">
                <label for="descripcion" class="col-sm-3 control-label">Descripción *</label>
                <div class="col-sm-9">
                    <textarea 
                        id="field-descripcion" 
                        name="descripcion" 
                        class="form-control" 
                        rows="2"
                        required
                        placeholder="Escriba la descripción del producto"></textarea>
                </div>
            </div>
            
            <div class="form-group row" id="form-group_fabricante">
                <label for="fabricante_id" class="col-sm-3 control-label">Fabricante - Marca *</label>
                <div class="col-sm-9">
                    <?php echo form_dropdown('fabricante_id', $opciones_fabricante, '', 'id="field-fabricante" class="form-control chosen-select"') ?>
                </div>
            </div>

            <div class="form-group row" id="form-group-categoria_id">
                <label for="categoria_id" class="col-sm-3 control-label">Categoría producto *</label>
                <div class="col-sm-9">
                    <?php echo form_dropdown('categoria_id', $opciones_categoria, '', 'id="field-categoria_id" class="form-control" required') ?>
                </div>
            </div>
            
            <div class="form-group" id="form-group_tags">
                <label class="control-label col-md-3" for="tags">Etiquetas *</label>

                <div class="col-md-9">
                    <select id="field-tags" name="tags[]" id="tags" class="form-control chosen-select" multiple>
                        <?php foreach ($opciones_tags as $key => $nombre_tag) : ?>
                            <option value="<?= $key ?>">
                                <?php echo $nombre_tag ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>
            
            <div class="form-group row">
                <label for="precio" class="col-sm-3 control-label">Precio de venta *</label>
                <div class="col-sm-9">
                    <input
                        id="field-precio"
                        name="precio"
                        class="form-control auto_precio"
                        placeholder="Precio del producto, solo números"
                        required
                        type="number"
                        min="50"
                        >
                </div>
            </div>
            <div class="form-group row">
                <label for="iva_porcentaje" class="col-sm-3 control-label">% IVA *</label>
                <div class="col-sm-9">
                    <input
                        id="field-iva_porcentaje"
                        name="iva_porcentaje"
                        class="form-control auto_precio"
                        placeholder="Porcentaje del IVA"
                        required
                        type="number"
                        min="0"
                        max="19"
                        >
                </div>
            </div>
            <div class="form-group row">
                <label for="costo" class="col-sm-3 control-label">Costo compra o fabricación *</label>
                <div class="col-sm-9">
                    <input
                        id="field-costo"
                        name="costo"
                        class="form-control"
                        placeholder="Costo de compra o fabricación"
                        required
                        type="number"
                        min="1"
                        step=".01"
                        >
                </div>
            </div>
            <div class="form-group row">
                <label for="cant_disponibles" class="col-sm-3 control-label">Cantidad disponibles *</label>
                <div class="col-sm-9">
                    <input
                        id="field-cant_disponibles"
                        name="cant_disponibles"
                        class="form-control"
                        placeholder="Cantidad de unidades disponibles"
                        required
                        type="number"
                        min="0"
                        >
                </div>
            </div>
            <div class="form-group row">
                <label for="peso" class="col-sm-3 control-label">Peso (gramos) *</label>
                <div class="col-sm-9">
                    <input
                        id="field-peso"
                        name="peso"
                        class="form-control auto_precio"
                        placeholder="Peso del producto en gramos"
                        required
                        type="number"
                        min="0"
                        >
                </div>
            </div>
            <div class="form-group row">
                <label for="iva" class="col-sm-3 control-label">$ IVA</label>
                <div class="col-sm-9">
                    <input
                        id="field-iva"
                        name="iva"
                        class="form-control auto_precio"
                        required
                        placeholder="$ Valor correspondiente al IVA"
                        >
                </div>
            </div>
            <div class="form-group row">
                <label for="precio_base" class="col-sm-3 control-label">Precio base (Sin IVA)</label>
                <div class="col-sm-9">
                    <input
                        id="field-precio_base"
                        name="precio_base"
                        class="form-control auto_precio"
                        placeholder="Precio base del producto, antes del IVA"
                        required
                        >
                </div>
            </div>
        </form>
    </div>    
</div>