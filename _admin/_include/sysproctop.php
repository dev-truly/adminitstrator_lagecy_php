<?php

	session_start();
	if(!$_SESSION['sess']['ruIndex']){ // 로그인 체크
		header("Location: ../../_admin/_login/_login.php");
	}

	include ('../../_inc/define.php');
	include (HOME_ROOT . '/_inc/lib.func.php');
	$db = class_load('db');
/*
	// 권한 추출
	$authQuery = "Select * From 
						" . GD_MANUAL_AUTH . " 
					Where 
						ma_code = '" . $_SESSION['sess']['maCode'] . "'
				";
	$authResult = $db->query($authQuery);
	$authRow = $db->fetch($authResult);
	

	if ($authRow['ma_member_auth'] == 'n' && ereg('/member/', $isUrl)) {
		header("Location: ../../_admin/_login/_login.php");
	}
	if ($authRow['ma_authority_auth'] == 'n' && ereg('/auth/', $isUrl)) {
		header("Location: ../../_admin/_login/_login.php");
	}
*/
?>