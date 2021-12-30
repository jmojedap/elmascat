<!-- Modal -->
<div class="modal fade" id="modal_form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form accept-charset="utf-8" method="POST" id="tag_form" @submit.prevent="send_form">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Etiquetas</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <div class="col-md-4 text-right col-form-label">
                        Nombre etiqueta
                    </div>
                    <div class="col-md-8">
                        <input
                            type="text" name="item" required class="form-control" placeholder="" title="Nombre etiqueta"
                            v-model="form_values.item"
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-4 text-right col-form-label">
                        Descripción
                    </div>
                    <div class="col-md-8">
                        <input
                            type="text" name="descripcion"
                            required class="form-control" title="Descripción"
                            v-model="form_values.descripcion"
                            >
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-4 text-right col-form-label">
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
                <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
  </div>
</div>