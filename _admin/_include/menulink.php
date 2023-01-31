<?php
	header("Content-type: text/xml; charset=utf-8");
	
	include ('../../_inc/define.php');
	include ('../../_inc/lib.func.php');
	
	$db = class_load('db');

	/* ------------------------------------------------
	*	- 도움말 - 넘어오는 값
	*  ------------------------------------------------
	*/
	$menuIdx		= $_GET['menuIdx'];				//------------ 메뉴일련번호

	$selectQuery = "Select * From " . GD_MENU . " Where m_index = '" . $menuIdx . "' And m_delete_flag = 'n'";
	$menuResult = $db->query($selectQuery);

	while ($listRow = $db->fetch($menuResult)) {
		$mIndex			= $listRow['m_index'];
		$mSubject		= $listRow['m_subject'];
		$mLogin			= $listRow['m_login'];
		$mLinkType		= $listRow['m_link_type'];
		$mWidth			= $listRow['m_width'];
		$mHeight		= $listRow['m_height'];
		$mUrl			= $listRow['m_url'];

		if ($mUrl) {
			//SendURL = "http://" & httpServerName & "/LoadPage.asp?URLHost=" + M_HTTP + "&MovePage="& server.urlencode(M_URL & "?" & quValue) //보안서버 사용시
			$sendURL = $mUrl . '?' . $_SERVER["QUERY_STRING"]; 
		}
		/* 로그인 체크 삽입
		if not GetLogin() and M_LOGIN = "Y" then '로그인페이지 이동
			call SetgoURL(SendURL)
			response.redirect UPageLoadURL(LoginMenuID)
			Response.End
		end if
		if GetLogin() and M_LOGIN = "N" then '메인페이지 이동
			call SetgoURL(SendURL)
			response.redirect UPageLoadURL(LogoutMenuID)
			Response.End
		end if
		*/
	}
?>

<xml>
<?php
	
	if ($mUrl) {
		echo '<LINKNAME LINK="' . $mIndex . '" VALUENAME="' . $mSubject .'" MENUCODE="' . $mLinkType . '" MENUPORT="80" PWIDTH ="' . $mWidth . '" PHEIGHT ="' . $mHeight . '" ><![CDATA[' . $sendURL . ']]></LINKNAME>';
	}
?>
</xml>
