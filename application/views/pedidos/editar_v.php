<div id="edit_app">
    <div class="card center_box_750">
        <div class="card-body">
            <form accept-charset="utf-8" method="POST" id="pedido_form" @submit.prevent="send_form">
                <div class="form-group row">
                    <label for="estado_pedido" class="col-md-4 col-form-label text-right">Estado</label>
                    <div class="col-md-8">
                        <?= form_dropdown('estado_pedido', $opciones_estado, $row->estado_pedido, 'class="form-control"') ?>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="factura" class="col-md-4 col-form-label text-right">Número factura</label>
                    <div class="col-md-8">
                        <input name="factura" type="text" class="form-control" title="Número factura" value="<?= $row->factura ?>">
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="no_guia" class="col-md-4 col-form-label text-right">Número guía</label>
                    <div class="col-md-8">
                        <input name="no_guia" id="field-no_guia" type="text" class="form-control" value="<?= $row->no_guia ?>">
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="notas_admin" class="col-md-4 col-form-label text-right">Notas internas</label>
                    <div class="col-md-8">
                        <textarea
                            name="notas_admin" id="field-notas_admin" type="text" class="form-control"
                            title="Notas internas sobre el pedido" placeholder="Notas internas sobre el pedido" rows="4"
                        ><?= $row->notas_admin ?></textarea>
                    </div>
                </div>
                
                <div class="form-group row">
                    <div class="offset-md-4 col-md-8">
                        <button class="btn btn-primary w120p" type="submit">
                            Guardar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    new Vue({
        el: '#edit_app',
        created: function(){
            //this.get_list();
        },
        data: {
            pedido_id: '<?= $row->id ?>'
        },
        methods: {
            send_form: function(){
                axios.post(url_api + 'pedidos/guardar_admin/' + this.pedido_id, $('#pedido_form').serialize())
                .then(response => {
                    if ( response.data.status == 1 ) {
                        toastr['success'](response.data.message);
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });  
            },
        }
    });
</script>