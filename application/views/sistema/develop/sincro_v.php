<?php

    //Key, corresponde al sis_opcion.id asociado a la fecha de actualización de la tabla correspondiente
    $tablas[13] = 'item';
    
?>

<script>
    //Variables
        var json_query = '';
        var cant_sincronizados = 0;
        var opcion_id = 10; //Ver tabla: sis_opcion.id
        var base_url = '<?= base_url() ?>';
        var sincro_url = '<?= $sincro_url ?>';
        var tabla = '';
        //var validacion = '/<?= $this->session->userdata('usuario_id') ?>/<?= $this->session->userdata('nombre_usuario') ?>';
        var mensaje = '';
</script>

<script>
    $(document).ready(function(){
        
        $('.sincro').click(function(){
            opcion_id = $(this).data('key');
            tabla = $(this).data('table');
            sincro();
        });
    });
</script>

<script>
    //Descargar los datos de la plataforma y guardarlos en la BD local
    function sincro()
    {
        $.ajax({
            type: 'POST',
            //url:  sincro_url + tabla,
            //url: 'http://www.plataformaenlinea.com/2015/datos/registros_json/',
            url: 'http://www.plataformaenlinea.com/2015/sincro/registros_json/',
            beforeSend : function(){
                $('#estado').html('<h4 class="alert_info">Descargando datos, por favor espere...</h4>');
            },
            success: function(respuesta){
                //$('#estado').html('<p>' + respuesta + '</p>');
                /*json_query = respuesta;
                cargar_query();   */
            }
        });
    }
    
    //Carga datos en la BD Local
    function cargar_query()
    {
        $.ajax({
            type: 'POST',
            url: base_url + 'develop/ajax_sincro_tabla/' + tabla,
            data: {
                json_query : json_query
            },
            success: function(respuesta){
                cant_sincronizados = respuesta;
                actualizar_fecha_sincro();
            }
        });
    }
    
    function actualizar_fecha_sincro()
    {
        $.ajax({
            type: 'POST',
            url: base_url + 'develop/actualizar_fecha_sincro/' + opcion_id,
            success : function(){
                mostrar_resultado();
            }
        });
    }
    
    function mostrar_resultado()
    {
        $('#estado').html('<h4 class="alert_success">la tabla [' + tabla + '] fue sincronizada correctamente</h4>');
        $('#cant_sincronizados_' + tabla).html(cant_sincronizados);
        $('#fecha_sincro_' + tabla).html('Hace un momento');
        $('#hace_sincro' + tabla).html('Hace un momento');
    }
</script>

<?php $this->load->view('datos/procesos/procesos_menu_v'); ?>

<div class="bs-caja">
    
    <table class="table table-hover" id="tabla_proceso">
        <thead>
            <th>Tabla</th>
            <th>Sincronizar</th>
            <th>Sincronizados</th>
            <th>Fecha sincronización</th>
            <th>Hace</th>
        </thead>
        <tbody>
            <?php foreach ($tablas as $key => $tabla) : ?>
                <tr>
                    <td>
                        <?= $tabla ?>
                    </td>
                    <td>
                        <span id="sincro_<?= $tabla ?>" class="btn btn-default sincro" data-table="<?= $tabla ?>" data-key="<?= $key ?>">
                            <i class="fa fa-refresh"></i>
                        </span>
                    </td>
                    <td>
                        <span  id="cant_sincronizados_<?= $tabla ?>" class="etiqueta informacion w2"></span>
                    </td>
                    <td id="fecha_sincro_<?= $tabla ?>">
                        <?= $fechas_sincro[$tabla] ?>
                    </td>

                    <td id="hace_sincro">
                        <?= $this->Pcrn->tiempo_hace($fechas_sincro[$tabla]) ?>
                    </td>
                </tr>
            <?php endforeach ?>

        </tbody>
    </table>         
</div>

<div class="div3" id="estado">
    
</div>