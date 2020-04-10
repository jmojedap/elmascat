<?php
    $texto_estado = $this->Item_model->nombre(43, $row_meta->estado);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?= $row_usuario->nombre ?></title>
    </head>
    <body>
        <div style="<?= $style['body'] ?>">
            <h1 style="<?= $style['h1'] ?>"><?= "{$row_usuario->nombre} {$row_usuario->apellidos}" ?></h1>
            <h4 style="<?= $style['suave'] ?>"><?= $row_usuario->email ?></h4>
            <p>
                Su solicitud Districatólicas Unidas S.A.S para cambiar de perfil de su cuenta de usuario fue: <span style="<?= $style['resaltar'] ?>"><?= $texto_estado ?></span>
            </p>
            
            <a target="_blank" href="<?= base_url("accounts/login/") ?>" style="<?= $style['btn'] ?>">
                Ir a Districatolicas.com
            </a>
        </div>
    </body>
    
</html>

