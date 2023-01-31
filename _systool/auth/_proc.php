<?php
	include ('../../_admin/_include/sysproctop.php');

	/* ------------------------------------------------
	*	- 도움말 - 넘어오는 값
	*  ------------------------------------------------
	*/
	$proc					= trimPostRequest('proc');					//------------ 처리구분

	$aIndex				= trimPostRequest('xUidx');				//------------ 일련번호
	$aCode				= trimPostRequest('a_code');				//------------ 코드
	$aName				= trimPostRequest('a_name');				//------------ 권한 이름
	if (!empty($_POST['d_code'])) {
		$dCode			= '|' . implode('|', $_POST['d_code']) . '|';			//------------ 부서 권한 코드
	}
	if (!empty($_POST['m_code'])) {
		$mCode				= '|' . implode('|', $_POST['m_code']) . '|';			//------------ 메뉴 권한 코드
	}

	if (!empty($_POST['a_auth_code'])) {
		$aAuthCode				= '|' . implode('|', $_POST['a_auth_code']) . '|';			//------------ 메뉴 권한 코드
	}
	
	$listCnt			= trimPostRequest('listCnt');		//------------ 출력되는 리스트 수
	$page				= trimPostRequest('page');			//------------ 현재 페이지

	$selectType			= trimPostRequest('selectType');	//------------ 검색 조건
	$selectValue		= trimPostRequest('selectValue');	//------------ 검색값
	$sort				= trimPostRequest('sort');			//------------ 검색값

	/* ------------------------------------------------
	*	- 도움말 - 등록 데이터 생성
	*  ------------------------------------------------
	*/
	$aIp				= $_SERVER['REMOTE_ADDR'];			//------------ ip
	$aDate				= date('Y-m-d H:i:s');				// 등록, 수정, 삭제일
	
	/* ------------------------------------------------
	*	- 도움말 - get데이터로 넘길값 생성
	*  ------------------------------------------------
	*/
	$getStr ='';
	$getStr = getStr($getStr,'listCnt',$listCnt);
	$getStr = getStr($getStr,'page',$page);
	$getStr = getStr($getStr,'selectType',$selectType);
	$getStr = getStr($getStr,'selectValue',$selectValue);
	$getStr = getStr($getStr,'sort',$sort);
	$getStr = getArrayStr($getStr, 'm_codesearch[]', trimPostRequest('m_codesearch'));
	$getStr = getArrayStr($getStr, 'd_codesearch[]', trimPostRequest('d_codesearch'));
	$getStr = getArrayStr($getStr, 'a_codesearch[]', trimPostRequest('a_codesearch'));

	/* ------------------------------------------------
	*	- 도움말 - 초기화
	*  ------------------------------------------------
	*/
	$falseUrl = '';
	$trueUrl = '../../_systool/auth/_list.php?' . $getStr;
	
	/* ------------------------------------------------
	*	- 도움말 - 데이터 변환 진행
	*  ------------------------------------------------
	*/
	$dataString = class_load('string');
	
	$dataString->addItem('d_code', $dCode, 'C');
	$dataString->addItem('a_menu_code', $mCode, 'C');
	$dataString->addItem('a_auth_code', $aAuthCode, 'C');
	$dataString->addItem('a_name', $aName, 'C');
	$dataString->addItem('a_ip', $aIp, 'C');


	/* ------------------------------------------------
	*	- 도움말 - 데이터 변환 후 등록,수정,삭제
	*  ------------------------------------------------
	*/
	if ($proc == 'WRITE') {			//등록
		$dataString->addItem('a_code', $aCode, 'C');
		$dataString->addItem('a_write_date', $aDate, 'C');

		list($insertColumn, $insertValue) = $dataString->getInsert();
		$processQuery = "Insert Into " . GD_AUTH . " (" . $insertColumn . ") Values (" . $insertValue . ")";
	}
	else if ($proc == 'MODIFY') {	// 수정
		$dataString->addItem('a_edit_date', $aDate, 'C');

		$updateValue = $dataString->getUpdate();
		$processQuery = "Update " . GD_AUTH . " Set " . $updateValue . " Where a_index = '" . $aIndex . "'";
	}
	else {							// 삭제
		$processQuery = "Update " . GD_AUTH . " Set a_deleteFl = 'y', a_edit_date = '" . $maDate . "' Where a_index = '" . $maIndex . "'";
	}
	
	/* ------------------------------------------------
	*	- 도움말 - 처리 결과
	*  ------------------------------------------------
	*/
	//echo $processQuery;
	$db->query($processQuery) or die(setXMLValueURL('false', '저장에 실패하였습니다.', $falseUrl));
	//$db->query($processQuery) or die(mysql_error().$processQuery); //쿼리 에러시 확인
	
	setXMLValueURL('true', '정상적으로 저장 하였습니다.', $trueUrl);
?>

