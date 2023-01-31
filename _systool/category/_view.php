<?php	
	include ('../../_admin/_include/systop.php');

	/* ------------------------------------------------
	*	- 도움말 - 넘어오는 값
	*  ------------------------------------------------
	*/

	$xUidx			= $_GET['xUidx'];				//------------ 일련번호

	$listCnt		= $_GET['listCnt'];				//------------ 출력되는 리스트 수
	$page			= $_GET['page'];				//------------ 현재 페이지

	$selectType		= $_GET['selectType'];			//------------ 검색 조건
	$selectValue	= $_GET['selectValue'];			//------------ 검색값
	$sort			= $_GET['sort'];				//------------ 검색값
    
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

	if ($xUidx) {
		$proc = 'MODIFY';
		/* ------------------------------------------------
		*	- 도움말 - 검색 문자 초기화
		*  ------------------------------------------------
		*/
		//$dataString = class_load('string');
		//$dataString->addItem('mc_index', $xUIdx, 'C');
		$selectWhere = 'Where mc_index = '. $xUidx;

		$selectQuery = "Select * From " . GD_MANUAL_CATEGORY ." ". $selectWhere;
		$dataView = $db->fetch($db->query($selectQuery));
		
		$mc_index		= $dataView['mc_index'];
		$mc_code		= $dataView['mc_code'];
		$mc_name		= $dataView['mc_name'];
		$mc_ip			= $dataView['mc_ip'];
		$mc_writer		= $dataView['mc_writer'];
		$mc_writedate	= $dataView['mc_writedate'];
		$mc_deleteFl	= $dataView['mc_deleteFl'];
	}
	else {
		$proc = 'WRITE';
		$mc_deleteFl	= "n";
	}

?>
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
	LoadLinkStr("15","<?=$getStr?>");
}

function _Finsert()
{
	LoadLinkStr("18","<?=$getStr?>");
}
//-->
</SCRIPT>

						<!-- view area -->
						<div class="viewArea" >
							<!-- bbsView -->
							<div class="bbsView">
								<div class="tableView">
									<table class="basic" style="_margin-left:250px;">
									<caption>카테고리 정보</caption>
									<colgroup>
										<col width="300px" />
										<col width="2px" />
										<col width="auto" />
									</colgroup>
									<thead>
									<tr class="bg">
										<th>CATEGORY <?=$proc?></th>
									</tr>
									</thead>
									<body>
									<tr  >
										<td scope="row" valign="top">
											<table class="basic">
											<form name="frmMain" method="post" action="./_proc.php" >
											<input type="hidden" name="proc" value="<?=$proc?>" >
											<input type="hidden" name="mc_deleteFl" value="<?=$mc_deleteFl?>" >
											<input type="hidden" name="mc_index" value="<?=$mc_index?>" >
											<caption>정보 요약</caption>
											<colgroup>
												<col width="15%" />
												<col width="auto" />
											</colgroup>
											<thead>
											<tr class="bgc">
												<th scope="row">카테고리 코드</th>
												<td scope="row">
													<?php if (!$xUidx) { ?>
														<input type="text" name="mc_code"  style="width:50px;"   value="<?=$mc_code?>" IsValidStr="STR"  NNull="true" LabelStr="카테고리 코드" />
													<?php } 
													else { ?>
														<input type="hidden" name="mc_code"  style="width:50px;"   value="<?=$mc_code?>" IsValidStr="STR"  NNull="true" LabelStr="카테고리 코드" /><?=$mc_code?>
													<?php } ?>
													
												</td>
											</tr>
											<tr class="bgc">
												<th scope="row">카테고리명</th>
												<td scope="row"><input type="text" name="mc_name"  style="width:95%;" value="<?=$mc_name?>" IsValidStr="STR" MinL="2" MaxL="50" NNull="true" LabelStr="카테고리명"></td>
											</tr>
											<tr class="bgc">
												<th scope="row">삭제유무</th>
												<td scope="row"><?=( $mc_deleteFl == 'n' ) ? "보임" : '삭제'?> (삭제시 상단에 삭제버튼을 눌러주세요)</td>
											</tr>
											<?php
												if ($xUidx) {
											?>
											<tr class="bgb">
												<th scope="row">등록일</th>
												<td scope="row"><?=$mc_writedate?></td>
											</tr>
											<?php
												}
											?>
											</thead>
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