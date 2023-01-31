<?php
include("../_inc/lib.func.php");
include("../_inc/define.php");

$db = admintool_class_load('db');
session_start();

if (!$_SESSION['sess']['mmIdx']){
	//alertMove('회원 로그인 후 이용이 가능합니다.','/relocation_admin/_Login/_login.php');
	///header("Location: /relocation_admin/_Login/_login.php");
}

$xUidx = $_GET['xUidx'];

$fileRow = $db->fetch("Select m_fileupload From " . GD_MANUAL . " Where m_index = '".$xUidx."'");

$downFileName				= $fileRow['m_fileupload'];
$downFileOriginName			= iconv('UTF-8', 'EUC-KR', urldecode($fileRow['m_fileupload']));

ob_start();
// 파일이 있는 디렉토리
$downfiledir = "../manual_upload/"; 

// 파일 존재 유/무 체크
if (file_exists($downfiledir . $patchFile)) {
	header("Content-Type: application/octet-stream");
	Header("Content-Disposition: attachment;; filename=$downFileOriginName");
	header("Content-Transfer-Encoding: binary"); 
	Header("Content-Length: " . (string)(filesize($downfiledir . $downFileName))); 
	Header("Cache-Control: cache, must-revalidate"); 
	header("Pragma: no-cache"); 
	header("Expires: 0");
	$fp = fopen($downfiledir . $downFileName ,"rb"); //rb 읽기전용 바이러니 타입
	while (!feof($fp)) { 
		echo fread($fp, 100*1024); //echo는 전송을 뜻함.
	}
	fclose ($fp);
	flush(); //출력 버퍼비우기 함수..
}
else {
	echo "<script>alert('존재하지 않는 파일입니다.');history.back();</script>";
}

?>
