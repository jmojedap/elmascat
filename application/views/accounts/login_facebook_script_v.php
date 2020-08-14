<?php $app_id = K_FBAI; ?>

<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v8.0&appId=<?= $app_id ?>&autoLogAppEvents=1" nonce="A8ebxgZ5"></script>

<script>
// Loading Facebook Tools
//-----------------------------------------------------------------------------
    window.fbAsyncInit = function()
    {
      FB.init({
        appId: '<?= K_FBAI ?>',
        cookie: true,
        xfbml: true,
        version: 'v8.0' 
      });
        
      FB.AppEvents.logPageView();
    };

    (function(d, s, id){
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) {return;}
      js = d.createElement(s); js.id = id;
      js.src = "https://connect.facebook.net/en_US/sdk.js";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

// Verificación y envío de datos
//-----------------------------------------------------------------------------
    
    //Verificar estado de Facebook Login
    function checkLoginState()
    {
      FB.getLoginStatus(function(response) {
        if (response.status === 'connected') {
            validateLogin(response.authResponse.accessToken);   //Validar Token y Crear Sesión en WebApp
        } else {
            //Respuesta si no está conectado
            document.getElementById('status').innerHTML = 'Please log into this webpage.';
        }
      });
    }

    //Enviar user access token recibido, y datos de usuario
    function validateLogin(access_token)
    {
      FB.api('/me', {fields: 'email, last_name, first_name'}, function(fbUserData) {
        var formData = new FormData();
        formData.append("input_token", access_token);
        formData.append("email", fbUserData.email);
        formData.append("first_name", fbUserData.first_name);
        formData.append("last_name", fbUserData.last_name);

        axios.post(url_api + 'accounts/validate_facebook_login/', formData)
        .then(response => {
          console.log(response.data);
          if ( response.data.status == 1 )
          {
            window.location = url_app + 'app/logged';
          }
        })
        .catch(function (error) {
          console.log(error);
        });
      });
    }
</script>