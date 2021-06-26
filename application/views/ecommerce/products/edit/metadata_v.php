<div id="edit_form_app">
    <div class="center_box_920">
        <div class="card">
            <div class="card-body">
                <form accept-charset="utf-8" method="POST" id="product_form" @submit.prevent="send_form">
                    <fieldset v-bind:disabled="loading">
                        <input type="hidden" name="id" value="<?= $row->id ?>">

                        <?php foreach ($metacampos->result() as $row_meta) : ?>
                            <?php
                                $value = $this->Meta_model->valor(3100, $row_meta->meta_id, $producto_id);
                            ?>
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label text-right" for="meta_<?= $row_meta->meta_id ?>">
                                    <?= $row_meta->nombre_meta ?>
                                </label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control"
                                        id="field-meta_<?= $row_meta->meta_id ?>" name="meta_<?= $row_meta->meta_id ?>"
                                        value="<?= $value ?>"
                                    >
                                    <small class="form-text text-muted">
                                        <?= $row_meta->descripcion ?>
                                    </small>
                                </div>
                            </div>
                        <?php endforeach ?>
                        
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
var edit_form_app = new Vue({
    el: '#edit_form_app',
    created: function(){
        //this.get_list()
    },
    data: {
        producto_id: <?= $row->id ?>,
        loading: false,
    },
    methods: {
        send_form: function(){
            this.loading = true
            var form_data = new FormData(document.getElementById('product_form'))
            axios.post(url_api + 'productos/guardar_metadatos/' + this.producto_id, form_data)
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

