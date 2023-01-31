<?
	include ('../../_admin/_include/systop.php');
	
	/* ------------------------------------------------
	*	- 도움말 - 메뉴 코드 in절 형태로 변환
	*  ------------------------------------------------
	*/
	$menuCode = class_load('code');
	$menuCode->setCode(explode('|', $_SESSION['sess']['aMenuCode']), 'menu');
	$menuCodeIn = $menuCode->getIn();

	/* ------------------------------------------------
	*	- 도움말 - 검색 절 생성
	*  ------------------------------------------------
	*/
	$dataString = class_load('string');

	$dataString->addItem('m_delete_flag', 'n', 'C');
	$dataString->addItem('m_code', $menuCodeIn, 'IN');

	$selectQuery = "Select * From " . GD_MENU . $dataString->getWhere() . "  Order By m_parent, m_sort asc";
	$menuResult = $db->query($selectQuery);
?>
<link rel="StyleSheet" href="/_admin/_css/wtree.css" type="text/css" />
<script language="javascript" src="/_admin/_js/wtree.js"></script>
<SCRIPT LANGUAGE="JavaScript">
<!--
function _Fsave()
{
	var form = document.frmMain;
	doForm(form);
}
function _Fdelete()
{
	var form = document.frmMain;
	form.proc.value = "DELETE";
	var form = document.frmMain;
	doForm(form);

}
function _Fselect()
{
	LoadLinkStr("60","<%=gStr2%>");
}
function _Finsert()
{
	var f = document.frmMain;
	var obj = iWtree.getSelectedObject();
	if(obj == null)
	{
		obj = {};
		obj.mSubject = "최상위";
		obj.mIndex = "0";
		obj.mCode = "";
	}



	document.getElementById("M_PARENTNAME").innerHTML = obj.mSubject;

	f.proc.value="WRITE";
	f.m_index.value =  "";
	f.m_subject.value =  "";
	f.m_explain.value =  "";
	f.m_url.value =  "";
	f.m_parent.value =  obj.mIndex;
	f.m_code.value =  obj.mCode;
	f.m_sort.value =  "";
	f.m_view_flag.value =  "";
	f.m_use_flag.value =  "";
	f.m_link_type.value =  "";
	f.m_width.value =  "";
	f.m_height.value =  "";
	f.m_login.value =  "";
	for (var i = 0; i < 7 ; i++) {
		eval('f.m_button' + (i+1) + '.checked = false');
	}
}

function foucsMenu()
{

	var obj = iWtree.getSelectedObject();
	if(obj == null)
		return;
	document.getElementById("M_PARENTNAME").innerHTML="";
	var f = document.frmMain;

	f.proc.value = "MODIFY";
	f.m_index.value =  obj.mIndex;
	f.m_code.value =  obj.mCode;
	f.m_subject.value =  obj.mSubject;
	f.m_explain.value = obj.mExplain;
	f.m_url.value =  obj.mUrl;
	f.m_parent.value =  obj.mParent;
	f.m_sort.value =  obj.mSort;
	f.m_view_flag.value =  obj.mViewFlag;
	f.m_use_flag.value =  obj.mUseFlag;
	f.m_link_type.value =  obj.mLinkType;
	f.m_width.value =  obj.mWidth;
	f.m_height.value =  obj.mHeight;
	f.m_login.value =  obj.mLogin;

	var bArray = obj.mButton.split(",");
	var Len = bArray.length;
	for(var i =0 ; i<7;i++)
	{

		if(i < Len)
			eval('f.m_button' + (i+1) + '.checked = ' +   ( (bArray[i] == "Y") ? "true" : "false"))  ;
		else
			eval('f.m_button' + (i+1) + '.checked = false');
	}
}

	var iWtree = new WTree('iWtree');

	iWtree.add(0,-1,'Godo Relocation Admin','javascript:foucsMenu()');
	<? while ($menuRow = $db->fetch($menuResult)) {
			$mIndex			= $menuRow['m_index'];
			$mCode			= $menuRow['m_code'];
			$mSubject		= $menuRow['m_subject'];
			$mExplain		= $menuRow['m_explain'];
			$mUrl			= $menuRow['m_url'];
			$mParent		= $menuRow['m_parent'];
			$mSort			= $menuRow['m_sort'];
			$mViewFlag		= $menuRow['m_view_flag'];
			$mUseFlag		= $menuRow['m_use_flag'];
			$mLinkType		= $menuRow['m_link_type'];
			$mWidth			= $menuRow['m_width'];
			$mHeight		= $menuRow['m_height'];
			$mLogin			= $menuRow['m_login'];
			$mButton		= $menuRow['m_button'];
			
				$viewFlag = '';
			if ($mViewFlag == 'n') {
				$viewFlag = '*';
				$viewKorean = '메뉴 미표시';
			}
			
	?>

	iWtree.add(<?=$mIndex?>,<?=$mParent?>,'<?=$viewFlag . " " ?>' + '<?=$mSubject?>' + '<?="(" . $mIndex . ")"?>','javascript:foucsMenu()','<?=$viewKorean?>','','','',false, {mIndex :"<?=$mIndex?>",mSubject : "<?=$mSubject?>",mUrl :"<?=$mUrl?>",mParent :"<?=$mParent?>",mSort :"<?=$mSort?>",mViewFlag :"<?=$mViewFlag?>",mUseFlag :"<?=$mUseFlag?>",mLinkType :"<?=$mLinkType?>",mWidth :"<?=$mWidth?>",mHeight :"<?=$mHeight?>",mLogin :"<?=$mLogin?>",mButton :"<?=$mButton?>",mCode :"<?=$mCode?>",mExplain :"<?=$mExplain?>"});
	<?php 
		}
	?>

//-->
</SCRIPT>

						<!-- view area -->
						<div class="viewArea" >
							<!-- bbsView -->
							<div class="bbsView">
								<div class="tableView">
									<table class="basic" style="_margin-left:250px;">
									<caption>게시판 요약</caption>
									<colgroup>
										<col width="300px" />
										<col width="2px" />
										<col width="auto" />
									</colgroup>
									<thead>
									<tr class="bg">
										<th>메뉴(<a href="javascript: iWtree.openAll();">open all</a> | <a href="javascript: iWtree.closeAll();">close all</a>)
										</th>
										<th></th>
										<th>정보</th>
									</tr>
									</thead>
									<body>
									<tr  >
										<th scope="row" style="height:500px;">
										<div class="Wtreebody" id="Wtree">
											<script type="text/javascript">
												<!--
												document.write(iWtree);
												//-->
											</script>
										</div>
										</th>
										<td></td>
										<td scope="row" valign="top">
											<table class="basic">
											<form name="frmMain" method="post" action="./menu_proc.php" >
											<input type="hidden" name="proc" >
											<caption>정보 요약</caption>
											<colgroup>
												<col width="15%" />
												<col width="auto" />
											</colgroup>
											<thead>
											<tr class="bg">
												<th scope="row">메뉴번호</th>
												<td scope="row"><input type="text" name="m_index"  style="width:50px;" readonly   value="" IsValidStr="NUM"  NNull="false" LabelStr="메뉴번호"></td>
											</tr>
											<tr class="nobgc">
												<th scope="row">상위메뉴번호</th>
												<td scope="row"><input type="text" name="m_parent"  style="width:50px;"    value="" IsValidStr="NUM"  NNull="true" LabelStr="상위메뉴"><span id="M_PARENTNAME"></span></td>
											</tr>
											<tr class="bgc">
												<th scope="row">메뉴코드</th>
												<td scope="row"><input type="text" name="m_code"  style="width:50px;"    value="" IsValidStr="STR" MinL="2" MaxL="50" NNull="true" LabelStr="메뉴코드"></td>
											</tr>
											<tr class="nobgc">
												<th scope="row">메뉴명</th>
												<td scope="row"><input type="text" name="m_subject"  style="width:300px;"    value="" IsValidStr="STR" MinL="2" MaxL="50" NNull="true" LabelStr="메뉴명"></td>
											</tr>
											<tr class="bgc">
												<th scope="row">메뉴 설명</th>
												<td scope="row"><input type="text" name="m_explain"  style="width:95%;"    value="" IsValidStr="STR" MinL="2" MaxL="50" NNull="false" LabelStr="메뉴 설명"></td>
											</tr>
											<tr class="nobgc">
												<th scope="row">링크</th>
												<td scope="row"><input type="text" name="m_url"  style="width:95%;"    value="" IsValidStr="STR"  NNull="false" LabelStr="링크"></td>
											</tr>
											<tr class="bgc">
												<th scope="row">링크구분</th>
												<td scope="row">
													<select name="m_link_type" IsValidStr="SELECT"  NNull="false" LabelStr="링크구분">
														<option value="">--</option>
														<option value="1">_self (현재페이지)</option>
														<option value="2">_blank (새창)</option>
														<option value="3">Layer (레이어)</option>
														<option value="4">popup (팝업)</option>
														<option value="5">top (최상위,다운로드)</option>
														<option value="6">+tab(관리화면새탭)</option>
													</select>
												</td>
											</tr>
											<tr class="nobgc">
												<th scope="row">팝업사이즈</th>
												<td scope="row">
													Width : <input type="text" name="m_width"  style="width:50px;"    value="" IsValidStr="STR" NNull="false" LabelStr="팝업사이즈 WIDTH">
													Height : <input type="text" name="m_height"  style="width:50px;"    value="" IsValidStr="STR" NNull="false" LabelStr="팝업사이즈 HEIGHT">
												</td>
											</tr>

											<tr class="bgc">
												<th scope="row">정렬순서</th>
												<td scope="row"><input type="text" name="m_sort"  style="width:50px;"    value="" IsValidStr="NUM"  NNull="true" LabelStr="정렬순서"></td>
											</tr>
											<tr class="nobgc">
												<th scope="row">사용유무</th>
												<td scope="row">
													<select name="m_use_flag" IsValidStr="SELECT"  NNull="true" LabelStr="사용유무">
														<option value="">--</option>
														<option value="y">사용</option>
														<option value="n">미사용</option>
													</select>
												</td>
											</tr>
											<tr class="bgc">
												<th scope="row">메뉴표시여부</th>
												<td scope="row">
													<select name="m_view_flag" IsValidStr="SELECT"  NNull="true" LabelStr="메뉴표시여부">
														<option value="">--</option>
														<option value="y">표시</option>
														<option value="n">미표시</option>
													</select>
												</td>
											</tr>
											<tr class="nobgc">
												<th scope="row">로그인구분</th>
												<td scope="row">
													<select name="m_login" IsValidStr="SELECT"  NNull="true" LabelStr="로그인구분">
														<option value="">--</option>
														<option value="y">로그인</option>
														<option value="n">비로그인</option>
													</select>
												</td>
											</tr>
											<tr class="bgb">
												<th scope="row">메뉴사용</th>
												<td scope="row">
														<input type="checkbox" name="m_button1" id="M_BUTTON1" value="Y"><label for="M_BUTTON1">조회</label>
														<input type="checkbox" name="m_button2" id="M_BUTTON2" value="Y"><label for="M_BUTTON2">저장</label>
														<input type="checkbox" name="m_button3" id="M_BUTTON3" value="Y"><label for="M_BUTTON3">추가</label>
														<input type="checkbox" name="m_button4" id="M_BUTTON4" value="Y"><label for="M_BUTTON4">삭제</label>
														<input type="checkbox" name="m_button5" id="M_BUTTON5" value="Y"><label for="M_BUTTON5">출력</label>
														<input type="checkbox" name="m_button6" id="M_BUTTON6" value="Y"><label for="M_BUTTON6">엑셀다운</label>
														<input type="checkbox" name="m_button7" id="M_BUTTON7" value="Y"><label for="M_BUTTON7">명단저장</label>
												</td>
											</tr>
											</form>
											</table>
										</td>
									</tr>
									</body>
									</table>
								</div>
							</div>
							<!-- //bbsView -->
						</div>

			</form>

<?
	include ('../../_admin/_include/sysbottom.php');
?>
