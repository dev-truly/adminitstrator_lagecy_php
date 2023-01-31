<?php
	
	define('HOME_ROOT', $_SERVER['DOCUMENT_ROOT']);			// 최상위 루트 
	
	/* ------------------------------------------------
	*	- 데이터 베이스 테이블 매칭
	*  ------------------------------------------------
	*/
	define('GD_MENU', 'gd_menu');									// 메뉴 관리 테이블
	define('GD_CODE_TYPE', 'gd_code_type');							// 코드 분류 관리 테이블
	define('GD_CODE', 'gd_code');									// 코드 관리 테이블
	define('GD_DIVISION', 'gd_division');							// 부서관리 테이블
	define('GD_POSITION', 'gd_position');							// 직급관리 테이블
	define('GD_AUTH', 'gd_auth');									// 권한관리 테이블
	define('GD_RELOCATION_USER', 'gd_relocation_user');				// 직원관리 테이블
	define('GD_SOLUTION', 'gd_solution');							// 솔루션관리 테이블
	define('GD_RELOCATION_DATA_LOG', 'gd_relocation_data_log');		// 타사이전 데이터 로그 테이블
	define('GD_RELOCATION_MALL', 'gd_relocation_mall');				// 타사이전 업체 테이블
	define('GD_PROMOTION_COUPON', 'gd_promotion_coupon');			// 프로모션 쿠폰 관리 테이블
	define('GD_RELOCATION_RESPONSE_LOG', 'gd_relocation_response_log');	// 타사이전 응대 내역 관리 테이블
	define('GD_RELOCATION_DATA_LOG', 'gd_relocation_data_log');		// 타사이전 이전 내역 관리 테이블
	define('GD_RELOCATION_SHARE', 'gd_relocation_share');			// 이전 업무 공유 페이지
	//define('GD_RELOCATION_MEMBER', 'gd_relocation_member');			// 맴버 테이블
	define('GD_RELOCATION_CODE_TYPE', 'gd_relocation_code_type');				// 맴버 테이블

?>