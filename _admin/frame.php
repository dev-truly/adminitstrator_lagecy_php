<?php
	include ('../_inc/define.php');
	include ('../_inc/lib.func.php');
	$db = admintool_class_load('db');
	
	session_start();
	if(!$_SESSION['sess']['ruIndex']){
		header("Location: ./_login/_login.php");
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko" lang="ko">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="Description" content="" />
	<meta name="Keywords" content="" />
	<title>Godo Relocation Admin</title>
	<link rel="stylesheet" type="text/css" href="/_admin/_css/main.css" />
	<script language="javascript" src="/_admin/_js/tab.js"></script>
	<script language="javascript" src="/_js/jquery-1.6.4.min.js"></script>
	<script language="javascript" src="/_admin/_js/frame.js"></script>
	<script language="javascript" src="/_admin/_wait/waiting.js"></script>
	<script language="javascript" src="/_admin/_wait/setwait.js"></script>
	<SCRIPT LANGUAGE="JavaScript">
	$(document).ready(function(){
		$(window).resize(function(){
			height = $('.iframe-box').css('height');
			height_c = height.split("px");
				left_resize(height_c[0]);
		});
	});
	

	function Right(str,n){
		if (n <= 0){
		   return "";

		}else if (n > String(str).length){
		   return str;

		}else{
		   var iLen = String(str).length;
		   return String(str).substring(iLen, iLen - n);
		}
	} 

	function left_resize(hSize){
		hSize = Number(hSize)+200;
		hSize = String(hSize)+"px";
		 document.getElementById('sidebar').style.height = hSize;
	}

	function ViewMenu(fthis,id)
	{
		if(document.getElementById('sidebar').style.display=='' )
		{
			document.getElementById('sidebar').style.display='none';
			document.getElementById('tabAreaLayer').style.marginLeft='0';
			document.getElementById('sidebarViewLayer').style.display='block';
		}
		else
		{
			document.getElementById('sidebar').style.display='';
			document.getElementById('tabAreaLayer').style.marginLeft='200px';
			document.getElementById('sidebarViewLayer').style.display='none';
		}
	}
		var Tabmenu = new _TabMenuMainClass("Tabmenu");
		Tabmenu.m_WaitObj = new WaitingDialog(WaitSetobject,"TempWinOpen");
	</SCRIPT>

</head>
<body>

<div id="wrap" ><!--  -->	
	<div id="header">
		<h1 class="h-h1">Godo Soft <div style="margin:3px 0 0 30px;">Relocation Admin</div><!-- <img src="../_admin/_images/common/logo.gif" alt="로고 삽입"> --></h1>
		<ul class="navi">
			<li><img src="/_admin/_images/txt/navi1_off.gif" alt="조회" onclick='Tabmenu.FoucsSelect(this);' OnAir="N"   id="btnSelect" style="cursor:pointer"></li>
			<li><img src="/_admin/_images/txt/navi2_off.gif" alt="저장"   onclick='Tabmenu.FoucsSave(this);' OnAir="N"  id="btnSave" style="cursor:pointer"></li>
			<li><img src="/_admin/_images/txt/navi3_off.gif" alt="추가" onclick='Tabmenu.FoucsInsert(this);' OnAir="N"  id="btnInsert" style="cursor:pointer"></li>
			<li><img src="/_admin/_images/txt/navi4_off.gif" alt="삭제" onclick='Tabmenu.FoucsDelete(this);'  OnAir="N" id="btnDelete" style="cursor:pointer"></li>
			<li><img src="/_admin/_images/txt/navi5_off.gif"  alt="출력" onclick='Tabmenu.FoucsPrint(this);' OnAir="N" id="btnPrint" style="cursor:pointer"></li>
			<li class="navi-last"><img src="/_admin/_images/txt/navi6_off.gif" alt="엑셀파일" onclick='Tabmenu.FoucsDownLoad(this);' OnAir="N" id="btnDownload" style="cursor:pointer"></li>
		</ul>
		<div class="login">
			<p class="btn-center">
				<span class="btn_type1_left"></span>
				<span class="btn_type1_con"><span class="btn_type1_txt"><a href="/_admin/_login/logout.php">로그아웃</a></span></span>
			</p>
		</div>
	</div> <!-- header 끝나는 div -->
	<div id="container" >
		<div class="left-menu" id="sidebar">
			<ul>
				<li class="l-navi"  style="margin-left:-14px;">
					<ul class="l-navi-ul">
						<p class="hide-icon" style="_padding-top:2px;"><a href="javascript:ViewMenu()"><img src="/_admin/_images/icon/hide.gif" alt="메뉴 숨기기"></a></p>
						<?php 
							include (HOME_ROOT . '/_admin/_include/left.php');
						?>
					</ul>
				</li>
			</ul>
		</div>
	<div class="content" id="tabAreaLayer" >
		<div class="tab-title-box">
			<p id="sidebarViewLayer" class="show-icon" style="display:none;"><a href="javascript:ViewMenu()"><img src="/_admin/_images/icon/show.gif" alt="메뉴 보이기"></a></p>
			<ul class="menu-tab" id="tabMenuArea" style="">
			</ul>
			<div class="tab-btn">
				<span><a onclick="Tabmenu.selView(this)" style="cursor:pointer"><img src="/_admin/_images/icon/check.gif"></a></span>
				<span><a href="javascript:Tabmenu.delAll();"><img src="/_admin/_images/icon/close.gif"></a></span>
			</div>
		</div>
		<!-- content -->
		<div class="iframe-box" id="iframe-box">
			<div id="MainContent">

			</div>
		</div>
		<!-- content -->
	</div>
	</div>


	<div id="footer" class="minWidth" style="height:35px;" >
		<p>Copyright ⓒ 2015 Godo Soft Relocation Admin. All rights Reserved</p>
	</div>
</div>
</body>
</html>

<SCRIPT LANGUAGE="JavaScript">
<!--
	var dmenu =  document.getElementById("defaultmenu");
	if(dmenu)
	{
		if(typeof dmenu.length == "number")
		{
		for(var dmanui = 0 ; dmanui< dmenu.length;dmenui++)
			dmenu[dmanui].click();
		}
		else
			dmenu.click();
	}

	
//-->
</SCRIPT>
