<?php
	include ('../../_admin/_include/sysproctop.php');

	/* ------------------------------------------------
	*	- 도움말 - 넘어오는 값
	*  ------------------------------------------------
	*/
					
	$mrIndex				= trimPostRequest('mr_index');						//------------ 일련번호
	$mIndex					= trimPostRequest('m_index');						//------------ 매뉴얼 일련번호
	$mrName					= trimPostRequest('mr_name');						//------------ 이름
	$mrContents				= trimPostRequest('mr_contents');					//------------ 내용

	/* ------------------------------------------------
	*	- 도움말 - 등록 데이터 생성
	*  ------------------------------------------------
	*/
	$mIp				= $_SERVER['REMOTE_ADDR'];			//------------ ip
	$mDate				= date('Y-m-d H:i:s');				// 등록, 수정, 삭제일
	
	/* ------------------------------------------------
	*	- 도움말 - 초기화
	*  ------------------------------------------------
	*/
	$falseUrl = '';
	$trueUrl = 'parent.location.reload();';
	
	/* ------------------------------------------------
	*	- 도움말 - 데이터 변환 진행 addItem('필드명','삽입데이터','데이터형')
	*  ------------------------------------------------
	*/
	$dataString = class_load('string');

	$dataString->addItem('mr_name', $mrName, 'C');
	$dataString->addItem('mr_ip', $mIp, 'C');
	$dataString->addItem('mr_contents', $mrContents, 'C');




	/* ------------------------------------------------
	*	- 도움말 - 데이터 변환 후 등록,수정,삭제
	*  ------------------------------------------------
	*/
	if (!$mrIndex) {			//등록
		$dataString->addItem('m_index', $mIndex, 'C');
		$dataString->addItem('mr_writedate', $mDate, 'C');

		list($insertColumn, $insertValue) = $dataString->getInsert(); //인서트메소드로 $dataString인서트문 생성
		$processQuery = "Insert Into " . GD_MANUAL_REPLY . " (" . $insertColumn . ") Values (" . $insertValue . ")";
	} 
	else {							// 삭제
		$dataString->addItem('mr_editdate', $mDate, 'C');
		$dataString->addItem('mr_deleteFl', 'y', 'C');
		$updateValue = $dataString->getUpdate();
		$processQuery = "Update " . GD_MANUAL_REPLY . " Set " . $updateValue . " Where mr_index = '" . $mrIndex . "'";
	}
	
	/* ------------------------------------------------
	*	- 도움말 - 처리 결과
	*  ------------------------------------------------
	*/
	//echo $processQuery;
	$db->query($processQuery) or die(setAlertValueURL('저장에 실패하였습니다.', $falseUrl));
	//$db->query($processQuery) or die(mysql_error().$processQuery); //쿼리 에러시 확인
	
	setAlertValueURL('정상적으로 저장 하였습니다.', $trueUrl);
?>

