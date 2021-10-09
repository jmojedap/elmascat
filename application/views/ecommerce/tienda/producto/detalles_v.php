<div class="product_details">
    <h2>Características principales</h2>
    <table class="table table-striped">
        <tbody>
            <tr>
                <td class="td-title">Marca / Editorial</td>
                <td>
                    <?= $this->Item_model->nombre_id($row->fabricante_id) ?>
                </td>
            </tr>
            
            <tr>
                <td class="td-title">Referencia</td>
                <td>
                    <?= $row->referencia ?>
                </td>
            </tr>
            
            <tr>
                <td class="td-title">Dimensiones</td>
                <td>
                    <?= $row->alto ?> x 
                    <?= $row->ancho ?> cm
                </td>
            </tr>

            <tr>
                <td class="td-title">Peso</td>
                <td>
                    <?= $row->peso ?> gramos
                </td>
            </tr>

            <?php foreach ($metadatos->result() as $row_metadato) : ?>
                <?php if ( strlen($row_metadato->valor) ) : ?>
                    <tr>
                        <td class="td-title"><?= $row_metadato->nombre_metadato ?></td>
                        <td>
                            <?= $row_metadato->valor ?>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach ?>
        </tbody>
    </table>
</div>