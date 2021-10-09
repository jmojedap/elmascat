<?php $this->load->view('assets/toastr'); ?>

<?php
    $att_subtitulo = array(
        'id'     => 'campo-subtitulo',
        'name'   => 'subtitulo',
        'type'   => 'text',
        'class'  => 'form-control',
        'value'  => $row->subtitulo,
        'placeholder'   => 'Escriba un subtítulo',
        'title'   => 'Escriba un subtítulo para el archivo'
    );
    
    $att_palabras_clave = array(
        'id'     => 'campo-palabras_clave',
        'name'   => 'palabras_clave',
        'required'   => TRUE,
        'type'   => 'text',
        'class'  => 'form-control',
        'value'  => $row->palabras_clave,
        'placeholder'   => 'Palabras claves que describen al archivo, útil para las búsquedas',
        'title'   => 'Palabras claves que describen al archivo, útil para las búsquedas'
    );
    
    $att_descripcion = array(
        'id'     => 'campo-descripcion',
        'name'   => 'descripcion',
        'type'   => 'text',
        'class'  => 'form-control',
        'value'  => $row->descripcion,
        'placeholder'   => 'Descripción del archivo',
        'title'   => 'Descripción del archivo',
        'rows'   => 3
    );
    
    $att_link = array(
        'id'     => 'campo-link',
        'name'   => 'link',
        'type'   => 'url',
        'class'  => 'form-control',
        'value'  => $row->link,
        'placeholder'   => 'Link destino al hacer clic en la imagen',
        'title'   => 'Link de destino al hacer clic en la imagen',
        'rows'   => 3
    );
    
    $att_submit = array(
        'class' => 'btn btn-primary',
        'value' => 'Guardar'
    );
?>

<div id="archivo_edit">

    <div class="row">
        <div class="col col-sm-8">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form accept-charset="utf-8" method="POST" id="archivo_form" @submit.prevent="send_form" class="form-horizontal">
                        <fieldset v-bind:disabled="loading">
                            <input type="hidden" name="id" value="<?= $row->id ?>">
                            
                            <div class="form-group">
                                <label for="titulo_archivo" class="col-sm-3 control-label">Título archivo</label>
                                <div class="col-sm-9">
                                    <input
                                        name="titulo_archivo" type="text" class="form-control"
                                        required
                                        title="Escriba el título para el archivo" placeholder="Escriba el título para el archivo"
                                        v-model="form_values.titulo_archivo"
                                    >
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="subtitulo" class="col-sm-3 control-label">Subtítulo</label>
                                <div class="col-sm-9">
                                    <?= form_input($att_subtitulo); ?>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="palabras_clave" class="col-sm-3 control-label">Palabras clave *</label>
                                <div class="col-sm-9">
                                    <?= form_input($att_palabras_clave); ?>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="descripcion" class="col-sm-3 control-label">Descripción</label>
                                <div class="col-sm-9">
                                    <?= form_textarea($att_descripcion); ?>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="link" class="col-sm-3 control-label">Link</label>
                                <div class="col-sm-9">
                                    <?= form_input($att_link); ?>
                                </div>
                            </div>
        
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <?= form_submit($att_submit) ?>
                                </div>
                            </div>
                            
                        </fieldset>
                    </form>
                    
                    
                </div>
            </div>
        </div>
        
        <div class="col col-sm-4">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="sep1">
                        <?= img($att_img) ?>
                    </div>
                    <?= anchor("archivos/cambiar/{$row->id}", 'Cambiar', 'class="btn btn-default" title="Cambiar esta imagen"') ?>
                </div>
                
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
var archivo_edit = new Vue({
    el: '#archivo_edit',
    data: {
        form_values: row,
        loading: false,
    },
    methods: {
        send_form: function(){
            this.loading = true
            var form_data = new FormData(document.getElementById('archivo_form'))
            axios.post(url_api + 'archivos/update/', form_data)
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