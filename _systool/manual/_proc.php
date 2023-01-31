<?php
	include ('../../_admin/_include/sysproctop.php');

	/* ------------------------------------------------
	*	- 도움말 - 넘어오는 값
	*  ------------------------------------------------
	*/
	$proc				= trimPostRequest('proc');							//------------ 처리구분
								
	$mIndex				= trimPostRequest('m_index');						//------------ 일련번호
	$mName				= trimPostRequest('m_name');						//------------ 이름
	

	if (!empty($_POST['md_code'])) {
		$mDivisionCode		= '|' . implode('|', $_POST['md_code']) . '|';			//------------ 부서 권한 코드
	}
	
	if (!empty($_POST['mc_code'])) {
		$mCategoryCode		= '|' . implode('|', $_POST['mc_code']) . '|';			//------------ 카테고리 권한
	}

	if (!empty($_POST['ms_code'])) {
		$mSolutionCode		= '|' . implode('|', $_POST['ms_code']) . '|';			//------------ 솔루션 권한 코드
	}


	//$mWriteDate			= trimPostRequest('m_writedate');					//------------ 작성날짜
	$mContents			= trimPostRequest('m_contents');					//------------ 내용
	$mSubject			= trimPostRequest('m_subject');						//------------ 제목

	
	$listCnt			= trimPostRequest('listCnt');						//------------ 출력되는 리스트 수
	$page				= trimPostRequest('page');							//------------ 현재 페이지

	$selectType			= trimPostRequest('selectType');					//------------ 검색 조건
	$selectValue		= trimPostRequest('selectValue');					//------------ 검색값
	$sort				= trimPostRequest('sort');							//------------ 검색값

	/* ------------------------------------------------
	*	- 도움말 - 등록 데이터 생성
	*  ------------------------------------------------
	*/
	$mIp				= $_SERVER['REMOTE_ADDR'];			//------------ ip
	$mDate				= date('Y-m-d H:i:s');				// 등록, 수정, 삭제일
	$mppDate			= date('Y-m-d H:i:s');	
	$mrDate				= date('Y-m-d H:i:s');	
	
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

	// 솔루션 검색 데이터 존재시 Get 데이터로 넘길값 생성
	if (!empty($_POST['ms_codesearch'])) {
		foreach ($_POST['ms_codesearch'] as $mamCodeSearchValue) {
			$getStr = getStr($getStr,'ms_codesearch[]',$mamCodeSearchValue);
		}
	}
	
	// 카테고리 검색 데이터 존재시 Get 데이터로 넘길값 생성
	if (!empty($_POST['mc_codesearch'])) {
		foreach ($_POST['mc_codesearch'] as $mcCodeSearchValue) {
			$getStr = getStr($getStr,'mc_codesearch[]',$mcCodeSearchValue);
		}
	}
	
	// 부서 검색 데이터 존재시 Get 데이터로 넘길값 생성
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
	$trueUrl = '../../_systool/manual/_list.php?' . $getStr;
	
	/* -------------------------------------------
	*	- Advice - 파일 초기화 / 업로드
	*  -------------------------------------------
	*/ 
	if($_FILES['m_fileupload']['name']){
		$fileName = urlencode($_FILES['m_fileupload']['name']);
				
		$upload_dir = '../../manual_upload/';

		if(!is_dir($upload_dir)){
			mkdir($upload_dir);
			chmod($upload_dir,0707);
		}

		$uploadFile = $upload_dir . basename($fileName);
		
		$upload_result = move_uploaded_file($_FILES['m_fileupload']['tmp_name'], $uploadFile);
		
		if(!$upload_result){
			setXMLValueURL('false', '파일 업로드에 실패 하였습니다.', $falseUrl);
			return false;
		}
	}

	/* ------------------------------------------------
	*	- 도움말 - 데이터 변환 진행 addItem('필드명','삽입데이터','데이터형')
	*  ------------------------------------------------
	*/
	$dataString = class_load('string');


	$dataString->addItem('m_division_code', $mDivisionCode, 'C');
	$dataString->addItem('m_category_code', $mCategoryCode, 'C');
	$dataString->addItem('m_solution_code', $mSolutionCode, 'C');

	if ($fileName) {
		$dataString->addItem('m_fileupload', $fileName, 'C');
	}
	$dataString->addItem('m_name', $mName, 'C');
	$dataString->addItem('m_ip', $mIp, 'C');

	$dataString->addItem('m_contents', $mContents, 'C');
	$dataString->addItem('m_subject', $mSubject, 'C');




	/* ------------------------------------------------
	*	- 도움말 - 데이터 변환 후 등록,수정,삭제
	*  ------------------------------------------------
	*/
	if ($proc == 'WRITE') {			//등록
		$dataString->addItem('m_writedate', $mDate, 'C');
		session_start();
		$dataString->addItem('m_mail',  $_SESSION['sess']['mmId'], 'C');

		list($insertColumn, $insertValue) = $dataString->getInsert(); //인서트메소드로 $dataString인서트문 생성
		$processQuery = "Insert Into " . GD_MANUAL . " (" . $insertColumn . ") Values (" . $insertValue . ")";
	}
	else if ($proc == 'MODIFY') {	// 수정
		$dataString->addItem('m_editdate', $mDate, 'C');

		$updateValue = $dataString->getUpdate();
		$processQuery = "Update " . GD_MANUAL . " Set " . $updateValue . " Where m_index = '" . $mIndex . "'";
	}
	else {							// 삭제
		$dataString->addItem('m_editdate', $mDate, 'C');
		$dataString->addItem('mpp_editdate', $mppDate, 'C');
		$dataString->addItem('mr_editdate', $mrDate, 'C');
		$processQuery = "Update " . GD_MANUAL . " Set m_deleteFl = 'y' Where m_index = '" . $mIndex . "'";
		$processQuery2 = "Update " . GD_MANUAL_PROCESS_PATCH . " Set mpp_deleteFl = 'y', mpp_editdate = '" . $mppDate . "' Where m_index = '" . $mIndex ."'";
		$processQuery3 = "Update " . GD_MANUAL_REPLY . " Set mr_deleteFl = 'y', mr_editdate = '" . $mrDate . "' Where m_index = '". $mIndex ."'";
		
		$db->query($processQuery2) or die(setAlertValueURL('저장에 실패하였습니다.', $falseUrl));
		$db->query($processQuery3) or die(setAlertValueURL('저장에 실패하였습니다.', $falseUrl));
	}
	
	/* ------------------------------------------------
	*	- 도움말 - 처리 결과
	*  ------------------------------------------------
	*/
	//print_r($_POST); 
	//echo $processQuery;
	$db->query($processQuery) or die(setAlertValueURL('저장에 실패하였습니다.', $falseUrl));


	//$db->query($processQuery) or die(mysql_error().$processQuery); //쿼리 에러시 확인
	
	setAlertValueURL('정상적으로 저장 하였습니다.', $trueUrl);
?>

