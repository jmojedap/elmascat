<div class="row">
    <div class="col-md-4">
        <table class="table bg-white">
            <tbody>
                <tr>
                    <td>Abrir</td>
                    <td>
                        <a href="<?= base_url("posts/open/{$row->id}") ?>" class="btn btn-light">
                            Abrir
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>ID</td>
                    <td><?= $row->id ?></td>
                </tr>
                <tr>
                    <td>tipo_id</td>
                    <td><?= $row->tipo_id ?></td>
                </tr>
                <tr>
                    <td>nombre_post</td>
                    <td><?= $row->nombre_post ?></td>
                </tr>
                <tr>
                    <td>estado</td>
                    <td><?= $row->estado ?></td>
                </tr>
                <tr>
                    <td>slug</td>
                    <td><?= $row->slug ?></td>
                </tr>
                <tr>
                    <td>code</td>
                    <td><?= $row->code ?></td>
                </tr>
                <tr>
                    <td>imagen_id</td>
                    <td><?= $row->imagen_id ?></td>
                </tr>
            </tbody>
        </table>

        <table class="table bg-white">
            <tbody>
                <tr>
                    <td>published at</td>
                    <td><?= $row->publicado ?></td>
                </tr>
                <tr>
                    <td>editor_id</td>
                    <td><?= $row->editor_id ?></td>
                </tr>
                <tr>
                    <td>edited_at</td>
                    <td><?= $row->editado ?></td>
                </tr>
                <tr>
                    <td>creator_id</td>
                    <td><?= $row->usuario_id ?></td>
                </tr>
                <tr>
                    <td>created_at</td>
                    <td><?= $row->creado ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h2><?= $row->nombre_post ?></h2>
                <div>
                    <h4 class="text-muted">resumen</h4>
                    <?= $row->resumen ?>
                </div>
                <hr>
                <div>
                    <h4 class="text-muted">contenido</h4>
                    <?= $row->contenido ?>
                </div>
                <hr>
                <div>
                    <h4 class="text-muted">content json</h4>
                    <?= $row->content_json ?>
                </div>
                <hr>
                <div>
                    <h4 class="text-muted">keywords:</h4>
                    <?= $row->keywords ?>
                </div>
            </div>
        </div>
    </div>
</div>