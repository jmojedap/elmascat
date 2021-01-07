<a href="<?= base_url("pedidos/compra_a") ?>" class="btn btn-polo w120p">
    <i class="fa fa-arrow-left"></i>
    Atrás
</a>

<hr>

<p class="text-center">
    Tu pedido será empacado con papel regalo, no se le enviará información de precios
     y se le agregará una <b class="text-primary">tarjeta</b> con los siguientes datos:
</p>

<p class="text-center">
    También puedes agregar a tu pedido una <strong>bolsa</strong> de regalo, mira 
    <a href="<?= base_url("productos/catalogo/?fab=528") ?>">
        aquí
    </a>
     las que tenemos: 
    <br>
    <a href="<?= base_url("productos/catalogo/?fab=528") ?>" class="btn btn-light">
        Catálogo Corazón de Papel
    </a>
</p>

<div id="datos_regalo_app">
    <form method="post" accept-charset="utf-8" class="form-horizontal" id="datos_regalo_form" @submit.prevent="send_form">
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="nombre" class="col-sm-4 control-label">De</label>
                    <div class="col-sm-8">
                        <input
                            type="text"
                            id="field-regalo_de"
                            name="regalo_de"
                            required
                            class="form-control input-lg"
                            placeholder="¿Quién hace el regalo?"
                            title=""
                            maxlength="30"
                            v-model="meta.regalo.de"
                            >
                    </div>
                </div>
                <div class="form-group">
                    <label for="nombre" class="col-sm-4 control-label">Para</label>
                    <div class="col-sm-8">
                        <input
                            type="text"
                            id="field-regalo_para"
                            name="regalo_para"
                            required
                            class="form-control input-lg"
                            placeholder="¿Quién recibe el regalo?"
                            title=""
                            maxlength="30"
                            v-model="meta.regalo.para"
                            >
                    </div>
                </div>
                <div class="form-group">
                    <label for="notas" class="col-md-4 control-label">Mensaje o dedicatoria <br> ({{ 200 - meta.regalo.mensaje.length }})</label>
                    <div class="col-md-8">
                        <textarea
                            rows="4"
                            name="regalo_mensaje"
                            class="form-control input-lg"
                            placeholder="Mensaje o dedicatoria"
                            title="Mensaje o dedicatoria"
                            maxlength="200"
                            v-model="meta.regalo.mensaje"
                            ></textarea>
                    </div>
                </div>
                
            </div>
            <div class="col-md-4">
                <?php $this->load->view('pedidos/compra/totales_v'); ?>
                <button class="btn btn-polo-lg btn-block" type="submit">
                    <i class="fa fa-check"></i>
                    Continuar
                </button>
            </div>
        </div>
    </form>
</div>

<script>
// Variables
//-----------------------------------------------------------------------------
    

// Vue App
//-----------------------------------------------------------------------------

    new Vue({
        el: '#datos_regalo_app',
        created: function(){
            //this.get_list();
        },
        data: {
            cod_pedido: '<?= $cod_pedido ?>',
            meta: <?= json_encode($arr_meta); ?>,
        },
        methods: {
            send_form: function(){
                axios.post(url_app + 'pedidos/guardar_datos_regalo/', $('#datos_regalo_form').serialize())
                .then(response => {
                    if ( response.data.status == 1) {
                        window.location = url_app + 'pedidos/compra_b'
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
        }
    });
</script>

<div class="pull-right">
    <img src="<?= URL_IMG ?>app/positivessl_trust_seal_md_167x42.png" alt="payment"> 
</div>