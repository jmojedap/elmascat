<table class="table table-striped">
    <tbody>
        <tr>
            <td width="25%" class="text-right">Marca/Editorial</td>
            <td>
                <?= $this->Item_model->nombre_id($row->fabricante_id) ?>
            </td>
        </tr>
        
        <tr>
            <td class="text-right">Referencia</td>
            <td>
                <?= $row->referencia ?>
            </td>
        </tr>
        
        <tr>
            <td class="text-right">Dimensiones</td>
            <td>
                <?= $row->alto ?> x 
                <?= $row->ancho ?> cm
            </td>
        </tr>

        <tr>
            <td class="text-right">Peso</td>
            <td>
                <?= $row->peso ?> gramos
            </td>
        </tr>

        <?php foreach ($metadatos->result() as $row_metadato) : ?>
            <tr>
                <td class="text-right"><?= $row_metadato->nombre_metadato ?></td>
                <td>
                    <?= $row_metadato->valor ?>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>