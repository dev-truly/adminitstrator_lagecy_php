<?php
header("Content-type: text/html; charset=euc-kr"); 

$originUrl		= $_REQUEST['originUrl'];
$savePath		= './copyLog/' . str_replace('.', '_', $originUrl) . '/';

$callback	= $_REQUEST["callback"];

$startLimit		= $_REQUEST['startLimit'];	
$originImage	= $_REQUEST['originImage'];	
$whFlag			= $_REQUEST['whFlag'];	
$newSize		= $_REQUEST['newSize'];	
$changeImage	= $_REQUEST['changeImage'];	

$goodsno	= $_REQUEST['goodsno'];	
$type		= $_REQUEST['type'];
$typeName	= $_REQUEST['typeName'];
$file		= $_REQUEST['file'];
$width		= $_REQUEST['width'];
$height		= $_REQUEST['height'];

for ($i = strlen($startLimit); $i < 8; $i++) {
	$startLimit = '0' . $startLimit;
}

$logFileName	= '_' . $startLimit . '_' . $originImage . '_' . $whFlag . '_' . $newSize . '_' . $changeImage . '_log.csv';

$jsons = '';
$result				= true;

if (!is_dir($savePath)) {
	if (!mkdir($savePath)) {
		$result = 0;
	}
	chmod($savePath, 0707 );
}

if ($result) {
	$logtext = '';
	$logtext .= '"' . time() . '"' . chr(10);
	for ($i = 0; $i < count($goodsno); $i++) {
		$logtext .= '"' . $type[$i] . '"	"' . $goodsno[$i] . '"	"' . $typeName[$i] . '"	"' . $file[$i] . '"	"' . $width[$i] . '"	"' . $height[$i] . '"' . chr(10);
	}
	
	if (file_exists($savePath . $logFileName)) {
		unlink($savePath . $logFileName);
	}

	$logFile = fopen($savePath . $logFileName, "a");  
	fwrite($logFile, $logtext);  
	fclose($logFile);
}

$jsons .= '"result":' . $result;

echo $callback . '({'.$jsons.'})';

/** 
* Date = 개발 작업일(2014.07.23)
* ETC = 썸네일 진행 로그 저장
* Developer = 한영민
*/
?>