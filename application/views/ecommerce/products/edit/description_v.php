<?php $this->load->view('assets/summernote') ?>
<?php $this->load->view('assets/bs4_chosen') ?>

<div id="edit_description_app">
    <div class="card">
        <div class="card-body">
            <form accept-charset="utf-8" method="POST" id="product_form" @submit.prevent="send_form">
                <input type="hidden" name="id" value="<?= $row->id ?>">
                <fieldset v-bind:disabled="loading">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="puntaje" class="col-md-4 col-form-label text-right">Puntaje ({{ row.puntaje }})</label>
                                <div class="col-md-8">
                                    <input
                                        name="puntaje" type="range" class="w100pc" min="0" max="100"
                                        v-model="form_values.puntaje"
                                    >
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="estado" class="col-md-4 col-form-label text-right">Estado</label>
                                <div class="col-md-8">
                                    <select name="estado" v-model="form_values.estado" class="form-control" required>
                                        <option v-for="(option_estado, key_estado) in options_estado" v-bind:value="key_estado">{{ option_estado }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="nombre_producto" class="col-md-4 col-form-label text-right">Nombre producto</label>
                                <div class="col-md-8">
                                    <input
                                        name="nombre_producto" type="text" class="form-control"
                                        required
                                        title="Nombre producto" placeholder="Nombre producto"
                                        v-model="form_values.nombre_producto"
                                    >
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="referencia" class="col-md-4 col-form-label text-right">Referencia</label>
                                <div class="col-md-8">
                                    <input
                                        name="referencia" type="text" class="form-control"
                                        required
                                        title="Referencia" placeholder="Referencia"
                                        v-model="form_values.referencia" v-on:change="validate_form"
                                        v-bind:class="{ 'is-invalid': validation.referencia_unique == false }"
                                    >
                                    <span class="invalid-feedback">
                                        La referencia escrita ya está asignada a otro producto
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="palabras_clave" class="col-md-4 col-form-label text-right">Palabras clave</label>
                                <div class="col-md-8">
                                    <input
                                        name="palabras_clave" type="text" class="form-control"
                                        required
                                        title="Palabras clave" placeholder="Palabras clave"
                                        v-model="form_values.palabras_clave"
                                    >
                                    <small class="form-text text-muted">
                                        No repita el nombre del producto, escriba palabras que ayuden a la búsqueda para los clientes
                                    </small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="categoria_id" class="col-md-4 col-form-label text-right">Categoría</label>
                                <div class="col-md-8">
                                    <select name="categoria_id" v-model="form_values.categoria_id" class="form-control" required>
                                        <option v-for="(option_categoria, key_categoria) in options_categoria" v-bind:value="key_categoria">{{ option_categoria }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="fabricante_id" class="col-md-4 col-form-label text-right">Fabricante / Marca</label>
                                <div class="col-md-8">
                                    <select name="fabricante_id" v-model="form_values.fabricante_id" class="form-control" required>
                                        <option v-for="(option_fabricante, key_fabricante) in options_fabricante" v-bind:value="key_fabricante">{{ option_fabricante }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="promocion_id" class="col-md-4 col-form-label text-right">Promoción</label>
                                <div class="col-md-8">
                                    <select name="promocion_id" v-model="form_values.promocion_id" class="form-control">
                                        <option v-for="(option_promocion, key_promocion) in options_promocion" v-bind:value="key_promocion">{{ option_promocion }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="tags" class="col-md-4 col-form-label text-right">Etiquetas</label>
                                <div class="col-md-8">
                                <select name="tags[]" id="tags" class="form-control-chosen" multiple>
                                    <?php foreach ($tags_activas->result() as $row_tag) : ?>
                                        <?php
                                            $selected = '';
                                            if ( $row_tag->activo > 0 ) { $selected = 'selected'; }

                                            $repeticiones_nivel = 4 * ($row_tag->nivel - 1);
                                        ?>
                                        <option value="0<?= $row_tag->id ?>" <?= $selected ?>>
                                            <?= str_repeat('&nbsp;', $repeticiones_nivel) ?>
                                            <?= $row_tag->nombre_tag ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                                </div>
                            </div>



                            
                            <div class="form-group row">
                                <div class="col-md-8 offset-md-4">
                                    <button class="btn btn-success w120p" type="submit">Guardar</button>
                                </div>
                            </div>
                            
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="descripcion">Descripción</label>
                                <textarea
                                    name="descripcion" type="text" class="summernote"
                                    v-model="form_values.descripcion"
                                ></textarea>
                            </div>
                        </div>
                    </div>

                <fieldset>
            </form>
        </div>
    </div>
</div>

<script>
// Variables
//-----------------------------------------------------------------------------
var row = <?= json_encode($row) ?>;
row.estado = '0<?= $row->estado ?>';
row.categoria_id = '0<?= $row->categoria_id ?>';
row.fabricante_id = '0<?= $row->fabricante_id ?>';
row.promocion_id = '0<?= $row->promocion_id ?>';


// VueApp
//-----------------------------------------------------------------------------
var edit_description_app = new Vue({
    el: '#edit_description_app',
    created: function(){
        //this.get_list()
    },
    data: {
        form_values: row,
        validation_status: 1,
        validation: {
            referencia_unique: 1
        },
        loading: false,
        options_estado: <?= json_encode($options_estado) ?>,
        options_categoria: <?= json_encode($options_categoria) ?>,
        options_fabricante: <?= json_encode($options_fabricante) ?>,
        options_promocion: <?= json_encode($options_promocion) ?>,
    },
    methods: {
        validate_form: function(){
            var form_data = new FormData(document.getElementById('product_form'))
            axios.post(url_api + 'productos/validate/' + this.form_values.id, form_data)
            .then(response => {
                this.validation = response.data.validation
                this.validation_status = response.data.status
            })
            .catch( function(error) {console.log(error)} )
        },
        send_form: function(){
            if ( this.validation_status == 1 )
            {
                this.loading = true
                var form_data = new FormData(document.getElementById('product_form'))

                axios.post(url_api + 'productos/update/' + this.form_values.id, form_data)
                .then(response => {
                    if ( response.data.saved_id > 0 ) {
                        toastr['success']('Guardado')
                    }
                    this.loading = false
                })
                .catch( function(error) {console.log(error)} )
            } else {
                toastr['error']('Hay datos incorrectos o incompletos en el formulario')
            }
        },
    }
})
</script>