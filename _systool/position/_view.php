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
		$dataString->addItem('p_index', $xUidx, 'I');
		$selectWhere = $dataString->getWhere();

		/* ------------------------------------------------
		*	- 도움말 - 검색
		*  ------------------------------------------------
		*/
		$selectQuery = "Select * From " . GD_POSITION . $selectWhere . ";";
		$dataView = $db->fetch($db->query($selectQuery));
		
		/* ------------------------------------------------
		*	- 도움말 - 데이터 변수에 대입
		*  ------------------------------------------------
		*/
		$pIndex			= $dataView['p_index'];
		$pCode			= $dataView['p_code'];
		$pName			= $dataView['p_name'];
		$pSort			= $dataView['p_sort'];
		$pIp			= $dataView['p_ip'];
		$pWriteDate		= $dataView['p_write_date'];
	}
	else {
		$proc	= 'WRITE';
		$info	= "등록";
	}

	$code = class_load('code');
	$code->setCode($ctCode, 'codeType');

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
	LoadLinkStr("13","<?=$getStr?>");
}

function _Finsert()
{
	LoadLinkStr("14","<?=$getStr?>");
}

//-->
</SCRIPT>

		<form name="frmMain" method="post" 	action="./_proc.php">
			<?php include ('../../_admin/_include/view_title.php');?>
									<colgroup>
										<col width="15%" />
										<col width="auto" />
									</colgroup>
									<thead>
									<tr class="bg">
										<th scope="row">직급 코드</th>
										<td scope="row">
											<?php if (!$xUidx) { ?>
											<input type="text" name="p_code"  style="width:50px;" value="<?=$pCode?>" IsValidStr="STR" MinL="1" MaxL="10" NNull="true" LabelStr="코드" />
											<?php } 
											else { ?>
												<?=$pCode?>
											<?php } ?>
										</td>
									</tr>
									<tr class="nobgc">
										<th scope="row">직급명</th>
										<td scope="row"><input type="text" name="p_name" style="width:200px;" value="<?=$pName?>" IsValidStr="STR"  NNull="true" MinL="2" MaxL="10" LabelStr="코드명" /></td>
									</tr>
									<tr class="bgc">
										<th scope="row">순서</th>
										<td scope="row"><input type="text" name="p_sort" style="width:20px;" value="<?=$pSort?>" IsValidStr="NUM"  NNull="true" MinL="1" MaxL="2" LabelStr="순서" /></td>
									</tr>
									<?php
										if ($xUidx) {
									?>
									<tr class="bgc">
										<th scope="row">등록IP</th>
										<td scope="row"><?=$pIp?></td>
									</tr>
									<tr class="nobgb">
										<th scope="row">등록일</th>
										<td scope="row"><?=$pWriteDate?></td>
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