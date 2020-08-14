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
    <span class="text-primary">
        <?= $this->App_model->nombre_item($row->codigo_respuesta_pol, 1, 10); ?>
    </span>
    <span class="text-muted"> &middot; </span>
</p>