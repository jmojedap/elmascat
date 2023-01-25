<?php
    $texts['title'] = 'Bienvenido a ' . APP_NAME;
    $texts['paragraph'] = 'Para activar tu cuenta haz clic en el siguiente enlace:';
    $texts['button'] = 'ACTIVAR';
    $texts['link'] = "accounts/activation/{$user->cod_activacion}";
    
    if ( $activation_type == 'recovery' ) 
    {
        $texts['title'] = APP_NAME;
        $texts['paragraph'] = 'Para establecer una nueva contraseña haz clic en el siguiente enlace:';
        $texts['button'] = 'NUEVA CONTRASEÑA';
        $texts['link'] = "accounts/activation/{$user->cod_activacion}";
    }
?>
<h1><?= $user->display_name ?></h1>
<h2 style="<?= $styles['h2'] ?>"><?= $texts['title'] ?></h2>
<p><?= $texts['paragraph'] ?></p>
<a style="<?= $styles['btn'] ?>" href="<?= URL_APP . $texts['link'] ?>" target="_blank">
    <?= $texts['button'] ?>
</a>