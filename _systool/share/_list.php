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
	
	$sort			= trimGetRequest('sort');				//------------ 검색값

	if (!$sort) $sort = "rs_index desc";

	$arraySearchType = array(
		'rs_title'			=> '제목',
		'rs_contents'		=> '공유 내용',
		'rs_name'			=> '등록 담당자',
	); // 전체 검색 툴
    
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
		if ($selectType == 'alldata') {
			$arrayWhere = array();
			foreach ($arraySearchType as $stringFieldName => $stringFieldValue) {
				$arrayWhere[] = $stringFieldName . " LIKE '%" . $selectValue . "%'";
			} // end foreach
			$selectWhere[] = " (" . implode(" OR ", $arrayWhere) . ") ";
		}
		else {
			$selectWhere[] = $selectType . " like '%" . $selectValue . "%'";
		}
	}
	$selectWhere[] = "rs_deleteFl = 'n'";

	
//-------------------------------------------
	//- Advice - 리스트 검색
	//-------------------------------------------
	$pg -> field = "*";
	$pg->setQuery($db_table=GD_RELOCATION_SHARE,$selectWhere,$sort="rs_index desc");
	$pg->exec();


	$resList = $db->query($pg->query);
?>

<SCRIPT LANGUAGE="JavaScript">

function LoadPageLink(idx)
{	
	LoadLinkStr("36","xUidx="+idx+ "&<?=$getStr?>")
}

function _Finsert()
{
	LoadLinkStr("37","<?=$getStr?>");
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
									include ('../../_admin/_include/list_title.php');
							?>
									<div class="selectArea">
										<select name="selectType">
											<option value="alldata" <?=($selectType == 'alldata') ? 'selected' : ''?>>전체</option>
											<?php
												foreach ($arraySearchType as $searchKey => $searchValue) {
													$selected = ($searchKey == $selectType) ? 'selected' : '';
													
											?>
												<option value="<?=$searchKey?>" <?=$selected?>><?=$searchValue?></option>
											<?php	
												}
											?>
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
						<table class="board-list" summary="관리 table">
						<caption>관리</caption>
						<colgroup>
							<col width="80">
							<col width="auto">
							<col width="100">
							<col width="100">
							<col width="100">
						</colgroup>
						<thead>
						<tr>
							<th scope="col" class="noLine"><span>번호</span></th>
							<th scope="col"><span>제목</span></th>
							<th scope="col"><span>등록담당자</span></th>
							<th scope="col"><span>조회수</span></th>
							<th scope="col"><span>등록일</span></th>
						</tr>
						</thead>
						<tbody>
						<? 
						while ($data = $db->fetch($resList)){
						?>
						<tr height="25" >
							<td align="center"><?=$pg->idx--;?></td>
							<td style="text-align:left;"><a href="javascript:LoadPageLink('<?=$data['rs_index']?>')" style="text-align:left;"hidefocus><?=$data['rs_title']?></a></td>
							<td ><?=$data['rs_name']?></td>
							<td ><?=$data['rs_readcnt']?></td>
							<td ><?=date('Y.m.d', strtotime($data['rs_writedate']))?></td>
						</tr>
						<? } 
							if(!$pg->page['navi']){
						?>
							<td colspan="5" class="nodata"> 검색된 내용이 없습니다.</td>
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