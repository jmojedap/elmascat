<form accept-charset="utf-8" method="POST" id="extra_form" @submit.prevent="send_form">
    <input type="hidden" value="<?= $row->id ?>" name="pedido_id">
    <div class="form-group row">
        <label for="producto_id" class="col-md-4 col-form-label text-right">Extra tipo</label>
        <div class="col-md-8">
            <?= form_dropdown('producto_id', $options_extra, '00', 'class="form-control" v-model="form_values.producto_id"') ?>
        </div>
    </div>
    <div class="form-group row">
        <label for="precio" class="col-md-4 col-form-label text-right">$ Valor</label>
        <div class="col-md-8">
            <input
                type="text"
                id="field-precio"
                name="precio"
                required
                class="form-control"
                placeholder="Valor extra"
                title="Valor extra"
                v-model="form_values.precio"
                >
        </div>
    </div>

    <div class="form-group row">
        <label for="nota" class="col-md-4 col-form-label text-right">Nota</label>
        <div class="col-md-8">
            <input
                type="text"
                id="field-nota"
                name="nota"
                required
                class="form-control"
                placeholder="Nota"
                title="Nota"
                v-model="form_values.nota"
                >
        </div>
    </div>
    
    <div class="form-group row">
        <div class="col-md-4"></div>
        <div class="col-md-8">
            <button type="submit" class="btn btn-success w120p" v-show="edition_id == 0">Agregar</button>
            <button type="button" class="btn btn-secondary w120p" v-show="edition_id == 0" v-on:click="set_show_form(false)">Cancelar</button>

            <button type="submit" class="btn btn-primary w120p" v-show="edition_id > 0">Actualizar</button>
            <button type="button" class="btn btn-secondary w120p" v-show="edition_id > 0" v-on:click="cancel_edition">Cancelar</button>
        </div>
    </div>
</form>