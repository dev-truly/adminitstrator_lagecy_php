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
	$arraySolutionUseType = trimGetRequest('solutionUseType'); //----------- 솔루션 사용 조건
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
	$getStr = getArrayStr($getStr, 'solutionUseType[]', $arraySolutionUseType);
	
	include '../../_inc/code.class.php';
	$solution = class_load('solution');
	if ($xUidx) {
		$proc = 'MODIFY';
		$info			= "수정";
		/* ------------------------------------------------
		*	- 도움말 - 검색 문자 초기화
		*  ------------------------------------------------
		*/
		$dataString = class_load('string');
		$dataString->addItem('s_index', $xUidx, 'I');
		$selectWhere = $dataString->getWhere();

		/* ------------------------------------------------
		*	- 도움말 - 검색
		*  ------------------------------------------------
		*/
		$selectQuery = "Select * From " . GD_SOLUTION . $selectWhere . ";";
		$dataView = $db->fetch($db->query($selectQuery));
		
		/* ------------------------------------------------
		*	- 도움말 - 데이터 변수에 대입
		*  ------------------------------------------------
		*/
		$sIndex			= $dataView['s_index'];
		$sCode			= $dataView['s_code'];
		$sName			= $dataView['s_name'];
		$sIp			= $dataView['s_ip'];
		$sWriteDate		= $dataView['s_write_date'];
		
		$arrayUseType = array();
		foreach ($solution->arrayUseFieldName as $useType => $useFieldName) {
			if ($dataView[$useFieldName] == 'y') {
				$arrayUseType[] = $useType;
			}
		}
	}
	else {
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
	var form = document.frmMain;
	form.proc.value = "DELETE";
	var form = document.frmMain;
	doForm(form);

}
function _Fselect()
{
	LoadLinkStr("54","<?=$getStr?>");
}

function _Finsert()
{
	LoadLinkStr("55","<?=$getStr?>");
}

//-->
</SCRIPT>

		<form name="frmMain" method="post" 	action="./_proc.php">
		<?=getStrFromReqValHid($getStr)?>
		<input type="hidden" name="s_index" value="<?=$sIndex?>">
			<?php include ('../../_admin/_include/view_title.php');?>
									<caption>솔루션 요약</caption>
									<colgroup>
										<col width="15%" />
										<col width="auto" />
									</colgroup>
									<thead>
									<tr class="bg">
										<th scope="row">솔루션코드</th>
										<td scope="row">
											<?php if (!$xUidx) { ?>
												<input type="text" name="s_code"  style="width:50px;"   value="<?=$sCode?>" IsValidStr="STR" MinL="2" MaxL="10" NNull="true" LabelStr="솔루션 코드" />
											<?php } 
											else { ?>
												<?=$sCode?>
											<?php } ?>
										</td>
									</tr>
									<tr class="nobgc">
										<th scope="row">솔루션명</th>
										<td scope="row"><input type="text" name="s_name" style="width:200px;" value="<?=$sName?>" IsValidStr="STR" MinL="2" MaxL="15" NNull="true" LabelStr="솔루션 명" /></td>
									</tr>
									<tr class="bgc">
										<th scope="row">사용 여부</th>
										<td scope="row">
											<?php $solution->setSolutionTypeCheckBox($arrayUseType)?>
										</td>
									</tr>
									<?php
										if ($xUidx) {
									?>
									<tr class="nobgc">
										<th scope="row">등록, 수정 IP</th>
										<td scope="row">
											<?=$sIp?>
										</td>
									</tr>
									<tr class="bgb">
										<th scope="row">등록일</th>
										<td scope="row"><?=$sWriteDate?></td>
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