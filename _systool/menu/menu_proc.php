<?php
	include ('../../_admin/_include/sysproctop.php');

	$mIndex			= trimPostRequest('m_index');			// 일련번호
	$mCode			= trimPostRequest('m_code');			// 메뉴코드
	$mSubject		= trimPostRequest('m_subject');			// 제목
	$mExplain		= trimPostRequest('m_explain');			// 메뉴 설명
	$mUrl			= trimPostRequest('m_url');				// URL
	$mParent		= trimPostRequest('m_parent');			// 부모글 일련번호
	$mSort			= trimPostRequest('m_sort');			// 정렬 순서
	$mViewFlag		= trimPostRequest('m_view_flag');		// 노출 여부
	$mUseFlag		= trimPostRequest('m_use_flag');			// 사용 여부
	$mLinktype		= trimPostRequest('m_link_type');		// 링크 구분
	$mLogin			= trimPostRequest('m_login');			// 로그인 구분

	$arrayMamButton = array();
	for ($i = 1; $i <= 7; $i++) {
		if (!$_POST['m_button' . $i]) $_POST['m_button' . $i] = 'N';
		$arrayMButton[]	= trimPostRequest('m_button' . $i);	// 버튼 사용 여부
	}
	
	$mDate			= date('Y-m-d H:i:s');				// 등록, 수정, 삭제일

	$proc				= trimPostRequest('proc');				// 처리구분
	
	$mButton			= implode(',', $arrayMButton);

	$falseUrl = '';
	$trueUrl = 'javascript:location.reload()';
	
	$dataString = class_load('string');
	
	$dataString->addItem('m_subject', $mSubject, 'C');
	$dataString->addItem('m_explain', $mExplain, 'C');
	$dataString->addItem('m_url', $mUrl, 'C');
	$dataString->addItem('m_parent', $mParent, 'C');
	$dataString->addItem('m_sort', $mSort, 'C');
	$dataString->addItem('m_view_flag', $mViewFlag, 'C');
	$dataString->addItem('m_use_flag', $mUseFlag, 'C');
	$dataString->addItem('m_link_type', $mLinktype, 'C');
	$dataString->addItem('m_login', $mLogin, 'C');
	$dataString->addItem('m_button', $mButton, 'C');
	$dataString->addItem('m_code', $mCode, 'C');

	if ($proc == 'WRITE') {
		$dataString->addItem('m_write_date', $mDate, 'C');

		list($insertColumn, $insertValue) = $dataString->getInsert();
		$processQuery = "Insert Into " . GD_MENU . " (" . $insertColumn . ") Values (" . $insertValue . ")";

		session_start();
		$_SESSION['sess']['aMenuCode'] .= $mCode . '|'; // 현재 등록한 사용자 세션에 등록되어 있는 메뉴 권한 수정

		$memberAuthUpdateQuery = "Update " . GD_AUTH . " Set a_menu_code = Concat(a_menu_code, '" . $mCode . '|' . "') Where a_code = '" . $_SESSION['sess']['aCode'] . "' and not a_menu_code like '%|" . $mCode . "|%'";
		//echo $memberAuthUpdateQuery . '<br />';
		$db->query($memberAuthUpdateQuery); // 현재 DB에 등록되어 있는 사용자 권한 메뉴 권한 수정
	}
	else if ($proc == 'MODIFY') {
		$dataString->addItem('m_edit_date', $mDate, 'C');

		$updateValue = $dataString->getUpdate();
		$processQuery = "Update " . GD_MENU . " Set " . $updateValue . " Where m_index = '" . $mIndex . "'";

		session_start();
		$_SESSION['sess']['aMenuCode'] .= $mCode . '|'; // 현재 등록한 사용자 세션에 등록되어 있는 메뉴 권한 수정

		$memberAuthUpdateQuery = "Update " . GD_AUTH . " Set a_menu_code = Concat(a_menu_code, '" . $mCode . '|' . "') Where a_code = '" . $_SESSION['sess']['aCode'] . "' and not a_menu_code like '%|" . $mCode . "|%'";
		//echo $memberAuthUpdateQuery . '<br />';
		$db->query($memberAuthUpdateQuery);
	}
	else {
		$processQuery = "Update " . GD_MENU . " Set m_delete_flag = 'y', m_edit_date = '" . $mDate . "' Where m_index = '" . $mIndex . "'";
		
		list($codeCnt) = $db->query("Select count(m_index) From " . GD_MENU . " Where m_code = '" . $mCode . "'");

		if ($codeCnt <= 1) {
			$codeUpdateQuery = "Update " . GD_AUTH . " Set a_menu_code = REPLACE(a_menu_code, '|" . $mCode . "|', '|') Where a_menu_code like concat('%|" . $mCode . "|%')";
			$db->query($codeUpdateQuery);
		}
	}
	
	//echo $processQuery;
	$db->query($processQuery) or die(setXMLValueURL('false', '저장에 실패하였습니다.', $falseUrl));
	//$db->query($processQuery) or die(mysql_error().$processQuery); //쿼리 에러시 확인
	
	setXMLValueURL('true', '정상적으로 저장 하였습니다.', $trueUrl);
	 
?>

