<?
	header("Content-type: text/html; charset=utf-8");
	ini_set('session.gc_maxlifetime', 36000);

	include ('../../_inc/define.php');
	include ('../../_inc/lib.func.php');
	
	$db = class_load('db');
	$isUrl = $_SERVER['PHP_SELF'];
	$menuQuery = "Select m_index, m_subject, m_explain, m_login, m_button, m_code From " . GD_MENU . " Where m_url like '%" . $isUrl . "%' And m_delete_flag = 'n'";
	$menuRow = $db->fetch($db->query($menuQuery));
	
	session_start();
	if ($menuRow['m_login'] == 'y') {
		if(!$_SESSION['sess']['ruIndex']){ // 로그인 체크
			echo "<script type='text/javascript'>parent.alert('로그인이 되어있지 않습니다.');parent.location.href = '../../_admin/_login/_login.php';</script>";
		}
		else {
			$session = class_load('session');
			if (!$session->permit_ip_check()) {
				echo "<script type='text/javascript'>parent.alert('로그인 허용 아이피가 아닙니다.');parent.location.reload();</script>";
			}
		}
	}

	$menuSubject = $menuRow['m_subject'];
	$menuExplain = $menuRow['m_explain'];
	$buttonAuth = str_replace(',', '', $menuRow['m_button']);

	if (!ereg($menuRow['m_code'], $_SESSION['sess']['aMenuCode']) && (!$menuRow['m_code'] == 'member' && !ereg('/_systool/member/', $_SERVER['PHP_SELF']))) {
		echo "<script type='text/javascript'>parent.alert('메뉴 접근 권한이 없습니다.');parent.location.href = '../../_admin/_login/_login.php';</script>";
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko" lang="ko">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="Description" content="" />
	<meta name="Keywords" content="" />
	<link rel="stylesheet" type="text/css" href="/_admin/_css/default.css" />
	<link rel="stylesheet" type="text/css" href="/_admin/_css/frame.css" />
	<link rel="stylesheet" type="text/css" href="/_systool/css/main.css">
	<script language="javascript" src="/_js/jquery-1.6.4.min.js"></script>
	<script language="javascript" src="/_admin/_js/frame.js"></script>
	<script language="javascript" src="/_admin/_js/nvalid.js"></script>
	<script language="javascript" src="/_admin/_js/ntooltips.js"></script>
	<script language="javascript" src="/_admin/_js/ncalendars.js"></script>
	<script language="javascript" src="/_admin/_js/tlayer.js"></script>
	<script language="javascript" src="/_admin/_wait/waiting.js"></script>
	<script language="javascript" src="/_admin/_wait/setwait.js"></script>
<SCRIPT LANGUAGE="JavaScript">
function SetPageMenuAuth()
{
		var  bauth = {
			//m_id_sel : "<%=Lg_M_IDX%>",
			m_auth_sel : "<?=substr($buttonAuth,0,1)?>",
			m_auth_save : "<?=substr($buttonAuth,1,1)?>",
			m_auth_add : "<?=(!ereg($menuRow['m_code'], $_SESSION['sess']['aMenuCode']) && ($menuRow['m_code'] == 'member' && ereg('/_systool/member/', $_SERVER['PHP_SELF']))) ? 'N' : substr($buttonAuth,2,1) ?>",
			m_auth_del : "<?=substr($buttonAuth,3,1)?>",
			m_auth_print : "<?=substr($buttonAuth,4,1)?>",
			m_auth_down : "<?=substr($buttonAuth,5,1)?>",
			m_auth_list : "<?=substr($buttonAuth,6,1)?>"
		};
		parent.Tabmenu.buttonauth(bauth);

}
SetPageMenuAuth();

$(window).ready(function() {
	
	$('#detail_search').toggle(
	function() {
			$(this).attr('src', '../../_admin/_images/icon/height_hide.jpg');

			$('#detail_searchForm').show();
	},
	 function() {
	 $(this).attr('src', '../../_admin/_images/icon/height_show.jpg');
			$('#detail_searchForm').hide();
		
	}).css('cursor', 'pointer');

});
</SCRIPT>
</head>
<body   !style="overflow:auto" >