<div class="modal" tabindex="-1" role="dialog" id="delete_modal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Retirar producto</h5>
            </div>
            <div class="modal-body">
                <p>¿Confirma que desea eliminar este producto del carrito?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" v-on:click="delete_element" data-dismiss="modal">
                    Eliminar
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>