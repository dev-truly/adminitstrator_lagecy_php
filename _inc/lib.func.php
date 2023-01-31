<?

/*
* 관리자 툴 클래스 호출
*/
function admintool_class_load ($classname) {
	static $class_arr=array();
	include_once ('../_inc/'.$classname.'.class.php');
	$class_arr[$classname]=new $classname();
	return $class_arr[$classname];
}

/*
* 일반 페이지 클래스 호출
*/
function class_load ($classname) {
	static $class_arr=array();
	include_once ('../../_inc/'.$classname.'.class.php');
	$class_arr[$classname]=new $classname();
	return $class_arr[$classname];
}

/*
* POST 값 trim 처리 후 리턴 
* 다수(배열) 형태의 값이 넘어올경우 parameterType = array
*/
function trimPostRequest ($parameterName) {
	$arrayOutParameter = array(); // 리턴 배열 변수

	if (is_array($_POST[$parameterName])) {
		foreach ($_POST[$parameterName] as $parameterValue) {
			$arrayOutParameter[] = trim($parameterValue);
		}
		return $arrayOutParameter;
	}
	else {
		return trim($_POST[$parameterName]);
	}
}

/*
* GET 값 trim 처리 후 리턴
* 다수(배열) 형태의 값이 넘어올경우 parameterType = array
*/
function trimGetRequest ($parameterName) {
	$arrayOutParameter = array(); // 리턴 배열 변수
	if (is_array($_GET[$parameterName])) {
		foreach ($_GET[$parameterName] as $parameterValue) {
			$arrayOutParameter[] = trim($parameterValue);
		}
		return $arrayOutParameter;
	}
	else {
		return trim($_GET[$parameterName]);
	}
}

/* ----------------------------------------------------------------------------------------
* - Advice - 값 체크후 값이 없으면 전 페이지 이동 및 페이지 처리 종료
* - Parmeter Advice - ProcType:처리 타입 형태 설정 ex) 01:return true false
*  ----------------------------------------------------------------------------------------
*/
function getParameterCheck ($Param,$ProcType) {
	switch($ProcType){
		case "01":
			if($Param == ''){
				return false;
			} else {
				return true;
			}
			break;
		case "02":
			if ($Param == '') {
				msg('입력된 정보가 없습니다.');
				return false;
			}
			return true;
			break;
	}
}

/* ----------------------------------------------------------------------------------------
* - Advice - get형태의 넘길값을 만들어줌..
*  ----------------------------------------------------------------------------------------
*/
function getStr ($param,$strName,$strValue) {
	if ($param == '' || !$param) {
		$param = '';
	} else {
		$param = $param."&";
	}
	return $param.$strName."=".$strValue;
}

/* ----------------------------------------------------------------------------------------
* - Advice - 배열 값을 get형태의 넘길값을 만들어줌..
*  ----------------------------------------------------------------------------------------
*/
function getArrayStr ($getStr, $parameterName, $arrayParameter) {
	if (is_array($arrayParameter)) {
		foreach ($arrayParameter as $aCodeSearchValue) {
			$getStr = getStr($getStr, $parameterName ,$aCodeSearchValue);
		}
	}
	return $getStr;
}

/* ----------------------------------------------------------------------------------------
* - Advice - get형태의 데이터를 input=hidden으로 바꾸어 생성
*  ----------------------------------------------------------------------------------------
*/
function getStrFromReqValHid ($param) {
	$ParseString = explode("&",$param);
	$htmstr='';
	for($i=0;$i < count($ParseString); $i++){
		$tempArray = explode("=",$ParseString[$i]);
		$htmstr = $htmstr."<input type='hidden' name='".$tempArray[0]."' value='".$tempArray[1]."' >".chr(13);
	}
	echo $htmstr;
}

/* ----------------------------------------------------------------------------------------
* - Advice - 배열 형태 데이터 인서트 컬럼, 인서트 벨류값 생성
*  ----------------------------------------------------------------------------------------
*/
function getInsert ($arrKeyValue) {
		foreach($arrKeyValue as $key => $value) {
			if (!$insertColum){
				$insertColum = $key;
				$insertValue = "'".$value."'";
			} else {
				$insertColum .= ",".$key;
				$insertValue .= ",'".$value."'";
			}
		}
		return array($insertColum,$insertValue);
	}

/* ----------------------------------------------------------------------------------------
* - Advice - 배열 형태 데이터 업데이트 컬럼 = 벨류 생성
*  ----------------------------------------------------------------------------------------
*/
function getUpdate ($arrKeyValue) {
	foreach($arrKeyValue as $key => $value) {
		if (!$value) continue;

		if (!$updateValue){
			$updateValue = $key." = '".$value."'";
		} else {
			$updateValue .= ",".$key." = '".$value."'";
		}
	}
	return $updateValue;
}

/* ----------------------------------------------------------------------------------------
* - Advice - alert창 후 페이지 이동
*  ----------------------------------------------------------------------------------------
*/
function alertMove ($Msg,$Url) {
	$Script = "alert('".$Msg."');";
	if ($Url){
		$Script .= "location.href='".$Url."';";
	} else {
		$Script .= "history.back();";
	}
	echo "<script type='text/javascript'>
			".$Script."
	</script>";
}

### GET/POST변수 자동 병합
function getVars ($except='', $request='')
{
	if ($except) $exc = explode(",",$except);
	if ( is_array( $request ) == false ) $request = $_REQUEST;
	foreach ($request as $k=>$v){
		if (isset($_COOKIE[$k])) continue; # 쿠키 제외(..sunny)
		if (!@in_array($k,$exc) && $v!=''){
			if (!is_array($v)) $ret[] = "$k=".urlencode(stripslashes($v));
			else {
				$tmp = getVarsSub($k,$v);
				if ($tmp) $ret[] = $tmp;
			}
		}
	}
	if ($ret) return implode("&",$ret);
}

function getVarsSub ($key,$value)
{
	foreach ($value as $k2=>$v2){
		if ($v2!='') $ret2[] = $key."[".$k2."]=".urlencode(stripslashes($v2));
	}
	if ($ret2) return implode("&",$ret2);
}

### 시간 측정
function get_microtime ($old,$new)
{
	$old = explode(" ", $old);
	$new = explode(" ", $new);
	$time['msec'] = $new[0] - $old[0];
	$time['sec']  = $new[1] - $old[1];
	if($time['msec'] < 0) {
		$time['msec'] = 1.0 + $time['msec'];
		$time['sec']--;
	}
	$ret = $time['sec'] + $time['msec'];
	return $ret;
}

function newIcon ($date) {
	$today=strftime("%Y-%m-%d"); // 뉴 이미지 처리
	$date=substr($date,0,-9); // 날짜 처리
	$sub_date = (strtotime("$today") - strtotime("$date"))/3600/24;

	if ($sub_date < 8)
	{
		return 1;
	}
	else{
		return 0;
	}
}

// 파일 용량표시
function byteFormat ($fs)
{
	// Gb
	if ($fs > 999999999)
	{
		$filesize = round($fs/1024/1024/1024, 2);
		$unit = 'gb';
	}
	// Mb
	elseif ($fs > 999999)
	{
		$filesize = round($fs/1024/1024,2);
		$unit = 'mb';
	}
	// Kb
	elseif ($fs > 999)
	{
		$filesize = round($fs/1024,2);
		$unit = 'kb';
	}

	return number_format($filesize, 1).$unit;
}

### 문자열 자르기 함수
function strcut($str,$len)
{
	if (strlen($str) > $len){
		$len = $len-2;
		for ($pos=$len;$pos>0 && ord($str[$pos-1])>=127;$pos--);
		if (($len-$pos)%2 == 0) $str = substr($str, 0, $len) . "..";
		else $str = substr($str, 0, $len+1) . "..";
	}
	return $str;
}

function array_value_cheking ($ar_fields,$ar_data) {
	$ar_result = array();
	foreach($ar_data as $field_name=>$value)
	{
		$ar_attr = $ar_fields[$field_name];

		if (strlen($value)==0 && $ar_attr['require']!=true) {
			continue;
		}

		if (strlen($value)==0 && $ar_attr['require']==true) {
			$ar_result[$field_name][] = 'require';
			continue;
		}

		switch($ar_attr['type'])
		{
			case 'int':
				if(!ctype_digit((string)$value)) $ar_result[$field_name][] = 'type';
				break;
			case 'float':
				if(!preg_match('/^-?[0-9]+(\.[0-9]+)?$/',$value)) $ar_result[$field_name][] = 'type';
				break;
			case 'digit':
				if(!ctype_digit((string)$value)) $ar_result[$field_name][] = 'type';
				break;
			case 'alnum':
				if(!ctype_alnum($value)) $ar_result[$field_name][] = 'type';
				break;
		}

		if ($ar_attr['max_byte'] && $ar_attr['max_byte']<strlen($value))
		{
			$ar_result[$field_name][] = 'max_byte';
		}
		if ($ar_attr['min_byte'] && $ar_attr['min_byte']<strlen($value))
		{
			$ar_result[$field_name][] = 'min_byte';
		}

		if ($ar_attr['max_length'] && $ar_attr['max_length']<mb_strlen($value,'EUC-KR'))
		{
			$ar_result[$field_name][] = 'max_length';
		}
		if ($ar_attr['min_length'] && $ar_attr['min_length']>mb_strlen($value,'EUC-KR'))
		{
			$ar_result[$field_name][] = 'min_length';
		}
		if ($ar_attr['pattern'] && !preg_match($ar_attr['pattern'],$value))
		{
			$ar_result[$field_name][] = 'pattern';
		}

		if ($ar_attr['array'] && !in_array($value,$ar_attr['array']))
		{
			$ar_result[$field_name][] = 'array';
		}

		if ($ar_attr['callback']) {
			if (!call_user_func($ar_attr['callback'],$value)) {
				$ar_result[$field_name][] = 'callback';
			}
		}
	}
	return $ar_result;
}

function go ($url) {
	header("Location: ".$url);
}

function setXMLValueURL($resultType,$resultValue,$resultURL) {
	header("Content-type: text/xml; charset=utf-8");
	echo '<?xml version="1.0" encoding="UTF-8"?>
		<xml>
			<row value="' . $resultType . '" resultmsg="' . $resultValue . '"><![CDATA[' . $resultURL . ']]></row>
		</xml>';
}

/* ----------------------------------------------------------------------------------------
* - Advice - alert창 후 부모 페이지 이동
*  ----------------------------------------------------------------------------------------
*/
function setAlertValueURL ($Msg, $Url) {
	header("Content-type: text/html; charset=utf-8");
	$Script = "alert('".$Msg."');";
	if ($Url){
		if (ereg('location', $Url)) {
			$Script .= $Url;
		} 
		else {
			$Script .= "parent.location.href='".$Url."';";
		}
	} 
	else {
		$Script .= "history.back();";
	}
	echo "<script type='text/javascript' >
			".$Script."
	</script>";
}

/* ----------------------------------------------------------------------------------------
* - Advice - 데이터 중복 조회
*  ----------------------------------------------------------------------------------------
*/
function dataOverlapCheck ($tableName, $fieldName, $data) {
	global $db;
	
	$cntResult = $db->query("Select " . $fieldName . " From " . $tableName . " Where " . $fieldName . " = '" . $data . "'");
	$cnt = $db->count_($cntResult);

	if ($cnt) {
		return false;
	}
	else {
		return true;
	}
}

/* ----------------------------------------------------------------------------------------
* - Advice - 작업 스케쥴 표시 함수
*  ----------------------------------------------------------------------------------------
*/

function setSchedule ($year, $month, $lastDate, $arrayScheduleData, $companyFlag = false) {
	global $db;
	if ($companyFlag) {
		global $dataRangeCode;
	}

	$monthFirstWeek		= getDate(mktime(0, 0, 0, $month, 1, $year)); //해당 월 1일의 요일

	$monthFirstWeek		= $monthFirstWeek['wday'] + 1;
	$monthLastWeek		= getDate(mktime(0, 0, 0, $month, $lastDate, $year));//해당 월의 마지막 날의 요일
	$monthLastWeek		= $monthLastWeek['wday'];

	$totalWeek			= ceil(($monthFirstWeek + $lastDate - 1) / 7);  // 총 주의 수
	$totalArea			= $totalWeek * 7;  //표시 영역 갯수
	
	/*	----------------------------------------
	*	담당자 검색
	*	----------------------------------------
	*/
	$arrayRelocationUserData = array();
	$selectQuery = "Select ru_index, ru_name, ru_color From " . GD_RELOCATION_USER;
	$relocationUserResult = $db->query($selectQuery);
	while ($relocationUserRow = $db->fetch($relocationUserResult, 1)) {
		$arrayRelocationUserData[$relocationUserRow['ru_index']] =  $relocationUserRow;
	}

	$dayCnt = 1;
	for ($areaCnt = 1; $areaCnt <= $totalArea; $areaCnt++) {
		$weekClass	= '';		// 요일 적용 클래스
		$startWeek	= '';		// 주 시작일
		$endWeek	= '';		// 주 종료일
		if ($areaCnt % 7 == 1) {
			$weekClass = 'sunday';
			$startWeek = '<tr>';
		}
		else if ($areaCnt % 7 == 0) {
			$weekClass = 'saturday';
			$lastWeek = '</tr>';
		}
		
		$todayclass = '';
	
		if (date('Ymd',strtotime($year.'-'.$month.'-'.$dayCnt)) === date('Ymd') && ($areaCnt >= $monthFirstWeek && $areaCnt <= $lastDate + $monthFirstWeek - 1)) {
			$todayclass = 'today';
		}
?>		
		<?=$startWeek?>
		<td class="<?=$weekClass?> <?=$todayclass?>">
			<?php
				if ($areaCnt >= $monthFirstWeek && $areaCnt <= $lastDate + $monthFirstWeek - 1) {
					echo '<span class="schedule_day">' . $dayCnt . '</span>';

					if (!empty($arrayScheduleData[strtotime($year . '-' . $month . '-' . $dayCnt)])) {
						foreach ($arrayScheduleData[strtotime($year . '-' . $month . '-' . $dayCnt)] as $grmIndex => $scheduleMember ) {

							echo '<div class="grm_area" style="background-color:' . $arrayRelocationUserData[$grmIndex]["ru_color"] . '"><h1>' . $arrayRelocationUserData[$grmIndex]['ru_name'] . '</h1>';
							foreach ($scheduleMember as $scheduleMall) {
								echo '<div style="cursor:pointer;" onclick="javascript:LoadPageLink(' . $scheduleMall[0]['dataIndex'] . ')">';
								$dataRangeView = '';
								if ($companyFlag) {
									foreach ($scheduleMall as $scheduleValue) {
										if ($dataRangeView) $dataRangeView .= ', ';
										$dataRangeView .= $dataRangeCode->arrayCodeTypeRow[$scheduleValue['rdl_data_range']];	
									}
								}
								else {
									$dataRangeView .= $scheduleMall[0]['godoId'];
								}
								echo  $dataRangeView;
								echo '</div>';
								
							}
							echo '</div>';
						}
					}
					$dayCnt++;
				}
				else {
					echo '&nbsp;';			
				}
			?>
		</td>
		<?=$endWeek?>
<?php
	}
}

function getPromotinInfo($pIndex = 0) {
	global $db;

	$arrayPromotion = array();
	$promotionQuery = "Select p_index, p_name From gd_promotion where p_delete_flag = 'n' and (p_startdate < date_format(now(), '%Y-%m-%d') and p_enddate > date_format(now(), '%Y-%m-%d') or p_index = '" . $pIndex . "')";

	$promotionResult = $db->query($promotionQuery);
	while ($promotionRow = $db->fetch($promotionResult, 1)) {
		$arrayPromotion[$promotionRow['p_index']] = $promotionRow['p_name'];
	}

	return $arrayPromotion;
}


/*
### 회원로그인 로그 남기기
function member_log( $mm_id ){

	$log_msg = "";
	$log_msg .= date('Y-m-d H:i:s') . "\t";
	$log_msg .= $_SERVER['REMOTE_ADDR'] . "\t";
	$log_msg .= $mm_id . "\n";

	error_log($log_msg, 3, $tmp = dirname(__FILE__) . "../log/login_" . date('Ym') . ".log");
	@chmod( $tmp, 0707 );
}
*/

?>