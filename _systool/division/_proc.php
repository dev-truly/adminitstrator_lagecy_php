<?php
	include ('../../_admin/_include/sysproctop.php');

	/* ------------------------------------------------
	*	- 도움말 - 넘어오는 값
	*  ------------------------------------------------
	*/
	$proc				= trimPostRequest('proc');			//------------ 처리구분

	$dIndex				= trimPostRequest('xUidx');		//------------ 일련번호
	$dCode				= trimPostRequest('d_code');		//------------ 코드
	$dName				= trimPostRequest('d_name');		//------------ 아이디

	
	$listCnt			= trimPostRequest('listCnt');		//------------ 출력되는 리스트 수
	$page				= trimPostRequest('page');			//------------ 현재 페이지

	$selectType			= trimPostRequest('selectType');	//------------ 검색 조건
	$selectValue		= trimPostRequest('selectValue');	//------------ 검색값

	/* ------------------------------------------------
	*	- 도움말 - 등록 데이터 생성
	*  ------------------------------------------------
	*/
	$dIp				= $_SERVER['REMOTE_ADDR'];			//------------ ip
	$dDate				= date('Y-m-d H:i:s');				// 등록, 수정, 삭제일
	
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

	/* ------------------------------------------------
	*	- 도움말 - 초기화
	*  ------------------------------------------------
	*/
	$falseUrl = '';
	$trueUrl = '../../_systool/division/_list.php?' . $getStr;
	
	/* ------------------------------------------------
	*	- 도움말 - 데이터 변환 진행
	*  ------------------------------------------------
	*/
	$dataString = class_load('string');
	
	$dataString->addItem('d_name', $dName, 'C');
	$dataString->addItem('d_ip', $dIp, 'C');

	/* ------------------------------------------------
	*	- 도움말 - 데이터 변환 후 등록,수정,삭제
	*  ------------------------------------------------
	*/
	if ($proc == 'WRITE') {			//등록
		$dataString->addItem('d_code', $dCode, 'C');
		$dataString->addItem('d_write_date', $dDate, 'C');

		list($insertColumn, $insertValue) = $dataString->getInsert();
		$processQuery = "Insert Into " . GD_DIVISION . " (" . $insertColumn . ") Values (" . $insertValue . ")";

		session_start();
		$_SESSION['sess']['maDivisionCode'] .= $mdCode . '|'; // 현재 등록한 사용자 세션에 등록되어 있는 부서 권한 수정

		$memberAuthUpdateQuery = "Update " . GD_MANUAL_AUTH . " Set ma_division_code = Concat(ma_division_code, '" . $mdCode . '|' . "') Where ma_code in ('" . $_SESSION['sess']['maCode'] . "', 'A')";

		$db->query($memberAuthUpdateQuery); // 현재 DB에 등록되어 있는 사용자 권한 부서 권한 수정
	}
	else if ($proc == 'MODIFY') {	// 수정
		$dataString->addItem('d_edit_date', $dDate, 'C');

		$updateValue = $dataString->getUpdate();
		$processQuery = "Update " . GD_DIVISION . " Set " . $updateValue . " Where d_index = '" . $dIndex . "'";
	}
	else {							// 삭제
		$dataString->addItem('d_edit_date', $dDate, 'C');
		$dataString->addItem('d_delete_flag', 'y', 'C');
		
		$updateValue = $dataString->getUpdate();
		$processQuery = "Update " . GD_DIVISION . " Set " . $updateValue . " Where d_index = '" . $dIndex . "'";

		$codeUpdateQuery = "Update " . GD_MANUAL_AUTH . " Set ma_division_code = REPLACE(ma_division_code, '|" . $mdCode . "|', '|') Where ma_division_code like concat('%|" . $mdCode . "|%')";
		$db->query($codeUpdateQuery);

		$codeUpdateQuery = "Update " . GD_MANUAL . " Set m_division_code = REPLACE(m_division_code, '|" . $mdCode . "|', '|') Where m_division_code like concat('%|" . $mdCode . "|%')";
		$db->query($codeUpdateQuery);
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

