<?php	
	include ('../../_admin/_include/systop.php');

	/* ------------------------------------------------
	*	- 도움말 - 넘어오는 값
	*  ------------------------------------------------
	*/

	$xUidx			= trimGetRequest('xUidx');				//------------ 일련번호

	$listCnt		= trimGetRequest('listCnt');				//------------ 출력되는 리스트 수
	$page			= trimGetRequest('page');				//------------ 현재 페이지

	$selectType		= trimGetRequest('selectType');			//------------ 검색 조건
	$selectValue	= trimGetRequest('selectValue');			//------------ 검색값
	$sort			= trimGetRequest('sort');				//------------ 검색값
    
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
		$info			= "수정";
		/* ------------------------------------------------
		*	- 도움말 - 검색 문자 초기화
		*  ------------------------------------------------
		*/
		$dataString = class_load('string');
		$dataString->addItem('ct_index', $xUidx, 'I');
		$selectWhere = $dataString->getWhere();

		/* ------------------------------------------------
		*	- 도움말 - 검색
		*  ------------------------------------------------
		*/
		$selectQuery = "Select * From " . GD_CODE_TYPE . $selectWhere . ";";
		$dataView = $db->fetch($db->query($selectQuery));
		
		/* ------------------------------------------------
		*	- 도움말 - 데이터 변수에 대입
		*  ------------------------------------------------
		*/
		$ctCode			= $dataView['ct_code'];
		$ctName			= $dataView['ct_name'];
		$ctSort			= $dataView['ct_sort'];
		$ctIp			= $dataView['ct_ip'];
		$ctWriteDate	= $dataView['ct_write_date'];

	} else {
		$proc	= 'WRITE';
		$info	= "등록";
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
	if (confirm('데이터를 정말 삭제 하시겠습니까?')) {
		var form = document.frmMain;
		form.proc.value = "DELETE";
		var form = document.frmMain;
		doForm(form);
	}
}
function _Fselect()
{
	LoadLinkStr("4","<?=$getStr?>");
}

function _Finsert()
{
	LoadLinkStr("5","<?=$getStr?>");
}

//-->
</SCRIPT>

		<form name="frmMain" method="post" 	action="./_type_proc.php">
			<?php include ('../../_admin/_include/view_title.php');?>
									<colgroup>
										<col width="15%" />
										<col width="auto" />
									</colgroup>
									<thead>
									<tr class="bg">
										<th scope="row">분류 코드</th>
										<td scope="row">
										<?php if (!$xUidx) { ?>
											<input type="text" name="ct_code"  style="width:50px;"   value="<?=$ctCode?>" IsValidStr="STR" MinL="1" MaxL="10" NNull="true" LabelStr="분류 코드" />
										<?php } 
										else { ?>
											<?=$ctCode?>
										<?php } ?>
										</td>
									</tr>
									<tr class="nobgc">
										<th scope="row">타입명</th>
										<td scope="row"><input type="text" name="ct_name" style="width:200px;" value="<?=$ctName?>" IsValidStr="STR" MinL="2" MaxL="15" NNull="true" LabelStr="타입명" /></td>
									</tr>

									<tr class="bgc">
										<th>정렬 순서</th>
										<td><input type="text" name="ct_sort" style="width:20px;" value="<?=$ctSort?>" IsValidStr="NUM" MinL="1" MaxL="2" NNull="true" LabelStr="정렬 순서" /></td>
									</tr>
									<?php
										if ($xUidx) {
									?>
									<tr class="nobgc">
										<th scope="row">등록, 수정IP</th>
										<td scope="row"><?=$ctIp?></td>
									</tr>
									<tr class="bgb">
										<th scope="row">등록일</th>
										<td scope="row"><?=$ctWriteDate?></td>
									</tr>
									<?php
										}
									?>
									</thead>
									</table>
								</div>
							</div>
							<!-- //bbsView -->
						</div>

			</form>

<?
	include ('../../_admin/_include/sysbottom.php');
?>