<?php
	include ('../../_admin/_include/sysproctop.php');

	/* ------------------------------------------------
	*	- 도움말 - 넘어오는 값
	*  ------------------------------------------------
	*/
	$proc				= trimPostRequest('proc');			//------------ 처리구분

	$ctIndex			= trimPostRequest('xUidx');			//------------ 일련번호
	$ctCode				= trimPostRequest('ct_code');		//------------ 코드
	$ctName				= trimPostRequest('ct_name');		//------------ 타입명
	$ctSort				= trimPostRequest('ct_sort');		//------------ 정렬순서
	
	$listCnt			= trimPostRequest('listCnt');		//------------ 출력되는 리스트 수
	$page				= trimPostRequest('page');			//------------ 현재 페이지

	$selectType			= trimPostRequest('selectType');	//------------ 검색 조건
	$selectValue		= trimPostRequest('selectValue');	//------------ 검색값
	$sort				= trimPostRequest('sort');			//------------ 검색값

	/* ------------------------------------------------
	*	- 도움말 - 등록 데이터 생성
	*  ------------------------------------------------
	*/
	$ctIp				= $_SERVER['REMOTE_ADDR'];			//------------ ip
	$ctDate			= date('Y-m-d H:i:s');				// 등록, 수정, 삭제일
	
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
	$trueUrl = '../../_systool/code/_type_list.php?' . $getStr;
	/* ------------------------------------------------
	*	- 도움말 - 데이터 변환 진행
	*  ------------------------------------------------
	*/
	$dataString = class_load('string');
	
	if ($proc != 'DELETE') {
		
		$dataString->addItem('ct_name', $ctName, 'C');
		$dataString->addItem('ct_sort', $ctSort, 'I');
	}
	$dataString->addItem('ct_ip', $ctIp, 'C');

	/* ------------------------------------------------
	*	- 도움말 - 데이터 변환 후 등록,수정,삭제
	*  ------------------------------------------------
	*/
	if ($proc == 'WRITE') {			//등록
		if (dataOverlapCheck(GD_CODE_TYPE, 'ct_code', $ctCode)) {
			$dataString->addItem('ct_code', $ctCode, 'C');
			$dataString->addItem('ct_write_date', $ctDate, 'C');

			list($insertColumn, $insertValue) = $dataString->getInsert();
			$processQuery = "Insert Into " . GD_CODE_TYPE . " (" . $insertColumn . ") Values (" . $insertValue . ")";
		}
		else {
			$db->query($processQuery) or die(setXMLValueURL('false', '중복 된 코드값이 있습니다.', $falseUrl));
		}
	}
	else if ($proc == 'MODIFY') {	// 수정
		$dataString->addItem('ct_edit_date', $ctDate, 'C');

		$updateValue = $dataString->getUpdate();
		$processQuery = "Update " . GD_CODE_TYPE . " Set " . $updateValue . " Where ct_index = '" . $ctIndex . "'";
	}
	else {							// 삭제
		$dataString->addItem('ct_edit_date', $ctDate, 'C');
		
		$dataString->addItem('ct_delete_flag', 'y', 'C');
		$updateValue = $dataString->getUpdate();
		$processQuery = "Update " . GD_CODE_TYPE . " Set " . $updateValue . " Where ct_index = '" . $ctIndex . "'";
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

