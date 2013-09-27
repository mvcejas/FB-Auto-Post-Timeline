<?php
require 'fbsdk/facebook.php';

$baseURL = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

if($_SERVER['REMOTE_ADDR']!=='127.0.0.1'){
	$AppID  = '350185631783017';
	$secret = '2596568af62db0ffd67a17fc7ee833e9';
}
else{
	$AppID  = '524988907583434';
	$secret = '18383be139c20afb27d6b5cd08c87b32';
}

$fb = new Facebook(
	array(
		'appId'  => $AppID,
		'secret' => $secret,
	)
);

$isAuth = $fb->getUser(); // check if user is authenticated

if(isset($_GET['PageID']) && isset($_GET['ReviewID'])){
	$PageID    = $_GET['PageID'];
	$ReviewID  = $_GET['ReviewID'];
	$ShareData = array(
		"message" => "Test review link.",
		"link"    => "http://wegla.net/test/UserReview.php",
  );

  $fb->api('/'.$PageID.'/links','post',$ShareData);
}
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Wegla App</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" media="screen">
  </head>
  <body style="padding-top:70px;">
    <div class="container">
    <?php if(!$isAuth):?>
      <div class="row" id="LoginDialog">
        <div class="col-lg-2 col-lg-offset-5">
          <a class="btn btn-primary btn-block" href="<?php echo $fb->getLoginUrl();?>">Login with Facebook</button>
        </div>
      </div>
    <?php endif;//user not authenticated?>

    <?php if($isAuth):?>
	    <div class="row" id="AppConfigDialog">
        <div class="col-lg-6 col-lg-offset-3">
          <div class="panel panel-default">
            <div class="panel-heading">
            	<a class="pull-right" href="<?php echo $fb->getLogoutUrl();?>">Logout</a>
              <h3 class="panel-title">App Config</h3>
            </div>
            <hr>
            <div class="col-lg-12">
	            <input type="text" name="ReviewID" placeholder="Review ID for the link to be posted on FB" class="form-control">
            </div>
            <div class="panel-body">
            	<div class="panel panel-info">
                <div class="panel-heading">
                  <h3 class="panel-title">SELECT PAGE TO PUBLISH POST</h3>
                </div>
                <div class="list-group" id="ManagePages">
                <?php // beginning this line, this will captures pages associated on your account
                $acctpages = $fb->api('/me/accounts');
               	foreach($acctpages['data'] as $page)://ps...?>
                  <a class="list-group-item" href="<?php echo $baseURL.'?PageID='.$page['id'].'&amp;ReviewID='.time()//test only;?>" data-pageid="<?php echo $page['id'];?>" data-accesstoken="<?php echo $page['access_token'];?>" data-toggle="listItem">
                    <b class="glyphicon glyphicon-link pull-right" onclick="window.open('http://fb.com/<?php echo $page['id'];?>','_blank');"></b>
                    <h3 class="list-group-item-heading"><?php echo $page['name'];?></h3>
                    <p class="list-group-item-text">
                      <span id="PageCat" class="pull-right"><?php echo $page['category'];?></span>
                      <span id="PageUID"><?php echo $page['id'];?></span>
                    </p>
                  </a>
                <?php endforeach;//account pages?>

                <?php // beginning this line, this will capture your personal information.
                	$acct = $fb->api('/me');?>
                	<a class="list-group-item" href="<?php echo $baseURL.'?PageID='.$page['id'].'&amp;ReviewID='.time()//test only;?>" data-pageid="<?php echo $acct['id'];?>" data-accesstoken="<?php echo $fb->getAccessToken();?>" data-toggle="listItem">
                    <b class="glyphicon glyphicon-link pull-right" onclick="window.open('http://fb.com/<?php echo $acct['id'];?>','_blank');"></b>
                    <h3 class="list-group-item-heading"><?php echo $acct['name'];?></h3>
                    <p class="list-group-item-text">
                      <span id="PageCat" class="pull-right">Personal Account</span>
                      <span id="PageUID"><?php echo $acct['id'];?></span>
                    </p>
                  </a>
                </div>
              </div>

            </div><!--/.panel-body-->
          </div><!--/.panel-->
        </div><!--/col-lg-*-->
      </div><!--/.row-->
    <?php endif;//user is authenticated?>
   	</div>
  </body>
</html>