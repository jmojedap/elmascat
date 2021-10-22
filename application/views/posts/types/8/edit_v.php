<div id="edit_app">
    <div class="center_box_750">
        <div class="card">
            <div class="card-body">
                <form accept-charset="utf-8" method="POST" id="edit_form" @submit.prevent="send_form">
                    <fieldset v-bind:disabled="loading">
                        <input type="hidden" name="id" value="<?= $row->id ?>">
                        <div class="form-group row">
                            <label for="nombre_post" class="col-md-4 col-form-label text-right">Nombre</label>
                            <div class="col-md-8">
                                <input
                                    name="nombre_post" type="text" class="form-control"
                                    required
                                    title="Nombre" placeholder="Nombre"
                                    v-model="form_values.nombre_post"
                                >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="resumen" class="col-md-4 col-form-label text-right">Resumen</label>
                            <div class="col-md-8">
                                <textarea
                                    name="resumen" type="text" class="form-control" rows="2"
                                    required
                                    title="Resumen" placeholder="Resumen"
                                    v-model="form_values.resumen"
                                ></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="publicado" class="col-md-4 col-form-label text-right">Fecha publicación</label>
                            <div class="col-md-8">
                                <input
                                    name="publicado" type="text" class="form-control"
                                    required
                                    title="Fecha publicación" placeholder="Fecha publicación"
                                    v-model="form_values.publicado"
                                >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="code" class="col-md-4 col-form-label text-right">Código</label>
                            <div class="col-md-8">
                                <input
                                    name="code" type="text" class="form-control"
                                    required
                                    title="Código" placeholder="Código"
                                    v-model="form_values.code"
                                >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="estado" class="col-md-4 col-form-label text-right">Estado</label>
                            <div class="col-md-8">
                                <input
                                    name="estado" type="text" class="form-control"
                                    required
                                    title="Estado" placeholder="Estado"
                                    v-model="form_values.estado"
                                >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="slug" class="col-md-4 col-form-label text-right">Slug</label>
                            <div class="col-md-8">
                                <input
                                    name="slug" type="text" class="form-control"
                                    required
                                    title="Slug" placeholder="Slug"
                                    v-model="form_values.slug"
                                >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="imagen_id" class="col-md-4 col-form-label text-right">ID imagen portada</label>
                            <div class="col-md-8">
                                <input
                                    name="imagen_id" type="text" class="form-control"
                                    title="ID Cover"
                                    v-model="form_values.imagen_id"
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