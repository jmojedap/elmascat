<div class="login" style="min-height: 600px;">
    <div style="text-align: center;">
        <img src="<?= URL_IMG ?>app/logo_eshopper.png" />
        <h4 role="alert" class="alert alert-warning"><i class="fa fa-info-circle"></i> Acceso no permitido</h4>
        <?= anchor('app', '<i class="fa fa-caret-left"></i> Volver', 'class="btn btn-info"') ?>
        <?= anchor('accounts/login', '<i class="fa fa-user"></i> Login de usuario', 'class="btn btn-default"') ?>
    </div>

</div>