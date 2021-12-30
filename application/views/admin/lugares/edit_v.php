<div id="edit_place">
    <div class="center_box_750">
        <div class="card">
            <div class="card-body">
                <form accept-charset="utf-8" method="POST" id="place_form" @submit.prevent="send_form">
                    <div class="form-group row">
                        <label for="tipo_id" class="col-md-4 col-form-label text-right">Tipo</label>
                        <div class="col-md-8">
                            <select name="tipo_id" v-model="form_values.tipo_id" class="form-control" required>
                                <option v-for="(option_type, key_type) in options_type" v-bind:value="key_type">{{ option_type }}</option>
                            </select>
                        </div>
                    </div>
                    <!-- UBICACIÓN ASCENDENCIA -->
                    <div class="form-group row">
                        <label for="pais_id" class="col-md-4 col-form-label text-right">País * </label>
                        <div class="col-md-8">
                            <select name="pais_id" v-model="form_values.pais_id" class="form-control" required v-on:change="get_regions">
                                <option v-for="(option_country, key_country) in options_country" v-bind:value="key_country">{{ option_country }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="region_id" class="col-md-4 col-form-label text-right">Departamento/Provincia</label>
                        <div class="col-md-8">
                            <select name="region_id" v-model="form_values.region_id" class="form-control">
                                <option v-for="(option_region, key_region) in options_region" v-bind:value="key_region">{{ option_region }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="nombre_lugar" class="col-md-4 col-form-label text-right">Nombre *</label>
                        <div class="col-md-8">
                            <input
                                name="nombre_lugar" type="text" class="form-control"
                                required
                                title="Nombre lugar" placeholder="Nombre lugar"
                                v-model="form_values.nombre_lugar"
                            >
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="full_name" class="col-md-4 col-form-label text-right">Nombre completo</label>
                        <div class="col-md-8">
                            <input name="full_name" type="text" class="form-control" v-model="form_values.full_name">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="palabras_clave" class="col-md-4 col-form-label text-right">Palabras clave</label>
                        <div class="col-md-8">
                            <input name="palabras_clave" type="text" class="form-control" v-model="form_values.palabras_clave">
                        </div>
                    </div>

                    <!-- CÓDIGOS -->
                    <div class="form-group row">
                        <label for="cod" class="col-md-4 col-form-label text-right">Código</label>
                        <div class="col-md-8">
                            <input name="cod" type="text" class="form-control" v-model="form_values.cod">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="cod_completo" class="col-md-4 col-form-label text-right">Código completo</label>
                        <div class="col-md-8">
                            <input name="cod_completo" type="text" class="form-control" v-model="form_values.cod_completo">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="cod_oficial" class="col-md-4 col-form-label text-right">Código oficial (DANE)</label>
                        <div class="col-md-8">
                            <input name="cod_oficial" type="text" class="form-control" v-model="form_values.cod_oficial">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="poblacion" class="col-md-4 col-form-label text-right">Población / Año</label>
                        <div class="col-md-4">
                            <input name="poblacion" type="number" class="form-control" required v-model="form_values.poblacion">
                        </div>
                        <div class="col-md-4">
                            <input name="population_year" type="number" class="form-control" required v-model="form_values.population_year">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-8 offset-md-4">
                            <button class="btn btn-primary w120p" type="submit">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Variables
//-----------------------------------------------------------------------------
    var row_place = <?= json_encode($row) ?>;
    row_place.tipo_id = '0<?= $row->tipo_id ?>';
    row_place.pais_id = '0<?= $row->pais_id ?>';
    row_place.region_id = '0<?= $row->region_id ?>';

// Vue Applicación
//-----------------------------------------------------------------------------
var edit_place = new Vue({
    el: '#edit_place',
    data: {
        row_id: <?= $row->id ?>,
        form_values: row_place,
        options_type: <?= json_encode($options_type) ?>,
        options_country: <?= json_encode($options_country) ?>,
        options_region: <?= json_encode($options_region) ?>,
    },
    methods: {
        send_form: function(){
            axios.post(url_api + 'lugares/save/' + this.row_id, $('#place_form').serialize())
            .then(response => {
                console.log(response.data)
                if ( response.data.saved_id > 0 )
                {
                    toastr['success']('Datos actualizados')
                }
            }).catch(function(error) {console.log(error)})  
        },
        get_regions: function(){
            var form_data = new FormData
            form_data.append('type', 3)
            form_data.append('fe1', this.form_values.pais_id)
            axios.post(url_api + 'lugares/get_options', form_data)
            .then(response => {
                this.options_region = response.data
                this.form_values.region_id = ''
            }).catch(function(error) {console.log(error)})
        },
    }
})
</script>