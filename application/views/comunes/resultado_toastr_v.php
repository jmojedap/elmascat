<?php if ( $this->session->flashdata('resultado') != NULL ): ?>
    <?php
        $resultado = $this->session->flashdata('resultado');
        $type = 'success';
        if ( ! is_null($resultado['type'] ) ) { $type = $resultado['type']; }
    ?>
    <script>
        toastr['<?= $type ?>']('<?= $resultado['mensaje'] ?>');
    </script>
<?php endif ?>