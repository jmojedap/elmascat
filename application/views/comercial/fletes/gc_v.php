<?php $this->load->view('assets/grocery_crud'); ?>

<?php if ( ! is_null($vista_menu) ){ ?>
    <?php $this->load->view($vista_menu); ?>
<?php } ?>

<?php if ( $this->uri->segment(3) == 'edit' ){ ?>
    <h3>Flete enviando desde <?= $this->App_model->nombre_lugar($origen_id) ?></h3>
<?php } else { ?>
    <h3>Flete enviando desde</h3>
    <?php foreach ($origenes as $origen_lista) : ?>
        <?php
            $clase = 'btn-default';
            if ( $origen_lista == $origen_id ) { $clase = 'btn-success'; }
        ?>
        <?= anchor("fletes/nuevo/add/{$origen_lista}", $this->App_model->nombre_lugar($origen_lista), 'class="btn ' . $clase . '" title=""') ?>
    <?php endforeach ?>
    
<?php } ?>

<?php echo $output; ?>