<?php
	include ('../../_admin/_include/sysproctop.php');

	/* ------------------------------------------------
	*	- 도움말 - 넘어오는 값
	*  ------------------------------------------------
	*/
	$proc				= trimPostRequest('proc');			//------------ 처리구분

	$sIndex				= trimPostRequest('s_index');		//------------ 일련번호
	$sCode				= trimPostRequest('s_code');		//------------ 코드
	$sName				= trimPostRequest('s_name');		//------------ 이름
	$saveUseType		= trimPostRequest('useType');		//----------- 등록 솔루션 사용 조건
		
	$listCnt			= trimPostRequest('listCnt');		//------------ 출력되는 리스트 수
	$page				= trimPostRequest('page');			//------------ 현재 페이지

	$selectType			= trimPostRequest('selectType');	//------------ 검색 조건
	$selectValue		= trimPostRequest('selectValue');	//------------ 검색값
	$arraySolutionUseType = trimPostRequest('solutionUseType');		//----------- 검색 솔루션 사용 조건
	$sort				= trimPostRequest('sort');			//------------ 검색값

	/* ------------------------------------------------
	*	- 도움말 - 등록 데이터 생성
	*  ------------------------------------------------
	*/
	$sIp				= $_SERVER['REMOTE_ADDR'];			//------------ ip
	$sDate				= date('Y-m-d H:i:s');				// 등록, 수정, 삭제일

	include '../../_inc/code.class.php';
	$solution = class_load('solution');
	
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
	$getStr = getArrayStr($getStr, 'solutionUseType[]', $arraySolutionUseType);

	/* ------------------------------------------------
	*	- 도움말 - 초기화
	*  ------------------------------------------------
	*/
	$falseUrl = '';
	$trueUrl = '../../_systool/solution/_list.php?' . $getStr;
	
	/* ------------------------------------------------
	*	- 도움말 - 데이터 변환 진행
	*  ------------------------------------------------
	*/
	$dataString = class_load('string');
	$dataString->addItem('s_name', $sName, 'C');
	$dataString->addItem('s_ip', $sIp, 'C');
	$dataString->addItem('s_delete_flag', 'n', 'C');

	foreach ($solution->arrayUseFieldName as $useType => $fieldName) {
		$flag = 'n';
		if (is_array($saveUseType)) {
			if (in_array($useType, $saveUseType)) {
				$flag = 'y';
			}
		}
		else {
			if ($useType == $saveUseType) {
				$flag = 'y';
			}
		}
		$dataString->addItem($fieldName, $flag, 'C');
	}

	/* ------------------------------------------------
	*	- 도움말 - 데이터 변환 후 등록,수정,삭제
	*  ------------------------------------------------
	*/
	if ($proc == 'WRITE') {			//등록
		$dataString->addItem('s_code', $sCode, 'C');
		$dataString->addItem('s_write_date', $sDate, 'C');

		list($insertColumn, $insertValue) = $dataString->getInsert();
		
		$processQuery = "Insert Into " . GD_SOLUTION . " (" . $insertColumn . ") Values (" . $insertValue . ")";
	}
	else if ($proc == 'MODIFY') {	// 수정
		$dataString->addItem('s_edit_date', $sDate, 'C');

		$updateValue = $dataString->getUpdate();
		$processQuery = "Update " . GD_SOLUTION . " Set " . $updateValue . " Where s_index = '" . $sIndex . "'";
	}
	else {							// 삭제
		$processQuery = "Update " . GD_SOLUTION . " Set ms_deleteFl = 'y', ms_editdate = '" . $sDate . "' Where ms_index = '" . $sIndex . "'";
	}
	/* ------------------------------------------------
	*	- 도움말 - 처리 결과
	*  ------------------------------------------------
	*/
	
	$db->query($processQuery) or die(setXMLValueURL('false', '저장에 실패하였습니다.', $falseUrl));
	//$db->query($processQuery) or die(mysql_error().$processQuery); //쿼리 에러시 확인
	
	setXMLValueURL('true', '정상적으로 저장 하였습니다.', $trueUrl);
?>

