<?php
	include ('../../_admin/_include/systop.php');

	/* ------------------------------------------------
	*	- 도움말 - 넘어오는 값
	*  ------------------------------------------------
	*/
	$listCnt		= trimGetRequest('listCnt');				//------------ 출력되는 리스트 수
	$page			= trimGetRequest('page');					//------------ 현재 페이지

	$selectType		= trimGetRequest('selectType');				//------------ 검색 조건
	$selectValue	= trimGetRequest('selectValue');			//------------ 검색값
	
	if (!empty($_GET['a_codesearch']))		$aCodeSearch	= trimGetRequest('a_codesearch');		//------------ 메뉴 검색 조건
	if (!empty($_GET['d_codesearch']))		$dCodeSearch	= trimGetRequest('d_codesearch');		//------------ 부서 검색 조건
	if (!empty($_GET['p_codesearch']))		$pCodeSearch	= trimGetRequest('p_codesearch');		//------------ 부서 검색 조건
	
	$sort			= trimGetRequest('sort');				//------------ 검색값

	$arrayField = array('ru_name', 'ru_id'); // 전체 검색 툴
    
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
	// 권한 검색 데이터 존재시 Get 데이터로 넘길값 생성
	$getStr = getArrayStr($getStr, 'a_codesearch[]', $aCodeSearch);
	// 부서 권한 검색 데이터 존재시 Get 데이터로 넘길값 생성
	$getStr = getArrayStr($getStr, 'd_codesearch[]', $dCodeSearch);
	// 직급 검색 데이터 존재시 Get 데이터로 넘길값 생성
	$getStr = getArrayStr($getStr, 'p_codesearch[]', $pCodeSearch);

	/* ------------------------------------------------
	*	- 도움말 - 소팅 선택시 넘어갈 GET값
	*  ------------------------------------------------
	*/
	$getStr2 ='';
	$getStr2 = getStr($getStr2,'listCnt',$listCnt);
	$getStr2 = getStr($getStr2,'selectType',$selectType);
	$getStr2 = getStr($getStr2,'selectValue',$selectValue);
	// 권한 검색 데이터 존재시 Get 데이터로 넘길값 생성
	$getStr2 = getArrayStr($getStr2, 'a_codesearch[]', $aCodeSearch);
	// 부서 권한 검색 데이터 존재시 Get 데이터로 넘길값 생성
	$getStr2 = getArrayStr($getStr2, 'd_codesearch[]', $dCodeSearch);
	// 직급 검색 데이터 존재시 Get 데이터로 넘길값 생성
	$getStr2 = getArrayStr($getStr2, 'p_codesearch[]', $pCodeSearch);

	/* ------------------------------------------------
	*	- 도움말 - 검색 절 생성
	*  ------------------------------------------------
	*/
	$authCode = class_load('code');
	$authCode->setCode($aCodeSearch, 'auth');
	$divisionCode = class_load('code');
	$divisionCode->setCode($dCodeSearch, 'division');
	$positionCode = class_load('code');
	$positionCode->setCode($pCodeSearch, 'position');

	$selectWhere = array();
	
	if (!empty($_GET['a_codesearch'])) {
		$selectWhere[] = 'a_code in (' . $authCode->getIn() . ')'; 
	}

	if (!empty($_GET['d_codesearch'])) {
		$selectWhere[] = 'd_code in (' . $divisionCode->getIn() . ')'; 
	}

	if (!empty($_GET['p_codesearch'])) {
		$selectWhere[] = 'p_code in (' . $positionCode->getIn() . ')';
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

	if (!ereg($menuRow['m_code'], $_SESSION['sess']['aMenuCode'])) {
		$selectWhere[] = "ru_index = '" . $_SESSION['sess']['ruIndex'] . "'";
	}
	
	if (ereg($menuRow['m_code'], $_SESSION['sess']['aMenuCode'])) {
		$arrayDivisionAuth = explode('|', $_SESSION['sess']['dCode']);
		$arrayWhere = array();
		foreach ($arrayDivisionAuth as $divisionAuth) {
			if ($divisionAuth) {
				$arrayWhere[] = "d_code = '" . $divisionAuth . "'";
			}
		}
		$selectWhere[] = " (" . implode(" OR ", $arrayWhere) . ") ";
	}

	$selectWhere[] = "ru_delete_flag = 'n'";
	
	if (!$sort) $sort = " ru_index desc";
	//-------------------------------------------
	//- Advice - 리스트 검색
	//-------------------------------------------
	$pg ->field = "ru_index, a_code, d_code, p_code, ru_name, ru_id, ru_write_date";
	$pg->setQuery($db_table= " " . GD_RELOCATION_USER . " " ,$selectWhere,$sort);
	$pg->exec();


	$resList = $db->query($pg->query);
?>

<SCRIPT LANGUAGE="JavaScript">

function LoadPageLink(idx)
{	
	LoadLinkStr("16","xUidx="+idx+ "&<?=$getStr?>")
}

function _Finsert()
{
	LoadLinkStr("16","<?=$getStr?>");
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


</SCRIPT>

<div id="viewContent">
	<?php 
		if (ereg($menuRow['m_code'], $_SESSION['sess']['aMenuCode'])) {
			include ('../../_admin/_include/list_title.php');
	?>
						<div class="selectArea">
							<select name="selectType" style="width:100px;"   >
								<option value="all" <? if ($_GET['selectType'] == "all") echo "selected"; ?> >전체</option>
								<option value="ru_name" <? if ($_GET['selectType'] == "ru_name") echo "selected"; ?> >이름</option>
								<option value="ru_id" <? if ($_GET['selectType'] == "ru_id") echo "selected"; ?> >아이디</option>
							</select>
							<span></span>
							<input type="text" name="selectValue" id="SelValue1" value="<?=$selectValue?>">
							<div class="btn" style="margin-left:-222px;margin-top:4px;">
								<a href="javascript:_Fselect();"><img src="/images/btn/search.gif" alt="검색" valign="absmiddle"/></a>
							</div>
							<div id="detail_searchForm">
								<!-- <p class="codesearchbox">권한 검색 : <div class="searchlist"><?php $authCode->setCodeCheckBox('a_codesearch');?></div></p> -->
								<p class="codesearchbox">부서 검색 : <div class="searchlist"><?php $divisionCode->setCodeCheckBox('d_codesearch');?></div></p>
								<p class="codesearchbox">직급 검색 : <div class="searchlist"><?php $positionCode->setCodeCheckBox('p_codesearch');?></div></p>
							</div>
						</div>

						<img src="../../_admin/_images/icon/height_show.jpg" alt="상세 검색 활성화" id="detail_search" />
					</div>
				</div>
			</form>
	<?php
		}
		else {
	?>
		<h1 class="frame-title" style="margin-bottom:30px;"><?=$menuSubject?> <span>본인 계정을 관리 합니다.</span></h1>
					
	<?php
		}
	?>
 						<div class="dataTablediv">
						<table class="board-list" summary="관리 table">
						<caption>관리</caption>
						<colgroup>
							<col width="80">
							<col width="100">
							<col width="100">
							<col width="100">
							<col width="auto">
							<col width="140">
							<col width="140">
						</colgroup>
						<thead>
						<tr>
							<th scope="col" class="noLine"><span>번호</span></th>
							<th scope="col"><span>부서</span></th>
							<th scope="col"><span>권한</span></th>
							<th scope="col"><span>직급</span></th>
							<th scope="col"><span>아이디</span></th>

							<th scope="col"><span>이름</span></th>
							<th scope="col"><span>등록일</span></th>
						</tr>
						</thead>
						<tbody>
						<? 
						while ($data = $db->fetch($resList)){
						?>
						<tr height="25" >
							<td align="center"><?=$pg->idx--;?></td>
							<td ><?=$divisionCode->arrayCodeTypeRow[$data['d_code']]?></td>
							<td ><?=$authCode->arrayCodeTypeRow[$data['a_code']]?></td>
							<td ><?=$positionCode->arrayCodeTypeRow[$data['p_code']]?></td>
							<td ><a href="javascript:LoadPageLink('<?=$data['ru_index']?>')" hidefocus><?=$data['ru_id']?></a></td>
							<td ><?=$data['ru_name']?></td>
							<td ><?=date('Y.m.d', strtotime($data['ru_write_date']))?></td>
						</tr>
						<? } 
							if(!$pg->page['navi']){
						?>
							<td colspan="7" class="nodata"> 검색된 내용이 없습니다.</td>
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