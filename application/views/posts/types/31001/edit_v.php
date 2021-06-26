<div id="edit_app">
    <div class="center_box_750">
        <div class="card">
            <div class="card-body">
                <form accept-charset="utf-8" method="POST" id="edit_form" @submit.prevent="send_form">
                    <fieldset v-bind:disabled="loading">
                        <input type="hidden" name="id" value="<?= $row->id ?>">

                        <div class="form-group row">
                            <label for="nombre_post" class="col-md-4 col-form-label text-right">Nombre promoción</label>
                            <div class="col-md-8">
                                <input
                                    name="nombre_post" type="text" class="form-control"
                                    required
                                    title="Nombre promoción" placeholder="Nombre promoción"
                                    v-model="form_values.nombre_post"
                                >
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="referente_1_id" class="col-md-4 col-form-label text-right">% descuento</label>
                            <div class="col-md-8">
                                <input
                                    name="referente_1_id" type="number" class="form-control"
                                    required
                                    title="% descuento" placeholder="% descuento"
                                    v-model="form_values.referente_1_id"
                                >
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="resumen" class="col-md-4 col-form-label text-right">Descripción</label>
                            <div class="col-md-8">
                                <textarea
                                    name="resumen" rows="3" class="form-control"
                                    required
                                    title="Descripción" placeholder="Descripción"
                                    v-model="form_values.resumen"
                                ></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="estado" class="col-md-4 col-form-label text-right">Promoción activa</label>
                            <div class="col-md-8">
                                <select name="estado" v-model="form_values.estado" class="form-control" required>
                                    <option v-for="(option_estado, key_estado) in options_estado" v-bind:value="key_estado">{{ option_estado }}</option>
                                </select>
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
</div>

<script>
// Variables
//-----------------------------------------------------------------------------
var row = <?= json_encode($row) ?>;

// VueApp
//-----------------------------------------------------------------------------
var edit_app = new Vue({
    el: '#edit_app',
    created: function(){
        //this.get_list()
    },
    data: {
        form_values: row,
        options_estado: {'0': 'No', '1': 'Sí'},
        loading: false,
    },
    methods: {
        send_form: function(){
            this.loading = true
            var form_data = new FormData(document.getElementById('edit_form'))
            axios.post(url_api + 'posts/save/', form_data)
            .then(response => {
                if ( response.data.saved_id > 0 ) {
                    toastr['success']('Guardado')
                }
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
    }
})
</script>