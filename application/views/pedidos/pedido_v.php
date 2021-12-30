<p>
    <span class="text-muted">Cliente </span>
    <span class="text-primary">
        <?= $row->nombre ?> <?= $row->apellidos ?>
    </span>
    <span class="text-muted"> &middot; </span>
    
    <span class="text-muted">Valor </span>
    <span class="text-primary">
        <?= $this->Pcrn->moneda($row->valor_total) ?>
    </span>
    <span class="text-muted"> &middot; </span>
    
    <span class="text-muted">Creado </span>
    <span class="text-primary">
        <?= $this->Pcrn->fecha_formato($row->creado, 'Y-M-d') ?>
    </span>
    <span class="text-muted">
        Hace <?= $this->Pcrn->tiempo_hace($row->creado); ?>
    </span>
    <span class="text-muted"> &middot; </span>
    
    <span class="text-muted">
        Estado Pago
    </span>
    <?php if ( $row->payed ) : ?>
        <span class="text-primary">
            <i class="fa fa-check-circle text-success"></i>
            Pagado
        </span>
    <?php else: ?>
        <i class="fa fa-info-circle text-warning"></i>
        <span class="text-info">No pagado</span>
    <?php endif; ?>
</p>