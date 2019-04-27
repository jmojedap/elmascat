<?php $this->load->view('assets/toastr') ?>

<script>
// Variables
//-----------------------------------------------------------------------------
    var base_url = '<?php echo base_url() ?>';

// Document ready
//-----------------------------------------------------------------------------

    $(document).ready(function(){
        
        $('.btn_proceso').click(function(){
            var cod_proceso = $(this).data('cod_proceso');
            ejecutar_proceso(cod_proceso);
        });

    });

// Funciones
//-----------------------------------------------------------------------------
    function ejecutar_proceso(cod_proceso)
    {
        $.ajax({        
            type: 'GET',
            url: base_url + 'productos/ejecutar_proceso/' + cod_proceso,
            success: function(response){
                console.log(response.message);
                var type = 'error';
                if ( response.status == 1 ) { type = 'success'; }
                toastr[type](response.message)
            }
    });
}


</script>

<?php $this->load->view($vista_menu); ?>

<?php
    $procesos = array();

    $procesos[] = array(
        'cod_proceso' => '1',
        'name' => 'Actualizar slug',
        'description' => 'Actualizar el campo producto.slug'
    );
?>

<?php $this->load->view('comunes/resultado_proceso_v'); ?>
<div class="panel panel-default">
    <table class="table table-bordered">
        <thead>
            <th class="w4">Ejecutar</th>
            <th>Proceso</th>
            <th>Descripción</th>
        </thead>
        <tbody>
            <tr>
                <td>
                    <button class="btn btn-primary btn_proceso" data-cod_proceso="1">
                        Ejecutar
                    </button>
                </td>
                <td>Actualizar slug</td>
                <td></td>
            </tr>

            <tr>
                <td>
                    <button class="btn btn-primary btn_proceso" data-cod_proceso="2">
                        Ejecutar
                    </button>
                </td>
                <td>Asignar imágenes</td>
                <td>Asignar imágenes de la carpeta "img_inicial"</td>
            </tr>


            <tr>
                <td>
                    <button class="btn btn-primary btn_proceso" data-cod_proceso="3">
                        Ejecutar
                    </button>
                </td>
                <td>Establecer imagen principal</td>
                <td>Establecer imagen principal para los productos con campo producto.imagen_id es nulo</td>
            </tr>

            <tr>
                <td>
                    <button class="btn btn-primary btn_proceso" data-cod_proceso="4">
                        Ejecutar
                    </button>
                </td>
                <td>Depurar imagen principal</td>
                <td>Actualizar producto.imagen_id, si el registro del archivo no existe, producto.imagen_id se actualiza a NULL</td>
            </tr>
            
            <tr>
                <td>
                    <button class="btn btn-primary btn_proceso" data-cod_proceso="5">
                        Ejecutar
                    </button>
                </td>
                <td>Actualizar puntaje_auto</td>
                <td>Actualizar producto.puntaje_auto, es el valor según el cual se ordenan los resultados en la búsqueda de un producto</td>
            </tr>
            
            <tr>
                <td>
                    <button class="btn btn-primary btn_proceso" data-cod_proceso="6">
                        Ejecutar
                    </button>
                </td>
                <td>Redondear costos</td>
                <td>Actualizar producto.costo y pedido_detalle.costo, redondeado al múltiplo de precios.</td>
            </tr>
            <tr>
                <td>
                    <button class="btn btn-primary btn_proceso" data-cod_proceso="7">
                        Ejecutar
                    </button>
                </td>
                <td>Actualizar meta</td>
                <td>Actualizar producto.meta según los campos adicionales de producto en la tabla meta, para búsquedas.</td>
            </tr>
        </tbody>
    </table>      
</div>