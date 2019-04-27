<?php
    $seccion = $this->uri->segment(2);
    $clases[$seccion] = 'active';
    
    $titulo_sublugares = $this->Lugar_model->titulo_sublugares($row->tipo_id);
    
    if ( $seccion == 'sublugares' ) {
        $titulo_sublugares .= ' (' . $sublugares->num_rows() . ')';
    }
?>
    
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <?php if ( $row->tipo_id > 0 ){ ?>
            <li class="breadcrumb-item">
                <?= anchor("lugares/sublugares/{$row->continente_id}", $this->App_model->nombre_lugar($row->continente_id)) ?>
            </li>
        <?php } ?>

        <?php if ( $row->tipo_id > 1 ){ ?>
            <li class="breadcrumb-item">
                <?= anchor("lugares/sublugares/{$row->pais_id}", $row->pais) ?>
            </li>
        <?php } ?>

        <?php if ( $row->tipo_id > 2 ){ ?>
            <li class="breadcrumb-item">
                <?= anchor("lugares/sublugares/{$row->region_id}", $row->region) ?>
            </li>
        <?php } ?>

        <?php if ( $row->tipo_id > 3 ){ ?>
            <li class="breadcrumb-item">
                <?= anchor("lugares/sublugares/{$row->region_id}", $row->region) ?>
            </li>
        <?php } ?>
    </ol>
</nav>

<ul class="nav nav-tabs sep1">
    <li role="presentation" class="<?=  $clases['explorar'] ?>"><?= anchor("lugares/explorar/?tp=1", '<i class="fa fa-list-alt"></i>') ?></li>
    <li role="presentation" class="<?=  $clases['sublugares'] ?>"><?= anchor("lugares/sublugares/{$row->id}", '<i class="fa fa-list"></i> ' . $titulo_sublugares) ?></li>
    <li role="presentation" class="<?=  $clases['fletes'] ?>"><?= anchor("lugares/fletes/{$row->id}", '<i class="fa fa-list"></i> Fletes') ?></li>
    <li role="presentation" class="<?=  $clases['pedidos'] ?>"><?= anchor("lugares/pedidos/{$row->id}", '<i class="fa fa-shopping-cart"></i> Pedidos') ?></li>
    <li role="presentation" class="<?=  $clases['editar'] ?>"><?= anchor("lugares/editar/edit/{$row->id}", '<i class="fa fa-pencil"></i> Editar') ?></li>
</ul>

<?php $this->load->view($vista_b) ?>