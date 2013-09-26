<!DOCTYPE html>
<html>
  <head>
    <title>Wegla App</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <script>
    var appConfig = {
      'base': "<?php echo '//'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);?>",
      'live': "350185631783017",
      'demo': "524988907583434"
    }
    </script>
  </head>
  <body style="padding-top:70px;">
    <div class="container">
      <div class="row">
        <div class="col-lg-2 col-lg-offset-5">
          <button class="btn btn-primary btn-block" onclick="javascript:Login();">Login with Facebook</button>
        </div>
      </div>
    </div>

    <!-- scripts -->
    <script src="//code.jquery.com/jquery.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
    <script src="//connect.facebook.net/en_US/all.js"></script>
    <script>
    $(window).load(function(){
      FB.init({
        appId      : appConfig.demo,
        channelUrl : appConfig.base+'/channel.html',
        status     : true,
        xfbml      : true
      });
    });
    
    function Login(){
      FB.login(function(response){
        console.log(response.status);
        if(response.status==='connected'){
          UserData();
          window.location = appConfig.base+'/settings.php';
        }
      },{scope:'email,publish_stream,manage_pages'});
    }

    function UserData(){
      FB.api('/me',function(response){
        console.log(response);
        $.post(appConfig.base+'/ajax/register.php',response);
      });
    }
    </script>
  </body>
</html>