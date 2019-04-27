<?php
    $img_id_1 = $this->Pcrn->campo_id('sis_opcion', 101, 'valor');
    $row_img_1 = $this->App_model->row_img($img_id_1, '');
    
    $img_id_2 = $this->Pcrn->campo_id('sis_opcion', 102, 'valor');
    $row_img_2 = $this->App_model->row_img($img_id_2, '');
?>

<div class="">
    <div class="div1">
        <a href="<?= $this->Pcrn->preparar_url($row_img_1->link) ?>" target="_blank">
            <img alt="<?= $row_img_1->titulo_archivo ?>" src="<?= $row_img_1->src ?>">
        </a>
    </div>
    
    <div class="div1">
        <a href="<?= $this->Pcrn->preparar_url($row_img_2->link) ?>" target="_blank">
            <img alt="<?= $row_img_2->titulo_archivo ?>" src="<?= $row_img_2->src ?>">
        </a>
    </div>
    
</div>