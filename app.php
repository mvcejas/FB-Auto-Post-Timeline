<?php
require 'fbsdk/facebook.php';

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
		'appID'  => $AppID,
		'secret' => $secret,
	)
);

var_dump($fb->getUser());

if($fb->getUser()){

}

