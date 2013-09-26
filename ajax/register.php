<?php
	
$base = realpath(dirname(__FILE__) . '/..');

require $base.'/config.php';

if(isset($_POST) && count($_POST)>0){
	$FBID      = $_POST['id'];
	$Email     = $_POST['email'];
	$Firstname = $_POST['first_name'];
	$Lastname  = $_POST['last_name'];
	$Password  = md5(time());

	$stmt = $sql->prepare("INSERT INTO users(FBID,Email,Firstname,Lastname,Password) VALUES(?,?,?,?,MD5(?))");
	$stmt->bind_param("sssss",$FBID,$Email,$Firstname,$Lastname,$Password);
	if($stmt->execute()){
		echo "Data saved.";
	}
	else{
		echo "An error has occured.";
	}
}