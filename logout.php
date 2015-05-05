<?php
session_start();
$_SESSION = array();
session_destroy();
if(!isset($_SESSION["uid"])){
	header("location: /?loc=ok");
}else{
	header("location: /");
}
?>
