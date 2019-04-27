<?php $this->load->view('sistema/develop/procesos_menu_v'); ?>
<?php $this->load->view('comunes/resultado_proceso_v'); ?>

<div class="row">
    <div class="col col-md-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                Parámetros
            </div>
            <div class="panel-body">
                Contenido
            </div>
        </div>
    </div>
    <div class="col col-md-9">
        <div class="">
            <table class="table table-hover bg-blanco" cellspacing="0">
                <thead>
                    <th style="width: 100px;">Ejecutar</th>
                    <th style="width: 20%">Procesos</th>
                    <th>Descripción</th>
                </thead>
                <tbody>
                    
                    <?php foreach($procesos->result() as $row_proceso) : ?>
                        <tr>
                            <td><?= anchor($row_proceso->link_proceso, 'Ejecutar', 'class="btn btn-primary"') ?></td>
                            <td><?= $row_proceso->nombre_proceso ?></td>
                            <td><?= $row_proceso->contenido ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>