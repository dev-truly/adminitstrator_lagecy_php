<?
	include("../../_inc/lib.func.php");


	$session = class_load('session');
	$session->logout();

	go('../_login/_login.php');

?>