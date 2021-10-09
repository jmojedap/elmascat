<div id="add_app">
    <div class="card center_box_750">
        <div class="card-body">
            <form accept-charset="utf-8" method="POST" id="product_form" @submit.prevent="send_form">
                <fieldset v-bind:disabled="loading">
                    <div class="form-group row">
                        <label for="referencia" class="col-md-4 col-form-label text-right">Referencia</label>
                        <div class="col-md-8">
                            <input
                                name="referencia" type="text" class="form-control" required title="Referencia" 
                                v-model="form_values.referencia" v-on:change="validate_form"
                                v-bind:class="{ 'is-invalid': validation.referencia_unique == false, 'is-valid': validation.referencia_unique == 1 }"
                            >
                            <span class="invalid-feedback">
                                La referencia escrita ya está asignada a otro producto
                            </span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="nombre_producto" class="col-md-4 col-form-label text-right">Nombre</label>
                        <div class="col-md-8">
                            <input
                                name="nombre_producto" type="text" class="form-control"
                                required
                                v-model="form_values.nombre_producto"
                            >
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
                        <label for="fabricante_id" class="col-md-4 col-form-label text-right">Fabricante/Marca</label>
                        <div class="col-md-8">
                            <select name="fabricante_id" v-model="form_values.fabricante_id" class="form-control" required>
                                <option v-for="(option_fabricante, key_fabricante) in options_fabricante" v-bind:value="key_fabricante">{{ option_fabricante }}</option>
                            </select>
                        </div>
                    </div>

                    
                    <div class="form-group row">
                        <div class="col-md-8 offset-md-4">
                            <button class="btn btn-success w120p" type="submit">Crear</button>
                        </div>
                    </div>
                <fieldset>
            </form>
        </div>
    </div>
    <?php $this->load->view('common/modal_created_v') ?>
</div>

<script>
// Variables
//-----------------------------------------------------------------------------
    var form_values = {
        referencia: '',
        nombre_producto: ''
    };

// VueApp
//-----------------------------------------------------------------------------
var add_app = new Vue({
    el: '#add_app',
    data: {
        form_values: form_values,
        validation_status: 0,
        validation: {
            referencia_unique: -1
        },
        row_id: 0,
        options_fabricante: <?= json_encode($options_fabricante) ?>,
        options_categoria: <?= json_encode($options_categoria) ?>,
        loading: false,
    },
    methods: {
        validate_form: function(){
            var form_data = new FormData(document.getElementById('product_form'))
            axios.post(url_api + 'productos/validate/', form_data)
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

                axios.post(url_api + 'productos/insert/', form_data)
                .then(response => {
                    if ( response.data.saved_id > 0 ) {
                        toastr['success']('Guardado')
                        this.row_id = response.data.saved_id
                        $('#modal_created').modal()
                    }
                    this.loading = false
                    this.clean_form()
                })
                .catch( function(error) {console.log(error)} )
            } else {
                toastr['error']('Hay datos incorrectos o incompletos en el formulario')
            }
        },
        clean_form: function(){
            for ( key in form_values ) this.form_values[key] = ''
        },
        go_created: function() {
            window.location = url_app + 'productos/edit/' + this.row_id
        }
    }
})
</script>