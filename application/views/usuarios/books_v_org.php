<style>
    .cover_book{
        border: 1px solid #DDD;
        max-width: 80px;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;
        -webkit-box-shadow: 5px 5px 5px 0px rgba(227,227,227,1);
        -moz-box-shadow: 5px 5px 5px 0px rgba(227,227,227,1);
        box-shadow: 5px 5px 5px 0px rgba(227,227,227,1);
    }

    .cover_book:hover{
        border: 1px solid #AAA;
        max-width: 81px;
    }
</style>

<?php if ( $books->num_rows() == 0 ) { ?>
    <div class="alert alert-info">
        <i class="fa fa-info-circle"></i>
        No tienes libros en tu biblioteca
    </div>
<?php } else { ?>
    <table class="table bg-blanco">
        <thead>
            <th width="100px"></th>
            <th>Descripción</th>
        </thead>
        <tbody>
            <?php foreach ( $books->result() as $book ) { ?>
                <?php
                    $att_img = $this->Archivo_model->att_img($book->imagen_id, '500px_');
                ?>
                
                <tr>
                    <td>
                        <?php if ( $book->estado == 1 ) { ?>
                            <a href="<?php echo base_url("books/read/{$book->code}/{$book->meta_id}/{$book->slug}") ?>">
                                <img src="<?php echo $att_img['src'] ?>" alt="<?php echo $att_img['alt'] ?>" class="cover_book" onerror="this.src='<?php echo URL_IMG ?>app/125px_producto.png'">
                            </a>
                        <?php } else { ?>
                            <img src="<?php echo $att_img['src'] ?>" alt="<?php echo $att_img['alt'] ?>" class="cover_book" onerror="this.src='<?php echo URL_IMG ?>app/125px_producto.png'">
                        <?php } ?>
                    </td>
                    <td>
                        <?php if ( $book->estado == 1 ) { ?>
                            <a href="<?php echo base_url("books/read/{$book->code}/{$book->meta_id}/{$book->slug}") ?>"><?= $book->title ?></a>
                        <?php } else { ?>
                            <b><?= $book->title ?></b>
                        <?php } ?>
                        <br>
                        <p><?= $book->resumen ?></p>
                        <?php if ( $book->estado > 1 ) { ?>
                            <p>
                                <span class="text-muted">Disponible</span> <?= $this->pml->ago($book->publicado); ?>
                                <span class="text-muted">
                                    (<?= $this->pml->date_format($book->publicado, 'M-d'); ?>)
                                </span>
                            </p>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php } ?>