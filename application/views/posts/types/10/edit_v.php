<div id="edit_app">
    <div class="center_box_750">
        <div class="card">
            <div class="card-body">
                <form accept-charset="utf-8" method="POST" id="edit_form" @submit.prevent="send_form">
                    <fieldset v-bind:disabled="loading">
                        <input type="hidden" name="id" value="<?= $row->id ?>">
                        <div class="form-group row">
                            <label for="texto_2" class="col-md-4 col-form-label text-right">Módulo</label>
                            <div class="col-md-8">
                                <input
                                    name="texto_2" type="text" class="form-control"
                                    required
                                    title="Módulo" placeholder="Módulo"
                                    v-model="form_values.texto_2"
                                >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="nombre_post" class="col-md-4 col-form-label text-right">Nombre proceso</label>
                            <div class="col-md-8">
                                <input
                                    name="nombre_post" type="text" class="form-control"
                                    required
                                    title="Nombre proceso" placeholder="Nombre proceso"
                                    v-model="form_values.nombre_post"
                                >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="contenido" class="col-md-4 col-form-label text-right">Descripción</label>
                            <div class="col-md-8">
                                <textarea
                                    name="contenido" class="form-control" rows="3"
                                    required
                                    title="Descripción" placeholder="Descripción"
                                    v-model="form_values.contenido"
                                ></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="texto_1" class="col-md-4 col-form-label text-right">Link proceso</label>
                            <div class="col-md-8">
                                <input
                                    name="texto_1" type="text" class="form-control"
                                    required
                                    title="Link proceso" placeholder="Link proceso"
                                    v-model="form_values.texto_1"
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