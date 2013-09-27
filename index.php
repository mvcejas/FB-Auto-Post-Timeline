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

      <div class="row" id="LoginDialog">
        <div class="col-lg-2 col-lg-offset-5">
          <button class="btn btn-primary btn-block" onclick="javascript:Login();">Login with Facebook</button>
        </div>
      </div>

      <div class="row" id="AppConfigDialog" style="display:none;">
        <div class="col-lg-6 col-lg-offset-3">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">App Config</h3>
            </div>
            <div class="panel-body">
              <div class="panel panel-info">
                <div class="panel-heading">
                  <h3 class="panel-title">PAGES YOU MANAGE</h3>
                </div>
                <div class="list-group" id="ManagePages">
                  <a class="list-group-item hide" href="javascript:;" data-pageid="" data-accesstoken="" data-toggle="listItem">
                    <b class="glyphicon glyphicon-link pull-right" data-toggle="OpenFB"></b>
                    <h3 class="list-group-item-heading">Sample Item</h3>
                    <p class="list-group-item-text">
                      <span id="PageCat" class="pull-right">Sample Description</span>
                      <span id="PageUID">ID</span>
                    </p>
                  </a>

                  <a class="list-group-item" id="PersonalDetails" href="javascript:;" data-pageid="" data-accesstoken="" data-toggle="listItem">
                    <b class="glyphicon glyphicon-link pull-right" data-toggle="OpenFB"></b>
                    <h3 class="list-group-item-heading">Sample Item</h3>
                    <p class="list-group-item-text">
                      <span id="PageCat" class="pull-right">Sample Description</span>
                      <span id="PageUID">ID</span>
                    </p>
                  </a>
                </div>
              </div>

              <h3 id="ConfigResult" class="alert alert-success" style="display:none;"></h3>
              
              <form action="javascript:;" data-action="/ajax/setup.php" method="post" class="form-horizontal" role="form" id="AppConfig">
                <input type="hidden" name="UserID" value="">
                <div class="form-group">
                  <label for="PageID" class="col-lg-2 control-label">Page ID</label>
                  <div class="col-lg-10">
                    <input type="text" class="form-control" id="PageID" name="PageID" placeholder="Enter Page ID">
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-lg-offset-2 col-lg-10">
                    <button type="button" class="btn btn-primary" onclick="javascript:TimelinePost();">Test Post To Page</button>
                    <button type="button" class="btn btn-primary" onclick="javascript:ShowReview();">Review &amp; Rating</button>
                    <button type="button" class="btn btn-danger" onclick="javascript:Logout();">Logout</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- scripts -->
    <script src="//code.jquery.com/jquery.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
    <script src="//connect.facebook.net/en_US/all.js"></script>
    <script>
    FB.init({
      appId      : appConfig.live,
      channelUrl : appConfig.base+'/channel.html',
      status     : true,
      xfbml      : true
    });

    $(document).ready(function(){
      $('#AppConfig').submit(function(e){
        e.preventDefault();
        var action = $(this).attr('data-action'),
            data = $(this).serialize();
        $.post(appConfig.base+action,data,function(response){
          $('#ConfigResult').html(response);
        });
      });

      $(document).on('click','[data-toggle="listItem"]',function(){
        $(this).parent().find('.list-group-item').removeClass('active');
        $(this).addClass('active');
        $('#PageID').val($(this).data('pageid'));
      });

      $(document).on('click','[data-toggle="OpenFB"]',function(){
        var PageID = $(this).parent().attr('data-pageid'),
            FBBase = 'http://fb.com/';
        window.open(FBBase+PageID,'_blank');
      });

      GetLoginStatus();
    });

    function Login(){
      FB.login(function(response){
        console.log(response.status);
        if(response.status==='connected'){
          PushUserData();
          ShowAccounts();
        }
      },{scope:'email,publish_stream,manage_pages'});
    }

    function Logout(){
      FB.logout(function(response){
        if(response){
          GetLoginStatus();
        }
      });
    }

    function PushUserData(){
      FB.api('/me',function(response){
        $.post(appConfig.base+'/ajax/register.php',response)
        .complete(function(){
          $.when($('#LoginDialog').fadeOut())
           .then(function(){
            $('#AppConfigDialog').fadeIn();
          });
        });
      });
    }

    function TimelinePost(){
      var data = {
        message: "Dummy review link.",
        link: "www.wegla.net/test/facebookFeed.php?kind=review&id=302&rnd="+(Math.random()*100+1),
      }, PageID = $('#PageID').val();

      if(PageID==''){
        $('#ConfigResult').text('Page ID empty').show();
        return false;
      }

      FB.api('/'+PageID+'/links','post',data, function(response) {
        console.log(response);
        if(response.error){
          $('#ConfigResult').text('An error has occured.').show();
        }
        else{
          $('#ConfigResult').text('Data successfully posted.').show();
        }
      });
      /*
      var data = {
        message: 'Message: The quick little brown fox jumps over the lazy dog.',
        link: "http://www.wegla.net/_de/restaurants/oesterreich/tirol/innsbruck/italienisch/Le_Vante_26/index.php",
        name: 'Name: Resto anzeigen',
        caption: 'Caption: Capcion',
        description: 'Description: The quick little brown fox jumps over the lazy dog.',
        picture: 'http://placekitten.com/380',
        actions: [{
          name: 'Restaurant anzeigen',
          link: "http://www.wegla.net/_de/restaurants/oesterreich/tirol/innsbruck/italienisch/Le_Vante_26/index.php",
        }]
      }, PageID = $('#PageID').val();

      FB.api('/'+PageID+'/feed','post',data,function(response){
        console.log(response);
      });*/
    }


    function ShowReview(){
      var PageID = $('#PageID').val();

      FB.api('/'+PageID+'/reviews',function(response){
        console.log(response);
      });
    }

    function GetLoginStatus(){
      FB.getLoginStatus(function(response){
        if(response.status==='connected'){
          FillDetails();
          ShowAccounts();
          $('#AppConfigDialog').show();
          $('#LoginDialog').hide();
          $('#PageID').val(response.authResponse.userID);
        }
        if(response.status==='unknown'){
          $('#AppConfigDialog').hide();
          $('#LoginDialog').show();
        }
      });
    }

    function FillDetails(){
      var i = $('#PersonalDetails');
      FB.api('/me',function(response){
        i.find('h3').text(response.first_name+' '+response.last_name);
        i.attr('data-pageid',response.id);
        i.find('#PageUID').text(response.id);
        i.find('#PageCat').text('Personal Account');
      });
    }

    function ShowAccounts(){
      FB.api('/me/accounts',function(response){
        if(response.data.length==0){
          $('#ConfigResult').text("You don't have any pages to manage").show();
        }
        if( typeof(response.data) ){
          var GroupPane = $('#ManagePages'),
              ListItem = $('#ManagePages > a:first');
          $('.app-page-info').remove();
          $.each(response.data,function(a,b){
            var NewItem = ListItem.clone();
            NewItem.attr('data-pageid',b.id);
            NewItem.attr('data-accesstoken',b.access_token);
            NewItem.find('h3').text(b.name);
            NewItem.find('#PageCat').text(b.category);
            NewItem.find('#PageUID').text(b.id);
            GroupPane.append(NewItem.addClass('show app-page-info'));
          });
        }
      });
    }
    </script>
  </body>
</html>