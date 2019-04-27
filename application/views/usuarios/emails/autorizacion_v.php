<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Pedido <?= $row_usuario->nombre ?></title>
    </head>
    <body>
        <div style="<?= $style['body'] ?>">
            <h1 style="<?= $style['h1'] ?>"><?= "{$row_usuario->nombre} {$row_usuario->apellidos}" ?></h1>
            <h4 style="<?= $style['suave'] ?>"><?= $row_usuario->email ?></h4>
            <p>
                El usuario ha solicitado registrarse en districatolicas.com como <span style="<?= $style['resaltar'] ?>">Distribuidor</span>
            </p>
            
            <a target="_blank" href="<?= base_url("usuarios/solicitudes_rol/") ?>" style="<?= $style['btn'] ?>">
                Aprobar
            </a>
        </div>
    </body>
    
</html>

