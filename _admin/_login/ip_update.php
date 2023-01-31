<?
include("../_inc/lib.func.php");
$db = class_load('db');

session_start();
$adminsession = class_load('adminsession');

$result = true;
$result = getParameterCheck($_POST['grm_id'],'02');
$grm_id = $_POST['grm_id'];

if($result){
	$result = getParameterCheck($_POST['grm_pwd'],'02');
	$grm_pwd = $_POST['grm_pwd'];
}

if($result){
	$mem_select = $db->_select("select grm_idx, grm_ip from gd_relocation_member where grm_id = '$grm_id' and grm_pwd = password('$grm_pwd')");
	if($mem_select[0]['grm_idx']) {
					
		if($mem_select[0]['grm_ip'] == '000.000.000.000'){
			$db->query("update gd_relocation_member set grm_ip='".$_SERVER['REMOTE_ADDR']."' Where grm_id = '$grm_id'");

			$result = $adminsession->login($grm_id,$grm_pwd);
			//exit;
			switch ($result){
				case 'NOT_VALID' :
					msg('아이디 및 비밀번호가 형식에 맞지 않는 형태 입니다.');
					$result = false;
					break;
				case 'NOT_FOUND' :
					msg('입력하신 아이디 및 비밀번호가 틀립니다.');
					$result = false;
					break;
				case 'NOT_ACCESS' :
					msg('입력하신 계정은 권한이 없습니다.');
					$result = false;
					break;
			}
			AlertMove('정상적으로 IP가 등록 되었습니다.\n 다음에 수정을 원하시면 페이지 관리자에게 문의하여 주시기 바랍니다.','/relocation_admin/');
		} else {
			$adminsession->logout();
			AlertMove('이미 IP가 등록 되어 있는 담당자 입니다.\n 다시 로그인을 하여 주시기 바랍니다.','');
		}
	}
}
?>
<script type="text/javascript">
location.href="./_login.php";
</script>