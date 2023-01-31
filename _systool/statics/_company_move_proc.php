<?php
	include ('../../_admin/_include/sysproctop.php');

	$proc				= trimPostRequest('proc');			// 처리구분
	$grmIndex			= trimPostRequest('grm_index');		// 일련번호
	$rmCode				= trimPostRequest('ra_code');		// 권한 권한
	$rdCode				= trimPostRequest('rd_code');		// 부서 코드
	$grmName			= trimPostRequest('grm_name');		// 이름
	$grmId				= trimPostRequest('grm_id');		// 아이디
	$grmPw				= trimPostRequest('grm_password');	// 비밀번호
	$grmEnName			= trimPostRequest('grm_enName');	// 영문이름
	$grmEmail			= trimPostRequest('grm_email');		// 이메일 주소
	$grmPosition		= trimPostRequest('grm_position');	// 직급
	$grmExtension		= trimPostRequest('grm_extension');	// 내선번호
	$grmIpCheckFl		= trimPostRequest('grm_ip_checkFl');// 로그인시 IP 체크 유무
	if (!$grmIpCheckFl) $grmIpCheckFl = 'n';
	$grmColor			= trimPostRequest('grm_color');		// 선택 색상


	$selectType			= trimPostRequest('selectType');	//------------ 검색 조건
	$selectValue		= trimPostRequest('selectValue');	//------------ 검색값
	$sort				= trimPostRequest('sort');			//------------ 검색값

	/* ------------------------------------------------
	*	- 도움말 - 등록 데이터 생성
	*  ------------------------------------------------
	*/
	$editIp				= $_SERVER['REMOTE_ADDR'];			// 등록, 수정, 삭제 ip
	$grmDate			= date('Y-m-d H:i:s');				// 등록, 수정, 삭제일

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
	if (!empty($_POST['ma_codesearch'])) {
		foreach ($_POST['ma_codesearch'] as $maCodeSearchValue) {
			$getStr = getStr($getStr,'ma_codesearch[]',$maCodeSearchValue);
		}
	}

	// 부서 권한 검색 데이터 존재시 Get 데이터로 넘길값 생성
	if (!empty($_POST['md_codesearch'])) {
		foreach ($_POST['md_codesearch'] as $mdCodeSearchValue) {
			$getStr = getStr($getStr,'md_codesearch[]',$mdCodeSearchValue);
		}
	}

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
		$dataString->addItem('rd_code', $rdCode, 'C');
		$dataString->addItem('ra_code', $raCode, 'C');
		$dataString->addItem('grm_name', $grmName, 'C');
		$dataString->addItem('grm_password', $grmPw, 'P');
		$dataString->addItem('grm_enName', $grmEnName, 'C');
		$dataString->addItem('grm_email', $grmEmail, 'C');
		$dataString->addItem('grm_position', $grmPosition, 'C');
		$dataString->addItem('grm_extension', $grmExtension, 'C');
		$dataString->addItem('grm_ip_checkFl', $grmIpCheckFl, 'C');
		$dataString->addItem('grm_color', $grmColor, 'C');
	}
	$dataString->addItem('grm_editip', $editIp, 'C');

	if ($proc == 'WRITE') {
		$dataString->addItem('grm_id', $grmId, 'C');
		$dataString->addItem('grm_writedate', $grmDate, 'C');

		list($insertColumn, $insertValue) = $dataString->getInsert();
		$processQuery = "Insert Into " . GD_RELOCATION_MEMBER . " (" . $insertColumn . ") Values (" . $insertValue . ")";
	}
	else if ($proc == 'MODIFY') {
		$dataString->addItem('grm_editdate', $grmDate, 'C');

		$updateValue = $dataString->getUpdate();
		$processQuery = "Update " . GD_RELOCATION_MEMBER . " Set " . $updateValue . " Where grm_index = '" . $grmIndex . "'";
	}
	else {
		$dataString->addItem('grm_deleteFl', 'y', 'C');
		$dataString->addItem('grm_editdate', $grmDate, 'C');
		
		$updateValue = $dataString->getUpdate();
		$processQuery = "Update " . GD_RELOCATION_MEMBER . " Set " . $updateValue . " Where grm_index = '" . $grmIndex . "'";
	}

//	echo $processQuery;
	$db->query($processQuery) or die(setXMLValueURL('false', '저장에 실패하였습니다.', $falseUrl));
	//$db->query($processQuery) or die(mysql_error().$processQuery); //쿼리 에러시 확인
	
	setXMLValueURL('true', '정상적으로 저장 하였습니다.', $trueUrl);
?>

