<?
	include_once dirname(__FILE__) . "/../_inc/lib.func.php";
	error_reporting(-1);

	$db = class_load('db');

	session_start();
?>
<!DOCTYPE html>
<html>
<head>
<title>이전 관리자</title>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
<script src="/_js/jquery-1.6.4.min.js"></script>
<script type="text/javascript">
function searchKw() {
	if($("[name=search_key]").val()) {
		document.location.href="/basic/manual.php?kw="+ $("[name=search_key]").val();
	}
	else {
		alert("검색 키워드를 입력해 주시기 바랍니다");
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
					<input type=checkbox name="search_title" >제목
					<input type=checkbox name="search_writer" >작성자
					<input type=checkbox name="search_content" >문의내용
					<input type=checkbox name="search_mpp_tilte" >패치.처리제목
					<input type=checkbox name="search_mpp_content">패치.처리내용
				</div>
				<div id="search-text">
					<input type="text" name="search_key" placeholder="검색어를 입력해 주세요"/>
				</div>
				<div id="search-box-btn"><img src="../images/btn/bu_search.gif" onclick="javascript:searchKw();" /></div>
			</div>

			<div id="search-btn" onClick="javascript:showSearchArea();">상세검색</div>
			<div class="search-detail-area">
			<select>
				<option>카테고리</option>
			</select>
			<select>
				<option>주요사용부서</option>
			</select>
			<table width=100%>
			<tr>
				<td>솔루션 선택</td>
				<td>디비등록시 값을 불러와서 체크박스로</td>
			</tr>
			</table>
			</div>
		</td>
	</tr>
	</table>