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
	$selectValue	= trimGetRequest('selectValue');		//------------ 검색값
	$sort			= trimGetRequest('sort');				//------------ 검색값
	
	if (!empty($_GET['ct_codesearch']))	$ctCodeSearch	= trimGetRequest('ct_codesearch');

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

	// 코드 타입 검색 데이터 존재시 Get 데이터로 넘길값 생성
	$getStr = getArrayStr($getStr, 'ct_codesearch[]', $ctCodeSearch);

	if ($xUidx) {
		$proc = 'MODIFY';
		$info			= "수정";
		/* ------------------------------------------------
		*	- 도움말 - 검색 문자 초기화
		*  ------------------------------------------------
		*/
		$dataString = class_load('string');
		$dataString->addItem('c_index', $xUidx, 'I');
		$selectWhere = $dataString->getWhere();

		/* ------------------------------------------------
		*	- 도움말 - 검색
		*  ------------------------------------------------
		*/
		$selectQuery = "Select * From " . GD_CODE . $selectWhere . ";";
		$dataView = $db->fetch($db->query($selectQuery));
		
		/* ------------------------------------------------
		*	- 도움말 - 데이터 변수에 대입
		*  ------------------------------------------------
		*/
		$cIndex			= $dataView['c_index'];
		$ctCode			= $dataView['ct_code'];
		$cCode			= $dataView['c_code'];
		$cName			= $dataView['c_name'];
		$cSort			= $dataView['c_sort'];
		$cIp			= $dataView['c_ip'];
		$cWriteDate		= $dataView['c_write_date'];
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
	LoadLinkStr("6","<?=$getStr?>");
}

function _Finsert()
{
	LoadLinkStr("7","<?=$getStr?>");
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
										<th scope="row">코드 타입</th>
										<td scope="row">
											<?$code->setCodeSelectBox('ct_code')?>
										</td>
									</tr>
									<tr class="nobgc">
										<th scope="row">코드</th>
										<td scope="row">
											<?php if (!$xUidx) { ?>
											<input type="text" name="c_code"  style="width:50px;" value="<?=$cCode?>" IsValidStr="STR" MinL="1" MaxL="10" NNull="true" LabelStr="코드" />
											<?php } 
											else { ?>
												<?=$cCode?>
											<?php } ?>
										</td>
									</tr>
									<tr class="bgc">
										<th scope="row">코드명</th>
										<td scope="row"><input type="text" name="c_name" style="width:200px;" value="<?=$cName?>" IsValidStr="STR"  NNull="true" MinL="2" MaxL="10" LabelStr="코드명" /></td>
									</tr>
									<tr class="nobgc">
										<th scope="row">순서</th>
										<td scope="row"><input type="text" name="c_sort" style="width:20px;" value="<?=$cSort?>" IsValidStr="NUM"  NNull="true" MinL="1" MaxL="2" LabelStr="순서" /></td>
									</tr>
									<?php
										if ($xUidx) {
									?>
									<tr class="nobgc">
										<th scope="row">등록IP</th>
										<td scope="row"><?=$cIp?></td>
									</tr>
									<tr class="bgb">
										<th scope="row">등록일</th>
										<td scope="row"><?=$cWriteDate?></td>
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