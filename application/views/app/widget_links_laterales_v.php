<?php
    
    $links_str = $this->Db_model->field_id('sis_option', 12, 'option_value');

    $this->db->where("id IN ({$links_str})");
    $this->db->order_by('id', 'RANDOM');
    $links = $this->db->get('archivo');
?>

<?php foreach ($links->result() as $row_link) : ?>
    <?php 
        $att_img = $this->Archivo_model->att_img($row_link->id, '250px_');
        $att_img['width'] = '100%';
    ?>
<div class="div2">
    <?= anchor($row_link->link, img($att_img), 'target="_blank" class="" title=""') ?>
</div>
    
<?php endforeach ?>