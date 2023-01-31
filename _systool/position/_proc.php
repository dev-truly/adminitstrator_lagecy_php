<?php
	include ('../../_admin/_include/sysproctop.php');

	/* ------------------------------------------------
	*	- 도움말 - 넘어오는 값
	*  ------------------------------------------------
	*/
	$proc				= trimPostRequest('proc');			//------------ 처리구분

	$pIndex				= trimPostRequest('xUidx');			//------------ 일련번호
	$pCode				= trimPostRequest('p_code');		//------------ 직급 코드
	$pName				= trimPostRequest('p_name');		//------------ 직급명
	$pSort				= trimPostRequest('p_sort');		//------------ 정렬순서
	
	$listCnt			= trimPostRequest('listCnt');		//------------ 출력되는 리스트 수
	$page				= trimPostRequest('page');			//------------ 현재 페이지

	$selectType			= trimPostRequest('selectType');	//------------ 검색 조건
	$selectValue		= trimPostRequest('selectValue');	//------------ 검색값
	$sort				= trimPostRequest('sort');			//------------ 검색값

	/* ------------------------------------------------
	*	- 도움말 - 등록 데이터 생성
	*  ------------------------------------------------
	*/
	$pIp				= $_SERVER['REMOTE_ADDR'];			//------------ ip
	$pDate				= date('Y-m-d H:i:s');				// 등록, 수정, 삭제일
	
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
	$trueUrl = '../../_systool/position/_list.php?' . $getStr;
	
	/* ------------------------------------------------
	*	- 도움말 - 데이터 변환 진행
	*  ------------------------------------------------
	*/
	$dataString = class_load('string');
	
	if ($proc != 'DELETE') {
		
		$dataString->addItem('p_name', $pName, 'C');
		$dataString->addItem('p_sort', $pSort, 'C');
	}
	
	$dataString->addItem('p_ip', $pIp, 'C');

	/* ------------------------------------------------
	*	- 도움말 - 데이터 변환 후 등록,수정,삭제
	*  ------------------------------------------------
	*/
	if ($proc == 'WRITE') {			//등록
		$dataString->addItem('p_code', $pCode, 'C');
		$dataString->addItem('p_write_date', $pDate, 'C');

		list($insertColumn, $insertValue) = $dataString->getInsert();
		$processQuery = "Insert Into " . GD_POSITION . " (" . $insertColumn . ") Values (" . $insertValue . ")";
	}
	else if ($proc == 'MODIFY') {	// 수정
		$dataString->addItem('p_edit_date', $pDate, 'C');

		$updateValue = $dataString->getUpdate();
		$processQuery = "Update " . GD_POSITION . " Set " . $updateValue . " Where p_index = '" . $pIndex . "'";
	}
	else {							// 삭제
		$dataString->addItem('p_edit_date', $pDate, 'C');

		$dataString->addItem('p_delete_flag', 'y', 'C');

		$updateValue = $dataString->getUpdate();
		$processQuery = "Update " . GD_POSITION . " Set " . $updateValue . " Where p_index = '" . $pIndex . "'";
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

