<?php
/*
	session 클래스
*/
define('SHOPROOT',$_SERVER['DOCUMENT_ROOT']);

class session {

	var $ruIndex;
	var $ruName;
	var $ruId;
	var $ruPermitIp;
	var $ruPermitIpCheckFlag;
	var $aCode;
	var $dCode;
	var $aMenuCode;
	var $log = array();
	var $logPath;

	function session() {
		if($_SESSION['sess']['ruIndex']) {
			$this->ruIndex				= $_SESSION['sess']['ruIndex'];
			$this->ruName				= $_SESSION['sess']['ruName'];
			$this->ruId					= $_SESSION['sess']['ruId'];
			$this->ruPermitIp			= $_SESSION['sess']['ruPermitIp'];
			$this->aCode				= $_SESSION['sess']['aCode'];
			$this->ruPermitIpCheckFlag	= $_SESSION['sess']['ruPermitIpCheckFlag'];
			$this->dCode				= $_SESSION['sess']['dCode'];
			$this->aAuthCode			= $_SESSION['sess']['aAuthCode'];
			$this->aMenuCode			= $_SESSION['sess']['aMenuCode'];
		}
		else {
			$this->ruIndex			= false;
			$this->ruName			= '';
			$this->ruId				= '';
			$this->ruPermitIp		= '';
			$this->ruPermitIpCheckFlag		= '';
			$this->aCode			= '';
			$this->dCode			= '';
			$this->aAuthCode		= '';
			$this->aMenuCode		= '';
		}
	}

	/*
		로그인

		return 값
		정상적으로 로그인 된 경우 = true
		아이디나 비밀번호가 입력형식에 어긋난 경우 = NOT_VALID
		아이디나 비밀번호 맞지 않는 경우 = NOT_FOUND
		접속 허용 아이피가 아닌 경우 = IPACCESEERROR
		접속이 승인되지 않는 경우 = NOT_ACCESS
	*/
	function login ( $id, $password ) {
		// 입력 형식 체크
		$validationCheck = array(
								'id'=>array( 'require'=>true,
											 'pattern'=>'/^[\xa1-\xfea-zA-Z0-9_-]{4,20}$/' ),
								'password'=>array( 'require'=>true,
												   'pattern'=>'/^[\x21-\x7E]{4,16}$/' ),
							);
		$checkResult = array_value_cheking( $validationCheck, array('id'=>$id, 'password'=>$password ) 
		
		);

		if( count($checkResult)) {
			return 'NOT_VALID';
		}

		// 아이디,비밀번호 조회
		GLOBAL $db;

		$query = $db->_query_print("
			select 
				ru.ru_index,
				ru.ru_name,
				ru.ru_id,
				ru.ru_permit_ip,
				ru.ru_permit_ip_check_flag,
				ru.a_code,
				a.a_auth_code,
				a.d_code,
				a.a_menu_code
			from
				" . GD_RELOCATION_USER . " as ru
				join
				" . GD_AUTH . " as a
				on ru.a_code = a.a_code
			where ru.ru_id = [s] and ru.ru_password = password([s]) and a.a_delete_flag = 'n' and ru.ru_delete_flag = 'n'", $id, $password);
		//echo $query.'<br/>';
		$result = $db->_select($query);
		$result = $result[0];

		if (!$result['ru_index']) { // 일치하는 결과 값이 없는 경우
			return 'NOT_FOUND';
		} 
		else {
			// 세션정보 저장
			$_SESSION['sess'] = array(
				'ruIndex'			=> $result['ru_index'],
				'ruName'			=> $result['ru_name'],
				'ruId'				=> $result['ru_id'],
				'ruPermitIp'		=> $result['ru_permit_ip'],
				'ruPermitIpCheckFlag'	=> $result['ru_permit_ip_check_flag'],
				'aCode'				=> $result['a_code'],
				'dCode'				=> $result['d_code'],
				'aAuthCode'			=> $result['a_auth_code'],
				'aMenuCode'			=> $result['a_menu_code'],
			);

			if (!$this->permit_ip_check()) {
				return 'IPACCESEERROR';
			} else {
				$this->session();
				$this->log_create();
				
				return 'SUCCESS';
			}
		}
	}

	//로그 아웃
	function logout() {
		session_start();
		unset($_SESSION);
		session_destroy();
	}

	private function log_create() {
		GLOBAL $db;

		$logUpdateQuery = "Update " . GD_RELOCATION_USER . " Set 
								ru_login_ip = '" . $_SERVER['REMOTE_ADDR'] . "',
								ru_login_date = '" . date('Y-m-d H:i:s') . "'
							Where ru_index = " . $this->ruIndex;

		$db->query($logUpdateQuery);

		$logYearDir = '../../log/login/'. date('Y');
		$logMonthDir = $logYearDir . '/' . date('m');
		if(!is_dir($logYearDir)){
			mkdir($logYearDir);
			chmod($logYearDir, 0707);
		}

		if(!is_dir($logMonthDir)){
			mkdir($logMonthDir);
			chmod($logMonthDir, 0707);
		}

		$logPath = $logMonthDir . '/'. date('d') . '_' . $this->ruId . '.log';
		
		$log[] = '#####################################';
		$log[] = '[IP] ' . $_SERVER['REMOTE_ADDR'];
		$log[] = '[Date] ' . date('Y-m-d H:i:s');
		$log[] = '[ID] ' . $this->ruId;
		$log[] = '[Name] ' . $this->ruName;
		$log[] = '[DivisionCode] ' . $this->dCode;
		

		$file_write = fopen($logPath,"a+");

		if($file_write){
			$result = true;
			
			fwrite($file_write, implode(chr(13), $log) . chr(13));
			fclose($file_write);
		} else {
			$result = false;
		}
	}

	function permit_ip_check() {
		if ($_SESSION['sess']['ruPermitIpCheckFlag'] == 'y') {
			if ($_SESSION['sess']['ruPermitIp'] == $_SERVER['REMOTE_ADDR']) {
				return true;
			}
			else {
				$this->logout();
				$this->session();
				return false;
			}
		} else {
			return true;
		}
	}
}


?>