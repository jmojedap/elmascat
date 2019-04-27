<?php if ( $this->session->flashdata('resultado') != NULL ): ?>
    <?php
        $resultado = $this->session->flashdata('resultado');
        $type = 'success';
        if ( ! is_null($resultado['type'] ) ) { $type = $resultado['type']; }
    ?>
    <script>
        toastr['<?php echo $type ?>']('<?php echo $resultado['mensaje'] ?>');
    </script>
<?php endif ?>