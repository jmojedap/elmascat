<?php $carpeta_icheck = URL_ASSETS . 'icheck/'; ?>

<link href="<?= $carpeta_icheck ?>skins/square/blue.css" rel="stylesheet">
<script src="<?= $carpeta_icheck ?>icheck.js"></script>

<script>
$(document).ready(function(){
  $('input').iCheck({
    checkboxClass: 'icheckbox_square-blue',
    radioClass: 'iradio_square-blue',
    increaseArea: '20%' // optional
  });
});
</script>
