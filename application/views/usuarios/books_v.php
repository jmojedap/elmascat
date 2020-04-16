<?php if ( $books->num_rows() == 0 ) { ?>
    <div class="alert alert-info">
        <i class="fa fa-info-circle"></i>
        No tienes libros en tu biblioteca
    </div>
<?php } else { ?>
    <table class="table bg-blanco">
        <thead>
            <th></th>
            <th>Descripción</th>
        </thead>
        <tbody>
            <?php foreach ( $books->result() as $book ) { ?>
                <tr>
                    <td></td>
                    <td>
                        <a href="<?php echo base_url("books/read/{$book->code}/{$book->meta_id}") ?>"><?= $book->title ?></a>
                        <br>
                        <p><?= $book->resumen ?></p>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php } ?>