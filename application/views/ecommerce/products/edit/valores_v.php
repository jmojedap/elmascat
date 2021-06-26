<div id="editar_producto">
    <div class="row center_box_920">
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <form accept-charset="utf-8" method="POST" id="product_form" @submit.prevent="send_form">
                        <fieldset v-bind:disabled="loading">
                            <input type="hidden" name="id" value="<?= $row->id ?>">

                            <div class="form-group row">
                                <label for="precio" class="col-md-4 col-form-label text-right">Precio de venta</label>
                                <div class="col-md-8">
                                    <input
                                        name="precio" type="number" class="form-control" min="50"
                                        required
                                        title="Precio de venta"
                                        v-model="form_values.precio" v-on:change="update_dependents"
                                    >
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="iva_porcentaje" class="col-md-4 col-form-label text-right">% IVA</label>
                                <div class="col-md-8">
                                    <input
                                        name="iva_porcentaje" type="number" class="form-control" min="0" max="100"
                                        required
                                        title="% IVA"
                                        v-model="form_values.iva_porcentaje" v-on:change="update_dependents"
                                    >
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="precio_base" class="col-md-4 col-form-label text-right">Precio base</label>
                                <div class="col-md-8">
                                    <input
                                        name="precio_base" type="number" class="form-control" required min="1" step="0.01"
                                        v-model="form_values.precio_base" v-on:change="update_dependents"
                                    >
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="iva" class="col-md-4 col-form-label text-right">$ Valor IVA</label>
                                <div class="col-md-8">
                                    <input
                                        name="iva" type="number" class="form-control" required min="0" step="0.01"
                                        v-model="form_values.iva" v-on:change="update_dependents"
                                    >
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="costo" class="col-md-4 col-form-label text-right">Costo</label>
                                <div class="col-md-8">
                                    <input
                                        name="costo" type="number" class="form-control" min="1" required
                                        v-model="form_values.costo"
                                    >
                                </div>
                            </div>

                            <hr>

                            <div class="form-group row">
                                <label for="cant_disponibles" class="col-md-4 col-form-label text-right">Cantidad disponibles</label>
                                <div class="col-md-8">
                                    <input
                                        name="cant_disponibles" type="number" class="form-control" min="0"
                                        required title="Cantidades disponibles"
                                        v-model="form_values.cant_disponibles"
                                    >
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="peso" class="col-md-4 col-form-label text-right">Peso en gramos</label>
                                <div class="col-md-8">
                                    <input
                                        name="peso" type="number" class="form-control" min="0" required
                                        v-model="form_values.peso"
                                    >
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="ancho" class="col-md-4 col-form-label text-right">Ancho (cm)</label>
                                <div class="col-md-8">
                                    <input
                                        name="ancho" type="number" class="form-control" required min="0"  step="0.1"
                                        v-model="form_values.ancho"
                                    >
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="alto" class="col-md-4 col-form-label text-right">Alto (cm)</label>
                                <div class="col-md-8">
                                    <input
                                        name="alto" type="text" class="form-control" required min="0"  step="0.1"
                                        v-model="form_values.alto"
                                    >
                                </div>
                            </div>

                            
                            <div class="form-group row">
                                <div class="col-md-8 offset-md-4">
                                    <button class="btn btn-primary w120p" type="submit">Guardar</button>
                                </div>
                            </div>
                        <fieldset>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <?php $this->load->view($this->views_folder . 'prices_table_v') ?>
        </div>
    </div>
</div>

<script>
// Variables
//-----------------------------------------------------------------------------
var row = <?= json_encode($row) ?>;

var editar_producto = new Vue({
    el: '#editar_producto',
    created: function(){
        //this.get_list()
    },
    data: {
        form_values: row,
        loading: false,
    },
    methods: {
        send_form: function(){
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
        },
        update_dependents: function(){
            var precio_base = this.form_values.precio / ( 1 + this.form_values.iva_porcentaje/100)
            var iva = parseFloat(this.form_values.precio) - precio_base
            this.form_values.iva = iva.toFixed(2)
            this.form_values.precio_base = precio_base.toFixed(2)
        },
    }
})
</script>