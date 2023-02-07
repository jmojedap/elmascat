<?php
    $condition = "producto_id = {$row->id} AND pedido_id IN (SELECT id FROM pedido WHERE codigo_respuesta_pol = 1)";
    $qty_sold = $this->Db_model->num_rows('pedido_detalle', $condition);

    $pct_sold = 0;
    if ( $row->cant_disponibles > 0 ) $pct_sold = 100 * $qty_sold / $row->cant_disponibles;
    
    if ( $pct_sold > 100 ) $pct_sold = 100;

    $cl_progress = '';
    if ( $pct_sold <= 20 ) $cl_progress = 'bg-danger';
    if ( $pct_sold >= 90 ) $cl_progress = 'bg-success';
?>

<div id="info_producto_app">
    <div class="row">
        <div class="col-md-3">
            <img v-bind:src="curr_image.url" alt="Imagen producto" class="rounded w100pc mb-2" onerror="this.src='<?= URL_IMG ?>app/262px_producto.png'">
            
            <div>
                <img v-for="(image, ik) in images" v-bind:src="image.url_thumbnail"
                    alt="Imagen producto" class="w40p mr-1 rounded pointer"
                    v-on:click="set_image(ik)"
                    onerror="this.src='<?= URL_IMG ?>app/262px_producto.png'"
                    >
            </div>

            <hr>
        </div>
        <div class="col-md-6">
            <h3 class="text-center">Descripción general</h3>
            <table class="table bg-white">
                <tbody>
                    <tr>
                        <td></td>
                        <td>
                            <a href="<?= URL_APP . "productos/detalle/{$row->id}" ?>" class="btn btn-light w120p" target="_blank">
                                Vista previa
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-title">Categoría</td>
                        <td>
                            <strong>
                                <a href="<?= URL_ADMIN . "productos/explore/1/?cat=0{$row->categoria_id}" ?>">
                                    <?= $this->Item_model->nombre(25, $row->categoria_id) ?>
                                </a>
                            </strong>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-title">Nombre</td>
                        <td><?= $row->nombre_producto ?></td>
                    </tr>
                    <tr>
                        <td class="td-title">Precio</td>
                        <td>
                            {{ producto.precio | currency }}
                        </td>
                    </tr>
                    <tr>
                        <td class="td-title">Referencia</td>
                        <td><?= $row->referencia ?></td>
                    </tr>
                    <tr>
                        <td class="td-title">Descripción</td>
                        <td v-html="producto.descripcion"></td>
                    </tr>
                    <tr>
                        <td class="td-title">Etiquetas</td>
                        <td v-html="producto.labels"></td>
                    </tr>
                    <tr>
                        <td class="td-title">Marca / Fabricante</td>
                        <td>
                            <a href="<?= URL_ADMIN . "productos/explore/?fab={$row->fabricante_id}" ?>">
                                <?= $this->Item_model->nombre_id($row->fabricante_id) ?>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-title">Peso</td>
                        <td><?= $row->peso ?> g</td>
                    </tr>
                    <tr>
                        <td class="td-title">Alto - Ancho - Grosor (cm)</td>
                        <td><?= $row->alto ?> &middot; <?= $row->ancho ?> &middot; <?= $row->grosor ?></td>
                    </tr>
                    <tr>
                        <td class="td-title">Disponibles</td>
                        <td>
                            <?= $row->cant_disponibles ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-title">Cantidad vendidas</td>
                        <td>
                        <div class="progress">
                            <div class="progress-bar <?= $cl_progress ?>" role="progressbar" style="width: <?= $pct_sold ?>%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><?= $qty_sold ?></div>
                        </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <h3 class="text-center">Metadatos / Especificaciones</h3>

            <table class="table bg-white">
                <tbody>
                    <?php foreach ($metadatos->result() as $row_metadato) : ?>
                        <tr>
                            <td class="td-title"><?= $row_metadato->nombre_metadato ?></td>
                            <td>
                                <?= $row_metadato->valor ?>
                            </td>    
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>

            <h3 class="text-center">Historial de edición</h3>
            <table class="table bg-white">
                <thead>
                    <th>Fecha</th>
                    <th>Hace</th>
                    <th>Usuario</th>
                </thead>
                <tbody>
                    <?php foreach ( $ediciones->result() as $edicion ) : ?>
                        <tr>
                            <td><?= $edicion->fecha ?></td>
                            <td><?= $this->pml->ago($edicion->fecha) ?></td>
                            <td>
                                <a href="<?= URL_ADMIN . "usuarios/profile/{$edicion->usuario_id}" ?>">
                                <?= $this->App_model->nombre_usuario($edicion->usuario_id, 'na') ?>
                                </a>
                            </td>
                        </tr>
                        
                    <?php endforeach ?>
                </tbody>
            </table>

            <table class="table bg-white">
                <tbody>
                    <tr>
                        <td class="td-title">Editado por</td>
                        <td><?= $this->App_model->nombre_usuario($row->editor_id, 'na');     ?></td>
                    </tr>
                    <tr>
                        <td class="td-title">Fecha edición</td>
                        <td v-bind:title="producto.editado">{{ producto.editado }} &middot; {{ producto.editado | ago }}</td>
                    </tr>
                    <tr>
                        <td class="td-title">Fecha creación</td>
                        <td v-bind:title="producto.editado">{{ producto.creado }} &middot; {{ producto.creado | ago }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
</div>

<script>
// Filters
//-----------------------------------------------------------------------------
Vue.filter('ago', function (date) {
    if (!date) return ''
    return moment(date, 'YYYY-MM-DD HH:mm:ss').fromNow()
});

Vue.filter('currency', function (value) {
    if (!value) return ''
    value = '$ ' + new Intl.NumberFormat().format(value)
    return value
});

// VueApp
//-----------------------------------------------------------------------------
var info_producto_app = new Vue({
    el: '#info_producto_app',
    created: function(){
        this.set_image(0)
    },
    data: {
        producto: <?= json_encode($row) ?>,
        images: <?= json_encode($images->result()) ?>,
        ediciones: <?= json_encode($ediciones->result()) ?>,
        curr_image: { url: '' },
        loading: false,
    },
    methods: {
        set_image: function(image_key){
            if ( this.images.length > 0 ) this.curr_image = this.images[image_key]
            
        },
    }
})
</script>