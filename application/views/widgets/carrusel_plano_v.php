<?php
    $contador = 0;
?>

<div id="widget_carrusel" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
        <?php for ($i = 0; $i < $imagenes->num_rows() ; $i++) { ?>
            <?php
                $clase_indicador = '';
                if ( $i == 0 ) { $clase_indicador = 'active'; }
            ?>
            <li data-target="#widget_carrusel" data-slide-to="<?= $i ?>" class="<?= $clase_indicador ?>"></li>
        <?php } ?>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox" style="margin-top: 0px;">
        <?php foreach ($imagenes->result() as $row_imagen) : ?>
            <?php
            $clase_item = '';
            if ($contador == 0) {
                $clase_item = 'active';
            }
            $contador++;
            ?>
            <div class="item <?= $clase_item ?>">
                <?php if ( strlen($row_imagen->link) > 0 ){ ?>
                    <a href="<?= $this->Pcrn->preparar_url($row_imagen->link) ?>">
                        <img src="<?= $row_imagen->src ?>" alt="<?= $row_imagen->titulo ?>">
                    </a>
                <?php } else { ?>
                    <img src="<?= $row_imagen->src ?>" alt="<?= $row_imagen->titulo ?>">
                <?php } ?>
                
            </div>
        <?php endforeach ?>

    </div>

    <!-- Controls -->
    <a class="left carousel-control" href="#widget_carrusel" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#widget_carrusel" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>