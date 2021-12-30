<div class="modal fade" id="modal_payment" tabindex="-1" role="dialog" aria-labelledby="modal_paymentTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form accept-charset="utf-8" method="POST" id="payment_form" @submit.prevent="send_form">
              <input type="hidden" name="id" value="<?= $row->id ?>">
              <fieldset v-bind:disabled="loading">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_paymentTitle">Pago del pedido</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                  <div v-if="missing_data.length > 0">
                    <div class="alert alert-warning">
                      <p>
                        <strong>Datos faltantes:</strong>
                        {{ missing_data.join(', ') }}.
                      </p>
                    </div>
                    <div class="text-center">
                      <a href="<?= URL_ADMIN . "pedidos/editar/{$row->id}" ?>">Completar datos</a>
                    </div>
                  </div>
                  <div v-else>
                    <select name="payment_channel" v-model="form_values.payment_channel" class="form-control" required>
                      <option v-for="(option_payment_channel, key_payment_channel) in options_payment_channels" v-bind:value="key_payment_channel">{{ option_payment_channel }}</option>
                    </select>
                  </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
              <fieldset>
            </form>
        </div>
    </div>
</div>