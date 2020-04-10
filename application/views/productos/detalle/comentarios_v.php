<?php
    $att_contenido = array(
        'id'     => 'contenido',
        'name'   => 'contenido',
        'class'   => 'required-entry',
        'rows'   => 4,
        'style'   => 'width: 100%'
    );
?>

<div id="customer-reviews">
    <?php if ( $this->session->userdata('logged') ){ ?>
        <h3>Escriba su comentario</h3>

        <?= form_open("productos/guardar_comentario/{$row->id}") ?>
            <div class="input-box">
                <?= form_textarea($att_contenido) ?>
            </div>
            
            <button type="submit" class="button submit">
                <span>
                    Enviar
                </span>
            </button>
            <?= form_hidden('producto_id', $row->id) ?>

        <?= form_close('') ?>
    <?php } else { ?>
        <?= anchor("accounts/login", 'Agregar comentario', 'class="btn btn-primary"') ?>
    <?php } ?>
</div>

<hr/>

<ul>
    <?php foreach ($comentarios->result() as $row_comentario) : ?>
        <li>
            <div class="review">
                <h6><i class="fa fa-user"></i> <?= $row_comentario->nombre_usuario ?></h6>
                <small>
                    <i class="fa fa-calendar-o"></i>
                    <?= $this->Pcrn->fecha_formato($row_comentario->creado, 'd M Y'); ?>
                    <?= $this->Pcrn->fecha_formato($row_comentario->creado, 'H:i A'); ?>
                </small>
                <div class="review-txt" style="border-bottom: 1px solid #dfdfdf; padding-bottom: 12px;">
                    <?= $row_comentario->texto_comentario ?>
                </div>
            </div>
        </li>
    <?php endforeach ?>
</ul>