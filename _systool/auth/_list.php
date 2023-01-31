<?php	
	
	include ('../../_admin/_include/systop.php');

	/* ------------------------------------------------
	*	- 도움말 - 넘어오는 값
	*  ------------------------------------------------
	*/
	$listCnt		= trimGetRequest('listCnt');				//------------ 출력되는 리스트 수
	$page			= trimGetRequest('page');				//------------ 현재 페이지

	$selectType		= trimGetRequest('selectType');			//------------ 검색 조건
	$selectValue	= trimGetRequest('selectValue');			//------------ 검색값

	if (!empty($_GET['m_codesearch']))	$mCodeSearch	= trimGetRequest('m_codesearch');	//------------ 메뉴 검색 조건
	if (!empty($_GET['d_codesearch']))	$dCodeSearch	= trimGetRequest('d_codesearch');		//------------ 부서 검색 조건
	if (!empty($_GET['a_codesearch']))	$aCodeSearch	= trimGetRequest('a_codesearch');		//------------ 부서 검색 조건
    
	$arrayField = array('a_code', 'a_name'); // 전체 검색 툴
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
	$getStr = getArrayStr($getStr, 'm_codesearch[]', $mCodeSearch);
	$getStr = getArrayStr($getStr, 'd_codesearch[]', $dCodeSearch);
	$getStr = getArrayStr($getStr, 'a_codesearch[]', $aCodeSearch);

	/* ------------------------------------------------
	*	- 도움말 - 검색 절 생성
	*  ------------------------------------------------
	*/

	$menuCode		= class_load('code');
	$menuCode->setCode($mCodeSearch, 'menu');
	$divisionCode	= class_load('code');
	$divisionCode->setCode($dCodeSearch, 'division');
	$authCode		= class_load('code');
	$authCode->setCode($dCodeSearch, 'auth');
	
	$menuCodeSearch			= $menuCode->getLike('a_menu_code', 'separator');
	$divisionCodeSearch		= $divisionCode->getLike('d_code', 'separator');
	$authCodeSearch			= $authCode->getLike('a_code', 'separator');

	$selectWhere = array();
	$likeWhere = array();
	if ($menuCodeSearch) {
		$likeWhere[] = '(' . $menuCodeSearch . ')';
	}
	if ($divisionCodeSearch) {
		$likeWhere[] = '(' . $divisionCodeSearch . ')';
	}
	if ($authCodeSearch) {
		$likeWhere[] = '(' . $authCodeSearch . ')';
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
	$selectWhere[] = "a_delete_flag = 'n'";
	
	//-------------------------------------------
	//- Advice - 리스트 검색
	//-------------------------------------------
	$pg -> field = "*";
	$pg->setQuery($db_table= " " . GD_AUTH . " " , $selectWhere, $sort);
	$pg->exec();

	$resList = $db->query($pg->query);

?>

<script language="javascript">
function LoadPageLink(idx)
{	
	LoadLinkStr("10","xUidx="+idx+ "&<?=$getStr?>")
}

function _Finsert()
{
	LoadLinkStr("10","<?=$getStr?>");
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
	<?php include ('../../_admin/_include/list_title.php');?>
								<div class="selectArea">
									<select name="selectType" style="width:100px;"   >
										<option value="a_code" <? if ($_GET['selectType'] == "a_code") echo "selected"; ?> >권한 코드</option>
										<option value="a_name" <? if ($_GET['selectType'] == "a_name") echo "selected"; ?> >권한명</option>
										<option value="all" <? if ($_GET['selectType'] == "all") echo "selected"; ?> >전체</option>
									</select>
									<span></span>
									<input type="text" name="selectValue" id="SelValue1" value="<?=$selectValue?>">
									<div class="btn" style="margin-left:-222px;margin-top:4px;">
										<a href="javascript:_Fselect();"><img src="/images/btn/search.gif" alt="검색" valign="absmiddle"/></a>
									</div>
									<div id="detail_searchForm">
										<p class="codesearchbox">메뉴 검색 : <div class="searchlist"><?php $menuCode->setCodeCheckBox('m_codesearch');?></div></p>
										<p class="codesearchbox">부서 검색 : <div class="searchlist"><?php $divisionCode->setCodeCheckBox('d_codesearch');?></div></p>
										<p class="codesearchbox">사용 가능 권한 검색 : <div class="searchlist"><?php $divisionCode->setCodeCheckBox('a_codesearch');?></div></p>
									</div>
								</div>
								<img src="../../_admin/_images/icon/height_show.jpg" alt="상세 검색 활성화" id="detail_search" />
							</div>
						</div>
					</form>

 					<div class="dataTablediv">
						<table class="board-list" summary="권한 관리 table">
						<caption>권한 관리</caption>
						<colgroup>
							<col width="80">
							<col width="150">
							<col width="auto">
							<col width="150">
						</colgroup>
						<thead>
						<tr>
							<th scope="col" class="noLine"><span>번호</span></th>
							<th scope="col"><span>권한 코드</span></th>
							<th scope="col"><span>권한명</span></th>
							<th scope="col"><span>등록일</span></th>
						</tr>
						</thead>
						<tbody>
						<?while ($data = $db->fetch($resList)){?>
						<tr height="25" >
							<td align="center"><?=$pg->idx--;?></td>
							<td align="center"><?=$data['a_code']?></td>
							<td !class="txt_left">
								<DIV class="ell"  >	<NOBR><a href="javascript:LoadPageLink('<?=$data['a_index']?>')" hidefocus><?=$data['a_name']?></a></NOBR></DIV>
							</td>
							<td ><?=date('Y.m.d', strtotime($data['a_write_date']))?></td>
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