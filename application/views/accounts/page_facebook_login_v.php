<!DOCTYPE html>
<html>
  <head>
    <title>Facebook Login JavaScript Example</title>
    <meta charset="UTF-8">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js"></script>
    <script>
      const url_api = '<?= URL_API ?>';
      const url_app = '<?= URL_APP ?>';
    </script>
  </head>
  <body>
    <h1 id="head_title">Login con Facebook 18:02</h1>
    <?php $this->load->view('accounts/login_facebook_script_v') ?>

    
  <!-- <fb:login-button
    scope="public_profile,email" onlogin="checkLoginState();">
  </fb:login-button> -->

  <div class="fb-login-button" data-size="large" data-button-type="login_with" data-layout="default" data-auto-logout-link="false" data-use-continue-as="false" data-width="" scope="public_profile,email" onlogin="checkLoginState();"></div>

  <div id="status">
      
  </div>

  <div id="message"></div>
  
  </body>
</html>