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
	$sort			= trimGetRequest('sort');				//------------ 검색값
    
	if (!$sort) {
		$sort = 'p_sort';
	}

	$arrayField = array('p_code', 'p_name'); // 전체 검색 툴
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

	/* ------------------------------------------------
	*	- 도움말 - 검색 절 생성
	*  ------------------------------------------------
	*/
	$selectWhere = array();
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
	$selectWhere[] = "p_delete_flag = 'n'";
	
	//-------------------------------------------
	//- Advice - 리스트 검색
	//-------------------------------------------
	$pg -> field = "*";
	$pg->setQuery($db_table= " " . GD_POSITION . " " , $selectWhere, $sort);
	$pg->exec();

	$resList = $db->query($pg->query);
?>

<script language="javascript">
function LoadPageLink(idx)
{	
	LoadLinkStr("14","xUidx="+idx+ "&<?=$getStr?>")
}

function _Finsert()
{
	LoadLinkStr("14","<?=$getStr?>");
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
									<option value="p_code" <? if ($_GET['selectType'] == "p_code") echo "selected"; ?> >직급 코드</option>
									<option value="p_name" <? if ($_GET['selectType'] == "p_name") echo "selected"; ?> >직급명</option>
									<option value="all" <? if ($_GET['selectType'] == "all") echo "selected"; ?> >전체</option>
								</select>
								<span></span>
								<input type="text" name="selectValue" id="SelValue1" value="<?=$selectValue?>">
									<div class="btn" style="margin-left:-222px;margin-top:4px;">
										<a href="javascript:_Fselect();"><img src="/images/btn/search.gif" alt="검색" valign="absmiddle"/></a>
									</div>
							</div>
						</div>
					</div>
				</form>
					<div class="dataTablediv">
					<table class="board-list" summary="직급 리스트 table">
					<caption>직급 리스트</caption>
					<colgroup>
						<col width="80">
						<col width="150">
						<col width="500">
						<col width="50">
						<col width="150">
					</colgroup>
					<thead>
					<tr>
						<th scope="col" class="noLine"><span>번호</span></th>
						<th scope="col"><span>직급 코드</span></th>
						<th scope="col"><span>직급명</span></th>
						<th scope="col"><span>정렬순서</span></th>
						<th scope="col"><span>등록일</span></th>
					</tr>
					</thead>
					<tbody>
					<?while ($data = $db->fetch($resList)){?>
					<tr height="25" >
						<td align="center"><?=$pg->idx--;?></td>
						<td align="center"><?=$data['p_code']?></td>
						<td !class="txt_left">
							<DIV class="ell"  >	<NOBR><a href="javascript:LoadPageLink('<?=$data['p_index']?>')" hidefocus><?=$data['p_name']?></a></NOBR></DIV>
						</td>
						<td align="center"><?=$data['p_sort']?></td>
						<td ><?=date('Y.m.d', strtotime($data['p_write_date']))?></td>
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