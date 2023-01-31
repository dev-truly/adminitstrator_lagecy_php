<?
include_once "../../_inc/define.php";
include_once "../../_inc/lib.func.php";

$db = class_load('db');
ini_set('session.gc_maxlifetime', 36000);
session_start();
$session = class_load('session');

function confirmmsg($msg,$result){
	echo	'<script type="text/javascript">
				if(confirm("'.$msg.'")){;
				'.$result.'
			}
			</script>';
}

$callback = $_REQUEST["callback"];
$msg = '';
$result = true;
$result = getParameterCheck($_POST['ru_id'],'01');
$mmId = $_POST['ru_id'];

if($result){
	$result = getParameterCheck($_POST['ru_password'],'01');
	$mmPassword = $_POST['ru_password'];
}

if(!$result){
	$msg = '입력한 정보가 정상적으로 전달 되지 않았습니다.';
} else {
	$result = $session->login($mmId, $mmPassword);
	switch ($result){
		case 'NOT_VALID' :
			$msg = '아이디 및 비밀번호가 형식에 맞지 않는 형태 입니다.';
			$result = false;
			break;
		case 'NOT_ACCESS' :
			$msg = '입력하신 계정은 권한이 없습니다.';
			$result = false;
			break;
		case 'NOT_FOUND' :
			$msg = '아이디 혹은 비밀번호가 맞지 않습니다.';
			$result = false;
			break;
		case 'IPACCESEERROR' :
			$msg = '로그인 허용 아이피가 아닙니다.';
			$result = false;
			break;
		default:
			$msg = '정상적으로 로그인이 되었습니다.';
			$result = true;
			break;
	}
}


if($url == ''){
	$url = './_login.php';
}

$jsons = '"result":"' . $result . '","msg":"' . $msg . '","url":"' . $url . '"';
echo $callback.'({'.$jsons.'})';

?>
