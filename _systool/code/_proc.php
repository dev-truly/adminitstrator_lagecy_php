<?php
	include ('../../_admin/_include/sysproctop.php');

	/* ------------------------------------------------
	*	- 도움말 - 넘어오는 값
	*  ------------------------------------------------
	*/
	$proc				= trimPostRequest('proc');			//------------ 처리구분

	$cIndex				= trimPostRequest('xUidx');			//------------ 일련번호
	$ctCode				= trimPostRequest('ct_code');		//------------ 코드 타입 코드
	$cCode				= trimPostRequest('c_code');		//------------ 코드
	$cName				= trimPostRequest('c_name');		//------------ 코드명
	$cSort				= trimPostRequest('c_sort');		//------------ 정렬순서
	
	$listCnt			= trimPostRequest('listCnt');		//------------ 출력되는 리스트 수
	$page				= trimPostRequest('page');			//------------ 현재 페이지

	$selectType			= trimPostRequest('selectType');	//------------ 검색 조건
	$selectValue		= trimPostRequest('selectValue');	//------------ 검색값
	$sort				= trimPostRequest('sort');			//------------ 검색값
	if (!empty($_POST['ct_codesearch']))	$ctCodeSearch	= trimPostRequest('ct_codesearch');

	/* ------------------------------------------------
	*	- 도움말 - 등록 데이터 생성
	*  ------------------------------------------------
	*/
	$cIp				= $_SERVER['REMOTE_ADDR'];			//------------ ip
	$cDate				= date('Y-m-d H:i:s');				// 등록, 수정, 삭제일
	
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

	// 코드 타입 검색 데이터 존재시 Get 데이터로 넘길값 생성
	$getStr = getArrayStr($getStr, 'ct_codesearch[]', $ctCodeSearch);

	/* ------------------------------------------------
	*	- 도움말 - 초기화
	*  ------------------------------------------------
	*/
	$falseUrl = '';
	$trueUrl = '../../_systool/code/_list.php?' . $getStr;
	
	/* ------------------------------------------------
	*	- 도움말 - 데이터 변환 진행
	*  ------------------------------------------------
	*/
	$dataString = class_load('string');
	
	if ($proc != 'DELETE') {
		
		$dataString->addItem('c_name', $cName, 'C');
		$dataString->addItem('c_sort', $cSort, 'C');
		$dataString->addItem('ct_code', $ctCode, 'C');
	}
	
	$dataString->addItem('c_ip', $cIp, 'C');

	/* ------------------------------------------------
	*	- 도움말 - 데이터 변환 후 등록,수정,삭제
	*  ------------------------------------------------
	*/
	if ($proc == 'WRITE') {			//등록
		$dataString->addItem('c_code', $cCode, 'C');
		$dataString->addItem('c_write_date', $cDate, 'C');

		list($insertColumn, $insertValue) = $dataString->getInsert();
		$processQuery = "Insert Into " . GD_CODE . " (" . $insertColumn . ") Values (" . $insertValue . ")";
	}
	else if ($proc == 'MODIFY') {	// 수정
		$dataString->addItem('c_edit_date', $cDate, 'C');

		$updateValue = $dataString->getUpdate();
		$processQuery = "Update " . GD_CODE . " Set " . $updateValue . " Where c_index = '" . $cIndex . "'";
	}
	else {							// 삭제
		$dataString->addItem('c_edit_date', $cDate, 'C');

		$dataString->addItem('c_delete_flag', 'y', 'C');

		$updateValue = $dataString->getUpdate();
		$processQuery = "Update " . GD_CODE . " Set " . $updateValue . " Where c_index = '" . $cIndex . "'";
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

