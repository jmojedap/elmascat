<div style="<?= $styles['body'] ?>">
    <?php $this->load->view($view_a) ?>
    
    <footer style="<?= $styles['footer'] ?>">
        <p style="<?= $styles['text_center'] ?> margin-bottom: 1em; ">
            <a href="<?= URL_APP ?>" target="_blank" title="Ir a DistriCatolicas.com">
                <img width="120px" src="<?= base_url() ?>resources/static/app/logo.png" alt="DistriCatólicas Unidas SAS">
            </a>
        </p>
        &copy; <?= date('Y') ?> &middot; Districatólicas Unidas S.A.S.
        <br>
        Creado por Pacarina Media Lab
    </footer>
</div>