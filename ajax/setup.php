<?php
	
$base = realpath(dirname(__FILE__) . '/..');

require $base.'/config.php';

if(isset($_POST) && count($_POST)>0){
	$FBID   = $_POST['UserID'];
	$PageID = $_POST['PageID'];

	$stmt = $sql->prepare("UPDATE users SET PageID=? WHERE FBID=?");
	$stmt->bind_param("ss",$PageID,$FBID);
	if($stmt->execute()){
		echo "Data saved.";
	}
	else{
		echo "An error has occured.";
	}
}