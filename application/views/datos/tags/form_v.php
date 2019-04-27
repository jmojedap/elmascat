<!-- Modal -->
<div class="modal fade" id="modal_form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form accept-charset="utf-8" method="POST" id="tag_form" @submit.prevent="send_form">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Etiquetas</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-right">
                        Nombre etiqueta
                    </div>
                    <div class="col-md-8">
                        <input
                            id="field-item"
                            type="text"
                            name="item"
                            required
                            class="form-control col-md-8"
                            placeholder="Nombre etiqueta"
                            title="Nombre etiqueta"
                            v-model="form_values.item"
                            >
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class="col-md-4 text-right">
                        Descripción
                    </div>
                    <div class="col-md-8">
                        <input
                            type="text"
                            name="descripcion"
                            required
                            class="form-control"
                            placeholder="Descripción"
                            title="Descripción"
                            v-model="form_values.descripcion"
                            >
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class="col-md-4 text-right">
                        Etiqueta padre
                    </div>
                    <div class="col-md-8">
                        <select name="padre_id" id="field-padre_id" class="form-control" v-model="form_values.padre_id">
                            <option value="00">
                                [ Ninguna ]
                            </option>
                            <option v-for="etiqueta in list" v-bind:value="`0` + etiqueta.id" v-if="etiqueta.id != element_id">
                                {{ etiqueta.nombre_tag }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
  </div>
</div>