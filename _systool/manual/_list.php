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
	$sort			= $_GET['sort'];				//------------ 검색값

	if (!$sort) $sort = 'm_index DESC';
	
	if (!empty($_GET['ms_codesearch']))		$msCodeSearch	= implode('|', $_GET['ms_codesearch']);		//------------ 솔루션 검색 조건
	if (!empty($_GET['mc_codesearch']))		$mcCodeSearch	= implode('|', $_GET['mc_codesearch']);		//------------ 카테고리 검색 조건
	if (!empty($_GET['md_codesearch']))		$mdCodeSearch	= implode('|', $_GET['md_codesearch']);		//------------ 부서 검색 조건

	$arrayField = array('m_subject', 'm_contents', 'm_name'); // 전체 검색 툴
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

	// 솔루션 검색 데이터 존재시 Get 데이터로 넘길값 생성
	if (!empty($_GET['ms_codesearch'])) {
		foreach ($_GET['ms_codesearch'] as $mamCodeSearchValue) {
			$getStr = getStr($getStr,'ms_codesearch[]',$mamCodeSearchValue);
		}
	}
	
	// 카테고리 검색 데이터 존재시 Get 데이터로 넘길값 생성
	if (!empty($_GET['mc_codesearch'])) {
		foreach ($_GET['mc_codesearch'] as $mcCodeSearchValue) {
			$getStr = getStr($getStr,'mc_codesearch[]',$mcCodeSearchValue);
		}
	}
	
	// 부서 검색 데이터 존재시 Get 데이터로 넘길값 생성
	if (!empty($_GET['md_codesearch'])) {
		foreach ($_GET['md_codesearch'] as $mdCodeSearchValue) {
			$getStr = getStr($getStr,'md_codesearch[]',$mdCodeSearchValue);
		}
	}

	/* ------------------------------------------------
	*	- 도움말 - 검색 절 생성
	*  ------------------------------------------------
	*/

	$solutionCode = class_load('code');
	$solutionCode->setCode($msCodeSearch, 'solution');
	$categoryCode = class_load('code');
	$categoryCode->setCode($mcCodeSearch, 'category');
	$divisionCode = class_load('code');
	$divisionCode->setCode($mdCodeSearch, 'division');
	
	$solutionCodeSearch	= $solutionCode->getLike('m_solution_code', 'separator');
	$categoryCodeSearch = $categoryCode->getLike('m_category_code', 'separator');
	$divisionCodeSearch = $divisionCode->getLike('m_division_code', 'separator');

	$selectWhere = array();
	$likeWhere = array();
	if ($solutionCodeSearch) {
		$likeWhere[] = '(' . $solutionCodeSearch . ')';
	}
	if ($categoryCodeSearch) {
		$likeWhere[] = '(' . $categoryCodeSearch . ')';
	}
	if ($divisionCodeSearch) {
		$likeWhere[] = '(' . $divisionCodeSearch . ')';
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
			$selectWhere[] = $selectType . " like '%" . $selectValue . "%'";
		}
	}
	$selectWhere[] = "m_deleteFl = 'n'";
	
	//-------------------------------------------
	//- Advice - 리스트 검색
	//-------------------------------------------
	$pg -> field = "*";
	$pg->setQuery($db_table= " " . GD_MANUAL . " " , $selectWhere, $sort);
	$pg->exec();

	$resList = $db->query($pg->query);
?>

<script language="javascript">
function LoadPageLink(idx)
{	
	LoadLinkStr("22","xUidx="+idx+ "&<?=$getStr?>")
}

function _Finsert()
{
	LoadLinkStr("22","<?=$getStr?>");
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
</script>
	<div id="viewContent">
	<h1 class="frame-title"><?=$menuSubject?> <span>메뉴얼 관리합니다. </span></h1>
					<form name="frmMain" action="<?=$_SERVER['PHP_SELF']?>" method="get">
						<div class="dataTablediv board-writeDiv">
							<div class="roundBox" id="type1">
								<p class="bul"> 전체 총 : 메뉴얼에 <?=$pg->recode[total]?>개의 메뉴얼이 등록 되어 있습니다.</p>
								<div class="selectArea">
									<select name="selectType" style="width:100px;"   >
										<option value="all" <? if ($_GET['selectType'] == "all") echo "selected"; ?> >전체</option>
										<option value="m_subject" <? if ($_GET['selectType'] == "m_subject") echo "selected"; ?> >제목</option>
										<option value="m_contents" <? if ($_GET['selectType'] == "m_contents") echo "selected"; ?> >내용</option>
										<option value="m_name" <? if ($_GET['selectType'] == "m_name") echo "selected"; ?> >작성자</option>
									</select>
									<span></span>
									<input type="text" name="selectValue" id="SelValue1" value="<?=$selectValue?>">
									<div class="btn" style="margin-left:-222px;margin-top:4px;">
										<a href="javascript:_Fselect();"><img src="/images/btn/search.gif" alt="검색" valign="absmiddle"/></a>
									</div>
									<div style="overflow:hidden;">
										<p class="codesearchbox">메뉴 검색 : <div class="searchlist"><?php $solutionCode->setCodeCheckBox('y');?></div></p>
										<p class="codesearchbox">카테고리 검색 : <div class="searchlist"><?php $categoryCode->setCodeCheckBox('y');?></div></p>
										<p class="codesearchbox">부서 검색 : <div class="searchlist"><?php $divisionCode->setCodeCheckBox('y');?></div></p>
									</div>
								</div>
							</div>
						</div>
					</form>

 						<div class="dataTablediv">
						<table class="board-list" summary="메뉴얼 관리 table">
						<caption>메뉴얼 관리</caption>
						<colgroup>
							<col width="80">
							<col width="auto">
							<col width="150">
							<col width="150">
						</colgroup>
						<thead>
						<tr>
							<th scope="col" class="noLine"><span>번호</span></th>
							<th scope="col"><span>메뉴얼 제목</span></th>
							<th scope="col"><span>메뉴얼 작성자</span></th>
							<th scope="col"><span>등록일</span></th>
						</tr>
						</thead>
						<tbody>
						<?while ($data = $db->fetch($resList)){?>
						<tr height="25" >
							<td align="center"><?=$pg->idx--;?></td>
							<td style="text-align:left;">
								<NOBR><a href="javascript:LoadPageLink('<?=$data['m_index']?>')" hidefocus><?=$data['m_subject']?></a></NOBR>
							</td>
							<td>
								<DIV class="ell"  ><NOBR><a href="javascript:LoadPageLink('<?=$data['m_index']?>')" hidefocus><?=$data['m_name']?></a></NOBR></DIV>
							</td>
							<td ><?=date('Y.m.d', strtotime($data['m_writedate']))?></td>
						</tr>
						<? } 
							if(!$pg->page['navi']){
						?>
							<td colspan="6" align="center" class="nodata"> 검색된 내용이 없습니다.</td>
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