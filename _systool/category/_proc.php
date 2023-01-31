<?php
	header("Content-type: text/html; charset=utf-8");
	include ('../../_inc/define.php');
	include (HOME_ROOT . '/_inc/lib.func.php');
	$db = class_load('db');

	$proc				= trimPostRequest('proc');			// 처리구분
	$mcIndex			= trimPostRequest('mc_index');		// 일련번호
	$mcCode				= trimPostRequest('mc_code');		// 코드
	$mcName				= trimPostRequest('mc_name');		// 솔루션명
	$mcDeleteFl			= trimPostRequest('mc_deleteFl');	// 삭제여부

	/* ------------------------------------------------
	*	- 도움말 - 등록 데이터 생성
	*  ------------------------------------------------
	*/
	$mcIp				= $_SERVER['REMOTE_ADDR'];			//------------ ip
	$mcDate				= date('Y-m-d H:i:s');				// 등록, 수정, 삭제일


	
	$falseUrl = '';
	$trueUrl = '../../_systool/category/_list.php?' . $getStr;
	
	$dataString = class_load('string');
	
	$dataString->addItem('mc_name', $mcName, 'C');
	$dataString->addItem('mc_deleteFl', $mcDeleteFl, 'C');
	$dataString->addItem('mc_ip', $mcIp, 'C');


	if ($proc == 'WRITE') {
		$dataString->addItem('mc_code', $mcCode, 'C');
		$dataString->addItem('mc_writedate', $mcDate, 'C');

		list($insertColumn, $insertValue) = $dataString->getInsert();
		$processQuery = "Insert Into " . GD_MANUAL_CATEGORY . " (" . $insertColumn . ") Values (" . $insertValue . ")";

		session_start();
		$_SESSION['sess']['maCategoryCode'] .= $mcCode . '|'; // 현재 등록한 사용자 세션에 등록되어 있는 카테고리 권한 수정

		$memberAuthUpdateQuery = "Update " . GD_MANUAL_AUTH . " Set ma_category_code = Concat(ma_category_code, '" . $mcCode . '|' . "') Where ma_code in ('" . $_SESSION['sess']['maCode'] . "', 'A')";

		$db->query($memberAuthUpdateQuery); // 현재 DB에 등록되어 있는 사용자 권한 카테고리 권한 수정
	}
	else if ($proc == 'MODIFY') {
		$dataString->addItem('mc_editdate', $mcDate, 'C');

		$updateValue = $dataString->getUpdate();
		$processQuery = "Update " . GD_MANUAL_CATEGORY . " Set " . $updateValue . " Where mc_index = '" . $mcIndex . "'";
	}
	else {
		$processQuery = "Update " . GD_MANUAL_CATEGORY . " Set mc_deleteFl = 'y' , mc_editdate = '" . $mcDate . "' Where mc_index = '" . $mcIndex . "'";

		$codeUpdateQuery = "Update " . GD_MANUAL_AUTH . " Set ma_category_code = REPLACE(ma_category_code, '|" . $mcCode . "|', '|') Where ma_category_code like concat('%|" . $mcCode . "|%')";
		$db->query($codeUpdateQuery);

		$codeUpdateQuery = "Update " . GD_MANUAL . " Set m_category_code = REPLACE(m_category_code, '|" . $mcCode . "|', '|') Where m_category_code like concat('%|" . $mcCode . "|%')";
		$db->query($codeUpdateQuery);
	}

	//echo $processQuery;
	$db->query($processQuery) or die(setXMLValueURL('false', '저장에 실패하였습니다.', $falseUrl));
	//$db->query($processQuery) or die(mysql_error().$processQuery); //쿼리 에러시 확인
	
	setXMLValueURL('true', '정상적으로 저장 하였습니다.', $trueUrl);
?>

