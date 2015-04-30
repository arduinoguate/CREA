<?php
session_start();
$_SESSION = array();
session_destroy();
if(!@session_is_registered("uid")){
	header("location:index.html");
}
?>
