<div class="row">
    <div class="col-md-4">
        <table class="table bg-white">
            <tbody>
                <tr>
                    <td>ID</td>
                    <td><?php echo $row->id ?></td>
                </tr>
                <tr>
                    <td>tipo_id</td>
                    <td><?php echo $row->tipo_id ?></td>
                </tr>
                <tr>
                    <td>nombre_post</td>
                    <td><?php echo $row->nombre_post ?></td>
                </tr>
                <tr>
                    <td>estado</td>
                    <td><?php echo $row->estado ?></td>
                </tr>
                <tr>
                    <td>slug</td>
                    <td><?php echo $row->slug ?></td>
                </tr>
                <tr>
                    <td>code</td>
                    <td><?php echo $row->code ?></td>
                </tr>
                <tr>
                    <td>imagen_id</td>
                    <td><?php echo $row->imagen_id ?></td>
                </tr>
            </tbody>
        </table>

        <table class="table bg-white">
            <tbody>
                <tr>
                    <td>published at</td>
                    <td><?php echo $row->publicado ?></td>
                </tr>
                <tr>
                    <td>editor_id</td>
                    <td><?php echo $row->editor_id ?></td>
                </tr>
                <tr>
                    <td>edited_at</td>
                    <td><?php echo $row->editado ?></td>
                </tr>
                <tr>
                    <td>creator_id</td>
                    <td><?php echo $row->usuario_id ?></td>
                </tr>
                <tr>
                    <td>created_at</td>
                    <td><?php echo $row->creado ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h2><?php echo $row->nombre_post ?></h2>
                <div>
                    <h4 class="text-muted">resumen</h4>
                    <?php echo $row->resumen ?>
                </div>
                <hr>
                <div>
                    <h4 class="text-muted">contenido</h4>
                    <?php echo $row->contenido ?>
                </div>
                <hr>
                <div>
                    <h4 class="text-muted">content json</h4>
                    <?php echo $row->content_json ?>
                </div>
                <hr>
                <div>
                    <h4 class="text-muted">keywords:</h4>
                    <?php echo $row->keywords ?>
                </div>
            </div>
        </div>
    </div>
</div>