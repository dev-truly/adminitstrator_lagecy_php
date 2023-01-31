<?php
	
	include ('../../_admin/_include/sysproctop.php');
	$string = class_load('string');
	// 필요 정보
	$xUidx					= trimPostRequest('xUidx');					// 일련번호
	$proc					= trimPostRequest('proc');					// 등록, 수정, 삭제 구분
	$mode					= trimPostRequest('mode');					// 응대내역, 이전 내역 등록, 수정, 삭제 구분

	$saveDate				= date('Y-m-d H:i:s');						// 저장일
	$saveIP					= $_SERVER['REMOTE_ADDR'];					// 저장 IP


	/*
	* -------------------------------------------
	* - Advice - 전달 받는 파라미터
	* -------------------------------------------
	*/
	$returnPage		= trimPostRequest('returnPage');			//------------ 리턴 페이지
	
	/*
	* -------------------------------------------
	* - Advice - get데이터로 넘길값 생성
	* -------------------------------------------
	*/
	//신규등록/업데이트
	$getStr = '';
	if ($returnPage == '_schedule_list') {
		$year	= trimPostRequest('year');					//------------ 스케쥴 페이지 연도
		$month	= trimPostRequest('month');					//------------ 스케쥴 페이지 월
		$getStr = getStr($getStr,'year',$year);
		$getStr = getStr($getStr,'month',$month);
	}
	else {
		$returnPage == '_list';
		$listCnt		= trimPostRequest('listCnt');			//------------ 출력되는 리스트 수
		$page			= trimPostRequest('page');				//------------ 현재 페이지
		$searchState	= trimPostRequest('searchState');		//------------ 이전 상태 검색
		$selectType		= trimPostRequest('selectType');		//------------ 검색 조건
		$selectValue	= trimPostRequest('selectValue');		//------------ 검색값
		$sort			= trimPostRequest('sort');				//------------ 정렬방식
		$arraySearchOld		= trimPostRequest('searchOld');		//------------ 이전 전 솔루션
		$arraySearchGodo	= trimPostRequest('searchGodo');	//------------ 이전 후 고도 솔루션

		$getStr = getStr($getStr,'listCnt',$listCnt);
		$getStr = getStr($getStr,'page',$page);
		$getStr = getStr($getStr,'searchState',$searchState);
		$getStr = getStr($getStr,'selectType',$selectType);
		$getStr = getStr($getStr,'selectValue',$selectValue);		
		$getStr = getStr($getStr,'sort',$sort);
		$getStr = getArrayStr($getStr, 'searchOld[]', $arraySearchOld);
		$getStr = getArrayStr($getStr,'searchGodo[]',$arraySearchGodo);
	}
		$getStr = getStr($getStr,'returnPage',$returnPage);
	//-------------------------------------------
	//- Advice - 초기화
	//-------------------------------------------
	$falseUrl = '';
	
	switch ($mode) {
		case 'dataTypeSave' :
			$rdlIndex			= trimPostRequest('rdl_index');			// 배열 형태 데이터 이전 내역 로그 일련번호
			$rdlDataType		= trimPostRequest('rdl_data_type');		// 데이터 종류
			$rdlDataRange		= trimPostRequest('rdl_data_range');	// 데이터 범위
			$rdlDataCnt			= trimPostRequest('rdl_data_cnt');		// 데이터 이전 갯수
			$rdlCompleteDate	= trimPostRequest('rdl_complete_date');	// 이전 일자
			$rdlProcType		= trimPostRequest('rdl_proc_type');		// 처리 구분
			$grmIndex			= trimPostRequest('grm_index');			// 담당자 일련번호

			
			$string->AddItem('rdl_data_type', $rdlDataType, 'C');
			$string->AddItem('rdl_data_range', $rdlDataRange, 'C');
			$string->AddItem('rdl_data_cnt', $rdlDataCnt, 'C');
			$string->AddItem('rdl_complete_date', $rdlCompleteDate, 'C');
			$string->AddItem('rdl_proc_type', $rdlProcType, 'C');
			$string->AddItem('grm_index', $grmIndex, 'C');
			$string->AddItem('rdl_ip', $saveIP, 'C');

			if ($rdlIndex) {
				$string->AddItem('rdl_editdate', $saveDate, 'C');

				$updateValue = $string->GetUpdate();
				$processQuery = "Update " . GD_RELOCATION_DATA_LOG . " Set ".$updateValue." Where rdl_index = '" . $rdlIndex . "'";

			}
			else {
				$string->AddItem('rm_index', $xUidx, 'C');
				$string->AddItem('rdl_writedate', $saveDate, 'C');

				list($insertColum, $insertValue) = $string->getInsert();
				$processQuery = "Insert Into " . GD_RELOCATION_DATA_LOG . " (" . $insertColum . ") Values (" . $insertValue . ")";
			}

			$db->query($processQuery) or die(setXMLValueURL('false', '저장에 실패하였습니다.', $falseUrl));

			$trueUrl = 'javascript:location.reload();';
			setXMLValueURL('true', '정상적으로 저장 하였습니다.', $trueUrl);

			break;
		case 'dataTypeDel' :
			$rdlIndex			= trimPostRequest('rdl_index');			// 배열 형태 데이터 이전 내역 로그 일련번호
			
			$string->AddItem('grm_index', $grmIndex, 'C');
			$string->AddItem('rdl_ip', $saveIP, 'C');
			$string->AddItem('rdl_deleteFl', 'y', 'C');
			$string->AddItem('rdl_editdate', $saveDate, 'C');

			$updateValue = $string->GetUpdate();
			$processQuery = "Update " . GD_RELOCATION_DATA_LOG . " Set ".$updateValue." Where rdl_index = '" . $rdlIndex . "'";

			$db->query($processQuery) or die(setXMLValueURL('false', '저장에 실패하였습니다.', $falseUrl));

			$trueUrl = 'javascript:location.reload();';
			setXMLValueURL('true', '정상적으로 삭제 하였습니다.', $trueUrl);

			break;
		case 'allDataTypeSave' :
				
			$arrayRdlIndex				= trimPostRequest('rdl_index');			// 배열 형태 데이터 이전 내역 로그 일련번호
			$arrayRdlDataType			= trimPostRequest('rdl_data_type');		// 데이터 종류
			$arrayRdlDataRange			= trimPostRequest('rdl_data_range');		// 데이터 범위
			$arrayRdlDataCnt			= trimPostRequest('rdl_data_cnt');			// 데이터 이전 갯수
			$arrayRdlCompleteDate		= trimPostRequest('rdl_complete_date');	// 이전 일자
			$arrayRdlProcType			= trimPostRequest('rdl_proc_type');		// 처리 구분
			$arrayGrmIndex				= trimPostRequest('grm_index');			// 담당자 일련번호
			
			for ($arrayCnt = 0; $arrayCnt <= count($arrayRdlDataType)-1; $arrayCnt++){
				$rdlIndex			= $arrayRdlIndex[$arrayCnt];
				$rdlDataType		= $arrayRdlDataType[$arrayCnt];
				$rdlDataRange		= $arrayRdlDataRange[$arrayCnt];
				$rdlDataCnt			= $arrayRdlDataCnt[$arrayCnt];
				$rdlCompleteDate	= $arrayRdlCompleteDate[$arrayCnt];
				$rdlProcType		= $arrayRdlProcType[$arrayCnt];
				$grmIndex			= $arrayGrmIndex[$arrayCnt];

				$string->setInit();
				$string->AddItem('rdl_data_type', $rdlDataType, 'C');
				$string->AddItem('rdl_data_range', $rdlDataRange, 'C');
				$string->AddItem('rdl_data_cnt', $rdlDataCnt, 'C');
				$string->AddItem('rdl_complete_date', $rdlCompleteDate, 'C');
				$string->AddItem('rdl_proc_type', $rdlProcType, 'C');
				$string->AddItem('grm_index', $grmIndex, 'C');
				$string->AddItem('rdl_ip', $saveIP, 'C');

				if ($rdlIndex) {
					$string->AddItem('rdl_writedate', $saveDate, 'C');
					$updateValue = $string->GetUpdate();
					$processQuery = "Update " . GD_RELOCATION_DATA_LOG . " Set ".$updateValue." Where rdl_index = '" . $rdlIndex . "'";
				}
				else {
					$string->AddItem('rm_index', $xUidx, 'C');
					$string->AddItem('rdl_editdate', $saveDate, 'C');

					list($insertColum, $insertValue) = $string->getInsert();
					$processQuery = "Insert Into " . GD_RELOCATION_DATA_LOG . " (" . $insertColum . ") Values (" . $insertValue . ")";
				}

				$db->query($processQuery) or die(setXMLValueURL('false', '저장에 실패하였습니다.', $falseUrl));
			}

			$trueUrl = 'javascript:location.reload();';
			setXMLValueURL('true', '정상적으로 저장 하였습니다.', $trueUrl);
			
			break;
		case 'responseSave' :
			$rrlIndex						= trimPostRequest('rrl_index');				// 응대 내역 일련번호
			$rrlType						= trimPostRequest('rrl_type');				// 검수 안내 구분
			$rrlResponseType				= trimPostRequest('rrl_response_type');		// 응대 방법
			$grmIndex						= trimPostRequest('grm_index');				// 답변 담당자
			$rrlResponseDate				= trimPostRequest('rrl_response_date');		// 답변일
			$rrlInquiryContents				= trimPostRequest('rrl_inquiry_contents');	// 문의 신청내용
			$rrlContents					= trimPostRequest('rrl_contents');			// 답변 내용

			$string->AddItem('rrl_type', $rrlType, 'C');
			$string->AddItem('rrl_response_type', $rrlResponseType, 'C');
			$string->AddItem('grm_index', $grmIndex, 'C');
			$string->AddItem('rrl_response_date', $rrlResponseDate, 'C');
			$string->AddItem('rrl_inquiry_contents', $rrlInquiryContents, 'C');
			$string->AddItem('rrl_contents', $rrlContents, 'C');
			$string->AddItem('rrl_ip', $saveIP, 'C');
			
			
			if ($rrlType == 'exam') {
				$rmUpdateQuery = "Update " . GD_RELOCATION_MALL . " Set rm_state = 'exam' Where rm_index = '" . $xUidx . "'";

				$db->query($rmUpdateQuery) or die(setXMLValueURL('false', '저장에 실패하였습니다.', $falseUrl));
			}
			
			if ($rrlIndex) {
				$string->AddItem('rrl_editdate', $saveDate, 'C');

				$updateValue = $string->GetUpdate();
				$processQuery = "Update " . GD_RELOCATION_RESPONSE_LOG . " Set ".$updateValue." Where rrl_index = '" . $rrlIndex . "'";
			}
			else {
				$string->AddItem('rm_index', $xUidx, 'C');
				$string->AddItem('rrl_writedate', $saveDate, 'C');

				list($insertColum, $insertValue) = $string->getInsert();
				$processQuery = "Insert Into " . GD_RELOCATION_RESPONSE_LOG . " (" . $insertColum . ") Values (" . $insertValue . ")";
			}
			
			
			$db->query($processQuery) or die(setXMLValueURL('false', '저장에 실패하였습니다.', $falseUrl));
			$getStr = getStr($getStr,'xUidx',$xUidx);
			if (!$rrlIndex) {
				$rrlIndex = mysql_insert_id();
			}
			$getStr = getStr($getStr,'rrlIndex',$rrlIndex);
			$trueUrl = './_view.php?' . $getStr;
			setXMLValueURL('true', '정상적으로 저장 하였습니다.', $trueUrl);

			break;
		case 'responseDel' :
			$rrlIndex						= trimPostRequest('rrl_index');				// 응대 내역 일련번호
			
			$string->AddItem('rrl_deleteFl', 'y', 'C');
			$string->AddItem('rrl_ip', $saveIP, 'C');
			$string->AddItem('rrl_editdate', $saveDate, 'C');

			$updateValue = $string->GetUpdate();
			$processQuery = "Update " . GD_RELOCATION_RESPONSE_LOG . " Set " . $updateValue . " Where rrl_index = '" . $rrlIndex . "'";
			

			$db->query($processQuery) or die(setXMLValueURL('false', '삭제에 실패하였습니다.', $falseUrl));
			
			$trueUrl = 'javascript:location.reload();';
			setXMLValueURL('true', '정상적으로 삭제 하였습니다.', $trueUrl);
			break;
		default :
			// 고객 정보 Request
			$rmName					= trimPostRequest('rm_name');				// 쇼핑몰명
			$rmState				= trimPostRequest('rm_state');				// 진행상태
			$rmStateCause			= trimPostRequest('rm_state_cause');		// 진행중 이유
			$rmGodoId				= trimPostRequest('rm_godo_id');			// 고도 아이디
			$rmAdminName			= trimPostRequest('rm_admin_name');			// 쇼핑몰 관리자
			$rmAdminEmail			= trimPostRequest('rm_admin_email');		// 관리자 이메일
			$rmDefaultTel			= trimPostRequest('rm_default_tel');		// 주사용 연락처
			$rmSubTel				= trimPostRequest('rm_sub_tel');			// 보조연락처
			$rmRelocationPriceFl	= trimPostRequest('rm_relocation_priceFl'); // 추가 비용 발생 여부
			$rmMainItem				= trimPostRequest('rm_main_item');			// 주요 취급 품목

			// 기존 쇼핑몰 정보 Request
			$rmBeforePG				= trimPostRequest('rm_before_PG');	// 기존 사용 PG
			$rmAfterPG				= trimPostRequest('rm_after_PG');	// 신규 사용 PG
			$rmPgMoveType			= trimPostRequest('rm_pg_move_type'); // PG 신규 신청 여부

			$rmMallBuyDate			= trimPostRequest('rm_mall_buy_date'); // PG 구매일

			$rmBeforeSolutionCode		= trimPostRequest('rm_before_solution_code');	// 쇼핑몰 코드
			$rmBeforeEtcSolutionName	= ($rmBeforeSolutionCode == 'ETC') ? trimPostRequest('rm_before_etc_solution_name') : ''; // 기타 쇼핑몰 명
			$rmBeforeSiteUrl			= trimPostRequest('rm_before_site_url');		// URL
			$rmBeforeFtpIp				= trimPostRequest('rm_before_ftp_ip');			// FTP IP
			$rmBeforeFtpPort			= trimPostRequest('rm_before_ftp_port');		// FTP 포트
			$rmBeforeFtpId				= trimPostRequest('rm_before_ftp_id');			// FTP ID
			$rmBeforeFtpPwd				= trimPostRequest('rm_before_ftp_pwd');			// FTP PWD
			$rmBeforeDbHost				= trimPostRequest('rm_before_db_host');			// DB IP
			$rmBeforeDbPort				= trimPostRequest('rm_before_db_port');			// DB 포트
			$rmBeforeDbName				= trimPostRequest('rm_before_db_name');			// DB 명
			$rmBeforeDbId				= trimPostRequest('rm_before_db_id');			// DB ID
			$rmBeforeDbPwd				= trimPostRequest('rm_before_db_pwd');			// DB PWD

			// 고도(이전 후) 쇼핑몰 정보 Request
			$rmGodoSolutionCode	= trimPostRequest('rm_godo_solution_code');	// 쇼핑몰 코드
			$rmGodoDomain		= trimPostRequest('rm_godo_domain');		// 고도 쇼핑몰 임시 도메인
			$rmGodoIp			= trimPostRequest('rm_godo_ip');			// FTP IP
			$rmGodoFtpId		= trimPostRequest('rm_godo_ftp_id');		// FTP ID
			$rmGodoFtpPwd		= trimPostRequest('rm_godo_ftp_pwd');		// FTP PWD
			$rmGodoDbHost		= trimPostRequest('rm_godo_db_host');		// DB HOST
			$rmGodoDbName		= trimPostRequest('rm_godo_db_name');		// DB NAME
			$rmGodoDbId			= trimPostRequest('rm_godo_db_id');			// DB ID
			$rmGodoDbPwd		= trimPostRequest('rm_godo_db_pwd');		// DB PWD
			
			// 이전 및 신청 사유
			$rmMoveReason			= trimPostRequest('rm_move_reason');		// 이전 사유
			$rmApplyReason			= trimPostRequest('rm_apply_reason');		// 신청 사유
			$rmRelocationReason		= trimPostRequest('rm_relocation_reason');	// 이전 및 신청 사유 상세 내용

			// 요청 및 전달사항 Request
			$rmRequestInfo		= trimPostRequest('rm_request_info');	// 요청 및 전달 사항
			
			if ($mode != 'delete') {
				// 데이터 쿼리 생성 준비
				$string->AddItem('rm_name', $rmName, 'C');
				$string->AddItem('rm_state', $rmState, 'C');
				$string->AddItem('rm_state_cause', $rmStateCause, 'C');
				$string->AddItem('rm_godo_id', $rmGodoId, 'C');
				$string->AddItem('rm_admin_name', $rmAdminName, 'C');
				$string->AddItem('rm_admin_email', $rmAdminEmail, 'C');
				$string->AddItem('rm_default_tel', $rmDefaultTel, 'C');
				$string->AddItem('rm_sub_tel', $rmSubTel, 'C');
				$string->AddItem('rm_relocation_priceFl', $rmRelocationPriceFl, 'C');
				$string->AddItem('rm_main_item', $rmMainItem, 'C');
				$string->AddItem('rm_before_solution_code', $rmBeforeSolutionCode, 'C');
				$string->AddItem('rm_before_PG', $rmBeforePG, 'C');
				$string->AddItem('rm_after_PG', $rmAfterPG, 'C');
				$string->AddItem('rm_pg_move_type', $rmPgMoveType, 'C');
				$string->AddItem('rm_mall_buy_date', $rmMallBuyDate, 'C');
				$string->AddItem('rm_before_etc_solution_name', $rmBeforeEtcSolutionName, 'C');
				$string->AddItem('rm_before_site_url', $rmBeforeSiteUrl, 'C');
				$string->AddItem('rm_before_ftp_ip', $rmBeforeFtpIp, 'C');
				$string->AddItem('rm_before_ftp_port', $rmBeforeFtpPort, 'C');
				$string->AddItem('rm_before_ftp_id', $rmBeforeFtpId, 'C');
				$string->AddItem('rm_before_ftp_pwd', $rmBeforeFtpPwd, 'C');
				$string->AddItem('rm_before_db_host', $rmBeforeDbHost, 'C');
				$string->AddItem('rm_before_db_port', $rmBeforeDbPort, 'C');
				$string->AddItem('rm_before_db_name', $rmBeforeDbName, 'C');
				$string->AddItem('rm_before_db_id', $rmBeforeDbId, 'C');
				$string->AddItem('rm_before_db_pwd', $rmBeforeDbPwd, 'C');
				$string->AddItem('rm_godo_solution_code', $rmGodoSolutionCode, 'C');
				$string->AddItem('rm_godo_domain', $rmGodoDomain, 'C');
				$string->AddItem('rm_godo_ip', $rmGodoIp, 'C');
				$string->AddItem('rm_godo_ftp_id', $rmGodoFtpId, 'C');
				$string->AddItem('rm_godo_ftp_pwd', $rmGodoFtpPwd, 'C');
				$string->AddItem('rm_godo_db_host', $rmGodoDbHost, 'C');
				$string->AddItem('rm_godo_db_name', $rmGodoDbName, 'C');
				$string->AddItem('rm_godo_db_id', $rmGodoDbId, 'C');
				$string->AddItem('rm_godo_db_pwd', $rmGodoDbPwd, 'C');
				$string->AddItem('rm_move_reason', $rmMoveReason, 'C');
				$string->AddItem('rm_apply_reason', $rmApplyReason, 'C');
				$string->AddItem('rm_relocation_reason', $rmRelocationReason, 'C');
				$string->AddItem('rm_request_info', $rmRequestInfo, 'C');
			}
				$string->AddItem('rm_ip', $saveIP, 'C');
			if ($proc == 'WRITE') {
				$string->AddItem('grm_index', $_SESSION['sess']['grm_idx'], 'C');
				$string->AddItem('rm_writename', $_SESSION['sess']['grm_name'], 'C');
				$string->AddItem('rm_writedate', $saveDate, 'C');

				list($insertColum, $insertValue) = $string->getInsert();
				$processQuery = "Insert Into " . GD_RELOCATION_MALL . " (" . $insertColum . ") Values (" . $insertValue . ")";
			}
			elseif ($proc == 'MODIFY') {
				$string->AddItem('rm_editdate', $saveDate, 'C');

				$updateValue = $string->GetUpdate();
				$processQuery = "Update " . GD_RELOCATION_MALL . " Set ".$updateValue." Where rm_index = '" . $xUidx . "'";
			}
			else {
				$string->AddItem('rm_editdate', $saveDate, 'C');
				$string->AddItem('rm_deleteFl', 'y', 'C');
				$updateValue = $string->GetUpdate();
				$processQuery = "Update " . GD_RELOCATION_MALL . " Set ".$updateValue." Where rm_index = '" . $xUidx . "'";
			}
		
		$db->query($processQuery) or die(setXMLValueURL('false', '저장에 실패하였습니다.', $falseUrl));

		if($proc == 'DELETE'){
			$trueUrl = './' . $returnPage . '.php?' . $getStr;
			setXMLValueURL('true', '정상적으로 삭제 하였습니다.', $trueUrl);
		} else {
			if ($proc == 'WRITE') { 
				$xUidx = mysql_insert_id();	
			}
			$getStr = getStr($getStr,'xUidx', $xUidx);
			$trueUrl = './_view.php?' . $getStr;
			setXMLValueURL('true', '정상적으로 저장 하였습니다.', $trueUrl);
		}
	}
	
?>