<?php
    $filters_style = ( strlen($str_filters) > 0 ) ? '' : 'display: none;' ;

    $options_peso = array(
        '' => 'Todos',
        '01' => 'Con peso',
        '02' => 'Sin peso',
    );

    $options_crpol = array(
        '' => '[ Todos ]',
        '01' => 'Transacción aprobada',
        '02' => 'Otra',
    );
?>

<form accept-charset="utf-8" method="POST" id="search_form" @submit.prevent="get_list">
    <div class="form-group row">
        <div class="col-md-9">
            <div class="input-group mb-2">
                <input
                    place="text"
                    name="q"
                    class="form-control"
                    placeholder="Buscar"
                    autofocus
                    title="Buscar"
                    v-model="filters.q"
                    v-on:change="get_list"
                    >
                <div class="input-group-append" title="Buscar">
                    <button type="button" class="btn btn-light btn-block" v-on:click="toggle_filters" title="Búsqueda avanzada">
                        <i class="fa fa-chevron-up" v-show="showing_filters"></i>
                        <i class="fa fa-chevron-down" v-show="!showing_filters"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary btn-block">
                <i class="fa fa-search"></i>
                Buscar
            </button>
        </div>
    </div>
    <div id="adv_filters" style="<?= $filters_style ?>">
        <div class="form-group row">
            <div class="col-md-9">
                <?= form_dropdown('status', $options_status, $filters['status'], 'class="form-control" title="Filtrar por estado pedido"'); ?>
            </div>
            <label for="status" class="col-md-3 control-label col-form-label">Estado pedido</label>
        </div>
        <div class="form-group row">
            <div class="col-md-9">
                <?= form_dropdown('fe2', $options_crpol, $filters['fe2'], 'class="form-control" title="Filtrar por Respuesta PayU"'); ?>
            </div>
            <label for="status" class="col-md-3 control-label col-form-label">Respuesta PayU</label>
        </div>
        <div class="form-group row">
            <div class="col-md-9">
                <?= form_dropdown('fe1', $options_peso, $filters['fe1'], 'class="form-control" title="Filtrar por tipo peso"'); ?>
            </div>
            <label for="status" class="col-md-3 col-form-label">Tipo peso</label>
        </div>
        <div class="form-group row">
            <div class="col-md-9">
                <input
                    type="date"
                    name="d1"
                    value="<?= $filters['d1'] ?>"
                    class="form-control"
                    title="Creados desde"
                    >
            </div>
            <label for="d1" class="col-md-3 col-form-label">Creado desde</label>
        </div>
    </div>
</form>