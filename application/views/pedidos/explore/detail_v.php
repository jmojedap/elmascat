<!-- Modal -->
<div class="modal fade" id="detail_modal" tabindex="-1" role="dialog" aria-labelledby="detail_modal_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detail_modal_label">{{ element.cod_pedido }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td>ID</td>
                        <td>{{ element.id }}</td>
                    </tr>
                    <tr>
                        <td>Cliente</td>
                        <td>
                            {{ element.nombre }} {{ element.apellidos }}
                        </td>
                    </tr>
                    <tr>
                        <td>Dirección</td>
                        <td>
                            {{ element.direccion }}
                            <br>
                            {{ element.ciudad }}
                        </td>
                    </tr>
                </table>
                <p>
                    <span v-show="element.factura">
                        <span class="text-muted">Factura: </span>
                        {{ element.factura }}
                    </span>
                    |
                    <span v-show="element.no_guia">
                        <span class="text-muted">Guía: </span>
                        {{ element.no_guia }}
                    </span>
                    |
                    <span class="text-muted">Celular: </span>
                    <span>{{ element.celular }}</span>
                </p>
            </div>
            <div class="modal-footer">
                    <a class="btn btn-primary w100p" v-bind:href="`<?= base_url('pedidos/info/') ?>` + element.id">Abrir</a>
                    <button type="button" class="btn btn-secondary w100p" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>