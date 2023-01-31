<?php
	include ('../../_admin/_include/sysproctop.php');

	$proc				= trimPostRequest('proc');			// 처리구분
	$ruIndex			= trimPostRequest('xUidx');			// 일련번호
	$aCode				= trimPostRequest('a_code');		// 권한 권한
	$dCode				= trimPostRequest('d_code');		// 부서 코드
	$pCode				= trimPostRequest('p_code');		// 직급 코드
	$ruName				= trimPostRequest('ru_name');		// 이름
	$ruId				= trimPostRequest('ru_id');		// 아이디
	$ruPw				= trimPostRequest('ru_password');	// 비밀번호
	$ruEnName			= trimPostRequest('ru_en_name');	// 영문이름
	
	$ruEmail			= trimPostRequest('ru_email');		// 이메일 주소
	
//	$ruPosition			= trimPostRequest('ru_position');	// 직급
	
	$ruExtension		= trimPostRequest('ru_extension');	// 내선번호
	
	$ruPermitIp			= trimPostRequest('ru_permit_ip');// 로그인시 체크 IP
	$ruIpCheckFl		= trimPostRequest('ru_permit_ip_check_flag');// 로그인시 IP 체크 유무
	
	if (!$ruIpCheckFl) $ruIpCheckFl = 'n';
	
	$ruColor			= trimPostRequest('ru_color');		// 선택 색상


	$selectType			= trimPostRequest('selectType');	//------------ 검색 조건
	$selectValue		= trimPostRequest('selectValue');	//------------ 검색값
	$sort				= trimPostRequest('sort');			//------------ 검색값

	/* ------------------------------------------------
	*	- 도움말 - 등록 데이터 생성
	*  ------------------------------------------------
	*/
	$editIp				= $_SERVER['REMOTE_ADDR'];			// 등록, 수정, 삭제 ip
	$ruDate			= date('Y-m-d H:i:s');				// 등록, 수정, 삭제일

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
	// 권한 검색 데이터 존재시 Get 데이터로 넘길값 생성
	$getStr = getArrayStr($getStr, 'a_codesearch[]', trimGetRequest('a_codesearch'));
	// 부서 권한 검색 데이터 존재시 Get 데이터로 넘길값 생성
	$getStr = getArrayStr($getStr, 'd_codesearch[]', trimGetRequest('d_codesearch'));
	// 직급 검색 데이터 존재시 Get 데이터로 넘길값 생성
	$getStr = getArrayStr($getStr, 'p_codesearch[]', trimGetRequest('p_codesearch'));

	/* ------------------------------------------------
	*	- 도움말 - 초기화
	*  ------------------------------------------------
	*/
	$falseUrl = '';
	$trueUrl = '../../_systool/member/_list.php?' . $getStr;

	/* ------------------------------------------------
	*	- 도움말 - 데이터 변환 진행
	*  ------------------------------------------------
	*/
	$dataString = class_load('string');
	
	if ($proc != 'DELETE') {
		
		$dataString->addItem('d_code', $dCode, 'C');
		$dataString->addItem('a_code', $aCode, 'C');
		$dataString->addItem('p_code', $pCode, 'C');
		$dataString->addItem('ru_name', $ruName, 'C');
		if ($ruPw) {
			$dataString->addItem('ru_password', $ruPw, 'P');
		}
		$dataString->addItem('ru_en_name', $ruEnName, 'C');
		
		$dataString->addItem('ru_email', $ruEmail, 'C');
		
//		$dataString->addItem('ru_position', $ruPosition, 'C');
		
		$dataString->addItem('ru_extension', $ruExtension, 'C');
	
		$dataString->addItem('ru_permit_ip', $ruPermitIp, 'C');
		$dataString->addItem('ru_permit_ip_check_flag', $ruIpCheckFl, 'C');
		
		$dataString->addItem('ru_color', $ruColor, 'C');
	
		}
	
		$dataString->addItem('ru_edit_ip', $editIp, 'C');

	
	if ($proc == 'WRITE') {
		$dataString->addItem('ru_id', $ruId, 'C');
		$dataString->addItem('ru_write_date', $ruDate, 'C');

		list($insertColumn, $insertValue) = $dataString->getInsert();
		$processQuery = "Insert Into " . GD_RELOCATION_USER . " (" . $insertColumn . ") Values (" . $insertValue . ")";
	}
	else if ($proc == 'MODIFY') {
		$dataString->addItem('ru_edit_date', $ruDate, 'C');

		$updateValue = $dataString->getUpdate();
		$processQuery = "Update " . GD_RELOCATION_USER . " Set " . $updateValue . " Where ru_index = '" . $ruIndex . "'";
	}
	else {
		
		$dataString->addItem('ru_delete_flag', 'y', 'C');
		
		$dataString->addItem('ru_edit_date', $ruDate, 'C');
		
		
		$updateValue = $dataString->getUpdate();
	$processQuery = "Update " . GD_RELOCATION_USER . " Set " . $updateValue . " Where ru_index = '" . $ruIndex . "'";
	}

	//echo $processQuery;
	$db->query($processQuery) or die(setXMLValueURL('false', '저장실패 하였습니다', $falseUrl));
	//$db->query($processQuery) or die(mysql_error().$processQuery); //쿼리 에러시 확인
	
	setXMLValueURL('true', '정상적으로 저장 하였습니다.', $trueUrl);
?>

