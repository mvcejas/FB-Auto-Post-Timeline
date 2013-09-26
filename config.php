<?php
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','wegla_app');

$sql = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);

if($sql->connect_errno){
	printf("DB Error: %s",$sql->connect_error);
	exit;
}

$sql->set_charset('utf8');