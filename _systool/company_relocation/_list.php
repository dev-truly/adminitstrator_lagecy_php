<?php	
	include ('../../_admin/_include/systop.php');

	/* ------------------------------------------------
	*	- 도움말 - 넘어오는 값
	*  ------------------------------------------------
	*/
	$listCnt			= trimGetRequest('listCnt');			//------------ 출력되는 리스트 수
	$page				= trimGetRequest('page');				//------------ 현재 페이지

	$selectType			= trimGetRequest('selectType');			//------------ 검색 조건
	$selectValue		= trimGetRequest('selectValue');		//------------ 검색값
	$arraySearchState	= trimGetRequest('searchState');		//------------ 이전 상태 검색
	$arraySearchOld		= trimGetRequest('searchOld', 'array');		//------------ 이전 전 솔루션
	$arraySearchGodo	= trimGetRequest('searchGodo', 'array');	//------------ 이전 후 고도 솔루션
	$sort				= trimGetRequest('sort');				//------------ 검색값

	if (!$sort) {
		$sort = 'rm_index DESC';
	}

	/* ------------------------------------------------
	*	- 페이지 설정
	*  ------------------------------------------------
	*/
	if(!$listCnt) $listCnt = 10;
	if(!$page) $page = 1;

	$pg = class_load('page');
	$pg->Page($page,$listCnt);
	
	if (!$returnPage) {
		$returnPage = '_list';
	}
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
	$getStr = getStr($getStr,'returnPage', $returnPage);			// 리턴 페이지
	
	/* ------------------------------------------------
	*	- 도움말 - 소팅 선택시 넘어갈 GET값
	*  ------------------------------------------------
	*/
	$getStr2 ='';
	$getStr2 = getStr($getStr2,'listCnt',$listCnt);
	$getStr2 = getStr($getStr2,'page',$page);
	$getStr2 = getStr($getStr2,'selectType',$selectType);
	$getStr2 = getStr($getStr2,'selectValue',$selectValue);

	if(!empty($arraySearchOld)) {
		foreach ($arraySearchOld as $searchOld) {
			$getStr = getStr($getStr,'searchOld[]',$searchOld);
			$getStr2 = getStr($getStr2,'searchOld[]',$searchOld);
		}
	}

	if(!empty($arraySearchGodo)) {
		foreach ($arraySearchGodo as $searchGodo) {
			$getStr = getStr($getStr,'searchGodo[]',$searchGodo);
			$getStr2 = getStr($getStr2,'searchGodo[]',$searchGodo);
		}
	}
	if(!empty($arraySearchState)) {
		foreach ($arraySearchState as $searchState) {
			$getStr = getStr($getStr,'searchState[]',$searchState);
			$getStr2 = getStr($getStr2,'searchState[]',$searchState);
		}
	}

	// 검색 타입 배열 생성
	$arraySearchType = array(
		'rm_name'			=> '쇼핑몰명',
		'rm_writename'		=> '등록 담당자',
		'rm_godo_id'		=> '고도 아이디',
		'rm_admin_name'		=> '담당자 이름',
		'rm_default_tel'	=> '주연락처',
		'rm_sub_tel'		=> '보조연락처',
		'rm_admin_email'	=> '담당자 메일 주소',
		'rm_godo_domain'	=> '고도 도메인',
		'rm_before_site_url'	=> '이전 전 도메인',
	);


	/* ------------------------------------------------
	*	- 도움말 - 검색 절 생성
	*  ------------------------------------------------
	*/

	$selectWhere = array();
	if($selectType && $selectValue){
		if ($selectType == 'alldata') {
			$arrayWhere = array();
			foreach ($arraySearchType as $stringFieldName => $stringFieldValue) {
				$arrayWhere[] = $stringFieldName . " LIKE '%" . $_GET['selectValue'] . "%'";
			} // end foreach
			$selectWhere[] = " (" . implode(" OR ", $arrayWhere) . ") ";
		}
		else {
			$selectWhere[] = $selectType . " like '%" . $selectValue . "%'";
		}
	}

	if(!empty($arraySearchOld)) {
		$arrayWhere = array();
		foreach ($arraySearchOld as $searchOld) {
			$arrayWhere[] = "'" . $searchOld . "'";
		}
		$selectWhere[] = "rm_before_solution_code in (" . implode(', ', $arrayWhere) . ")";
	}

	if(!empty($arraySearchGodo)) {
		$arrayWhere = array();
		foreach ($arraySearchGodo as $searchGodo) {
			$arrayWhere[] = "'" . $searchGodo . "'";
		}
		$selectWhere[] = "rm_godo_solution_code in (" . implode(', ', $arrayWhere) . ")";
	}

	if(!empty($arraySearchState)) {
		$arrayWhere = array();
		foreach ($arraySearchState as $searchState) {
			$arrayWhere[] = "'" . $searchState . "'";
		}
		$selectWhere[] = "rm_state in (" . implode(', ', $arrayWhere) . ")";
	}

	$selectWhere[] = "rm_deleteFl = 'n'";

	//-------------------------------------------
	//- Advice - 리스트 검색
	//-------------------------------------------
	$pg -> field = "rm_index, rm_godo_id, rm_name, rm_state,  rm_before_solution_code, rm_before_etc_solution_name, rm_writename, rm_writedate";
	$pg->setQuery($db_table= " " . GD_RELOCATION_MALL . " " , $selectWhere, $sort);
	$pg->exec();

	$resList = $db->query($pg->query);

	$code			= class_load('code');
	
	$godoSolution	= class_load('solution');
	$oldSolution	= class_load('solution');
	
?>

<script language="javascript">
function LoadPageLink(idx)
{	
	LoadLinkStr("23","xUidx="+idx+ "&<?=$getStr?>")
}

function _Finsert()
{
	LoadLinkStr("23","<?=$getStr?>");
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
									<select name="selectType">
										<?php
											foreach ($arraySearchType as $searchKey => $searchValue) {
												$selected = ($searchKey == $selectType) ? 'selected' : '';
												
										?>
											<option value="<?=$searchKey?>" <?=$selected?>><?=$searchValue?></option>
										<?php	
											}
										?>
										<option value="alldata" <?=($selectType == 'alldata') ? 'selected' : ''?>>전체</option>
									</select>
									<span></span>
									<input type="text" name="selectValue" id="SelValue1" value="<?=$selectValue?>">
									<div class="btn" style="margin-left:-222px;margin-top:4px;">
										<a href="javascript:_Fselect();"><img src="/images/btn/search.gif" alt="검색" valign="absmiddle"/></a>
									</div>
									<div id="detail_searchForm">
										<p class="codetypesearchbox">
											이전 전 솔루션 : 
											<?php 
												if (!empty($arraySearchOld)) {
													$codeData = implode('|', $arraySearchOld);
												}
												$oldSolution->setWhere($codeData, array('RB'));
												$oldSolution->getSolutionListCheckBox('searchOld');
											?>
										</p>
										<p class="codetypesearchbox" style="margin-top:5px;padding-top:2px;border-top:solid 1px #ffffff;clear:both;">
										고도 솔루션 : 
											<?php
												if (!empty($arraySearchGodo)) {
													$codeData = implode('|', $arraySearchGodo);
												}
												$godoSolution->setWhere($codeData, array('RA'));
												$godoSolution->getSolutionListCheckBox('searchGodo');
											?>
										</p>
										<p class="codetypesearchbox" style="margin-top:5px;padding-top:2px;border-top:solid 1px #ffffff;clear:both;">
										이전 상태 : 
											<?php
												if (!empty($arraySearchState)) {
													$codeData = implode('|', $arraySearchState);
												}
												$code->setCodeTableType($codeData, 'state');
												$code->setCodeCheckBox('searchState');
											?>
										</p>
									</div>
									
								</div>
							<img src="../../_admin/_images/icon/height_show.jpg" alt="상세 검색 활성화" id="detail_search" />
						</div>
					</div>
				</form>

 						<div class="dataTablediv">
						<table class="board-list" summary="타사 이전 관리 table">
						<caption>타사 이전 관리</caption>
						<colgroup>
							<col width="80">
							<col width="150">
							<col width="300">
							<col width="150">
							<col width="100">
							<col width="150">
							<col width="100">
						</colgroup>
						<thead>
						<tr>
							<th scope="col" class="noLine"><span>번호</span></th>
							<th scope="col"><span>회원아이디</span></th>
							<th scope="col"><span>쇼핑몰명</span></th>
							<th scope="col"><span>이전 솔루션</span></th>
							<th scope="col"><span>진행상태</span></th>
							<th scope="col"><span>등록담당자</span></th>
							<th scope="col"><span>등록일</span></th>
						</tr>
						</thead>
						<tbody>
						<?while ($data = $db->fetch($resList)){?>
						<tr height="25" >
							<td align="center"><?=$pg->idx--;?></td>
							<td align="center"><?=$data['rm_godo_id']?></td>
							<td !class="txt_left">
								<DIV class="ell"  >	<NOBR><a href="javascript:LoadPageLink('<?=$data['rm_index']?>')" hidefocus><?=$data['rm_name']?></a></NOBR></DIV>
							</td>
							<td>
								<?=($data['rm_before_solution_code'] == 'ETC') ? $data['rm_before_etc_solution_name'] : $oldSolution->arrayCodeTypeRow[$data['rm_before_solution_code']]?>
							</td>
							<td>
								<?=$code->arrayCodeTypeRow[$data['rm_state']];?>
							</td>
							<td>
								<?=$data['rm_writename']?>
							</td>
							<td ><?=date('Y.m.d', strtotime($data['rm_writedate']))?></td>
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