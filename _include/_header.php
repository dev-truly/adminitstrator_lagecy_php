<?
	include_once dirname(__FILE__) . "/../_inc/lib.func.php";
	error_reporting(-1);

	$db = class_load('db');

	session_start();
?>
<!DOCTYPE html>
<html>
<head>
<title>���� ������</title>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
<script src="/_js/jquery-1.6.4.min.js"></script>
<script type="text/javascript">
function searchKw() {
	if($("[name=search_key]").val()) {
		document.location.href="/basic/manual.php?kw="+ $("[name=search_key]").val();
	}
	else {
		alert("�˻� Ű���带 �Է��� �ֽñ� �ٶ��ϴ�");
		return;
	}
}
function showSearchArea() {
	if($(".search-detail-area").is(':hidden') == true) {

		$(".search-detail-area").slideDown(30);
	}
	else {
		
		$(".search-detail-area").slideUp(0);
	}
}

</script>
<link rel="styleSheet" href="/_js/css/style.css">

<body>
	<table width=100%>
	<tr>
		<td valign=top>
			<div class="search-area">
				<div id="search-check">
					<input type=checkbox name="search_title" >����
					<input type=checkbox name="search_writer" >�ۼ���
					<input type=checkbox name="search_content" >���ǳ���
					<input type=checkbox name="search_mpp_tilte" >��ġ.ó������
					<input type=checkbox name="search_mpp_content">��ġ.ó������
				</div>
				<div id="search-text">
					<input type="text" name="search_key" placeholder="�˻�� �Է��� �ּ���"/>
				</div>
				<div id="search-box-btn"><img src="../images/btn/bu_search.gif" onclick="javascript:searchKw();" /></div>
			</div>

			<div id="search-btn" onClick="javascript:showSearchArea();">�󼼰˻�</div>
			<div class="search-detail-area">
			<select>
				<option>ī�װ�</option>
			</select>
			<select>
				<option>�ֿ���μ�</option>
			</select>
			<table width=100%>
			<tr>
				<td>�ַ�� ����</td>
				<td>����Ͻ� ���� �ҷ��ͼ� üũ�ڽ���</td>
			</tr>
			</table>
			</div>
		</td>
	</tr>
	</table>