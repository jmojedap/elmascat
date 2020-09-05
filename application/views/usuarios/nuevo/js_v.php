<script>
// Variables
//-----------------------------------------------------------------------------
    
    var base_url = '<?= base_url() ?>';
    var controlador = 'usuarios';
    var username = '';
    var random = '<?= random_string('alpha', 4) ?>';
    //Validación
    var vld = {
        email : 1,  //1 por defecto, puede ser vacío
        username : 0,
        no_documento : 0
    };
    
// Document Ready
//-----------------------------------------------------------------------------
    
    $(document).ready(function ()
    {
        //autollenado();
        
        $('#formulario').submit(function () {
            
            console.log(vld);
            if ( vld.email + vld.username + vld.no_documento == 3 )
            {
                enviar_formulario();
            } else {
                
                toastr['error']('Revise los campos en rojo');
            }
            
            return false;
        });
        
        $('#campo-username').change(function(){
            var username_pre = $(this).val();
            unico_username(username_pre);
        });
        
        $('#campo-email').change(function(){
            var email_pre = $(this).val();
            unico_email(email_pre);
        });
        
        $('#campo-no_documento').change(function(){
            var no_documento_pre = $(this).val();
            unico_no_documento(no_documento_pre);
        });
        
        /* Al actualizar uno de los argumentos con los que se construye el 
         * username: nombre y apellidos
         * */
        $('#btn_generar_username').click(function(){
            generar_username();
            unico_username($('#campo-username').val());
        });
        
        $('#campo-password').focus(function(){
        
            if ( $(this).val().length < 1 ){
                var password = $('#campo-nombre').val().substring(0,1);
                password += $('#campo-apellidos').val().substring(0,1);
                password += $('#campo-no_documento').val();
                password = password.toLowerCase();
                $(this).val(password);
            }
            
        });
    });
    
// Funciones
//-----------------------------------------------------------------------------
    
    function enviar_formulario()
    {
        //toastr['success']('Usuario creado');
        $.ajax({
            type: 'POST',
            url: base_url + controlador + '/insertar',
            data: $('#formulario').serialize(),
            success: function (resultado) {
                if ( resultado.ejecutado ) {
                    window.location = base_url + controlador + '/info/' + resultado.nuevo_id;
                }
            }
        });
    }
    
    //Ajax, verificar que el username no exista ya en la BD
    function unico_username(username_pre)
    {
        $.ajax({
            type: "POST",
            url: base_url + 'app/es_unico/',
            data: {
                tabla: 'usuario',
                campo: 'username',
                valor: username_pre
            },
            success: function (resultado) {
                if ( resultado.ejecutado )
                {
                    vld.username = 1;
                    $('#form-group_username').removeClass('has-error');
                    $('#form-group_username').addClass('has-success');
                } else {
                    vld.username = 0;
                    toastr["error"]('El username escrito no está disponible');
                    $('#form-group_username').addClass('has-error');
                }
            }
        });
    }
    
    //Ajax, verificar que el email no exista ya en la BD
    function unico_email(email_pre)
    {
        $.ajax({
            type: "POST",
            url: base_url + 'app/es_unico/',
            data: {
                tabla: 'usuario',
                campo: 'email',
                valor: email_pre
            },
            success: function (resultado) {
                if ( resultado.ejecutado )
                {
                    vld.email = 1;
                    $('#form-group_email').removeClass('has-error');
                    $('#form-group_email').addClass('has-success');
                } else {
                    vld.email = 0;
                    toastr["error"]('El correo electrónico escrito ya está registrado por otro usuario');
                    $('#form-group_email').addClass('has-error');
                }
            }
        });
    }
    
    //Ajax, verificar que el no_documento no exista ya en la BD
    function unico_no_documento(no_documento_pre)
    {
        $.ajax({
            type: "POST",
            url: base_url + 'app/es_unico/',
            data: {
                tabla: 'usuario',
                campo: 'no_documento',
                valor: no_documento_pre
            },
            success: function (resultado) {
                if ( resultado.ejecutado )
                {
                    vld.no_documento = 1;
                    $('#form-group_no_documento').removeClass('has-error');
                    $('#form-group_no_documento').addClass('has-success');
                } else {
                    vld.no_documento = 0;
                    toastr["error"]('El número de documento escrito ya está registrado por otro usuario');
                    $('#form-group_no_documento').addClass('has-error');
                }
            }
        });
    }
    
    //Ajax
    function generar_username()
    {
        $.ajax({        
            type: 'POST',
            url: base_url + 'usuarios/username',
            data: {
                nombre: $('#campo-nombre').val(),
                apellidos: $('#campo-apellidos').val()
            },
            success: function (respuesta) {
                username = respuesta;
                $('#campo-username').val(username);
            }
        });
    }
    
    function autollenado()
    {
        $('#campo-nombre').val('Juan ' + random);
        $('#campo-apellidos').val('Pérez');
        generar_username();
        vld.username = 1;
        $('#campo-email').val(random + '@gmail.com');
        unico_email();
        $('#campo-no_documento').val('<?= date('Ymdhis') ?>');
        unico_no_documento();
        $('#campo-fecha_nacimiento').val('1975-06-14');
    }
</script>