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
					msg('���̵� �� ��й�ȣ�� ���Ŀ� ���� �ʴ� ���� �Դϴ�.');
					$result = false;
					break;
				case 'NOT_FOUND' :
					msg('�Է��Ͻ� ���̵� �� ��й�ȣ�� Ʋ���ϴ�.');
					$result = false;
					break;
				case 'NOT_ACCESS' :
					msg('�Է��Ͻ� ������ ������ �����ϴ�.');
					$result = false;
					break;
			}
			AlertMove('���������� IP�� ��� �Ǿ����ϴ�.\n ������ ������ ���Ͻø� ������ �����ڿ��� �����Ͽ� �ֽñ� �ٶ��ϴ�.','/relocation_admin/');
		} else {
			$adminsession->logout();
			AlertMove('�̹� IP�� ��� �Ǿ� �ִ� ����� �Դϴ�.\n �ٽ� �α����� �Ͽ� �ֽñ� �ٶ��ϴ�.','');
		}
	}
}
?>
<script type="text/javascript">
location.href="./_login.php";
</script>