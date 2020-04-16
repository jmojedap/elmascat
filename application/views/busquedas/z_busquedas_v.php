<?php
    $data_carrusel['imagenes'] = $this->App_model->imagenes_carrusel();
?>

<div class="row">
    <div class="col-lg-3 col-md-4">
        <?php $this->load->view('busquedas/widget_categorias_v'); ?>
    </div>

    <div class="col-lg-9">
        <?php if ( strlen($busqueda_str) == 0 ){ ?>
            <div class="row">
                <div class="col-md-8">
                    <?php if ( $data_carrusel['imagenes']->num_rows() > 0 ){ ?>
                        <?php $this->load->view('widgets/carrusel_v', $data_carrusel); ?>
                    <?php } ?>

                </div>
                <div class="col-md-4 hidden-xs">
                    <?php $this->load->view('widgets/posters_laterales_v'); ?>
                </div>
            </div>
        <?php } ?>
        <div class="row">
            <div class="category-products">
                <div class="toolbar">
                    <div class="pager">
                        <div class="pages">
                            <?= $this->pagination->create_links(); ?>
                        </div>
                    </div>
                </div>
                <?php $this->load->view($vista_b); ?>
                <div class="toolbar">
                    <div class="pager">
                        <div class="pages">
                            <?= $this->pagination->create_links(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>