<div class="card card-default">
    <div class="card-header">Extras</div>
    <!-- /.card-header -->
    <div class="table-responsive">
        <table class="table">
            <thead>
                <th>Concepto</th>
                <th>Nota</th>
                <th>Valor</th>
            </thead>
            <tbody>
                <tr class="info">
                    <td>Total</td>
                    <td></td>
                    <td>
                        <b>
                            <?= $this->Pcrn->moneda($row->total_extras); ?>
                        </b>
                    </td>
                </tr>
                <?php foreach ($extras->result() as $row_extra) : ?>
                <tr>
                    <td><?= $this->App_model->nombre_item($row_extra->producto_id, 1, 6) ?></td>
                    <td><?= $row_extra->nota; ?></td>
                    <td>
                        <span>
                            <?= $this->Pcrn->moneda($row_extra->precio) ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
</div>