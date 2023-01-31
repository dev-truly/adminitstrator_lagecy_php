<?php
	include ('../../_admin/_include/systop.php');

	/* ------------------------------------------------
	*	- 도움말 - 넘어오는 값
	*  ------------------------------------------------
	*/
	$listCnt		= $_GET['listCnt'];				//------------ 출력되는 리스트 수
	$page			= $_GET['page'];				//------------ 현재 페이지

	$selectType		= $_GET['selectType'];			//------------ 검색 조건
	$selectValue	= $_GET['selectValue'];			//------------ 검색값

	if (!empty($_GET['ma_codesearch']))		$maCodeSearch	= implode('|', $_GET['ma_codesearch']);		//------------ 메뉴 검색 조건
	if (!empty($_GET['md_codesearch']))		$mdCodeSearch	= implode('|', $_GET['md_codesearch']);		//------------ 부서 검색 조건

	$sort			= $_GET['sort'];				//------------ 검색값

	$arrayField = array('grm_name', 'grm_id'); // 전체 검색 툴
    
	/* ------------------------------------------------
	*	- 페이지 설정
	*  ------------------------------------------------
	*/
	if(!$listCnt) $listCnt = 10;
	if(!$page) $page = 1;

	$pg = class_load('page');
	$pg->Page($page,$listCnt);

	/* ------------------------------------------------
	*	- 도움말 - get데이터로 넘길값 생성
	*  ------------------------------------------------
	*/
	$getStr ='';
	$getStr = getStr($getStr,'listCnt',$listCnt);
	$getStr = getStr($getStr,'page',$page);
	$getStr = getStr($getStr,'selectType',$selectType);
	$getStr = getStr($getStr,'selectValue',$selectValue);
	$getStr = getStr($getStr,'sort',$sort);

	/* ------------------------------------------------
	*	- 도움말 - 소팅 선택시 넘어갈 GET값
	*  ------------------------------------------------
	*/
	$getStr2 ='';
	$getStr2 = getStr($getStr2,'listCnt',$listCnt);
	$getStr2 = getStr($getStr2,'selectType',$selectType);
	$getStr2 = getStr($getStr2,'selectValue',$selectValue);
	
	// 권한 검색 데이터 존재시 Get 데이터로 넘길값 생성
	if (!empty($_GET['ma_codesearch'])) {
		foreach ($_GET['ma_codesearch'] as $maCodeSearchValue) {
			$getStr = getStr($getStr,'ma_codesearch[]',$maCodeSearchValue);
		}
	}

	// 부서 권한 검색 데이터 존재시 Get 데이터로 넘길값 생성
	if (!empty($_GET['md_codesearch'])) {
		foreach ($_GET['md_codesearch'] as $mdCodeSearchValue) {
			$getStr = getStr($getStr,'md_codesearch[]',$mdCodeSearchValue);
		}
	}

	/* ------------------------------------------------
	*	- 도움말 - 검색 절 생성
	*  ------------------------------------------------
	*/
	$authCode = class_load('code');
	$authCode->setCode($maCodeSearch, 'auth');
	$divisionCode = class_load('code');
	$divisionCode->setCode($mdCodeSearch, 'division');

	$selectWhere = array();
	
	if (!empty($_GET['ma_codesearch'])) {
		$selectWhere[] = 'ra_code in (' . $authCode->getIn() . ')'; 
	}

	if (!empty($_GET['md_codesearch'])) {
		$selectWhere[] = 'rd_code in (' . $divisionCode->getIn() . ')'; 
	}
	
	if (empty($likeWhere) === false) $selectWhere[] = '(' . implode(' And ', $likeWhere) . ')';

	if($selectType){
		if ($selectType == 'all') {
			$arrayWhere = array();
			foreach ( $arrayField as $stringFieldName ) {
				$arrayWhere[] = $stringFieldName . " LIKE '%" . $_GET['selectValue'] . "%'";
			} // end foreach
			$selectWhere[] = " (" . implode(" OR ", $arrayWhere) . ") ";
		}
		else {
			$selectWhere[] = $selectType." like '%" . $selectValue . "%'";
		}
	}
	$selectWhere[] = "grm_deleteFl = 'n'";
	
	if (!$sort) $sort = " grm_index desc";
	//-------------------------------------------
	//- Advice - 리스트 검색
	//-------------------------------------------
	$pg ->field = "grm_index, rd_code, grm_name, grm_id, grm_writedate";
	$pg->setQuery($db_table= " " . GD_RELOCATION_MEMBER . " " ,$selectWhere,$sort);
	$pg->exec();

	$resList = $db->query($pg->query);
?>

<SCRIPT LANGUAGE="JavaScript">

function LoadPageLink(idx)
{	
	LoadLinkStr("17","xUidx="+idx+ "&<?=$getStr?>")
}

function _Finsert()
{
	LoadLinkStr("17","<?=$getStr?>");
}

function _Fselect()
{
	f = document.frmMain;
	if(!IsValidList(f))
	{
		return;
	}
	f.submit();
}

$(window).ready(function() {
	$('#detail_search').toggle(
		function() {
			$(this).attr('src', '../../_admin/_images/icon/height_hide.jpg');
			$('#detail_searchForm').show();
		},
		function() {
			$(this).attr('src', '../../_admin/_images/icon/height_show.jpg');
			$('#detail_searchForm').hide();
		}
	).css('cursor', 'pointer');
});

</SCRIPT>

<div id="viewContent">
	<h1 class="frame-title">타사이전 통계 <span>타사이전 통계를 관리합니다.</span></h1>
			<form name="frmMain" action="<?=$_SERVER['PHP_SELF']?>" method="get">
				<div class="dataTablediv board-writeDiv" >
					<div class="roundBox" id="type1" >
						<p class="bul"> 전체 총 : <?=$pg->recode[total]?>명의 담당자가 등록되었습니다.</p>
						<div class="selectArea">
							<select name="selectType" style="width:100px;"   >
								<option value="all" <? if ($_GET['selectType'] == "all") echo "selected"; ?> >전체</option>
								<option value="grm_name" <? if ($_GET['selectType'] == "grm_name") echo "selected"; ?> >이름</option>
								<option value="grm_id" <? if ($_GET['selectType'] == "grm_id") echo "selected"; ?> >아이디</option>
							</select>
							<span></span>
							<input type="text" name="selectValue" id="SelValue1" value="<?=$selectValue?>">
								<div class="btn" style="margin-left:-222px;margin-top:4px;">
									<a href="javascript:_Fselect();"><img src="/images/btn/search.gif" alt="검색" valign="absmiddle"/></a>
								</div>
							<div id="detail_searchForm">
								<p class="codesearchbox">권한 검색 : <div class="searchlist"><?php $authCode->setCodeCheckBox('y');?></div></p>
								<p class="codesearchbox">부서 검색 : <div class="searchlist"><?php $divisionCode->setCodeCheckBox('y');?></div></p>
							</div>
						</div>
						<img src="../../_admin/_images/icon/height_show.jpg" alt="상세 검색 활성화" id="detail_search" />
					</div>
				</div>
			</form>

 						<div class="dataTablediv">
						<table class="board-list" summary="관리 table">
						<caption>관리</caption>
						<colgroup>
							<col width="80">
							<col width="100">
							<col width="140">
							<col width="100">
							<col width="140">
							<col width="140">
						</colgroup>
						<thead>
						<tr>
							<th scope="col" class="noLine"><span>번호</span></th>
							<th scope="col"><span>부서</span></th>
							<th scope="col"><span>아이디</span></th>
							<th scope="col"><span>이름</span></th>
							<th scope="col"><span>등록일</span></th>
						</tr>
						</thead>
						<tbody>
						<? 
						while ($data = $db->fetch($resList)){
						### 부서명 추출
						$query = "select md_name as division from " . GD_MANUAL_DIVISION . " where md_code = '$data[rd_code]'";
						list($division) = $db->fetch($query);
						?>
						<tr height="25" >
							<td align="center"><?=$pg->idx--;?></td>
							<td ><?=$division?></td>
							<td ><a href="javascript:LoadPageLink('<?=$data['grm_index']?>')" hidefocus><?=$data['grm_id']?></a></td>
							<td ><?=$data['grm_name']?></td>
							<td ><?=$data['grm_writedate']?></td>
						</tr>
						<? } 
							if(!$pg->page['navi']){
						?>
							<td colspan="6" class="nodata"> 검색된 내용이 없습니다.</td>
						<? } ?>
						</tbody>
						</table>
						</div>
						<!-- paging -->
						<div class="pageArea">
						<div class="hiddenObj">페이지 리스트</div>
							<div class="pageList">
								<?=$pg->page['navi']?>
							</div>
						</div>
<?
	include ('../../_admin/_include/sysbottom.php');
?>