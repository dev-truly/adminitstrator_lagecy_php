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
	$arraySolutionUseType = trimGetRequest('solutionUseType'); //----------- 솔루션 사용 조건
	$sort			= trimGetRequest('sort');				//------------ 검색값

	if (!$sort) $sort = 's_index desc';
    
	$arrayField = array('s_code', 's_name'); // 전체 검색 툴
	/* ------------------------------------------------
	*	- 페이지 설정
	*  ------------------------------------------------
	*/
	if(!$listCnt) $listCnt = 10;
	if(!$page) $page = 1;

	$pg = class_load('page');
	include '../../_inc/code.class.php';
	$solution = class_load('solution');
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

	
	/* ------------------------------------------------
	*	- 도움말 - 검색 절 생성
	*  ------------------------------------------------
	*/
	$selectWhere = array();
	if (!empty($arraySolutionUseType)) {
		$selectWhere[] = $solution->setWhere($arraySolutionUseType);
		foreach ($arraySolutionUseType as $solutionUseType) {
			$getStr = getStr($getStr,'solutionUseType[]', $solutionUseType); // get데이터로 넘길값 생성
			$getStr2 = getStr($getStr2,'solutionUseType[]',$solutionUseType); // 소팅 선택시 넘어갈 GET값
		}
	}
	
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
	$selectWhere[] = "s_delete_flag = 'n'";
	
	//-------------------------------------------
	//- Advice - 리스트 검색
	//-------------------------------------------
	$pg -> field = "*";
	$pg->setQuery($db_table= " " . GD_SOLUTION . " " , $selectWhere, $sort);
	$pg->exec();

	$resList = $db->query($pg->query);
?>

<script language="javascript">
function LoadPageLink(idx)
{	
	LoadLinkStr("55","xUidx="+idx+ "&<?=$getStr?>")
}

function _Finsert()
{
	LoadLinkStr("55","<?=$getStr?>");
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
										<option value="s_code" <? if ($_GET['selectType'] == "s_code") echo "selected"; ?> >솔루션 코드</option>
										<option value="s_name" <? if ($_GET['selectType'] == "s_name") echo "selected"; ?> >솔루션</option>
										<option value="all" <? if ($_GET['selectType'] == "all") echo "selected"; ?> >전체</option>
									</select>
									<span></span>
									<input type="text" name="selectValue" id="SelValue1" value="<?=$selectValue?>">
									<div class="btn" style="margin-left:-222px;margin-top:4px;">
										<a href="javascript:_Fselect();"><img src="/images/btn/search.gif" alt="검색" valign="absmiddle"/></a>
									</div>
									<div id="detail_searchForm">
										<p class="codesearchbox">사용여부 검색 : 
											<div class="searchlist">
												<?php $solution->setSolutionTypeCheckBox($arraySolutionUseType, true)?>
											</div>
										</p>
									</div>
								</div>
								<img src="../../_admin/_images/icon/height_show.jpg" alt="상세 검색 활성화" id="detail_search" />
							</div>
						</div>
					</div>
				</form>

 						<div class="dataTablediv">
						<table class="board-list" summary="솔루션 관리 table">
						<caption>솔루션 관리</caption>
						<colgroup>
							<col width="80">
							<col width="150">
							<col width="200">
							<col width="150">
							<col width="150">
							<col width="150">
							<col width="150">
						</colgroup>
						<thead>
						<tr>
							<th scope="col" class="noLine"><span>번호</span></th>
							<th scope="col"><span>솔루션 코드</span></th>
							<th scope="col"><span>솔루션 명</span></th>
							<th scope="col"><span>타사이전(전/후)</span></th>
							<th scope="col"><span>자사이전(전/후)</span></th>
							<th scope="col"><span>기술지원(여부)</span></th>
							<th scope="col"><span>등록일</span></th>
						</tr>
						</thead>
						<tbody>
						<?while ($data = $db->fetch($resList)){?>
						<tr height="25" >
							<td align="center"><?=$pg->idx--;?></td>
							<td align="center"><?=$data['s_code']?></td>
							<td !class="txt_left">
								<DIV class="ell"  >	<NOBR><a href="javascript:LoadPageLink('<?=$data['s_index']?>')" hidefocus><?=$data['s_name']?></a></NOBR></DIV>
							</td>
							<td ><?=($data['s_relocation_before'] == 'y') ? '<font style="font-weight:bold;color:blue;">사용</font>' : '<font style="color:red;">미사용</font>'?> / <?=($data['s_relocation_after'] == 'y') ? '<font style="font-weight:bold;color:blue;">사용</font>' : '<font style="color:red;">미사용</font>'?></td>
							<td ><?=($data['s_godo_before'] == 'y') ? '<font style="font-weight:bold;color:blue;">사용</font>' : '<font style="color:red;">미사용</font>'?> / <?=($data['s_godo_after'] == 'y') ? '<font style="font-weight:bold;color:blue;">사용</font>' : '<font style="color:red;">미사용</font>'?></td>
							<td ><?=($data['s_techsupport'] == 'y') ? '<font style="font-weight:bold;color:blue;">사용</font>' : '<font style="color:red;">미사용</font>'?></td>
							<td ><?=date('Y.m.d', strtotime($data['s_write_date']))?></td>
						</tr>
						<? } 
							if(!$pg->page['navi']){
						?>
							<td colspan="7" align="center" class="nodata"> 검색된 내용이 없습니다.</td>
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